<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use App\Models\Meeting;
use App\Models\MeetingType;
use App\Models\Indicators;

use Illuminate\Http\Request;

//model
use App\Models\Menu;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        return view('home')
            ->with(compact("request"));
    }

    /**
     * Show the application calendar.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function calendar(Request $request)
    {
        $status = Status::getForCatalogByIds([Status::CELEBRADA,Status::EN_PROCESO,Status::POR_CELEBRAR]);
        $meeting_types = MeetingType::getForCatalog();
        $meetings = Meeting::with(["meeting_type.entities","meeting_type","modality","place","status"])
        ->whereHas("meeting_type.entities",function ($q) use($request)
        {
            if(!empty($request->input('entity_id')||isset(Auth::user()->entity->id))){
                $q->where("entities.id",'=',(isset(Auth::user()->entity->id)?Auth::user()->entity->id:$request->input('entity_id')));
            }
        })
        ->whereHas("meeting_type",function ($q) use($request)
        {
            if(!empty($request->input('meeting_type_id'))){
                $q->where("meeting_types.id",$request->input('meeting_type_id'));
            }
        })->whereHas("modality",function ($q) use($request)
        {
            if(!empty($request->input('modality_id'))){
                $q->where("modalities.id",$request->input('modality_id'));
            }
        })
        ->where("status_id",Status::POR_CELEBRAR)->get()->toJson();
        return view('calendar.index')
            ->with(compact("request","meeting_types","meetings","status"));
    }

    /**
     * Show the application semaforo.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function indicators(Request $request)
    {
        $perPage = request('iPerPage') ?? 10;
        $entities = Entity::select("entities.id","entities.name"
                                            ,DB::raw("count(distinct(ne.id)) AS TotalAsignados")
                                            ,DB::raw("sum(if(ne.status_id=7,1,0)) AS TotalConcluido")
                                            ,DB::raw("sum(if(ne.status_id=6,1,0)) AS TotalEnProceso")
                                            ,DB::raw("sum(if(ne.status_id=5,1,0)) AS TotalPendiente")
                                            ,DB::raw("COALESCE(
                                                (((SUM(IF(N.status_id=7,1,0))) + (SUM(IF(N.status_id=6,1,0))*.5)) / count(DISTINCT(N.id)) ) * 100
                                            ,0) AS Porcentaje")
                                            ,DB::raw("SUM(IF(N.status_id=7,1,0)) AS ActionsTotalConcluidas")
                                            ,DB::raw("SUM(IF(N.status_id=6,1,0))AS ActionsTotalEnProceso")
                                            ,DB::raw("count(DISTINCT(N.id)) AS TotalActions")
        
        )
        ->leftjoin( "agreements_entities AS ae","ae.entity_id","entities.id" )
        ->leftjoin( "agreements AS ne","ne.id","ae.agreement_id" )
        ->leftjoin( "actions AS N", function ($q)
        {
            $q->on("N.entity_id","entities.id")->on("N.agreement_id","ae.agreement_id");
        })
        ->where( "entities.status_id", "=",Status::ACTIVO )
        ->orderBy('entities.id','ASC')
        ->groupBy("entities.id","entities.name")->paginate($perPage);
        // dd($entities);
        return view('indicators.indicators')
            ->with(compact("request","entities"));
    }
   

}
