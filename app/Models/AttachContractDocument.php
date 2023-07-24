<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttachContractDocument extends Model
{
    use HasFactory;
    protected $fillable = [
        'contract_id', 'name', 'is_acknowledgment_receipt','file','active'
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class,'contract_id');
    }

    public static function _create(AttachContractDocument $model)
    {
        try{
            return ['saved' => $model->save(), 'message' => 'Los datos se guardaron exitosamente.'];
        }
        catch(\Exception $e){
            return ['saved' => false, 'message' => $e->getMessage()];
        }
    }

    public static function _edit(AttachContractDocument $model)
    {
        try{
            $model->update();
            return ['saved' => true, 'message' => 'Los datos se guardaron exitosamente.'];
        }
        catch(\Exception $e){
            return ['saved' => false, 'message' => $e];
        }
    }

    public static function exists_acknowledgment_receipt($contract_id,$id){
        $exists = AttachContractDocument::where([
            'contract_id' => $contract_id,
            'active' => true,
            'is_acknowledgment_receipt' => true
        ])->where('id','!=',$id)->exists();


        return $exists;
    }
}
