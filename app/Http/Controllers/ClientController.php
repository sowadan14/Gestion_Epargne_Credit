<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\AvoirClt;
use App\Models\Sexe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:listclient|createclient|editclient|deleteclient', ['only' => ['index','show']]);
         $this->middleware('permission:createclient', ['only' => ['create','store']]);
         $this->middleware('permission:editclient', ['only' => ['edit','update']]);
         $this->middleware('permission:deleteclient', ['only' => ['destroy']]);
    }

    public function index()
    {
       
            $data = Client::orderBy('id', 'DESC')->where('Supprimer', false)->where('EntrepriseId',Auth::user()->EntrepriseId)->get();       
            $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"/clients\">Clients</a></li>";
        
        return view('clients.index', compact('data'))->with('Titre','Clients')->with('Breadcrumb',$Breadcrumb) ;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
        $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"/clients\">Clients</a></li>";
        
        return view('clients.create')->with('Titre','Clients')->with('Breadcrumb',$Breadcrumb) ;

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
         Rule::unique('clients')->ignore($request->id, 'id')->where(function ($query) {
                $query->where('EntrepriseId', Auth::user()->EntrepriseId);
            }),],
            'Email' => ['required','email',
                // Rule::unique('roles', 'Nom')->where('EntrepriseId','<>',Auth::user()->EntrepriseId)->ignore($request->id),
             Rule::unique('clients')->ignore($request->id, 'id')->where(function ($query) {
                    $query->where('EntrepriseId', Auth::user()->EntrepriseId);
                }),],
            'Telephone' => 'required',
        ],
        [
            'Nom.required' => 'Le champ Nom est obligatoire.',
            'Nom.unique' => 'Ce client existe déjà.',
            'Email.required' => 'Le champ Adresse éléctronique est obligatoire.',
            'Email.email' => 'Adresse éléctronique  incorrecte.',
            'Email.unique' => 'Cette adresse éléctronique  est déjà utilisée.',
            'Telephone.required' => 'Le champ Téléphone est obligatoire.',             
        ]);

        $client= Client::updateOrCreate(['id' => $request->id],
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

        $avoirclt= New AvoirClt();
        $avoirclt->ClientId=$client->id;
        $avoirclt->Montant=0;
        $avoirclt->EntrepriseId = Auth::user()->EntrepriseId;
        $avoirclt->Create_user = Auth::user()->id;
        $avoirclt->save();

        return redirect()->route('clients.index')
        ->with('success','Client créé avec succès');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = Client::find($id);
        $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"/clients\">Clients</a></li>";
        return view('clients.show',compact('client'))->with('Titre','Clients')->with('Breadcrumb',$Breadcrumb);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $client = Client::find($id);
        $Breadcrumb="<li class=\"breadcrumb-item\"><a href=\"/clients\">Clients</a></li>";
        return view('clients.edit',compact('client'))->with('Titre','Clients')->with('Breadcrumb',$Breadcrumb);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'Nom' => ['required',
            // Rule::unique('roles', 'Nom')->where('EntrepriseId','<>',Auth::user()->EntrepriseId)->ignore($request->id),
         Rule::unique('clients')->ignore($request->id, 'id')->where(function ($query) {
                $query->where('EntrepriseId', Auth::user()->EntrepriseId);
            }),],
            'Email' => ['required','email',
                // Rule::unique('roles', 'Nom')->where('EntrepriseId','<>',Auth::user()->EntrepriseId)->ignore($request->id),
             Rule::unique('clients')->ignore($request->id, 'id')->where(function ($query) {
                    $query->where('EntrepriseId', Auth::user()->EntrepriseId);
                }),],
            'Telephone' => 'required',
        ],
        [
            'Nom.required' => 'Le champ Nom est obligatoire.',
            'Nom.unique' => 'Ce client existe déjà.',
            'Email.required' => 'Le champ Adresse éléctronique est obligatoire.',
            'Email.email' => 'Adresse éléctronique  incorrecte.',
            'Email.unique' => 'Cette adresse éléctronique  est déjà utilisée.',
            'Telephone.required' => 'Le champ Téléphone est obligatoire.',             
        ]);

        $client = Client::find($id);

        $client->Nom =$request->Nom;
        $client->Email=$request->Email; 
        $client->Telephone=$request->Telephone; 
        $client->Adresse=$request->Adresse; 
        $client->Status=$request->Status == 'on' ? 1 : 0; 
        $client->Pays=$request->Pays;
        $client->Ville=$request->Ville; 
        $client->Fax=$request->Fax; 
        $client->Code=$request->Code;
        $client->CodePostal=$request->CodePostal;
        $client->Edit_user= Auth::user()->id;
        $client->save();
    
        return redirect()->route('clients.index')
                        ->with('success','Client modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $clients= DB::table('clients')->where('PosteId',$request->id)->get();
        if(count(DB::table('ventes')->where('ClientId',$id)->get())>0)
        {
            return redirect('/clients')->with('danger', "Ce client ne peut être supprimé car elle est utilisée par d'autres données.");
        }
        else
        {
            $client = Client::find($id);
            $client->Supprimer =true;
            $client->Delete_user= Auth::user()->id;
            $client->save();


            $avoirclt = AvoirClt::where('ClientId', $client->id)->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->first();
            if ($avoirclt != null) {
                $avoirclt = AvoirClt::find($avoirclt->id);
                $avoirclt->Supprimer =true;
                $avoirclt->Delete_user= Auth::user()->id;
                $avoirclt->save();
            }

            return redirect('/clients')->with('success', 'Client supprimé avec succès.');
        }
    }
}
