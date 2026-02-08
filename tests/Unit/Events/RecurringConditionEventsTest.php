<?php

use VincenzoRaco\Recurrences\Events\RecurringConditionCreatedEvent;
use VincenzoRaco\Recurrences\Events\RecurringConditionDeletedEvent;
use VincenzoRaco\Recurrences\Events\RecurringConditionUpdatedEvent;

describe('RecurringConditionCreatedEvent', function () {
    it('has correct property', function () {
        $condition = new \VincenzoRaco\Recurrences\Models\RecurringCondition;
        $event = new RecurringConditionCreatedEvent($condition);

        expect($event->recurringCondition)->toBe($condition);
    });
});

describe('RecurringConditionUpdatedEvent', function () {
    it('has correct property', function () {
        $condition = new \VincenzoRaco\Recurrences\Models\RecurringCondition;
        $event = new RecurringConditionUpdatedEvent($condition);

        expect($event->recurringCondition)->toBe($condition);
    });
});

describe('RecurringConditionDeletedEvent', function () {
    it('has correct property', function () {
        $condition = new \VincenzoRaco\Recurrences\Models\RecurringCondition;
        $event = new RecurringConditionDeletedEvent($condition);

        expect($event->recurringCondition)->toBe($condition);
    });
});
