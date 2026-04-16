# Laravel Recurrences

[![Latest Version on Packagist](https://img.shields.io/packagist/v/vincenzoraco/recurrences.svg?style=flat-square)](https://packagist.org/packages/vincenzoraco/recurrences)
[![Total Downloads](https://img.shields.io/packagist/dt/vincenzoraco/recurrences.svg?style=flat-square)](https://packagist.org/packages/vincenzoraco/recurrences)
![GitHub Actions](https://github.com/vincenzoraco/recurrences/actions/workflows/main.yml/badge.svg)

A Laravel package for managing recurring events using the RFC 5545 RRULE specification. Built on top of `rlanvin/php-rrule`, this package provides a Laravel-native interface for working with recurring dates and events.

## Why this package?

While the bare `rlanvin/php-rrule` library is powerful, this package offers significant advantages for Laravel applications:

- **Eloquent Integration**: Attach recurrence conditions to any Eloquent model using a simple trait
- **Type-Safe Data Objects**: All configuration is done through typed data objects with validation
- **Laravel Events**: Dispatchable events for when recurrence conditions are created, updated, or deleted
- **MorphMany Relationship**: Attach multiple recurrence rules to any model without creating additional tables
- **Facade Access**: Convenient facade for accessing the service layer
- **Occurrence Hashing**: Built-in utilities for generating unique hashes for occurrences (useful for caching)
- **Exclusion Support**: Easily exclude specific dates or date ranges from recurring events

## Installation

You can install the package via composer:

```bash
composer require vincenzoraco/recurrences
```

## Usage

### Making a model recurrable

Add the `HasRecurrences` trait and `Recurrable` contract to any Eloquent model:

```php
use VincenzoRaco\Recurrences\Concerns\HasRecurrences;
use VincenzoRaco\Recurrences\Contracts\Recurrable;
use Illuminate\Database\Eloquent\Model;

class Event extends Model implements Recurrable
{
    use HasRecurrences;
}
```

### Creating a recurring event

Use the data objects to define recurrence conditions:

```php
use Illuminate\Support\Carbon;
use VincenzoRaco\Recurrences\Enums\RecurringFrequency;
use VincenzoRaco\Recurrences\DataObjects\MultipleOccurrencesConditionDataObject;
use VincenzoRaco\Recurrences\DataObjects\EndingConditionUntilDataObject;

$event = Event::find(1);

$condition = new MultipleOccurrencesConditionDataObject(
    start: Carbon::parse('2024-01-01'),
    frequency: RecurringFrequency::WEEKLY,
    interval: 1,
    endingCondition: new EndingConditionUntilDataObject(
        until: Carbon::parse('2024-03-31'),
    ),
    byWeekDay: null,
);

app(\VincenzoRaco\Recurrences\RecurrencesService::class)
    ->createMultipleOccurrencesCondition($event, $condition);
```

### Filtering by weekday

Restrict a recurring event to specific days of the week using the `RecurringWeekDay` enum:

```php
use Illuminate\Support\Carbon;
use VincenzoRaco\Recurrences\Enums\RecurringFrequency;
use VincenzoRaco\Recurrences\Enums\RecurringWeekDay;
use VincenzoRaco\Recurrences\DataObjects\MultipleOccurrencesConditionDataObject;
use VincenzoRaco\Recurrences\DataObjects\NoEndingConditionDataObject;

$condition = new MultipleOccurrencesConditionDataObject(
    start: Carbon::parse('2024-01-01'),
    frequency: RecurringFrequency::WEEKLY,
    interval: 1,
    endingCondition: new NoEndingConditionDataObject,
    byWeekDay: [RecurringWeekDay::MONDAY, RecurringWeekDay::WEDNESDAY, RecurringWeekDay::FRIDAY],
);

app(\VincenzoRaco\Recurrences\RecurrencesService::class)
    ->createMultipleOccurrencesCondition($event, $condition);
```

### Creating a one-time occurrence

Add single dates to a recurrable model:

```php
use Illuminate\Support\Carbon;
use VincenzoRaco\Recurrences\DataObjects\SingleOccurrenceConditionDataObject;

$event = Event::find(1);

$condition = new SingleOccurrenceConditionDataObject(
    date: Carbon::parse('2024-02-14'),
);

app(\VincenzoRaco\Recurrences\RecurrencesService::class)
    ->createOneTimeOccurrenceCondition($event, $condition);
```

### Excluding dates from a recurring event

Exclude specific dates or date ranges:

```php
use Illuminate\Support\Carbon;
use VincenzoRaco\Recurrences\DataObjects\ExcludeOneTimeOccurrenceDataObject;
use VincenzoRaco\Recurrences\DataObjects\ExcludeOccurrencesRangeDataObject;

// Exclude a single date
$excludeSingle = new ExcludeOneTimeOccurrenceDataObject(
    date: Carbon::parse('2024-02-14'),
);

app(\VincenzoRaco\Recurrences\RecurrencesService::class)
    ->createExcludeOneTimeOccurrenceCondition($event, $excludeSingle);

// Exclude a range of dates
$excludeRange = new ExcludeOccurrencesRangeDataObject(
    startDate: Carbon::parse('2024-12-24'),
    endDate: Carbon::parse('2024-12-26'),
    frequency: RecurringFrequency::DAILY,
);

app(\VincenzoRaco\Recurrences\RecurrencesService::class)
    ->createExcludeOccurrencesRangeCondition($event, $excludeRange);
```

### Getting occurrences

Retrieve occurrences from a recurrable model:

```php
use Illuminate\Support\Carbon;
use RRule\RSet;
use VincenzoRaco\Recurrences\DataObjects\GetRSetOccurrencesBetweenDataObject;

$event = Event::find(1);

// Build the RSet from all recurrence conditions
$rset = app(\VincenzoRaco\Recurrences\RecurrencesService::class)
    ->getRSet($event->recurrenceConditions);

// Get occurrences within a date range
$dataObject = new GetRSetOccurrencesBetweenDataObject(
    startDate: Carbon::parse('2024-01-01'),
    endDate: Carbon::parse('2024-01-31'),
);

$occurrences = app(\VincenzoRaco\Recurrences\RecurrencesService::class)
    ->getRSetOccurrencesBetween($rset, $dataObject);

foreach ($occurrences->getOccurrences() as $occurrence) {
    echo $occurrence->toDateString();
}
```

### Getting occurrences directly from a model

A shorthand to build the RSet and query occurrences in one call:

```php
use Illuminate\Support\Carbon;
use VincenzoRaco\Recurrences\DataObjects\GetRSetOccurrencesBetweenDataObject;

$event = Event::find(1);

$dataObject = new GetRSetOccurrencesBetweenDataObject(
    startDate: Carbon::parse('2024-01-01'),
    endDate: Carbon::parse('2024-01-31'),
);

$occurrences = app(\VincenzoRaco\Recurrences\RecurrencesService::class)
    ->getOccurrencesBetween($event, $dataObject);
```

### Deleting all conditions

Remove every recurrence condition attached to a model:

```php
$deleted = app(\VincenzoRaco\Recurrences\RecurrencesService::class)
    ->deleteAllConditions($event);
```

### Using the facade

```php
use VincenzoRaco\Recurrences\Recurrences;
use Illuminate\Support\Carbon;
use VincenzoRaco\Recurrences\DataObjects\GetRSetOccurrencesBetweenDataObject;

$event = Event::find(1);
$rset = Recurrences::getRSet($event->recurrenceConditions);

$dataObject = new GetRSetOccurrencesBetweenDataObject(
    startDate: Carbon::parse('2024-01-01'),
    endDate: Carbon::parse('2024-01-31'),
);

$occurrences = Recurrences::getRSetOccurrencesBetween($rset, $dataObject);
```

### Using the facade alias

```php
use VincenzoRaco\Recurrences\Recurrences;

// Same as above, but shorter
$rset = Recurrences::getRSet($event->recurrenceConditions);
```

## Configuration

Publish the config file:

```bash
php artisan vendor:publish --provider="VincenzoRaco\Recurrences\RecurrencesServiceProvider"
```

Available configuration options:

```php
return [
    'max_occurrences' => 1000,
];
```

## Available Frequencies

- `RecurringFrequency::DAILY`
- `RecurringFrequency::WEEKLY`
- `RecurringFrequency::MONTHLY`
- `RecurringFrequency::YEARLY`

## Available Week Days

- `RecurringWeekDay::MONDAY`
- `RecurringWeekDay::TUESDAY`
- `RecurringWeekDay::WEDNESDAY`
- `RecurringWeekDay::THURSDAY`
- `RecurringWeekDay::FRIDAY`
- `RecurringWeekDay::SATURDAY`
- `RecurringWeekDay::SUNDAY`

## Ending Conditions

- `NoEndingConditionDataObject` - Never ends
- `EndingConditionUntilDataObject` - Ends on a specific date
- `EndingConditionTimesDataObject` - Ends after a specific number of occurrences

## Events

The package dispatches Laravel events for lifecycle hooks:

- `RecurringConditionCreatedEvent`
- `RecurringConditionUpdatedEvent`
- `RecurringConditionDeletedEvent`

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email vincenzo [at] vincenzoraco.dev instead of using the issue tracker.

## Credits

-   [Vincenzo Raco](https://github.com/vincenzoraco)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
