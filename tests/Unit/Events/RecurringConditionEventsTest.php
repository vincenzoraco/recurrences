<?php

use VincenzoRaco\Recurrences\Events\RecurringConditionCreatedEvent;
use VincenzoRaco\Recurrences\Events\RecurringConditionDeletedEvent;
use VincenzoRaco\Recurrences\Events\RecurringConditionUpdatedEvent;
use VincenzoRaco\Recurrences\Models\RecurringCondition;

describe('RecurringConditionCreatedEvent', function () {
    it('has correct property', function () {
        $condition = new RecurringCondition;
        $event = new RecurringConditionCreatedEvent($condition);

        expect($event->recurringCondition)->toBe($condition);
    });
});

describe('RecurringConditionUpdatedEvent', function () {
    it('has correct property', function () {
        $condition = new RecurringCondition;
        $event = new RecurringConditionUpdatedEvent($condition);

        expect($event->recurringCondition)->toBe($condition);
    });
});

describe('RecurringConditionDeletedEvent', function () {
    it('has correct property', function () {
        $condition = new RecurringCondition;
        $event = new RecurringConditionDeletedEvent($condition);

        expect($event->recurringCondition)->toBe($condition);
    });
});
