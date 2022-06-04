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
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Site\Entity\Site;

class ProcessPayments extends AbstractSitesCommand
{
    protected function configure(): void
    {
        $this
            ->setDescription(
                'Process all invoices that are payed but don\'t have a payment process time
assigned yet by dispatching an event.'
            );
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->writeln('Processing invoice payments...');
        return $this->processSites($input, $output);
    }

    protected function processSite(Site $site, SymfonyStyle $io): void
    {
        $statistics = (new ProcessPaymentsTask($site))->initialize()->process();
        $io->writeln(sprintf(
            '- Site "%s": %d unprocessed, %d processed',
            $site->getIdentifier(),
            $statistics[ProcessPaymentsTask::PROCESS_RESULT_UNPROCESSED],
            $statistics[ProcessPaymentsTask::PROCESS_RESULT_PROCESSED]
        ));
    }
}
