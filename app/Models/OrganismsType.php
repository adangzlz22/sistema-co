<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


//Enums
use App\Enums\log_movements;
use App\Enums\system_catalogues;

use App\Helpers\HelperApp;

class OrganismsType extends Model
{
    use HasFactory;

    static $system_catalogue = system_catalogues::OrganismsTypes;
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description'
    ];

    public static function getForCatalog()
    {
        $model = OrganismsType::where("active",1)->orderBy("name")->pluck('name', 'id');
        return $model;
    }

    public static function get_pagination($name,$description, $perPage)
    {

        $model = OrganismsType::query()
            ->where(function($query) use ($name,$description) {
                if(!empty($name))
                    $query->where('name', 'like', '%' . $name . '%');

                if(!empty($description))
                    $query->where('description', 'like', '%' . $description . '%');

                $query->where('active', 1);

            })
            ->orderBy('name','DESC')
            ->paginate($perPage);

        return $model;
    }
    public static function _create(OrganismsType $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function _edit(OrganismsType $model)
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
            HelperApp::save_log($model->id, log_movements::NewRegister, OrganismsType::$system_catalogue, null, $json_model);

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
            HelperApp::save_log($model->id, log_movements::Edit, OrganismsType::$system_catalogue, $json_old, $json_new);

        });

    }
}
