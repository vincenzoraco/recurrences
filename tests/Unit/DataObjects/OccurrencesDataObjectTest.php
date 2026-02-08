<?php

use Illuminate\Support\Carbon;
use VincenzoRaco\Recurrences\DataObjects\OccurrencesDataObject;

describe('OccurrencesDataObject', function () {
    it('returns the occurrences collection', function () {
        $occurrences = collect([
            Carbon::parse('2024-01-01'),
            Carbon::parse('2024-01-02'),
        ]);

        $dataObject = new OccurrencesDataObject($occurrences);

        expect($dataObject->getOccurrences())->toBe($occurrences);
        expect($dataObject->getOccurrences()->count())->toBe(2);
    });

    it('returns empty collection when no occurrences', function () {
        $occurrences = collect([]);

        $dataObject = new OccurrencesDataObject($occurrences);

        expect($dataObject->getOccurrences())->toBeEmpty();
    });
});
