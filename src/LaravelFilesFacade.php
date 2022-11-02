<?php

namespace Omotolaawokunle\LaravelFiles;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Omotolaawokunle\LaravelFiles\Skeleton\SkeletonClass
 */
class LaravelFilesFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-files';
    }
}
