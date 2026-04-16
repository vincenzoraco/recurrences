<?php

namespace VincenzoRaco\Recurrences\DataObjects;

class EndingConditionTimesDataObject extends EndingConditionDataObject
{
    public function __construct(
        private readonly int $times,
    ) {
        if ($this->times < 1) {
            throw new \InvalidArgumentException('Times must be at least 1');
        }
    }

    public function getUntil(): null
    {
        return null;
    }

    public function getTimes(): int
    {
        return $this->times;
    }
}
