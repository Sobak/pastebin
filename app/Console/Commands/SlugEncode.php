<?php

namespace App\Console\Commands;

use App\Services\Slugger;
use Illuminate\Console\Command;

class SlugEncode extends Command
{
    protected $signature = 'slug:encode {id}';
    protected $description = 'Encodes an ID into the alphanumerical slug';

    public function handle(Slugger $slugger): int
    {
        $slug = $slugger->encode($this->argument('id'));

        $this->info($slug);
        $this->newLine();
        $this->line(config('app.url') . '/' . $slug);

        return self::SUCCESS;
    }
}
