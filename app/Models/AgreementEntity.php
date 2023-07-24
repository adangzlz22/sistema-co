<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Enums
use App\Enums\log_movements;
use App\Enums\system_catalogues;

use App\Helpers\HelperApp;


class AgreementEntity extends Model
{
    use HasFactory;

    protected $table = 'agreements_entities';

    protected $fillable = [
        'agreement_id','entity_id','user_id'
    ];

    public static function _create(AgreementEntity $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function _delete($id)
    {
        try{
           
            self::where('agreement_id', $id)->delete();
            return ['saved' => true, 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e];
        }
    }
}

