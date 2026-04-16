<?php

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use RRule\RSet;
use VincenzoRaco\Recurrences\Contracts\Recurrable;
use VincenzoRaco\Recurrences\DataObjects\ExcludeOccurrencesRangeDataObject;
use VincenzoRaco\Recurrences\DataObjects\ExcludeOneTimeOccurrenceDataObject;
use VincenzoRaco\Recurrences\DataObjects\GetRSetOccurrencesBetweenDataObject;
use VincenzoRaco\Recurrences\DataObjects\GetRSetOccurrencesLimitedDataObject;
use VincenzoRaco\Recurrences\DataObjects\MultipleOccurrencesConditionDataObject;
use VincenzoRaco\Recurrences\DataObjects\NoEndingConditionDataObject;
use VincenzoRaco\Recurrences\DataObjects\OccurrencesDataObject;
use VincenzoRaco\Recurrences\DataObjects\SingleOccurrenceConditionDataObject;
use VincenzoRaco\Recurrences\Enums\RecurringFrequency;
use VincenzoRaco\Recurrences\Models\RecurringCondition;
use VincenzoRaco\Recurrences\RecurrencesService;

describe('RecurrencesService', function () {
    beforeEach(function () {
        $this->service = new RecurrencesService;
    });

    it('can be instantiated', function () {
        $service = new RecurrencesService;

        expect($service)->not->toBeNull();
    });

    it('generates correct occurrence hash', function () {
        $mockRecurrable = mock(Recurrable::class);
        $mockRecurrable->shouldReceive('getKey')->andReturn('model_123');

        $occurrence = Carbon::parse('2024-06-15');

        $hash = $this->service->getOccurrenceHash($mockRecurrable, $occurrence);

        expect($hash)->toBe(md5('model_123'.$occurrence->toDateTimeString()));
    });

    it('generates collection of occurrence hashes', function () {
        $mockRecurrable = mock(Recurrable::class);
        $mockRecurrable->shouldReceive('getKey')->andReturn('model_123');

        $occurrences = collect([
            Carbon::parse('2024-06-15'),
            Carbon::parse('2024-06-16'),
        ]);

        $hashes = $this->service->getOccurrencesHash($mockRecurrable, $occurrences);

        expect($hashes)->toBeInstanceOf(Collection::class);
        expect($hashes->count())->toBe(2);
        expect($hashes->first())->toBe(md5('model_123'.$occurrences->first()->toDateTimeString()));
    });

    it('creates RSet from recurrence conditions', function () {
        $mockCondition = mock(RecurringCondition::class);
        $mockCondition->shouldReceive('getRsetMethod')->andReturn('addRRule');
        $mockCondition->shouldReceive('getRsetValue')->andReturn('FREQ=DAILY');

        $rset = $this->service->getRSet([$mockCondition]);

        expect($rset)->toBeInstanceOf(RSet::class);
    });

    it('returns occurrences between dates', function () {
        $rset = new RSet;
        $rset->addRRule('FREQ=DAILY;COUNT=5');

        $dataObject = new GetRSetOccurrencesBetweenDataObject(
            Carbon::parse('2024-01-01'),
            Carbon::parse('2024-01-10'),
            null,
        );

        $result = $this->service->getRSetOccurrencesBetween($rset, $dataObject);

        expect($result)->toBeInstanceOf(OccurrencesDataObject::class);
    });

    it('returns limited occurrences', function () {
        $rset = new RSet;
        $rset->addRRule('FREQ=DAILY;COUNT=10');

        $dataObject = new GetRSetOccurrencesLimitedDataObject(5);

        $result = $this->service->getRSetOccurrencesWithLimit($rset, $dataObject);

        expect($result)->toBeInstanceOf(OccurrencesDataObject::class);
        expect($result->getOccurrences()->count())->toBeLessThanOrEqual(5);
    });

    it('creates one-time occurrence condition', function () {
        $mockRecurrable = mock(Recurrable::class);
        $mockRecurrable->shouldReceive('recurrenceConditions')->andReturn(mock(MorphMany::class));
        $mockRecurrable->recurrenceConditions()->shouldReceive('create')->andReturn(new RecurringCondition);

        $dataObject = new SingleOccurrenceConditionDataObject(Carbon::parse('2024-06-15'));

        $result = $this->service->createOneTimeOccurrenceCondition($mockRecurrable, $dataObject);

        expect($result)->toBeInstanceOf(RecurringCondition::class);
    });

    it('creates multiple occurrences condition', function () {
        $mockRecurrable = mock(Recurrable::class);
        $mockRecurrable->shouldReceive('recurrenceConditions')->andReturn(mock(MorphMany::class));
        $mockRecurrable->recurrenceConditions()->shouldReceive('create')->andReturn(new RecurringCondition);

        $dataObject = new MultipleOccurrencesConditionDataObject(
            Carbon::parse('2024-01-01'),
            RecurringFrequency::WEEKLY,
            1,
            new NoEndingConditionDataObject,
            null,
        );

        $result = $this->service->createMultipleOccurrencesCondition($mockRecurrable, $dataObject);

        expect($result)->toBeInstanceOf(RecurringCondition::class);
    });

    it('creates exclude one-time occurrence condition', function () {
        $mockRecurrable = mock(Recurrable::class);
        $mockRecurrable->shouldReceive('recurrenceConditions')->andReturn(mock(MorphMany::class));
        $mockRecurrable->recurrenceConditions()->shouldReceive('create')->andReturn(new RecurringCondition);

        $dataObject = new ExcludeOneTimeOccurrenceDataObject(Carbon::parse('2024-06-15'));

        $result = $this->service->createExcludeOneTimeOccurrenceCondition($mockRecurrable, $dataObject);

        expect($result)->toBeInstanceOf(RecurringCondition::class);
    });

    it('creates exclude occurrences range condition', function () {
        $mockRecurrable = mock(Recurrable::class);
        $mockRecurrable->shouldReceive('recurrenceConditions')->andReturn(mock(MorphMany::class));
        $mockRecurrable->recurrenceConditions()->shouldReceive('create')->andReturn(new RecurringCondition);

        $dataObject = new ExcludeOccurrencesRangeDataObject(
            Carbon::parse('2024-06-01'),
            Carbon::parse('2024-06-30'),
            RecurringFrequency::DAILY,
        );

        $result = $this->service->createExcludeOccurrencesRangeCondition($mockRecurrable, $dataObject);

        expect($result)->toBeInstanceOf(RecurringCondition::class);
    });

    it('returns occurrences with safety limit', function () {
        $rset = new RSet;
        $rset->addRRule('FREQ=DAILY;COUNT=20');

        $result = $this->service->getOccurrencesWithSafety($rset, 5);

        expect($result)->toBeInstanceOf(OccurrencesDataObject::class);
        expect($result->getOccurrences()->count())->toBe(5);
    });

    it('returns occurrences with default safety limit from config', function () {
        config(['recurrences.max_occurrences' => 3]);

        $rset = new RSet;
        $rset->addRRule('FREQ=DAILY;COUNT=20');

        $result = $this->service->getOccurrencesWithSafety($rset);

        expect($result)->toBeInstanceOf(OccurrencesDataObject::class);
        expect($result->getOccurrences()->count())->toBe(3);
    });

    it('gets occurrences between dates directly from recurrable', function () {
        $mockCondition = mock(RecurringCondition::class);
        $mockCondition->shouldReceive('getRsetMethod')->andReturn('addRRule');
        $mockCondition->shouldReceive('getRsetValue')->andReturn('FREQ=DAILY;COUNT=10');

        $mockMorphMany = mock(MorphMany::class);
        $mockMorphMany->shouldReceive('get')->andReturn(collect([$mockCondition]));

        $mockRecurrable = mock(Recurrable::class);
        $mockRecurrable->shouldReceive('recurrenceConditions')->andReturn($mockMorphMany);

        $dataObject = new GetRSetOccurrencesBetweenDataObject(
            Carbon::parse('2024-01-01'),
            Carbon::parse('2024-01-10'),
            null,
        );

        $result = $this->service->getOccurrencesBetween($mockRecurrable, $dataObject);

        expect($result)->toBeInstanceOf(OccurrencesDataObject::class);
    });

    it('deletes all conditions for a recurrable', function () {
        $mockMorphMany = mock(MorphMany::class);
        $mockMorphMany->shouldReceive('delete')->once()->andReturn(3);

        $mockRecurrable = mock(Recurrable::class);
        $mockRecurrable->shouldReceive('recurrenceConditions')->andReturn($mockMorphMany);

        $count = $this->service->deleteAllConditions($mockRecurrable);

        expect($count)->toBe(3);
    });
});
