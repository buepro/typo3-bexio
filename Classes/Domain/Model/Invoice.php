<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-bexio.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Bexio\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Invoice extends AbstractEntity
{
    public const STATUS_DRAFT = 7;
    public const STATUS_OPEN = 8;
    public const STATUS_PAID = 9;
    public const STATUS_CANCELLED = 19;

    protected const PROPERTIES = ['id', 'user', 'title', 'documentNr', 'languageId', 'bankAccountId', 'currencyId',
        'total', 'isValidFrom', 'isValidTo', 'kbItemStatusId', 'reference', 'apiReference', 'viewedByClientAt',
        'esrId', 'qrInvoiceId', 'networkLink', 'paymentProcessTime'];

    protected int $id = 0;
    protected ?User $user = null;
    protected string $title = '';
    protected string $documentNr = '';
    protected int $languageId = 0;
    protected int $bankAccountId = 0;
    protected int $currencyId = 0;
    protected float $total = 0.0;
    protected ?\DateTime $isValidFrom = null;
    protected ?\DateTime $isValidTo = null;
    protected int $kbItemStatusId = 0;
    protected string $reference = '';
    protected string $apiReference = '';
    protected ?\DateTime $viewedByClientAt = null;
    protected int $esrId = 0;
    protected int $qrInvoiceId = 0;
    protected string $networkLink = '';
    protected ?\DateTime $paymentProcessTime = null;

    public function __construct()
    {
        $nullTime = (new \DateTime())->setTimestamp(0);
        $this->isValidFrom = $nullTime;
        $this->isValidTo = $nullTime;
        $this->viewedByClientAt = $nullTime;
        $this->paymentProcessTime = $nullTime;
    }

    public function getId(): int
    {
        /** @extensionScannerIgnoreLine */
        return $this->id;
    }

    public function setId(int $id): self
    {
        /** @extensionScannerIgnoreLine */
        $this->id = $id;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getDocumentNr(): string
    {
        return $this->documentNr;
    }

    public function setDocumentNr(string $documentNr): self
    {
        $this->documentNr = $documentNr;
        return $this;
    }

    public function getLanguageId(): int
    {
        return $this->languageId;
    }

    public function setLanguageId(int $languageId): self
    {
        $this->languageId = $languageId;
        return $this;
    }

    public function getBankAccountId(): int
    {
        return $this->bankAccountId;
    }

    public function setBankAccountId(int $bankAccountId): self
    {
        $this->bankAccountId = $bankAccountId;
        return $this;
    }

    public function getCurrencyId(): int
    {
        return $this->currencyId;
    }

    public function setCurrencyId(int $currencyId): self
    {
        $this->currencyId = $currencyId;
        return $this;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;
        return $this;
    }

    public function getIsValidFrom(): \DateTime
    {
        return $this->isValidFrom ?? (new \DateTime())->setTimestamp(0);
    }

    public function setIsValidFrom(\DateTime $isValidFrom): self
    {
        $this->isValidFrom = $isValidFrom;
        return $this;
    }

    public function getIsValidTo(): \DateTime
    {
        return $this->isValidTo ?? (new \DateTime())->setTimestamp(0);
    }

    public function setIsValidTo(\DateTime $isValidTo): self
    {
        $this->isValidTo = $isValidTo;
        return $this;
    }

    public function getKbItemStatusId(): int
    {
        return $this->kbItemStatusId;
    }

    public function setKbItemStatusId(int $kbItemStatusId): self
    {
        $this->kbItemStatusId = $kbItemStatusId;
        return $this;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;
        return $this;
    }

    public function getApiReference(): string
    {
        return $this->apiReference;
    }

    public function setApiReference(string $apiReference): self
    {
        $this->apiReference = $apiReference;
        return $this;
    }

    public function getViewedByClientAt(): \DateTime
    {
        return $this->viewedByClientAt ?? (new \DateTime())->setTimestamp(0);
    }

    public function setViewedByClientAt(\DateTime $viewedByClientAt): self
    {
        $this->viewedByClientAt = $viewedByClientAt;
        return $this;
    }

    public function getEsrId(): int
    {
        return $this->esrId;
    }

    public function setEsrId(int $esrId): self
    {
        $this->esrId = $esrId;
        return $this;
    }

    public function getQrInvoiceId(): int
    {
        return $this->qrInvoiceId;
    }

    public function setQrInvoiceId(int $qrInvoiceId): self
    {
        $this->qrInvoiceId = $qrInvoiceId;
        return $this;
    }

    public function getNetworkLink(): string
    {
        return $this->networkLink;
    }

    public function setNetworkLink(string $networkLink): self
    {
        $this->networkLink = $networkLink;
        return $this;
    }

    public function setPaymentProcessTime(\DateTime $paymentProcessTime): self
    {
        $this->paymentProcessTime = $paymentProcessTime;
        return $this;
    }

    public function getPaymentProcessTime(): \DateTime
    {
        return $this->paymentProcessTime ?? (new \DateTime())->setTimestamp(0);
    }

    public function toArray(): array
    {
        $result = [];
        foreach (self::PROPERTIES as $property) {
            $result[$property] = $this->$property;
        }
        return $result;
    }
}
