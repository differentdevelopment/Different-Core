<?php

namespace Different\DifferentCore\app\Console\Commands;

use Illuminate\Console\Command;
use Different\DifferentCore\Database\Seeds\DifferentSeeder;

class SeederCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'core:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Feltölti az adatbázist a megfelelő kezdő adatokkal.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $seeder = new DifferentSeeder;
        $seeder->run();
    }
}
