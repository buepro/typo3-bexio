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
    protected ?ServerRequestInterface $request = null;
    protected ?Client $client = null;

    public function initialize(ServerRequestInterface $request): self
    {
        $this->request = $request;
        return $this;
    }

    public function getRequest(): ?ServerRequestInterface
    {
        return $this->request;
    }

    public function getSite(): ?Site
    {
        if (
            ($request = $this->getRequest()) !== null &&
            ($site = $request->getAttribute('site')) instanceof Site
        ) {
            return $site;
        }
        return null;
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

    public function getRedirectUrl(): string
    {
        if (
            ($request = $this->getRequest()) !== null &&
            ($conf = $this->getConfiguration()) !== null &&
            ($authUrlSegmentChallenge = $conf['authUrlSegmentChallenge'] ?? '') !== ''
        ) {
            return rtrim($request->getAttribute('normalizedParams')->getRequestHost(), '/')
                . '/bexio-auth-' . $authUrlSegmentChallenge;
        }
        return '';
    }

    public function getClient(): ?Client
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
        return null;
    }
}
