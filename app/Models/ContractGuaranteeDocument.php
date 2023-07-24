<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractGuaranteeDocument extends Model
{
    use HasFactory;
    protected $fillable = [
        'guarantee_id', 'name','file','active'
    ];

    public function guarantee()
    {
        return $this->belongsTo(ContractGuarantee::class,'guarantee_id','id');
    }

    public static function _create(ContractGuaranteeDocument $model)
    {
        try{
            return ['saved' => $model->save(), 'message' => 'Los datos se guardaron exitosamente.'];
        }
        catch(\Exception $e){
            return ['saved' => false, 'message' => $e->getMessage()];
        }
    }

    public static function _edit(ContractGuaranteeDocument $model)
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
