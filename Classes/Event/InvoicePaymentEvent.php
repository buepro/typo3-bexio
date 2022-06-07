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
use TYPO3\CMS\Core\Site\Entity\Site;

final class InvoicePaymentEvent
{
    private Invoice $invoice;
    private Site $site;
    private bool $reprocessingRequested = false;
    /** @var string[] $reprocessingRequestReasons */
    private array $reprocessingRequestReasons = [];

    public function __construct(Site $site, Invoice $invoice)
    {
        $this->invoice = $invoice;
        $this->site = $site;
    }

    public function getInvoice(): Invoice
    {
        return $this->invoice;
    }

    public function getSite(): Site
    {
        return $this->site;
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
    public function requestReprocessing(string $reason = ''): self
    {
        $this->reprocessingRequested = true;
        return $this;
    }

    /** @return string[] */
    public function getReprocessingRequestReasons(): array
    {
        return $this->reprocessingRequestReasons;
    }
}
