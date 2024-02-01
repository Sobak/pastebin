<?php

declare(strict_types=1);

namespace App\LanguageDetection;

class DiffLanguageDetector extends AbstractLanguageDetector
{
    public function detect(string $paste): ?string
    {
        $lines = $this->getNonEmptyTrimmedLines($paste);

        foreach ($lines as $line) {
            if ($line === '--- /dev/null' || str_starts_with($line, '--- a/')) {
                return 'diff';
            }
        }

        return null;
    }
}
