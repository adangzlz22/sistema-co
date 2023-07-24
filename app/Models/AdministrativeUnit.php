<?php

namespace App\Models;

use App\Enums\log_movements;
use App\Enums\status;
use App\Enums\system_catalogues;
use App\Helpers\HelperApp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AdministrativeUnit extends Model
{
    use HasFactory;
    static $system_catalogue = system_catalogues::AdministrativeUnits;

    protected $fillable = [
        'organism_id', 'name', 'acronym', 'description'
    ];


    public static function getForCatalogByIdOrganismsId($id)
    {
        $model = AdministrativeUnit::where("active",1)->where("organism_id",$id)->orderBy('name')->pluck('name','id');
        return $model;
    }

    public static function getForCatalogByIdOrganismsId_contract($id)
    {
        $model = AdministrativeUnit::where("active",1)->where("organism_id",$id)->orderBy('name')->get();
        return $model;
    }

    public static function create(AdministrativeUnit $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(AdministrativeUnit $model)
    {
        try{
            $model->update();
            return ['saved' => true, 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e];
        }
    }

    public function organism()
    {
        return $this->belongsTo(Organism::class,'organism_id');
    }

    public static function get_by_id($id)
    {
        $model = AdministrativeUnit::where([
            ['id', '=', $id],
            ['active', '=', true]
        ])->first();
        return $model;
    }

    public static function get_pagination($name, $organisms_id, $perPage)
    {
        $model = AdministrativeUnit::query()
            ->where(function($query) use ($name,$organisms_id) {

                if(!empty($name))
                    $query->where('name', 'like', '%' . $name . '%');

                if(!empty($organisms_id))
                    $query->where('organism_id', '=', $organisms_id);

                $query->where('active', '=', 1);

            })
            ->orderBy('id','ASC');
        return $model->paginate($perPage);
    }
    public static function boot() {

        parent::boot();
        static::created(function($model) {
            $json_model = $model->toJson();
            HelperApp::save_log($model->id, log_movements::NewRegister, AdministrativeUnit::$system_catalogue, null, $json_model);

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
            HelperApp::save_log($model->id, log_movements::Edit, AdministrativeUnit::$system_catalogue, $json_old, $json_new);

        });

    }
}
