<?php namespace Gavalierm\SolarApiLaravel;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Gavalierm\SolarApiLaravel\SolarApiLaravel
 */
class SolarApiLaravelFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * Don't use this. Just... don't.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Gavalierm\SolarApiLaravel\SolarApiLaravel';
    }
}