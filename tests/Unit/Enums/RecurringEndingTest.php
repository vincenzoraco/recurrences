<?php

use VincenzoRaco\Recurrences\Enums\RecurringEnding;

describe('RecurringEnding', function () {
    it('has all expected values', function () {
        expect(RecurringEnding::NEVER->value)->toBe('NEVER');
        expect(RecurringEnding::UNTIL->value)->toBe('UNTIL');
        expect(RecurringEnding::TIMES->value)->toBe('TIMES');
    });

    it('has exactly three cases', function () {
        $cases = RecurringEnding::cases();
        expect(count($cases))->toBe(3);
    });
});
