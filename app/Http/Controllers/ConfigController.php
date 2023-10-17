<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

use DataTables;


class ConfigController extends Controller
{
 
    public function index()
    {
       
  $data = Entreprise::find(Auth::user()->EntrepriseId);
        $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"/config\">Configuration</a></li>";
        
        return view('config.index', compact('data'))->with('Titre','Général')->with('Breadcrumb',$Breadcrumb) ;
        // dd($data);
    }
}
