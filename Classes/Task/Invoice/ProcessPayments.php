<?php

/*
 * This file is part of the composer package buepro/typo3-bexio.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Bexio\Task\Invoice;

use Buepro\Bexio\Command\Invoice\UserInvoiceTrait;
use Buepro\Bexio\Event\InvoicePaymentEvent;
use Buepro\Bexio\Task\AbstractTask;
use Buepro\Bexio\Task\TaskInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

class ProcessPayments extends AbstractTask implements TaskInterface
{
    use UserInvoiceTrait;

    public const PROCESS_RESULT_UNPROCESSED = 'unprocessed';
    public const PROCESS_RESULT_PROCESSED = 'processed';
    public const DEFAULT_PROCESS_RESULT = [
        self::PROCESS_RESULT_UNPROCESSED => 0,
        self::PROCESS_RESULT_PROCESSED => 0
    ];

    protected EventDispatcherInterface $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function initialize(Site $site): TaskInterface
    {
        parent::initialize($site);
        $this->initializeUserInvoiceElements();
        $this->setInitialized();
        return $this;
    }

    public function process(): array
    {
        $result = self::DEFAULT_PROCESS_RESULT;
        $this->assertInitialized();
        $invoices = $this->invoiceRepository->findAllForPaymentProcessing();
        foreach ($invoices as $invoice) {
            $event = new InvoicePaymentEvent($invoice);
            if ($this->eventDispatcher->dispatch($event)->getReprocessingRequested()) {
                $result[self::PROCESS_RESULT_UNPROCESSED]++;
                continue;
            }
            $result[self::PROCESS_RESULT_PROCESSED]++;
            $invoice->setPaymentProcessTime((new \DateTime())->setTimestamp($GLOBALS['EXEC_TIME']));
            $this->invoiceRepository->update($invoice);
        }
        GeneralUtility::makeInstance(PersistenceManager::class)->persistAll();
        return $result;
    }
}
