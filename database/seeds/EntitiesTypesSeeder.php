<?php

namespace Database\Seeders;

use App\Models\EntitiesType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EntitiesTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        //disable foreign key check for this connection before running seeders
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        EntitiesType::truncate();
        EntitiesType::create(['name'=>'DEPENDENCIA','status_id'=>1]);
        EntitiesType::create(['name'=>'ENTIDAD','status_id'=>1]);
        EntitiesType::create(['name'=>'FIDEICOMISO','status_id'=>1]);
        EntitiesType::create(['name'=>'ORGANISMO','status_id'=>1]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
