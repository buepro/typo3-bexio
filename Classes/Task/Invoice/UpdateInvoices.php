<?php

/*
 * This file is part of the composer package buepro/typo3-bexio.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Bexio\Task\Invoice;

use Bexio\Resource\Invoice as InvoiceResource;
use Buepro\Bexio\Domain\Model\Invoice;
use Buepro\Bexio\Domain\Repository\InvoiceRepository;
use Buepro\Bexio\Domain\Repository\UserRepository;
use Buepro\Bexio\Dto\InvoiceDto;
use Buepro\Bexio\Task\AbstractTask;
use Buepro\Bexio\Task\TaskInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

class UpdateInvoices extends AbstractTask implements TaskInterface
{
    public const DEFAULT_OPTIONS = [
        'from' => null,
        'include-paid' => false,
    ];
    protected array $options = self::DEFAULT_OPTIONS;
    protected array $userStorageUids = [];
    protected array $invoiceStorageUids = [];
    protected ?UserRepository $userRepository = null;
    protected ?InvoiceRepository $invoiceRepository = null;

    public function initialize(array $options = self::DEFAULT_OPTIONS): TaskInterface
    {
        $this->options = $options;
        if (
            (($userStorageUid = $this->site->getConfiguration()['bexio']['user']['storageUid'] ?? null) === null) ||
            (($invoiceStorageUid = $this->site->getConfiguration()['bexio']['invoice']['storageUid'] ?? null) === null) ||
            count($this->userStorageUids = GeneralUtility::intExplode(',', $userStorageUid, true)) < 1 ||
            count($this->invoiceStorageUids = GeneralUtility::intExplode(',', $invoiceStorageUid, true)) < 1
        ) {
            return $this;
        }
        ($this->userRepository = GeneralUtility::makeInstance(UserRepository::class))
            ->setQuerySettings($this->userStorageUids);
        ($this->invoiceRepository = GeneralUtility::makeInstance(InvoiceRepository::class))
            ->setQuerySettings($this->invoiceStorageUids);
        $this->setInitialized();
        return $this;
    }

    public function process(): array
    {
        $result = [];
        $this->assertInitialized();
        $localInvoices = $this->invoiceRepository->getAllPending();
        $invoicesSince = new \DateTime();
        if (isset($localInvoices[0])) {
            $invoicesSince = $localInvoices[0]->getIsValidFrom();
        }
        if ($this->options['from'] instanceof \DateTime) {
            $invoicesSince = $this->options['from'];
        }
        $remoteInvoices = $this->getRemoteInvoices($invoicesSince->modify('midnight'));
        $result['updated'] = $this->updateLocalInvoices($localInvoices, $remoteInvoices);
        $result['new'] = $this->createLocalInvoices($localInvoices, $remoteInvoices);
        GeneralUtility::makeInstance(PersistenceManager::class)->persistAll();
        return $result;
    }

    /**
     * @param Invoice[] $localInvoices
     * @param \stdClass[] $remoteInvoices
     */
    protected function updateLocalInvoices(array $localInvoices, array $remoteInvoices): int
    {
        $count = 0;
        foreach ($localInvoices as $localInvoice) {
            if (
                ($remoteInvoice = $remoteInvoices[$localInvoice->getId()] ?? null) === null ||
                !(InvoiceDto::update($localInvoice, $remoteInvoice))
            ) {
                continue;
            }
            $this->invoiceRepository->update($localInvoice);
            $count++;
        }
        return $count;
    }

    /**
     * @param Invoice[] $localInvoices
     * @param \stdClass[] $remoteInvoices
     */
    protected function createLocalInvoices(array $localInvoices, array $remoteInvoices): int
    {
        $count = 0;
        $indexedLocalInvoices = [];
        foreach ($localInvoices as $localInvoice) {
            $indexedLocalInvoices[$localInvoice->getId()] = $localInvoice;
        }
        foreach ($remoteInvoices as $remoteInvoice) {
            $statusCheck = $remoteInvoice->kb_item_status_id === Invoice::STATUS_OPEN;
            if ($this->options['include-paid']) {
                $statusCheck = $statusCheck || ($remoteInvoice->kb_item_status_id === Invoice::STATUS_PAID);
            }
            if (
                !$statusCheck ||
                isset($indexedLocalInvoices[$remoteInvoice->id]) ||
                ($bexioId = $remoteInvoice->contact_sub_id ?? $remoteInvoice->contact_id ?? null) === null ||
                ($user = $this->userRepository->findByBexioId($bexioId)->getFirst()) === null
            ) {
                continue;
            }
            $newInvoice = InvoiceDto::getLocalFromRemote($remoteInvoice);
            $newInvoice->setUser($user)->setPid($this->invoiceStorageUids[0]);
            $this->invoiceRepository->add($newInvoice);
            $count++;
        }
        return $count;
    }

    /**
     * Get all remote invoices with pending state OR that are younger
     * than specified by $since.
     *
     * @return \stdClass[]
     */
    protected function getRemoteInvoices(\DateTime $since): array
    {
        $invoiceResource = new InvoiceResource($this->apiClient);
        $constraint = ['field' => 'kb_item_status_id', 'value' => Invoice::STATUS_OPEN, 'criteria' => '='];
        $queryParams = ['order_by=is_valid_from'];
        $pendingInvoices = $invoiceResource->searchInvoices([$constraint], $queryParams);
        $constraint = ['field' => 'is_valid_from', 'value' => $since->format('Y-m-d'), 'criteria' => '>'];
        $sinceInvoices = $invoiceResource->searchInvoices([$constraint], $queryParams);
        $indexedInvoices = [];
        foreach ($pendingInvoices as $invoice) {
            $indexedInvoices[$invoice->id] = $invoice;
        }
        foreach ($sinceInvoices as $invoice) {
            $indexedInvoices[$invoice->id] = $invoice;
        }
        return $indexedInvoices;
    }
}
