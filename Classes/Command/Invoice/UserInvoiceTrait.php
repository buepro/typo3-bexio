<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-bexio.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Bexio\Command\Invoice;

use Buepro\Bexio\Domain\Repository\InvoiceRepository;
use Buepro\Bexio\Domain\Repository\UserRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;

trait UserInvoiceTrait
{
    protected array $userStorageUids = [];
    protected array $invoiceStorageUids = [];
    protected ?UserRepository $userRepository = null;
    protected ?InvoiceRepository $invoiceRepository = null;

    public function injectUserRepository(UserRepository $userRepository): void
    {
        $this->userRepository = $userRepository;
    }

    public function injectInvoiceRepository(InvoiceRepository $invoiceRepository): void
    {
        $this->invoiceRepository = $invoiceRepository;
    }
    public function initializeUserInvoiceElements(): self
    {
        if (
            (($userStorageUid = $this->site->getConfiguration()['bexio']['user']['storageUid'] ?? null) === null) ||
            (($invoiceStorageUid = $this->site->getConfiguration()['bexio']['invoice']['storageUid'] ?? null) === null) ||
            count($this->userStorageUids = GeneralUtility::intExplode(',', (string) $userStorageUid, true)) < 1 ||
            count($this->invoiceStorageUids = GeneralUtility::intExplode(',', (string) $invoiceStorageUid, true)) < 1
        ) {
            throw new \InvalidArgumentException('The storage uid for the user or the invoice is not defined', 1689148654);
        }
        if ($this->userRepository === null || $this->invoiceRepository === null) {
            throw new \LogicException('The user or invoice repository is not available', 1689148777);
        }
        $this->userRepository->setQuerySettings($this->userStorageUids);
        $this->invoiceRepository->setQuerySettings($this->invoiceStorageUids);
        return $this;
    }
}
