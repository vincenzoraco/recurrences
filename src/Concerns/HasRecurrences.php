<?php

namespace VincenzoRaco\Recurrences\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use VincenzoRaco\Recurrences\Models\RecurringCondition;

trait HasRecurrences
{
    public function recurrenceConditions(): MorphMany
    {
        return $this->morphMany(
            RecurringCondition::class,
            RecurringCondition::MORPH_TO,
        );
    }
}
