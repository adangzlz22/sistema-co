<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JudgmentAttachSynthesis extends Model
{
    use HasFactory;
    protected $fillable = [
        'judgment_id', 'date_publication', 'summary','active'
    ];
    protected $dates = ['date_publication'];

    public function judgment()
    {
        return $this->belongsTo(Judgment::class,'judgment_id');
    }

    public static function _create(JudgmentAttachSynthesis $model)
    {
        try{
            return ['saved' => $model->save(), 'message' => 'Los datos se guardaron exitosamente.'];
        }
        catch(\Exception $e){
            return ['saved' => false, 'message' => $e->getMessage()];
        }
    }

    public static function _edit(JudgmentAttachSynthesis $model)
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
