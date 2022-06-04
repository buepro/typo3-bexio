<?php

/*
 * This file is part of the composer package buepro/typo3-bexio.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Bexio\Command\Invoice;

use Buepro\Bexio\Command\AbstractSitesCommand;
use Buepro\Bexio\Task\Invoice\UpdateInvoices as UpdateInvoicesTask;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Site\Entity\Site;

class UpdateInvoices extends AbstractSitesCommand
{
    protected array $options = UpdateInvoicesTask::DEFAULT_OPTIONS;

    protected function configure(): void
    {
        $this
            ->setDescription(
                'Update local invoices and emit events on state changes. Invoices in "Pending"
state will be created locally.'
            )
            ->addOption(
                'from',
                'f',
                InputOption::VALUE_REQUIRED,
                'Overwrite the automatically calculated date from which
invoices are updated. The input will be parsed with the
php function strtotim'
            )
            ->addOption(
                'include-paid',
                'i',
                InputOption::VALUE_NONE,
                'Create as well paid invoices. This allows events to be
emitted for invoices that were were not available in
"Pending" state.'
            );
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->writeln('Updating local invoices...');
        $this->options['include-paid'] = $input->getOption('include-paid');
        if ($input->getOption('from') !== null) {
            $this->options['from'] = (new \DateTime())->setTimestamp(strtotime($input->getOption('from')));
        }
        return $this->processSites($input, $output);
    }

    protected function processSite(Site $site, SymfonyStyle $io): void
    {
        $statistics = (new UpdateInvoicesTask($site))->initialize($this->options)->process();
        $io->writeln(sprintf(
            '- Site "%s": %d updated, %d new',
            $site->getIdentifier(),
            $statistics['updated'],
            $statistics['new']
        ));
    }
}
