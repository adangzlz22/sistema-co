<?php

namespace App\Http\Controllers;

use App\Helpers\HelperApp;
use App\Models\Log;
use Illuminate\Http\Request;

class LogsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $current_date = HelperApp::get_datetime();
        $end_date = $current_date->format('d/m/Y');
        $init_date = $current_date->addMonths(-1)->format('d/m/Y');


        $user = $request->tbUser ?? '';
        $init_date = $request->init_date ?? $init_date;
        $end_date = $request->end_date ?? $end_date;

        $perPage = request('iPerPage') ?? 50;

        $model = Log::get_pagination($user, $init_date, $end_date,$perPage);
        if($request->ajax()){
            return view('logs._index')
                ->with(compact("model"))
                ->with(compact('perPage'));
        }

        return view('logs.index')
            ->with(compact("model"))
            ->with(compact("request"))
            ->with(compact("init_date"))
            ->with(compact("end_date"))
            ->with(compact('perPage'));
    }
}
