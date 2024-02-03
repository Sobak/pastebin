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

/**
 * Spam paste detector
 *
 * A very opinionated service for detecting That One Particular™ paste pattern they
 * love adding on paste.sobak.pl. As silly as it seems, this may actually do a trick,
 * especially considering that they post M3U playlist files which are expected to be
 * in a particular format.
 *
 * I can only hope that they'll just choose different service at this point.
 */
class SpamDetectorService
{
    public function isSpamPaste(string $paste): bool
    {
        $lines = array_map(function (string $line) {
            return trim($line);
        }, explode("\n", $paste));

        if ($lines[0] === '#EXTM3U' || str_starts_with($lines[0], '#EXTM3U ')) {
            return true;
        }

        $detectedExtInfCount = 0;
        $detectedLinksCount = 0;
        foreach ($lines as $line) {
            if (str_starts_with($line, '#EXTINF')) {
                ++$detectedExtInfCount;
            }

            /** @noinspection HttpUrlsUsage */
            if (str_starts_with($line, 'http://') || str_starts_with($line, 'https://')) {
                ++$detectedLinksCount;
            }
        }

        if ($detectedExtInfCount > 3 && $detectedLinksCount > 3) {
            return true;
        }

        return false;
    }
}
