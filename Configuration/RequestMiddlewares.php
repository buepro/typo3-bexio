<?php

/*
 * This file is part of the composer package buepro/typo3-bexio.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

return [
    'frontend' => [
        'buepro/bexio/auth' => [
            'target' => \Buepro\Bexio\Middleware\AuthMiddleware::class,
            'before' => [
                'typo3/cms-frontend/page-resolver',
            ],
            'after' => [
                'typo3/cms-frontend/static-route-resolver',
            ],
        ],
    ],
];
