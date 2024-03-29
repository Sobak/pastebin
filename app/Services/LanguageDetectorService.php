<?php

declare(strict_types=1);

namespace App\Services;

use App\LanguageDetection\AbstractLanguageDetector;
use App\LanguageDetection\CLikeLanguageDetector;
use App\LanguageDetection\DiffLanguageDetector;
use App\LanguageDetection\HtmlLanguageDetector;
use App\LanguageDetection\JsonLanguageDetector;
use App\LanguageDetection\PhpLanguageDetector;
use App\LanguageDetection\ShellLanguageDetector;
use App\LanguageDetection\SqlLanguageDetector;

class LanguageDetectorService
{
    private array $detectors = [
        DiffLanguageDetector::class,
        CLikeLanguageDetector::class,
        HtmlLanguageDetector::class,
        PhpLanguageDetector::class,
        ShellLanguageDetector::class,
        SqlLanguageDetector::class,
        // let's keep it close to an end bc of slowness
        JsonLanguageDetector::class,
    ];

    public function detectLanguage(string $paste): ?string
    {
        foreach ($this->detectors as $detectorFqcn) {
            /** @var AbstractLanguageDetector $detector */
            $detector = new $detectorFqcn;

            if ($detected = $detector->detect($paste)) {
                return $detected;
            }
        }

        return null;
    }
}
