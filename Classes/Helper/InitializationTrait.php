<?php

/*
 * This file is part of the composer package buepro/typo3-bexio.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Bexio\Helper;

trait InitializationTrait
{
    private ?bool $initialized = null;

    protected function setInitialized(bool $initialized = true): void
    {
        $this->initialized = $initialized;
    }

    protected function getInitialized(): bool
    {
        return $this->initialized === true;
    }

    protected function assertInitialized(): void
    {
        $this->initialized ?? throw new \DomainException('The object has not been initialized.', 1654169945);
    }
}
