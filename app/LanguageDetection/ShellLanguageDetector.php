<?php

declare(strict_types=1);

namespace App\LanguageDetection;

class ShellLanguageDetector extends AbstractLanguageDetector
{
    public function detect(string $paste): ?string
    {
        $lines = $this->getNonEmptyTrimmedLines($paste);

        if (str_starts_with($lines[0], '#!/')) {
            return 'bash';
        }

        return null;
    }
}
