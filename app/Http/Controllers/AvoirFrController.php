<?php

namespace App\Http\Controllers;

use App\Models\AvoirFr;
use App\Models\Compte;
use App\Models\Fournisseur;
use App\Models\ReceptionAvoirFr;
use App\Models\Unite;
use App\Models\Produit;
use App\Models\ModePaiement;
use App\Models\FactureAvoirFr;
use App\Models\PaiementAvoirFr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AvoirFrController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // function __construct()
    // {
    //     $this->middleware('auth');
    //     $this->middleware('permission:listavoirfr', ['only' => ['index']]);
       
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = AvoirFr::orderBy('id', 'DESC')->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Recouvrement</a></li><li class=\"breadcrumb-item\"><a href=\"/recouv/avoirfrs\">Avoir fournisseur</a></li>";

        return view('recouv.avoirfrs.index', compact('data'))->with('Titre', 'Avoir fournisseur')->with('Breadcrumb', $Breadcrumb);
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
      
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AvoirFr  $recouv
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         }

        }