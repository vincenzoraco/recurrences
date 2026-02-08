<?php

namespace VincenzoRaco\Recurrences\Enums;

enum RecurringEnding: string
{
    case NEVER = 'NEVER';
    case UNTIL = 'UNTIL';
    case TIMES = 'TIMES';
}
