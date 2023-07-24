<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class Menu extends Model
{
    use HasFactory;

    protected $appends = ['url'];

    protected $fillable = [
        'name', 'category_id','icon_id','dropdown','permission_id','order','published'
    ];


    public function getUrlAttribute()
    {
        return $this->route ? route($this->route,array(), false) : '/';

    }

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class,'permission_id');
    }
    public function icon()
    {
        return $this->belongsTo(Icon::class,'icon_id');
    }
    public function sons()
    {
        return Menu::Where('parent_id',$this->id)->where('active',1)->count();
    }
    public static function getMenu()
    {
        // dd(\Auth::user()->roles);
        $roles = \Auth::user()->roles();
        $menu = collect();
        $list = Menu::Where('menus.active',1)
        ->Where('menus.published',1)
        ->leftJoin('permissions', 'menus.permission_id', '=', 'permissions.id')
        ->join('categories', 'menus.category_id', '=', 'categories.id')
        ->leftjoin('icons', 'menus.icon_id', '=', 'icons.id')
        ->orderBy('categories.order')->orderBy('menus.order','asc')
        ->select('menus.name as name','categories.name as category_name','menus.id','menus.permission_id',
        'menus.category_id','menus.icon_id','icons.key','menus.dropdown','menus.published','menus.order',
        'permissions.route as route','permissions.id as permissions_id','permissions.name as permission_name')
        ->get();

        //dd($list);

        $category_name='';
        $first_divider=true;
        foreach($list as $item){
            if(\Auth::user()->can($item->permission_name) || $item->dropdown){

                $url = $item->url;
                $icon=$item->key;
                $text = $item->name;
                $children = Menu::Where('parent_id',$item->id)
                ->leftjoin('icons', 'menus.icon_id', '=', 'icons.id')
                ->join('permissions', 'menus.permission_id', '=', 'permissions.id')
                ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                // ->WhereIn('role_has_permissions.role_id', [2])
                ->whereIn('role_has_permissions.role_id', function($query) use ($roles){
                    $query->select('id')
                    ->from($roles);
                })
                ->Where('menus.active',1)
                ->Where('published',1)
                ->select('menus.name as text','icons.key as icon','permissions.route as route','permissions.id as permissions_id')
                ->orderBy('menus.order')
                ->get()
                ->toArray();

                if(!$children){
                    if(!$item->dropdown){
                        //header and divitions
                        if($category_name !== $item->category_name){
                            if(!$first_divider){
                                $menu->push(['is_divider' => true]);
                            }
                            $first_divider=false;
                            $category_name=$item->category_name;
                            if($menu)
                                $menu->push(['text' =>$category_name,'is_header' => true ]);
                            else
                                $menu= push(['text' =>$category_name,'is_header' => true ]);
                        }
                        $menu->push(['url' =>$url,'icon' => $icon, 'text' => $text]);
                    }
                    // $menu->push(['url' =>$url,'icon' => $icon, 'text' => $text]);
                }
                else{
                    //header and divitions
                    if($category_name !== $item->category_name){
                        if(!$first_divider){
                            $menu->push(['is_divider' => true]);
                        }
                        $first_divider=false;
                        $category_name=$item->category_name;
                        if($menu)
                            $menu->push(['text' =>$category_name,'is_header' => true ]);
                        else
                            $menu= push(['text' =>$category_name,'is_header' => true ]);
                    }
                    $menu->push(['url' =>$url,'icon' => $icon, 'text' => $text,'children'=> $children]);
                }

            }

        }
         return $menu->all();
    }

    public function dropdown_label(){
    $label =  $this->dropdown ? '<span class="badge bg-success">desplegable</span>' : '<span class="badge bg-secondary">No desplegable</span>';
       return  $label;
    }
    public function published_label(){
        $label =  $this->published ? '<span class="badge bg-success">Publicado</span>' : '<span class="badge bg-secondary">No publicado</span>';
        return $label;
    }

    public static function get_pagination($name,$category_id,$icon_id,$permission_id, $dropdown,$published, $perPage)
    {

        $model = Menu::query()
        ->join('categories', 'menus.category_id', '=', 'categories.id')
            ->where(function($query) use ($name,$category_id,$icon_id,$permission_id, $dropdown,$published) {
                if(!empty($name))
                    $query->where('menus.name', 'like', '%' . $name . '%');

                if(!empty($category_id))
                    $query->where('menus.category_id',$category_id);

                if(!empty($icon_id))
                    $query->where('icon_id', $icon_id);

                if(!empty($permission_id))
                    $query->where('permission_id', $permission_id);

                if(!empty($dropdown))
                    $query->where('dropdown', $dropdown);

                if(!empty($published))
                    $query->where('published', $published);

                $query->where('menus.active', 1);
                $query->where('parent_id', null);

            })
            ->orderBy('categories.order')
            ->orderBy('menus.order','asc')
            ->select('menus.name as name','menus.id','menus.permission_id','menus.category_id','menus.icon_id','menus.dropdown','menus.published','menus.order')

            // ->select('name', 'log.date', 'log.id', 'log.date', 'log.movement', 'log.catalogue')
            ->paginate($perPage);

        return $model;
    }

    public static function _create(Menu $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }
    public static function _edit(Menu $model)
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
