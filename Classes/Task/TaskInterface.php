<?php

/*
 * This file is part of the composer package buepro/typo3-bexio.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Bexio\Task;

use TYPO3\CMS\Core\Site\Entity\Site;

interface TaskInterface
{
    public function initialize(Site $site): self;
    public function process(): array;
}
