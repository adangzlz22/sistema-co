<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Enums
use App\Enums\log_movements;
use App\Enums\system_catalogues;

use App\Helpers\HelperApp;
use Illuminate\Support\Facades\Auth;

class Place extends Model
{
    use HasFactory;
    static $system_catalogue = system_catalogues::Places;

    protected $fillable = [
        'name','address','status_id',
    ];

    public static function getForCatalog()
    {
        $model = self::where("status_id","<>", Status::INACTIVO)->orderBy("name")->pluck('name', 'id', 'address');
        return $model;
    }

    public static function getForCatalogCresteMeeting()
    {
        $model = self::select('name', 'id', 'address')->where("status_id","<>", Status::INACTIVO)->orderBy("name")->get();
        return $model;
    }

    public static function get_pagination($name, $address, $perPage)
    {
        $model = self::query()->with('status')
            ->where(function($query) use ($name, $address) {
                if(!empty($name))
                    $query->where('name', 'like', '%' . $name . '%');

                if(!empty($address))
                    $query->where('address', 'like', '%' . $address . '%');

                if(!Auth::user()->hasRole([HelperApp::$roleSuperUsuario])){
                    $query->where('status_id','<>', Status::INACTIVO);
                }
            })
            ->orderBy('name','DESC')
            ->paginate($perPage);

        return $model;
    }

    public static function _create(Place $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function _edit(Place $model)
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

    public function status()
    {
        return $this->belongsTo(Status::class,'status_id');
    }
}

