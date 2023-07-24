<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupPermission extends Model
{
    use HasFactory;

    public function permissions(){
        return $this->hasMany(Permission::class);
    }


    public static function selectList()
    {
        $model = self::orderBy("name")->pluck('name', 'id');
        return $model;
    }
}
