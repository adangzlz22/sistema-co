<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionsStoreRequest;
use App\Models\GroupPermission;
use App\Models\Permission;
use \Route;


class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = Permission::orderBy('id', 'ASC')->paginate(50);
        return view('permissions.index', compact('permissions'));
    }
    private function rutas(){
        $routes = [];

        foreach (Route::getRoutes()->getRoutes() as $route) {
            $action = $route->getAction();
            if (array_key_exists('as', $action)) {
                $routes[$action['as']] = $action['as'];
            }
        }
        return $routes;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $routes = $this->rutas();
        $groups = GroupPermission::selectList();
        return view('permissions.create',compact('groups','routes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermissionsStoreRequest $request)
    {
        $permission = new Permission($request->all());
        $permission->group_permission_id = $request->group_permission_id;
        $permission->guard_name = 'web';
        $permission->save();
        flash('<i class="mdi mdi-information-outline"></i> Se ha registrado el rol de usuario  <strong>' . $permission->name . '</strong> de forma exitosa.')->success();
        return redirect()->route('permissions.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $routes = $this->rutas();
        $groups = GroupPermission::selectList();
        $permission = Permission::find($id);
        return view('permissions.edit', compact('permission','groups','routes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function update(PermissionsStoreRequest $request, $id)
    {
        $permission = Permission::find($id);
        $permission->name = $request->name;
        $permission->route = $request->route;
        $permission->group_permission_id = $request->group_permission_id;
        $permission->save();
        flash('<i class="mdi mdi-information-outline"></i> El permiso de usuario ' . $request->name . ' a sido renombrado como <strong>' . $permission->name . ' </strong> de forma exitosa.')->success();
        return redirect()->route('permissions.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permission = Permission::find($id);
        $permission->delete();
        flash('<i class="mdi mdi-information-outline"></i> El permiso de usuario  <strong>' . $permission->name . '</strong> ha sido borrado de forma exitosa.')->error();
        return redirect()->route('permissions.index');
    }
}
