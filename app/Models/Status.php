<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Enums
use App\Enums\log_movements;
use App\Enums\system_catalogues;

use App\Helpers\HelperApp;


class Status extends Model
{
    use HasFactory;
    static $system_catalogue = system_catalogues::Status;

    protected $table = 'status';

    protected $fillable = [
        'name','description','color','active'
    ];

    const ACTIVO = 1;
    const INACTIVO = 2;
    const POR_CELEBRAR = 3;
    const CELEBRADA = 4;
    const SIN_AVANCE = 5;
    const EN_PROCESO = 6;
    const CONCLUIDO = 7;
    const CANCELADA = 8;
    const ACTIONS=[self::SIN_AVANCE,self::EN_PROCESO,self::CONCLUIDO];
    const MEETINGS=[self::POR_CELEBRAR,self::CELEBRADA,self::CANCELADA];

    public static function getForCatalog()
    {
        $model = self::where("active", 1)->orderBy("name")->pluck('name', 'id');
        return $model;
    }

    public static function getForCatalogByIds($arr)
    {
        $model = self::where("active", 1)->whereIn("id", $arr)->orderBy("name")->pluck('name', 'id');
        return $model;
    }

    public static function getForCatalogExIds($arr)
    {
        $model = self::where("active", 1)->whereNotIn("id", $arr)->orderBy("name")->pluck('name', 'id');
        return $model;
    }

    public static function get_pagination( $name, $perPage)
    {
        $model = self::query()
            ->where(function($query) use ($name) {
                if(!empty($name))
                    $query->where('name', 'like', '%' . $name . '%');
            })
            ->orderBy('name','DESC')
            ->paginate($perPage);

        return $model;
    }

    public static function _create(Status $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function _edit(Status $model)
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
            HelperApp::save_log($model->id, log_movements::NewRegister, self::$system_catalogue, null, $json_model);

        });
        static::updated(function ($model) {
            $modelOld = $model->getOriginal();
            $change =  array_merge($modelOld, $model->getChanges());
            $json_old = json_encode($modelOld);
            $json_new = json_encode($change);
            HelperApp::save_log($model->id, log_movements::Edit, self::$system_catalogue, $json_old, $json_new);

        });
    }
}

