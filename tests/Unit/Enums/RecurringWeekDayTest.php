<?php

use VincenzoRaco\Recurrences\Enums\RecurringWeekDay;

describe('RecurringWeekDay', function () {
    it('has all expected values', function () {
        expect(RecurringWeekDay::MONDAY->value)->toBe('MO');
        expect(RecurringWeekDay::TUESDAY->value)->toBe('TU');
        expect(RecurringWeekDay::WEDNESDAY->value)->toBe('WE');
        expect(RecurringWeekDay::THURSDAY->value)->toBe('TH');
        expect(RecurringWeekDay::FRIDAY->value)->toBe('FR');
        expect(RecurringWeekDay::SATURDAY->value)->toBe('SA');
        expect(RecurringWeekDay::SUNDAY->value)->toBe('SU');
    });

    it('has exactly seven cases', function () {
        expect(RecurringWeekDay::cases())->toHaveCount(7);
    });
});
