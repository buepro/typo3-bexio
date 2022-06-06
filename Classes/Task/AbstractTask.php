<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-bexio.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Bexio\Task;

use Buepro\Bexio\Api\Client;
use Buepro\Bexio\Helper\InitializationTrait;
use Buepro\Bexio\Service\ApiService;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Utility\GeneralUtility;

abstract class AbstractTask
{
    use InitializationTrait;

    protected Site $site;
    protected Client $apiClient;

    /** @return self|TaskInterface */
    public function initialize(Site $site)
    {
        $this->site = $site;
        $this->apiClient = GeneralUtility::makeInstance(ApiService::class)->initialize($site)->getClient();
        return $this;
    }
}
