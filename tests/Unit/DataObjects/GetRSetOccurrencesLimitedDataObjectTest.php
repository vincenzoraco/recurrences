<?php

use VincenzoRaco\Recurrences\DataObjects\GetRSetOccurrencesLimitedDataObject;

describe('GetRSetOccurrencesLimitedDataObject', function () {
    it('returns correct times value', function () {
        $dataObject = new GetRSetOccurrencesLimitedDataObject(10);

        expect($dataObject->getTimes())->toBe(10);
    });

    it('throws exception for times less than 1', function () {
        expect(fn () => new GetRSetOccurrencesLimitedDataObject(0))
            ->toThrow(InvalidArgumentException::class, 'Times must be at least 1');
    });

    it('throws exception for negative times', function () {
        expect(fn () => new GetRSetOccurrencesLimitedDataObject(-1))
            ->toThrow(InvalidArgumentException::class, 'Times must be at least 1');
    });

    it('does not throw for times equal to 1', function () {
        $dataObject = new GetRSetOccurrencesLimitedDataObject(1);

        expect($dataObject->getTimes())->toBe(1);
    });

    it('works with large times values', function () {
        $dataObject = new GetRSetOccurrencesLimitedDataObject(1000);

        expect($dataObject->getTimes())->toBe(1000);
    });
});
