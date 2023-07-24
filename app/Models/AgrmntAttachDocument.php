<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgrmntAttachDocument extends Model
{
    use HasFactory;


    protected $fillable = [
        'agrmnt_agreement_id', 'name', 'is_acknowledgment_receipt','file','active'
    ];

    public function agreement()
    {
        return $this->belongsTo(AgrmntAgreement::class,'agrmnt_agreement_id');
    }

    public static function _create(AgrmntAttachDocument $model)
    {
        try{
            return ['saved' => $model->save(), 'message' => 'Los datos se guardaron exitosamente.'];
        }
        catch(\Exception $e){
            return ['saved' => false, 'message' => $e->getMessage()];
        }
    }

    public static function _edit(AgrmntAttachDocument $model)
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
        $exists = AgrmntAttachDocument::where([
            'agrmnt_agreement_id' => $contract_id,
            'active' => true,
            'is_acknowledgment_receipt' => true
        ])->where('id','!=',$id)->exists();


        return $exists;
    }
}
