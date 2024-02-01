<?php

declare(strict_types=1);

namespace App\LanguageDetection;

class SqlLanguageDetector extends AbstractLanguageDetector
{
    public function detect(string $paste): ?string
    {
        // It's a subset of keywords only - this is for the sake of simplicity,
        // but also to reduce the likelihood of classifying regular text as SQL
        $keywords = [
            'SELECT', 'FROM', 'ALTER', 'UPDATE', 'DELETE', 'TRUNCATE', 'TABLE',
        ];

        $paste = mb_strtoupper($paste);

        $keywordsCount = 0;
        foreach ($keywords as $keyword) {
            $keywordsCount += substr_count($paste, "$keyword ");
        }

        if ($keywordsCount < 2) {
            return null;
        }

        // That's super arbitrary of course, but I found out
        // it helps a lot with preventing false-positives
        if (count(explode("\n", $paste)) > 8) {
            return null;
        }

        // Reduce the chance of classifying regular text as SQL even further
        if (
            !str_contains($paste, ' *')
            && !str_contains($paste, "`")
            && !str_contains($paste, '=')
        ) {
            return null;
        }

        return 'sql';
    }
}
