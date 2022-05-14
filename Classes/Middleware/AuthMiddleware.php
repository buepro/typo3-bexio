<?php

/*
 * This file is part of the composer package buepro/typo3-bexio.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Bexio\Middleware;

use Buepro\Bexio\Controller\AuthController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AuthMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (
            strpos($request->getRequestTarget(), 'bexio-auth') !== false &&
            ($site = $request->getAttribute('site')) instanceof Site &&
            ($challenge = trim($site->getConfiguration()['bexio']['authUrlSegmentChallenge'] ?? '')) !== '' &&
            strpos($request->getRequestTarget(), 'bexio-auth-' . $challenge) !== false
        ) {
            return GeneralUtility::makeInstance(AuthController::class)->authenticate($request);
        }
        return $handler->handle($request);
    }
}
