<?php

namespace Database\Seeders;

use App\Models\Modality;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModalitiesSeeder extends Seeder
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

        Modality::truncate();
        Modality::create(['name'=>'PRESENCIAL','color'=>'#FF9B9B']);
        Modality::create(['name'=>'VIRTUAL','color'=>'#BBFFFF']);
        Modality::create(['name'=>'MIXTA','color'=>'#00B336']);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
