<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Enums
use App\Enums\log_movements;
use App\Enums\system_catalogues;

use App\Helpers\HelperApp;
use Illuminate\Support\Facades\Auth;

class Action extends Model
{
    use HasFactory;
    static $system_catalogue = system_catalogues::Actions;

    protected $table = 'actions';
    
    protected $fillable = [
        'action','agreement_id','user_id','entity_id','status_id',
    ];

    /**
     * Usar en update del status de acciones
     * Si la validacion se cumple:
     * Actualizar Agreement a CONCLUIDO
     * Si la validacion no se cumple y Agreement es <> EN_PROCESO:
     * Actualizar Agreement a EN_PROCESO
     */
    private static function validateUpdateAgreementStatus($agreement_id)
    {
        return self::where('agreement_id',$agreement_id)->where('status_id','<>',Status::INACTIVO)->where('status_id','<>',Status::CONCLUIDO)->count()==0;
    }

    private static function validateUpdateAgreementOnCreate($agreement_id)
    {
        return self::where('agreement_id',$agreement_id)->where('status_id','<>',Status::INACTIVO)->where('status_id','<>',Status::SIN_AVANCE)->count()>0;
    }

    public static function get_pagination($action, $agreement_id, $entity_id, $status_id, $perPage)
    {

        $model = self::query()
            ->where(function($query) use ($action, $agreement_id, $entity_id, $status_id) {
                if(!empty($action))
                    $query->where('action', 'like', '%' . $action . '%');

                if(!empty($agreement_id))
                    $query->where('agreement_id', $agreement_id);

                if(!empty($entity_id) || Auth::user()->hasRole([HelperApp::$roleEntidad])){
                    $query->where('entity_id', Auth::user()->entity->id??$entity_id);
                }

                if(!empty($status_id))
                    $query->where('status_id', $status_id);

                // if(!Auth::user()->hasRole([HelperApp::$roleSuperUsuario])){
                    $query->where('status_id','<>', Status::INACTIVO);
                // }
            })
            ->orderBy('status_id','ASC')
            ->orderBy('id','DESC')
            ->paginate($perPage);

        return $model;
    }
    public static function _create(Action $model)
    {
        try{
            $agreement = Agreement::findOrFail($model->agreement_id);
            $model->save();
            if(self::validateUpdateAgreementOnCreate($agreement->id)){
                $agreement->status_id=Status::EN_PROCESO;
                Agreement::_edit($agreement);
            }
            return ['saved' => true, 'error' => '' ,'id' => $model->id];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function _edit(Action $model)
    {
        try{
            $agreement = Agreement::findOrFail($model->agreement_id);
            $model->update();
            self::validateUpdateAgreementStatus($agreement->id)?$agreement->status_id=Status::CONCLUIDO:$agreement->status_id=Status::EN_PROCESO;
            Agreement::_edit($agreement);
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

    public function agreement()
    {
        return $this->belongsTo(Agreement::class, 'agreement_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function entity()
    {
        return $this->belongsTo(Entity::class, 'entity_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function replies()
    {
        $model = $this->hasMany(Reply::class, 'action_id');
        return $model->where('status_id', '<>', Status::INACTIVO)->orderBy('created_at','DESC');
    }

    public function files()
    {
        $model = $this->hasMany(File::class, 'parent_id');
        return $model->where('modulo', File::MOD_ACTIONS)->orderBy('created_at','DESC');
    }

}

