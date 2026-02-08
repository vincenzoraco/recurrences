<?php

namespace VincenzoRaco\Recurrences\DataObjects;

use Illuminate\Support\Carbon;

class EndingConditionUntilDataObject extends EndingConditionDataObject
{
    public function __construct(
        private readonly Carbon $until,
    ) {}

    public function getUntil(): Carbon
    {
        return $this->until;
    }

    public function getTimes(): null
    {
        return null;
    }
}
