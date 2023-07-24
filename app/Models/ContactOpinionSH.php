<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactOpinionSH extends Model
{
    use HasFactory;
    protected $table = 'contact_opinion_shs';
    protected $fillable = [
        'contract_id', 'opinion','revision','comments','active'
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class,'contract_id');
    }

    public static function _create(ContactOpinionSH $model)
    {
        try{
            return ['saved' => $model->save(), 'message' => 'Los datos se guardaron exitosamente.'];
        }
        catch(\Exception $e){
            return ['saved' => false, 'message' => $e->getMessage()];
        }
    }

    public static function _edit(ContactOpinionSH $model)
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
