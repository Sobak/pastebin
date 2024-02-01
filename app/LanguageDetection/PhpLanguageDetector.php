<?php

declare(strict_types=1);

namespace App\LanguageDetection;

class PhpLanguageDetector extends AbstractLanguageDetector
{
    public function detect(string $paste): ?string
    {
        $lines = $this->getNonEmptyTrimmedLines($paste);

        if ($lines[0] === '<?php') {
            return 'php';
        }

        return null;
    }
}
