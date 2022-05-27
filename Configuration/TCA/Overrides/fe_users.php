<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-bexio.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') or die('Access denied.');

(static function (): void {
    $columns = [
        'tx_bexio_id' => [
            'label' => 'LLL:EXT:bexio/Resources/Private/Language/locallang_db.xlf:fe_users.tx_bexio_id',
            'exclude' => 1,
            'config' => [
                'type' => 'input',
                'size' => 10,
                'eval' => 'num,trim'
            ],
        ],
        'tx_bexio_company_id' => [
            'label' => 'LLL:EXT:bexio/Resources/Private/Language/locallang_db.xlf:fe_users.tx_bexio_company_id',
            'exclude' => 1,
            'config' => [
                'type' => 'input',
                'size' => 10,
                'eval' => 'num,trim'
            ],
        ],
    ];
    $GLOBALS['TCA']['fe_users']['palettes']['bexio'] = [
        'showitem' => 'tx_bexio_id, tx_bexio_company_id',
    ];
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('fe_users', $columns);
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'fe_users',
        '--palette--;Bexio;bexio',
        '',
        'after:tx_extbase_type'
    );
})();
