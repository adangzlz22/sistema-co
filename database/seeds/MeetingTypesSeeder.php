<?php

namespace Database\Seeders;

use App\Models\MeetingType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MeetingTypesSeeder extends Seeder
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

        MeetingType::truncate();
        MeetingType::create(['name'=>'GABINETE LEGAL Y AMPLIADO', 'acronym'=>'RGLA','color'=>'#DC7F37','user_id'=>1,'status_id'=>1]);
        MeetingType::create(['name'=>'GABINETE LEGAL', 'acronym'=>'RGL','color'=>'#B94645','user_id'=>1,'status_id'=>1]);
        MeetingType::create(['name'=>'GABINETES SECTORIALES', 'acronym'=>'SECTORIAL','color'=>'#410324','user_id'=>1,'status_id'=>1]);
        MeetingType::create(['name'=>'GABINETE REGIONAL', 'acronym'=>'REGIONAL','color'=>'#960E53','user_id'=>1,'status_id'=>1]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
