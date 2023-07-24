<?php

namespace App\Models;

use App\Enums\log_movements;
use App\Enums\system_catalogues;
use App\Helpers\HelperApp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpedientType extends Model
{
    use HasFactory;
    static $system_catalogue = system_catalogues::ExpedientType;

    protected $fillable = [
        'name', 'order'
    ];

    public static function getForCatalog()
    {
        $model = ExpedientType::where("active",1)->orderBy("order")->pluck('name', 'id');
        return $model;
    }

    public static function get_pagination($name, $perPage)
    {

        $model = ExpedientType::query()
            ->where(function($query) use ($name) {
                if(!empty($name))
                    $query->where('name', 'like', '%' . $name . '%');
                $query->where('active', 1);
            })
            ->orderBy('order')
            ->paginate($perPage);
        return $model;
    }

    public static function _create(ExpedientType $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function _edit(ExpedientType $model)
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
            HelperApp::save_log($model->id, log_movements::NewRegister, ExpedientType::$system_catalogue, null, $json_model);

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
            HelperApp::save_log($model->id, log_movements::Edit, ExpedientType::$system_catalogue, $json_old, $json_new);

        });

    }
}
