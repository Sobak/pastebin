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

            // TODO: Replace with regex to match more variants (ld+json, <script defer> etc)
            if (str_contains($paste, '<script>') || str_contains($paste, '<script type="text/javascript">')) {
                return 'html > js';
            }

            if (str_contains($paste, '<style>') || str_contains($paste, '<style type="text/css">')) {
                return 'html > css'; // Limitation of KeyLighter, we can't embed more than one language
            }

            return 'html';
        }

        return null;
    }
}
