<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Enums
use App\Enums\log_movements;
use App\Enums\system_catalogues;

use App\Helpers\HelperApp;
use Illuminate\Support\Facades\Auth;

class Entity extends Model
{
    use HasFactory;
    static $system_catalogue = system_catalogues::Entities;

    protected $table = 'entities';
    protected $fillable = [
        'acronym','name','email','job','holder','entities_types_id','user_id','status_id',
    ];

    const DEPENDENCIA="DEPENDENCIA";
    const ENTIDAD="ENTIDAD";
    const FIDEICOMISO="FIDEICOMISO";
    const ORGANISMO="ORGANISMO";

    public static function getForCatalog($request=null)
    {
        $model = self::where("status_id", "<>", Status::INACTIVO)->where(function ($q) use($request)
        {
            if(!empty($request->meeting_type_id)){
                $q->whereHas("meeting_types",function ($q) use($request)
                {
                    $q->where("meeting_type_id", $request->meeting_type_id);
                });
            }
        })
        ->orderBy("name")->pluck('name', 'id');
        return $model;
    }

    public static function getForCatalogByEntitiesTypes($request=null)
    {
        $model = self::where("status_id", "<>", Status::INACTIVO)->where(function ($q) use($request)
        {
            if(!empty($request->meeting_type_id)) {
                $q->whereHas("meeting_types",function ($q) use($request)
                {
                    $q->where("meeting_type_id", $request->meeting_type_id);
                });
            }
        })
        ->orderBy("name")->select('acronym', 'name', 'id', 'entities_types_id')->get()->toArray();
        return $model;// get()->toArray() y get()->toJson()
    }

    public static function getForCatalogByIdAgreement($id)
    {
        $model = self::where("status_id","<>", Status::INACTIVO)->whereHas("agreements",function ($q) use($id)
        {
            $q->where('agreement_id',$id);
        })->orderBy("name")->pluck('name', 'id');
        return $model;
    }

    public static function get_pagination($acronym, $name, $entities_types, $meeting_type, $perPage)
    {
        $model = self::query()->with('meeting_types', 'entities_type', 'status')
            ->where(function($query) use ($name, $acronym, $entities_types, $meeting_type) {
                if(!empty($name))
                    $query->where('name', 'like', '%' . $name . '%');

                if(!empty($acronym))
                    $query->where('acronym', 'like', '%' . $acronym . '%');

                if(!empty($entities_types))
                    $query->where('entities_types_id', $entities_types);

                if(!empty($meeting_type))
                    $query->whereHas('meeting_types', function ($q) use($meeting_type)
                    {
                        $q->where('meeting_type_id',$meeting_type);
                    });

                if(!Auth::user()->hasRole([HelperApp::$roleSuperUsuario])){
                    $query->where('status_id','<>', Status::INACTIVO);
                }
            })
            ->orderBy('name','ASC')
            ->paginate($perPage);

        return $model;
    }

    public static function _create(Entity $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function _edit(Entity $model)
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
    
    public function meeting_types()
    {
        return $this->belongsToMany(MeetingType::class,'entities_meeting_types','entity_id','meeting_type_id');
    }

    public function entities_type()
    {
        return $this->belongsTo(EntitiesType::class,'entities_types_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class,"status_id");
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function agreements()
    {
        return $this->belongsToMany(Agreement::class,'agreements_entities','entity_id','agreement_id');
    }
}

