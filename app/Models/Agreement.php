<?php

namespace App\Models;

use DateTime;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


use Illuminate\Support\Facades\DB;

//Enums
use App\Enums\log_movements;
use App\Enums\system_catalogues;

use App\Helpers\HelperApp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Agreement extends Model
{
    use HasFactory;
    static $system_catalogue = system_catalogues::Agreements;

    protected $fillable = [
        'agreement','meeting_id','user_id','status_id',
    ];


    public static function _create(Agreement $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function _edit(Agreement $model)
    {
        try{
            $model->update();
            return ['saved' => true, 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e];
        }
    }

    public static function getForAgreementId($agreement_id)
    {
        $arrAgreements = self::with('entities', 'meeting')
            ->where('agreements.id', $agreement_id)->get();

        return $arrAgreements;
    }

    public static function get_by_id($id)
    {
        $model = Agreement::where([
            ['id', '=', $id],
            ['status_id', '<>', Status::INACTIVO]
        ])->first();
        return $model;
    }

    public static function get_count_by_year($year)
    {
        $model = Agreement::where([
            ['status_id', '<>', Status::INACTIVO]
        ])
            ->where(DB::raw('YEAR(created_at)'), '=', $year)
            ->count();
        return $model;
    }

    public function status_label()
    {
        $span ='';
        switch ($this->status) {
            case 5:
                $span= '<span class="badge bg-danger-400 text-white-transparent-100 px-2 pt-5px pb-5px rounded fs-12px d-inline-flex align-items-center"><i class="fa fa-circle text-white-500 fs-9px fa-fw me-5px"></i> SIN_AVANCE</span>';
                break;
            case 6:
                $span= '<span class="badge bg-warning-400 text-white-transparent-100 px-2 pt-5px pb-5px rounded fs-12px d-inline-flex align-items-center"><i class="fa fa-circle text-white-500 fs-9px fa-fw me-5px"></i> EN_PROCESO</span>';
                break;
            case 7:
                $span= '<span class="badge bg-success-400 text-white-transparent-100 px-2 pt-5px pb-5px rounded fs-12px d-inline-flex align-items-center"><i class="fa fa-circle text-white-500 fs-9px fa-fw me-5px"></i> CONCLUIDO</span>';
                break;
            default:
            $span= '<span class="badge bg-secondary-400 text-white-transparent-100 px-2 pt-5px pb-5px rounded fs-12px d-inline-flex align-items-center"><i class="fa fa-circle text-white-500 fs-9px fa-fw me-5px"></i> SIN_AVANCE</span>';
                break;
        }
        return $span;
    }

    public static function get_pagination($agreement, $meeting_type_id, $meeting_id, $entity_id, $start_date, $end_date, $status_id, $perPage)
    {
        $model = Agreement::query()->select("*")->with('entities', 'meeting', 'status')->withCount(['actions' => function ($q)
        {
            $q->whereIn("status_id",[...Status::ACTIONS]);
        },'actions as actions_c'=>function ($q)
        {
            $q->where("status_id",Status::CONCLUIDO);
        },'actions as actions_p'=>function ($q)
        {
            $q->where("status_id",Status::EN_PROCESO);
        }])
            ->where(function($query) use ($agreement, $meeting_type_id, $meeting_id, $entity_id, $start_date, $end_date, $status_id) {
                if(!empty($meeting_id))
                    $query->where('meeting_id', '=', $meeting_id);

                if(!empty($agreement))
                    $query->where('agreement', 'like', '%' . $agreement . '%');

                if(!empty($status_id))
                    $query->where('status_id', '=', $status_id);

                if(!empty($entity_id)||!empty(Auth::user()->entity))
                    $query->whereHas('entities',function ($q) use($entity_id)
                    {
                        $q->where('entity_id',Auth::user()->entity->id??$entity_id);
                    }); 
                
                if(!empty($meeting_type_id))
                    $query->whereHas('meeting',function ($q) use($meeting_type_id)
                    {
                        $q->where('meeting_type_id',$meeting_type_id);
                    }); 

                if(!empty($start_date))
                    $query->whereHas('meeting',function ($q) use($start_date)
                    {
                        $q->whereDate('meeting_date','>=',Carbon::parse($start_date)->format('Y-m-d'));
                    });

                if(!empty($end_date))
                    $query->whereHas('meeting',function ($q) use($end_date)
                    {
                        $q->whereDate('meeting_date','<=',Carbon::parse($end_date)->format('Y-m-d'));
                    });
                    
                if(!Auth::user()->hasRole([HelperApp::$roleSuperUsuario])){
                    $query->where('status_id','<>', Status::INACTIVO);
                }
            })
            ->orderBy('id','DESC')
            ->orderBy('status_id','ASC');
        return $model->paginate($perPage);
    }

    public static function getForMeetingId($meeting_id)
    {
        $arrAgreements = self::query()->with('entities', 'meeting', 'status')
            ->where(function($query) use ($meeting_id) {
                if(!empty($meeting_id))
                    $query->where('meeting_id', $meeting_id);
                    
            })->orderBy('name','ASC');

        return $arrAgreements;
    }
    
    public static function boot() {

        parent::boot();
        static::created(function($model) {
            $json_model = $model->toJson();
            HelperApp::save_log($model->id, log_movements::NewRegister, Agreement::$system_catalogue, null, $json_model);

        });
        static::updated(function ($model) {
            $modelOld = $model->getOriginal();
            $change =  array_merge($modelOld, $model->getChanges());
            $json_old = json_encode($modelOld);
            $json_new = json_encode($change);
            HelperApp::save_log($model->id, log_movements::Edit, Agreement::$system_catalogue, $json_old, $json_new);

        });

    }

    public function meeting()
    {
        return $this->belongsTo(Meeting::class, 'meeting_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function entities()
    {
        return $this->belongsToMany(Entity::class,'agreements_entities','agreement_id','entity_id');
    }

    // public function replies()
    // {
    //     $model = $this->hasMany(Reply::class, 'agreement_id');
    //     return $model->where('status_id', '<>', Status::INACTIVO);
    // }

    public function actions()
    {
        $model = $this->hasMany(Action::class, 'agreement_id');
        return $model->where('status_id', '<>', Status::INACTIVO);
    }
}
