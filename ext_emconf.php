<?php

/*
 * This file is part of the composer package buepro/typo3-bexio.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

$EM_CONF[$_EXTKEY] = [
    'title'            => 'Bexio',
    'description'      => 'Provides a client for the Bexio API by extending the client from the package onlime/bexio-api-client',
    'category'         => 'backend',
    'version'          => '0.1.0',
    'state'            => 'alpha',
    'clearCacheOnLoad' => 1,
    'author'           => 'Roman Büchler',
    'author_email'     => 'rb@buechler.pro',
    'constraints'      => [
        'depends'   => [
            'php'    => '8.0.0-8.0.99',
            'typo3'  => '11.5.9-11.5.99',
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'Buepro\\Bexio\\' => 'Classes',
        ],
    ],
];
