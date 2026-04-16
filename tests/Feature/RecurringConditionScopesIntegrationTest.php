<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use VincenzoRaco\Recurrences\Enums\RecurringConditionType;
use VincenzoRaco\Recurrences\Models\RecurringCondition;

uses(RefreshDatabase::class);

beforeEach(function () {
    RecurringCondition::withoutEvents(function () {
        RecurringCondition::create([
            'recurrable_id' => 1,
            'recurrable_type' => 'App\Models\Event',
            'condition_type' => RecurringConditionType::ADD_RRULE,
            'condition_value' => 'FREQ=DAILY;COUNT=5',
        ]);

        RecurringCondition::create([
            'recurrable_id' => 1,
            'recurrable_type' => 'App\Models\Event',
            'condition_type' => RecurringConditionType::ADD_EX_RRULE,
            'condition_value' => 'FREQ=DAILY;COUNT=2',
        ]);

        RecurringCondition::create([
            'recurrable_id' => 1,
            'recurrable_type' => 'App\Models\Event',
            'condition_type' => RecurringConditionType::ADD_DATE,
            'condition_value' => '2024-06-15',
        ]);

        RecurringCondition::create([
            'recurrable_id' => 1,
            'recurrable_type' => 'App\Models\Event',
            'condition_type' => RecurringConditionType::ADD_EX_DATE,
            'condition_value' => '2024-06-20',
        ]);
    });
});

describe('RecurringCondition scope filtering', function () {
    it('addRrule scope returns only ADD_RRULE conditions', function () {
        $results = RecurringCondition::addRrule()->get();

        expect($results)->toHaveCount(1);
        expect($results->first()->condition_type)->toBe(RecurringConditionType::ADD_RRULE);
    });

    it('addExRrule scope returns only ADD_EX_RRULE conditions', function () {
        $results = RecurringCondition::addExRrule()->get();

        expect($results)->toHaveCount(1);
        expect($results->first()->condition_type)->toBe(RecurringConditionType::ADD_EX_RRULE);
    });

    it('addDate scope returns only ADD_DATE conditions', function () {
        $results = RecurringCondition::addDate()->get();

        expect($results)->toHaveCount(1);
        expect($results->first()->condition_type)->toBe(RecurringConditionType::ADD_DATE);
    });

    it('addExDate scope returns only ADD_EX_DATE conditions', function () {
        $results = RecurringCondition::addExDate()->get();

        expect($results)->toHaveCount(1);
        expect($results->first()->condition_type)->toBe(RecurringConditionType::ADD_EX_DATE);
    });

    it('returns all conditions without scope', function () {
        $results = RecurringCondition::all();

        expect($results)->toHaveCount(4);
    });
});
