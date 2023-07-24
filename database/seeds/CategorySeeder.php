<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
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

        //Category::truncate();
        Category::firstOrCreate(['name' => 'Navegación', 'description' => 'Navegación', 'active' => true, 'order' => 1]);
        Category::firstOrCreate(['name' => 'Modulos', 'description' => 'Modulos', 'active' => true, 'order' => 2]);
        Category::firstOrCreate(['name' => 'Ajustes', 'description' => 'Ajustes', 'active' => true, 'order' => 5]);
        Category::firstOrCreate(['name' => 'Administración', 'description' => 'Administración', 'active' => true, 'order' => 6]);
        Category::firstOrCreate(['name' => 'Cerrar Sesión', 'description' => 'Cerrar Sesión', 'active' => true, 'order' => 7]);


        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
