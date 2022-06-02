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
use Buepro\Bexio\Helper\InitializationTrait;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ApiService
{
    use InitializationTrait;

    protected ?Site $site = null;
    protected ?Client $client = null;
    protected array $authConfig = [];
    protected string $tokensFileNameWithPath = '';

    public function initialize(Site $site): self
    {
        $this->site = $site;
        $this->client = null;
        $this->authConfig = $site->getConfiguration()['bexio']['auth'] ?? [];
        $this->tokensFileNameWithPath = sprintf(
            '%s/sites/%s/bexio/clientTokens.json',
            Environment::getConfigPath(),
            $site->getIdentifier()
        );
        $this->setInitialized();
        return $this;
    }

    public function getClient(): Client
    {
        $this->assertInitialized();
        if ($this->client !== null) {
            return $this->client;
        }
        if (
            ($clientId = $this->authConfig['clientId'] ?? '') !== '' &&
            ($clientSecret = $this->authConfig['clientSecret'] ?? '') !== ''
        ) {
            $this->client = new Client($clientId, $clientSecret);
            if (file_exists($this->tokensFileNameWithPath)) {
                $this->client->loadTokens($this->tokensFileNameWithPath);
            }
            return $this->client;
        }
        throw new \DomainException('No client obtained. Please check the authentication and configuration.', 1653555488);
    }

    public function authenticate(ServerRequestInterface $request): void
    {
        $this->assertInitialized();
        $client = $this->getClient();
        $client->authenticate($this->getScopes(), $this->getRedirectUrl($request));
        $tokensDir = GeneralUtility::dirname($this->tokensFileNameWithPath);
        GeneralUtility::mkdir_deep($tokensDir);
        $client->persistTokens($this->tokensFileNameWithPath);
    }

    protected function getScopes(): array
    {
        return $this->authConfig['scopes'] ?? [];
    }

    protected function getRedirectUrl(ServerRequestInterface $request): string
    {
        if (($urlSegmentChallenge = $this->authConfig['urlSegmentChallenge'] ?? '') !== '') {
            return rtrim($request->getAttribute('normalizedParams')->getRequestHost(), '/')
                . '/bexio-auth-' . $urlSegmentChallenge;
        }
        return '';
    }
}
