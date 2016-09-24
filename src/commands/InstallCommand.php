<?php

namespace davestewart\sketchpad\commands;

use davestewart\sketchpad\services\Installer;
use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sketchpad:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs the Sketchpad installation process';

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
     *§
     * @return mixed
     */
    public function handle()
    {
        $installer = new Installer();
        $this->info('Running Sketchpad installer...');
        $installer->install($this);
        $this->info('Sketchpad installer complete!');
    }



}
