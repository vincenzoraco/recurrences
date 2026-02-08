<?php

use VincenzoRaco\Recurrences\Concerns\HasRecurrences;

describe('HasRecurrences', function () {
    it('is a valid trait', function () {
        $reflection = new ReflectionClass(HasRecurrences::class);

        expect($reflection->isTrait())->toBeTrue();
    });

    it('defines recurrenceConditions method', function () {
        $reflection = new ReflectionClass(HasRecurrences::class);

        expect($reflection->hasMethod('recurrenceConditions'))->toBeTrue();
        expect($reflection->getMethod('recurrenceConditions')->isPublic())->toBeTrue();
    });

    it('recurrenceConditions method exists on using class', function () {
        expect(method_exists(HasRecurrences::class, 'recurrenceConditions'))->toBeTrue();
    });
});
