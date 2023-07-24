<?php

namespace Database\Seeders;

use App\Models\Entity;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EntitiesSeeder extends Seeder
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
        
        $user = new User();
        $user->email = 'super@admin.com';
        $user->password = Hash::make('123456789');
        $user->email_verified_at = now();
        $user->name = 'Admin';
        $user->assignRole('Super Usuario');
        $user->save();

        Entity::truncate();

        $csvFile = fopen(base_path("database/data/ins_pruebas.csv"), "r");

        $firstline = true;

        while (($data = fgetcsv($csvFile, 2000, ";")) !== FALSE) {

                $entity = Entity::create([
                    "id" => $data['0'],
                    "name" => $data['2'],
                    "acronym" => $data['3'],
                    "entities_types_id" => $data['1'],
                    "holder" => $data['4'],
                    "job" => $data['5'],
                    "email" => $data['6'],
                    "user_id" => 1,
                    "status_id" => 1
                ]);

                $user = User::create([
                    "email" => $data['6'],
                    "password" => Hash::make('123456789'),
                    "email_verified_at" => now(),
                    "name" => $data['2'],
                    "entity_id"=>$entity->id
                ]);
                $user->assignRole('Institucion');
        }

        fclose($csvFile);

        $csvFile = fopen(base_path("database/data/us_pruebas.csv"), "r");

        $firstline = true;

        while (($data = fgetcsv($csvFile, 2000, ";")) !== FALSE) {
            $user = User::create([
                "email" => $data['3'],
                "password" => Hash::make('123456789'),
                "email_verified_at" => now(),
                "name" => $data['1'],
                "job" => $data['2'],
                "phone" => $data['4'],
                "entity_id"=>$data['0']
            ]);
            $user->assignRole('Institucion');
        }

        fclose($csvFile);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}