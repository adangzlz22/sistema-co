<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgrmntSigningFunctionary extends Model
{
    use HasFactory;

    protected $fillable = [
        'agrmnt_agreement_id', 'name','job','active'
    ];

    public function agreement()
    {
        return $this->belongsTo(AgrmntAgreement::class,'agrmnt_agreement_id');
    }

    public static function _create(AgrmntSigningFunctionary $model)
    {
        try{
            return ['saved' => $model->save(), 'message' => 'Los datos se guardaron exitosamente.'];
        }
        catch(\Exception $e){
            return ['saved' => false, 'message' => $e->getMessage()];
        }
    }

    public static function _edit(AgrmntSigningFunctionary $model)
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
