<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-bexio.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

return [
    \Buepro\Bexio\Domain\Model\User::class => [
        'tableName' => 'fe_users',
        'properties' => [
            'bexioId' => [
                'fieldName' => 'tx_bexio_id',
            ],
            'bexioCompanyId' => [
                'fieldName' => 'tx_bexio_company_id',
            ],
            'bexioLanguageId' => [
                'fieldName' => 'tx_bexio_language_id',
            ],
            'bexioInvoices' => [
                'fieldName' => 'tx_bexio_invoices',
            ],
        ],
    ],
];
