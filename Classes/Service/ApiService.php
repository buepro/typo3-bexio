<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-bexio.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Bexio\Service;

use Buepro\Bexio\Api\Client;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ApiService
{
    protected ?Site $site = null;
    protected ?Client $client = null;

    public function initialize(Site $site): self
    {
        $this->site = $site;
        $this->client = null;
        return $this;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function getTokensFile(): ?string
    {
        if (($site = $this->getSite()) !== null) {
            $tokensDir = sprintf('%s/sites/%s/bexio', Environment::getConfigPath(), $site->getIdentifier());
            GeneralUtility::mkdir_deep($tokensDir);
            return $tokensDir . '/clientTokens.json';
        }
        return null;
    }

    public function getConfiguration(): ?array
    {
        if (
            ($site = $this->getSite()) !== null &&
            is_array($conf = $site->getConfiguration()['bexio'] ?? null)
        ) {
            return $conf;
        }
        return null;
    }

    public function getScopes(): array
    {
        if (($conf = $this->getConfiguration()) !== null) {
            return $conf['scopes'] ?? [];
        }
        return [];
    }

    public function getRedirectUrl(ServerRequestInterface $request): string
    {
        if (
            ($conf = $this->getConfiguration()) !== null &&
            ($authUrlSegmentChallenge = $conf['authUrlSegmentChallenge'] ?? '') !== ''
        ) {
            return rtrim($request->getAttribute('normalizedParams')->getRequestHost(), '/')
                . '/bexio-auth-' . $authUrlSegmentChallenge;
        }
        return '';
    }

    public function getClient(): Client
    {
        if ($this->client !== null) {
            return $this->client;
        }
        if (
            ($conf = $this->getConfiguration()) !== null &&
            ($clientId = $conf['clientId'] ?? '') !== '' &&
            ($clientSecret = $conf['clientSecret'] ?? '') !== ''
        ) {
            $this->client = new Client($clientId, $clientSecret);
            if (($tokensFile = $this->getTokensFile()) !== null) {
                $this->client->loadTokens($tokensFile);
            }
            return $this->client;
        }
        throw new \DomainException('No client obtained. Please check the configuration.', 1653555488);
    }
}
