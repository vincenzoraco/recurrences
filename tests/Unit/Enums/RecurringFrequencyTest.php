<?php

use VincenzoRaco\Recurrences\Enums\RecurringFrequency;

describe('RecurringFrequency', function () {
    it('has all expected values', function () {
        expect(RecurringFrequency::DAILY->value)->toBe('DAILY');
        expect(RecurringFrequency::WEEKLY->value)->toBe('WEEKLY');
        expect(RecurringFrequency::MONTHLY->value)->toBe('MONTHLY');
        expect(RecurringFrequency::YEARLY->value)->toBe('YEARLY');
    });

    it('has exactly four cases', function () {
        $cases = RecurringFrequency::cases();
        expect(count($cases))->toBe(4);
    });
});
