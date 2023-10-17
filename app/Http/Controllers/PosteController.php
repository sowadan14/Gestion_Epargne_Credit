<?php

namespace App\Http\Controllers;

use App\Models\Poste;
use App\Models\Employe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use DataTables;


class PosteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


     function __construct()
    {
        $this->middleware('auth');
         $this->middleware('permission:listposte|createposte|editposte|deleteposte', ['only' => ['index','show']]);
         $this->middleware('permission:createposte', ['only' => ['create','store']]);
         $this->middleware('permission:editposte', ['only' => ['edit','update']]);
         $this->middleware('permission:deleteposte', ['only' => ['destroy']]);
    }
 
    public function index()
    {
            $data = Poste::orderBy('id', 'DESC')->where('Supprimer', false)->where('EntrepriseId',Auth::user()->EntrepriseId)->get();
        $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/config/postes\">Postes</a></li>";
        
        return view('config.postes.index', compact('data'))->with('Titre','Postes')->with('Breadcrumb',$Breadcrumb) ;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/config/postes\">Postes</a></li>";
        
        return view('config.postes.create')->with('Titre','Postes')->with('Breadcrumb',$Breadcrumb) ;
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
            Rule::unique('postes')->ignore($request->id, 'id')->where(function ($query) {
                $query->where('EntrepriseId', Auth::user()->EntrepriseId);
            }),],
        ],
        [
            'Libelle.required' => 'Le champ Libellé est obligatoire.',
            'Libelle.unique' => 'Ce poste existe déjà.',
           
        ]);
        
      
        Poste::updateOrCreate(['id' => $request->id],
                ['Libelle' => $request->Libelle,
                'Code' => $request->Code,
                'Status' => $request->Status == 'on' ? 1 : 0, 
               'EntrepriseId' => Auth::user()->EntrepriseId, 'Create_user' => Auth::user()->id, 
                // 'DateCreation' => Carbon::now()
            ]);        
           
        //    if(empty($request->input('id')))
        //    {
            return redirect()->route('postes.index')
            ->with('success','Poste créé avec succès');

            // $request->session()->flash('success', 'Poste créé avec succès.');

        //    }
        //    else
        //    {
            // $request->session()->flash('success', 'Poste modifié avec succès.');
        //     return redirect()->route('postes.index')
        //     ->with('success','Poste modifié avec succès');
        //    }
                // return response()->json(['url'=>url('/postes')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Poste  $poste
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $poste = Poste::find($id);
        $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/config/postes\">Postes</a></li>";
        return view('config.postes.show',compact('poste'))->with('Titre','Postes')->with('Breadcrumb',$Breadcrumb) ;
   
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Poste  $poste
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $poste = Poste::find($id);
        $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/config/postes\">Postes</a></li>";
        return view('config.postes.edit',compact('poste'))->with('Titre','Postes')->with('Breadcrumb',$Breadcrumb) ;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Poste  $poste
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        $this->validate($request, [
            'Libelle' => ['required',
            Rule::unique('postes')->ignore($request->id, 'id')->where(function ($query) {
                $query->where('EntrepriseId', Auth::user()->EntrepriseId);
            }),],
        ],
        [
            'Libelle.required' => 'Le champ Libellé est obligatoire.',
            'Libelle.unique' => 'Ce poste existe déjà.',
           
        ]);
        $poste = Poste::find($request->id);
        $poste->Status = $request->Status == 'on' ? 1 : 0;
        $poste->Code = $request->Code;
        $poste->Libelle = $request->Libelle;
        $poste->Edit_user= Auth::user()->id;
        $poste->save();
    
        return redirect()->route('postes.index')
                        ->with('success','Poste modifié avec succès.');
 
    }

   

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Poste  $poste
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      
        // $employes= DB::table('employes')->where('PosteId',$request->id)->get();
        if(count(DB::table('employes')->where('PosteId',$id)->get())>0)
        {
            // $request->session()->flash('danger', 'Ce poste ne peut être supprimé car il est utilisé par un employé.');
            return redirect('/config/postes')->with('danger', 'Ce poste ne peut être supprimé car il est attribué à un employé.');
      
        }
        else
        {
            // Poste::where('id',$id)->delete();
            $poste = Poste::find($id);
            $poste->Supprimer =true;
            $poste->Delete_user= Auth::user()->id;
            $poste->save();
        return redirect('/config/postes')->with('success', 'Poste supprimé avec succès.');

        }
      
        // return response()->json(['url'=>url('/postes')]);
    }
}
