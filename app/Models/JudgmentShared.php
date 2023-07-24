<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JudgmentShared extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','judgment_id','active'
    ];

    protected $dates = ['created_at'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function judgment()
    {
        return $this->belongsTo(Judgment::class,'judgment_id','id');
    }

    public static function get_pagination($judgment_id,$user_id, $perPage)
    {

        $model = JudgmentShared::query()
            ->where(function($query) use ($judgment_id,$user_id) {
                if(!empty($judgment_id))
                    $query->where('judgment_id',$judgment_id);

                if(!empty($user_id)) {
                    $query->where('user_id',$user_id);
                }

                $query->where('active', 1);
            })
            ->orderBy('created_at','DESC')
            ->paginate($perPage);
        return $model;
    }

    public static function _create(JudgmentShared $model)
    {
        try{

            return ['saved' => $model->save(), 'message' => 'Los datos se guardaron exitosamente.','id' => $model->id];
        }
        catch(\Exception $e){
            return ['saved' => false, 'message' => $e->getMessage()];
        }
    }

    public static function _edit(JudgmentShared $model)
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
