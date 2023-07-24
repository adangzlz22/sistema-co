<?php

namespace App\Models;

//Enum
use App\Enums\log_movements;
use App\Enums\origin_of_resources;
use App\Enums\status;
use App\Enums\priority;

use App\Enums\system_catalogues;
use App\Helpers\HelperApp;
use App\Listeners\SendContractNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use DateTime;


class Contract extends Model
{
    use HasFactory;
    static $system_catalogue = system_catalogues::Contracts;

    protected $appends = ['registration_date_format'
        ,'registration_date_shareds_format'
        ,'created_at_date_format',
        'status_label','priority_label','priority_description','shifted','registred','terminated','tracking',
        'access_to_revision','access_to_activities','its_yours','its_yours_consultivo'];

    protected $fillable = [
        'contract_number', 'invoice_number', 'received_date', 'received_hour',
        'organisms_id', 'contract_type_id', 'jurisdiction_id', 'assignment_method_id',
        'contract_number_general','object', 'amount_without_tax', 'total_amount', 'valid_from', 'valid_to',
        'payment_description', 'payment_method_id',
        'kind_person_id', 'rfc','legal_name', 'status_id', 'active', 'registration_date', 'priority_id', 'administrative_unit_id','origin_of_resources_id',
        'name_signer','job_signer'
    ];
    protected $hidden = ['its_yours', 'its_yours_consultivo', 'registred', 'shifted', 'terminated', 'tracking',
        'access_to_revision', 'access_to_activities', 'origin_of_resources', 'priority_label', 'priority_description',
        'status_label', 'registration_date_format','is_valid_to_registred'];
    protected $dates = ['valid_to', 'valid_from', 'registration_date', 'received_date'];




    public function getItsYoursAttribute()
    {
        if (Auth::user()->hasRole(HelperApp::$roleDependenciaEntidad) ){

            if($this->user_id == Auth::user()->id)
                return true;
            return false;
        }
        return true;
    }

    public function getItsYoursConsultivoAttribute()
    {
        if (Auth::user()->hasRole(HelperApp::$roleRevisorConsultivo) ){

            if($this->contract_shareds && $this->contract_shareds->Where('user_id',Auth::user()->id)->count() > 0)
                return true;
            return false;
        }
        return true;
    }
    public function getRegistredAttribute()
    {
        if ($this->status_id == status::Registered && !Auth::user()->hasRole(HelperApp::$roleDependenciaEntidad ))
            return true;
        return false;
    }

    public function getShiftedAttribute()
    {

        if ($this->status_id == status::Received || $this->status_id == status::Registered)
            return true;
        return false;
    }
    public function getTerminatedAttribute()
    {
        if ($this->status_id == status::Cancelled || $this->status_id == status::Finished || $this->status_id == status::Attended)
            return true;
        return false;
    }
    public function getIsValidToRegistredAttribute()
    {
        return $this->attach_documents()->where('is_acknowledgment_receipt','=',1)->count();
    }

    public function getTrackingAttribute()
    {
        if ($this->status_id >= status::Received){
            if(Auth::user()->hasRole(HelperApp::$roleDependenciaEntidad ) && $this->status_id < status::Revision )
                return false;
            return true;

        }

        return false;
    }

    public function getAccessToRevisionAttribute()
    {
        if ($this->status_id >= status::Revision)
            return true;
        return false;
    }
    // access_to_activities

    public function getAccessToActivitiesAttribute()
    {
        if (($this->status_id >= status::Revision && $this->status_id <= status::Recommendations) || $this->status_id == status::Signing){
            if(Auth::user()->hasRole(HelperApp::$roleDependenciaEntidad ) && $this->status_id != status::Recommendations && $this->status_id != status::Signing)
                return false;
            return true;
        }
        return false;
    }


    public function getOriginOfResourcesAttribute()
    {
        if($this->origin_of_resources_id && $this->origin_of_resources_id  != 0 ){
            $enum = origin_of_resources::fromValue((int)$this->origin_of_resources_id);
            return $enum->description;
        }
        return "";

    }

    public function getPriorityLabelAttribute()
    {
        if(!$this->priority_id)
            return "<span class='dot' data-toggle='tooltip' data-placement='top' title='N/A'></span>";

        $enum = priority::fromValue((int)$this->priority_id);
        $color = priority::getColor($enum);

        return "<span class='dot' style='background-color:$color;' data-toggle='tooltip' data-placement='top' title='$enum->description'></span>";
    }

    public function getPriorityDescriptionAttribute()
    {
        if(!$this->priority_id)
            return '';

        $enum = priority::fromValue((int)$this->priority_id);
        return $enum->description;
    }

    public function getStatusLabelAttribute()
    {
        if(!$this->status_id)
            return "<span class='badge'>N/A</span>";
        $enum = status::fromValue($this->status_id);
        $color = status::getColor($enum);

        return "<span class='badge' style='background-color:$color;'>$enum->description</span>";
    }

    public function getRegistrationDateFormatAttribute()
    {
        if (!$this->registration_date)
            return '';

        $date = Carbon::parse($this->registration_date);
        return $date->format('d') . ' de ' . $date->translatedFormat('F') . ' del ' . $date->format('Y');// M \del Y');
    }

    public function getCreatedAtDateFormatAttribute()
    {
        if (!$this->created_at)
            return '';

        $date = Carbon::parse($this->created_at);
        return $date->format('d') . ' de ' . $date->translatedFormat('F') . ' del ' . $date->format('Y');// M \del Y');
    }

    public function getRegistrationDateSharedsFormatAttribute()
    {
        if (!$this->contract_shareds->count())
            return '-';

        $contact_shared = $this->contract_shareds->first();

        return $contact_shared->created_at ?  $contact_shared->created_at->format('d-m-Y')  : "";// M \del Y');
    }

    public function signning_functionaries()
    {
        $model = $this->hasMany(SigningFunctionary::class, 'contract_id', 'id');
        return $model->where('active', '=', true)->get();
    }

    public function contract_managers()
    {
        $model = $this->hasMany(ContractManager::class, 'contract_id', 'id');
        return $model->where('active', '=', true)->get();
    }

    public function attach_documents()
    {
        $model = $this->hasMany(AttachContractDocument::class, 'contract_id', 'id');
        return $model->where('active', '=', true)->get();
    }

    public function guarantee()
    {
        return $this->belongsTo(ContractGuarantee::class, 'id', 'contract_id');
    }

    public function attended()
    {
        return $this->belongsTo(AttendedContract::class, 'id', 'contract_id');
    }

    public function opinion()
    {
        return $this->belongsTo(ContractOpinion::class, 'id', 'contract_id');
    }

    public function opinion_SH()
    {
        return $this->belongsTo(ContactOpinionSH::class, 'id', 'contract_id');
    }

    public function opinion_BC()
    {
        return $this->belongsTo(ContactOpinionBC::class, 'id', 'contract_id');
    }

    public function organism()
    {
        return $this->belongsTo(Organism::class, 'organisms_id', 'id');
    }

    public function administrative_unit()
    {
        return $this->belongsTo(AdministrativeUnit::class, 'administrative_unit_id', 'id');
    }

    public function created_user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function contract_type()
    {
        return $this->belongsTo(ContractType::class, 'contract_type_id', 'id');
    }

    public function jurisdiction()
    {
        return $this->belongsTo(JurisdictionContract::class, 'jurisdiction_id', 'id');
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethods::class, 'payment_method_id', 'id');
    }

    public function comment()
    {
        return $this->belongsTo(ContractComment::class, 'id', 'contract_id');
    }

    public function contract_shareds()
    {
        //return $this->belongsTo(ContractShared::class, 'id', 'contract_id');
        return $this->hasMany(ContractShared::class, 'contract_id', 'id');
//        return $model->where('active', '=', true)->get();
    }
    public function contract_trackings()
    {
        $model = $this->hasMany(ContractTracking::class, 'contract_id', 'id');
        return $model->where('active', '=', true)->orderByDesc('created_at');
    }


    public static function get_pagination($invoice_number, $received_date_init, $received_date_end, $organisms_id, $contract_type_id, $object, $priority_id, $status_id, $perPage)
    {

        $model = Contract::query()
            ->where(function ($query) use (
                $invoice_number, $received_date_init, $received_date_end,
                $organisms_id, $contract_type_id, $object, $priority_id, $status_id
            ) {
                if (!empty($invoice_number))
                    $query->where('invoice_number', 'like', '%' . $invoice_number . '%');

                if (!empty($received_date_init)) {
                    //$init_date = DateTime::createFromFormat('yyyy-mm-dd', $received_date_init);
                    // dd($init_date);
                    $query->whereDate('registration_date', '>=', \Carbon\Carbon::parse($received_date_init)->format('Y-m-d'));
                }

                if (!empty($received_date_end)) {
                    //$end_date = DateTime::createFromFormat('yyyy-mm-dd', $received_date_end);
                    $query->whereDate('registration_date', '<=', \Carbon\Carbon::parse($received_date_end)->format('Y-m-d'));
                }
                if (!empty($organisms_id))
                    $query->where('organisms_id', $organisms_id);

                if (!empty($contract_type_id))
                    $query->where('contract_type_id', $contract_type_id);

                if (!empty($object))
                    $query->where('object', 'like', '%' . $object . '%');

                if (!empty($priority_id))
                    $query->where('priority_id', $priority_id);

                if (!empty($status_id))
                    $query->where('status_id', $status_id);

                $query->where('contracts.active', 1);

                if (Auth::user()->hasRole(HelperApp::$roleDependenciaEntidad )) {
                    $query->where('user_id', Auth::user()->id);
                } else if (Auth::user()->hasRole(HelperApp::$roleAdministradorConsultivo)) {
                    $query->where('status_id', '!=', status::Saved);
                    // $query->where('organisms_id', \Auth::user()->organisms_id ?? -1);
                } else if (Auth::user()->hasRole(HelperApp::$roleRevisorConsultivo)) {
                    $query->where('status_id', '>=', status::Shifted);
                }

            });

        if (Auth::user()->hasRole(HelperApp::$roleRevisorConsultivo)) {
            $model = $model->join('contract_shareds', function ($join) {
                $join->on('contract_shareds.contract_id', '=', 'contracts.id')
                    ->where('contract_shareds.user_id',Auth::user()->id)
                    ->where('contract_shareds.active', 1);
            });
        }

        $model = $model->orderBy('contracts.created_at', 'DESC')
            ->select('contracts.*',
                DB::raw("
                    (SELECT file
                    FROM attach_contract_documents ata_doc
                    WHERE ata_doc.active = 1 AND ata_doc.contract_id = contracts.id AND ata_doc.is_acknowledgment_receipt = 1) AS acknowledgment_receipt_file
                "))
            ->paginate($perPage);

        return $model;
    }

    public static function _create(Contract $model)
    {
        try {
            $model->save();
            return ['saved' => true, 'message' => 'Los datos se guardaron exitosamente.', 'id' => $model->id];
        } catch (\Exception $e) {
            return ['saved' => false, 'message' => $e->getMessage()];
        }
    }

    public static function _edit(Contract $model)
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
        $model = Contract::where([
            ['id', '=', $id],
            ['active', '=', true]
        ])->first();
        return $model;
    }

    public static function get_list_for_dashboard($request)
    {

        $model = Contract::query()
            ->where('active', true)
            ->where('status_id','!=', status::Saved)
            ->where(function($query) use ($request) {

                if(!empty($request->init_date)) {
                    $query->whereDate('created_at', '>=', $request->init_date);
                }
                if(!empty($request->end_date)) {
                    $query->whereDate('created_at', '<=', $request->end_date);
                }
                if(!empty($request->organism_id))
                    $query->where('organisms_id', $request->organism_id);

                if(Auth::user()->hasRole(HelperApp::$roleDependenciaEntidad))
                    $query->where('organisms_id', Auth::user()->organisms_id);
            })
            ->select([
                'status_id',
                'priority_id',
                'contract_type_id',
                'organisms_id',
            ])->get();



        return $model;


    }

    public static function organismContractDashboard($model){

        $model = $model->groupBy('organisms_id');


        $model =  $model->map(function ($v, $k){

            return [
                'name' => $v->first()->organism->name,
                'short_name' => $v->first()->organism->acronym,
                'value' => $v->count(),
            ];

        });

        return $model->values();

    }

    public static function statusContractDashboard($model){

        $model = $model->groupBy('status_id');


        $model =  $model->map(function ($v, $k){

            return [
                'name' =>  \App\Enums\status::getDescription($v->first()->status_id),
                //'short_name' => $v->first()->organism->acronym,
                'value' => $v->count(),
                'color' => \App\Enums\status::getColor(status::fromValue($v->first()->status_id)),
            ];

        });

        return $model->values();

    }


    public static function priorityContractDashboard($model){

        $model = $model->groupBy('priority_id');


        $model =  $model->map(function ($v, $k){

            return [
                'name' =>  \App\Enums\priority::getDescription($v->first()->priority_id),
                //'short_name' => $v->first()->organism->acronym,
                'value' => $v->count(),
                'color' => \App\Enums\priority::getColor(priority::fromValue($v->first()->priority_id)),
            ];

        });

        return $model->values();

    }


    public static function typeContractDashboard($model){

        $model = $model->groupBy('contract_type_id');


        $model =  $model->map(function ($v, $k){

            return [
                'name' =>  $v->first()->contract_type->name ?? 'No especificado',
                //'short_name' => $v->first()->organism->acronym,
                'value' => $v->count(),
                //'color' => \App\Enums\priority::getColor(priority::fromValue($v->first()->contract_type_id)),
            ];

        });

        return $model->values();

    }

    public static function boot() {

        parent::boot();
        static::created(function($model) {
            $json_model = $model->toJson();
            HelperApp::save_log($model->id, log_movements::NewRegister, Contract::$system_catalogue, null, $json_model);
        });
        static::updated(function ($model) {
            $modelOld = $model->getOriginal();

            if($model->status_id != $modelOld['status_id'] && $model->status_id > status::Registered){
                //Notification
                try{ (new SendContractNotification())->handle($model,1); }catch (\Exception $ex) { }
            }

            //TODO: hacer la bitacora update de contratos

//            $change =  array_merge($modelOld, $model->getChanges());
//            //relaciones
//            // $modelOld['activo'] =  get_label_activo($modelOld['activo']);
//            // $model->activo =  get_label_activo($model->activo);
//            // created_model_bitacora($model,$modelOld,2,$model->niceNames);
//            $json_old = json_encode($modelOld);
//            $json_new = json_encode($change);
//            HelperApp::save_log($model->id, log_movements::Edit, Organism::$system_catalogue, $json_old, $json_new);

        });

    }


}
