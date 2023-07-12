<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-bexio.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Bexio\Task\User\Service;

use Buepro\Bexio\Domain\Model\User;
use Buepro\Bexio\Domain\Repository\UserRepository;
use Buepro\Bexio\Dto\ContactDto;
use Buepro\Bexio\Task\User\UpdateUsers;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class UpdateService
{
    protected array $options;
    /** @var int<0, max>[] */
    protected array $storageUids = [0];
    protected string $userGroupUids = '';
    protected array $linkMatchProperties = ['email'];
    protected UserRepository $userRepository;
    protected int $updatedCount = 0;
    protected int $newCount = 0;

    public function __construct(Site $site, array $options)
    {
        $this->options = $options;
        $config = $site->getConfiguration()['bexio']['user'] ?? [];
        $storageUid = (string)($config['storageUid'] ?? '0');
        $this->storageUids = array_filter(
            GeneralUtility::intExplode(',', $storageUid, true),
            fn ($v) => $v >= 0
        );
        $userGroupUid = trim((string)($config['userGroupUid'] ?? ''));
        if ($userGroupUid !== '') {
            $this->userGroupUids = implode(',', GeneralUtility::intExplode(',', $userGroupUid, true));
        }
        $linkMatchProperties = trim((string)($config['linkMatchProperties'] ?? ''));
        if ($linkMatchProperties !== '') {
            $this->linkMatchProperties = GeneralUtility::trimExplode(',', $linkMatchProperties, true);
        }
        $this->userRepository = (GeneralUtility::makeInstance(UserRepository::class));
        $this->userRepository->setQuerySettings($this->storageUids);
    }

    public function processContactDto(ContactDto $dto): void
    {
        if (
            ($user = $this->getUserForDto($dto)) !== null &&
            (
                $user->getBexioId() > 0 ||
                $this->options[UpdateUsers::OPTION_LINK] ||
                $this->options[UpdateUsers::OPTION_CREATE]
            )
        ) {
            $this->updateUser($user, $dto, $this->options[UpdateUsers::OPTION_OVERWRITE]);
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

    protected function getUserForDto(ContactDto $dto): ?User
    {
        /** @var ?User $user */
        $user = $this->userRepository->findOneBy(['bexioId' => $dto->getId()]);
        if ($user === null) {
            $user = $this->userRepository->findOneByProperties($dto->getProperties($this->linkMatchProperties));
        }
        return $user;
    }

    protected function updateUser(User $user, ContactDto $dto, bool $overwrite = false): void
    {
        if ($dto->updateUser($user, $overwrite)) {
            $this->updatedCount += 1;
            $this->userRepository->update($user);
        }
    }

    protected function createUser(ContactDto $dto): void
    {
        $user = $dto->createUser();
        $user->setUsergroup($this->userGroupUids)->setPid($this->storageUids[0]);
        $this->userRepository->add($user);
        $this->newCount++;
    }
}
