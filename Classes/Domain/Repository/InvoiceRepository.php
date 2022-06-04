<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-bexio.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Bexio\Domain\Repository;

use Buepro\Bexio\Domain\Model\Invoice;
use TYPO3\CMS\Extbase\Persistence\Generic\Query;

class InvoiceRepository extends AbstractRepository
{
    /** @return Invoice[] */
    public function getAllPending(): array
    {
        $query = $this->createQuery();
        return $query
            ->matching($query->equals('kbItemStatusId', Invoice::STATUS_OPEN))
            ->setOrderings(['isValidFrom' => Query::ORDER_ASCENDING])
            ->execute()
            ->toArray();
    }
}
