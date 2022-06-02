<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-bexio.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Bexio\Command\Other;

use Buepro\Bexio\Service\ApiService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Query extends Command
{
    protected function configure(): void
    {
        $this
            ->setDescription(
                'Query an api endpoint and print the result in json format.'
            )
            ->addOption(
                'with-arguments',
                'a',
                InputOption::VALUE_NONE,
                'Set this option to provide arguments after entering the command.
Without this option no arguments will be passed to the method.'
            )
            ->addOption(
                'file',
                'f',
                InputOption::VALUE_REQUIRED,
                'Write the result to the file specified by this option.'
            )
            ->addOption(
                'raw',
                'r',
                InputOption::VALUE_NONE,
                'Get raw response. No json encoding takes place.'
            )
            ->addArgument(
                'site',
                InputArgument::REQUIRED,
                'The identifier from the site the query should be created for.'
            )
            ->addArgument(
                'resource',
                InputArgument::REQUIRED,
                'The resource to be used.'
            )
            ->addArgument(
                'method',
                InputArgument::REQUIRED,
                'The resource method to be used.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $methodArguments = $this->getMethodArguments($input, $output);
        $io->writeln("\nQuerying the endpoint...");
        try {
            $site = GeneralUtility::makeInstance(SiteFinder::class)
                ->getSiteByIdentifier((string)$input->getArgument('site'));
            $client = GeneralUtility::makeInstance(ApiService::class)->initialize($site)->getClient();
            $resourceClass = '\\Bexio\\Resource\\' . ucfirst((string)$input->getArgument('resource'));
            $method = (string)$input->getArgument('method');
            $result = (new $resourceClass($client))->$method(...$methodArguments);
            if ($input->getOption('raw') === false) {
                $result = json_encode($result, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT|JSON_THROW_ON_ERROR);
            }
            if (($file = $input->getOption('file')) !== null) {
                $this->writeResultToFile($result, $file);
                $io->writeln('Response written to file "' . $file . '"');
                return Command::SUCCESS;
            }
            $io->writeln('Response:');
            /** @var FormatterHelper $formatter */
            $formatter = $this->getHelper('formatter');
            $io->writeln($formatter->formatBlock(explode("\n", $result), 'info'));
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error(sprintf('%s (%d)', $e->getMessage(), (int)$e->getCode()));
            return Command::FAILURE;
        }
    }

    protected function getMethodArguments(InputInterface $input, OutputInterface $output): array
    {
        $args = [];
        if ($input->getOption('with-arguments') !== false) {
            $helper = $this->getHelper('question');
            $question = new Question("Arguments (one per line, continue with Ctrl-D on Linux, Ctrl-Z on Windows):\n");
            $question->setMultiline(true);
            $args = $helper->ask($input, $output, $question);
            $args = GeneralUtility::trimExplode("\n", $args, true);
            $args = array_map(static fn ($arg) => \json_decode($arg, true), $args);
        }
        return $args;
    }

    protected function writeResultToFile(mixed $result, string $file): void
    {
        if (
            $result instanceof \stdClass &&
            property_exists($result, 'mime') && $result->mime === 'application/pdf' &&
            property_exists($result, 'content')
        ) {
            $result = base64_decode($result->content, true);
        }
        file_put_contents($file, $result);
    }
}
