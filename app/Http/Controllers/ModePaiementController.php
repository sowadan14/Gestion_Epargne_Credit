<?php

namespace App\Http\Controllers;

use App\Models\ModePaiement;
use App\Models\Unite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class ModePaiementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


     function __construct()
    {
        $this->middleware('auth');
         $this->middleware('permission:listmodepaiement|createmodepaiement|editmodepaiement|deletemodepaiement', ['only' => ['index','show']]);
         $this->middleware('permission:createmodepaiement', ['only' => ['create','store']]);
         $this->middleware('permission:editmodepaiement', ['only' => ['edit','update']]);
         $this->middleware('permission:deletemodepaiement', ['only' => ['destroy']]);
    }
 
    public function index()
    {
  
            $data = ModePaiement::orderBy('id', 'DESC')->where('Supprimer', false)->where('EntrepriseId',Auth::user()->EntrepriseId)->get();
        $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/config/mpaiements\">Modes de paiement</a></li>";
        
        return view('config.mpaiements.index', compact('data'))->with('Titre','Modes de paiement')->with('Breadcrumb',$Breadcrumb) ;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/config/mpaiements\">Modes de paiement</a></li>";
        
        return view('config.mpaiements.create')->with('Titre','Modes de paiement')->with('Breadcrumb',$Breadcrumb) ;
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
            'Nom' => ['required',
            Rule::unique('modepaiements')->ignore($request->id, 'id')->where(function ($query) {
                $query->where('EntrepriseId', Auth::user()->EntrepriseId);
            }),],
        ],
        [
            'Nom.required' => 'Le champ Nom est obligatoire.',
            'Nom.unique' => 'Cette unité existe déjà.',
           
        ]);
        
      
        ModePaiement::updateOrCreate(['id' => $request->id],
                ['Nom' => $request->Nom,'Code' => $request->Code,'Status' => $request->Status == 'on' ? 1 : 0,'EntrepriseId' => Auth::user()->EntrepriseId, 'Create_user' => Auth::user()->id,
                //  'DateCreation' => Carbon::now() 
                ]);        
           
        //    if(empty($request->input('id')))
        //    {
            return redirect()->route('mpaiements.index')
            ->with('success','Mode de paiement créé avec succès');

            // $request->session()->flash('success', 'ModePaiement créé avec succès.');

        //    }
        //    else
        //    {
            // $request->session()->flash('success', 'ModePaiement modifié avec succès.');
        //     return redirect()->route('modepaiements.index')
        //     ->with('success','ModePaiement modifié avec succès');
        //    }
                // return response()->json(['url'=>url('/modepaiements')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ModePaiement  $modepaiement
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $modepaiement = ModePaiement::find($id);
        $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/config/mpaiements\">Modes de paiement</a></li>";
        return view('config.mpaiements.show',compact('modepaiement'))->with('Titre','Modes de paiement')->with('Breadcrumb',$Breadcrumb) ;
   
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ModePaiement  $modepaiement
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $modepaiement = ModePaiement::find($id);
        $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/config/mpaiements\">Modes de paiement</a></li>";
        return view('config.mpaiements.edit',compact('modepaiement'))->with('Titre','Modes de paiement')->with('Breadcrumb',$Breadcrumb) ;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ModePaiement  $modepaiement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        $this->validate($request, [
            'Nom' => ['required',
            Rule::unique('modepaiements')->ignore($request->id, 'id')->where(function ($query) {
                $query->where('EntrepriseId', Auth::user()->EntrepriseId);
            }),],
        ],
        [
            'Nom.required' => 'Le champ Nom est obligatoire.',
            'Nom.unique' => 'Cette unité existe déjà.',
           
        ]);
        $modepaiement = ModePaiement::find($request->id);
        $modepaiement->Status = $request->Status == 'on' ? 1 : 0;
        $modepaiement->Code = $request->Code;
        $modepaiement->Nom = $request->Nom;
        $modepaiement->Edit_user= Auth::user()->id;
        $modepaiement->save();
    
        return redirect()->route('mpaiements.index')
                        ->with('success','Mode de paiement modifié avec succès.');
 
    }

   

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ModePaiement  $modepaiement
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      
        // $employes= DB::table('employes')->where('PosteId',$request->id)->get();
        if(count(DB::table('modepaiementproduits')->where('ModePaiementId',$id)->get())>0)
        {
            // $request->session()->flash('danger', 'Ce modepaiement ne peut être supprimé car il est utilisé par un employé.');
            return redirect('/config/mpaiements')->with('danger', 'cet mode de paiement ne peut être supprimé car il est attribué à un employé.');
      
        }
        else
        {
            $modepaiement = ModePaiement::find($id);
            $modepaiement->Supprimer =true;
            $modepaiement->Delete_user= Auth::user()->id;
            $modepaiement->save();
        return redirect('/config/mpaiements')->with('success', 'Mode de paiement supprimé avec succès.');

        }
      
        // return response()->json(['url'=>url('/modepaiements')]);
    }
}
