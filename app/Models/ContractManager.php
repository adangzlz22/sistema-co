<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractManager extends Model
{
    use HasFactory;
    protected $fillable = [
        'contract_id', 'name','job','active'
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class,'contract_id');
    }

    public static function _create(ContractManager $model)
    {
        try{
            return ['saved' => $model->save(), 'message' => 'Los datos se guardaron exitosamente.'];
        }
        catch(\Exception $e){
            return ['saved' => false, 'message' => $e->getMessage()];
        }
    }

    public static function _edit(ContractManager $model)
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
