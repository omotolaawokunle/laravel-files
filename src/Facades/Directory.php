<?php

namespace Omotolaawokunle\LaravelFiles\Facades;

use Illuminate\Support\Facades\Facade;
use Omotolaawokunle\LaravelFiles\Contracts\Files;

/**
 * Class Directory
 * 
 * @mixin \Omotolaawokunle\LaravelFiles\Directory
 * 
 * @package Omotolaawokunle\LaravelFiles
 */
class Directory extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'directory';
    }
}
