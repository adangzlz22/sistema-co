<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'html_report'
    ];

    public static function get_pagination($perPage)
    {
        $model = CustomReport::query()
            ->where('active', true)
            ->paginate($perPage);
        return $model;
    }

    public static function get_by_id($id)
    {
        $model = CustomReport::where([
            ['id', '=', $id],
            ['active', '=', true]
        ])->first();
        return $model;
    }

    public static function get_by_report($rerport)
    {
        $model = CustomReport::where([
            ['report', '=', $rerport],
            ['active', '=', true]
        ])->first();
        return $model;
    }

    public static function edit(CustomReport $model)
    {
        try{
            $model->update();
            return ['saved' => true, 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e];
        }
    }
}
