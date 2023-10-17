<?php

namespace App\Http\Controllers;

use App\Models\AvoirFr;
use App\Models\Fournisseur;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class FournisseurController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:listfournisseur|createfournisseur|editfournisseur|deletefournisseur', ['only' => ['index','show']]);
         $this->middleware('permission:createfournisseur', ['only' => ['create','store']]);
         $this->middleware('permission:editfournisseur', ['only' => ['edit','update']]);
         $this->middleware('permission:deletefournisseur', ['only' => ['destroy']]);
    }

    public function index()
    {
       
            $data = Fournisseur::orderBy('id', 'DESC')->where('Supprimer', false)->where('EntrepriseId',Auth::user()->EntrepriseId)->get();       
            $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"/frs\">Fournisseurs</a></li>";
        
        return view('frs.index', compact('data'))->with('Titre','Fournisseurs')->with('Breadcrumb',$Breadcrumb) ;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
        $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"/frs\">Fournisseurs</a></li>";
        
        return view('frs.create')->with('Titre','Fournisseurs')->with('Breadcrumb',$Breadcrumb) ;

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
            // Rule::unique('roles', 'Nom')->where('EntrepriseId','<>',Auth::user()->EntrepriseId)->ignore($request->id),
         Rule::unique('fournisseurs')->ignore($request->id, 'id')->where(function ($query) {
                $query->where('EntrepriseId', Auth::user()->EntrepriseId);
            }),],
            'Email' => ['required','email',
                // Rule::unique('roles', 'Nom')->where('EntrepriseId','<>',Auth::user()->EntrepriseId)->ignore($request->id),
             Rule::unique('fournisseurs')->ignore($request->id, 'id')->where(function ($query) {
                    $query->where('EntrepriseId', Auth::user()->EntrepriseId);
                }),],
            'Telephone' => 'required',
        ],
        [
            'Nom.required' => 'Le champ Nom est obligatoire.',
            'Nom.unique' => 'Ce fournisseur existe déjà.',
            'Email.required' => 'Le champ Adresse éléctronique est obligatoire.',
            'Email.email' => 'Adresse éléctronique  incorrecte.',
            'Email.unique' => 'Cette adresse éléctronique  est déjà utilisée.',
            'Telephone.required' => 'Le champ Téléphone est obligatoire.',             
        ]);

        // dd($request);

       $fournisseur= Fournisseur::updateOrCreate(['id' => $request->id],
            ['Nom' => $request->Nom, 
            'Email' => $request->Email, 
            'Telephone' => $request->Telephone, 
            'Adresse' => $request->Adresse, 
            'Status' => $request->Status == 'on' ? 1 : 0, 
            'Pays' => $request->Pays, 
            'Ville' => $request->Ville, 
            'Fax' => $request->Fax, 
            'Code' => $request->Code, 
            'CodePostal' => $request->CodePostal, 
           'EntrepriseId' => Auth::user()->EntrepriseId, 'Create_user' => Auth::user()->id,
        ]); 


        $avoirfr= New AvoirFr();
        $avoirfr->FournisseurId=$fournisseur->id;
        $avoirfr->Montant=0;
        $avoirfr->EntrepriseId = Auth::user()->EntrepriseId;
        $avoirfr->Create_user = Auth::user()->id;
        $avoirfr->save();
       
        return redirect()->route('frs.index')
        ->with('success','Fournisseur créé avec succès');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Fournisseur  $fournisseur
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $frs = Fournisseur::find($id);
        $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"/frs\">Fournisseurs</a></li>";
        return view('frs.show',compact('frs'))->with('Titre','Fournisseurs')->with('Breadcrumb',$Breadcrumb);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Fournisseur  $fournisseur
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $frs = Fournisseur::find($id);
        $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"/frs\">Fournisseurs</a></li>";
        return view('frs.edit',compact('frs'))->with('Titre','Fournisseurs')->with('Breadcrumb',$Breadcrumb);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fournisseur  $fournisseur
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'Nom' => ['required',
            // Rule::unique('roles', 'Nom')->where('EntrepriseId','<>',Auth::user()->EntrepriseId)->ignore($request->id),
         Rule::unique('fournisseurs')->ignore($request->id, 'id')->where(function ($query) {
                $query->where('EntrepriseId', Auth::user()->EntrepriseId);
            }),],
            'Email' => ['required','email',
                // Rule::unique('roles', 'Nom')->where('EntrepriseId','<>',Auth::user()->EntrepriseId)->ignore($request->id),
             Rule::unique('fournisseurs')->ignore($request->id, 'id')->where(function ($query) {
                    $query->where('EntrepriseId', Auth::user()->EntrepriseId);
                }),],
            'Telephone' => 'required',
        ],
        [
            'Nom.required' => 'Le champ Nom est obligatoire.',
            'Nom.unique' => 'Ce fournisseur existe déjà.',
            'Email.required' => 'Le champ Adresse éléctronique est obligatoire.',
            'Email.email' => 'Adresse éléctronique  incorrecte.',
            'Email.unique' => 'Cette adresse éléctronique  est déjà utilisée.',
            'Telephone.required' => 'Le champ Téléphone est obligatoire.',             
        ]);

        $fournisseur = Fournisseur::find($id);

        $fournisseur->Nom =$request->Nom;
        $fournisseur->Email=$request->Email; 
        $fournisseur->Telephone=$request->Telephone; 
        $fournisseur->Adresse=$request->Adresse; 
        $fournisseur->Status=$request->Status == 'on' ? 1 : 0; 
        $fournisseur->Pays=$request->Pays;
        $fournisseur->Ville=$request->Ville; 
        $fournisseur->Fax=$request->Fax; 
        $fournisseur->Code=$request->Code;
        $fournisseur->CodePostal=$request->CodePostal;
        $fournisseur->Edit_user= Auth::user()->id;
        $fournisseur->save();
    
        return redirect()->route('frs.index')
                        ->with('success','Fournisseur modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fournisseur  $fournisseur
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $frs= DB::table('frs')->where('PosteId',$request->id)->get();
        if(count(DB::table('ventes')->where('FournisseurId',$id)->get())>0)
        {
            return redirect('/frs')->with('danger', "Ce fournisseur ne peut être supprimé car elle est utilisée par d'autres données.");
        }
        else
        {
            $fournisseur = Fournisseur::find($id);
            $fournisseur->Supprimer =true;
            $fournisseur->Delete_user= Auth::user()->id;
            $fournisseur->save();

            $avoirfr = AvoirFr::where('FournisseurId', $fournisseur->id)->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->first();
            if ($avoirfr != null) {
                $avoirfr = AvoirFr::find($avoirfr->id);
                $avoirfr->Supprimer =true;
                $avoirfr->Delete_user= Auth::user()->id;
                $avoirfr->save();
            }

            return redirect('/frs')->with('success', 'Fournisseur supprimé avec succès.');
        }
    }
}
