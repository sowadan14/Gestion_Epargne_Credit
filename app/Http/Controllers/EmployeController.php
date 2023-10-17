<?php

namespace App\Http\Controllers;

use App\Models\Employe;
use App\Models\Poste;
use App\Models\Sexe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EmployeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // setlocale (LC_TIME, "fr_FR.utf8");

    function __construct()
    {
         $this->middleware('permission:listemploye|createemploye|editemploye|deleteemploye', ['only' => ['index','show']]);
         $this->middleware('permission:createemploye', ['only' => ['create','store']]);
         $this->middleware('permission:editemploye', ['only' => ['edit','update']]);
         $this->middleware('permission:deleteemploye', ['only' => ['destroy']]);
    }

    public function index()
    {
       
            $data = Employe::orderBy('id', 'DESC')->where('Supprimer', false)->where('EntrepriseId',Auth::user()->EntrepriseId)->get();       
            $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/employes\">Employes</a></li>";
        
        return view('config.employes.index', compact('data'))->with('Titre','Employés')->with('Breadcrumb',$Breadcrumb) ;
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

        $postes= Poste::where('Supprimer',false)->where('EntrepriseId',Auth::user()->EntrepriseId)->get();
        $sexes=  Sexe::distinct()->get();
        $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/config/employes\">Employés</a></li>";
        
        return view('config.employes.create',compact('postes','sexes'))->with('Titre','Employés')->with('Breadcrumb',$Breadcrumb) ;
        
       
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
         Rule::unique('employes')->ignore($request->id, 'id')->where(function ($query) {
                $query->where('EntrepriseId', Auth::user()->EntrepriseId);
            }),],
                'Sexe' => 'required',
                'PosteId' => 'required',
                // 'Email' => 'required|email|unique:employes,email,'.$request->id,
                'Email' => ['required','email',
                // Rule::unique('roles', 'Nom')->where('EntrepriseId','<>',Auth::user()->EntrepriseId)->ignore($request->id),
             Rule::unique('employes')->ignore($request->id, 'id')->where(function ($query) {
                    $query->where('EntrepriseId', Auth::user()->EntrepriseId);
                }),],
                'Telephone' => 'required',
                'DateNaissance' => 'required|date_format:"d/m/Y"',
            ],
            [
                'Nom.required' => 'Le champ Nom est obligatoire.',
                'Nom.unique' => 'Cet employé existe déjà.',
                'Sexe.required' => 'Le champ Sexe est obligatoire.',
                'PosteId.required' => 'Le champ Poste est obligatoire.',
                'Email.required' => 'Le champ Adresse éléctronique est obligatoire.',
                'Email.email' => 'Cette adresse éléctronique est incorrecte.',
                'Email.unique' => 'Cette adresse éléctronique  est déjà utilisée.',
                'Telephone.required' => 'Le champ Téléphone est obligatoire.',
                'DateNaissance.required' => 'Le champ Date naissance est obligatoire.',
                'DateNaissance.date_format' => 'Le format de Date naissance est incorrect (dd/mm/yyyy).',               
            ]);
           

            Employe::updateOrCreate(['id' => $request->id],
            ['Nom' => $request->Nom, 
            'Email' => $request->Email, 
            'Telephone' => $request->Telephone, 
            'Adresse' => $request->Adresse, 
            'Sexe' => $request->Sexe, 
            'Status' => $request->Status == 'on' ? 1 : 0, 
            'Pays' => $request->Pays, 
            'Ville' => $request->Ville, 
            'Fax' => $request->Fax, 
            'Code' => $request->Code, 
            'CodePostal' => $request->CodePostal, 
            'PosteId' => $request->PosteId,
            'DateNaissance' => date('Y-m-d' , strtotime(str_replace('/', '-', $request->DateNaissance))),
           'EntrepriseId' => Auth::user()->EntrepriseId, 'Create_user' => Auth::user()->id,
            // 'DateCreation' => Carbon::now()
        ]); 
       
        return redirect()->route('employes.index')
        ->with('success','Employé créé avec succès');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employe  $employe
     * @return \Illuminate\Http\Response
     */
    public function show($employe_id)
    {
          //  $sexes=  Sexe::distinct()->get();
         $employe = Employe::find($employe_id);
         $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/config/employes\">Employés</a></li>";
         return view('config.employes.show',compact('employe'))->with('Titre','Employés')->with('Breadcrumb',$Breadcrumb) ;
 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employe  $employe
     * @return \Illuminate\Http\Response
     */
    public function edit($employe_id)
    {
        //
        $postes= Poste::where('Supprimer',false)->where('EntrepriseId',Auth::user()->EntrepriseId)->get();
        $sexes=  Sexe::distinct()->get();
        $employe = Employe::find($employe_id);
        $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/config/employes\">Employés</a></li>";
        return view('config.employes.edit',compact('employe','sexes','postes'))->with('Titre','Employés')->with('Breadcrumb',$Breadcrumb) ;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employe  $employe
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employe $employe)
    {
        //
        $this->validate($request, [
            'Nom' => ['required',
            // Rule::unique('roles', 'Nom')->where('EntrepriseId','<>',Auth::user()->EntrepriseId)->ignore($request->id),
         Rule::unique('employes')->ignore($request->id, 'id')->where(function ($query) {
                $query->where('EntrepriseId', Auth::user()->EntrepriseId);
            }),],
            'Sexe' => 'required',
            'PosteId' => 'required',
            'Email' => ['required','email',
                // Rule::unique('roles', 'Nom')->where('EntrepriseId','<>',Auth::user()->EntrepriseId)->ignore($request->id),
             Rule::unique('employes')->ignore($request->id, 'id')->where(function ($query) {
                    $query->where('EntrepriseId', Auth::user()->EntrepriseId);
                }),],
            'Telephone' => 'required',
            'DateNaissance' => 'required|date_format:"d/m/Y"',
        ],
        [
            'Nom.required' => 'Le champ Nom est obligatoire.',
            'Nom.unique' => 'Cet employé existe déjà.',
            'Sexe.required' => 'Le champ Sexe est obligatoire.',
            'PosteId.required' => 'Le champ Poste est obligatoire.',
            'Email.required' => 'Le champ Adresse éléctronique est obligatoire.',
                'Email.email' => 'Cette adresse éléctronique est incorrecte.',
                'Email.unique' => 'Cette adresse éléctronique  est déjà utilisée.',
            'Telephone.required' => 'Le champ Téléphone est obligatoire.',
            'DateNaissance.required' => 'Le champ Date naissance est obligatoire.',
            'DateNaissance.date_format' => 'Le format de Date naissance est incorrect (dd/mm/yyyy).',               
        ]);
       
        $employe = employe::find($request->id);

        $employe->Nom =$request->Nom;
        $employe->Email=$request->Email; 
        $employe->Telephone=$request->Telephone; 
        $employe->Adresse=$request->Adresse; 
        $employe->Sexe=$request->Sexe;
        $employe->Status=$request->Status == 'on' ? 1 : 0; 
        $employe->Pays=$request->Pays;
        $employe->Ville=$request->Ville; 
        $employe->Fax=$request->Fax; 
        $employe->Code=$request->Code;
        $employe->CodePostal=$request->CodePostal;
        $employe->PosteId=$request->PosteId;
        $employe->DateNaissance=date('Y-m-d' , strtotime(str_replace('/', '-', $request->DateNaissance)));
        $employe->Edit_user= Auth::user()->id;
        $employe->save();
    
        return redirect()->route('employes.index')
                        ->with('success','Employé modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employe  $employe
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $employes= DB::table('employes')->where('PosteId',$request->id)->get();
        if(count(DB::table('users')->where('EmployeId',$id)->get())>0)
        {
            return redirect('/config/employes')->with('danger', "Cet employé ne peut être supprimé car elle est utilisée par d'autres données.");
      
        }
        else
        {
            $employe = Employe::find($id);
            $employe->Supprimer =true;
            $employe->Delete_user= Auth::user()->id;
            $employe->save();
            return redirect('/config/employes')->with('success', 'Employé supprimé avec succès.');
        }
    }
}