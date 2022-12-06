<?php

if (!function_exists('sizeInUnits')) {
    function sizeInUnits($size, $unit = 'B')
    {
        if ($unit == 'B') return $size;
        if ($unit == "KB") return round($size / 1024, 4);
        if ($unit == "MB") return round($size / 1024 / 1024, 4);
        if ($unit == "GB") return round($size / 1024 / 1024 / 1024, 4);
    }
}

if (!function_exists('convertToBytes')) {
    function convertToBytes($size, $unit)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];

        $exponent = array_flip($units)[$unit] ?? null;
        if ($exponent === null) {
            return null;
        }

        return $size * (1024 ** $exponent);
    }
}

if (!function_exists('formatSize')) {
    function formatSize($size)
    {
        if ($size < 1000) {
            return $size . ' B';
        } else if ($size < 1000000) {
            return number_format(sizeInUnits($size, 'KB')) . ' KB';
        } else if ($size < (1 * (10 ^ 9))) {
            return number_format(sizeInUnits($size, 'MB')) . ' MB';
        } else if ($size >= (1 * (10 ^ 9))) {
            return number_format(sizeInUnits($size, 'GB')) . ' GB';
        }
    }
}
