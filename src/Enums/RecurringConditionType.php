<?php

namespace VincenzoRaco\Recurrences\Enums;

enum RecurringConditionType: string
{
    case ADD_RRULE = 'ADD_RRULE';
    case ADD_EX_RRULE = 'ADD_EX_RRULE';
    case ADD_DATE = 'ADD_DATE';
    case ADD_EX_DATE = 'ADD_EX_DATE';

    public function getRsetMethod(): string
    {
        return match ($this) {
            self::ADD_RRULE => 'addRRule',
            self::ADD_EX_RRULE => 'addExRule',
            self::ADD_DATE => 'addDate',
            self::ADD_EX_DATE => 'addExDate',
        };
    }

    public function isDate(): bool
    {
        return match ($this) {
            self::ADD_DATE, self::ADD_EX_DATE => true,
            self::ADD_RRULE, self::ADD_EX_RRULE => false,
        };
    }
}
