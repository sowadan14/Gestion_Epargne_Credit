<?php

namespace App\Http\Controllers;


use App\Models\Vente;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EtatCltController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // function __construct()
    // {
    //     $this->middleware('auth');
    //     $this->middleware('permission:listetatfr', ['only' => ['index']]);
       
    // }

    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    //    dd($request);
        $data = Vente::orderBy('id', 'DESC')->where('Supprimer', false)->where('ClientId', $request->id)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Recouvrement</a></li><li class=\"breadcrumb-item\"><a href=\"/recouv/etatclts\">Etat client</a></li>";
        $clients = Client::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $CltId=$request->id;
        return view('recouv.etatclts.index', compact('data','clients','CltId'))->with('Titre', 'Etat client')->with('Breadcrumb', $Breadcrumb);
   }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
           }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return redirect()->route('etatclts.index', ['id' => $request->ClientId]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EtatFr  $recouv
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         }

        }