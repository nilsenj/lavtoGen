<?php
/**
 * Created by PhpStorm.
 * User: nilse
 * Date: 3/16/2016
 * Time: 6:23 AM
 */

namespace Core\modular\Traits;

use Illuminate\Support\Facades\DB;
use Core\Messenger\Models\Thread;
use Core\Models\ModuleLayer;
use Core\Modular\Module;

trait ModularAssetsTrait
{

    public function renewModulesDbInstance() {

        if (\Schema::hasTable('modules')) {

            DB::table('modules')->truncate();

            foreach (\Core\Modular\Facades\Module::all() as $key => $module) {
               $module = \Core\Models\ModuleLayer::create([
                    "name" => $module->getName(),
                    "created" => $module->moduleCreated(),
                    "default" => $module->moduleIsDefault(),
                    "private" => $module->modulePrivacy(),
                    'status' => $module->moduleStatus()
                ]);
            }

            foreach (ModuleLayer::all() as $module) {

                $thread = Thread::create(
                    [
                        'subject' => [$module->name],
                    ]
                );
                $module->update(["thread_id", $thread->id]);
                $module->makeDefaultModuleImg($module);
            }

        }

    }

    public function updateAutoloadShell() {
        shell_exec('composer dump-autoload');
        shell_exec('composer update');
    }
}