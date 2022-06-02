<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-bexio.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Bexio\Api\Resource;

use Bexio\Bexio;

class Banking extends Bexio
{
    /**
     * Fetch a list of bank accounts
     *
     * @link https://docs.bexio.com/#tag/Bank-Accounts/operation/ListBankAccounts
     */
    public function getBankAccounts(array $params = []): array
    {
        return is_array($result = $this->client->get('3.0/banking/accounts', $params)) ? $result : [];
    }

    /**
     * Fetch a single bank account
     *
     * @link https://docs.bexio.com/#tag/Bank-Accounts/operation/ShowBankAccount
     */
    public function getBankAccount(int $id): ?\stdClass
    {
        try {
            return (($result = $this->client->get('3.0/banking/accounts/' . $id)) instanceof \stdClass) ? $result : null;
        } catch (\Exception $e) {
            // The bank account doesn't exist
            return null;
        }
    }
}
