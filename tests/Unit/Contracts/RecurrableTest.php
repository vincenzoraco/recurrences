<?php

use VincenzoRaco\Recurrences\Contracts\Recurrable;

describe('Recurrable', function () {
    it('is a valid interface', function () {
        $reflection = new ReflectionClass(Recurrable::class);

        expect($reflection->isInterface())->toBeTrue();
    });

    it('defines getKey method', function () {
        $reflection = new ReflectionClass(Recurrable::class);

        expect($reflection->hasMethod('getKey'))->toBeTrue();
        expect($reflection->getMethod('getKey')->isPublic())->toBeTrue();
    });

    it('defines getMorphClass method', function () {
        $reflection = new ReflectionClass(Recurrable::class);

        expect($reflection->hasMethod('getMorphClass'))->toBeTrue();
        expect($reflection->getMethod('getMorphClass')->isPublic())->toBeTrue();
    });

    it('defines recurrenceConditions method', function () {
        $reflection = new ReflectionClass(Recurrable::class);

        expect($reflection->hasMethod('recurrenceConditions'))->toBeTrue();
        expect($reflection->getMethod('recurrenceConditions')->isPublic())->toBeTrue();
    });
});
