<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

//models for
use App\Models\Menu;
use App\Models\Icon;
use App\Models\Category;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
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

        Menu::truncate();

        //Home
        $icon = Icon::Where('name', 'home')->first();
        $navegacion = Category::Where('name', 'Navegación')->first();
        $permiso = Permission::Where('name', 'Consultar Home')->first();
        Menu::create(['name' => 'Inicio', 'dropdown' => false,
            'icon_id' => $icon->id ?? null,
            'category_id' => $navegacion->id ?? null,
            'permission_id' => $permiso->id ?? null,
            'order' => 1]);
        
        
        $icon = Icon::Where('name', 'account group')->first();
        $navegacion = Category::Where('name', 'Modulos')->first();
        $parent = Menu::create(['name' => 'Gabinete', 'dropdown' => true,
            'icon_id' => $icon->id ?? null,
            'category_id' => $navegacion->id ?? null,
            'order' => 1]);

        //Children of modulos

        $icon = Icon::Where('name', 'view dashboard variant outline')->first();
        $permiso = Permission::Where('name', 'Consultar Tablero Gabinete')->first();
        Menu::create(['name' => 'Tablero', 'dropdown' => false,
            'icon_id' => $icon->id ?? null,
            'parent_id' => $parent->id,
            'permission_id' => $permiso->id ?? null,
            'order' => 1]);

        $permiso = Permission::Where('name', 'Consultar Calendario')->first();
        $icon = Icon::Where('name', 'calendar cursor')->first();
        Menu::create(['name' => 'Calendario', 'dropdown' => false,
            'icon_id' => $icon->id ?? null,
            'parent_id' => $parent->id,
            'permission_id' => $permiso->id ?? null,
            'order' => 2]);

        $permiso = Permission::Where('name', 'Consultar Semaforo')->first();
        $icon = Icon::Where('name', 'table')->first();
        Menu::create(['name' => 'Semáforo', 'dropdown' => false,
            'icon_id' => $icon->id ?? null,
            'parent_id' => $parent->id,
            'permission_id' => $permiso->id ?? null,
            'order' => 3]);
        //reunión
        $icon = Icon::Where('name', 'calendar arrow right')->first();
        $permiso = Permission::Where('name', 'Consultar Reuniones')->first();
        Menu::create(['name' => 'Reuniones',
            'dropdown' => false,
            'icon_id' => $icon->id ?? null,
            'parent_id' => $parent->id,
            'permission_id' => $permiso->id ?? null,
            'order' => 4]);
        //Acuerdos
        $icon = Icon::Where('name', 'handshake outline')->first();
        $permiso = Permission::Where('name', 'Consultar Acuerdos')->first();
        Menu::create(['name' => 'Seguimiento de Acuerdos',
            'dropdown' => false,
            'icon_id' => $icon->id ?? null,
            'parent_id' => $parent->id,
            'permission_id' => $permiso->id ?? null,
            'order' => 5]);
        //Salir del sistema
        $icon = Icon::Where('name', 'logout variant')->first();
        $navegacion = Category::Where('name', 'Cerrar Sesión')->first();
        $permiso = Permission::Where('name', 'Salir del sistema')->first();
        Menu::create(['name' => 'Salir del Sistema', 'dropdown' => false,
            'icon_id' => $icon->id ?? null,
            'category_id' => $navegacion->id ?? null,
            'permission_id' => $permiso->id ?? null,
            'order' => 1]);

        //Catálogos
        $icon = Icon::Where('name', 'database outline')->first();
        $navegacion = Category::Where('name', 'Ajustes')->first();
        $parent = Menu::create(['name' => 'Catálogos', 'dropdown' => true,
            'icon_id' => $icon->id ?? null,
            'category_id' => $navegacion->id ?? null,
            'order' => 1]);

        //Children of catalog
        
        //Tipo de reuniones
        $permiso = Permission::Where('name', 'Consultar Tipos de Reuniones')->first();
        Menu::create(['name' => 'Tipo de reuniones',
            'dropdown' => false,
            'permission_id' => $permiso->id ?? null,
            'parent_id' => $parent->id,
            'order' => 2]);
        ///entidad
        $permiso = Permission::Where('name', 'Consultar Instituciones')->first();
        Menu::create(['name' => 'Instituciones',
            'dropdown' => false,
            'permission_id' => $permiso->id ?? null,
            'parent_id' => $parent->id,
            'order' => 3]);
        
        //Lugares
        $permiso = Permission::Where('name', 'Consultar Lugares')->first();
        Menu::create(['name' => 'Lugares',
            'dropdown' => false,
            'permission_id' => $permiso->id ?? null,
            'parent_id' => $parent->id,
            'order' => 4]);

        //Acceso
        $icon = Icon::Where('name', 'clipboard account')->first();
        $navegacion = Category::Where('name', 'Administración')->first();
        $parent = Menu::create(['name' => 'Acceso',
            'dropdown' => true,
            'icon_id' => $icon->id ?? null,
            'category_id' => $navegacion->id ?? null,
            'order' => 1]);
        //Children of Access
        ///Usuarios
        $permiso = Permission::Where('name', 'Consultar Usuarios')->first();
        Menu::create(['name' => 'Usuarios',
            'dropdown' => false,
            'permission_id' => $permiso->id ?? null,
            'parent_id' => $parent->id,
            'order' => 1]);
        ///Roles
        $permiso = Permission::Where('name', 'Consultar Roles')->first();
        Menu::create(['name' => 'Roles',
            'dropdown' => false,
            'permission_id' => $permiso->id ?? null,
            'parent_id' => $parent->id,
            'order' => 2]);
        ///Permisos
        $permiso = Permission::Where('name', 'Consultar Permisos')->first();
        Menu::create(['name' => 'Permisos',
            'dropdown' => false,
            'permission_id' => $permiso->id ?? null,
            'parent_id' => $parent->id,
            'order' => 3]);
        ///Categoria del menu
        $permiso = Permission::Where('name', 'Consultar Categoria')->first();
        Menu::create(['name' => 'Categoría del menú',
            'dropdown' => false,
            'permission_id' => $permiso->id ?? null,
            'parent_id' => $parent->id,
            'order' => 4]);
        ///Iconos
        $permiso = Permission::Where('name', 'Consultar Icono')->first();
        Menu::create(['name' => 'Iconos',
            'dropdown' => false,
            'permission_id' => $permiso->id ?? null,
            'parent_id' => $parent->id,
            'order' => 5]);

        //Menu
        $icon = Icon::Where('name', 'menu')->first();
        $navegacion = Category::Where('name', 'Administración')->first();
        $permiso = Permission::Where('name', 'Consultar Menu')->first();
        Menu::create(['name' => 'Menú',
            'dropdown' => false,
            'icon_id' => $icon->id ?? null,
            'category_id' => $navegacion->id ?? null,
            'permission_id' => $permiso->id ?? null,
            'order' => 2]);

        //bitacora
        $icon = Icon::Where('name', 'book clock outline')->first();
        $navegacion = Category::Where('name', 'Administración')->first();
        $permiso = Permission::Where('name', 'Consultar Bitacora')->first();
        Menu::create(['name' => 'Bitacora',
            'dropdown' => false,
            'icon_id' => $icon->id ?? null,
            'category_id' => $navegacion->id ?? null,
            'permission_id' => $permiso->id ?? null,
            'order' => 3]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
