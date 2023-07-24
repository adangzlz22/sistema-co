<?php

namespace Database\Seeders;

use App\Models\Agreement;
use App\Models\GroupPermission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;


use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Model::unguard();
        //disable foreign key check for this connection before running seeders
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');


        // firstOrCreate permissions
        //Permission::truncate();

        $usaurios = GroupPermission::firstOrCreate(['name' => 'Usuarios']);
        Permission::firstOrCreate(['name' => 'Consultar Usuarios','route'=> 'users.index','group_permission_id'=>$usaurios->id]);
        Permission::firstOrCreate(['name' => 'Crear Usuarios','group_permission_id'=>$usaurios->id]);
        Permission::firstOrCreate(['name' => 'Editar Usuarios','group_permission_id'=>$usaurios->id]);
        Permission::firstOrCreate(['name' => 'Eliminar Usuarios','group_permission_id'=>$usaurios->id]);
        Permission::firstOrCreate(['name' => 'Activar Usuarios','group_permission_id'=>$usaurios->id]);
        Permission::firstOrCreate(['name' => 'Desactivar Usuarios','group_permission_id'=>$usaurios->id]);

        $roles = GroupPermission::firstOrCreate(['name' => 'Roles']);
        Permission::firstOrCreate(['name' => 'Consultar Roles','route'=> 'roles.index','group_permission_id'=>$roles->id]);
        Permission::firstOrCreate(['name' => 'Crear Roles','group_permission_id'=>$roles->id]);
        Permission::firstOrCreate(['name' => 'Editar Roles','group_permission_id'=>$roles->id]);
        Permission::firstOrCreate(['name' => 'Eliminar Roles','group_permission_id'=>$roles->id]);
        Permission::firstOrCreate(['name' => 'Asignar Permisos','group_permission_id'=>$roles->id]);

        $permisos = GroupPermission::firstOrCreate(['name' => 'Permisos']);
        Permission::firstOrCreate(['name' => 'Consultar Permisos','route'=> 'permissions.index','group_permission_id'=>$permisos->id]);
        Permission::firstOrCreate(['name' => 'Crear Permisos','group_permission_id'=>$permisos->id]);
        Permission::firstOrCreate(['name' => 'Eliminar Permisos','group_permission_id'=>$permisos->id]);
        Permission::firstOrCreate(['name' => 'Editar Permisos','group_permission_id'=>$permisos->id]);

        $bitacoras = GroupPermission::firstOrCreate(['name' => 'Bitacoras']);
        Permission::firstOrCreate(['name' => 'Consultar Bitacora','route'=> 'logs.index','group_permission_id'=>$bitacoras->id]);

        $category = GroupPermission::firstOrCreate(['name' => 'Categoría']);
        Permission::firstOrCreate(['name' => 'Consultar Categoria','route'=> 'categories.index','group_permission_id'=>$category->id]);
        Permission::firstOrCreate(['name' => 'Crear Categoria','group_permission_id'=>$category->id]);
        Permission::firstOrCreate(['name' => 'Editar Categoria','group_permission_id'=>$category->id]);
        Permission::firstOrCreate(['name' => 'Eliminar Categoria','group_permission_id'=>$category->id]);

        $icon = GroupPermission::firstOrCreate(['name' => 'Icono']);
        Permission::firstOrCreate(['name' => 'Consultar Icono','route'=> 'icons.index','group_permission_id'=>$icon->id]);
        Permission::firstOrCreate(['name' => 'Crear Icono','group_permission_id'=>$icon->id]);
        Permission::firstOrCreate(['name' => 'Editar Icono','group_permission_id'=>$icon->id]);
        Permission::firstOrCreate(['name' => 'Eliminar Icono','group_permission_id'=>$icon->id]);

        $menu = GroupPermission::firstOrCreate(['name' => 'Menú']);
        Permission::firstOrCreate(['name' => 'Consultar Menu','route'=> 'menus.index','group_permission_id'=>$menu->id]);
        Permission::firstOrCreate(['name' => 'Crear Menu','group_permission_id'=>$menu->id]);
        Permission::firstOrCreate(['name' => 'Editar Menu','group_permission_id'=>$menu->id]);
        Permission::firstOrCreate(['name' => 'Eliminar Menu','group_permission_id'=>$menu->id]);

        $home = GroupPermission::firstOrCreate(['name' => 'Home']);
        Permission::firstOrCreate(['name' => 'Consultar Home','route'=> 'home','group_permission_id'=>$home->id]);

        $logOff = GroupPermission::firstOrCreate(['name' => 'Salir del sistema']);
        Permission::firstOrCreate(['name' => 'Salir del sistema','route'=> 'system.logoff','group_permission_id'=>$logOff->id]);

        $tipos_reuniones = GroupPermission::firstOrCreate(['name' => 'Tipos de Reuniones']);
        Permission::firstOrCreate(['name' => 'Consultar Tipos de Reuniones','route'=> 'meeting_types.index','group_permission_id'=>$tipos_reuniones->id]);
        Permission::firstOrCreate(['name' => 'Crear Tipos de Reuniones','group_permission_id'=>$tipos_reuniones->id]);
        Permission::firstOrCreate(['name' => 'Editar Tipos de Reuniones','group_permission_id'=>$tipos_reuniones->id]);
        Permission::firstOrCreate(['name' => 'Eliminar Tipos de Reuniones','group_permission_id'=>$tipos_reuniones->id]);

        $reuniones = GroupPermission::firstOrCreate(['name' => 'Reuniones']);
        Permission::firstOrCreate(['name' => 'Consultar Reuniones','route'=> 'meetings.index','group_permission_id'=>$reuniones->id]);
        Permission::firstOrCreate(['name' => 'Crear Reuniones','group_permission_id'=>$reuniones->id]);
        Permission::firstOrCreate(['name' => 'Editar Reuniones','group_permission_id'=>$reuniones->id]);
        Permission::firstOrCreate(['name' => 'Eliminar Reuniones','group_permission_id'=>$reuniones->id]);

        $instituciones = GroupPermission::firstOrCreate(['name' => 'Instituciones']);
        Permission::firstOrCreate(['name' => 'Consultar Instituciones','route'=> 'entities.index','group_permission_id'=>$instituciones->id]);
        Permission::firstOrCreate(['name' => 'Crear Instituciones','group_permission_id'=>$instituciones->id]);
        Permission::firstOrCreate(['name' => 'Editar Instituciones','group_permission_id'=>$instituciones->id]);
        Permission::firstOrCreate(['name' => 'Eliminar Instituciones','group_permission_id'=>$instituciones->id]);

        $lugares = GroupPermission::firstOrCreate(['name' => 'Lugares']);
        Permission::firstOrCreate(['name' => 'Consultar Lugares','route'=> 'places.index','group_permission_id'=>$lugares->id]);
        Permission::firstOrCreate(['name' => 'Crear Lugares','group_permission_id'=>$lugares->id]);
        Permission::firstOrCreate(['name' => 'Editar Lugares','group_permission_id'=>$lugares->id]);
        Permission::firstOrCreate(['name' => 'Eliminar Lugares','group_permission_id'=>$lugares->id]);

        $asuntos = GroupPermission::firstOrCreate(['name' => 'Asuntos']);
        Permission::firstOrCreate(['name' => 'Consultar Asuntos','group_permission_id'=>$asuntos->id]);
        Permission::firstOrCreate(['name' => 'Crear Asuntos','group_permission_id'=>$asuntos->id]);
        Permission::firstOrCreate(['name' => 'Editar Asuntos','group_permission_id'=>$asuntos->id]);
        Permission::firstOrCreate(['name' => 'Eliminar Asuntos','group_permission_id'=>$asuntos->id]);

        $acuerdos = GroupPermission::firstOrCreate(['name' => 'Acuerdos']);
        Permission::firstOrCreate(['name' => 'Consultar Acuerdos','route'=> 'agreements.index','group_permission_id'=>$acuerdos->id]);
        Permission::firstOrCreate(['name' => 'Crear Acuerdos','group_permission_id'=>$acuerdos->id]);
        Permission::firstOrCreate(['name' => 'Editar Acuerdos','group_permission_id'=>$acuerdos->id]);
        Permission::firstOrCreate(['name' => 'Eliminar Acuerdos','group_permission_id'=>$acuerdos->id]);

        $acciones = GroupPermission::firstOrCreate(['name' => 'Acciones']);
        Permission::firstOrCreate(['name' => 'Crear Acciones','group_permission_id'=>$acciones->id]);
        Permission::firstOrCreate(['name' => 'Editar Acciones','group_permission_id'=>$acciones->id]);
        Permission::firstOrCreate(['name' => 'Eliminar Acciones','group_permission_id'=>$acciones->id]);

        $avances = GroupPermission::firstOrCreate(['name' => 'Avances']);
        Permission::firstOrCreate(['name' => 'Consultar Avances','group_permission_id'=>$avances->id]);
        Permission::firstOrCreate(['name' => 'Crear Avances','group_permission_id'=>$avances->id]);
        Permission::firstOrCreate(['name' => 'Editar Avances','group_permission_id'=>$avances->id]);
        Permission::firstOrCreate(['name' => 'Eliminar Avances','group_permission_id'=>$avances->id]);

        $calendario = GroupPermission::firstOrCreate(['name' => 'Calendario']);
        Permission::firstOrCreate(['name' => 'Consultar Calendario','route'=> 'calendar.index','group_permission_id'=>$calendario->id]);
        
        $semaforo = GroupPermission::firstOrCreate(['name' => 'Semaforo']);
        Permission::firstOrCreate(['name' => 'Consultar Semaforo','route'=> 'indicators.index','group_permission_id'=>$semaforo->id]);
        
        $tablero = GroupPermission::firstOrCreate(['name' => 'Tablero']);
        Permission::firstOrCreate(['name' => 'Consultar Tablero Gabinete','route'=> 'dashboard.gabinete.index','group_permission_id'=>$tablero->id]);
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
