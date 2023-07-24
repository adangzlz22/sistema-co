<?php

namespace App\Http\Controllers;

//Requests for
use App\Http\Requests\EntitiesCreateRequest;
use App\Http\Requests\EntitiesEditRequest;
use Illuminate\Http\Request;

//models for
use App\Models\Entity;
use App\Models\EntitiesType;
use App\Models\MeetingType;
use App\Models\Status;
use App\Models\EntitiesMeetingTypes;

use Illuminate\Support\Facades\Auth;

class EntitiesController extends Controller
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
        $arrEntitiesTypes = EntitiesType::getForCatalog();
        $arrMeetingTypes = MeetingType::getForCatalog();
        $arrEntities = Entity::get_pagination($request->acronym, $request->name, $request->entities_type, $request->meeting_type, 25);
        return view('entities.index', compact('arrEntities', 'arrEntitiesTypes', 'arrMeetingTypes', 'request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $arrEntitiesTypes = EntitiesType::getForCatalog();
        $arrMeetingTypes = MeetingType::getForCatalog();
        return view('entities.create', compact('arrEntitiesTypes', 'arrMeetingTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EntitiesCreateRequest $request)
    {
        $model = new Entity($request->all());
        
        $model->name = $request->name;
        $model->acronym = $request->acronym;
        $model->email = $request->email;
        $model->job = $request->job;
        $model->holder = $request->holder;
        
        $model->entities_types_id = $request->entities_type;
        $model->user_id = Auth::user()->id;
        $model->status_id = ($request->active === 'on'? Status::ACTIVO : Status::INACTIVO);

        $response = Entity::_create($model);
        if($response['saved']){
            $this->store_EntitiesMeetingTypes($model->id, $request->meeting_type);
            flash('<i class="mdi mdi-information-outline"></i> Se ha creado el tipo de organismo  <strong>' . $model->name . '</strong> de forma exitosa.')->success();
            return redirect()->route('entities.index');
        } else {
            flash('<i class="mdi mdi-information-outline"></i> Ocurrio un error al momento de crear, Error:  <strong>' . $response['error'] . '</strong>.')->error();
            return \Redirect::back();
        }
    }

    private function store_EntitiesMeetingTypes($id, $arrMeetingType) {
        foreach ($arrMeetingType as $key => $name) {
            $model = new EntitiesMeetingTypes();

            $model->entity_id = $id;
            $model->meeting_type_id = $name;
            
            EntitiesMeetingTypes::_create($model);
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
        $model = Entity::find($id);
        $arrEntitiesTypes = EntitiesType::getForCatalog();
        $arrMeetingTypes = MeetingType::getForCatalog();
        return view('entities.edit', compact('model', 'arrEntitiesTypes','arrMeetingTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EntitiesEditRequest $request, $id)
    {
        $model = Entity::find($id);

        $model->name = $request->name;
        $model->acronym = $request->acronym;
        $model->email = $request->email;
        $model->job = $request->job;
        $model->holder = $request->holder;
        $model->entities_types_id = $request->entities_type;

        $response = Entity::_edit($model);

        if($response['saved']){
            $this->update_EntitiesMeetingTypes($id, $model->meeting_types, $request->meeting_type);
            flash('<i class="mdi mdi-information-outline"></i> Se ha actualizado la institución <strong>' . $model->name . '</strong> de forma exitosa.')->success();
            return redirect()->route('entities.index');
        } else {
            flash('<i class="mdi mdi-information-outline"></i> Ocurrio un error al momento de actualizar, Error:  <strong>' . $response['error'] . '</strong>.')->error();
            return \Redirect::back();
        }
    }

    private function update_EntitiesMeetingTypes($id, $modelMeetingType, $requestMeetingType) {
        if(count($modelMeetingType) > 0) 
            EntitiesMeetingTypes::_delete($id);
        
        if(count($requestMeetingType) > 0) 
            foreach ($requestMeetingType as $key => $name) {
                $model = new EntitiesMeetingTypes();

                $model->entity_id = $id;
                $model->meeting_type_id =  $name;
                
                EntitiesMeetingTypes::_create($model);
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
        $model = Entity::find($id);
        $model->status_id = Status::INACTIVO;
        $response = Entity::_edit($model);

        if($response['saved']){
            flash('<i class="mdi mdi-information-outline"></i> Se ha desactivado esta entidad  <strong>' . $model->name . '</strong> de forma exitosa.')->success();
            return redirect()->route('entities.index');
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
        $model = Entity::find($id);
        $model->status_id = Status::ACTIVO;
        $response = Entity::_edit($model);

        if($response['saved']){
            flash('<i class="mdi mdi-information-outline"></i> Se ha activado esta entidad <strong>' . $model->name . '</strong> de forma exitosa.')->success();
            return redirect()->route('entities.index');
        } else {
            flash('<i class="mdi mdi-information-outline"></i> Ocurrio un error al momento de desactivar, Error:  <strong>' . $response['error'] . '</strong>.')->error();
            return \Redirect::back();
        }
    }

    public function ajaxGetForCatalog(Request $request){
        $data = Entity::getForCatalog($request);
        return $data;
    }
}
