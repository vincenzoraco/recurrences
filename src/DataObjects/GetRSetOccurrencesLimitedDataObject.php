<?php

namespace VincenzoRaco\Recurrences\DataObjects;

use InvalidArgumentException;

class GetRSetOccurrencesLimitedDataObject extends DataObject
{
    public function __construct(
        private readonly int $times,
    ) {
        $this->validate();
    }

    /**
     * @throws InvalidArgumentException
     */
    private function validate(): void
    {
        if ($this->times < 1) {
            throw new InvalidArgumentException('Times must be at least 1');
        }
    }

    public function getTimes(): int
    {
        return $this->times;
    }
}
