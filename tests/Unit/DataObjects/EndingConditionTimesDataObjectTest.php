<?php

use VincenzoRaco\Recurrences\DataObjects\EndingConditionTimesDataObject;

describe('EndingConditionTimesDataObject', function () {
    it('throws exception when times is less than 1', function () {
        new EndingConditionTimesDataObject(0);
    })->throws(InvalidArgumentException::class, 'Times must be at least 1');

    it('throws exception when times is negative', function () {
        new EndingConditionTimesDataObject(-5);
    })->throws(InvalidArgumentException::class, 'Times must be at least 1');

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
