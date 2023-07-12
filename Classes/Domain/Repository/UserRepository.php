<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-bexio.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Bexio\Domain\Repository;

use Buepro\Bexio\Domain\Model\User;

class UserRepository extends AbstractRepository
{
    public function findOneByProperties(array $properties): ?User
    {
        $query = $this->createQuery();
        $constraints = [];
        foreach ($properties as $property => $value) {
            $constraints[] = $query->equals($property, $value);
        }
        /** @var User[] $users */
        $users = $query->matching($query->logicalAnd(...$constraints))->execute()->toArray();
        if (count($users) === 1) {
            return $users[0];
        }
        return null;
    }
}
