<?php

namespace Buepro\Bexio\Helper;

trait InitializationTrait
{
    private ?bool $initialized = null;

    protected function setInitialized(bool $initialized): void
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
