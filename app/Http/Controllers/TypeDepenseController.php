<?php

namespace App\Http\Controllers;

use App\Models\TypeDepense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TypeDepenseController extends Controller
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
            $data = TypeDepense::orderBy('id', 'DESC')->where('Supprimer', false)->where('EntrepriseId',Auth::user()->EntrepriseId)->get();
        $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"#\">Dépense</a></li><li class=\"breadcrumb-item\"><a href=\"/depenses/type\">Type</a></li>";
        
        return view('depenses.type.index', compact('data'))->with('Titre','Types dépense')->with('Breadcrumb',$Breadcrumb) ;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"#\">Dépense</a></li><li class=\"breadcrumb-item\"><a href=\"/depenses/type\">Type</a></li>";
        
        return view('depenses.type.create')->with('Titre','Types dépense')->with('Breadcrumb',$Breadcrumb) ;
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
            Rule::unique('typedepenses')->ignore($request->id, 'id')->where(function ($query) {
                $query->where('EntrepriseId', Auth::user()->EntrepriseId);
            }),],
        ],
        [
            'Libelle.required' => 'Le champ Libellé est obligatoire.',
            'Libelle.unique' => 'Ce type dépense existe déjà.',
           
        ]);
        
      
        TypeDepense::updateOrCreate(['id' => $request->id],
                ['Libelle' => $request->Libelle,
                'Code' => $request->Code,
                'Status' => $request->Status == 'on' ? 1 : 0, 
               'EntrepriseId' => Auth::user()->EntrepriseId, 'Create_user' => Auth::user()->id, 
                // 'DateCreation' => Carbon::now()
            ]);        
           
        //    if(empty($request->input('id')))
        //    {
            return redirect()->route('type.index')
            ->with('success','Type dépense créé avec succès');

            // $request->session()->flash('success', 'TypeDepense créé avec succès.');

        //    }
        //    else
        //    {
            // $request->session()->flash('success', 'TypeDepense modifié avec succès.');
        //     return redirect()->route('type.index')
        //     ->with('success','TypeDepense modifié avec succès');
        //    }
                // return response()->json(['url'=>url('/type')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TypeDepense  $poste
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $poste = TypeDepense::find($id);
        $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"#\">Dépense</a></li><li class=\"breadcrumb-item\"><a href=\"/depenses/type\"mpte>Type</a></li>";
        return view('depenses.type.show',compact('poste'))->with('Titre','Types dépense')->with('Breadcrumb',$Breadcrumb) ;
   
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TypeDepense  $poste
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $typedepense = TypeDepense::find($id);
        $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"#\">Dépense</a></li><li class=\"breadcrumb-item\"><a href=\"/depenses/type\"mpte>Type</a></li>";
        return view('depenses.type.edit',compact('typedepense'))->with('Titre','Types dépense')->with('Breadcrumb',$Breadcrumb) ;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TypeDepense  $poste
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        $this->validate($request, [
            'Libelle' => ['required',
            Rule::unique('typedepenses')->ignore($request->id, 'id')->where(function ($query) {
                $query->where('EntrepriseId', Auth::user()->EntrepriseId);
            }),],
        ],
        [
            'Libelle.required' => 'Le champ Libellé est obligatoire.',
            'Libelle.unique' => 'Ce type dépense existe déjà.',
           
        ]);
        $typedepense = TypeDepense::find($request->id);
        $typedepense->Status = $request->Status == 'on' ? 1 : 0;
        $typedepense->Code = $request->Code;
        $typedepense->Libelle = $request->Libelle;
        $typedepense->Edit_user= Auth::user()->id;
        $typedepense->save();
    
        return redirect()->route('type.index')
                        ->with('success','Type dépense modifié avec succès.');
 
    }

   

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TypeDepense  $poste
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      
        // $employes= DB::table('employes')->where('TypeDepenseId',$request->id)->get();
        if(count(DB::table('depenses')->where('TypeDepenseId',$id)->get())>0)
        {
            // $request->session()->flash('danger', 'Ce poste ne peut être supprimé car il est utilisé par un employé.');
            return redirect('/depenses/type')->with('danger', 'Ce type dépense ne peut être supprimé car il est attribué à une dépense.');
      
        }
        else
        {
            // TypeDepense::where('id',$id)->delete();
            $poste = TypeDepense::find($id);
            $poste->Supprimer =true;
            $poste->Delete_user= Auth::user()->id;
            $poste->save();
        return redirect('/depenses/type')->with('success', 'Type dépense supprimé avec succès.');

        }
      
        // return response()->json(['url'=>url('/type')]);
    }
}
