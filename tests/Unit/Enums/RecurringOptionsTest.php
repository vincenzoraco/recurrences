<?php

use VincenzoRaco\Recurrences\Enums\RecurringOptions;

describe('RecurringOptions', function () {
    it('has all expected values', function () {
        expect(RecurringOptions::ONE_TIME->value)->toBe('ONE_TIME');
        expect(RecurringOptions::RECURRING->value)->toBe('RECURRING');
    });

    it('has exactly two cases', function () {
        $cases = RecurringOptions::cases();
        expect(count($cases))->toBe(2);
    });
});
