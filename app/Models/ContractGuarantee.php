<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractGuarantee extends Model
{
    use HasFactory;
    protected $fillable = [
        'contract_id', 'guarantee','guarantee_days','day_type',
        'guarantee_type','contract_percentage',
        'date_guarantee','active'
    ];
    protected $dates = ['date_guarantee'];

    public function contract()
    {
        return $this->belongsTo(Contract::class,'contract_id','id');
    }

    public function attach_documents()
    {
        $model = $this->hasMany(ContractGuaranteeDocument::class, 'guarantee_id', 'id');
        return $model->where('active', '=', true)->get();
    }

    public static function _create(ContractGuarantee $model)
    {
        try{
            return ['saved' => $model->save(), 'message' => 'Los datos se guardaron exitosamente.'];
        }
        catch(\Exception $e){
            return ['saved' => false, 'message' => $e->getMessage()];
        }
    }

    public static function _edit(ContractGuarantee $model)
    {
        try{
            $model->update();
            return ['saved' => true, 'message' => 'Los datos se guardaron exitosamente.'];
        }
        catch(\Exception $e){
            return ['saved' => false, 'message' => $e];
        }
    }
}
