<?php

namespace App\Http\Controllers;

use App\Models\TypeProduit;
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


class TypeProduitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


     function __construct()
    {
        $this->middleware('auth');
         $this->middleware('permission:listtypeproduit|createtypeproduit|edittypeproduit|deletetypeproduit', ['only' => ['index','show']]);
         $this->middleware('permission:createtypeproduit', ['only' => ['create','store']]);
         $this->middleware('permission:edittypeproduit', ['only' => ['edit','update']]);
         $this->middleware('permission:deletetypeproduit', ['only' => ['destroy']]);
    }
 
    public function index()
    {
  
            $data = TypeProduit::orderBy('id', 'DESC')->where('Supprimer', false)->where('EntrepriseId',Auth::user()->EntrepriseId)->get();
        $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/config/tprods\">Types produit</a></li>";
        
        return view('config.tprods.index', compact('data'))->with('Titre','Types produit')->with('Breadcrumb',$Breadcrumb) ;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/config/tprods\">Types produit</a></li>";
        
        return view('config.tprods.create')->with('Titre','Types produit')->with('Breadcrumb',$Breadcrumb) ;
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
            Rule::unique('typeproduits')->ignore($request->id, 'id')->where(function ($query) {
                $query->where('EntrepriseId', Auth::user()->EntrepriseId);
            }),],
        ],
        [
            'Nom.required' => 'Le champ Nom est obligatoire.',
            'Nom.unique' => 'Cette unité existe déjà.',
           
        ]);
        
      
        TypeProduit::updateOrCreate(['id' => $request->id],
                ['Nom' => $request->Nom,'Code' => $request->Code,'Status' => $request->Status == 'on' ? 1 : 0,'EntrepriseId' => Auth::user()->EntrepriseId, 'Create_user' => Auth::user()->id, 
                // 'DateCreation' => Carbon::now()
            ]);        
           
        //    if(empty($request->input('id')))
        //    {
            return redirect()->route('tprods.index')
            ->with('success','Type produit créé avec succès');

            // $request->session()->flash('success', 'TypeProduit créé avec succès.');

        //    }
        //    else
        //    {
            // $request->session()->flash('success', 'TypeProduit modifié avec succès.');
        //     return redirect()->route('typeproduits.index')
        //     ->with('success','TypeProduit modifié avec succès');
        //    }
                // return response()->json(['url'=>url('/typeproduits')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TypeProduit  $typeproduit
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $typeproduit = TypeProduit::find($id);
        $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/config/tprods\">Types produit</a></li>";
        return view('config.tprods.show',compact('typeproduit'))->with('Titre','Types produit')->with('Breadcrumb',$Breadcrumb) ;
   
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TypeProduit  $typeproduit
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $typeproduit = TypeProduit::find($id);
        $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/config/tprods\">Types produit</a></li>";
        return view('config.tprods.edit',compact('typeproduit'))->with('Titre','Types produit')->with('Breadcrumb',$Breadcrumb) ;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TypeProduit  $typeproduit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        $this->validate($request, [
            'Nom' => ['required',
            Rule::unique('typeproduits')->ignore($request->id, 'id')->where(function ($query) {
                $query->where('EntrepriseId', Auth::user()->EntrepriseId);
            }),],
        ],
        [
            'Nom.required' => 'Le champ Nom est obligatoire.',
            'Nom.unique' => 'Cette unité existe déjà.',
           
        ]);
        $typeproduit = TypeProduit::find($request->id);
        $typeproduit->Status = $request->Status == 'on' ? 1 : 0;
        $typeproduit->Code = $request->Code;
        $typeproduit->Nom = $request->Nom;
        $typeproduit->Edit_user= Auth::user()->id;
        $typeproduit->save();
    
        return redirect()->route('tprods.index')
                        ->with('success','Type produit modifié avec succès.');
 
    }

   

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TypeProduit  $typeproduit
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      
        // $employes= DB::table('employes')->where('PosteId',$request->id)->get();
        if(count(DB::table('produits')->where('TypeProduitId',$id)->get())>0)
        {
            // $request->session()->flash('danger', 'Ce typeproduit ne peut être supprimé car il est utilisé par un employé.');
            return redirect('/config/tprods')->with('danger', 'Ce type produit ne peut être supprimé car il est attribué à un employé.');
      
        }
        else
        {
            $typeproduit = TypeProduit::find($id);
            $typeproduit->Supprimer =true;
            $typeproduit->Delete_user= Auth::user()->id;
            $typeproduit->save();
        return redirect('/config/tprods')->with('success', 'Type produit supprimé avec succès.');

        }
      
        // return response()->json(['url'=>url('/typeproduits')]);
    }
}
