<?php

namespace App\Http\Controllers;

use App\Helpers\HelperApp;
use App\Models\Action;
use App\Models\Agreement;
use App\Models\AgreementEntity;
use App\Models\Entity;
use App\Models\Meeting;
use App\Models\MeetingType;
use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Http\Request;

//model
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function gabinete_index(Request $request)
    {
        if(Auth::user()->hasRole([HelperApp::$roleSuperUsuario])){
            return $this->gabinete_admin($request);
        }elseif(Auth::user()->hasRole([HelperApp::$roleEntidad])){
            return $this->gabinete_entity($request);
        }
        
    }

    public function gabinete_admin(Request $request){

        $entities = Entity::getForCatalog();
        $table = $this->getTableGabinete($request->start_date,$request->end_date,$request->entity_id);
        $arr = $this->getAgreementsByEntitiesType($request->start_date,$request->end_date,$request->entity_id);
        $data = [
            "table" => $table,
            "dep"=>isset($arr[0])?$arr[0]:json_encode(null),
            "ent"=>isset($arr[1])?$arr[1]:json_encode(null),
            "fid"=>isset($arr[2])?$arr[2]:json_encode(null),
            "org"=>isset($arr[3])?$arr[3]:json_encode(null),
        ];
        return view('dashboards.gabinete.admin')->with(compact('request','entities','data'));
    }

    public function gabinete_entity(Request $request){
        $table = $this->getTableGabinete($request->start_date,$request->end_date,$request->entity_id);
        $arr = $this->getAgreementsByEntitiesType($request->start_date,$request->end_date,$request->entity_id);
        $data = [
            "table" => $table,
            "dep"=>isset($arr[0])?$arr[0]:json_encode(null),
            "ent"=>isset($arr[1])?$arr[1]:json_encode(null),
            "fid"=>isset($arr[2])?$arr[2]:json_encode(null),
            "org"=>isset($arr[3])?$arr[3]:json_encode(null),
        ];
        return view('dashboards.gabinete.entity')->with(compact('request','data'));
    }

    private function getTableGabinete($start_date, $end_date, $entity_id)
    {
        $sub_m = Meeting::query()->select("meetings.*")->join("entities_meeting_types as emt",function ($q) use($entity_id)
        {
            $q->on("emt.meeting_type_id","=","meetings.meeting_type_id");
            if (!empty($entity_id))
                $q->where("emt.entity_id",$entity_id);
        })->where(function ($q) use($start_date, $end_date)
        {
            if(!empty($start_date))
                $q->whereDate('meeting_date','>=',Carbon::parse($start_date)->format('Y-m-d'));
            if(!empty($end_date))
                $q->whereDate('meeting_date','<=',Carbon::parse($end_date)->format('Y-m-d'));
            $q->where("meetings.status_id",Status::CELEBRADA);
        })->groupBy("meetings.id");

        $sub_a = Agreement::query()->select(DB::raw("agreements.id, agreements.meeting_id, ((ac.avance/ac.total)*100)/ae.total as percent"))
        ->leftJoinSub((
            Action::query()->select(DB::raw("actions.agreement_id,count(actions.id) as total,sum(case when actions.status_id = 6 then .5 when actions.status_id = 7 then 1 else 0 end) as avance"))
            ->join("entities as e","e.id", "=", "actions.entity_id")
            ->where(function ($q) use($entity_id)
            {
                if (!empty($entity_id))
                    $q->where("actions.entity_id",$entity_id);
                $q->whereIn("actions.status_id",Status::ACTIONS);
            })
            ->groupBy("actions.agreement_id")
        ),"ac","ac.agreement_id", "=", "agreements.id")
        ->joinSub((
            AgreementEntity::query()->select(DB::raw("agreements_entities.agreement_id,count(distinct(agreements_entities.entity_id)) as total"))
            ->where(function ($q) use($entity_id)
            {
                if (!empty($entity_id))
                    $q->where("agreements_entities.entity_id",$entity_id);
            })
            ->groupBy("agreements_entities.agreement_id")
        ),"ae","ae.agreement_id", "=", "agreements.id")
        ->whereIn("agreements.status_id",Status::ACTIONS);

        $model= MeetingType::query()
        ->select(DB::raw("meeting_types.id as type_id,meeting_types.name as type,COUNT(DISTINCT(s_m.id)) as t_m,COUNT(DISTINCT(s_a.id)) as t_a,sum(s_a.percent) as t_percent"))
        ->leftjoinSub($sub_m,"s_m","s_m.meeting_type_id","=","meeting_types.id")
        ->leftjoinSub($sub_a,"s_a","s_a.meeting_id","=","s_m.id")
        ->groupBy("meeting_types.id")->get();
        return $model;
    }

    private function getAgreementsByEntitiesType($start_date, $end_date, $entity_id)
    {
        $sub = AgreementEntity::select("e.entities_types_id","agreement_id")
        ->join('entities as e',function ($q)use($entity_id)
        {
            $q->on('e.id','=','agreements_entities.entity_id');
            if(!empty($entity_id)||!empty(Auth::user()->entity))
                $q->where('e.id',Auth::user()->entity->id??$entity_id);
        })
        ->groupBy("agreement_id","e.entities_types_id");
        $model = Agreement::query()
        ->select(DB::raw("et.name as tipo,SUM(IF(agreements.status_id=5,1,0)) as sa,SUM(IF(agreements.status_id=6,1,0)) as ep,SUM(IF(agreements.status_id=7,1,0)) as c"))
        ->join('meetings as m','m.id','=','agreements.meeting_id')
        ->joinSub($sub,"ae","ae.agreement_id","=","agreements.id")
        ->rightjoin('entities_types as et','et.id','=','ae.entities_types_id')
        ->where(function ($query) use($start_date, $end_date, $entity_id)
        {
            if(!empty($start_date))
                $query->whereDate('meeting_date','>=',Carbon::parse($start_date)->format('Y-m-d'));
            if(!empty($end_date))
                $query->whereDate('meeting_date','<=',Carbon::parse($end_date)->format('Y-m-d'));
            $query->where("m.status_id",Status::CELEBRADA);
        })
        ->groupBy("et.id")->orderBy('et.name','ASC')->get();
        return $model;
    }

    public function gabinete_link($params)
    {
        $params = base64_decode($params); 
        $parse = parse_str($params,$input);
        $tipo = $input['tipo'];
        $startdate = $input['startdate'];
        $enddate = $input['enddate'];
        $request = json_decode(json_encode(array(
            "folio"=>null,
            "meeting_type_id"=>$input["tipo"],
            "init_date"=>$input["startdate"],
            "end_date"=>$input["enddate"],
            "status_id"=>Status::CELEBRADA,
        )));
        $arrMeetingTypes = MeetingType::getForCatalog();
        $arrStatus = Status::getForCatalogByIds([Status::CELEBRADA,Status::EN_PROCESO,Status::POR_CELEBRAR]);
        $arrMeetings = Meeting::get_pagination($request->folio, $request->meeting_type_id, $request->init_date, $request->end_date, $request->status_id, 25);
        return view('meetings.index', compact('arrMeetings', 'arrMeetingTypes', 'arrStatus', 'request'));
    }

}
