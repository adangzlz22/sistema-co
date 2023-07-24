<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Enums\log_movements;
use App\Enums\system_catalogues;
use App\Helpers\HelperApp;
use App\Http\Requests\ActionCreateRequest;
use App\Http\Requests\ReplyActionCreateRequest;
use App\Http\Requests\ReplyCreateRequest;
use App\Models\Action;
use App\Models\Entity;
use App\Models\File;
use App\Models\Meeting;
use App\Models\MeetingType;
use App\Models\Reply;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AgreementsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {

        $meeting_types = MeetingType::getForCatalog();
        $entities = Entity::getForCatalog();
        $status = Status::getForCatalogByIds([Status::SIN_AVANCE,Status::EN_PROCESO,Status::CONCLUIDO,]);

        $agreement = $request->agreement ?? '';
        $meeting_type_id = $request->meeting_type_id ?? '';
        $meeting_id = $request->meeting_id ?? '';
        $entity_id = $request->entity_id ?? '';
        $status_id = $request->status_id ?? '';
        $start_date = $request->start_date ?? '';
        $end_date = $request->end_date ?? '';

        // $meetings = Meeting::getForCatalogByTypeId($meeting_type_id);

        $perPage = request('iPerPage') ?? 10;
        $agreements = Agreement::get_pagination($agreement,$meeting_type_id,$meeting_id,$entity_id,$start_date,$end_date,$status_id,$perPage);

        return view('agreements.index')
            ->with(compact("agreements"))
            ->with(compact("request"))
            ->with(compact("meeting_types"))
            ->with(compact("entities"))
            ->with(compact("status"))
            ->with(compact('perPage'));
    }

    public function replies(Request $request,$id)
    {
        $agreement_id = $id;
        $entity = $request->entity??'';
        $action = $request->action??'';
        $status_id = $request->status_id??'';
        $model = Agreement::findOrFail($agreement_id);
        $arrActions = Action::get_pagination($action,$agreement_id,$entity,$status_id, 12);
        $arrEntities = Entity::getForCatalogByIdAgreement($id);
        $arrStatus = Status::whereIn('id',Status::ACTIONS)->get()->pluck('name','id');
        $isAdmin = Auth::user()->hasRole(HelperApp::$roleSuperUsuario);
        return view('agreements.replies', compact('model', 'arrActions', 'arrEntities', 'arrStatus', 'agreement_id','isAdmin','request'));
    }

    public function action_store(ActionCreateRequest $request)
    {
        $model = new Action($request->all());
        
        $model->action = $request->action;
        $model->agreement_id = $request->agreement_id;
        $model->user_id = Auth::user()->id;
        $model->entity_id = Auth::user()->entity->id??$request->entity_id;

        $response = Action::_create($model);
        if($response['saved']){
            flash('<i class="mdi mdi-information-outline"></i> Se ha creado una nueva acción: <strong>' . $model->action . '</strong>')->success();
            return redirect()->route('agreements.reply', $request->agreement_id);
        } else {
            flash('<i class="mdi mdi-information-outline"></i> Ocurrio un error al momento de crear, Error:  <strong>' . $response['error'] . '</strong>.')->error();
            return \Redirect::back();
        } 
    }

    public function entities_detail(Request $request)
    {
        $agreement_id = $request->agreement_id;
        $entities_actions = Action::select(DB::raw("e.id as entity_id,count(distinct(actions.id)) as total, sum(if(actions.status_id=5,1,0)) as sin_avance, sum(if(actions.status_id=6,1,0)) as en_proceso, sum(if(actions.status_id=7,1,0)) as concluidas"))->join('entities as e','e.id','=','entity_id')
        ->where('agreement_id',$agreement_id)->whereIn('actions.status_id',Status::ACTIONS)->groupBy('e.id');
        $detail = Action::select("e.acronym as entity",DB::raw("round((sum(case when actions.status_id=7 then 1 when actions.status_id=6 then 0.5 else 0 end)/ea.total*100),2) as percent"),"ea.en_proceso","ea.sin_avance","ea.concluidas","ea.total")
        ->leftjoinSub($entities_actions, 'ea', function ($join) {
            $join->on('actions.entity_id', '=', 'ea.entity_id');
        })
        ->leftjoin('entities as e','e.id','=','actions.entity_id')
        ->where('agreement_id',$agreement_id)->whereIn('actions.status_id',Status::ACTIONS)->groupBy('e.id','total','sin_avance','en_proceso','concluidas')->get();
        return view('agreements.entities_detail', compact('detail'));
    }

    public function reply_action_form(Request $request)
    {
        $action_id = $request->action_id;
        $action = Action::findOrFail($action_id);
        $status = Auth::user()->hasRole(HelperApp::$roleEntidad)?"CONCLUIR":($action->status_id==Status::CONCLUIDO?"EN PROCESO":"CONCLUIR");
        return view('agreements.reply_action_form', compact('action','status'));
    }

    public function reply_action_store(ReplyActionCreateRequest $request)
    {
        DB::beginTransaction();
        try {
            $model = new Reply();
            $model->reply = $request->reply;
            $model->action_id = $request->action_id;
            $model->user_id = Auth::user()->id;
            $model->entity_id = Auth::user()->entity->id??null;
            $response = Reply::_create($model);
            if($response['saved']){
                $action = Action::findOrFail($request->action_id);
                if(filter_var(($request->status_id), FILTER_VALIDATE_BOOLEAN)){
                    $action->status_id = Auth::user()->hasRole(HelperApp::$roleSuperUsuario) && $action->status_id==Status::CONCLUIDO?Status::EN_PROCESO:Status::CONCLUIDO;
                    $edit = Action::_edit($action);
                }elseif($action->status_id==Status::SIN_AVANCE){
                    $action->status_id = Status::EN_PROCESO;
                    $edit = Action::_edit($action);
                }else{}
                if ($request->hasfile('files')) {
                    File::storeFiles($request->file('files'),File::MOD_ACTIONS,$request->action_id,null);
                }
                DB::commit();
                flash('<i class="mdi mdi-information-outline"></i> Se ha creado un nuevo avance: <strong>' . $model->reply . '</strong>')->success();
                return redirect()->route('agreements.reply', $request->agreement_id);
            } else {
                DB::rollback();
                flash('<i class="mdi mdi-information-outline"></i> Ocurrio un error al momento de crear, Error:  <strong>28.3</strong>.')->error();
                return \Redirect::back();
            } 
        } catch (\Throwable $th) {
            DB::rollback();
            flash('<i class="mdi mdi-information-outline"></i> Ocurrio un error al momento de crear, Error:  <strong>28.4</strong>.')->error();
            return \Redirect::back();
        }
    }

    public function action_replies(Request $request)
    {
        $action_id = $request->action_id;
        $action = Action::findOrFail($action_id);
        return view('agreements.action_replies', compact('action'));
    }

    public function action_destroy($id)
    {
        $model = Action::find($id);
        $model->status_id = Status::INACTIVO;
        $response = Action::_edit($model);

        if($response['saved']){
            flash('<i class="mdi mdi-information-outline"></i> Se ha eliminado esta acción  <strong>' . $model->action . '</strong> de forma exitosa.')->success();
            return redirect()->route('agreements.reply',$model->agreement_id);
        }else{
            flash('<i class="mdi mdi-information-outline"></i> Ocurrio un error al momento de eliminar, Error:  <strong>28.5</strong>.')->error();
            return \Redirect::back();
        }
    }

    public function action_edit(Request $request)
    {
        $action_id = $request->action_id;
        $action = Action::findOrFail($action_id);
        return view('agreements.edit_action_form', compact('action'));
    }

    public function action_update(ActionCreateRequest $request,$id)
    {
        $model = Action::find($id);
        
        $model->action = $request->action;

        $response = Action::_edit($model);

        if($response['saved']){
            flash('<i class="mdi mdi-information-outline"></i> Se ha actualizado la acción <strong>' . $model->action . '</strong> de forma exitosa.')->success();
            return redirect()->route('agreements.reply',$model->agreement_id);
        } else {
            flash('<i class="mdi mdi-information-outline"></i> Ocurrio un error al momento de actualizar, Error:  <strong>28.6</strong>.')->error();
            return \Redirect::back();
        }
    }

}
