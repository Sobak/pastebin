<?php

namespace App\Console\Commands;

use App\Models\Paste;
use App\Services\Slugger;
use App\Services\SpamDetectorService;
use Illuminate\Console\Command;

class DetectSpamPastes extends Command
{
    protected $signature = 'paste:detect-spam {--save}';
    protected $description = 'Detect pastes which we consider spam';

    public function handle(SpamDetectorService $spamDetector, Slugger $slugger): int
    {
        $pastes = Paste::all();

        $rows = [];
        $detectedCount = 0;

        /** @var Paste $paste */
        foreach ($pastes as $paste) {
            $isSpam = $spamDetector->isSpamPaste($paste->content);
            if ($isSpam === false) {
                continue;
            }

            $detectedCount++;

            $rows[] = [
                $paste->id,
                config('app.url') . '/' . $slugger->encode($paste->id),
                $paste->author_ip ?? 'UNKNOWN',
            ];

            if ($this->option('save') && $isSpam) {
                $paste->delete();
            }
        }

        $headers = ['ID', 'URL', 'IP Address'];

        $this->table($headers, $rows);

        $this->newLine();
        $this->info("Detected $detectedCount out of {$pastes->count()} pastes");

        return self::SUCCESS;
    }
}
