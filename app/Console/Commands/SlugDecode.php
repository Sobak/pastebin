<?php

namespace App\Console\Commands;

use App\Services\Slugger;
use Illuminate\Console\Command;

class SlugDecode extends Command
{
    protected $signature = 'slug:decode {slug}';

    protected $description = 'Decodes an alphanumerical slug into the underlying ID';

    protected $slugger;

    public function __construct(Slugger $slugger)
    {
        parent::__construct();

        $this->slugger = $slugger;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $result = $this->slugger->decode($this->argument('slug'));

        if ($result === null) {
            $this->error('Cannot decode given slug');
            exit(1);
        }

        $this->info($result);
    }
}
