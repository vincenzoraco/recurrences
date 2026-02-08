<?php

use Illuminate\Support\Carbon;
use VincenzoRaco\Recurrences\DataObjects\ExcludeOccurrencesRangeDataObject;

describe('ExcludeOccurrencesRangeDataObject', function () {
    it('returns correct start date', function () {
        $startDate = Carbon::parse('2024-01-01');
        $endDate = Carbon::parse('2024-01-31');

        $dataObject = new ExcludeOccurrencesRangeDataObject($startDate, $endDate);

        expect($dataObject->getStartDate())->toBe($startDate);
    });

    it('returns correct end date', function () {
        $startDate = Carbon::parse('2024-01-01');
        $endDate = Carbon::parse('2024-01-31');

        $dataObject = new ExcludeOccurrencesRangeDataObject($startDate, $endDate);

        expect($dataObject->getEndDate())->toBe($endDate);
    });

    it('throws exception when end date is before start date', function () {
        expect(fn () => new ExcludeOccurrencesRangeDataObject(
            Carbon::parse('2024-01-31'),
            Carbon::parse('2024-01-01'),
        ))->toThrow(InvalidArgumentException::class, 'Start date must be before end date');
    });

    it('does not throw for valid dates', function () {
        $dataObject = new ExcludeOccurrencesRangeDataObject(
            Carbon::parse('2024-01-01'),
            Carbon::parse('2024-01-31'),
        );

        expect($dataObject->getStartDate())->not->toBeNull();
        expect($dataObject->getEndDate())->not->toBeNull();
    });

    it('creates RRule condition value', function () {
        $dataObject = new ExcludeOccurrencesRangeDataObject(
            Carbon::parse('2024-01-01'),
            Carbon::parse('2024-01-31'),
        );

        $rrule = $dataObject->getConditionValue();

        expect($rrule)->not->toBeNull();
    });
});
