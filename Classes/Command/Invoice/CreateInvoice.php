<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-bexio.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Bexio\Command\Invoice;

use Buepro\Bexio\Service\InvoiceSiteService;
use Buepro\Bexio\Task\Invoice\CreateInvoice as CreateInvoiceTask;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class CreateInvoice extends Command
{
    protected Site $site;

    protected function configure(): void
    {
        $this
            ->setDescription(
                'Create an invoice.'
            )
            ->addArgument(
                'site',
                InputArgument::REQUIRED,
                'Site identifier from the site for which the invoice should be created.'
            )
            ->addArgument(
                'user',
                InputArgument::REQUIRED,
                'UID from the frontend user the invoice should be created for.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        try {
            $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
            $this->site = $siteFinder->getSiteByIdentifier((string)$input->getArgument('site'));
            $invoiceDetails = $this->askInvoiceDetails($input, $output);
            $invoiceDetails['positions'] = $this->askPositionDetails($input, $output);
            $io->writeln("\nCreating invoice...");
            $result = (new CreateInvoiceTask())
                ->initialize($this->site, (int)$input->getArgument('user'), $invoiceDetails)
                ->process();
            $io->writeln('Response:');
            /** @var FormatterHelper $formatter */
            $formatter = $this->getHelper('formatter');
            $result = json_encode($result, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
            $io->writeln($formatter->formatBlock(explode("\n", (string)$result), 'info'));
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }
    }

    protected function askInvoiceDetails(InputInterface $input, OutputInterface $output): array
    {
        $output->writeln('-----------------');
        $output->writeln(' Base properties ');
        $output->writeln('-----------------');
        $details = (new InvoiceSiteService($this->site))->getNewBase(['title' => '']);
        return $this->askDetails($input, $output, $details);
    }

    protected function askPositionDetails(InputInterface $input, OutputInterface $output): array
    {
        $details = [];
        $invoiceService = new InvoiceSiteService($this->site);
        $helper = $this->getHelper('question');
        $addPositionQuestion = new ConfirmationQuestion('Add position [y, n](y): ', true);
        $pos = 1;
        while ($helper->ask($input, $output, $addPositionQuestion)) {
            $output->writeln('');
            $output->writeln('-------');
            $output->writeln(' Pos ' . $pos++);
            $output->writeln('-------');
            $positionDetails = $invoiceService->getNewPosition(['text' => '']);
            $details[] = $this->askDetails($input, $output, $positionDetails);
        }
        return $details;
    }

    protected function askDetails(InputInterface $input, OutputInterface $output, array $details): array
    {
        $helper = $this->getHelper('question');
        foreach ($details as $property => $defaultValue) {
            $question =  sprintf(
                '%s%s: ',
                ucfirst(str_replace('_', ' ', GeneralUtility::camelCaseToLowerCaseUnderscored($property))),
                (string)$defaultValue !== '' ? ' (' . (string)$defaultValue . ')' : ''
            );
            $details[$property] = $helper->ask($input, $output, new Question($question, $defaultValue));
        }
        return $details;
    }
}
