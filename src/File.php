<?php

namespace Omotolaawokunle\LaravelFiles;

use Omotolaawokunle\LaravelFiles\Contracts\Files;

/**
 * @todo Add code to the empty functions
 */
class File implements Files
{
    private $file;

    public function __construct($path = "/")
    {
    }

    public function make(string $path = "/", string $name): Files
    {
        return $this;
    }

    public function create(string $path = "/", string $name, $content = null): Files
    {
        return $this;
    }

    public function exists(): bool
    {
        return true;
    }

    public function size($unit = 'B'): string
    {
        return "";
    }

    public function delete(): bool
    {
        return true;
    }

    public function clone($path = "/", string $name): Files
    {
        return $this;
    }

    public function move($path = "/"): Files
    {
        return $this;
    }

    public function rename(string $name): Files
    {
        return $this;
    }
}
