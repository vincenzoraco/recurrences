<?php

namespace VincenzoRaco\Recurrences\DataObjects;

class NoEndingConditionDataObject extends EndingConditionDataObject
{
    public function getUntil(): null
    {
        return null;
    }

    public function getTimes(): null
    {
        return null;
    }
}
