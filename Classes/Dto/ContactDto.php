<?php

/*
 * This file is part of the composer package buepro/typo3-bexio.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Bexio\Dto;

use Buepro\Bexio\Task\User\UpdateUsers;
use TYPO3\CMS\Core\Database\Connection;

class ContactDto
{
    protected int $id = 0;
    protected int $companyId = 0;
    protected int $languageId = 0;
    protected string $company = '';
    protected string $firstName = '';
    protected string $lastName = '';
    protected string $address = '';
    protected string $zip = '';
    protected string $city = '';
    protected string $country = '';
    protected string $telephone = '';
    protected string $email = '';
    protected string $www = '';

    public const OWN_FOREIGN_PROPERTY_MAP = [
        'languageId' => 'language_id',
        'company' => 'name_1',
        'firstName' => 'name_2',
        'lastName' => 'name_1',
        'address' => 'address',
        'zip' => 'postcode',
        'city' => 'city',
        'email' => 'mail',
        'www' => 'url',
    ];

    public const PROPERTY_FIELD_MAP = [
        'id' => 'tx_bexio_id',
        'companyId' => 'tx_bexio_company_id',
        'languageId' => 'tx_bexio_language_id',
        'company' => 'company',
        'firstName' => 'first_name',
        'lastName' => 'last_name',
        'address' => 'address',
        'zip' => 'zip',
        'city' => 'city',
        'country' => 'country',
        'telephone' => 'telephone',
        'email' => 'email',
        'www' => 'www',
    ];

    public const PROPERTY_TYPE_MAP = [
        'id' => Connection::PARAM_INT,
        'companyId' => Connection::PARAM_INT,
        'languageId' => Connection::PARAM_INT,
        'company' => Connection::PARAM_STR,
        'firstName' => Connection::PARAM_STR,
        'lastName' => Connection::PARAM_STR,
        'address' => Connection::PARAM_STR,
        'zip' => Connection::PARAM_STR,
        'city' => Connection::PARAM_STR,
        'country' => Connection::PARAM_STR,
        'telephone' => Connection::PARAM_STR,
        'email' => Connection::PARAM_STR,
        'www' => Connection::PARAM_STR,
    ];

    public static function createFromUnrelatedContact(\stdClass $contact, array $countryNames): self
    {
        $dto = new self();
        self::assignStandardProperties($dto, $contact);
        self::assignAdvancedProperties($dto, $contact, $countryNames);
        $dto->id = $contact->id;
        return $dto;
    }

    public static function createFromRelatedContact(array $contact, array $countryNames): ?self
    {
        /** @var ?\stdClass $company */
        $company = $contact[UpdateUsers::RELATED_CONTACT_COMPANY] ?? null;
        /** @var ?\stdClass $person */
        $person = $contact[UpdateUsers::RELATED_CONTACT_PERSON] ?? null;
        if ($company === null && $person === null) {
            return null;
        }
        if ($company === null) {
            return self::createFromUnrelatedContact($person, $countryNames);
        }
        /** @var \stdClass $company */
        /** @var \stdClass $person */
        $dto = self::createFromUnrelatedContact($company, $countryNames);
        self::assignStandardProperties($dto, $person);
        self::assignAdvancedProperties($dto, $person, $countryNames);
        $dto->id = $person->id;
        $dto->companyId = $company->id;
        return $dto;
    }

    private static function assignStandardProperties(self $dto, \stdClass $contact): void
    {
        $propertyMap = self::OWN_FOREIGN_PROPERTY_MAP;
        if ($contact->contact_type_id === 1) {
            unset($propertyMap['firstName'], $propertyMap['lastName']);
        }
        if ($contact->contact_type_id === 2) {
            unset($propertyMap['company']);
        }
        foreach ($propertyMap as $own => $foreign) {
            if (!isset($contact->$foreign)) {
                continue;
            }
            $value = is_string($contact->$foreign) ? trim($contact->$foreign) : $contact->$foreign;
            if ((bool)$value) {
                $dto->$own = $value;
            }
        }
    }

    private static function assignAdvancedProperties(self $dto, \stdClass $contact, array $countryNames): void
    {
        if (($country = $countryNames[$contact->country_id ?? 'unset'] ?? '') !== '') {
            $dto->country = $country;
        }
        if (($telephone = $dto->getTelephoneForContact($contact)) !== '') {
            $dto->telephone = $telephone;
        }
    }

    private function getTelephoneForContact(\stdClass $contact): string
    {
        if ($contact->phone_fixed !== null && $contact->phone_fixed !== '') {
            return $contact->phone_fixed;
        }
        if ($contact->phone_fixed_second !== null && $contact->phone_fixed_second !== '') {
            return $contact->phone_fixed_second;
        }
        if ($contact->phone_mobile !== null && $contact->phone_mobile !== '') {
            return $contact->phone_mobile;
        }
        return '';
    }

    public function getUpdateData(array $user = [], bool $overwriteFields = false): array
    {
        $result = [];
        foreach (array_keys(self::PROPERTY_TYPE_MAP) as $property) {
            $fieldName = self::PROPERTY_FIELD_MAP[$property];
            if (
                $this->$property !== '' &&
                $this->$property !== 0 &&
                (!isset($user[$fieldName]) || ($user[$fieldName] === 0) || ($user[$fieldName] === '') || $overwriteFields)
            ) {
                $result[$fieldName] = $this->$property;
            }
        }
        return $result;
    }

    public function getUpdateTypes(array $data): array
    {
        $result = [];
        $fieldPropertyMap = array_flip(self::PROPERTY_FIELD_MAP);
        foreach (array_keys($data) as $fieldName) {
            if (($key = $fieldPropertyMap[$fieldName] ?? null) !== null) {
                $result[] = self::PROPERTY_TYPE_MAP[$key];
            }
        }
        return $result;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCompanyId(): int
    {
        return $this->companyId;
    }

    public function getLanguageId(): int
    {
        return $this->languageId;
    }

    public function getCompany(): string
    {
        return $this->company;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getZip(): string
    {
        return $this->zip;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getTelephone(): string
    {
        return $this->telephone;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getWww(): string
    {
        return $this->www;
    }
}
