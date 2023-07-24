<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ContractShared extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','contract_id','active'
    ];

    protected $dates = ['created_at'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function conctract()
    {
        return $this->belongsTo(Contract::class,'contract_id','id');
    }

    public static function get_pagination($contract_id,$user_id, $perPage)
    {

        $model = ContractShared::query()
            ->where(function($query) use ($contract_id,$user_id) {
                if(!empty($contract_id))
                    $query->where('contract_id',$contract_id);

                if(!empty($user_id)) {
                    $query->where('user_id',$user_id);
                }

                $query->where('active', 1);
            })
            ->orderBy('created_at','DESC')
            ->paginate($perPage);
        return $model;
    }

    public static function _create(ContractShared $model)
    {
        try{

            return ['saved' => $model->save(), 'message' => 'Los datos se guardaron exitosamente.','id' => $model->id];
        }
        catch(\Exception $e){
            return ['saved' => false, 'message' => $e->getMessage()];
        }
    }

    public static function _edit(ContractShared $model)
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
