<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryCreateRequest;

use Illuminate\Http\Request;


//models
use App\Models\Category;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $models = Category::get_pagination($request->name,$request->description, 25);
        return view('categories.index', compact('models','request'));
    }
    public function create()
    {
        return view('categories.create');
    }
    public function store(CategoryCreateRequest $request)
    {

        $model = new Category($request->all());
        $model->order = $request->order ?? 0;
        $response = Category::_create($model);
        if($response['saved']){
            flash('<i class="mdi mdi-information-outline"></i> Se ha creado la categoria del menú  <strong>' . $model->name . '</strong> de forma exitosa.')->success();
            return redirect()->route('categories.index');
        }else{
            flash('<i class="mdi mdi-information-outline"></i> Ocurrio un error al momento de crear, Error:  <strong>' . $response['error'] . '</strong>.')->error();
            return \Redirect::back();
        }
    }
    public function edit($id)
    {
        $model = Category::find($id);
        return view('categories.edit', compact('model'));
    }
    public function update(CategoryCreateRequest $request, $id)
    {

        $model = Category::find($id);
        $model->name = $request->name;
        $model->description = $request->description;
        $model->order = $request->order ?? 0;
        $response = Category::_edit($model);

        if($response['saved']){
            flash('<i class="mdi mdi-information-outline"></i> Se ha actualizado la categoria del menú  <strong>' . $model->name . '</strong> de forma exitosa.')->success();
            return redirect()->route('categories.index');
        }else{
            flash('<i class="mdi mdi-information-outline"></i> Ocurrio un error al momento de actualizar, Error:  <strong>' . $response['error'] . '</strong>.')->error();
            return \Redirect::back();
        }

    }
    public function destroy($id)
    {

        $model = Category::find($id);
        $model->active = false;
        $response = Category::_edit($model);

        if($response['saved']){
            flash('<i class="mdi mdi-information-outline"></i> Se ha desactivado la categoria del menú  <strong>' . $model->name . '</strong> de forma exitosa.')->success();
            return redirect()->route('categories.index');
        }else{
            flash('<i class="mdi mdi-information-outline"></i> Ocurrio un error al momento de desactivar, Error:  <strong>' . $response['error'] . '</strong>.')->error();
            return \Redirect::back();
        }
    }


    public function sortable(Request $request){


        foreach ($request->order as $k => $v){
            $item = Category::find($k);
            $item->order = $v;
            $item->save();
        }

    }


}
