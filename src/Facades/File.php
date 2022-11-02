<?php

namespace Omotolaawokunle\LaravelFiles\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Omotolaawokunle\LaravelFiles\File
 */
class File extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'file';
    }
}
