<?php

namespace VincenzoRaco\Recurrences;

use Illuminate\Support\Facades\Facade;

/**
 * @see \VincenzoRaco\Recurrences\RecurrencesService
 */
class Recurrences extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'recurrences';
    }
}
