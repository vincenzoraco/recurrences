<?php

namespace VincenzoRaco\Recurrences\DataObjects;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class OccurrencesDataObject extends DataObject
{
    /**
     * @param  Collection<Carbon>  $occurrences
     */
    public function __construct(
        private readonly Collection $occurrences,
    ) {}

    /**
     * @return Collection<Carbon>
     */
    public function getOccurrences(): Collection
    {
        return $this->occurrences;
    }
}
