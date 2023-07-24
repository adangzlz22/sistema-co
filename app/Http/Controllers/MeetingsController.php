<?php

namespace App\Http\Controllers;

//Requests for

use App\Helpers\HelperApp;
use App\Http\Requests\MeetingCreateRequest;
use App\Http\Requests\SubjectCreateRequest;
use App\Http\Requests\AgreementsCreateRequest;
use App\Jobs\SendEmailMeetingCreateJob;
use App\Listeners\SendMeetingNotification;
use App\Mail\EmailErrorNotification;
use App\Mail\MeetingCancelNotification;
use App\Mail\MeetingCreateNotification;
use Illuminate\Http\Request;

//models for
use App\Models\Meeting;
use App\Models\MeetingType;
use App\Models\Modality;
use App\Models\Status;
use App\Models\Entity;
use App\Models\Place;
use App\Models\Subject;
use App\Models\Agreement;
use App\Models\AgreementEntity;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\File;

class MeetingsController extends Controller
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
        $arrMeetingTypes = MeetingType::getForCatalog();
        $arrStatus = Status::getForCatalogByIds([Status::CELEBRADA,Status::EN_PROCESO,Status::POR_CELEBRAR]);
        $arrMeetings = Meeting::get_pagination($request->folio, $request->meeting_type, $request->init_date, $request->end_date, $request->status_id, 25);
        return view('meetings.index', compact('arrMeetings', 'arrMeetingTypes', 'arrStatus', 'request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    { 
        $arrMeetingTypes = MeetingType::getForCatalog();
        $arrModalities = Modality::getForCatalog();
        $arrPlaces = Place::getForCatalogCresteMeeting();
        return view('meetings.create', compact('arrMeetingTypes', 'arrModalities', 'arrPlaces'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MeetingCreateRequest $request)
    {
        $model = new Meeting($request->all());
        
        $model->folio = $this->create_folio();
        $model->meeting_type_id = $request->meeting_type;
        $model->meeting_date = $request->date;
        $model->meeting_time = $request->time_start;
        $model->meeting_time_end = $request->time_end;
        $model->modality_id = $request->modality;
        $model->place_id = $request->place;
        $model->link = $request->link;
        //$model->user_id = Auth::user()->id;
        $model->status_id = Status::POR_CELEBRAR;

        $response = Meeting::_create($model);

        if($response['saved']){
            if ($request->hasfile('files')) {
                File::storeFiles($request->file('files'), File::MOD_MEETINGS, $response['id'], null);
            }

            try {
                $modelo = Meeting::with('meeting_type.entities','place','status','modality')->where('id',$response['id'])->first();
                $entities = $modelo->meeting_type->entities;
                $su = User::whereHas('roles', function ($query) {
                    $query->where('name', HelperApp::$roleSuperUsuario);
                })->get()->pluck('email');
                $err = [];
                foreach ($entities as $key => $value) {
                    try {
                        $cc = User::where('entity_id',$value->id)->where('email','<>',$value->email)->get()->pluck('email');
                        // dispatch(new SendEmailMeetingCreateJob($value,$cc,$modelo))->onQueue('quemails');
                        Mail::to($value->email)->cc($cc)->queue(new MeetingCreateNotification($value,$modelo));
                    } catch (\Throwable $th) {
                        array_push($err,$value);
                    }
                }
                if(sizeof($err)>0){
                    Mail::to($su)->queue(new EmailErrorNotification("Creación",$err,$modelo));
                }
                (new SendMeetingNotification())->handle($modelo,3);
            } catch (\Throwable $th) {
                \Log::info($th->getMessage());
            }

           // flash('<i class="mdi mdi-information-outline"></i> Se ha creado una reunion con el folio: <strong>' . $model->folio . '</strong>')->success();
           // return redirect()->route('meetings.subject', $response['id']);

           return response()->json(['saved' => true, 'meeting_id' => $response['id'], 'folio' => $model->folio, 'message' => 'Se ha creado una reunión con el folio: <strong>' . $model->folio . '</strong>']);
        } else {
            //flash('<i class="mdi mdi-information-outline"></i> Ocurrio un error al momento de crear, Error:  <strong>' . $response['error'] . '</strong>.')->error();
           // return \Redirect::back();
           return response()->json(['saved' => false, 'message' => 'Ocurrió un error al momento de crear la reunión']);
        }
    }

    private function create_folio()
    {	
        $previous_folio = Meeting::getPreviousFolio();
        $current_year    = date("y");//dos digitos 18000001
        
        if($previous_folio > 0){
            $previous_year  = substr($previous_folio, 0, 2); 
            $consecutive    = substr($previous_folio, 2, 6); 
            
            if($previous_year == $current_year){
                $current_folio = intval($previous_folio)+1;
            }else{
                $current_folio = $current_year.$consecutive;
            }		 
        } else {
            $current_folio = $current_year.'000001';
        }
        
        return $current_folio;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function subject(Request $request, $id)
    {
        $meeting_id = $id;
        $model = Meeting::getForMeetingId($meeting_id);
        $arrSubjects = Subject::get_pagination($meeting_id, $request->subject, $request->expositor, $request->entity_id, 10);
        $arrEntities = Entity::getForCatalog($model[0]??null);
        $arrStatus = Status::getForCatalogByIds(Status::ACTIONS);
        return view('meetings.subject', compact('model', 'arrEntities', 'arrSubjects', 'arrStatus', 'meeting_id', 'request'));
    }

    public function subject_store(SubjectCreateRequest $request)
    {
        DB::beginTransaction();
        try {
            $model = new Subject($request->all());
            
            $model->subject = $request->subject;
            $model->expositor = $request->expositor;
            $model->entity_id = $request->entity_id;
            $model->meeting_id = $request->meeting_id;
            $model->observation = $request->observation;
            $model->user_id = Auth::user()->id;
            $model->status_id = Status::ACTIVO;

            $response = Subject::_create($model);
            if($response['saved']) {

                if ($request->hasfile('files')) {
                    File::storeFiles($request->file('files'), File::MOD_SUBJECTS, $response['id'], null);
                }

                DB::commit();
                flash('<i class="mdi mdi-information-outline"></i> Se ha creado un nuevo asunto: <strong>' . $model->subject . '</strong>')->success();
                return redirect()->route('meetings.subject', $request->meeting_id);
            } else {
                DB::rollback();
                flash('<i class="mdi mdi-information-outline"></i> Ocurrio un error al momento de crear, Error(1200):  <strong>' . $response['error'] . '</strong>.')->error();
                return \Redirect::back();
            }
        } catch (\Throwable $th) {
            DB::rollback();
            flash('<i class="mdi mdi-information-outline"></i> Ocurrio un error al momento de crear, Error(1201):  <strong>' . $th->getMessage()."-".$th->getCode() . '</strong>.')->error();
            return \Redirect::back();
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit_subject($id)
    {
        $subject_id = $id;
        $model = Subject::getForSubjectId($subject_id);
        $arrEntities = Entity::getForCatalog($model[0]??null);
        return view('meetings.edit_subject', compact('model', 'arrEntities'));
    }

    public function update_subject(SubjectCreateRequest $request, $id) {
        $model = Subject::find($id);
        
        $model->subject = $request->subject;
        $model->expositor = $request->expositor;
        $model->entity_id = $request->entity_id;
        $model->observation = $request->observation;

        $response = Subject::_edit($model);

        if($response['saved']){
            flash('<i class="mdi mdi-information-outline"></i> Se ha actualizado el asuinto <strong>' . $model->subject . '</strong> de forma exitosa.')->success();
            return redirect()->route('meetings.subject', $request->meeting_id);
        } else {
            flash('<i class="mdi mdi-information-outline"></i> Ocurrio un error al momento de actualizar, Error:  <strong>' . $response['error'] . '</strong>.')->error();
            return \Redirect::back();
        }
    }

    public function delete_subject($id) {
        $model = Subject::find($id);
        $model->status_id = Status::INACTIVO;
        $response = Subject::_edit($model);

        if($response['saved']){
            flash('<i class="mdi mdi-information-outline"></i> Se ha eliminado este asunto de forma exitosa.')->success();
            return redirect()->route('meetings.subject', $model->meeting_id);
        } else {
            flash('<i class="mdi mdi-information-outline"></i> Error: <strong>1206</strong> Ocurrio un error al momento de eliminar.')->error();
            return \Redirect::back();
        }
    }

    public function delete_agreement($id) {
        $model = Agreement::find($id);
        $model->status_id = Status::INACTIVO;
        $response = Agreement::_edit($model);

        if($response['saved']){
            flash('<i class="mdi mdi-information-outline"></i> Se ha eliminado este acuerdo de forma exitosa.')->success();
            return redirect()->route('meetings.agreement', $model->meeting_id);
        } else {
            flash('<i class="mdi mdi-information-outline"></i> Error: <strong>1205</strong> Ocurrio un error al momento de eliminar.')->error();
            return \Redirect::back();
        }
    }

    public function subject_files(Request $request) {
        if ($request->hasfile('files')) {
            File::storeFiles($request->file('files'), File::MOD_SUBJECTS, $request->subject_id, null);
        }

        flash('<i class="mdi mdi-information-outline"></i> Se han adjuntado archivos correctamente')->success();
        return redirect()->route('meetings.subject', $request->meeting_id);
    }

    public function agreement(Request $request, $id)
    {
        $meeting_id = $id;
        $model = Meeting::getForMeetingId($meeting_id);
        $arrAgreements = Agreement::get_pagination($request->agreement, null, $meeting_id, $request->entity_id, null, null, $request->status_id, 5);
        $arrEntitiesType = Entity::getForCatalogByEntitiesTypes($model[0]??null);
        $arrEntities = Entity::getForCatalog($model[0]??null);
        $arrStatus = Status::getForCatalogByIds(Status::ACTIONS);

        $arrEntitiesDep = array_filter(($arrEntitiesType), function($v) {
            return $v['entities_types_id'] == 1;
        });

        $arrEntitiesEnt = array_filter(($arrEntitiesType), function($v) {
            return $v['entities_types_id'] == 2;
        });

        $arrEntitiesFid = array_filter(($arrEntitiesType), function($v) {
            return $v['entities_types_id'] == 3;
        });

        $arrEntitiesOrg = array_filter(($arrEntitiesType), function($v) {
            return $v['entities_types_id'] == 4;
        });

        return view('meetings.agreement', compact('model', 'arrStatus', 'arrAgreements', 'arrEntities', 'arrEntitiesDep', 'arrEntitiesEnt', 'arrEntitiesFid', 'arrEntitiesOrg', 'meeting_id', 'request'));
    }

    public function agreement_store(AgreementsCreateRequest $request)
    {
        $model = new Agreement($request->all());
        
        $model->agreement = $request->agreement;
        $model->meeting_id = $request->meeting_id;
        $model->user_id = Auth::user()->id;
        $model->status_id = Status::SIN_AVANCE;
        $response = Agreement::_create($model);

        if($response['saved']){
            $this->store_entitiesByAgreement($model->id, $request->entity_id);
            flash('<i class="mdi mdi-information-outline"></i> Se ha creado un nuevo acuerdo: <strong>' . $model->agreement . '</strong>')->success();
            return redirect()->route('meetings.agreement', $request->meeting_id);
        } else {
            flash('<i class="mdi mdi-information-outline"></i> Ocurrio un error al momento de crear, Error:  <strong>' . $response['error'] . '</strong>.')->error();
            return \Redirect::back();
        }
    }

    private function store_entitiesByAgreement($id, $arrEntities) {
        foreach ($arrEntities as $key => $name) {
            $model = new AgreementEntity();

            $model->entity_id =  $name;
            $model->agreement_id = $id;
            $model->user_id = Auth::user()->id;

            AgreementEntity::_create($model);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit_agreement($id)
    {
        $agreement_id = $id;
        $model = Agreement::getForAgreementId($agreement_id);

        $arrEntitiesType = Entity::getForCatalogByEntitiesTypes($model[0]->meeting??null);
        $arrEntitiesDep = array_filter(($arrEntitiesType), function($v) {
            return $v['entities_types_id'] == 1;
        });

        $arrEntitiesEnt = array_filter(($arrEntitiesType), function($v) {
            return $v['entities_types_id'] == 2;
        });

        $arrEntitiesFid = array_filter(($arrEntitiesType), function($v) {
            return $v['entities_types_id'] == 3;
        });

        $arrEntitiesOrg = array_filter(($arrEntitiesType), function($v) {
            return $v['entities_types_id'] == 4;
        });

        $arrSelectedEntities = array();
        foreach($model[0]->entities as $key) {
            array_push($arrSelectedEntities, $key->id);
        }

        return view('meetings.edit_agreement', compact('model',  'arrEntitiesDep', 'arrEntitiesEnt', 'arrEntitiesFid', 'arrEntitiesOrg', 'arrSelectedEntities'));
    }

    public function update_agreement(Request $request, $id) {
        $model = Agreement::find($id);
        $model->agreement = $request->agreement;
        $response = Agreement::_edit($model);

        if($response['saved']){
            $this->update_EntitiesForAgreement($id, $model->entities, $request->entity_id);

            flash('<i class="mdi mdi-information-outline"></i> Se ha actualizado el acuerdo <strong>' . $model->agreement . '</strong> de forma exitosa.')->success();
            return redirect()->route('meetings.agreement', $request->meeting_id);
        } else {
            flash('<i class="mdi mdi-information-outline"></i> Ocurrio un error al momento de actualizar, Error:  <strong>' . $response['error'] . '</strong>.')->error();
            return \Redirect::back();
        }
    }

    private function update_EntitiesForAgreement($id, $modelEntities, $requestEntity_id) {
        if(count($modelEntities) > 0) 
            AgreementEntity::_delete($id);
        
        if(count($requestEntity_id) > 0) 
            foreach ($requestEntity_id as $key => $name) {
                $model = new AgreementEntity();

                $model->entity_id =  $name;
                $model->agreement_id = $id;
                $model->user_id = Auth::user()->id;

                AgreementEntity::_create($model);
            }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = Meeting::find($id);
        $arrMeetingTypes = MeetingType::getForCatalog();
        $arrModalities = Modality::getForCatalog();
        $arrPlaces = Place::getForCatalog();
        return view('meetings.edit', compact('model', 'arrMeetingTypes', 'arrModalities', 'arrPlaces'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MeetingCreateRequest $request, $id)
    {
        $model = Meeting::find($id);
        
        $model->meeting_type_id = $request->meeting_type;
        $model->meeting_date = $request->date;
        $model->meeting_time = $request->time_start;
        $model->meeting_time_end = $request->time_end;
        $model->modality_id = $request->modality;
        $model->place_id = $request->place;
        $model->link = $request->link;

        $response = Meeting::_edit($model);

        if($response['saved']){
            flash('<i class="mdi mdi-information-outline"></i> Se ha actualizado la información de la reunión <strong>' . $model->folio . '</strong> de forma exitosa.')->success();
            return redirect()->route('meetings.index', );
        } else {
            flash('<i class="mdi mdi-information-outline"></i> <strong>Error 1204:</strong> Ocurrió un error al momento de actualizar.')->error();
            return \Redirect::back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancel($id)
    {
        $model = Meeting::find($id);
        if($model->status_id == Status::POR_CELEBRAR) {
            $model->status_id = Status::CANCELADA;
            $response = Meeting::_edit($model);

            if($response['saved']){
                try {
                    $modelo = $model;
                    $entities = $modelo->meeting_type->entities;
                    $su = User::whereHas('roles', function ($query) {
                        $query->where('name', HelperApp::$roleSuperUsuario);
                    })->get()->pluck('email');
                    $cc = User::whereIn('entity_id',$entities->pluck('id'))->whereNotIn('email',$entities->pluck('email'))->get()->pluck('email');
                    Mail::to($entities)->cc($cc)->queue(new MeetingCancelNotification($modelo));

                    (new SendMeetingNotification())->handle($modelo,4);
                } catch (\Throwable $th) {
                    Mail::to($su)->queue(new EmailErrorNotification("Cancelación",$entities,$modelo));
                }
                flash('<i class="mdi mdi-information-outline"></i> Se ha cancelado esta reunion  <strong>FOLIO: ' . $model->folio . '</strong> de forma exitosa.')->success();
                return redirect()->route('meetings.index');
            } else {
                flash('<i class="mdi mdi-information-outline"></i> <strong>Error: 1202</strong> Ocurrio un error al momento de cancelar esta reunion.')->error();
                return \Redirect::back();
            }
        } else {
            flash('<i class="mdi mdi-information-outline"></i> <strong>Error: 1203</strong> No es posible cancelar esta reunion')->error();
            return \Redirect::back();
        }
            
    }

    public function ajaxGetForCatalogByTypeId(Request $request){
        $data = Meeting::getForCatalogByTypeId($request->id);
        return $data;
    }
}
