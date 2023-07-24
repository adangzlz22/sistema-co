<?php

return [

    /*
    |--------------------------------------------------------------------------
    | View Storage Paths
    |--------------------------------------------------------------------------
    |
    | Most templating systems load templates from disk. Here you may specify
    | an array of paths that should be checked for your views. Of course
    | the usual Laravel view path has already been registered for you.
    |
    */

    'menu' => [
        [
            'text' => 'Navegación',
            'is_header' => true
        ], [
            'url' => '/',
            'icon' => 'mdi mdi-view-dashboard-variant-outline',
            'text' => 'Inicio'
        ]/*
        ,[
            'is_divider' => true
        ],[
            'text' => 'Seguimiento',
            'is_header' => true
        ],[
            'icon' => 'mdi mdi-file-check-outline',
            'text' => 'Acuerdos',
            'children' => [[
                    'url' => '/acuerdos/nuevo',
                    'text' => 'Nuevo Acuerdo',
                ], [
                    'url' => '/acuerdos/seguimiento',
                    'text' => 'Listado de Acuerdos',
                ]
            ],
        ]*/
        , [
            'is_divider' => true
        ], [
            'text' => 'Ajustes',
            'is_header' => true
        ], [
            'icon' => 'mdi mdi-database-outline',
            'text' => 'Catálogos',
            'children' => [
                [
                    'url' => '/instituciones',
                    'text' => 'Instituciones'
                ], [
                    'url' => '/tipos-de-instituciones',
                    'text' => 'Tipos de Instituciones',
                ], [
                    'url' => '/unidades-administrativas',
                    'text' => 'Unidades administrativas',
                ]
            ],
        ],[
            'is_divider' => true
        ],[
            'text' => 'Administración',
            'is_header' => true
        ],[
            'icon' => 'mdi mdi-account-group',
            'text' => 'Acceso',
            'children' => [
                [
                    'url' => '/usuarios',
                    'text' => 'Usuarios',
                ], [
                    'url' => '/roles',
                    'text' => 'Roles',
                ], [
                    'url' => '/permisos',
                    'text' => 'Permisos',
                ],
                [
                    'url' => '/categoria',
                    'text' => 'Categoria del Menú',
                ],
                [
                    'url' => '/icono',
                    'text' => 'Iconos',
                ],
            ]
        ],[
            'icon' => 'mdi mdi-book-clock-outline',
            'text' => 'Bitácora',
            'url' => '/bitacora/',
        ], [
            'is_divider' => true
        ]
        /*
        ,[
            'text' => 'Reportes',
            'is_header' => true
        ],[
            'url' => '/reports/agreements',
            'icon' => 'mdi mdi-file',
            'text' => 'Acuerdos',
        ],[
            'is_divider' => true
        ]*/
        ,[
            'text' => 'Cerrar Sesión',
            'is_header' => true
        ], [
            'url' => '/salir',
            'icon' => 'mdi mdi-logout-variant',
            'text' => 'Salir del Sistema'
        ]
    ]


];
