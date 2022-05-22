<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-bexio.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Bexio\Controller;

use Buepro\Bexio\Service\ApiService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\ResponseFactory;
use TYPO3\CMS\Core\Site\Entity\Site;

class AuthController
{
    protected ResponseFactory $responseFactory;
    protected ApiService $apiService;

    public function __construct(ResponseFactory $responseFactory, ApiService $apiService)
    {
        $this->responseFactory = $responseFactory;
        $this->apiService = $apiService;
    }

    public function authenticate(ServerRequestInterface $request): ResponseInterface
    {
        if (
            ($site = $request->getAttribute('site')) instanceof Site &&
            ($client = $this->apiService->initialize($site)->getClient()) !== null &&
            ($tokensFile = $this->apiService->getTokensFile()) !== null
        ) {
            $client->authenticate($this->apiService->getScopes(), $this->apiService->getRedirectUrl($request));
            $client->persistTokens($tokensFile);
            return $this->getResponse('You have been authenticated and can now use the services.');
        }
        return $this->getResponse('Authentication failed.');
    }

    public function getResponse(string $text): ResponseInterface
    {
        $response = $this->responseFactory->createResponse()
            ->withHeader('Content-Type', 'text/plain; charset=utf-8');
        $response->getBody()->write($text);
        return $response;
    }
}
