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
        'tx_bexio_language_id' => [
            'label' => 'LLL:EXT:bexio/Resources/Private/Language/locallang_db.xlf:fe_users.tx_bexio_language_id',
            'exclude' => 1,
            'config' => [
                'type' => 'input',
                'size' => 10,
                'eval' => 'num,trim'
            ],
        ],
        'tx_bexio_invoices' => [
            'label' => 'LLL:EXT:bexio/Resources/Private/Language/locallang_db.xlf:fe_users.tx_bexio_invoices',
            'exclude' => 1,
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_bexio_domain_model_invoice',
                'foreign_field' => 'user',
                'maxitems' => 9999,
                'appearance' => [
                    'collapseAll' => 1,
                    'enabledControls' => [
                        'info' => false,
                        'new' => false,
                        'dragdrop' => false,
                        'sort' => false,
                        'hide' => false,
                        'delete' => false,
                        'localize' => false,
                    ],
                ],
            ],
        ],
    ];
    $GLOBALS['TCA']['fe_users']['palettes']['bexio'] = [
        'showitem' => 'tx_bexio_id, tx_bexio_company_id, tx_bexio_language_id, --linebreak--,tx_bexio_invoices',
    ];
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('fe_users', $columns);
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'fe_users',
        '--palette--;Bexio;bexio',
        '',
        'after:tx_extbase_type'
    );
})();
