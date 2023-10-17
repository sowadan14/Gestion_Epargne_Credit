<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use App\Models\Poste;
use App\Models\Employe;
use App\Models\Sexe;
use App\Models\Font;
use App\Models\Couleur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


class ParamsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // setlocale (LC_TIME, "fr_FR.utf8");

    // function __construct()
    // {
    //      $this->middleware('permission:editparam', ['only' => ['index','show']]);
    // }

    public function index()
    {
        $couleurs=  Couleur::distinct()->get();
        $fonts=  Font::distinct()->get();
        $param = Entreprise::where('Supprimer',false)->where('id',Auth::user()->EntrepriseId)->firstOr(function () {
            return Entreprise::where('Supprimer',false)->where('id',Auth::user()->EntrepriseId)->first();
        });  
       
             $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/config/params\">Paramètres</a></li>";
        
        return view('config.params.index', compact('param','couleurs','fonts'))->with('Titre','Paramètres')->with('Breadcrumb',$Breadcrumb) ;
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
        $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/postes\">Postes</a></li>";
        
        return view('config.params.create',compact('postes','sexes'))->with('Titre','Postes')->with('Breadcrumb',$Breadcrumb) ;
        
       
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
                'Nom' => 'required',
                'Sexe' => 'required',
                'PosteId' => 'required',
                'Email' => 'required|email|unique:params,email,'.$request->id,
                'Telephone' => 'required',
                'DateNaissance' => 'required|date_format:"d/m/Y"',
            ],
            [
                'Nom.required' => 'Le champ Nom est obligatoire.',
                'Sexe.required' => 'Le champ Sexe est obligatoire.',
                'PosteId.required' => 'Le champ Poste est obligatoire.',
                'Email.required' => 'Le champ Email est obligatoire.',
                'Email.email' => 'Email  incorrect.',
                'Email.unique' => 'Email  est déjà utilisé.',
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
            'DateCreation' => Carbon::now()]); 
       
        return redirect()->route('params.index')
        ->with('success','Employé créé avec succès');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employe  $param
     * @return \Illuminate\Http\Response
     */
    public function show($param_id)
    {
          //  $sexes=  Sexe::distinct()->get();
         $param = Employe::find($param_id);
         $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/config/params\">Employés</a></li>";
         return view('config.params.show',compact('param'))->with('Titre','Employés')->with('Breadcrumb',$Breadcrumb) ;
 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employe  $param
     * @return \Illuminate\Http\Response
     */
    public function edit($param_id)
    {
        //
        $postes= Poste::where('Supprimer',false)->where('EntrepriseId',Auth::user()->EntrepriseId)->get();
        $sexes=  Sexe::distinct()->get();
        $param = Employe::find($param_id);
        $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/config/params\">Employés</a></li>";
        return view('config.params.edit',compact('param','sexes','postes'))->with('Titre','Employés')->with('Breadcrumb',$Breadcrumb) ;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employe  $param
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        // dd($request->id);
        $this->validate($request, [
            'Nom' => 'required',
            'NomReduit' => 'required',
            'Email' => ['required','email'],
            'EmailNotification' => 'email',
            'Telephone' => 'required',
            'Taille' => ['required','numeric'],
            'Police' => 'required',
            'ColorEntete' => 'required',
            'ColorSidebar' => 'required',
        ],
        [
            'Nom.required' => 'Le champ Nom est obligatoire.',
            'NomReduit.required' => 'Le champ Nom réduit est obligatoire.',
            'Email.required' => 'Le champ Adresse éléctronique est obligatoire.',
            'Email.email' => 'Adresse éléctronique  incorrecte.',
            'EmailNotification.email' => 'Email de notification  incorrect.',
            'Telephone.required' => 'Le champ Téléphone est obligatoire.',
            'Taille.required' => 'Le champ Taille est obligatoire.',
            'Taille.numeric' => 'Le champ Taille doit être un nombre.',
            'Police.required' => 'Le champ Police est obligatoire.',
            'ColorEntete.required' => 'Veuillez séléctionner au moins une couleur entête.',
            'ColorSidebar.required' => 'Veuillez séléctionner au moins une couleur menu.',      
        ]);
       
        $param = Entreprise::find($request->id);

        $fileName = '';

        if ($request->hasFile( 'LogoEntreprise' ) ) {
            $file = $request->file( 'LogoEntreprise' ) ;
            $fileName = Auth::user()->EntrepriseId.''.time().'.'.$file->extension();
            $path= "images/".$fileName;
            Storage::disk('public')->put($path, file_get_contents($file));

            if(Storage::exists("public/images/".$param->LogoEntreprise)){
                Storage::delete("public/images/".$param->LogoEntreprise);
            }
        } else {
            $fileName = $param->LogoEntreprise;
        }

        $param->Code =$request->Code;
        $param->Nom =$request->Nom;
        $param->NomReduit =$request->NomReduit;
        $param->Email=$request->Email; 
        $param->Telephone=$request->Telephone; 
        $param->Adresse=$request->Adresse; 
        $param->Taille=$request->Taille;
        $param->LogoEntreprise=$fileName;
        $param->Police=$request->Police;
        $param->Pays = $request->Pays;
        $param->Ville = $request->Ville;
        $param->Fax = $request->Fax; 
        $param->CodePostal = $request->CodePostal; 
        $param->ColorEntete=$request->ColorEntete;
        $param->ColorSidebar=$request->ColorSidebar;
        $param->EmailNotification=$request->EmailNotification;
        $param->PasswordNotification=$request->PasswordNotification;
        $param->save();

        // $entreprise = Entreprise::find($param->EntrepriseId);
        // $entreprise->Libelle =$request->Nom;
        // $entreprise->Email=$request->Email; 
        // $entreprise->Telephone=$request->Telephone; 
        // $entreprise->Adresse=$request->Adresse; 
        // $entreprise->save();


    
        return redirect()->route('params.index')
                        ->with('success','Paramètres mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employe  $param
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $params= DB::table('params')->where('PosteId',$request->id)->get();
        if(count(DB::table('users')->where('EmployeId',$id)->get())>0)
        {
            return redirect('/config/params')->with('danger', "Cet employé ne peut être supprimé car elle est utilisée par d'autres données.");
      
        }
        else
        {
            // Employe::where('id',$id)->delete();
            $employe = Employe::find($id);
            $employe->Supprimer =true;
            $employe->save();
            return redirect('/config/params')->with('success', 'Employé supprimé avec succès.');
        }
    }
}
