<?php

namespace App\Models;

use App\Enums\log_movements;
use App\Enums\status;
use App\Enums\system_catalogues;
use App\Helpers\HelperApp;
use App\Listeners\SendContractNotification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendedContract extends Model
{
    use HasFactory;
    static $system_catalogue = system_catalogues::AttendedContract;


    protected $fillable = [
        'contract_id', 'attended_date', 'folio', 'addressee_job_name', 'position_addressee_job_name', 'validated', 'destination_folio', 'content_1', 'content_2', 'signer_user_id'
    ];

    protected $dates = ['attended_date'];

    protected $casts = [
        'validated' => 'boolean'
    ];


    public static function _create(AttendedContract $model)
    {
        try {
            $model->save();
            return ['saved' => true, 'message' => 'Los datos se guardaron exitosamente.', 'id' => $model->id];
        } catch (\Exception $e) {
            return ['saved' => false, 'message' => $e->getMessage()];
        }
    }

    public static function _edit(AttendedContract $model)
    {
        try {

            $model->update();
            return ['saved' => true, 'message' => 'Los datos se guardaron exitosamente.'];
        } catch (\Exception $e) {
            return ['saved' => false, 'message' => $e->getMessage()];
        }
    }

    public function signer_user()
    {
        return $this->belongsTo(User::class, 'signer_user_id', 'id');
    }

    public static function get_by_contract_id($id)
    {
        $model = AttendedContract::where([
            ['contract_id', '=', $id]
        ])->first();
        return $model;
    }

    public function getAttendeddateFormatAttribute()
    {
        if (!$this->attended_date)
            return '';

        $date = Carbon::parse($this->attended_date);
        return $date->format('d') . ' de ' . $date->translatedFormat('F') . ' del ' . $date->format('Y');// M \del Y');
    }

    public static function boot() {

        parent::boot();
        static::created(function($model) {
            $json_model = $model->toJson();
            HelperApp::save_log($model->id, log_movements::NewRegister, AttendedContract::$system_catalogue, null, $json_model);
        });
        static::updated(function ($model) {
            $modelOld = $model->getOriginal();
            $change =  array_merge($modelOld, $model->getChanges());
            //relaciones
            $json_old = json_encode($modelOld);
            $json_new = json_encode($change);
            HelperApp::save_log($model->id, log_movements::Edit, AttendedContract::$system_catalogue, $json_old, $json_new);
        });

    }
}
