<?php

namespace VincenzoRaco\Recurrences\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use VincenzoRaco\Recurrences\Enums\RecurringConditionType;
use VincenzoRaco\Recurrences\Events\RecurringConditionCreatedEvent;
use VincenzoRaco\Recurrences\Events\RecurringConditionDeletedEvent;
use VincenzoRaco\Recurrences\Events\RecurringConditionUpdatedEvent;

class RecurringCondition extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const string MORPH_TO = 'recurrable';

    protected $casts = [
        'condition_type' => RecurringConditionType::class,
    ];

    protected $dispatchesEvents = [
        'created' => RecurringConditionCreatedEvent::class,
        'updated' => RecurringConditionUpdatedEvent::class,
        'deleted' => RecurringConditionDeletedEvent::class,
    ];

    public function recurrable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getRsetMethod(): string
    {
        return $this->condition_type->getRsetMethod();
    }

    public function getRsetValue(): string
    {
        return $this->condition_value;
    }

    public function scopeAddRrule(Builder $query): Builder
    {
        return $query->where(
            'condition_type',
            RecurringConditionType::ADD_RRULE,
        );
    }

    public function scopeAddExRrule(Builder $query): Builder
    {
        return $query->where(
            'condition_type',
            RecurringConditionType::ADD_EX_RRULE,
        );
    }

    public function scopeAddDate(Builder $query): Builder
    {
        return $query->where(
            'condition_type',
            RecurringConditionType::ADD_DATE,
        );
    }

    public function scopeAddExDate(Builder $query): Builder
    {
        return $query->where(
            'condition_type',
            RecurringConditionType::ADD_EX_DATE,
        );
    }
}
