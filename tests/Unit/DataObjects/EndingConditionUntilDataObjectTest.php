<?php

use Illuminate\Support\Carbon;
use VincenzoRaco\Recurrences\DataObjects\EndingConditionUntilDataObject;

describe('EndingConditionUntilDataObject', function () {
    it('returns the correct until date', function () {
        $until = Carbon::parse('2024-12-31');
        $dataObject = new EndingConditionUntilDataObject($until);

        expect($dataObject->getUntil())->toBe($until);
    });

    it('returns null for getTimes', function () {
        $until = Carbon::parse('2024-12-31');
        $dataObject = new EndingConditionUntilDataObject($until);

        expect($dataObject->getTimes())->toBeNull();
    });
});
