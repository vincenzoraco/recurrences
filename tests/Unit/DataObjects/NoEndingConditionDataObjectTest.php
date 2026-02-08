<?php

use VincenzoRaco\Recurrences\DataObjects\NoEndingConditionDataObject;

describe('NoEndingConditionDataObject', function () {
    it('returns null for getUntil', function () {
        $dataObject = new NoEndingConditionDataObject;

        expect($dataObject->getUntil())->toBeNull();
    });

    it('returns null for getTimes', function () {
        $dataObject = new NoEndingConditionDataObject;

        expect($dataObject->getTimes())->toBeNull();
    });
});
