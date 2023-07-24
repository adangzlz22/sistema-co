<?php

namespace App\Http\Controllers;

//Requests for
use App\Http\Requests\PlaceCreateRequest;
use Illuminate\Http\Request;

//models for
use App\Models\Place;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;

class PlacesController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $arrPlaces = Place::get_pagination($request->name, $request->address, 25);
        return view('places.index', compact('arrPlaces', 'request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('places.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PlaceCreateRequest $request)
    {
        $model = new place();
        
        $model->name = $request->name;
        $model->address = $request->address;
        $model->status_id = ($request->active === 'on'? Status::ACTIVO : Status::INACTIVO);

        $response = Place::_create($model);
        if($response['saved']){
            flash('<i class="mdi mdi-information-outline"></i> Se ha creado lugar para una reunión <strong>' . $model->name . ', ' . $request->address . '</strong> de forma exitosa.')->success();
            return redirect()->route('places.index');
        } else {
            flash('<i class="mdi mdi-information-outline"></i> Ocurrio un error al momento de crear, Error:  <strong>' . $response['error'] . '</strong>.')->error();
            return \Redirect::back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = Place::find($id);
        return view('places.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PlaceCreateRequest $request, $id)
    {
        $model = Place::find($id);
        
        $model->name = $request->name;
        $model->address = $request->address;

        $response = Place::_edit($model);

        if($response['saved']){
            flash('<i class="mdi mdi-information-outline"></i> Se ha actualizado el lugar de reunión <strong>' . $model->name . '</strong> de forma exitosa.')->success();
            return redirect()->route('places.index');
        } else {
            flash('<i class="mdi mdi-information-outline"></i> Ocurrio un error al momento de actualizar, Error:  <strong>' . $response['error'] . '</strong>.')->error();
            return \Redirect::back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = Place::find($id);
        $model->status_id = Status::INACTIVO;
        $response = Place::_edit($model);

        if($response['saved']){
            flash('<i class="mdi mdi-information-outline"></i> Se ha desactivado este lugar de reunión  <strong>' . $model->name . '</strong> de forma exitosa.')->success();
            return redirect()->route('places.index');
        } else {
            flash('<i class="mdi mdi-information-outline"></i> Ocurrio un error al momento de desactivar, Error:  <strong>' . $response['error'] . '</strong>.')->error();
            return \Redirect::back();
        }
    }

    /**
     * Active the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function active($id)
    {
        $model = Place::find($id);
        $model->status_id = Status::ACTIVO;
        $response = Place::_edit($model);

        if($response['saved']){
            flash('<i class="mdi mdi-information-outline"></i> Se ha activado este lugar de reunión  <strong>' . $model->name . '</strong> de forma exitosa.')->success();
            return redirect()->route('places.index');
        } else {
            flash('<i class="mdi mdi-information-outline"></i> Ocurrio un error al momento de activar, Error:  <strong>' . $response['error'] . '</strong>.')->error();
            return \Redirect::back();
        }
    }
}