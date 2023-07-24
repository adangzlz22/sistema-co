<?php

namespace App\Http\Controllers\Auth;

use App\Enums\log_movements;
use App\Helpers\HelperApp;
use App\Http\Controllers\Controller;
use App\Models\AdministrativeUnit;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


//model
use App\Models\Menu;
use App\Rules\ReCaptcha;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest')->except('logout');
    }


    public function login()
    {
        if(Auth::check()){
            return redirect()->route('home');
        }

        return view('auth.login');
    }

    public function loginPost(Request $request)
    {
        $input = $request->all();

        $this->validateLogin($request);


        // $this->validate($request, [
        //     'email' => 'required',
        //     'password' => 'required',
        // ], [
        //     'email.required' => 'El correo es requerido',
        //     'password.required' => 'La contraseña es requerida',
        // ]);

        if(auth()->attempt(array('email' => $input['email'], 'password' => $input['password'],'active' => 1), $input['remember'] ?? false))
        {

            /*if(!auth()->user()->activo)
            {
                Auth::logout();
                return redirect()->route('login')
                    ->with('error',Lang::get('dictionary.message_user_not_exists'));
            }*/

            HelperApp::save_log(auth()->user()->id, log_movements::LogIn, null, null, null);

            //consultamos el menu dynamic
            $menuDynamic = Menu::getMenu();
            Session::put('menu', $menuDynamic);
            // dd(session('menu'));


            return redirect()->intended('');

            //return redirect()->route('home');
        }else{
            flash('<i class="mdi mdi-information-outline"></i> Usuario y/o contraseña incorrectos.')->error();

            return redirect()->route('login')
                ->with('error', 'Usuario y/o contraseña incorrecta.');
        }
    }

    public function logoff() {

        if(\auth()->user())
            HelperApp::save_log(auth()->user()->id, log_movements::LogOut, null, null, null);

        Auth::logout();

        return redirect()->route('login');
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
            // 'g-recaptcha-response' => ['required', new ReCaptcha]
        ],[
          $this->username().'.required' => 'El usuario es requerido.',
          'password.required' => 'La contraseña es requerida.',
          'g-recaptcha-response.required'=>'El recaptcha es requerido.']);
    }
}
