<?php

namespace App\Console\Commands;

use App\Http\Controllers\ProjectController;
use App\Services\Interfaces\ProjectServiceInterface;
use Illuminate\Console\Command;

class CheckProjects extends Command
{
    /**
     * @var ProjectServiceInterface
     */
    protected $projectServiceInterface;

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
     * CheckProjects constructor.
     * @param ProjectServiceInterface $projectServiceInterface
     */
    public function __construct(ProjectServiceInterface $projectServiceInterface)
    {
        parent::__construct();
        $this->projectServiceInterface = $projectServiceInterface;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $controller = new ProjectController($this->projectServiceInterface);
        $controller->checkProjects();
    }
}
