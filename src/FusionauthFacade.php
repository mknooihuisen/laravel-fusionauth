<?php

namespace Mknooihuisen\LaravelFusionauth;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Mknooihuisen\LaravelFusionauth\Skeleton\SkeletonClass
 */
class FusionauthFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-fusionauth';
    }
}
