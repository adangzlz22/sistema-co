<?php

namespace Database\Seeders;

use App\Models\FileCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FileCategoriesSeeder extends Seeder
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

        FileCategory::truncate();
        FileCategory::create(['name'=>'OTROS','status_id'=>1]);
        FileCategory::create(['name'=>'OFICIO DE INVITACIÓN','status_id'=>1]);
        FileCategory::create(['name'=>'ORDEN DEL DÍA','status_id'=>1]);
        FileCategory::create(['name'=>'LISTA DE ASISTENCIA','status_id'=>1]);
        FileCategory::create(['name'=>'MINUTA DE ACUERDOS','status_id'=>1]);
        FileCategory::create(['name'=>'PRESENTACIONES','status_id'=>1]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
