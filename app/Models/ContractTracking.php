<?php

namespace App\Models;

use App\Enums\log_movements;
use App\Enums\status;
use App\Enums\system_catalogues;
use App\Helpers\HelperApp;
use App\Listeners\SendContractNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ContractTracking extends Model
{
    static $system_catalogue = system_catalogues::ContractTrackig;
    use HasFactory;
    protected $appends = ['its_yours','was_updated'];
    protected $fillable = [
        'contract_id','comments', 'agreement_id','user_id'
    ];


    public function getWasUpdatedAttribute(){
        if($this->created_at != $this->updated_at){
            $date = $this->updated_at->diffForHumans();
            return " <span class='badge bg-info'>Modificado $date  </span>";
        }
        return '';
    }

    public function getItsYoursAttribute()
    {
        if ($this->user_id == Auth::user()->id){
            return true;
        }
        return false;
    }
    public function conctract()
    {
        return $this->belongsTo(Contract::class,'contract_id','id');
    }
    public function created_user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public static function _create(ContractTracking $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function _edit(ContractTracking $model)
    {
        try{
            $model->update();
            return ['saved' => true, 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e];
        }
    }

    public static function get_by_id($id)
    {
        $model = ContractTracking::where([
            ['id', '=', $id],
            ['active', '=', true]
        ])->first();
        return $model;
    }


    public static function boot() {

        parent::boot();
        static::created(function($model) {

            try{ (new SendContractNotification())->handle($model,3); }catch (\Exception $ex) { dd($ex);}

            $json_model = $model->toJson();
            HelperApp::save_log($model->id, log_movements::NewRegister, ContractTracking::$system_catalogue, null, $json_model);

        });
        static::updated(function ($model) {
            //TODO: hacer la bitacora update de tracking cotratos
//            $modelOld = $model->getOriginal();
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
