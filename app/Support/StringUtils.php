<?php

declare(strict_types=1);

namespace App\Support;

class StringUtils
{
    public static function toFilename(string $input, string $fallback): string
    {
        $input = trim($input, '. ');

        if (empty($input)) {
            return $fallback;
        }

        $reservedWindowsFilenames = [
            'CON', 'PRN', 'AUX', 'NUL' ,'COM1', 'COM2', 'COM3', 'COM4', 'COM5',
            'COM6', 'COM7', 'COM8', 'COM9', 'LPT1', 'LPT2', 'LPT3', 'LPT4',
            'LPT5', 'LPT6', 'LPT7', 'LPT8', 'LPT9',
        ];

        if (in_array(mb_strtoupper($input), $reservedWindowsFilenames)) {
            return $fallback;
        }

        if (preg_match('#[^a-z_. \-0-9\p{L}]#ui', $input)) {
            return $fallback;
        }

        return $input;
    }
}
