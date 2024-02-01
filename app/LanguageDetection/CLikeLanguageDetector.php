<?php

declare(strict_types=1);

namespace App\LanguageDetection;

class CLikeLanguageDetector extends AbstractLanguageDetector
{
    public function detect(string $paste): ?string
    {
        $lines = $this->getNonEmptyTrimmedLines($paste);

        if (
            str_starts_with($lines[0], '#include<')
            || str_starts_with($lines[0], '#include ')
            || str_starts_with($lines[0], '#define ')
        ) {
            if (str_contains($paste, 'std::') || str_contains($paste, 'using namespace ')) {
                return 'cpp';
            }

            return 'c';
        }

        return null;
    }
}
