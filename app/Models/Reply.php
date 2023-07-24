<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Enums
use App\Enums\log_movements;
use App\Enums\system_catalogues;

use App\Helpers\HelperApp;
use Illuminate\Support\Facades\Auth;

class Reply extends Model
{
    use HasFactory;
    static $system_catalogue = system_catalogues::Replies;

    protected $table = 'replies';

    protected $fillable = [
        'reply','agreement_id','entity_id','user_id','status_id',
    ];

    public static function get_pagination($reply, $agreement_id, $entity_id, $perPage)
    {

        $model = self::query()
            ->where(function($query) use ($reply, $agreement_id, $entity_id) {
                if(!empty($reply))
                    $query->where('reply', 'like', '%' . $reply . '%');

                if(!empty($agreement_id))
                    $query->where('agreement_id', $agreement_id);

                if(!empty($entity_id) || Auth::user()->hasRole([HelperApp::$roleEntidad])){
                    $query->where('entity_id', Auth::user()->entity->id??$entity_id);
                }

                if(!empty($status))
                    $query->where('status_id', $status);

                if(!Auth::user()->hasRole([HelperApp::$roleSuperUsuario])){
                    $query->where('status_id','<>', Status::INACTIVO);
                }
            })
            ->orderBy('id','DESC')
            ->paginate($perPage);

        return $model;
    }
    public static function _create(Reply $model)
    {
        try{
            $model->save();
            return ['saved' => true, 'error' => '' ,'id' => $model->id];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function _edit(Reply $model)
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

    public function agreement()
    {
        return $this->belongsTo(Agreement::class, 'agreement_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function entity()
    {
        return $this->belongsTo(Entity::class, 'entity_id');
    }

}

