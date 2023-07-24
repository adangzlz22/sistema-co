<?php

namespace App\Models;

use App\Helpers\HelperApp;
use App\Notifications\CustomResetPassword;
use App\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $appends = ['user_with_photo'];
    protected $fillable = [
        'name', 'is_signer', 'email', 'password', 'avatar', 'entity_id', 'job', 'phone', 'created_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getUserWithPhotoAttribute(){
        $name = $this->name ?? '';
        $photo = asset('/images/avatars/'.$this->avatar);
        return "<strong class='d-block text-primary'><img style='width: 2rem;' class='rounded-circle me-2' src='$photo'>$name</strong>";
    }
    public static function getForCatalogForTurn($user_id)
    {
        $model = User::query()
            ->where(function($query) use ($user_id) {
                if(!empty($user_id) && !Auth::user()->hasRole('Super Usuario'))
                    $query->where('created_by',$user_id);

                $query->whereHas(
                    'roles', function($q){
                    $q->where('name', HelperApp::$roleRevisorConsultivo);
                });
                //$query->where('active', 1);
            })->pluck('name', 'id')->sortBy("name")->take(10);

        return $model;
    }

    public static function getForCatalogForTurnJuicio($user_id)
    {
        $model = User::query()->with('roles')
            ->where(function($query) use ($user_id) {
               if(!empty($user_id) && !Auth::user()->hasRole('Super Usuario'))
                    $query->where('created_by',$user_id);

                $query->whereHas(
                    'roles', function($q){
                    $q->where('name', HelperApp::$roleRevisorConsultivoJuicios);
                });
                //$query->where('active', 1);
            })->pluck('name', 'id')->sortBy("name")->take(10);

        return $model;
    }

    public static function get_by_id($id)
    {
        $model = User::where([
            ['id', '=', $id]
        ])->first();
        return $model;
    }

    public function entity()
    {
        return $this->belongsTo(Entity::class, 'entity_id');
    }

    public function role_exist($role_id)
    {
        return $this->roles()->where('id', $role_id)->exists();
    }

    public function roles_exist($roles)
    {
        return $this->roles()->WhereIn('id', $roles)->exists();
    }


    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token));
    }
    public static function getForCatalog()
    {
        $model = User::where([
            "active" => true,
            'is_signer' => true
        ])->orderBy("name")->pluck('name', 'id');
        return $model;
    }

}
