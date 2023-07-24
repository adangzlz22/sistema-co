<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Enums
use App\Enums\log_movements;
use App\Enums\system_catalogues;

use App\Helpers\HelperApp;
use Illuminate\Support\Facades\Auth;

class MeetingType extends Model
{
    use HasFactory;
    static $system_catalogue = system_catalogues::MeetingTypes;

    protected $fillable = [
        'acronym','name','color','user_id','status_id',
    ];

    public static function getForCatalog()
    {
        $model = self::where("status_id","<>", Status::INACTIVO)->orderBy("name")->pluck('name', 'id');
        return $model;
    }

    public static function getValidateRepeated()
    {
        $model = self::where("status_id","<>", Status::INACTIVO)->orderBy("name")->pluck('name', 'id');
        return $model;
    }

    public static function get_pagination($acronym, $name, $perPage)
    {
        $model = self::query()
            ->where(function($query) use ($name, $acronym) {
                if(!empty($name))
                    $query->where('name', 'like', '%' . $name . '%');

                if(!empty($acronym))
                    $query->where('acronym', 'like', '%' . $acronym . '%');

                if(!Auth::user()->hasRole([HelperApp::$roleSuperUsuario])){
                    $query->where('status_id','<>', Status::INACTIVO);
                }
            })
            ->orderBy('name','DESC')
            ->paginate($perPage);

        return $model;
    }

    public static function _create(MeetingType $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function _edit(MeetingType $model)
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

    public function entities()
    {
        return $this->belongsToMany(Entity::class,'entities_meeting_types','meeting_type_id','entity_id');
    }

    public function meetings()
    {
        return $this->hasMany(Meeting::class,'meeting_type_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class,'status_id');
    }
}

