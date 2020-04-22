<?php

namespace App\Console\Commands;

use App\Http\Controllers\ProjectController;
use Illuminate\Console\Command;

class CheckProjects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:projects';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks projects URLs and store data to "logs" table';

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
        $controller = new ProjectController();
        $controller->checkProjects();
    }
}
