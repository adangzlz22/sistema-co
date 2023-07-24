<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class Permission extends \Spatie\Permission\Models\Permission
{
    use HasFactory;

    protected $appends = ['url'];

    protected $fillable = [
        'name',
        'group_permission_id',
        'route'

    ];

    public static function getForCatalog()
    {
        $model = Permission::pluck('name', 'id')->sortBy("name");
        return $model;
    }
    public function getUrlAttribute()
    {
        return $this->route ? route($this->route) : '#';

    }
    public function group_permission(){
        return $this->belongsTo(GroupPermission::class);
    }
}
