<?php

namespace App\Http\Controllers;


use App\Models\Achat;
use App\Models\Fournisseur;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EtatFrController extends Controller
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
        $data = Achat::orderBy('id', 'DESC')->where('Supprimer', false)->where('FournisseurId', $request->id)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Recouvrement</a></li><li class=\"breadcrumb-item\"><a href=\"/recouv/etatfrs\">Etat fournisseur</a></li>";
        $fournisseurs = Fournisseur::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $FrId=$request->id;
        return view('recouv.etatfrs.index', compact('data','fournisseurs','FrId'))->with('Titre', 'Etat fournisseur')->with('Breadcrumb', $Breadcrumb);
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
        return redirect()->route('etatfrs.index', ['id' => $request->FournisseurId]);
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