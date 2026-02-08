<?php

namespace VincenzoRaco\Recurrences\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Recurrable
{
    public function getKey(): mixed;

    public function getMorphClass(): string;

    public function recurrenceConditions(): MorphMany;
}
