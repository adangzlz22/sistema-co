<?php

namespace App\Http\Controllers;

use App\Http\Requests\RolesStoreRequest;

use App\Models\GroupPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use Illuminate\Support\Facades\Event;
use App\Helpers\HelperApp;

class RoleController extends Controller
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
        $roles = Role::orderBy('id', 'ASC')->paginate(25);
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(RolesStoreRequest $request)
    {
        $role = new Role();
        $role->name = $request->name;
        $role->save();
        flash('<i class="mdi mdi-information-outline"></i> Se ha registrado el rol de usuario  <strong>' . $role->name . '</strong> de forma exitosa.')->success();
        return redirect()->route('roles.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Role $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Role $role
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find($id);
        return view('roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Role $role
     * @return \Illuminate\Http\Response
     */
    public function update(RolesStoreRequest $request, $id)
    {
        $role = Role::find($id);
        $role->name = $request->name;
        $role->save();

        flash('<i class="mdi mdi-information-outline"></i> El rol de usuario ' . $request->name . ' a sido renombrado como <strong>' . $role->name . ' </strong> de forma exitosa.')->success();
        return redirect()->route('roles.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Role $role
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::find($id);
        $role->delete();

        HelperApp::EventMenuChage();
        flash('<i class="mdi mdi-information-outline"></i> El rol de usuario  <strong>' . $role->name . '</strong> ha sido borrado de forma exitosa.')->error();
        return redirect()->route('roles.index');

    }


    public function permissions($id)
    {
        $role = Role::find($id);
        $groupPermissions = GroupPermission::all()->sortBy('id');

        return view('roles.permissions', compact('role', 'groupPermissions'));
    }


    public function rolePermissions(Request $request, $id)
    {

        $role = Role::find($id);
        $permissions = Permission::all();
        $role->permissions()->detach();

        if (!empty($request->permission)) {

            foreach ($request->permission as $perm) {
                $permission = Permission::where('id', $perm)->first();
                $role->permissions()->attach($permission);
            }
        }

        Artisan::call('cache:clear');
        Artisan::call('config:cache');
        Artisan::call('config:clear');
        Artisan::call('optimize:clear');

        HelperApp::EventMenuChage();
        flash('<i class="mdi mdi-information-outline"></i> Los permisos han sido asignados al  <strong>' . $role->name . '</strong> de forma exitosa.')->success();

        return back()->withInput();
    }




}
