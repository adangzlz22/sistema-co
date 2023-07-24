<?php

namespace App\Http\Controllers;

//Requests for
use App\Http\Requests\MeetingTypeCreateRequest;
use Illuminate\Http\Request;

//models for
use App\Models\MeetingType;
use App\Models\Status;

use Illuminate\Support\Facades\Auth;

class MeetingTypeController extends Controller
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
        $meeting_types = MeetingType::get_pagination($request->acronym, $request->name, 25);
        return view('meeting_types.index', compact('meeting_types','request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('meeting_types.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MeetingTypeCreateRequest $request)
    {
        $model = new MeetingType($request->all());
        
        $model->acronym = $request->acronym;
        $model->name = $request->name;
        $model->color = $request->color;
        $model->user_id = Auth::user()->id;
        $model->status_id = ($request->active === 'on' ? Status::ACTIVO : Status::INACTIVO);

        $response = MeetingType::_create($model);
        if($response['saved']){
            flash('<i class="mdi mdi-information-outline"></i> Se ha creado el tipo de organismo  <strong>' . $model->name . '</strong> de forma exitosa.')->success();
            return redirect()->route('meeting_types.index');
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
        $model = MeetingType::find($id);
        return view('meeting_types.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MeetingTypeCreateRequest $request, $id)
    {
        $model = MeetingType::find($id);
        
        $model->acronym = $request->acronym;
        $model->name = $request->name;
        $model->color = $request->color;

        $response = MeetingType::_edit($model);

        if($response['saved']){
            flash('<i class="mdi mdi-information-outline"></i> Se ha actualizado el tipo de reunión <strong>' . $model->name . '</strong> de forma exitosa.')->success();
            return redirect()->route('meeting_types.index');
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
        $model = MeetingType::find($id);
        $model->status_id = Status::INACTIVO;

        $response = MeetingType::_edit($model);

        if($response['saved']){
            flash('<i class="mdi mdi-information-outline"></i> Se ha desactivado el tipo de reunión  <strong>' . $model->name . '</strong> de forma exitosa.')->success();
            return redirect()->route('meeting_types.index');
        }else{
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
        $model = MeetingType::find($id);
        $model->status_id = Status::ACTIVO;
        $response = MeetingType::_edit($model);

        if($response['saved']){
            flash('<i class="mdi mdi-information-outline"></i> Se ha activado este tipo de reunión  <strong>' . $model->name . '</strong> de forma exitosa.')->success();
            return redirect()->route('meeting_types.index');
        } else {
            flash('<i class="mdi mdi-information-outline"></i> Ocurrio un error al momento de activar, Error:  <strong>' . $response['error'] . '</strong>.')->error();
            return \Redirect::back();
        }
    }
}
