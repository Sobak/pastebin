<?php

declare(strict_types=1);

namespace App\LanguageDetection;

class JsonLanguageDetector extends AbstractLanguageDetector
{
    public function detect(string $paste): ?string
    {
        $paste = trim($paste);

        // Let's intentionally ignore scalar values
        if (!str_starts_with($paste, '{') && !str_starts_with($paste, '[')) {
            return null;
        }

        try {
            // TODO: Replace with json_validate() on PHP 8.3+
            json_decode($paste, null,512, JSON_THROW_ON_ERROR);

            return 'json';
        } catch (\JsonException $exception) {
            return null;
        }
    }
}
