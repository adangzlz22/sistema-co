<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Log extends Model
{
    use HasFactory;

    public static function create(Log $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function get_pagination($user, $init_date, $end_date, $perPage)
    {

        $model = DB::table('logs AS log')
            ->where(function($query) use ($user, $init_date, $end_date) {

                if(!empty($user))
                    $query->where('user.name', 'like', '%' . $user . '%');

                if(!empty($init_date)) {
                    $init_date = DateTime::createFromFormat('d/m/Y', $init_date);
                    $query->whereDate('log.date', '>=', $init_date->format('Y-m-d'));
                }

                if(!empty($end_date)) {
                    $end_date = DateTime::createFromFormat('d/m/Y', $end_date);
                    $query->whereDate('log.date', '<=', $end_date->format('Y-m-d'));
                }
            })
            ->join('users AS user', 'log.user_id', '=', 'user.id')
            ->orderBy('log.id', 'desc')
            ->select('user.name', 'log.date', 'log.id', 'log.date', 'log.movement', 'log.catalogue')
            ->paginate($perPage);

        return $model;
    }
}
