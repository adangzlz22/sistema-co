<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Helpers\HelperApp;

//Enums
use App\Enums\log_movements;
use App\Enums\system_catalogues;

class Icon extends Model
{
    use HasFactory;
    static $system_catalogue = system_catalogues::Icon;

    protected $fillable = [
        'name', 'key'
    ];

    public static function getForCatalog()
    {
        $model = Icon::where("active",1)->pluck('name', 'id')->sortBy("name")->take(10);
        return $model;
    }
    public static function get()
    {
        $model = Icon::where("active",1)->select('id','name','key')->take(10)->get()->sortBy("name");
        return $model;
    }

    public static function get_pagination($name,$key, $perPage)
    {

        $model = Icon::query()
            ->where(function($query) use ($name,$key) {
                if(!empty($name))
                    $query->where('name', 'like', '%' . $name . '%');

                if(!empty($key))
                    $query->where('key', 'like', '%' . $key . '%');

                $query->where('active', 1);

            })
            ->orderBy('name','ASC')
            ->paginate($perPage);
        return $model;
    }

    public static function _create(Icon $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function _edit(Icon $model)
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
            HelperApp::save_log($model->id, log_movements::NewRegister, Icon::$system_catalogue, null, $json_model);

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
            HelperApp::save_log($model->id, log_movements::Edit, Icon::$system_catalogue, $json_old, $json_new);

        });

    }



}
