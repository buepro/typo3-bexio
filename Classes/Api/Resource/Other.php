<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-bexio.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Bexio\Api\Resource;

class Other extends \Bexio\Resource\Other
{
    /**
     * Fetch a list of users
     *
     * @link https://docs.bexio.com/#tag/User-Management/operation/v3ListUsers
     */
    public function getUsers(array $params = []): array
    {
        return is_array($result = $this->client->get('3.0/users', $params)) ? $result : [];
    }
}
