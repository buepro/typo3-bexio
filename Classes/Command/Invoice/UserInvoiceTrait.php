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

    public function initializeUserInvoiceElements(): self
    {
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
        return $this;
    }
}
