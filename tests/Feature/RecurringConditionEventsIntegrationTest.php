<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use VincenzoRaco\Recurrences\Enums\RecurringConditionType;
use VincenzoRaco\Recurrences\Events\RecurringConditionCreatedEvent;
use VincenzoRaco\Recurrences\Events\RecurringConditionDeletedEvent;
use VincenzoRaco\Recurrences\Events\RecurringConditionUpdatedEvent;
use VincenzoRaco\Recurrences\Models\RecurringCondition;

uses(RefreshDatabase::class);

describe('RecurringCondition event dispatching', function () {
    it('dispatches created event when a condition is created', function () {
        Event::fake([RecurringConditionCreatedEvent::class]);

        RecurringCondition::create([
            'recurrable_id' => 1,
            'recurrable_type' => 'App\Models\Event',
            'condition_type' => RecurringConditionType::ADD_RRULE,
            'condition_value' => 'FREQ=DAILY;COUNT=5',
        ]);

        Event::assertDispatched(RecurringConditionCreatedEvent::class, function ($event) {
            return $event->recurringCondition->condition_value === 'FREQ=DAILY;COUNT=5';
        });
    });

    it('dispatches updated event when a condition is updated', function () {
        Event::fake([RecurringConditionUpdatedEvent::class]);

        $condition = RecurringCondition::withoutEvents(function () {
            return RecurringCondition::create([
                'recurrable_id' => 1,
                'recurrable_type' => 'App\Models\Event',
                'condition_type' => RecurringConditionType::ADD_RRULE,
                'condition_value' => 'FREQ=DAILY;COUNT=5',
            ]);
        });

        $condition->update(['condition_value' => 'FREQ=WEEKLY;COUNT=10']);

        Event::assertDispatched(RecurringConditionUpdatedEvent::class, function ($event) {
            return $event->recurringCondition->condition_value === 'FREQ=WEEKLY;COUNT=10';
        });
    });

    it('dispatches deleted event when a condition is deleted', function () {
        Event::fake([RecurringConditionDeletedEvent::class]);

        $condition = RecurringCondition::withoutEvents(function () {
            return RecurringCondition::create([
                'recurrable_id' => 1,
                'recurrable_type' => 'App\Models\Event',
                'condition_type' => RecurringConditionType::ADD_DATE,
                'condition_value' => '2024-06-15',
            ]);
        });

        $condition->delete();

        Event::assertDispatched(RecurringConditionDeletedEvent::class);
    });
});
