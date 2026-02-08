<?php

namespace VincenzoRaco\Recurrences\DataObjects;

use Illuminate\Support\Carbon;
use InvalidArgumentException;

class GetRSetOccurrencesBetweenDataObject extends DataObject
{
    public function __construct(
        private readonly Carbon $startDate,
        private readonly Carbon $endDate,
    ) {
        $this->validate();
    }

    public function getStartDate(): Carbon
    {
        return $this->startDate;
    }

    public function getEndDate(): Carbon
    {
        return $this->endDate;
    }

    /**
     * @throws InvalidArgumentException
     */
    private function validate(): void
    {
        if ($this->endDate->isBefore($this->startDate)) {
            throw new InvalidArgumentException('Start date must be before end date');
        }
    }
}
