<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\MenuCreateRequest;

//models for
use App\Models\Icon;
use App\Models\Category;
use App\Models\Permission;
use App\Models\Menu;

use App\Enums\dropdown;
use App\Enums\published;

use Illuminate\Support\Facades\Event;

use Session;

use App\Helpers\HelperApp;






class MenuController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {

        // $icons = Icon::getForCatalog();
        $category = Category::getForCatalog();
        $permission = Permission::getForCatalog();
        $dropdown = dropdown::toSelectArray();
        $published = published::toSelectArray();

        $models = Menu::get_pagination($request->name,$request->category_id,$request->icon_id, $request->permission_id,$request->dropdown,$request->published, 25);
        return view('menus.index', compact('models','category','permission','dropdown','published','request'));
    }

    public function create()
    {
        // $icons = Icon::getForCatalog();
        $category = Category::getForCatalog();
        $permission = Permission::getForCatalog();
        return view('menus.create', compact('category','permission'));
    }
    public function store(MenuCreateRequest $request)
    {
        $model = new Menu($request->all());
        $model->dropdown = $request->dropdown ? true : false;
        $model->published = $request->published ? true : false;
        $model->permission_id = !$request->dropdown ? $request->permission_id : null;
        $model->order = $request->order ? $request->order  : 0;

        $response = Menu::_create($model);
        if($request->dropdown){
            if($request->row){
                //insertamos los hijos
               foreach($request->row as $item){
                    $child = new Menu();
                    $child->name = $item['name'];
                    $child->permission_id = $item['permission_id'];
                    $child->order = $item['order'];
                    $child->published =  $item['published'] ? true : false;
                    $child->parent_id = $model->id;
                    $response = Menu::_create($child);
               }
            }
        }

        if($response['saved'] == true){
            HelperApp::EventMenuChage();
            flash('<i class="mdi mdi-information-outline"></i> Se ha creado el menu  <strong>' . $model->name . '</strong> de forma exitosa.')->success();
            return redirect()->route('menus.index');
        }else{
            flash('<i class="mdi mdi-information-outline"></i> Ocurrio un error al momento de crear, Error:  <strong>' . $response['error'] . '</strong>.')->error();
            return \Redirect::back();
        }
    }
    public function edit($id)
    {
        $model = Menu::find($id);
        $sons = Menu::Where('parent_id',$model->id)->where('active',1)->orderBy("order")->get();
        // $icons = Icon::getForCatalog();
        $category = Category::getForCatalog();
        $permission = Permission::getForCatalog();
        return view('menus.edit', compact('model','category','permission','sons'));
    }
    public function update(MenuCreateRequest $request, $id)
    {

        $model = Menu::find($id);
        $model->name = $request->name;
        $model->dropdown = $request->dropdown ? true : false;
        $model->published = $request->published ? true : false;
        $model->icon_id = $request->icon_id;
        $model->category_id = $request->category_id;
        $model->permission_id = !$request->dropdown ? $request->permission_id : null;
        $model->order = $request->order ? $request->order  : 0;
        $response = Menu::_edit($model);

        $sons = Menu::Where('parent_id',$model->id)->where('active',1)->orderBy("order")->update(['active'=>0]);
        if($request->dropdown){
            if($request->row){
                //insertamos los hijos
               foreach($request->row as $item){

                    $child = new Menu();
                    $child->name = $item['name'];
                    $child->permission_id = $item['permission_id'];
                    $child->order = $item['order'];
                    $child->published =  $item['published'] ? true : false;
                    $child->parent_id = $model->id;
                    $response = Menu::_create($child);
               }
            }
        }


        // $model->acronym = $request->acronym;
        // $model->description = $request->description;
        // $model->organisms_type_id = $request->organisms_type;

        // $response = Organism::_edit($model);

        if($response['saved'] == true){
            HelperApp::EventMenuChage();
            flash('<i class="mdi mdi-information-outline"></i> Se ha actualizado el menú  <strong>' . $model->name . '</strong> de forma exitosa.')->success();
            return redirect()->route('menus.index');
        }else{
            flash('<i class="mdi mdi-information-outline"></i> Ocurrio un error al momento de actualizar, Error:  <strong>' . $response['error'] . '</strong>.')->error();
            return \Redirect::back();
        }
    }
    public function destroy($id)
    {
        $model = Menu::find($id);
        $model->active = false;

        $response = Menu::_edit($model);
        if($response['saved'] == true){
             HelperApp::EventMenuChage();
            flash('<i class="mdi mdi-information-outline"></i> Se ha desactivado el menu  <strong>' . $model->name . '</strong> de forma exitosa.')->success();
            return redirect()->route('menus.index');
        }else{
            flash('<i class="mdi mdi-information-outline"></i> Ocurrio un error al momento de desactivar, Error:  <strong>' . $response['error'] . '</strong>.')->error();
            return \Redirect::back();
        }
    }
    //complement

    public function ajaxIcon(Request $request){
        $term = $request->term ?: '';
        // $id = $request->id ?? 0;
        $data = Icon::query()->orderBy('name', 'asc');


        if ($request->input('term') && $request->term) {
            $data = $data->where('name', 'like', '%' . $term . '%');
            $data = $data->orWhere('key', 'like', '%' . $term . '%');
        }
        $result = $data->limit(10)->get();
        // $resultb = Icon::where('id', $id)->get();
        // // dd($resultb);
        // $result = $result->merge($resultb);

        $model = $result->map(function ($item, $key) {
            return [
                'id' => $item->id,
                'text' => $this->createHTML($item),
                'html' => $this->createHTML($item),
            ];
        });
        //return Response::json($result);
        return $model->toJson();
    }

    public function ajaxIconById(Request $request){


        $id = $request->id ?: 0;
        $data =  Icon::where('id', $id)->first();

        return $data;
    }
    public function createHTML($item){
        $html = <<<HTML
                    <i class="{{ $item->key }}"></i> $item->name
                HTML;

        return $html;
    }
    public function ajaxGetMenu(){
        $menuDynamic = Menu::getMenu();
        Session::put('menu', $menuDynamic);
        return view('layout.partial.sidebar')
        ->with(compact("menuDynamic"));
    }

    public function sortable(Request $request){

        foreach ($request->order as $k => $v){
            $slide = Menu::find($k);
            $slide->order = $v;
            $slide->save();
        }

    }


}
