<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Enums
use App\Enums\log_movements;
use App\Enums\system_catalogues;

use App\Helpers\HelperApp;


class EntitiesMeetingTypes extends Model
{
    use HasFactory;

    protected $table = 'entities_meeting_types';

    protected $fillable = [
        'entity_id','meeting_type_id'
    ];

    public static function _create(EntitiesMeetingTypes $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function _edit(EntitiesMeetingTypes $model)
    {
        try{
            $model->update();
            return ['saved' => true, 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e];
        }
    }

    public static function _delete($id)
    {
        try{
           
            self::where('entity_id', $id)->delete();
            return ['saved' => true, 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e];
        }
    }
}

