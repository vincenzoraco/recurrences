<?php

use Illuminate\Support\Carbon;
use VincenzoRaco\Recurrences\DataObjects\EndingConditionTimesDataObject;
use VincenzoRaco\Recurrences\DataObjects\EndingConditionUntilDataObject;
use VincenzoRaco\Recurrences\DataObjects\MultipleOccurrencesConditionDataObject;
use VincenzoRaco\Recurrences\DataObjects\NoEndingConditionDataObject;
use VincenzoRaco\Recurrences\Enums\RecurringFrequency;
use VincenzoRaco\Recurrences\Enums\RecurringWeekDay;

describe('MultipleOccurrencesConditionDataObject', function () {
    it('returns correct start date', function () {
        $start = Carbon::parse('2024-01-01');
        $dataObject = new MultipleOccurrencesConditionDataObject(
            $start,
            RecurringFrequency::WEEKLY,
            1,
            new NoEndingConditionDataObject,
            null,
        );

        expect($dataObject->getStart())->toBe($start);
    });

    it('returns correct frequency', function () {
        $dataObject = new MultipleOccurrencesConditionDataObject(
            Carbon::parse('2024-01-01'),
            RecurringFrequency::WEEKLY,
            1,
            new NoEndingConditionDataObject,
            null,
        );

        expect($dataObject->getFrequency())->toBe(RecurringFrequency::WEEKLY);
    });

    it('returns correct interval', function () {
        $dataObject = new MultipleOccurrencesConditionDataObject(
            Carbon::parse('2024-01-01'),
            RecurringFrequency::WEEKLY,
            2,
            new NoEndingConditionDataObject,
            null,
        );

        expect($dataObject->getInterval())->toBe(2);
    });

    it('returns correct ending condition', function () {
        $endingCondition = new EndingConditionTimesDataObject(10);
        $dataObject = new MultipleOccurrencesConditionDataObject(
            Carbon::parse('2024-01-01'),
            RecurringFrequency::WEEKLY,
            1,
            $endingCondition,
            null,
        );

        expect($dataObject->getEndingCondition())->toBe($endingCondition);
    });

    it('throws exception for interval less than 1', function () {
        expect(fn () => new MultipleOccurrencesConditionDataObject(
            Carbon::parse('2024-01-01'),
            RecurringFrequency::WEEKLY,
            0,
            new NoEndingConditionDataObject,
            null,
        ))->toThrow(InvalidArgumentException::class, 'Interval must be at least 1');
    });

    it('throws exception when until is before start', function () {
        expect(fn () => new MultipleOccurrencesConditionDataObject(
            Carbon::parse('2024-06-01'),
            RecurringFrequency::WEEKLY,
            1,
            new EndingConditionUntilDataObject(Carbon::parse('2024-01-01')),
            null,
        ))->toThrow(InvalidArgumentException::class, 'Start must be before until');
    });

    it('does not throw for valid until date', function () {
        $dataObject = new MultipleOccurrencesConditionDataObject(
            Carbon::parse('2024-01-01'),
            RecurringFrequency::WEEKLY,
            1,
            new EndingConditionUntilDataObject(Carbon::parse('2024-12-31')),
            null,
        );

        expect($dataObject->getStart())->not->toBeNull();
    });

    it('returns null byWeekDay when not provided', function () {
        $dataObject = new MultipleOccurrencesConditionDataObject(
            Carbon::parse('2024-01-01'),
            RecurringFrequency::WEEKLY,
            1,
            new NoEndingConditionDataObject,
            null,
        );

        expect($dataObject->getByWeekDay())->toBeNull();
    });

    it('returns correct byWeekDay values', function () {
        $days = [RecurringWeekDay::MONDAY, RecurringWeekDay::WEDNESDAY, RecurringWeekDay::FRIDAY];
        $dataObject = new MultipleOccurrencesConditionDataObject(
            Carbon::parse('2024-01-01'),
            RecurringFrequency::WEEKLY,
            1,
            new NoEndingConditionDataObject,
            $days,
        );

        expect($dataObject->getByWeekDay())->toBe($days);
    });

    it('includes BYDAY in condition value when byWeekDay is set', function () {
        $dataObject = new MultipleOccurrencesConditionDataObject(
            Carbon::parse('2024-01-01'),
            RecurringFrequency::WEEKLY,
            1,
            new EndingConditionTimesDataObject(10),
            [RecurringWeekDay::MONDAY, RecurringWeekDay::FRIDAY],
        );

        $rruleString = (string) $dataObject->getConditionValue();

        expect($rruleString)->toContain('BYDAY=MO,FR');
    });
});
