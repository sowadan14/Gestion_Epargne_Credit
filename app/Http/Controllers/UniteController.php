<?php

namespace App\Http\Controllers;

use App\Models\Unite;
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


class UniteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


     function __construct()
    {
        $this->middleware('auth');
         $this->middleware('permission:listunite|createunite|editunite|deleteunite', ['only' => ['index','show']]);
         $this->middleware('permission:createunite', ['only' => ['create','store']]);
         $this->middleware('permission:editunite', ['only' => ['edit','update']]);
         $this->middleware('permission:deleteunite', ['only' => ['destroy']]);
    }
 
    public function index()
    {
       
  
            $data = Unite::orderBy('id', 'DESC')->where('Supprimer', false)->where('EntrepriseId',Auth::user()->EntrepriseId)->get();
        $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/config/unites\">Unités</a></li>";
        
        return view('config.unites.index', compact('data'))->with('Titre','Unités')->with('Breadcrumb',$Breadcrumb) ;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/config/unites\">Unités</a></li>";
        
        return view('config.unites.create')->with('Titre','Unités')->with('Breadcrumb',$Breadcrumb) ;
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
            Rule::unique('unites')->ignore($request->id, 'id')->where(function ($query) {
                $query->where('EntrepriseId', Auth::user()->EntrepriseId);
            }),],
        ],
        [
            'Nom.required' => 'Le champ Nom est obligatoire.',
            'Nom.unique' => 'Cette unité existe déjà.',
           
        ]);
        
      
        Unite::updateOrCreate(['id' => $request->id],
                ['Nom' => $request->Nom,'Code' => $request->Code,'Status' => $request->Status == 'on' ? 1 : 0,'EntrepriseId' => Auth::user()->EntrepriseId, 'Create_user' => Auth::user()->id, 
                // 'DateCreation' => Carbon::now()
            ]);        
           
        //    if(empty($request->input('id')))
        //    {
            return redirect()->route('unites.index')
            ->with('success','Unité créée avec succès');

            // $request->session()->flash('success', 'Unite créé avec succès.');

        //    }
        //    else
        //    {
            // $request->session()->flash('success', 'Unite modifié avec succès.');
        //     return redirect()->route('unites.index')
        //     ->with('success','Unite modifié avec succès');
        //    }
                // return response()->json(['url'=>url('/unites')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Unite  $unite
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $unite = Unite::find($id);
        $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/config/unites\">Unités</a></li>";
        return view('config.unites.show',compact('unite'))->with('Titre','Unités')->with('Breadcrumb',$Breadcrumb) ;
   
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Unite  $unite
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $unite = Unite::find($id);
        $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/config/unites\">Unités</a></li>";
        return view('config.unites.edit',compact('unite'))->with('Titre','Unités')->with('Breadcrumb',$Breadcrumb) ;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Unite  $unite
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        $this->validate($request, [
            'Nom' => ['required',
            Rule::unique('unites')->ignore($request->id, 'id')->where(function ($query) {
                $query->where('EntrepriseId', Auth::user()->EntrepriseId);
            }),],
        ],
        [
            'Nom.required' => 'Le champ Nom est obligatoire.',
            'Nom.unique' => 'Cette unité existe déjà.',
           
        ]);
        $unite = Unite::find($request->id);
        $unite->Status = $request->Status == 'on' ? 1 : 0;
        $unite->Code = $request->Code;
        $unite->Nom = $request->Nom;
        $unite->Edit_user= Auth::user()->id;
        $unite->save();
    
        return redirect()->route('unites.index')
                        ->with('success','Unité modifiée avec succès.');
 
    }

   

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Unite  $unite
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      
        // $employes= DB::table('employes')->where('PosteId',$request->id)->get();
        if(count(DB::table('uniteproduits')->where('UniteId',$id)->get())>0)
        {
            // $request->session()->flash('danger', 'Ce unite ne peut être supprimé car il est utilisé par un employé.');
            return redirect('/config/unites')->with('danger', 'Cette unité ne peut être supprimée car il est attribué à un employé.');
      
        }
        else
        {
            $unite = Unite::find($id);
            $unite->Supprimer =true;
            $unite->Delete_user= Auth::user()->id;
            $unite->save();
        return redirect('/config/unites')->with('success', 'Unité supprimée avec succès.');

        }
      
        // return response()->json(['url'=>url('/unites')]);
    }
}
