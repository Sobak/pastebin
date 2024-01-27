<?php

namespace App\Console\Commands;

use App\Models\Paste;
use Illuminate\Console\Command;

class UpdateKey extends Command
{
    protected $signature = 'paste:update-key {old} {new}';
    protected $description = 'Updates pastes where key equals {old} to use {new} key';

    public function handle(): int
    {
        $newKey = bcrypt($this->argument('new'));

        $i = 0;

        Paste::select(['id', 'key'])->chunk(100, function ($chunk) use ($newKey, &$i) {
            /** @var Paste $paste */
            foreach ($chunk as $paste) {
                if (password_verify($this->argument('old'), $paste->key)) {
                    $paste->key = $newKey;
                    $paste->timestamps = false;
                    $paste->save();

                    $i++;
                }
            }
        });

        $this->info("Successfully updated $i pastes");

        return self::SUCCESS;
    }
}
