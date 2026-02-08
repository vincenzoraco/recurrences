<?php

namespace VincenzoRaco\Recurrences\DataObjects;

use Illuminate\Support\Carbon;

abstract class EndingConditionDataObject extends DataObject
{
    abstract public function getUntil(): ?Carbon;

    abstract public function getTimes(): ?int;
}
