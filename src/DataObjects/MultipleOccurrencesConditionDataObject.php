<?php

namespace VincenzoRaco\Recurrences\DataObjects;

use Illuminate\Support\Carbon;
use InvalidArgumentException;
use RRule\RRule;
use VincenzoRaco\Recurrences\Enums\RecurringFrequency;
use VincenzoRaco\Recurrences\Enums\RecurringWeekDay;

class MultipleOccurrencesConditionDataObject extends DataObject
{
    /**
     * @param  array<RecurringWeekDay>|null  $byWeekDay
     */
    public function __construct(
        private readonly Carbon $start,
        private readonly RecurringFrequency $frequency,
        private readonly int $interval,
        private readonly NoEndingConditionDataObject|EndingConditionUntilDataObject|EndingConditionTimesDataObject $endingCondition,
        private readonly ?array $byWeekDay,
    ) {
        $this->validate();
    }

    public function getStart(): Carbon
    {
        return $this->start;
    }

    public function getFrequency(): RecurringFrequency
    {
        return $this->frequency;
    }

    public function getInterval(): int
    {
        return $this->interval;
    }

    public function getEndingCondition(): NoEndingConditionDataObject|EndingConditionUntilDataObject|EndingConditionTimesDataObject
    {
        return $this->endingCondition;
    }

    /**
     * @return array<RecurringWeekDay>|null
     */
    public function getByWeekDay(): ?array
    {
        return $this->byWeekDay;
    }

    public function getConditionValue(): RRule
    {
        return new RRule(array_filter([
            'DTSTART' => $this->getStart()->format('Y-m-d'),
            'FREQ' => $this->getFrequency()->value,
            'INTERVAL' => $this->getInterval(),
            // If UNTIL and COUNT are NULL the RRULE never ends
            'UNTIL' => $this->getEndingCondition()->getUntil()?->format('Y-m-d'),
            'COUNT' => $this->getEndingCondition()->getTimes(),
            'BYDAY' => $this->getByWeekDay() ? array_map(fn (RecurringWeekDay $day) => $day->value, $this->getByWeekDay()) : null,
        ]));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function validate(): void
    {
        if ($this->getInterval() < 1) {
            throw new InvalidArgumentException('Interval must be at least 1');
        }

        $until = $this->getEndingCondition()->getUntil();

        if (! is_null($until) && $until->isBefore($this->getStart())) {
            throw new InvalidArgumentException('Start must be before until');
        }
    }
}
