<?php

use VincenzoRaco\Recurrences\Enums\RecurringConditionType;

describe('RecurringConditionType', function () {
    it('returns correct rset method for ADD_RRULE', function () {
        expect(RecurringConditionType::ADD_RRULE->getRsetMethod())->toBe('addRRule');
    });

    it('returns correct rset method for ADD_EX_RRULE', function () {
        expect(RecurringConditionType::ADD_EX_RRULE->getRsetMethod())->toBe('addExRule');
    });

    it('returns correct rset method for ADD_DATE', function () {
        expect(RecurringConditionType::ADD_DATE->getRsetMethod())->toBe('addDate');
    });

    it('returns correct rset method for ADD_EX_DATE', function () {
        expect(RecurringConditionType::ADD_EX_DATE->getRsetMethod())->toBe('addExDate');
    });

    it('returns true for isDate when ADD_DATE', function () {
        expect(RecurringConditionType::ADD_DATE->isDate())->toBeTrue();
    });

    it('returns true for isDate when ADD_EX_DATE', function () {
        expect(RecurringConditionType::ADD_EX_DATE->isDate())->toBeTrue();
    });

    it('returns false for isDate when ADD_RRULE', function () {
        expect(RecurringConditionType::ADD_RRULE->isDate())->toBeFalse();
    });

    it('returns false for isDate when ADD_EX_RRULE', function () {
        expect(RecurringConditionType::ADD_EX_RRULE->isDate())->toBeFalse();
    });

    it('has correct string values', function () {
        expect(RecurringConditionType::ADD_RRULE->value)->toBe('ADD_RRULE');
        expect(RecurringConditionType::ADD_EX_RRULE->value)->toBe('ADD_EX_RRULE');
        expect(RecurringConditionType::ADD_DATE->value)->toBe('ADD_DATE');
        expect(RecurringConditionType::ADD_EX_DATE->value)->toBe('ADD_EX_DATE');
    });
});
