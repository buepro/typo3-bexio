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
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class User extends AbstractEntity
{
    protected string $username = '';
    protected string $password = '';
    protected string $usergroup = '';
    protected string $firstName = '';
    protected string $lastName = '';
    protected string $address = '';
    protected string $telephone = '';
    protected string $email = '';
    protected string $zip = '';
    protected string $city = '';
    protected string $country = '';
    protected string $www = '';
    protected string $company = '';
    protected int $bexioId = 0;
    protected int $bexioCompanyId = 0;
    protected int $bexioLanguageId = 0;
    /**
     * @var ObjectStorage<Invoice>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $bexioInvoices = null;

    public function __construct()
    {
        // Do not remove the next line: It would break the functionality
        $this->initializeObject();
    }

    /**
     * Initializes all ObjectStorage properties when model is reconstructed from DB (where __construct is not called)
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     *
     * @return void
     */
    public function initializeObject()
    {
        $this->bexioInvoices = $this->bexioInvoices ?? new ObjectStorage();
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setUsergroup(string $usergroup): self
    {
        $this->usergroup = $usergroup;
        return $this;
    }

    public function getUsergroup(): string
    {
        return $this->usergroup;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;
        return $this;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;
        return $this;
    }

    public function getTelephone(): string
    {
        return $this->telephone;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setZip(string $zip): self
    {
        $this->zip = $zip;
        return $this;
    }

    public function getZip(): string
    {
        return $this->zip;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;
        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;
        return $this;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setWww(string $www): self
    {
        $this->www = $www;
        return $this;
    }

    public function getWww(): string
    {
        return $this->www;
    }

    public function setCompany(string $company): self
    {
        $this->company = $company;
        return $this;
    }

    public function getCompany(): string
    {
        return $this->company;
    }

    public function setBexioId(int $bexioId): self
    {
        $this->bexioId = $bexioId;
        return $this;
    }

    public function getBexioId(): int
    {
        return $this->bexioId;
    }

    public function setBexioCompanyId(int $bexioCompanyId): self
    {
        $this->bexioCompanyId = $bexioCompanyId;
        return $this;
    }

    public function getBexioCompanyId(): int
    {
        return $this->bexioCompanyId;
    }

    public function setBexioLanguageId(int $bexioLanguageId): self
    {
        $this->bexioLanguageId = $bexioLanguageId;
        return $this;
    }

    public function getBexioLanguageId(): int
    {
        return $this->bexioLanguageId;
    }

    public function addBexioInvoice(Invoice $bexioInvoice): self
    {
        $this->bexioInvoices->attach($bexioInvoice);
        return $this;
    }

    public function removeBexioInvoice(Invoice $bexioInvoiceToRemove): self
    {
        $this->bexioInvoices->detach($bexioInvoiceToRemove);
        return $this;
    }

    /** @return ObjectStorage<Invoice> $bexioInvoices */
    public function getBexioInvoices()
    {
        return $this->bexioInvoices;
    }

    /** @param ObjectStorage<Invoice> $bexioInvoices */
    public function setBexioInvoices(ObjectStorage $bexioInvoices): self
    {
        $this->bexioInvoices = $bexioInvoices;
        return $this;
    }

    public function updateProperties(array $properties, bool $overwrite = false): bool
    {
        $result = false;
        foreach ($properties as $name => $value) {
            $property = &$this->$name;
            if ($property !== $value && ($overwrite || !(bool)$property)) {
                $property = $value;
                $result = true;
            }
        }
        unset($property);
        return $result;
    }
}
