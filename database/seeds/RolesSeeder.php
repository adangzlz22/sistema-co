<?php

namespace Database\Seeders;

use App\Helpers\HelperApp;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Facades\Hash;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $new_role = new Role();
        $new_role->name = "Super Usuario";
        $new_role->save();

        //Rol entidad
        $new_role = new Role();
        $new_role->name = "Institucion";
        $new_role->save();

        // //Consultivo Revisor
        // $new_role = new Role();
        // $new_role->name = "Administrador Consultivo";
        // $new_role->save();

        // //Consultivo
        // $new_role = new Role();
        // $new_role->name = "Consultivo Revisor";
        // $new_role->save();

        //dependencia o entidad
        // $new_role = new Role();
        // $new_role->name = HelperApp::$roleDependenciaEntidad;
        // $new_role->save();

    }
}
