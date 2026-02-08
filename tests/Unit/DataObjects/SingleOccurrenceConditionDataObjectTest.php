<?php

use Illuminate\Support\Carbon;
use VincenzoRaco\Recurrences\DataObjects\SingleOccurrenceConditionDataObject;

describe('SingleOccurrenceConditionDataObject', function () {
    it('returns the correct date', function () {
        $date = Carbon::parse('2024-06-15');
        $dataObject = new SingleOccurrenceConditionDataObject($date);

        expect($dataObject->getDate())->toBe($date);
    });

    it('returns correct condition value as date string', function () {
        $date = Carbon::parse('2024-06-15');
        $dataObject = new SingleOccurrenceConditionDataObject($date);

        expect($dataObject->getConditionValue())->toBe('2024-06-15');
    });
});
