<?php

namespace Omotolaawokunle\LaravelFiles;

use Exception;
use Illuminate\Support\Collection;
use Omotolaawokunle\LaravelFiles\Contracts\Files;
use Omotolaawokunle\LaravelFiles\Facades\File;

/**
 * @todo Add code to the empty functions
 */
class Directory implements Files
{
    private $directory;
    private string $path = "";

    public function __construct($path = "/", $name)
    {
        $this->make($path, $name);
    }

    public function make(string $path = "/", string $name, bool $strict = false): Files
    {
        $this->path = $path . "/$name";
        if (!is_dir($this->path) && $strict) throw new Exception("Directory not found!");

        if (!is_dir($this->path) && !$strict) return static::create($path, $name);
        $directory = opendir($this->path);
        if (!$directory) throw new Exception("Directory could not be found!");
        closedir($directory);
        return $this;
    }

    public function create(string $path = "/", string $name, $content = null): Files
    {
        if (!file_exists($path . "/$name")) {
            mkdir($path . "/$name");
        } else {
            if (is_file($path . "/$name")) throw new Exception("A file with the name $name already exists!");
        }
        return $this->make($path, $name);
    }

    public function exists($path = ""): bool
    {
        return file_exists($path ?? $this->path) && is_dir($path ?? $this->path);
    }

    public function size($unit = 'B', $format = false): string
    {
        $size = 0;
        foreach (glob(rtrim($this->path, '/') . '/*', GLOB_NOSORT) as $each) {
            $size += is_file($each) ? filesize($each) : $this->size($each);
        }
        return $format ? sizeInUnits($size, $unit) . " $unit" : sizeInUnits($size, $unit);
    }

    public function delete(): bool
    {
        if (is_null($this->path)) throw new Exception("Path is not set for directory!");
        $this->contents()->each(function (Files $content) {
            $content->delete();
        });
        $this->closeDirectory();
        rmdir($this->path);
        
        return true;
    }

    public function clone($path = "/", $name = null): Files
    {
        $newDir = static::create($path, $name);;
        return $newDir;
    }

    public function move($path = "/"): Files
    {
        if (rename($this->path, $path)) {
            $this->path = $path;
            return $this;
        }
        throw new Exception("Error moving directory!");
    }

    public function rename(string $name): Files
    {
        try {
            return $this->move($this->getNewPath($this->path, $name));
        } catch (Exception $e) {
            throw new Exception("Error renaming directory!");
        }
    }

    private function getNewPath($path, $name)
    {
        $pathArray = explode('/', $path);
        $pathArray[count($pathArray) - 1] = $name;
        return implode('/', $pathArray);
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getDirectory()
    {
        $this->directory = opendir($this->path);
        return $this->directory;
    }

    public function closeDirectory()
    {
        closedir($this->directory);
        return;
    }

    /**
     * Get contents of directory
     *
     * @return Collection
     */
    public function contents(): Collection
    {
        return collect(scandir($this->path))->filter(function ($content) {
            return $content != "." && $content != "..";
        })->map(function ($content) {
            if (is_file($this->path . "/$content")) return File::make($this->path, $content);
            if (is_dir($this->path . "/$content")) return static::make($this->path, $content);
        });
    }
}
