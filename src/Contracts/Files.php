<?php

namespace Omotolaawokunle\LaravelFiles\Contracts;


interface Files
{
    /**
     * Instantiate or create new file or directory class
     *
     * @param  string $path
     * @param  string  $name
     * @return Files
     */
    public function make(string $path = "/", string $name): Files;

    /**
     * Create a new file or directory
     *
     * @param  string $path Path to file/directory
     * @param  string  $name Name of file/directory
     * @param  mixed  $content Content of file
     * @return Files
     */
    public function create(string $path = "/", string $name, mixed $content = null): Files;

    /**
     * Get size of file or directory
     *
     * @return string
     */
    public function size($unit = 'B'): string;

    /**
     * Delete file or directory
     *
     * @return boolean
     */
    public function delete(): bool;

    /**
     * Check if file or directory exists
     *
     * @return boolean
     */
    public function exists(): bool;

    public function clone($path = "/", string $name): Files;

    public function move($path = "/"): Files;

    public function rename(string $name): Files;
}
