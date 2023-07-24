<?php

namespace App\Http\Controllers;

use App\Enums\log_movements;
use App\Enums\system_catalogues;
use App\Helpers\HelperApp;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserPasswordUpdateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Listeners\SendNewUserNotification;
use App\Models\Entity;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use PHPUnit\Exception;
use Spatie\Permission\Models\Role;

//models
use App\Models\User;

use Illuminate\Support\Facades\Event;

class UserController extends Controller
{
    private $system_catalogue = system_catalogues::Users;

    public function __construct()
    {
        $this->middleware('auth');
    }

    private function uploadAvatar($request)
    {
        $customMessages['avatar.mimes'] = 'El archivo tiene un formato diferente a los permitidos: :values';
        $customMessages['avatar.max'] = 'El archivo tiene un tamaño mayor a 500kb';

        $this->validate($request, [

            'avatar' => 'mimes:jpg,jpeg,png|max:512',

        ], $customMessages);

        $filename = null;

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');

            $filename = time() . '.jpg';
            Image::make($avatar)->resize(512, 512)->encode('jpg')->save(public_path('images/avatars/' . $filename));

        } else {
            $filename = "default.png";
        }

        return $filename;
    }

    public function index(Request $request)
    {
        //$roles = Role::all('name','id')->sortBy('name','asc');

        $entities = Entity::getForCatalog();
        $data = Role::query()->orderBy('name', 'asc');
        $roles = $data->pluck('name', 'id');
        $roles->prepend('Roles de Usuario', '');


        $data = User::query();
        if (Auth::user()->hasRole('Administrador Consultivo')) {
            $data->where('created_by', Auth::user()->id);
        }
        if ($request->has('name') && trim($request->input('name')) !== '') {
            $data = $data->where('name', 'LIKE', '%' . trim($request->input('name')) . '%');
            $data = $data->orWhere('email', 'LIKE', '%' . trim($request->input('name')) . '%');
        }

        if ($request->has('entity_id') && trim($request->input('entity_id')) !== '')
            $data = $data->where('entity_id', $request->input('entity_id'));


        if ($request->has('role') && intval($request->input('role')) > 0) {

            $data = $data->whereHas('roles', function ($query) use ($request) {
                $query->where('role_id', $request->input('role'));
            });

        }

        $users = $data->orderBy('name', 'ASC')->paginate(25);

        return view('users.index', compact('users', 'request', 'roles','entities'));


        //$users = User::orderBy('name', 'ASC')->paginate(10);
        //return view('users.index', compact('users'));
    }

    public function create()
    {
        $entities = Entity::getForCatalog();
        $roles = Role::all('name', 'id')->sortBy('id');
        return view('users.create', compact('roles','entities'));
    }

    public function store(UserCreateRequest $request)
    {
        $user = new User($request->all());
        $user->password = bcrypt($request->password);
        $filename = $this->uploadAvatar($request);
        $user->avatar = $filename;
        $user->email_verified_at = Carbon::now();
        $user->created_by = Auth::user()->id;
        $user->save();
        




        if (Auth::user()->hasRole('Administrador Consultivo')) {
            $role = Role::where('name', 'Consultivo Revisor')->first();
            $user->roles()->attach($role);

        } else {

            if (is_array($request->role) && count($request->role) >= 1) {
                foreach ($request->role as $role) {
                    $role = Role::where('id', $role)->first();
                    $user->roles()->attach($role);
                }
            } elseif (isset($request->role)) {
                $role = Role::where('id', $request->role)->first();
                $user->roles()->attach($role);
            }
        }

        //Notification
        try{
            //event(new SendNewUserNotification($user));
            (new SendNewUserNotification())->handle($user);
        }catch (\Exception $ex) { }

        $json_model = $user->toJson();
        $response = HelperApp::save_log($user->id, log_movements::NewRegister, $this->system_catalogue, null, $json_model);
        flash('<i class="mdi mdi-information-outline"></i> Se ha registrado a  <strong>' . $user->name . '</strong> de forma exitosa.')->success();
        return redirect()->route('users.index');
    }

    public function edit($id)
    {
        $user = User::find($id);
        $entities = Entity::getForCatalog();
        $roles = Role::all('name', 'id')->sortBy("name");

        return view('users.edit', compact('user', 'roles', 'entities'));
    }

    public function update(UserUpdateRequest $request, $id)
    {
        
        $original_user = User::get_by_id($id);
        $user = User::get_by_id($id);

        $user->name = $request->name;
        $user->entity_id = $request->entity_id;
        $user->job = $request->job;
        $user->is_signer = $request->is_signer;
        $user->phone = $request->phone;

        if ($request->hasFile('avatar')) {
            $filename = $this->uploadAvatar($request);
            $user->avatar = $filename;
        }
        if (!empty($request->password)) {
            $user->password = bcrypt($request->password);
        }

        $user->save();
        $user->roles()->detach();
        if (Auth::user()->hasRole('Administrador Consultivo')) {
            $role = Role::where('name', 'Consultivo Revisor')->first();
            $user->roles()->attach($role);

        }else{

            if (is_array($request->role) && count($request->role) >= 1) {
                foreach ($request->role as $role) {
                    $role = Role::where('id', $role)->first();
                    $user->roles()->attach($role);
                }
                HelperApp::EventMenuChage();
            } elseif (isset($request->role)) {
                $role = Role::where('id', $request->role)->first();
                $user->roles()->attach($role);
                HelperApp::EventMenuChage();
            }
        }

        $json_old = $original_user->toJson();
        $json_new = $user->toJson();
        $response = HelperApp::save_log($user->id, log_movements::Edit, $this->system_catalogue, $json_old, $json_new);
        flash('<i class="mdi mdi-information-outline"></i> Se ha actualizado a  <strong>' . $user->name . '</strong> de forma exitosa.')->success();
        return redirect()->route('users.index');


    }

    public function toggleActive($id)
    {
        $user = User::get_by_id($id);
        $user->active = ! $user->active;
        $user->save();
        //EVENTO
        HelperApp::EventMenuChage();
        $json_model = $user->toJson();
        $response = HelperApp::save_log($user->id, log_movements::Inactive, $this->system_catalogue, null, $json_model);
        flash('<i class="mdi mdi-information-outline"></i> El usuario  <strong>' . $user->name . '</strong> ha inactivado de forma exitosa.')->success();
        return redirect()->route('users.index');
    }


    public function profile()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    public function avatar(Request $request)
    {


        $user = Auth::user();

        if ($request->hasFile('avatar')) {
            $filename = $this->uploadAvatar($request);
            $user->avatar = $filename;
        }

        $user->name = $request->name;
        $user->phone = $request->phone;

        if($user->save()){
            flash('<i class="mdi mdi-information-outline"></i> Perfil actualizado con éxito.')->success();
        }else{
            flash('<i class="mdi mdi-information-outline"></i> Ha ocurrido un error al actualizar el perfil.')->error();
        }
        return \Redirect::route('profile');
    }

    public function about(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;
        //$filename = $this->uploadAvatar($request);
        $student->about = $request->about;
        $student->facebook = $request->facebook;
        $student->twitter = $request->twitter;
        $student->linkedin = $request->linkedin;
        $student->website = $request->website;
        $student->instagram = $request->instagram;
        $student->whatsapp = $request->whatsapp;
        $student->save();
        return \Redirect::route('profile');
    }

    public function passwordUpdate(UserPasswordUpdateRequest $request)
    {
        $user = User::find(Auth::user()->id);
        if (!empty($request->password)) {
            $user->password = bcrypt($request->password);
        }
        $user->save();
        flash('<i class="mdi mdi-information-outline"></i> Se ha actualizado su contraseña de forma exitosa.')->success();
        return \Redirect::route('profile');
    }

    public function verify($id)
    {
        $user = User::find($id);
        $user->markEmailAsVerified();
        $user->save();
        flash('<i class="mdi mdi-information-outline"></i> Se ha verificado  a  <strong>' . $user->name . '</strong> de forma exitosa.')->success();
        return redirect()->route('users.index');
    }


}
