<?php

use VincenzoRaco\Recurrences\DataObjects\EndingConditionTimesDataObject;

describe('EndingConditionTimesDataObject', function () {
    it('returns null for getUntil', function () {
        $dataObject = new EndingConditionTimesDataObject(10);

        expect($dataObject->getUntil())->toBeNull();
    });

    it('returns the correct times value', function () {
        $dataObject = new EndingConditionTimesDataObject(10);

        expect($dataObject->getTimes())->toBe(10);
    });

    it('works with different times values', function () {
        $dataObject = new EndingConditionTimesDataObject(1);

        expect($dataObject->getTimes())->toBe(1);
    });
});
