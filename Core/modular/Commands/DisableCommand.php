<?php

namespace Core\Modular\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Core\modular\Traits\ModularAssetsTrait;

class DisableCommand extends Command
{
    use ModularAssetsTrait;
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:disable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disable the specified module.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $module = $this->laravel['modules']->findOrFail($this->argument('module'));

        if ($module->enabled()) {
            $module->disable();

            $this->renewModulesDbInstance();
            $this->updateAutoloadShell();
            $this->info("Module [{$module}] disabled successful.");
        } else {
            $this->comment("Module [{$module}] has already disabled.");
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('module', InputArgument::REQUIRED, 'Module name.'),
        );
    }
}
