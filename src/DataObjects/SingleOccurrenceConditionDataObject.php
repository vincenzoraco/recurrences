<?php

namespace VincenzoRaco\Recurrences\DataObjects;

use Illuminate\Support\Carbon;

class SingleOccurrenceConditionDataObject extends DataObject
{
    public function __construct(
        private readonly Carbon $date,
    ) {}

    public function getDate(): Carbon
    {
        return $this->date;
    }

    public function getConditionValue(): string
    {
        return $this->getDate()->toDateString();
    }
}
