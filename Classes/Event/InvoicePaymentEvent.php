<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-bexio.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Bexio\Event;

use Buepro\Bexio\Domain\Model\Invoice;

final class InvoicePaymentEvent
{
    private Invoice $invoice;
    private bool $reprocessingRequested = false;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function getInvoice(): Invoice
    {
        return $this->invoice;
    }

    public function getReprocessingRequested(): bool
    {
        return $this->reprocessingRequested;
    }

    /**
     * Event handlers call this method to signal that something went wrong
     * during their payment processing and that the event should be emitted
     * again.
     */
    public function requestReprocessing(): self
    {
        $this->reprocessingRequested = true;
        return $this;
    }
}
