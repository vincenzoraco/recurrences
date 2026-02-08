<?php

namespace VincenzoRaco\Recurrences\DataObjects;

use Illuminate\Support\Carbon;
use InvalidArgumentException;
use RRule\RRule;
use VincenzoRaco\Recurrences\Enums\RecurringFrequency;

class ExcludeOccurrencesRangeDataObject extends DataObject
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

    public function getConditionValue(): RRule
    {
        return new RRule([
            'DTSTART' => $this->getStartDate()->toDateString(),
            'FREQ' => RecurringFrequency::DAILY->value,
            'INTERVAL' => 1,
            'UNTIL' => $this->getEndDate()->toDateString(),
        ]);
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
