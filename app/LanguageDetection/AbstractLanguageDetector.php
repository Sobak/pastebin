<?php

declare(strict_types=1);

namespace App\LanguageDetection;

abstract class AbstractLanguageDetector
{
    abstract public function detect(string $paste): ?string;

    protected function getNonEmptyTrimmedLines(string $paste): array
    {
        $trimmedLines = array_map(function (string $line) {
            return trim($line);
        }, explode("\n", $paste));

        $nonEmptyLines = array_filter($trimmedLines, function (string $line) {
            return !empty(trim($line));
        });

        // Reset keys
        return array_values($nonEmptyLines);
    }
}
