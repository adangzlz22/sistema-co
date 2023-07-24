<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgrmntInstitution extends Model
{
    use HasFactory;

    protected $fillable = [
        'agrmnt_agreement_id', 'organisms_id','active'
    ];

    public function organism()
    {
        return $this->belongsTo(Organism::class, 'organisms_id', 'id');
    }

    public function agreement()
    {
        return $this->belongsTo(AgrmntAgreement::class,'agrmnt_agreement_id');
    }

    public static function _create(AgrmntInstitution $model)
    {
        try{
            return ['saved' => $model->save(), 'message' => 'Los datos se guardaron exitosamente.'];
        }
        catch(\Exception $e){
            return ['saved' => false, 'message' => $e->getMessage()];
        }
    }

    public static function _edit(AgrmntInstitution $model)
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
