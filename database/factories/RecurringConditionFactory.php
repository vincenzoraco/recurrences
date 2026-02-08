<?php

namespace VincenzoRaco\Recurrences\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use VincenzoRaco\Recurrences\Contracts\Recurrable;
use VincenzoRaco\Recurrences\DataObjects\MultipleOccurrencesConditionDataObject;
use VincenzoRaco\Recurrences\DataObjects\SingleOccurrenceConditionDataObject;
use VincenzoRaco\Recurrences\Enums\RecurringConditionType;
use VincenzoRaco\Recurrences\Models\RecurringCondition;

class RecurringConditionFactory extends Factory
{
    protected $model = RecurringCondition::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    public function setRecurrable(
        Recurrable $recurrable,
    ): static {
        return $this->state(fn () => [
            'recurrable_id' => $recurrable->getKey(),
            'recurrable_type' => $recurrable->getMorphClass(),
        ]);
    }

    public function addRRule(
        MultipleOccurrencesConditionDataObject $dataObject,
    ): static {
        return $this->state(fn () => [
            'condition_type' => RecurringConditionType::ADD_RRULE,
            'condition_value' => $dataObject->getConditionValue(),
        ]);
    }

    public function addExRRule(
        MultipleOccurrencesConditionDataObject $dataObject,
    ): static {
        return $this->state(fn () => [
            'condition_type' => RecurringConditionType::ADD_EX_RRULE,
            'condition_value' => $dataObject->getConditionValue(),
        ]);
    }

    public function addDate(
        SingleOccurrenceConditionDataObject $dataObject,
    ): static {
        return $this->state(fn () => [
            'condition_type' => RecurringConditionType::ADD_DATE,
            'condition_value' => $dataObject->getConditionValue(),
        ]);
    }

    public function addExDate(
        SingleOccurrenceConditionDataObject $dataObject,
    ): static {
        return $this->state(fn () => [
            'condition_type' => RecurringConditionType::ADD_EX_DATE,
            'condition_value' => $dataObject->getConditionValue(),
        ]);
    }
}
