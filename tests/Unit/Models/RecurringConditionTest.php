<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use VincenzoRaco\Recurrences\Enums\RecurringConditionType;
use VincenzoRaco\Recurrences\Models\RecurringCondition;

describe('RecurringCondition', function () {
    it('has correct guard attributes', function () {
        $model = new RecurringCondition;

        expect($model->getGuarded())->toBe([]);
    });

    it('has correct casts', function () {
        $model = new RecurringCondition;

        expect($model->getCasts())->toHaveKey('condition_type', RecurringConditionType::class);
    });

    it('returns correct rset method for ADD_RRULE', function () {
        $condition = new RecurringCondition;
        $condition->condition_type = RecurringConditionType::ADD_RRULE;

        expect($condition->getRsetMethod())->toBe('addRRule');
    });

    it('returns correct rset method for ADD_EX_RRULE', function () {
        $condition = new RecurringCondition;
        $condition->condition_type = RecurringConditionType::ADD_EX_RRULE;

        expect($condition->getRsetMethod())->toBe('addExRule');
    });

    it('returns correct rset method for ADD_DATE', function () {
        $condition = new RecurringCondition;
        $condition->condition_type = RecurringConditionType::ADD_DATE;

        expect($condition->getRsetMethod())->toBe('addDate');
    });

    it('returns correct rset method for ADD_EX_DATE', function () {
        $condition = new RecurringCondition;
        $condition->condition_type = RecurringConditionType::ADD_EX_DATE;

        expect($condition->getRsetMethod())->toBe('addExDate');
    });

    it('returns correct rset value', function () {
        $condition = new RecurringCondition;
        $condition->condition_value = 'FREQ=DAILY';

        expect($condition->getRsetValue())->toBe('FREQ=DAILY');
    });

    it('has recurrable morphTo relationship', function () {
        $model = new RecurringCondition;

        expect($model->recurrable())->toBeInstanceOf(MorphTo::class);
    });

    it('uses HasFactory trait', function () {
        expect(in_array(HasFactory::class, class_uses(RecurringCondition::class)))->toBeTrue();
    });

    it('has correct morph name constant', function () {
        expect(RecurringCondition::MORPH_TO)->toBe('recurrable');
    });

    it('addRrule scope filters by ADD_RRULE condition type', function () {
        $query = RecurringCondition::addRrule();

        expect($query)->toBeInstanceOf(Builder::class);
    });

    it('addExRrule scope filters by ADD_EX_RRULE condition type', function () {
        $query = RecurringCondition::addExRrule();

        expect($query)->toBeInstanceOf(Builder::class);
    });

    it('addDate scope filters by ADD_DATE condition type', function () {
        $query = RecurringCondition::addDate();

        expect($query)->toBeInstanceOf(Builder::class);
    });

    it('addExDate scope filters by ADD_EX_DATE condition type', function () {
        $query = RecurringCondition::addExDate();

        expect($query)->toBeInstanceOf(Builder::class);
    });
});
