<?php

namespace Database\Seeders;

use App\Helpers\HelperApp;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\GroupPermission;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->email = 'admin@admin.com';
        $user->password = Hash::make('123456789');
        $user->email_verified_at = now();
        $user->name = 'Luis Carlos Arredondo';
        $user->assignRole('Super Usuario');
        $user->save();

        //asigamos todos los permisos
        $role =  Role::find(1);
        $permissions = Permission::all()->toArray();

        $role->permissions()->detach();
        if (!empty($permissions)) {
            foreach ($permissions as $perm) {
                $permission = Permission::where('id', $perm)->first();
                $role->permissions()->attach($permission);
            }
        }

        Artisan::call('cache:clear');
        Artisan::call('config:cache');
        Artisan::call('config:clear');
        Artisan::call('optimize:clear');


        $user = new User();
        $user->email = 'leonf.jorge@sonora.edu.mx';
        $user->password = Hash::make('123456789');
        $user->email_verified_at = now();
        $user->name = 'Dependencia';
        $user->entity_id = 1;
        $user->assignRole('Institucion');
        $user->save();

        $role =  Role::find(2);
        $permissions = Permission::whereIn('name',[
            'Consultar Tablero Gabinete',
            'Consultar Calendario',
            'Consultar Reuniones',
            'Consultar Asuntos',
            'Consultar Acuerdos',
            'Consultar Avances',
            'Crear Acciones',
            'Crear Avances',
            'Consultar Home',
            'Salir del sistema'
        ])->get()->toArray();

        $role->permissions()->detach();
        if (!empty($permissions)) {
            foreach ($permissions as $perm) {
                $permission = Permission::where('id', $perm)->first();
                $role->permissions()->attach($permission);
            }
        }
    }
}
