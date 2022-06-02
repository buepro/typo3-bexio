<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-bexio.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Bexio\Task\Invoice;

use Bexio\Resource\Invoice;
use Buepro\Bexio\Service\ApiService;
use Buepro\Bexio\Service\InvoiceSiteService;
use Buepro\Bexio\Task\AbstractTask;
use Buepro\Bexio\Task\TaskInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class CreateInvoice extends AbstractTask implements TaskInterface
{
    protected array $invoiceData = [];

    public function initialize(int $userUid = 0, array $invoice = []): TaskInterface
    {
        $this->setUserData($userUid)->setBaseData($invoice)->setPositionData($invoice);
        $this->setInitialized();
        return $this;
    }

    public function process(): array
    {
        $this->assertInitialized();
        $client = GeneralUtility::makeInstance(ApiService::class)->initialize($this->site)->getClient();
        $invoiceResource = new Invoice($client);
        $response = $invoiceResource->createInvoice($this->invoiceData);
        $invoiceId = $response->id;
        $invoiceResource->issueInvoice($invoiceId);
        $response = $invoiceResource->getInvoice($invoiceId);
        return json_decode((string)json_encode($response), true);
    }

    protected function setUserData(int $userUid): self
    {
        $user = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('fe_users')
            ->select(['*'], 'fe_users', ['uid' => $userUid])
            ->fetchAssociative();
        if (!is_array($user)) {
            throw new \LogicException('User not found', 1654071977);
        }
        $this->invoiceData['contact_id'] = $user['tx_bexio_company_id'] > 0 ? $user['tx_bexio_company_id'] : null;
        $this->invoiceData['contact_sub_id'] = $user['tx_bexio_id'];
        $this->invoiceData['language_id'] = $user['tx_bexio_language_id'] > 0 ? $user['tx_bexio_language_id'] : 1;
        return $this;
    }

    protected function setBaseData(array $invoice): self
    {
        $invoiceBase = $invoice;
        if (isset($invoiceBase['positions'])) {
            unset($invoiceBase['positions']);
        }
        $invoiceBase = (new InvoiceSiteService($this->site))->getNewBase($invoiceBase);
        foreach ($invoiceBase as $property => $value) {
            $this->invoiceData[GeneralUtility::camelCaseToLowerCaseUnderscored($property)] = $value;
        }
        return $this;
    }

    protected function setPositionData(array $invoice): self
    {
        if (!is_array($positions = $invoice['positions'] ?? null)) {
            return $this;
        }
        $this->invoiceData['positions'] = [];
        $invoiceService = new InvoiceSiteService($this->site);
        foreach ($positions as $position) {
            $position = $invoiceService->getNewPosition($position);
            $positionData = [];
            foreach ($position as $property => $value) {
                $positionData[GeneralUtility::camelCaseToLowerCaseUnderscored($property)] = $value;
            }
            $this->invoiceData['positions'][] = $positionData;
        }
        return $this;
    }
}
