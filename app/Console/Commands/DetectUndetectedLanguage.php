<?php

namespace App\Console\Commands;

use App\Models\Paste;
use App\Services\LanguageDetectorService;
use App\Services\Slugger;
use Illuminate\Console\Command;

class DetectUndetectedLanguage extends Command
{
    protected $signature = 'paste:detect-language {--language=} {--save}';
    protected $description = 'Try detecting language for pastes with no language provided';

    public function handle(LanguageDetectorService $languageDetector, Slugger $slugger): int
    {
        $pastes = Paste::query()
            ->whereNull('language')
            ->orWhere('language', '=', 'plaintext') // for legacy pastes
            ->get();

        $rows = [];
        $detectedCount = 0;

        /** @var Paste $paste */
        foreach ($pastes as $paste) {
            $language = $languageDetector->detectLanguage($paste->content);
            if ($language !== null) {
                $detectedCount++;
            }

            if ($languageFilter = $this->option('language')) {
                if ($language !== $languageFilter) {
                    continue;
                }
            }

            $rows[] = [
                $paste->id,
                config('app.url') . '/' . $slugger->encode($paste->id),
                $language ? "<fg=green>$language</>" : 'UNKNOWN',
            ];

            if ($this->option('save') && $language !== null) {
                $paste->language = $language;
                $paste->timestamps = false;
                $paste->save();
            }
        }

        $headers = ['ID', 'URL', 'Language'];

        $this->table($headers, $rows);

        $this->newLine();
        $this->info("Detected $detectedCount out of {$pastes->count()} pastes");

        return self::SUCCESS;
    }
}
