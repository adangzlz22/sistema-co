<?php

namespace App\Http\Controllers;
use App\Http\Requests\IconCreateRequest;

use Illuminate\Http\Request;

//models
use App\Models\Icon;

class IconController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $models = Icon::get_pagination($request->name,$request->key, 25);
        return view('icons.index', compact('models','request'));
    }
    public function create()
    {
        return view('icons.create');
    }
    public function store(IconCreateRequest $request)
    {

        $model = new Icon($request->all());
        $response = Icon::_create($model);
        if($response['saved']){
            flash('<i class="mdi mdi-information-outline"></i> Se ha creado el icono  <strong>' . $model->name . '</strong> de forma exitosa.')->success();
            return redirect()->route('icons.index');
        }else{
            flash('<i class="mdi mdi-information-outline"></i> Ocurrio un error al momento de crear, Error:  <strong>' . $response['error'] . '</strong>.')->error();
            return \Redirect::back();
        }
    }
    public function edit($id)
    {
        $model = Icon::find($id);
        return view('icons.edit', compact('model'));
    }

    public function update(IconCreateRequest $request, $id)
    {

        $model = Icon::find($id);
        $model->name = $request->name;
        $model->key = $request->key;
        $response = Icon::_edit($model);

        if($response['saved']){
            flash('<i class="mdi mdi-information-outline"></i> Se ha actualizado el icono <strong>' . $model->name . '</strong> de forma exitosa.')->success();
            return redirect()->route('icons.index');
        }else{
            flash('<i class="mdi mdi-information-outline"></i> Ocurrio un error al momento de actualizar, Error:  <strong>' . $response['error'] . '</strong>.')->error();
            return \Redirect::back();
        }

    }
    public function destroy($id)
    {

        $model = Icon::find($id);
        $model->active = false;
        $response = Icon::_edit($model);

        if($response['saved']){
            flash('<i class="mdi mdi-information-outline"></i> Se ha desactivado el icono  <strong>' . $model->name . '</strong> de forma exitosa.')->success();
            return redirect()->route('icons.index');
        }else{
            flash('<i class="mdi mdi-information-outline"></i> Ocurrio un error al momento de desactivar, Error:  <strong>' . $response['error'] . '</strong>.')->error();
            return \Redirect::back();
        }
    }
}
