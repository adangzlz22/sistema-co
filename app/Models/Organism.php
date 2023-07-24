<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Enums
use App\Enums\log_movements;
use App\Enums\system_catalogues;

use App\Helpers\HelperApp;


class Organism extends Model
{
    use HasFactory;
    static $system_catalogue = system_catalogues::Organisms;

    protected $fillable = [
        'name', 'acronym','organisms_type_id'
    ];

    public static function getForCatalog()
    {
        $model = Organism::where("active",1)->orderBy("name")->pluck('name', 'id');
        return $model;
    }

    public static function getForCatalogByIdOrganismType($id)
    {
        $model = Organism::where("active",1)->where("organisms_type_id",$id)->orderBy("name")->pluck('name', 'id');
        return $model;
    }


    public function organisms_type()
    {
        return $this->belongsTo(OrganismsType::class,'organisms_type_id');
    }

    public static function get_pagination($name,$acronym,$description, $organisms_type, $perPage)
    {

        $model = Organism::query()
            ->where(function($query) use ($name,$acronym,$description, $organisms_type) {
                if(!empty($name))
                    $query->where('name', 'like', '%' . $name . '%');

                if(!empty($acronym))
                    $query->where('acronym', 'like', '%' . $acronym . '%');

                if(!empty($description))
                    $query->where('description', 'like', '%' . $description . '%');

                if(!empty($organisms_type))
                    $query->where('organisms_type_id', $organisms_type);

                $query->where('active', 1);

            })
            ->orderBy('name','ASC')
            // ->select('name', 'log.date', 'log.id', 'log.date', 'log.movement', 'log.catalogue')
            ->paginate($perPage);

        return $model;
    }
    public static function _create(Organism $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function _edit(Organism $model)
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
            HelperApp::save_log($model->id, log_movements::NewRegister, Organism::$system_catalogue, null, $json_model);

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
            HelperApp::save_log($model->id, log_movements::Edit, Organism::$system_catalogue, $json_old, $json_new);

        });

    }

    public function agreetments(){
        return $this->hasMany(Agreement::class, 'organism_id');
    }



}

