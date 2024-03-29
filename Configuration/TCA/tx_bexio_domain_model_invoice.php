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
        'label_alt' => 'document_nr, reference, total, is_valid_to',
        'label_alt_force' => 1,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'enablecolumns' => [
        ],
        'searchFields' => 'title, document_nr, total, reference',
        'default_sortby' => 'id desc',
        'iconfile' => 'EXT:bexio/Resources/Public/Icons/domain-model-invoice.svg',
        'security' => [
            'ignorePageTypeRestriction' => true,
        ],
    ],
    'palettes' => [
        'header' => [
            'showitem' => 'title, kb_item_status_id',
        ],
        'dates' => [
            'showitem' => 'is_valid_from, is_valid_to, --linebreak--, viewed_by_client_at',
        ],
        'ids' => [
            'showitem' => 'id, document_nr, --linebreak--, esr_id, qr_invoice_id',
        ],
        'refs' => [
            'showitem' => 'reference, api_reference',
        ],
        'payment' => [
            'showitem' => 'total, currency_id, --linebreak--, bank_account_id, payment_process_time',
        ],
    ],
    'types' => [
        '1' => [
            'showitem' => 'user, --palette--;;header, --palette--;;payment, --palette--;;dates, --palette--;;ids, ' .
                '--palette--;;refs, language_id, network_link',
        ],
    ],
    'columns' => [
        'id' => [
            'exclude' => true,
            'label' => 'LLL:EXT:bexio/Resources/Private/Language/locallang_db.xlf:tx_bexio_domain_model_invoice.id',
            'config' => [
                'readOnly' => 1,
                'type' => 'input',
            ],
        ],
        'user' => [
            'exclude' => true,
            'label' => 'LLL:EXT:bexio/Resources/Private/Language/locallang_db.xlf:tx_bexio_domain_model_invoice.user',
            'config' => [
                'readOnly' => 1,
                'type' => 'inline',
                'foreign_table' => 'fe_users',
                'foreign_label' => 'company',
                'maxitems' => 1,
                'appearance' => [
                    'collapseAll' => 1,
                ],
                'behaviour' => [
                    'disableMovingChildrenWithParent' => true,
                    'enableCascadingDelete' => false,
                ],
            ],
        ],
        'title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:bexio/Resources/Private/Language/locallang_db.xlf:tx_bexio_domain_model_invoice.title',
            'config' => [
                'readOnly' => 1,
                'type' => 'input',
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
                'type' => 'datetime',
                'size' => 14,
                'default' => 0,
            ],
        ],
        'is_valid_to' => [
            'exclude' => true,
            'label' => 'LLL:EXT:bexio/Resources/Private/Language/locallang_db.xlf:tx_bexio_domain_model_invoice.is_valid_to',
            'config' => [
                'readOnly' => 1,
                'type' => 'datetime',
                'size' => 14,
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
                'type' => 'datetime',
                'size' => 14,
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
        'payment_process_time' => [
            'exclude' => true,
            'label' => 'LLL:EXT:bexio/Resources/Private/Language/locallang_db.xlf:tx_bexio_domain_model_invoice.payment_process_time',
            'config' => [
                'readOnly' => 1,
                'type' => 'datetime',
                'size' => 14,
                'default' => 0,
            ],
        ],
    ],
];
