<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
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

        Status::truncate();
        Status::create(['name'=>'ACTIVO','color'=>'success','active'=>true]);
        Status::create(['name'=>'INACTIVO','color'=>'secondary','active'=>true]);
        Status::create(['name'=>'POR_CELEBRAR','color'=>'primary','active'=>true]);
        Status::create(['name'=>'CELEBRADA','color'=>'success','active'=>true]);
        Status::create(['name'=>'SIN_AVANCE','color'=>'secondary','active'=>true]);
        Status::create(['name'=>'EN_PROCESO','color'=>'warning','active'=>true]);
        Status::create(['name'=>'CONCLUIDO','color'=>'success','active'=>true]);
        Status::create(['name'=>'CANCELADA','color'=>'dark','active'=>true]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
