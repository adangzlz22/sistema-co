<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Enums
use App\Enums\log_movements;
use App\Enums\system_catalogues;

use App\Helpers\HelperApp;
use App\Listeners\SendMeetingNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Meeting extends Model
{
    use HasFactory;
    static $system_catalogue = system_catalogues::Meetings;

    protected $fillable = [
        'folio','meeting_date','meeting_time','meeting_time_end','place_id','link','modality_id','status_id'
    ];

    public static function getForCatalogByTypeId($type)
    {
        $model = self::where("status_id","<>", Status::INACTIVO)->where(function ($q) use($type)
        {
            if(!empty($type))
                $q->where("meeting_type_id", $type);

        })->orderBy("folio")->pluck('folio', 'id');
        return $model;
    }

    public static function getPreviousFolio()
    {
        $folio = self::max('folio');
        return $folio;
    }

    public static function getMeetings()
    {
        $arrMeeting = self::join('meeting_types', 'meetings.meeting_type_id', '=', 'meeting_types.id')
            ->join('modalities', 'meetings.modality_id', '=', 'modalities.id')
            ->join('status', 'meetings.status_id', '=', 'status.id')
            ->select(
                'meetings.*',
                'meeting_types.acronym as meeting_type_acronym', 
                'meeting_types.name as meeting_type_name', 
                'meeting_types.color as meeting_color', 
                'modalities.name as modality_name',
                'status.name as status_name', 
                'status.color'
            )->get();

        return $arrMeeting;
    }

    public static function getForMeetingId($id)
    {
        $arrMeeting = self::join('meeting_types', 'meetings.meeting_type_id', '=', 'meeting_types.id')
            ->join('modalities', 'meetings.modality_id', '=', 'modalities.id')
            ->join('status', 'meetings.status_id', '=', 'status.id')
            ->select(
                'meetings.*',
                'meeting_types.id as meeting_type_id', 
                'meeting_types.acronym', 
                'meeting_types.name as meeting_name', 
                'meeting_types.color as meeting_color', 
                'modalities.name as modality_name', 
                'status.name as status_name', 
                'status.color'
            )
            ->where('meetings.id', $id)->get();

        return $arrMeeting;
    }

    public static function get_pagination($folio, $meeting_type, $init_date, $end_date, $status_id, $perPage)
    {
        $model = self::query()
            ->where(function($query) use ($folio, $meeting_type, $init_date, $end_date, $status_id) {
                if(!empty($folio))
                    $query->where('folio', 'like', '%' . $folio . '%');

                if(!empty($meeting_type))
                    $query->where('meeting_type_id', $meeting_type);

                if(!empty($init_date) && !empty($end_date))
                    $query->whereBetween('meeting_date', [HelperApp::formatDate($init_date), HelperApp::formatDate($end_date)]);

                if(!empty($status_id))
                    $query->where('status_id', $status_id);

                if(Auth::user()->hasRole([HelperApp::$roleEntidad])) {

                       $query->whereIn('meeting_type_id', Auth::user()->entity->meeting_types->pluck('id'));
          
                }

            })
            ->orderBy('meeting_date','ASC')
            ->paginate($perPage);

        return $model;
    }

    public static function _create(Meeting $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => '', 'id' => $model->id];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage(), 'id' => false];
        }
    }

    public static function _edit(Meeting $model)
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

    public function meeting_type()
    {
        return $this->belongsTo(MeetingType::class,"meeting_type_id");
    }

    public function modality()
    {
        return $this->belongsTo(Modality::class,"modality_id");
    }

    public function status()
    {
        return $this->belongsTo(Status::class,"status_id");
    }

    public function place()
    {
        return $this->belongsTo(Place::class,"place_id");
    }
}

