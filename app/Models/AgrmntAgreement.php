<?php

namespace App\Models;

use App\Enums\status;
use App\Enums\system_catalogues;
use App\Helpers\HelperApp;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AgrmntAgreement extends Model
{
    static $system_catalogue = system_catalogues::AgrmntAgreement;

    protected $appends = ['registration_date_format'
        ,'registration_date_shareds_format'
        ,'created_at_date_format',
        'status_label','priority_label','priority_description','shifted','registred','terminated','tracking',
        'access_to_revision','access_to_activities','its_yours','its_yours_consultivo'];

    protected $fillable = [
        'organisms_id','administrative_unit_id','priority_id','contract_number','invoice_number','received_date','received_hour',
        'name_signer','job_signer','agreement_type_id','jurisdiction_id','description','agreement_number_general','agreement_organisms_type_id',
        'agreement_organisms_id'
    ];
    protected $hidden = ['its_yours', 'its_yours_consultivo', 'registred', 'shifted', 'terminated', 'tracking',
        'access_to_revision', 'access_to_activities', 'origin_of_resources', 'priority_label', 'priority_description',
        'status_label', 'registration_date_format','is_valid_to_registred'];
    protected $dates = ['valid_to', 'valid_from', 'registration_date', 'received_date'];

    public static function get_pagination($invoice_number, $received_date_init, $received_date_end, $organisms_id, $type_agreement_id, $description, $priority_id, $status_id, $perPage)
    {

        $model = AgrmntAgreement::query()
            ->where(function ($query) use (
                $invoice_number, $received_date_init, $received_date_end,
                $organisms_id, $type_agreement_id, $description, $priority_id, $status_id
            ) {
                if (!empty($invoice_number))
                    $query->where('invoice_number', 'like', '%' . $invoice_number . '%');

                if (!empty($received_date_init)) {
                    $query->whereDate('registration_date', '>=', \Carbon\Carbon::parse($received_date_init)->format('Y-m-d'));
                }

                if (!empty($received_date_end)) {
                    $query->whereDate('registration_date', '<=', \Carbon\Carbon::parse($received_date_end)->format('Y-m-d'));
                }
                if (!empty($organisms_id))
                    $query->where('organisms_id', $organisms_id);

                if (!empty($type_agreement_id))
                    $query->where('agreement_type_id', $type_agreement_id);

                if (!empty($description))
                    $query->where('description', 'like', '%' . $description . '%');

                if (!empty($priority_id))
                    $query->where('priority_id', $priority_id);

                if (!empty($status_id))
                    $query->where('status_id', $status_id);

                $query->where('agrmnt_agreements.active', 1);

                if (Auth::user()->hasRole(HelperApp::$roleDependenciaEntidad )) {
                    $query->where('user_id', Auth::user()->id);
                } else if (Auth::user()->hasRole(HelperApp::$roleAdministradorConsultivo)) {
                    $query->where('status_id', '!=', status::Saved);
                } else if (Auth::user()->hasRole(HelperApp::$roleRevisorConsultivo)) {
                    $query->where('status_id', '>=', status::Shifted);
                }

            });

        //TODO: descomentar cuando se  tenga esta parte
        /*if (Auth::user()->hasRole(HelperApp::$roleRevisorConsultivo)) {
            $model = $model->join('contract_shareds', function ($join) {
                $join->on('contract_shareds.contract_id', '=', 'contracts.id')
                    ->where('contract_shareds.user_id',Auth::user()->id)
                    ->where('contract_shareds.active', 1);
            });
        }*/
        //TODO: cambiar cuando se tenga esto
        $model = $model->orderBy('agrmnt_agreements.created_at', 'DESC')
            ->select('agrmnt_agreements.*',
                DB::raw("
                    (SELECT file
                    FROM attach_contract_documents ata_doc
                    WHERE ata_doc.active = 1 AND ata_doc.contract_id = agrmnt_agreements.id AND ata_doc.is_acknowledgment_receipt = 1) AS acknowledgment_receipt_file
                "))
            ->paginate($perPage);

        return $model;
    }

    public static function _create(AgrmntAgreement $model)
    {
        try {
            $model->save();
            return ['saved' => true, 'message' => 'Los datos se guardaron exitosamente.', 'id' => $model->id];
        } catch (\Exception $e) {
            return ['saved' => false, 'message' => $e->getMessage()];
        }
    }

    public static function _edit(AgrmntAgreement $model)
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
        $model = AgrmntAgreement::where([
            ['id', '=', $id],
            ['active', '=', true]
        ])->first();
        return $model;
    }

    //#region foreign objects
    public function type_agreement()
    {
        return $this->belongsTo(TypeAgreement::class, 'agreement_type_id', 'id');
    }

    public function institutions()
    {
        $model = $this->hasMany(AgrmntInstitution::class, 'agrmnt_agreement_id', 'id');
        return $model->where('active', '=', true)->get();
    }

    public function signning_functionaries()
    {
        $model = $this->hasMany(AgrmntSigningFunctionary::class, 'agrmnt_agreement_id', 'id');
        return $model->where('active', '=', true)->get();
    }

    public function attach_documents()
    {
        $model = $this->hasMany(AgrmntAttachDocument::class, 'agrmnt_agreement_id', 'id');
        return $model->where('active', '=', true)->get();
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
    //#endregion

    #region attributes
    public function getCreatedAtDateFormatAttribute()
    {
        if (!$this->created_at)
            return '';

        $date = Carbon::parse($this->created_at);
        return $date->format('d') . ' de ' . $date->translatedFormat('F') . ' del ' . $date->format('Y');// M \del Y');
    }

    public function getRegistrationDateFormatAttribute()
    {
        if (!$this->registration_date)
            return '';

        $date = Carbon::parse($this->registration_date);
        return $date->format('d') . ' de ' . $date->translatedFormat('F') . ' del ' . $date->format('Y');// M \del Y');
    }
    #endregion
}
