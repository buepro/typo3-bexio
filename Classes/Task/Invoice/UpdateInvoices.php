<?php

/*
 * This file is part of the composer package buepro/typo3-bexio.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Bexio\Task\Invoice;

use Bexio\Resource\Invoice as InvoiceResource;
use Buepro\Bexio\Command\Invoice\UserInvoiceTrait;
use Buepro\Bexio\Domain\Model\Invoice;
use Buepro\Bexio\Dto\InvoiceDto;
use Buepro\Bexio\Task\AbstractTask;
use Buepro\Bexio\Task\TaskInterface;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

class UpdateInvoices extends AbstractTask implements TaskInterface
{
    use UserInvoiceTrait;

    public const DEFAULT_OPTIONS = [
        'from' => null,
        'include-paid' => false,
    ];
    protected array $options = self::DEFAULT_OPTIONS;

    public function initialize(Site $site, array $options = self::DEFAULT_OPTIONS): TaskInterface
    {
        parent::initialize($site);
        $this->options = $options;
        $this->initializeUserInvoiceElements();
        $this->setInitialized();
        return $this;
    }

    public function process(): array
    {
        $result = [];
        $this->assertInitialized();
        $localInvoices = $this->invoiceRepository->findAllPending();
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
                /** @extensionScannerIgnoreLine */
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
     * than specified by $since. The array key is the invoice id.
     *
     * @return \stdClass[]
     */
    protected function getRemoteInvoices(\DateTime $since): array
    {
        $result = $this->getRemoteInvoicesForConstraint(['field' => 'kb_item_status_id', 'value' => Invoice::STATUS_OPEN, 'criteria' => '=']);
        $youngInvoices = $this->getRemoteInvoicesForConstraint(['field' => 'is_valid_from', 'value' => $since->format('Y-m-d'), 'criteria' => '>']);
        foreach ($youngInvoices as $key => $value) {
            $result[$key] = $value;
        }
        return $result;
    }

    /**
     * @return \stdClass[]
     */
    private function getRemoteInvoicesForConstraint(
        array $constraint,
        array $queryParams = ['order_by=is_valid_from']
    ): array {
        $invoiceResource = new InvoiceResource($this->apiClient);
        $result = [];
        $invoices = $invoiceResource->searchInvoices([$constraint], $queryParams);
        if (!is_iterable($invoices)) {
            return $result;
        }
        foreach ($invoices as $invoice) {
            if ($invoice instanceof \stdClass) {
                /** @extensionScannerIgnoreLine */
                $result[$invoice->id] = $invoice;
            }
        }
        return $result;
    }
}
