<?php

namespace VincenzoRaco\Recurrences\DataObjects;

class EndingConditionTimesDataObject extends EndingConditionDataObject
{
    public function __construct(
        private readonly int $times,
    ) {}

    public function getUntil(): null
    {
        return null;
    }

    public function getTimes(): int
    {
        return $this->times;
    }
}
