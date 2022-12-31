<?php

namespace Omotolaawokunle\LaravelFiles;

use Exception;
use Omotolaawokunle\LaravelFiles\Contracts\Files;

/**
 * @todo Add code to the empty functions
 */
class File implements Files
{
    private $file;
    private string $path;

    public function __construct($path = null, $name = null, $content = null)
    {
        if (!is_null($path) && !is_null($name)) $this->make($path, $name);
    }

    public function make(string $path = "/", string $name, $content = null, $strict = false): Files
    {
        $this->path = $path . "/$name";
        if (!file_exists($this->path) && $strict) throw new Exception("File not found!");
        if (!file_exists($this->path) && !$strict) return static::create($path, $name, $content);
        return $this;
    }

    public function create(string $path = "/", string $name, $content = null): Files
    {
        if (file_exists("$path/$name")) throw new Exception("File already exists!");
        file_put_contents("$path/$name", $content);
        return static::make($path, $name);
    }

    public function exists($path = null): bool
    {
        return file_exists($path ?? $this->path);
    }

    public function size($unit = 'B', $format = false): string
    {
        $size = filesize($this->path);
        return $format ? sizeInUnits($size, $unit) . " $unit" : sizeInUnits($size, $unit);
    }

    public function delete(): bool
    {
        if (!file_exists($this->path)) throw new Exception('File could not be found!');
        if (unlink($this->path)) {
            return true;
        }
        return false;
    }

    public function clone($path = null, string $name = "", $replace = false): Files
    {
        $name = $name == "" ? $this->nameWithoutExtension() . " (Copy)" : $name;
        $name .= ".{$this->extension()}";
        $newPath = $path == null ? $this->getNewPath($this->path, $name) : $path . "/$name";
        if (file_exists($newPath) && !$replace) throw new Exception("$name already exists!");
        if (copy($this->path, $newPath)) {
            return $path ? static::make($path, $name) : static::make($newPath, "");
        }
        throw new Exception('File could not be copied');
    }

    public function extension()
    {
        return pathinfo($this->path, PATHINFO_EXTENSION);
    }

    public function nameWithoutExtension()
    {
        return basename($this->path, "." . $this->extension());
    }

    private function getNewPath($path, $name)
    {
        return str_replace(basename($path), $name, $path);
    }

    public function move($path = "/"): Files
    {
        if (rename($this->path, $path)) {
            $this->path = $path;
            return $this;
        }
        throw new Exception("Error moving file!");
    }

    public function rename(string $name): Files
    {
        if (pathinfo($name, PATHINFO_EXTENSION) != $this->extension()) {
            $name .= "." . $this->extension();
        }
        if (rename($this->path, $this->getNewPath($this->path, $name))) {
            $this->path = $this->getNewPath($this->path, $name);
            return $this;
        }
        throw new Exception("Error renaming file!");
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getFile($read = true, $write = false, $overwrite = false)
    {
        $this->openFile($read, $write, $overwrite);
        return $this->file;
    }

    public function openFile($read = true, $write = false, $overwrite = false)
    {
        $this->file = fopen($this->path, $this->getMode($read, $write, $overwrite));
        return $this;
    }

    public function closeFile()
    {
        fclose($this->file);
        return $this;
    }

    public function contents()
    {
        return file_get_contents($this->path);
    }

    public function writeToFile($content, $overwrite = false)
    {
        fwrite($this->getFile(true, true, $overwrite), $content);
        $this->closeFile();
        return $this;
    }

    private function getMode(bool $read, bool $write, bool $overwrite)
    {
        if ($read && $write) return $overwrite ? "w+" : "a+";
        if ($read) return "r";
        if ($write) return $overwrite ? "w" : "a";
    }
}
