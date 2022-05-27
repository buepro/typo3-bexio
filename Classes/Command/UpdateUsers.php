<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-bexio.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Bexio\Command;

use Buepro\Bexio\Task\UpdateUsers as UpdateUsersTask;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class UpdateUsers extends Command
{
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
            )
            ->addOption(
                'site',
                's',
                InputOption::VALUE_REQUIRED,
                'Site identifier from the site for which users should
be updated. Without this option all sites are included.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->writeln('Updating local frontend users...');

        if (($sites = $this->getSites($input, $io)) === null) {
            return Command::INVALID;
        }

        $options = [
            UpdateUsersTask::OPTION_CREATE => (bool)$input->getOption('create'),
            UpdateUsersTask::OPTION_LINK => (bool)$input->getOption('link'),
            UpdateUsersTask::OPTION_OVERWRITE => (bool)$input->getOption('overwrite'),
        ];

        foreach ($sites as $site) {
            try {
                $statistics = (new UpdateUsersTask($site))->process($options);
                $io->writeln(sprintf(
                    '- Site "%s": %d updated, %d new',
                    $site->getIdentifier(),
                    $statistics[UpdateUsersTask::STATISTICS_UPDATED],
                    $statistics[UpdateUsersTask::STATISTICS_NEW]
                ));
            } catch (\Exception $e) {
                $io->writeln(sprintf(
                    '- Site "%s": %s (error code %d)',
                    $site->getIdentifier(),
                    $e->getMessage(),
                    $e->getCode()
                ));
            }
        }

        return Command::SUCCESS;
    }

    /** @return ?Site[] */
    protected function getSites(InputInterface $input, SymfonyStyle $io): ?array
    {
        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
        $sites = $siteFinder->getAllSites();
        if (count($sites) === 0) {
            $io->writeln('<error>No site available.</error>');
            return null;
        }
        if (($siteOption = $input->getOption('site')) !== null) {
            $sites = [];
            try {
                $site = $siteFinder->getSiteByIdentifier($siteOption);
                $sites[] = $site;
            } catch (\Exception $e) {
                $io->writeln('<error>The site "' . $siteOption . '" is not available.</error>');
                return null;
            }
        }
        return $sites;
    }
}
