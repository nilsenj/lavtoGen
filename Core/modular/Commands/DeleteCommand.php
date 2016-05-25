<?php
/**
 * Created by PhpStorm.
 * User: nilse
 * Date: 3/16/2016
 * Time: 5:29 AM
 */

namespace Core\modular\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Console\Input\InputArgument;
use Core\modular\Traits\ModularAssetsTrait;

class DeleteCommand extends Command
{
    use ModularAssetsTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete the specified module.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $module = $this->laravel['modules']->findOrFail($this->argument('module'));

        if ($module->enabled() && $module->moduleIsDefault() || $module->enabled()) {

            $password = $this->secret('What is LOCAL_ADMIN password?');

            if ($password == env("LOCAL_ADMIN")) {

                if($this->confirm("Are YOU sure to delete Default Enabled Module From Entire System [yes|no]")) {

                    $module->delete();
                    // The passwords match...
                    $this->info("Module [{$module}] deleted successful. See if the App still working");
                    $this->updateAutoloadShell();
                    $this->renewModulesDbInstance();
                    $this->info("Yeah everything done. Continue working.");

                } else {

                    $this->info("Module [{$module}] not deleted.");

                    return;
                }

            } else {
                $this->error("You don't have a permission.");
                return;
            }
        } elseif ($module->disabled() && !$module->moduleIsDefault()) {

            $module->delete();
            // The passwords match...
            $this->info("Module [{$module}] deleted successful.");

        } else {

            $this->comment("Module [{$module}] not found.");
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