<?php

/*
 * This file is part of the composer package buepro/typo3-bexio.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Bexio\Command\Invoice;

use Buepro\Bexio\Command\AbstractSitesCommand;
use Buepro\Bexio\Task\Invoice\ProcessPayments as ProcessPaymentsTask;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ProcessPayments extends AbstractSitesCommand
{
    protected function configure(): void
    {
        $this->setHelp(
            'Process all invoices that are payed but don\'t have a payment process time
assigned yet by dispatching an event.'
        );
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        try {
            $io->writeln('Processing invoice payments...');
            return $this->processSites($input, $output);
        } catch (\Exception $e) {
            /** @extensionScannerIgnoreLine */
            $io->error($e->getMessage());
            return Command::FAILURE;
        }
    }

    protected function processSite(Site $site, SymfonyStyle $io): void
    {
        $statistics = GeneralUtility::makeInstance(ProcessPaymentsTask::class)
            ->initialize($site)
            ->process();
        $io->writeln(sprintf(
            '- Site "%s": %d unprocessed, %d processed',
            $site->getIdentifier(),
            $statistics[ProcessPaymentsTask::PROCESS_RESULT_UNPROCESSED],
            $statistics[ProcessPaymentsTask::PROCESS_RESULT_PROCESSED]
        ));
    }
}
