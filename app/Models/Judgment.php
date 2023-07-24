<?php

namespace App\Models;

use App\Enums\log_movements;
use App\Enums\priority;
use App\Enums\status;
use App\Enums\system_catalogues;
use App\Helpers\HelperApp;
use App\Listeners\SendContractNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Judgment extends Model
{
    use HasFactory;
    static $system_catalogue = system_catalogues::Judgmennt;

    protected $appends = ['access_to_revision','its_yours'];

    protected $fillable = [
        'jurisdictional_institutions_id','expedient_types_id','expedient_number','date_presentation','date_registration','actor',
        'organism_id','estimated_amount','priority_id','sentencing_date','comments','state','city','user_id','active'
    ];

    protected $dates = ['date_presentation', 'date_registration', 'sentencing_date','date_save'];

    public static function get_pagination($jurisdictional_institutions_id,$expedient_types_id,$expedient_number,$date_presentation_init,$date_presentation_end,
                                          $date_registration_init,$date_registration_end,$actor,$organism_id, $priority_id,
                                          $sentencing_date_init,$sentencing_date_end,$state,$city,$perPage)
    {

        $model = Judgment::query()
            ->where(function ($query) use (
                $jurisdictional_institutions_id,$expedient_types_id,$expedient_number,$date_presentation_init,$date_presentation_end,
                $date_registration_init,$date_registration_end,$actor,$organism_id, $priority_id,
                $sentencing_date_init,$sentencing_date_end,$state,$city
            ) {

                if (!empty($jurisdictional_institutions_id))
                    $query->where('jurisdictional_institutions_id', $jurisdictional_institutions_id);

                if (!empty($expedient_types_id))
                    $query->where('expedient_types_id', $expedient_types_id);

                if (!empty($expedient_number))
                    $query->where('actor', 'like', '%' . $expedient_number . '%');

                if (!empty($date_presentation_init))
                    $query->whereDate('date_presentation', '>=', \Carbon\Carbon::parse($date_presentation_init)->format('Y-m-d'));

                if (!empty($date_presentation_end))
                    $query->whereDate('date_registration', '<=', \Carbon\Carbon::parse($date_presentation_end)->format('Y-m-d'));

                if (!empty($date_registration_init))
                    $query->whereDate('date_registration', '>=', \Carbon\Carbon::parse($date_registration_init)->format('Y-m-d'));

                if (!empty($date_registration_end))
                    $query->whereDate('date_registration', '<=', \Carbon\Carbon::parse($date_registration_end)->format('Y-m-d'));

                if (!empty($actor))
                    $query->where('actor', 'like', '%' . $actor . '%');

                if (!empty($organisms_id))
                    $query->where('organisms_id', $organisms_id);

                if (!empty($priority_id))
                    $query->where('priority_id', $priority_id);

                if (!empty($sentencing_date_init))
                    $query->whereDate('sentencing_date', '>=', \Carbon\Carbon::parse($sentencing_date_init)->format('Y-m-d'));

                if (!empty($received_date_end))
                    $query->whereDate('sentencing_date', '<=', \Carbon\Carbon::parse($received_date_end)->format('Y-m-d'));

                if (!empty($state))
                    $query->where('state', 'like', '%' . $state . '%');

                if (!empty($city))
                    $query->where('city', 'like', '%' . $city . '%');

                $query->where('judgments.active', 1);

                if (Auth::user()->hasRole(HelperApp::$roleDependenciaEntidadJuicios)) {
                    $query->where('user_id', Auth::user()->id);
                } else if (Auth::user()->hasRole(HelperApp::$roleAdministradorJuicios)) {
                    $query->where('status_id', '!=', status::Saved);
                    // $query->where('organisms_id', \Auth::user()->organisms_id ?? -1);
                } else if (Auth::user()->hasRole(HelperApp::$roleRevisorConsultivoJuicios)) {
                    $query->where('status_id', '>=', status::Shifted);
                }
            });


        if (Auth::user()->hasRole(HelperApp::$roleRevisorConsultivo)) {
            $model = $model->join('judgment_shareds', function ($join) {
                $join->on('judgment_shareds.judgment_id', '=', 'judgments.id')
                    ->where('judgment_shareds.user_id',Auth::user()->id)
                    ->where('judgment_shareds.active', 1);
            });
        }


        $model = $model->orderBy('judgments.created_at', 'DESC')
            ->select('judgments.*')
                /*,
                DB::raw("
                    (SELECT file
                    FROM attach_contract_documents ata_doc
                    WHERE ata_doc.active = 1 AND ata_doc.contract_id = contracts.id AND ata_doc.is_acknowledgment_receipt = 1) AS acknowledgment_receipt_file
                "))*/
            ->paginate($perPage);

        return $model;
    }

    public static function _create(Judgment $model)
    {
        try {
            $model->save();
            return ['saved' => true, 'message' => 'Los datos se guardaron exitosamente.', 'id' => $model->id];
        } catch (\Exception $e) {
            return ['saved' => false, 'message' => $e->getMessage()];
        }
    }

    public static function _edit(Judgment $model)
    {
        try {

            $model->update();
            return ['saved' => true, 'message' => 'Los datos se guardaron exitosamente.'];
        } catch (\Exception $e) {
            return ['saved' => false, 'message' => $e->getMessage()];
        }
    }

    public static function get_by_id($id)
    {
        $model = Judgment::where([
            ['id', '=', $id],
            ['active', '=', true]
        ])->first();
        return $model;
    }

    public function getAccessToRevisionAttribute()
    {
        if ($this->status_id >= status::Revision)
            return true;
        return false;
    }

    public function getItsYoursAttribute()
    {
        if (Auth::user()->hasRole(HelperApp::$roleDependenciaEntidadJuicios) ){

            if($this->user_id == Auth::user()->id)
                return true;
            return false;
        }
        return true;
    }

    public function getStatusLabelAttribute()
    {
        if(!$this->status_id)
            return "<span class='badge'>N/A</span>";
        $enum = status::fromValue($this->status_id);
        $color = status::getColor($enum);

        return "<span class='badge' style='background-color:$color;'>$enum->description</span>";
    }

    public function getPriorityLabelAttribute()
    {
        if(!$this->priority_id)
            return "<span class='badge'>N/A</span>";
        $enum = priority::fromValue($this->priority_id);
        $color = priority::getColor($enum);

        return "<span class='badge' style='background-color:$color;'>$enum->description</span>";
    }

    public function judgment_synthesis()
    {
        $model = $this->hasMany(JudgmentAttachSynthesis::class, 'judgment_id', 'id');
        return $model->where('active', '=', true)->get();
    }

    public function organism()
    {
        return $this->belongsTo(Organism::class, 'organisms_id', 'id');
    }
    public function expedient()
    {
        return $this->belongsTo(ExpedientType::class, 'expedient_types_id', 'id');
    }

    public static function boot() {

        parent::boot();
        static::created(function($model) {
            $json_model = $model->toJson();
            HelperApp::save_log($model->id, log_movements::NewRegister, Judgment::$system_catalogue, null, $json_model);
        });

        static::updated(function ($model) {
            $modelOld = $model->getOriginal();
            $change =  array_merge($modelOld, $model->getChanges());
            //TODO: hacer la bitacora update de juicios
            /*
            if($model->status_id != $modelOld['status_id'] && $model->status_id > status::Registered){
                //Notification
                try{ (new SendContractNotification())->handle($model,1); }catch (\Exception $ex) { }
            }
            */
            //TODO: hacer la bitacora update de contratos
            //relaciones
            // $modelOld['activo'] =  get_label_activo($modelOld['activo']);
            // $model->activo =  get_label_activo($model->activo);
            // created_model_bitacora($model,$modelOld,2,$model->niceNames);
            $json_old = json_encode($modelOld);
            $json_new = json_encode($change);
            HelperApp::save_log($model->id, log_movements::Edit, Judgment::$system_catalogue, $json_old, $json_new);

        });
    }
}
