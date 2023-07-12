<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-bexio.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Bexio\Command\Other;

use Bexio\Resource\Accounting;
use Buepro\Bexio\Api\Resource\Banking;
use Buepro\Bexio\Api\Resource\Other;
use Buepro\Bexio\Service\ApiService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Yaml\Yaml;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Settings extends Command
{
    protected Site $site;

    protected function configure(): void
    {
        $this
            ->setDescription(
                'Get bexio settings like currencies, bank accounts, languages, payment types,
taxes and users.'
            )
            ->addOption(
                'file',
                'f',
                InputOption::VALUE_REQUIRED,
                'Write the result in yaml format to the file specified by this option.'
            )
            ->addArgument(
                'site',
                InputArgument::REQUIRED,
                'Site identifier from the site for which the invoice should be created.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        try {
            $io->writeln('Getting bexio settings...');
            $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
            /** @var string $siteIdentifier */
            $siteIdentifier = $input->getArgument('site');
            $this->site = $siteFinder->getSiteByIdentifier($siteIdentifier);
            $settings = $this->getSettings();
            $result = json_encode($settings, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT| JSON_THROW_ON_ERROR);
            if (
                is_string($file = $input->getOption('file')) &&
                is_array($resultArray = json_decode($result, true)) &&
                file_put_contents($file, Yaml::dump($resultArray, 10, 2)) !== false
            ) {
                $io->writeln('Response written to file "' . $file . '"');
                return Command::SUCCESS;
            }
            $io->writeln('Response:');
            /** @var FormatterHelper $formatter */
            $formatter = $this->getHelper('formatter');
            $io->writeln($formatter->formatBlock(explode("\n", $result), 'info'));
            return Command::SUCCESS;
        } catch (\Exception $e) {
            /** @extensionScannerIgnoreLine */
            $io->error(sprintf('%s (%d)', $e->getMessage(), (int)$e->getCode()));
            return Command::FAILURE;
        }
    }

    protected function getSettings(): array
    {
        $client = GeneralUtility::makeInstance(ApiService::class)->initialize($this->site)->getClient();
        $accountingResource = new Accounting($client);
        $bankingResource = new Banking($client);
        $otherResource = new Other($client);
        /** @var \stdClass[] $taxes */
        $taxes = is_array($taxes = $accountingResource->getTaxes()) ? $taxes : [];
        return [
            'accounting' => [
                'currencies' => $this->mapResponse($accountingResource->getCurrencies(), 'id', 'name'),
                'taxes' => $this->mapResponse(
                    array_filter($taxes, static fn ($item) => $item->is_active === true),
                    'id',
                    'display_name'
                )
            ],
            'banking' => [
                'bankAccounts' => $this->mapResponse($bankingResource->getBankAccounts(), 'id', 'name, currency_id')
            ],
            'other' => [
                'languages' => $this->mapResponse($otherResource->getLanguages(), 'id', 'iso_639_1, name'),
                'paymentTypes' => $this->mapResponse($otherResource->getPaymentTypes(), 'id', 'name'),
                'users' => $this->mapResponse($otherResource->getUsers(), 'id', 'lastname, firstname')
            ],
        ];
    }

    protected function mapResponse(mixed $response, string $keyProperty, string $valueProperties): array
    {
        $result = [];
        if (!is_array($response)) {
            return $result;
        }
        $valueProperties = GeneralUtility::trimExplode(',', $valueProperties, true);
        foreach ($response as $item) {
            $values = array_map(static fn ($p) => $item->$p, $valueProperties);
            $result[$item->$keyProperty] = implode(', ', $values);
        }
        return $result;
    }
}
