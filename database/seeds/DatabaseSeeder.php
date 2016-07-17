<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->call(ModuleLayerSeeder::class);
        $this->command->info('Seeded the Modules data!');
        Model::reguard();
    }
}
