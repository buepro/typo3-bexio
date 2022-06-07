<?php

/*
 * This file is part of the composer package buepro/typo3-bexio.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Bexio\Task\User;

use Bexio\Resource\Contact;
use Buepro\Bexio\Api\Resource\Other;
use Buepro\Bexio\Dto\ContactDto;
use Buepro\Bexio\Task\AbstractTask;
use Buepro\Bexio\Task\TaskInterface;
use Buepro\Bexio\Task\User\Service\UpdateService;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

class UpdateUsers extends AbstractTask implements TaskInterface
{
    public const OPTION_CREATE = 'create';
    public const OPTION_LINK = 'link';
    public const OPTION_OVERWRITE = 'overwrite';
    public const DEFAULT_OPTIONS = [
        self::OPTION_CREATE => false,
        self::OPTION_LINK => false,
        self::OPTION_OVERWRITE => false,
    ];

    public const STATISTICS_UPDATED = 'updated';
    public const STATISTICS_NEW = 'new';
    public const DEFAULT_STATISTICS = [
        self::STATISTICS_NEW => 0,
        self::STATISTICS_UPDATED => 0,
    ];

    public const RELATED_CONTACT_COMPANY = 'company';
    public const RELATED_CONTACT_PERSON = 'person';

    protected array $options = self::DEFAULT_OPTIONS;

    public function initialize(Site $site, array $options = self::DEFAULT_OPTIONS): TaskInterface
    {
        parent::initialize($site);
        $this->options = array_merge(self::DEFAULT_OPTIONS, $options);
        $this->setInitialized();
        return $this;
    }

    public function process(): array
    {
        $this->assertInitialized();
        $contactResource = new Contact($this->apiClient);
        $contacts = $contactResource->getContacts();
        $relations = $contactResource->getContactsRelations();
        $countries = (new Other($this->apiClient))->getCountries();
        if (!is_array($relations) || !is_array($countries)) {
            return self::DEFAULT_STATISTICS;
        }
        $countryNames = [];
        foreach ($countries as $country) {
            $countryNames[$country->id] = $country->name;
        }
        $bookkeepingContacts = $this->compileBookkeepingContacts($contacts, $relations, $countryNames);
        return $this->updateFrontendUsers($bookkeepingContacts);
    }

    /**
     * Compiles the contact details used to direct a bookkeeping document (e.g. invoice).
     * A bookkeeping contact can be represented by a frontend user.
     *
     * @param \stdClass[] $contacts Contains company and contact person details in separate data sets
     * @param \stdClass[] $relations Relates persons with companies (id => company, sub_id => person)
     * @param array $countryNames
     * @return ContactDto[] Bookkeeping contacts as they are reflected by the frontend users
     */
    protected function compileBookkeepingContacts(array $contacts, array $relations, array $countryNames): array
    {
        $indexedContacts = [];
        foreach ($contacts as $contact) {
            $indexedContacts[$contact->id] = $contact;
        }
        $relatedContacts = $relatedCompanies = [];
        foreach ($relations as $relation) {
            $relatedContacts[$relation->contact_sub_id] = [
                self::RELATED_CONTACT_COMPANY => $indexedContacts[$relation->contact_id],
                self::RELATED_CONTACT_PERSON => $indexedContacts[$relation->contact_sub_id],
            ];
            $relatedCompanies[$relation->contact_id] = [...($relatedCompanies[$relation->contact_id] ?? []), $relation];
        }
        $unrelatedContacts = array_diff_key($indexedContacts, $relatedContacts, $relatedCompanies);
        $result = [];
        foreach ($unrelatedContacts as $contact) {
            $result[$contact->id] = ContactDto::createFromUnrelatedContact($contact, $countryNames);
        }
        foreach ($relatedContacts as $contact) {
            if (($dto = ContactDto::createFromRelatedContact($contact, $countryNames)) !== null) {
                $result[$contact[self::RELATED_CONTACT_PERSON]->id] = $dto;
            }
        }
        return $result;
    }

    /**
     * @param ContactDto[] $contactDtos
     * @return array Statistics with keys from self::DEFAULT_STATISTICS
     */
    protected function updateFrontendUsers(array $contactDtos): array
    {
        $updateUserService = new UpdateService($this->site, $this->options);
        foreach ($contactDtos as $contactDto) {
            $updateUserService->processContactDto($contactDto);
        }
        GeneralUtility::makeInstance(PersistenceManager::class)->persistAll();
        return $updateUserService->getStatistics();
    }
}
