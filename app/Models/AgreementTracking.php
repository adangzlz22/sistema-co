<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgreementTracking extends Model
{
    use HasFactory;

    protected $fillable = [
        'comments', 'agreement_id'
    ];

    public static function create(AgreementTracking $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(AgreementTracking $model)
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
        $model = AgreementTracking::where([
            ['id', '=', $id],
            ['active', '=', true]
        ])->first();
        return $model;
    }
}
