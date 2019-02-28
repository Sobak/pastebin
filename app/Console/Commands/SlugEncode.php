<?php

namespace App\Console\Commands;

use App\Services\Slugger;
use Illuminate\Console\Command;

class SlugEncode extends Command
{
    protected $signature = 'slug:encode {id}';

    protected $description = 'Encodes an ID into the alphanumerical slug';

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
        $this->info($this->slugger->encode($this->argument('id')));
    }
}
