<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-bexio.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Bexio\Command\User;

use Buepro\Bexio\Command\AbstractSitesCommand;
use Buepro\Bexio\Task\User\UpdateUsers as UpdateUsersTask;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class UpdateUsers extends AbstractSitesCommand
{
    protected array $options = UpdateUsersTask::DEFAULT_OPTIONS;

    protected function configure(): void
    {
        $this
            ->setDescription(
                'Update users with data from bexio contacts. Just empty fields from already
linked users will be updated.'
            )
            ->addOption(
                'create',
                'c',
                InputOption::VALUE_NONE,
                'Create new users for bexio contacts that can not
be linked to any existing frontent user.'
            )
            ->addOption(
                'link',
                'l',
                InputOption::VALUE_NONE,
                'Link bexio contacts to frontend user by matching
properties.'
            )
            ->addOption(
                'overwrite',
                'o',
                InputOption::VALUE_NONE,
                'Overwrite all field values with the ones from the
linked bexio contact.'
            );
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        try {
            $io->writeln('Updating local frontend users...');
            $this->options = [
                UpdateUsersTask::OPTION_CREATE => (bool)$input->getOption('create'),
                UpdateUsersTask::OPTION_LINK => (bool)$input->getOption('link'),
                UpdateUsersTask::OPTION_OVERWRITE => (bool)$input->getOption('overwrite'),
            ];
            return $this->processSites($input, $output);
        } catch (\Exception $e) {
            /** @extensionScannerIgnoreLine */
            $io->error($e->getMessage());
            return Command::FAILURE;
        }
    }

    protected function processSite(Site $site, SymfonyStyle $io): void
    {
        $statistics = GeneralUtility::makeInstance(UpdateUsersTask::class)
            ->initialize($site, $this->options)
            ->process();
        $io->writeln(sprintf(
            '- Site "%s": %d updated, %d new',
            $site->getIdentifier(),
            $statistics[UpdateUsersTask::STATISTICS_UPDATED],
            $statistics[UpdateUsersTask::STATISTICS_NEW]
        ));
    }
}
