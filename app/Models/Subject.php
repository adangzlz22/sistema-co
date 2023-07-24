<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Enums
use App\Enums\log_movements;
use App\Enums\system_catalogues;

use App\Helpers\HelperApp;
use Illuminate\Support\Facades\Auth;

class Subject extends Model
{
    use HasFactory;
    static $system_catalogue = system_catalogues::Subjects;

    protected $fillable = [
        'subject','expositor','observation','meeting_id','entity_id','status_id','user_id'
    ];

    public static function getForMeetingId($meeting_id)
    {
        $arrSubjects = self::join('entities', 'subjects.entity_id', '=', 'entities.id')
            ->join('status', 'subjects.status_id', '=', 'status.id')
            ->select(
                'subjects.*',
                'entities.acronym', 
                'entities.name as entities_name', 
                'entities.email', 
                'entities.job', 
                'status.name as status_name', 
                'status.color as status_color'
            )
            ->where('subjects.meeting_id', $meeting_id)
            ->where('subjects.status_id', Status::ACTIVO)->get();

        return $arrSubjects;
    }

    public static function getForSubjectId($subject_id)
    {
        $arrSubjects = self::select(
                'subjects.*',
            )
            ->where('subjects.id', $subject_id)->get();

        return $arrSubjects;
    }

    public static function get_pagination($meeting_id, $subject, $expositor, $entity_id, $perPage)
    {

        $model = self::query()->with('entity')
            ->where(function($query) use ($meeting_id, $subject, $expositor, $entity_id) {
                if(!empty($meeting_id))
                    $query->where('meeting_id', $meeting_id);

                if(!empty($subject))
                    $query->where('subject', 'like', '%' . $subject . '%');

                if(!empty($expositor))
                    $query->where('expositor', 'like', '%' . $expositor . '%');

                if(!empty($entity_id))
                    $query->where('entity_id', $entity_id);

                if(!Auth::user()->hasRole([HelperApp::$roleSuperUsuario])){
                    $query->where('status_id','<>', Status::INACTIVO);
                }

                $query->where('status_id', Status::ACTIVO);
            })
            ->orderBy('id','ASC')
            ->paginate($perPage);

        return $model;
    }

    public static function _create(Subject $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => '', 'id' => $model->id];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function _edit(Subject $model)
    {
        try{
            $model->update();
            return ['saved' => true, 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e];
        }
    }

    public function status()
    {
        return $this->belongsTo(Status::class,"status_id");
    }

    public function entity()
    {
        return $this->belongsTo(Entity::class, "entity_id");
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

