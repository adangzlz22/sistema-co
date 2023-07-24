<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Enums
use App\Enums\log_movements;
use App\Enums\system_catalogues;

//helper
use App\Helpers\HelperApp;


class JurisdictionContract extends Model
{
    use HasFactory;
    static $system_catalogue = system_catalogues::JurisdictionContract;

    protected $fillable = [
        'name', 'order'
    ];

    public static function getForCatalog()
    {
        $model = JurisdictionContract::orderBy('name')->where("active",1)->pluck('name', 'id');
        return $model;
    }

    public static function get_pagination($name, $perPage)
    {

        $model = JurisdictionContract::query()
            ->where(function($query) use ($name) {
                if(!empty($name))
                    $query->where('name', 'like', '%' . $name . '%');
                $query->where('active', 1);
            })
            ->orderBy('name')
            ->paginate($perPage);
        return $model;
    }

    public static function _create(JurisdictionContract $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function _edit(JurisdictionContract $model)
    {
        try{
            $model->update();
            return ['saved' => true, 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e];
        }
    }

    public static function boot() {

        parent::boot();
        static::created(function($model) {
            $json_model = $model->toJson();
            HelperApp::save_log($model->id, log_movements::NewRegister, JurisdictionContract::$system_catalogue, null, $json_model);

        });
        static::updated(function ($model) {
            $modelOld = $model->getOriginal();
            $change =  array_merge($modelOld, $model->getChanges());
            //relaciones
            // $modelOld['activo'] =  get_label_activo($modelOld['activo']);
            // $model->activo =  get_label_activo($model->activo);
            // created_model_bitacora($model,$modelOld,2,$model->niceNames);
            $json_old = json_encode($modelOld);
            $json_new = json_encode($change);
            HelperApp::save_log($model->id, log_movements::Edit, JurisdictionContract::$system_catalogue, $json_old, $json_new);

        });

    }
}
