<?php

namespace VincenzoRaco\Recurrences\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use VincenzoRaco\Recurrences\Models\RecurringCondition;

class RecurringConditionCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public RecurringCondition $recurringCondition,
    ) {}
}
