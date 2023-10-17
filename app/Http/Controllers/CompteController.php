<?php

namespace App\Http\Controllers;

use App\Models\Compte;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CompteController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:listcompte|createcompte|editcompte|deletecompte', ['only' => ['index','show']]);
         $this->middleware('permission:createcompte', ['only' => ['create','store']]);
         $this->middleware('permission:editcompte', ['only' => ['edit','update']]);
         $this->middleware('permission:deletecompte', ['only' => ['destroy']]);
    }

    public function index()
    {
             $data = Compte::orderBy('id', 'DESC')->where('Supprimer', false)->where('EntrepriseId',Auth::user()->EntrepriseId)->get();       
            $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"/comptes\">Comptes</a></li>";
        
        return view('config.comptes.index', compact('data'))->with('Titre','Liste des comptes')->with('Breadcrumb',$Breadcrumb) ;
            // 'Breadcrumb'=> $Breadcrumb , // add as much as you want
        //  ]);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
        // $postes= User::select("id", DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"))
        // ->pluck('full_name', 'id');
        $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/config/comptes\">Comptes</a></li>";
        
        return view('config.comptes.create')->with('Titre','Comptes')->with('Breadcrumb',$Breadcrumb) ;
      
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'Libelle' => ['required',
            Rule::unique('comptes')->ignore($request->id, 'id')->where(function ($query) {
                $query->where('EntrepriseId', Auth::user()->EntrepriseId);
            }),],
                'SoldeInitial' => "numeric",
            ],
            [
                'Libelle.required' => 'Le champ Libellé est obligatoire.',
                'Libelle.unique' => 'Ce compte existe déjà.',
                // 'soldeInitial.required' => 'Le champ Solde initial est obligatoire.',
                'SoldeInitial.numeric' => 'Le champ Solde initial doit être un nombre.',
            ]);

            Compte::updateOrCreate(['id' => $request->id],
            ['Libelle' => $request->Libelle, 
            'SoldeInitial' => $request->SoldeInitial, 
            'Code' => $request->Code,
            'Solde' => 0, 
            'Status' => $request->Status == 'on' ? 1 : 0,
           'EntrepriseId' => Auth::user()->EntrepriseId, 'Create_user' => Auth::user()->id,
            //  'DateCreation' => Carbon::now()
            ]); 

           
             return redirect()->route('comptes.index')
             ->with('success','Compte créé avec succès');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Compte  $compte
     * @return \Illuminate\Http\Response
     */
    public function show($compte_id)
    {
        $compte = Compte::find($compte_id);
        $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/config/comptes\">Postes</a></li>";
        return view('config.comptes.show',compact('compte'))->with('Titre','Comptes')->with('Breadcrumb',$Breadcrumb) ;
   
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Compte  $compte
     * @return \Illuminate\Http\Response
     */
    public function edit($compte_id)
    {

        $compte = Compte::find($compte_id);
        $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/config/comptes\">Comptes</a></li>";
        return view('config.comptes.edit',compact('compte'))->with('Titre','Comptes')->with('Breadcrumb',$Breadcrumb) ; 
       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Compte  $compte
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Compte $compte)
    {
        $this->validate($request, [
            'Libelle' => ['required',
            Rule::unique('comptes')->ignore($request->id, 'id')->where(function ($query) {
                $query->where('EntrepriseId', Auth::user()->EntrepriseId);
            }),],
            'SoldeInitial' => "numeric",
        ],
        [
            'Libelle.required' => 'Le champ Libellé est obligatoire.',
            'Libelle.unique' => 'Ce compte existe déjà.',
            // 'soldeInitial.required' => 'Le champ Solde initial est obligatoire.',
            'SoldeInitial.numeric' => 'Le champ Solde initial doit être un nombre.',
        ]);

        $compte = Compte::find($request->id);
        $compte->Status = $request->Status == 'on' ? 1 : 0;
        $compte->Code = $request->Code;
        $compte->Libelle = $request->Libelle;
        $compte->SoldeInitial = $request->SoldeInitial;
        $compte->Edit_user= Auth::user()->id;
        $compte->save();
    
        return redirect()->route('comptes.index')
                        ->with('success','Compte modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Compte  $compte
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        // $comptes= DB::table('comptes')->where('PosteId',$request->id)->get();
        if(count(DB::table('ventes')->where('CompteId',$id)->get())>0)
        {
            return redirect('/config/comptes')->with('danger', "Ce compte ne peut être supprimé car elle est utilisée par d'autres données.");
      
        }
        else
        {
            // Compte::where('id',$id)->delete();
            $compte = Compte::find($id);
            $compte->Supprimer =true;
            $compte->Delete_user= Auth::user()->id;
            $compte->save();
            return redirect('/config/comptes')->with('success', 'Compte supprimé avec succès.');
        }
    }
}
