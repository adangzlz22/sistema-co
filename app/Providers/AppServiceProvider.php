<?php

namespace App\Providers;


use App\Helpers\HelperApp;
use App\Models\AttachContractDocument;
use App\Models\AttendedContract;
use App\Models\ContractShared;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Blade;

use DateTime;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    private function set_blades()
    {
        Blade::directive('money_format', function ($money) {
            return "<?php echo '$'. number_format($money, 2); ?>";
        });
    }

    public function boot()
    {
        $this->set_blades();
        Paginator::useBootstrap();
        //Schema::defaultStringLength(200);

        Schema::defaultStringLength(191);

        // Configuración para fechas en español
        Carbon::setUTF8(true);
        Carbon::setLocale(config('app.locale'));
        setlocale(LC_ALL, 'es_ES', 'es_MX', 'es', 'ES', 'es_MX.utf8');


        //validacion para unidades administrativas
        Validator::extend('au_is_required', function ($attribute, $value, $parameters, $validator) {
            $inputs = $validator->getData();
            $role = $inputs['role'] ?? null;
            $administrative_units = $inputs['administrative_unit_id'];
            $is_dependency = Role::where('name', HelperApp::$roleDependenciaEntidad )->where('id', $role)->exists();
            if (!$administrative_units && $is_dependency) {
                return false;
            }
            return true;
        });
        //validacion para organismos
        Validator::extend('org_is_required', function ($attribute, $value, $parameters, $validator) {
            $inputs = $validator->getData();
            $role = $inputs['role'] ?? null;
            $organims = $inputs['organisms_id'];
            $is_dependency = Role::where('name', 'Consultivo Revisor')->where('id', $role)->exists();
            if (!$organims && $is_dependency) {
                return false;
            }
            return true;
        });

        //validacion para usuario es unico en turnado
        Validator::extend('user_is_unique', function ($attribute, $value, $parameters, $validator) {
            $inputs = $validator->getData();
            $contract_id = $inputs['contract_id'] ?? null;
            $user_id = $inputs['user_id'];
            $exist = ContractShared::where('contract_id', $contract_id)->where('user_id', $user_id)->where('active',1)->exists();
            if ($exist) {
                return false;
            }
            return true;
        });

        /*//validacion para usuario es unico en turnado
        Validator::extend('unique_acknowledgment_receipt_documents', function ($attribute, $value, $parameters, $validator) {
            $inputs = $validator->getData();
            $contract_id = $inputs['contract_id'] ?? null;
            dd($inputs);
            $exists = AttachContractDocument::where([
                'contract_id' => $contract_id,
                'active' => true,
                'is_acknowledgment_receipt' => true
            ])->exists();
            return false;
        });*/
//
//
//        user_is_unique


        //Collection Macros
        // DateTime::macro('toLongDate', function () {
        //     return $this->map(function ($value) {
        //         return $value->format('d'). ' de '. Str::ucfirst($value->formatLocalized('%B')). ' del '.$value->format('Y');//Str::upper($value);
        //     });
        // });
    }
}
