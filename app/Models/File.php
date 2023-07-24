<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Enums
use App\Enums\log_movements;
use App\Enums\system_catalogues;

use App\Helpers\HelperApp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory;
    static $system_catalogue = system_catalogues::Files;

    protected $table = 'files';
    
    const MOD_MEETINGS = 1;
    const MOD_SUBJECTS = 2;
    const MOD_AGREEMENTS = 3;
    const MOD_REPLIES = 4;
    const MOD_ACTIONS = 5;
    const SPATH = "app/public/attachments/";

    protected $fillable = [
        'o_name','name','ext','path','url','modulo','parent_id','file_category_id','user_id','status_id',
    ];

    public static function get_pagination($modulo, $parent_id,$file_category_id, $perPage)
    {

        $model = self::query()
        ->where('modulo',$modulo)
        ->where('parent_id',$parent_id)
        ->where(function($query) use ($file_category_id) {
            if(!empty($file_category_id))
                $query->where('file_category_id', $file_category_id);
            $query->where('status_id', Status::ACTIVO);
        })
        ->orderBy('id','ASC')
        ->paginate($perPage);

        return $model;
    }

    public static function storeFiles($files,$modulo,$parent_id,$file_category_id)
    {
            foreach($files as $key => $file)
            {
                $extension = $file->getClientOriginalExtension();
                $o_name = $file->getClientOriginalName();
            // if ($extension == 'png' || $extension == 'jpeg' || $extension == 'jpg') {
                $nombre = $modulo."_".$parent_id."_".(time().rand(1,999)).".".$extension;
                $path = $file->move(storage_path(self::SPATH), $nombre);

                $insert[$key]['ext'] = $extension;
                $insert[$key]['modulo'] = $modulo;
                $insert[$key]['parent_id'] = $parent_id;
                $insert[$key]['name'] = $nombre;
                $insert[$key]['o_name'] = $o_name;
                $insert[$key]['path'] = $path;
                $insert[$key]['url'] = Storage::url("attachments/".$nombre);
                $insert[$key]['file_category_id'] = $file_category_id??1;
                $insert[$key]['user_id'] = Auth::user()->id;
                $insert[$key]['created_at'] = Carbon::now();
            // }
    
            }
            File::insert($insert);
    }
    public static function _create(File $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function _edit(File $model)
    {
        try{
            $model->update();
            return ['saved' => true, 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e];
        }
    }

    public static function boot() {

        parent::boot();
        static::created(function($model) {
            $json_model = $model->toJson();
            HelperApp::save_log($model->id, log_movements::NewRegister, self::$system_catalogue, null, $json_model);

        });
        static::updated(function ($model) {
            $modelOld = $model->getOriginal();
            $change =  array_merge($modelOld, $model->getChanges());
            $json_old = json_encode($modelOld);
            $json_new = json_encode($change);
            HelperApp::save_log($model->id, log_movements::Edit, self::$system_catalogue, $json_old, $json_new);

        });

    }

    public function category()
    {
        return $this->belongsTo(FileCategory::class, 'file_category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


}

