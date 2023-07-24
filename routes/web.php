<?php

use App\Http\Controllers\AdministrativeUnitsController;
use App\Http\Controllers\AgrmntAgreementController;
use App\Http\Controllers\AgrmntAttachDocumentController;
use App\Http\Controllers\AgrmntInstitutionController;
use App\Http\Controllers\AgrmntSigningFunctionaryController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactOpinionBCController;
use App\Http\Controllers\ContactOpinionSHController;
use App\Http\Controllers\ExpedientTypeController;
use App\Http\Controllers\IconController;
use App\Http\Controllers\JudgmentAttachSynthesisController;
use App\Http\Controllers\JudgmentController;
use App\Http\Controllers\JudgmentSharedController;
use App\Http\Controllers\JurisdictionalInstitutionController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ContractTypeController;
use App\Http\Controllers\JurisdictionContractController;
use App\Http\Controllers\PaymentMethodsController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\SigningFunctionaryController;
use App\Http\Controllers\ContractManagerController;
use App\Http\Controllers\AttachContractDocumentController;
use App\Http\Controllers\ContractGuaranteeController;
use App\Http\Controllers\ContractGuaranteeDocumentController;
use App\Http\Controllers\ContractOpinionController;
use App\Http\Controllers\ContractCommentController;
use App\Http\Controllers\ContractSharedController;
use App\Http\Controllers\NotificationController;
//use App\Http\Controllers\MeetingTypeController;
//use App\Http\Controllers\EntitiesController;

use App\Http\Controllers\ReportController;
use App\Http\Controllers\TypeAgreementController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Event;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/auto-pull-deploy', function () {
    shell_exec( 'cd /var/www/gi.sonora.gob.mx/; git reset --hard origin/deploy; git pull https://gitlab-ci-token:glpat-FD9DmzNQppzZk7Eje7Pj@gitlab.com/jorge0993/sistema-oe.git deploy' );
});

Route::get('/auto-pull-dev', function () {
    // shell_exec( 'cd /var/www/devgi.sonora.gob.mx/; git reset --hard origin/development; git pull https://gitlab-ci-token:glpat-FD9DmzNQppzZk7Eje7Pj@gitlab.com/jorge0993/sistema-oe.git development' );
});

Route::get('/clear', function() {
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('config:cache');
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    return "La cache ha sido borrada";
});


Route::get("/broadcast", function () {
    event(new \App\Events\MenuMessage);
    return "Se mando el mensaje";
});


Route::get('/home', function () {
    return redirect('/');
});


//Rutas de Log In, Register, etc
Auth::routes(['register'=>false]);

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::group(['middleware' => ['auth']], function () {

Route::get('/perfil/', [App\Http\Controllers\UserController::class, 'profile'])->name('profile');
Route::post('/perfil/avatar', [App\Http\Controllers\UserController::class, 'avatar'])->name('profile.avatar');
Route::post('/perfil/contrasena', [App\Http\Controllers\UserController::class, 'passwordUpdate'])->name('profile.password');
Route::post('/perfil/information', [App\Http\Controllers\UserController::class, 'information'])->name('profile.information');

#endregion

#region Category
Route::get('categoria', [CategoryController::class, 'index'])->name('categories.index')->middleware(['permission:Consultar Categoria']);
Route::post('categoria', [CategoryController::class, 'index'])->name('categories.index')->middleware(['permission:Consultar Categoria']);
Route::get('categoria/crear', [CategoryController::class, 'create'])->name('categories.create')->middleware(['permission:Crear Categoria']);
Route::post('categoria/crear', [CategoryController::class, 'store'])->name('categories.store')->middleware(['permission:Crear Categoria']);
Route::get('categoria/{id}/eliminar', [CategoryController::class, 'destroy'])->name('categories.destroy')->middleware(['permission:Eliminar Categoria']);
Route::get('categoria/{id}/editar', [CategoryController::class, 'edit'])->name('categories.edit')->middleware(['permission:Editar Categoria']);
Route::put('categoria/{id}/editar', [CategoryController::class, 'update'])->name('categories.update')->middleware(['permission:Editar Categoria']);
Route::put('categoria/ordenar/opciones', [CategoryController::class, 'sortable'])->name('categories.sortable')->middleware(['permission:Crear Categoria']);

#endregion


#region Icon
Route::get('icono', [IconController::class, 'index'])->name('icons.index')->middleware(['permission:Consultar Icono']);
Route::post('icono', [IconController::class, 'index'])->name('icons.index')->middleware(['permission:Consultar Icono']);
Route::get('icono/crear', [IconController::class, 'create'])->name('icons.create')->middleware(['permission:Crear Icono']);
Route::post('icono/crear', [IconController::class, 'store'])->name('icons.store')->middleware(['permission:Crear Icono']);
Route::get('icono/{id}/eliminar', [IconController::class, 'destroy'])->name('icons.destroy')->middleware(['permission:Eliminar Icono']);
Route::get('icono/{id}/editar', [IconController::class, 'edit'])->name('icons.edit')->middleware(['permission:Editar Icono']);
Route::put('icono/{id}/editar', [IconController::class, 'update'])->name('icons.update')->middleware(['permission:Editar Icono']);
#endregion

#region Menu
Route::get('menu', [MenuController::class, 'index'])->name('menus.index')->middleware(['permission:Consultar Menu']);
Route::post('menu', [MenuController::class, 'index'])->name('menus.index')->middleware(['permission:Consultar Menu']);
Route::get('menu/crear', [MenuController::class, 'create'])->name('menus.create')->middleware(['permission:Crear Menu']);
Route::post('menu/crear', [MenuController::class, 'store'])->name('menus.store')->middleware(['permission:Crear Menu']);
Route::get('menu/{id}/eliminar', [MenuController::class, 'destroy'])->name('menus.destroy')->middleware(['permission:Eliminar Menu']);
Route::get('menu/{id}/editar', [MenuController::class, 'edit'])->name('menus.edit')->middleware(['permission:Editar Menu']);
Route::put('menu/{id}/editar', [MenuController::class, 'update'])->name('menus.update')->middleware(['permission:Editar Menu']);
Route::get('menu/ajaxIcon', [MenuController::class, 'ajaxIcon'])->name('menus.ajax')->middleware(['permission:Crear Menu']);
Route::post('menu/ajaxIcon', [MenuController::class, 'ajaxIcon'])->name('menus.ajax')->middleware(['permission:Crear Menu']);
Route::post('menu/ajax/IconById', [MenuController::class, 'ajaxIconById'])->name('menus.ajaxIconById')->middleware(['permission:Crear Menu']);
Route::post('menu/ajax/GetMenu', [MenuController::class, 'ajaxGetMenu'])->name('menus.ajaxGetMenu');
Route::put('menu/ordenar/opciones', [MenuController::class, 'sortable'])->name('menus.sortable')->middleware(['permission:Crear Menu']);
Route::put('menu/configurar/opciones', [MenuController::class, 'setting'])->name('menus.setting')->middleware(['permission:Configurar Opciones Menu']);


Route::get('reporteador', [HomeController::class, 'index'])->name('reports.index')->middleware(['permission:Crear Reporte']);

#region notificaciones
    Route::get('notificaciones', [NotificationController::class, 'index'])->name('notifications.index');
    #endregion

Route::get('usuarios', [App\Http\Controllers\UserController::class, 'index'])->name('users.index')->middleware(['permission:Consultar Usuarios']);
Route::post('usuarios', [App\Http\Controllers\UserController::class, 'index'])->name('users.index')->middleware(['permission:Consultar Usuarios']);
Route::get('usuarios/crear', [App\Http\Controllers\UserController::class, 'create'])->name('users.create')->middleware(['permission:Crear Usuarios']);
Route::post('usuarios/crear', [App\Http\Controllers\UserController::class, 'store'])->name('users.store')->middleware(['permission:Crear Usuarios']);
Route::get('usuarios/{id}/eliminar', [App\Http\Controllers\UserController::class, 'toggleActive'])->name('users.destroy')->middleware(['permission:Eliminar Usuarios']);
Route::get('usuarios/{id}/editar', [App\Http\Controllers\UserController::class, 'edit'])->name('users.edit')->middleware(['permission:Editar Usuarios']);
Route::put('usuarios/{id}/editar', [App\Http\Controllers\UserController::class, 'update'])->name('users.update')->middleware(['permission:Editar Usuarios']);
Route::get('usuarios/{id}/verificar', [App\Http\Controllers\UserController::class, 'verify'])->name('users.verify')->middleware(['permission:Estatus Usuarios']);


Route::get('roles', [App\Http\Controllers\RoleController::class, 'index'])->name('roles.index')->middleware(['permission:Consultar Roles']);
Route::post('roles', [App\Http\Controllers\RoleController::class, 'index'])->name('roles.index')->middleware(['permission:Consultar Roles']);
Route::get('roles/crear', [App\Http\Controllers\RoleController::class, 'create'])->name('roles.create')->middleware(['permission:Crear Roles']);
Route::post('roles/crear', [App\Http\Controllers\RoleController::class, 'store'])->name('roles.store')->middleware(['permission:Crear Roles']);
Route::get('roles/{id}/eliminar', [App\Http\Controllers\RoleController::class, 'destroy'])->name('roles.destroy')->middleware(['permission:Eliminar Roles']);
Route::get('roles/{id}/editar', [App\Http\Controllers\RoleController::class, 'edit'])->name('roles.edit')->middleware(['permission:Editar Roles']);
Route::put('roles/{id}/editar', [App\Http\Controllers\RoleController::class, 'update'])->name('roles.update')->middleware(['permission:Editar Roles']);
Route::get('roles/{id}/permisos', [App\Http\Controllers\RoleController::class, 'permissions'])->name('roles.permissions')->middleware(['permission:Asignar Permisos']);
Route::post('roles/{id}/permisos', [App\Http\Controllers\RoleController::class, 'rolePermissions'])->name('roles.permissions')->middleware(['permission:Asignar Permisos']);


Route::get('permisos', [App\Http\Controllers\PermissionController::class,'index'])->name('permissions.index')->middleware(['permission:Consultar Permisos']);
Route::post('permisos', [App\Http\Controllers\PermissionController::class,'index'])->name('permissions.index')->middleware(['permission:Consultar Permisos']);
Route::get('permisos/crear', [App\Http\Controllers\PermissionController::class,'create'])->name('permissions.create')->middleware(['permission:Crear Permisos']);
Route::post('permisos/crear', [App\Http\Controllers\PermissionController::class,'store'])->name('permissions.store')->middleware(['permission:Crear Permisos']);
Route::get('permisos/{id}/eliminar', [App\Http\Controllers\PermissionController::class,'destroy'])->name('permissions.destroy')->middleware(['permission:Eliminar Permisos']);
Route::get('permisos/{id}/editar', [App\Http\Controllers\PermissionController::class,'edit'])->name('permissions.edit')->middleware(['permission:Editar Permisos']);
Route::put('permisos/{id}/editar', [App\Http\Controllers\PermissionController::class,'update'])->name('permissions.update')->middleware(['permission:Editar Permisos']);




Route::get('bitacora/', 'LogsController@index')->name('logs.index')->middleware(['permission:Consultar Bitacora']);
Route::get('bitacora/listado', 'LogsController@index')->name('logs.index')->middleware(['permission:Consultar Bitacora']);

    Route::get('reportes', [ReportController::class, 'index'])->name('custom_reports.index')->middleware(['permission:Consultar Reporte']);
    Route::post('reportes', [ReportController::class, 'index'])->name('custom_reports.index')->middleware(['permission:Consultar Reporte']);
    Route::get('reportes/{id}/editar', [ReportController::class, 'edit'])->name('custom_reports.edit')->middleware(['permission:Editar Reporte']);
    Route::put('reportes/{id}/editar', [ReportController::class, 'edit_post'])->name('custom_reports.edit_post')->middleware(['permission:Editar Reporte']);
    Route::get('reportes/{id}/generar_pdf', [ReportController::class, 'generate_pdf'])->name('custom_reports.generate_pdf')->middleware(['permission:Descargar Reporte']);
    Route::get('reportes/{register_id}/{report}/generate_pdf_by_id', [ReportController::class, 'generate_pdf_by_id'])->name('custom_reports.generate_pdf_by_id')->middleware(['permission:Descargar Reporte']);

    Route::get('reportes/contracts', [ReportController::class, 'contracts'])->name('report.contracts')->middleware(['permission:Generar Contrato Reporte']);
    Route::post('reportes/contracts_excel', [ReportController::class, 'contracts_excel'])->name('report.contracts_excel')->middleware(['permission:Generar Contrato Reporte']);
});

//region auth
Route::get('/identificarse', 'Auth\LoginController@login')->name('login');
Route::post('/identificarse', 'Auth\LoginController@loginPost')->name('login_post');
Route::post('/salir', 'Auth\LoginController@logoff')->name('system.logoff');
Route::get('/salir', 'Auth\LoginController@logoff')->name('system.logoff');
//endregion

Route::group(['middleware' => ['auth']], function () {
#meeting_types: sec
Route::get('tipos-de-reuniones', [App\Http\Controllers\MeetingTypeController::class, 'index'])->name('meeting_types.index')->middleware(['permission:Consultar Tipos de Reuniones']);
Route::post('tipos-de-reuniones', [App\Http\Controllers\MeetingTypeController::class, 'index'])->name('meeting_types.index')->middleware(['permission:Consultar Tipos de Reuniones']);
Route::get('tipos-de-reuniones/crear', [App\Http\Controllers\MeetingTypeController::class, 'create'])->name('meeting_types.create')->middleware(['permission:Crear Tipos de Reuniones']);
Route::post('tipos-de-reuniones/crear', [App\Http\Controllers\MeetingTypeController::class, 'store'])->name('meeting_types.store')->middleware(['permission:Crear Tipos de Reuniones']);
Route::get('tipos-de-reuniones/{id}/eliminar', [App\Http\Controllers\MeetingTypeController::class, 'destroy'])->name('meeting_types.destroy')->middleware(['permission:Eliminar Tipos de Reuniones']);
Route::get('tipos-de-reuniones/{id}/activar', [App\Http\Controllers\MeetingTypeController::class, 'active'])->name('meeting_types.active')->middleware(['permission:Eliminar Tipos de Reuniones']);
Route::get('tipos-de-reuniones/{id}/editar', [App\Http\Controllers\MeetingTypeController::class, 'edit'])->name('meeting_types.edit')->middleware(['permission:Editar Tipos de Reuniones']);
Route::put('tipos-de-reuniones/{id}/editar', [App\Http\Controllers\MeetingTypeController::class, 'update'])->name('meeting_types.update')->middleware(['permission:Editar Tipos de Reuniones']);

#emtities: Entidades -> instituciones
Route::get('instituciones', [App\Http\Controllers\EntitiesController::class, 'index'])->name('entities.index')->middleware(['permission:Consultar Instituciones']);
Route::post('instituciones', [App\Http\Controllers\EntitiesController::class, 'index'])->name('entities.index')->middleware(['permission:Consultar Instituciones']);
Route::get('instituciones/crear', [App\Http\Controllers\EntitiesController::class, 'create'])->name('entities.create')->middleware(['permission:Crear Instituciones']);
Route::post('instituciones/crear', [App\Http\Controllers\EntitiesController::class, 'store'])->name('entities.store')->middleware(['permission:Crear Instituciones']);
Route::get('instituciones/{id}/eliminar', [App\Http\Controllers\EntitiesController::class, 'destroy'])->name('entities.destroy')->middleware(['permission:Eliminar Instituciones']);
Route::get('instituciones/{id}/activar', [App\Http\Controllers\EntitiesController::class, 'active'])->name('entities.active')->middleware(['permission:Eliminar Instituciones']);
Route::get('instituciones/{id}/editar', [App\Http\Controllers\EntitiesController::class, 'edit'])->name('entities.edit')->middleware(['permission:Editar Instituciones']);
Route::put('instituciones/{id}/editar', [App\Http\Controllers\EntitiesController::class, 'update'])->name('entities.update')->middleware(['permission:Editar Instituciones']);
Route::post('instituciones/ajaxGetForCatalog', [App\Http\Controllers\EntitiesController::class, 'ajaxGetForCatalog'])->name('entities.ajaxgetforcatalog');

#meeting: Reuniones
Route::get('reuniones', [App\Http\Controllers\MeetingsController::class, 'index'])->name('meetings.index')->middleware(['permission:Consultar Reuniones']);
Route::post('reuniones', [App\Http\Controllers\MeetingsController::class, 'index'])->name('meetings.index')->middleware(['permission:Consultar Reuniones']);
Route::post('reuniones/crear', [App\Http\Controllers\MeetingsController::class, 'store'])->name('meetings.store')->middleware(['permission:Crear Reuniones']);
Route::get('reuniones/crear', [App\Http\Controllers\MeetingsController::class, 'create'])->name('meetings.create')->middleware(['permission:Crear Reuniones']);
Route::get('reuniones/{id}/editar', [App\Http\Controllers\MeetingsController::class, 'edit'])->name('meetings.edit')->middleware(['permission:Editar Reuniones']);
Route::put('reuniones/{id}/editar', [App\Http\Controllers\MeetingsController::class, 'update'])->name('meetings.update')->middleware(['permission:Editar Reuniones']);
Route::post('reuniones/ajaxGetForCatalogByTypeId', [App\Http\Controllers\MeetingsController::class, 'ajaxGetForCatalogByTypeId'])->name('meetings.ajaxgetforcatalog');
Route::post('reuniones/archivos', [App\Http\Controllers\MeetingsController::class, 'subject_files'])->name('meetings.subject_files');
Route::get('reuniones/{id}/cancelar', [App\Http\Controllers\MeetingsController::class, 'cancel'])->name('meetings.cancel')->middleware(['permission:Cancelar Reunion']);

Route::post('reuniones/acuerdo', [App\Http\Controllers\MeetingsController::class, 'agreement_store'])->name('meetings.agreement_store')->middleware(['permission:Crear Acuerdos']);
Route::get('reuniones/{id}/acuerdo', [App\Http\Controllers\MeetingsController::class, 'agreement'])->name('meetings.agreement')->middleware(['permission:Consultar Acuerdos']);
Route::post('reuniones/{id}/acuerdo', [App\Http\Controllers\MeetingsController::class, 'agreement'])->name('meetings.agreement')->middleware(['permission:Consultar Acuerdos']);
Route::get('reuniones/acuerdo/{id}/editar', [App\Http\Controllers\MeetingsController::class, 'edit_agreement'])->name('meetings.edit_agreement')->middleware(['permission:Crear Acuerdos']);
Route::put('reuniones/acuerdo/{id}/editar', [App\Http\Controllers\MeetingsController::class, 'update_agreement'])->name('meetings.update_agreement')->middleware(['permission:Crear Acuerdos']);
Route::get('reuniones/acuerdo/{id}/eliminar', [App\Http\Controllers\MeetingsController::class, 'delete_agreement'])->name('meetings.delete_agreement')->middleware(['permission:Crear Asuntos']);

Route::post('reuniones/asunto', [App\Http\Controllers\MeetingsController::class, 'subject_store'])->name('meetings.subject_store')->middleware(['permission:Crear Asuntos']);
Route::get('reuniones/{id}/asunto', [App\Http\Controllers\MeetingsController::class, 'subject'])->name('meetings.subject')->middleware(['permission:Consultar Asuntos']);
Route::post('reuniones/{id}/asunto', [App\Http\Controllers\MeetingsController::class, 'subject'])->name('meetings.subject')->middleware(['permission:Consultar Asuntos']);
Route::get('reuniones/asunto/{id}/editar', [App\Http\Controllers\MeetingsController::class, 'edit_subject'])->name('meetings.edit_subject')->middleware(['permission:Crear Asuntos']);
Route::put('reuniones/asunto/{id}/editar', [App\Http\Controllers\MeetingsController::class, 'update_subject'])->name('meetings.update_subject')->middleware(['permission:Crear Asuntos']);
Route::get('reuniones/asunto/{id}/eliminar', [App\Http\Controllers\MeetingsController::class, 'delete_subject'])->name('meetings.delete_subject')->middleware(['permission:Crear Asuntos']);

#Calendario
Route::get('/calendario', [App\Http\Controllers\HomeController::class, 'calendar'])->name('calendar.index')->middleware(['permission:Consultar Calendario']);
Route::post('/calendario', [App\Http\Controllers\HomeController::class, 'calendar'])->name('calendar.index')->middleware(['permission:Consultar Calendario']);

#places: sec
Route::get('lugares', [App\Http\Controllers\PlacesController::class, 'index'])->name('places.index')->middleware(['permission:Consultar Lugares']);
Route::post('lugares', [App\Http\Controllers\PlacesController::class, 'index'])->name('places.index')->middleware(['permission:Consultar Lugares']);
Route::get('lugares/crear', [App\Http\Controllers\PlacesController::class, 'create'])->name('places.create')->middleware(['permission:Crear Lugares']);
Route::post('lugares/crear', [App\Http\Controllers\PlacesController::class, 'store'])->name('places.store')->middleware(['permission:Crear Lugares']);
Route::get('lugares/{id}/eliminar', [App\Http\Controllers\PlacesController::class, 'destroy'])->name('places.destroy')->middleware(['permission:Eliminar Lugares']);
Route::get('lugares/{id}/activar', [App\Http\Controllers\PlacesController::class, 'active'])->name('places.active')->middleware(['permission:Eliminar Lugares']);
Route::get('lugares/{id}/editar', [App\Http\Controllers\PlacesController::class, 'edit'])->name('places.edit')->middleware(['permission:Editar Lugares']);
Route::put('lugares/{id}/editar', [App\Http\Controllers\PlacesController::class, 'update'])->name('places.update')->middleware(['permission:Eliminar Lugares']);

#agreements: sec
Route::get('acuerdos', [App\Http\Controllers\AgreementsController::class, 'index'])->name('agreements.index')->middleware(['permission:Consultar Acuerdos']);
Route::post('acuerdos', [App\Http\Controllers\AgreementsController::class, 'index'])->name('agreements.index')->middleware(['permission:Consultar Acuerdos']);
Route::get('acuerdos/crear', [App\Http\Controllers\AgreementsController::class, 'create'])->name('agreements.create')->middleware(['permission:Crear Acuerdos']);
Route::post('acuerdos/crear', [App\Http\Controllers\AgreementsController::class, 'store'])->name('agreements.store')->middleware(['permission:Crear Acuerdos']);
Route::get('acuerdos/{id}/editar', [App\Http\Controllers\AgreementsController::class, 'edit'])->name('agreements.edit')->middleware(['permission:Editar Acuerdos']);
Route::put('acuerdos/{id}/editar', [App\Http\Controllers\AgreementsController::class, 'update'])->name('agreements.update')->middleware(['permission:Editar Acuerdos']);
Route::get('acuerdos/{id}/eliminar', [App\Http\Controllers\AgreementsController::class, 'destroy'])->name('agreements.destroy')->middleware(['permission:Eliminar Acuerdos']);
Route::get('acuerdos/{id}/activar', [App\Http\Controllers\AgreementsController::class, 'active'])->name('agreements.active')->middleware(['permission:Eliminar Acuerdos']);

#agreements-actions: sec
Route::post('acciones/crear', [App\Http\Controllers\AgreementsController::class, 'action_store'])->name('agreements.action.store')->middleware(['permission:Crear Acciones']);
Route::get('acciones/{id}/eliminar', [App\Http\Controllers\AgreementsController::class, 'action_destroy'])->name('actions.destroy')->middleware(['permission:Eliminar Acciones']);
Route::post('acciones/editar', [App\Http\Controllers\AgreementsController::class, 'action_edit'])->name('agreements.action.edit')->middleware(['permission:Editar Acciones']);
Route::put('acciones/{id}/editar', [App\Http\Controllers\AgreementsController::class, 'action_update'])->name('agreements.action.update')->middleware(['permission:Editar Acciones']);

#agreements-replies: sec
Route::get('acuerdos/{id}/avances', [App\Http\Controllers\AgreementsController::class, 'replies'])->name('agreements.reply')->middleware(['permission:Crear Avances']);
Route::post('acuerdos/{id}/avances', [App\Http\Controllers\AgreementsController::class, 'replies'])->name('agreements.reply')->middleware(['permission:Crear Avances']);
Route::post('acuerdos/instituciones/detalle', [App\Http\Controllers\AgreementsController::class, 'entities_detail'])->name('agreements.entities.detail')->middleware(['permission:Consultar Acuerdos']);
Route::post('accion/avance', [App\Http\Controllers\AgreementsController::class, 'reply_action_form'])->name('agreements.reply.action')->middleware(['permission:Crear Avances']);
Route::post('accion/avance/crear', [App\Http\Controllers\AgreementsController::class, 'reply_action_store'])->name('agreements.reply.action.store')->middleware(['permission:Crear Avances']);
Route::post('accion/avances', [App\Http\Controllers\AgreementsController::class, 'action_replies'])->name('agreements.action.replies')->middleware(['permission:Consultar Avances']);

#Semaforo
Route::get('/semaforo', [App\Http\Controllers\HomeController::class, 'indicators'])->name('indicators.index')->middleware(['permission:Consultar Semaforo']);
// Route::post('/semaforo', [App\Http\Controllers\HomeController::class, 'indicators'])->name('indicators.index')->middleware(['permission:Consultar Indicators']);

#files detail
Route::post('adjuntos/detalle', [App\Http\Controllers\FilesController::class, 'files_detail'])->name('files.detail');

#Tablero
Route::get('/tablero/gabinete/consulta/reuniones/{params}', [App\Http\Controllers\DashboardController::class, 'gabinete_link'])->name('dashboard.gabinete.link')->middleware(['permission:Consultar Reuniones']);
Route::get('/tablero/gabinete', [App\Http\Controllers\DashboardController::class, 'gabinete_index'])->name('dashboard.gabinete.index')->middleware(['permission:Consultar Tablero Gabinete']);
Route::post('/tablero/gabinete', [App\Http\Controllers\DashboardController::class, 'gabinete_index'])->name('dashboard.gabinete.index')->middleware(['permission:Consultar Tablero Gabinete']);
});