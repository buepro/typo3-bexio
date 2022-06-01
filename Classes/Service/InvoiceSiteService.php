<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-bexio.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Bexio\Service;

use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Utility\ArrayUtility;

class InvoiceSiteService
{
    protected Site $site;

    public function __construct(Site $site)
    {
        $this->site = $site;
    }

    public function getNewBase(array $overwrite = []): array
    {
        return $this->getNewItem('bexio.invoice.new', $overwrite);
    }

    public function getNewPosition(array $overwrite = []): array
    {
        return $this->getNewItem('bexio.invoice.position.new', $overwrite);
    }

    protected function getNewItem(string $configPath, array $overwrite = []): array
    {
        $config = ArrayUtility::getValueByPath($this->site->getConfiguration(), $configPath, '.');
        if (!is_array($config)) {
            return $overwrite;
        }
        return array_replace($config, $overwrite);
    }
}
