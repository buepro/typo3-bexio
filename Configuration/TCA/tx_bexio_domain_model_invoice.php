<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-bexio.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

return [
    'ctrl' => [
        'title' => 'LLL:EXT:bexio/Resources/Private/Language/locallang_db.xlf:tx_bexio_domain_model_invoice',
        'label' => 'title',
        'label_alt' => 'user, title, document_nr, reference',
        'label_alt_force' => 1,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'enablecolumns' => [
        ],
        'searchFields' => 'title, document_nr, total, reference',
        'default_sortby' => 'tstamp DESC',
        'iconfile' => 'EXT:bexio/Resources/Public/Icons/domain-model-invoice.svg'
    ],
    'palettes' => [
        'document_refs' => [
            'showitem' => 'document_nr, esr_id, qr_invoice_id, --linebreak--, language_id, kb_item_status_id, ' .
                '--linebreak--, reference, api_reference',
        ],
        'payment' => [
            'showitem' => 'bank_account_id, currency_id, total',
        ],
        'dates' => [
            'showitem' => 'is_valid_from, is_valid_to, viewed_by_client_at',
        ],
    ],
    'types' => [
        '1' => [
            'showitem' => 'user, title, --palette--;;document_refs, --palette--;;payment, network_link',
        ],
    ],
    'columns' => [
        'user' => [
            'exclude' => true,
            'label' => 'LLL:EXT:bexio/Resources/Private/Language/locallang_db.xlf:tx_bexio_domain_model_invoice.user',
            'config' => [
                'readOnly' => 1,
                'type' => 'inline',
                'foreign_table' => 'fe_users',
                'foreign_field' => 'uid',
                'maxitems' => 1,
                'appearance' => [
                    'collapseAll' => 1,
                ],
            ],
        ],
        'title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:bexio/Resources/Private/Language/locallang_db.xlf:tx_bexio_domain_model_invoice.title',
            'config' => [
                'readOnly' => 1,
                'type' => 'input',
                'size' => 100,
            ],
        ],
        'document_nr' => [
            'exclude' => true,
            'label' => 'LLL:EXT:bexio/Resources/Private/Language/locallang_db.xlf:tx_bexio_domain_model_invoice.document_nr',
            'config' => [
                'readOnly' => 1,
                'type' => 'input',
            ],
        ],
        'language_id' => [
            'exclude' => true,
            'label' => 'LLL:EXT:bexio/Resources/Private/Language/locallang_db.xlf:tx_bexio_domain_model_invoice.language_id',
            'config' => [
                'readOnly' => 1,
                'type' => 'input',
            ],
        ],
        'bank_account_id' => [
            'exclude' => true,
            'label' => 'LLL:EXT:bexio/Resources/Private/Language/locallang_db.xlf:tx_bexio_domain_model_invoice.bank_account_id',
            'config' => [
                'readOnly' => 1,
                'type' => 'input',
            ],
        ],
        'currency_id' => [
            'exclude' => true,
            'label' => 'LLL:EXT:bexio/Resources/Private/Language/locallang_db.xlf:tx_bexio_domain_model_invoice.currency_id',
            'config' => [
                'readOnly' => 1,
                'type' => 'input',
            ],
        ],
        'total' => [
            'exclude' => true,
            'label' => 'LLL:EXT:bexio/Resources/Private/Language/locallang_db.xlf:tx_bexio_domain_model_invoice.total',
            'config' => [
                'readOnly' => 1,
                'type' => 'input',
            ],
        ],
        'is_valid_from' => [
            'exclude' => true,
            'label' => 'LLL:EXT:bexio/Resources/Private/Language/locallang_db.xlf:tx_bexio_domain_model_invoice.is_valid_from',
            'config' => [
                'readOnly' => 1,
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 14,
                'eval' => 'datetime',
                'default' => 0,
            ],
        ],
        'is_valid_to' => [
            'exclude' => true,
            'label' => 'LLL:EXT:bexio/Resources/Private/Language/locallang_db.xlf:tx_bexio_domain_model_invoice.is_valid_to',
            'config' => [
                'readOnly' => 1,
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 14,
                'eval' => 'datetime',
                'default' => 0,
            ],
        ],
        'kb_item_status_id' => [
            'exclude' => true,
            'label' => 'LLL:EXT:bexio/Resources/Private/Language/locallang_db.xlf:tx_bexio_domain_model_invoice.kb_item_status_id',
            'config' => [
                'readOnly' => 1,
                'type' => 'input',
            ],
        ],
        'reference' => [
            'exclude' => true,
            'label' => 'LLL:EXT:bexio/Resources/Private/Language/locallang_db.xlf:tx_bexio_domain_model_invoice.reference',
            'config' => [
                'readOnly' => 1,
                'type' => 'input',
                'size' => 50,
            ],
        ],
        'api_reference' => [
            'exclude' => true,
            'label' => 'LLL:EXT:bexio/Resources/Private/Language/locallang_db.xlf:tx_bexio_domain_model_invoice.api_reference',
            'config' => [
                'readOnly' => 1,
                'type' => 'input',
            ],
        ],
        'viewed_by_client_at' => [
            'exclude' => true,
            'label' => 'LLL:EXT:bexio/Resources/Private/Language/locallang_db.xlf:tx_bexio_domain_model_invoice.viewed_by_client_at',
            'config' => [
                'readOnly' => 1,
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 14,
                'eval' => 'datetime',
                'default' => 0,
            ],
        ],
        'esr_id' => [
            'exclude' => true,
            'label' => 'LLL:EXT:bexio/Resources/Private/Language/locallang_db.xlf:tx_bexio_domain_model_invoice.esr_id',
            'config' => [
                'readOnly' => 1,
                'type' => 'input',
            ],
        ],
        'qr_invoice_id' => [
            'exclude' => true,
            'label' => 'LLL:EXT:bexio/Resources/Private/Language/locallang_db.xlf:tx_bexio_domain_model_invoice.qr_invoice_id',
            'config' => [
                'readOnly' => 1,
                'type' => 'input',
            ],
        ],
        'network_link' => [
            'exclude' => true,
            'label' => 'LLL:EXT:bexio/Resources/Private/Language/locallang_db.xlf:tx_bexio_domain_model_invoice.network_link',
            'config' => [
                'readOnly' => 1,
                'type' => 'input',
                'size' => 100,
            ],
        ],
    ],
];
