<?php

namespace App\Services;

use App\Models\Jamaah\Jamaah;

class JamaahService
{

    private ?Jamaah $jamaah = null;
    public function __construct(
        private readonly int $tenantId
    )
    {}

    public function createJamaah(array $input)
    {

    }

    /**
     * @return Jamaah|null
     */
    public function getJamaah(): ?Jamaah
    {
        return $this->jamaah;
    }

    /**
     * @param Jamaah $jamaah
     * @return JamaahService
     */
    public function setJamaah(Jamaah $jamaah): static
    {
        $this->jamaah = $jamaah;
        return $this;
    }
}
