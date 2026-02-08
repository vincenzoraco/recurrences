<?php

namespace VincenzoRaco\Recurrences\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Recurrable
{
    public function getKey();

    public function getMorphClass();

    public function recurrenceConditions(): MorphMany;
}
