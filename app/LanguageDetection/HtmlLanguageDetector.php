<?php

declare(strict_types=1);

namespace App\LanguageDetection;

class HtmlLanguageDetector extends AbstractLanguageDetector
{
    public function detect(string $paste): ?string
    {
        $lines = $this->getNonEmptyTrimmedLines($paste);

        $firstLine = mb_strtolower($lines[0]);

        if (
            $firstLine === '<html>'
            || $firstLine === '<!doctype html>'
            || str_starts_with($firstLine, '<!doctype ')
            || str_starts_with($firstLine, '<html ')
        ) {
            if (str_contains($paste, '<?php') || str_contains($paste, '<?=')) {
                return 'html > php';
            }

            return 'html';
        }

        return null;
    }
}
