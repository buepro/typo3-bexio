<?php

/*
 * This file is part of the composer package buepro/typo3-bexio.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Bexio\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

abstract class AbstractSitesCommand extends Command
{
    protected function configure(): void
    {
        $this->addOption(
            'site',
            's',
            InputOption::VALUE_REQUIRED,
            'Site identifier from the site for which the command
should be used. Without this option all sites are
included.'
        );
    }

    abstract protected function processSite(Site $site, SymfonyStyle $io): void;

    protected function processSites(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        if (($sites = $this->getSites($input, $io)) === null) {
            return Command::INVALID;
        }

        foreach ($sites as $site) {
            try {
                $this->processSite($site, $io);
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
