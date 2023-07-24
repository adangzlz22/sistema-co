<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FilesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function files_detail(Request $request)
    {
        $parent_id = $request->parent_id;
        $mod_id = $request->mod_id;
        $detail = File::where('parent_id',$parent_id)->where('modulo',$mod_id)->orderBy('created_at','DESC')->get();
        return view('files.files_detail', compact('detail'));
    }

}
