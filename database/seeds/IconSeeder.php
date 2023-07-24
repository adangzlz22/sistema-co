<?php

namespace Database\Seeders;

use App\Models\Icon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IconSeeder extends Seeder
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

        Icon::truncate();

        $csvFile = fopen(base_path("database/data/mdi_icons.csv"), "r");

        $firstline = true;
        $old = array("mdi", "mdi", "-");
        $new   = array("", "", " ");

        while (($data = fgetcsv($csvFile, 7000, ";")) !== FALSE) {


            if (!$firstline) {
                Icon::create([
                    "name" => trim(str_replace($old,$new,$data['1'])),
                    "key" => $data['1'],
                    "active" => true

                ]);
            }
            $firstline = false;

        }
        fclose($csvFile);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
