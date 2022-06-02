<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-bexio.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Bexio\Task;

use Buepro\Bexio\Helper\InitializationTrait;
use TYPO3\CMS\Core\Site\Entity\Site;

abstract class AbstractTask
{
    use InitializationTrait;

    protected Site $site;

    public function __construct(Site $site)
    {
        $this->site = $site;
    }
}
