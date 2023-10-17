<?php

namespace App\Http\Controllers;

use App\Models\CategProduit;
use App\Models\Employe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


class CategProduitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


     function __construct()
    {
        $this->middleware('auth');
         $this->middleware('permission:listcategproduit|createcategproduit|editcategproduit|deletecategproduit', ['only' => ['index','show']]);
         $this->middleware('permission:createcategproduit', ['only' => ['create','store']]);
         $this->middleware('permission:editcategproduit', ['only' => ['edit','update']]);
         $this->middleware('permission:deletecategproduit', ['only' => ['destroy']]);
    }
 
    public function index()
    {
       
  
            $data = CategProduit::orderBy('id', 'DESC')->where('Supprimer', false)->where('EntrepriseId',Auth::user()->EntrepriseId)->get();
        $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/config/categprods\">Familles produit</a></li>";
        
        return view('config.categprods.index', compact('data'))->with('Titre','Familles produit')->with('Breadcrumb',$Breadcrumb) ;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/config/categprods\">Familles produit</a></li>";
        
        return view('config.categprods.create')->with('Titre','Familles produit')->with('Breadcrumb',$Breadcrumb) ;
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
            Rule::unique('categproduits')->ignore($request->id, 'id')->where(function ($query) {
                $query->where('EntrepriseId', Auth::user()->EntrepriseId);
            }),],
        ],
        [
            'Nom.required' => 'Le champ Nom est obligatoire.',
            'Nom.unique' => 'Cette unité existe déjà.',
           
        ]);
        
      
        CategProduit::updateOrCreate(['id' => $request->id],
                ['Nom' => $request->Nom,'Code' => $request->Code,'Status' => $request->Status == 'on' ? 1 : 0,'EntrepriseId' => Auth::user()->EntrepriseId, 'Create_user' => Auth::user()->id, 
                // 'DateCreation' => Carbon::now()
            ]);        
           
        //    if(empty($request->input('id')))
        //    {
            return redirect()->route('categprods.index')
            ->with('success','Famille produit créée avec succès');

            // $request->session()->flash('success', 'CategProduit créé avec succès.');

        //    }
        //    else
        //    {
            // $request->session()->flash('success', 'CategProduit modifié avec succès.');
        //     return redirect()->route('categprods.index')
        //     ->with('success','CategProduit modifié avec succès');
        //    }
                // return response()->json(['url'=>url('/categproduits')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CategProduit  $categproduit
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $categproduit = CategProduit::find($id);
        $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/config/categprods\">Familles produit</a></li>";
        return view('config.categprods.show',compact('categproduit'))->with('Titre','Familles produit')->with('Breadcrumb',$Breadcrumb) ;
   
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CategProduit  $categproduit
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categproduit = CategProduit::find($id);
        $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/config/categprods\">Familles produit</a></li>";
        return view('config.categprods.edit',compact('categproduit'))->with('Titre','Familles produit')->with('Breadcrumb',$Breadcrumb) ;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CategProduit  $categproduit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        $this->validate($request, [
            'Nom' => ['required',
            Rule::unique('categproduits')->ignore($request->id, 'id')->where(function ($query) {
                $query->where('EntrepriseId', Auth::user()->EntrepriseId);
            }),],
        ],
        [
            'Nom.required' => 'Le champ Nom est obligatoire.',
            'Nom.unique' => 'Cette unité existe déjà.',
           
        ]);
        $categproduit = CategProduit::find($request->id);
        $categproduit->Status = $request->Status == 'on' ? 1 : 0;
        $categproduit->Code = $request->Code;
        $categproduit->Nom = $request->Nom;
        $categproduit->Edit_user= Auth::user()->id;
        $categproduit->save();
    
        return redirect()->route('categprods.index')
                        ->with('success','Famille produit modifiée avec succès.');
 
    }

   

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CategProduit  $categproduit
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      
        // $employes= DB::table('employes')->where('PosteId',$request->id)->get();
        if(count(DB::table('produits')->where('CategProduitId',$id)->get())>0)
        {
            // $request->session()->flash('danger', 'Ce categproduit ne peut être supprimé car il est utilisé par un employé.');
            return redirect('/config/categprods')->with('danger', 'Cette famille produit ne peut être supprimée car il est attribué à un employé.');
      
        }
        else
        {
            $categproduit = CategProduit::find($id);
            $categproduit->Supprimer =true;
            $categproduit->Delete_user= Auth::user()->id;
            $categproduit->save();
        return redirect('/config/categprods')->with('success', 'Famille produit supprimée avec succès.');

        }
      
        // return response()->json(['url'=>url('/categproduits')]);
    }
}
