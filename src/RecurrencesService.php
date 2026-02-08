<?php

namespace VincenzoRaco\Recurrences;

use DateTime;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use RRule\RSet;
use VincenzoRaco\Recurrences\Contracts\Recurrable;
use VincenzoRaco\Recurrences\DataObjects\ExcludeOccurrencesRangeDataObject;
use VincenzoRaco\Recurrences\DataObjects\ExcludeOneTimeOccurrenceDataObject;
use VincenzoRaco\Recurrences\DataObjects\GetRSetOccurrencesBetweenDataObject;
use VincenzoRaco\Recurrences\DataObjects\GetRSetOccurrencesLimitedDataObject;
use VincenzoRaco\Recurrences\DataObjects\MultipleOccurrencesConditionDataObject;
use VincenzoRaco\Recurrences\DataObjects\OccurrencesDataObject;
use VincenzoRaco\Recurrences\DataObjects\SingleOccurrenceConditionDataObject;
use VincenzoRaco\Recurrences\Enums\RecurringConditionType;
use VincenzoRaco\Recurrences\Models\RecurringCondition;

class RecurrencesService
{
    public function getOccurrenceHash(
        Recurrable $recurrable,
        Carbon $occurrence,
    ): string {
        return hash(
            'md5',
            sprintf(
                '%s%s',
                $recurrable->getKey(),
                $occurrence->toDateString(),
            ),
        );
    }

    /**
     * @param  Collection<Carbon>  $occurrences
     */
    public function getOccurrencesHash(
        Recurrable $recurrable,
        Collection $occurrences
    ): Collection {
        return $occurrences->map(function (Carbon $occurrence) use ($recurrable) {
            return $this->getOccurrenceHash(
                $recurrable,
                $occurrence,
            );
        });
    }

    /**
     * @param  iterable<RecurringCondition>  $recurrenceConditions
     */
    public function getRSet(
        iterable $recurrenceConditions,
    ): RSet {
        $rset = new RSet;

        foreach ($recurrenceConditions as $condition) {
            $rset->{$condition->getRsetMethod()}(
                $condition->getRsetValue(),
            );
        }

        return $rset;
    }

    public function getRSetOccurrencesBetween(
        RSet $rset,
        GetRSetOccurrencesBetweenDataObject $dataObject,
    ): OccurrencesDataObject {
        $occurrences = $rset->getOccurrencesBetween(
            $dataObject->getStartDate(),
            $dataObject->getEndDate(),
            $dataObject->getLimit(),
        );

        return $this->getOccurrencesDataObject(
            collect($occurrences),
        );
    }

    public function getRSetOccurrencesWithLimit(
        RSet $rset,
        GetRSetOccurrencesLimitedDataObject $dataObject,
    ): OccurrencesDataObject {
        $occurrences = $rset->getOccurrences(
            $dataObject->getTimes(),
        );

        return $this->getOccurrencesDataObject(
            collect($occurrences),
        );
    }

    public function getOccurrencesWithSafety(
        RSet $rset,
        ?int $limit = null,
    ): OccurrencesDataObject {
        $occurrences = $rset->getOccurrences(
            $limit ?? $this->getMaximumOccurrences(),
        );

        return $this->getOccurrencesDataObject(
            collect($occurrences),
        );
    }

    public function createOneTimeOccurrenceCondition(
        Recurrable $recurrable,
        SingleOccurrenceConditionDataObject $dataObject,
    ): RecurringCondition {
        return $recurrable->recurrenceConditions()->create([
            'condition_type' => RecurringConditionType::ADD_DATE,
            'condition_value' => $dataObject->getConditionValue(),
        ]);
    }

    public function createMultipleOccurrencesCondition(
        Recurrable $recurrable,
        MultipleOccurrencesConditionDataObject $dataObject,
    ): RecurringCondition {
        return $recurrable->recurrenceConditions()->create([
            'condition_type' => RecurringConditionType::ADD_RRULE,
            'condition_value' => $dataObject->getConditionValue(),
        ]);
    }

    public function createExcludeOccurrencesRangeCondition(
        Recurrable $recurrable,
        ExcludeOccurrencesRangeDataObject $dataObject,
    ): RecurringCondition {
        return $recurrable->recurrenceConditions()->create([
            'condition_type' => RecurringConditionType::ADD_EX_RRULE,
            'condition_value' => $dataObject->getConditionValue(),
        ]);
    }

    public function createExcludeOneTimeOccurrenceCondition(
        Recurrable $recurrable,
        ExcludeOneTimeOccurrenceDataObject $dataObject,
    ): RecurringCondition {
        return $recurrable->recurrenceConditions()->create([
            'condition_type' => RecurringConditionType::ADD_EX_DATE,
            'condition_value' => $dataObject->getConditionValue(),
        ]);
    }

    protected function getMaximumOccurrences(): int
    {
        return config('recurrences.max_occurrences', 1000);
    }

    /**
     * @param  Collection<DateTime>  $occurrences
     */
    protected function getOccurrencesDataObject(
        Collection $occurrences,
    ): OccurrencesDataObject {
        return new OccurrencesDataObject(
            $occurrences->mapInto(Carbon::class),
        );
    }
}
