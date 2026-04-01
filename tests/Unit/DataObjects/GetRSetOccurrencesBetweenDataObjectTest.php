<?php

use Illuminate\Support\Carbon;
use VincenzoRaco\Recurrences\DataObjects\GetRSetOccurrencesBetweenDataObject;

describe('GetRSetOccurrencesBetweenDataObject', function () {
    it('returns correct start date', function () {
        $startDate = Carbon::parse('2024-01-01');
        $endDate = Carbon::parse('2024-01-31');

        $dataObject = new GetRSetOccurrencesBetweenDataObject($startDate, $endDate, null);

        expect($dataObject->getStartDate())->toBe($startDate);
    });

    it('returns correct end date', function () {
        $startDate = Carbon::parse('2024-01-01');
        $endDate = Carbon::parse('2024-01-31');

        $dataObject = new GetRSetOccurrencesBetweenDataObject($startDate, $endDate, null);

        expect($dataObject->getEndDate())->toBe($endDate);
    });

    it('throws exception when end date is before start date', function () {
        expect(fn () => new GetRSetOccurrencesBetweenDataObject(
            Carbon::parse('2024-01-31'),
            Carbon::parse('2024-01-01'),
            null,
        ))->toThrow(InvalidArgumentException::class, 'Start date must be before end date');
    });

    it('does not throw for same dates', function () {
        $date = Carbon::parse('2024-01-15');

        $dataObject = new GetRSetOccurrencesBetweenDataObject($date, $date, null);

        expect($dataObject->getStartDate())->toBe($date);
        expect($dataObject->getEndDate())->toBe($date);
    });
});
