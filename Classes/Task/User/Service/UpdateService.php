<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-bexio.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Bexio\Task\User\Service;

use Buepro\Bexio\Dto\ContactDto;
use Buepro\Bexio\Task\User\UpdateUsers;
use TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashFactory;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class UpdateService
{
    protected array $options;
    /** @var int[] */
    protected array $storageUids = [0];
    protected string $userGroupUids = '';
    protected array $linkMatchProperties = ['email'];
    protected Connection $connection;
    protected int $updatedCount = 0;
    protected int $newCount = 0;

    public function __construct(Site $site, array $options)
    {
        $this->options = $options;
        $config = $site->getConfiguration()['bexio']['user'] ?? [];
        $storageUid = (string)($config['storageUid'] ?? '0');
        $this->storageUids = GeneralUtility::intExplode(',', $storageUid, true);
        $userGroupUid = trim((string)($config['userGroupUid'] ?? ''));
        if ($userGroupUid !== '') {
            $this->userGroupUids = implode(',', GeneralUtility::intExplode(',', $userGroupUid, true));
        }
        $linkMatchProperties = trim((string)($config['linkMatchProperties'] ?? ''));
        if ($linkMatchProperties !== '') {
            $this->linkMatchProperties = GeneralUtility::trimExplode(',', $linkMatchProperties, true);
        }
        $this->connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('fe_users');
    }

    public function processContactDto(ContactDto $dto): void
    {
        if (
            ($user = $this->getUserForDto($dto)) !== null &&
            (
                (int)$user['tx_bexio_id'] > 0 ||
                $this->options[UpdateUsers::OPTION_LINK] ||
                $this->options[UpdateUsers::OPTION_CREATE]
            )
        ) {
            $this->updateUser($user, $dto);
            return;
        }
        if ($this->options[UpdateUsers::OPTION_CREATE]) {
            $this->createUser($dto);
        }
    }

    public function getStatistics(): array
    {
        return [
            UpdateUsers::STATISTICS_NEW => $this->newCount,
            UpdateUsers::STATISTICS_UPDATED => $this->updatedCount,
        ];
    }

    protected function getUserForDto(ContactDto $dto): ?array
    {
        $user = $this->getUniqueUserDto(['id'], $dto);
        if ($user === null) {
            $user = $this->getUniqueUserDto($this->linkMatchProperties, $dto);
        }
        return $user;
    }

    protected function getUniqueUserDto(array $properties, ContactDto $dto): ?array
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $constraints = [];
        foreach ($properties as $property) {
            if (
                method_exists($dto, ($method = 'get' . ucfirst($property))) &&
                ($field = ContactDto::PROPERTY_FIELD_MAP[$property] ?? null) !== null &&
                ($type = ContactDto::PROPERTY_TYPE_MAP[$property] ?? null) !== null
            ) {
                $constraints[] = $queryBuilder->expr()->eq(
                    $field,
                    $queryBuilder->createNamedParameter($dto->$method(), $type)
                );
            }
        }
        $constraints[] = $queryBuilder->expr()->in(
            'pid',
            $queryBuilder->createNamedParameter($this->storageUids, Connection::PARAM_INT_ARRAY)
        );
        $users = $queryBuilder
            ->select('*')
            ->from('fe_users')
            ->where(...$constraints)
            ->executeQuery()
            ->fetchAllAssociative();
        if (is_array($users[0] ?? null) && count($users) === 1) {
            return $users[0];
        }
        return null;
    }

    protected function updateUser(array $user, ContactDto $dto): void
    {
        if (($data = $dto->getUpdateData($user, $this->options[UpdateUsers::OPTION_OVERWRITE])) === []) {
            return;
        }
        $this->updatedCount += $this->connection->update(
            'fe_users',
            $data,
            ['uid' => (int)$user['uid']],
            $dto->getUpdateTypes($data)
        );
    }

    protected function createUser(ContactDto $dto): void
    {
        $data = $dto->getUpdateData();
        $types = $dto->getUpdateTypes($data);
        $data['pid'] = $this->storageUids[0];
        $data['crdate'] = $data['tstamp'] = $GLOBALS['EXEC_TIME'] ?? time();
        $data['usergroup'] = $this->userGroupUids;
        $data['username'] = 'bexio-' . md5((string)$dto->getId());
        $data['password'] = $this->getPassword();
        $types = [...$types, Connection::PARAM_INT, Connection::PARAM_INT, Connection::PARAM_INT, Connection::PARAM_STR,
            Connection::PARAM_STR, Connection::PARAM_STR];
        $this->newCount += $this->connection->insert(
            'fe_users',
            $data,
            $types,
        );
    }

    protected function getPassword(): string
    {
        $passwordHashFactory = GeneralUtility::makeInstance(PasswordHashFactory::class);
        $objInstanceSaltedPW = $passwordHashFactory->getDefaultHashInstance('FE');
        $password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$'), 0, 20);
        return $objInstanceSaltedPW->getHashedPassword($password);
    }
}
