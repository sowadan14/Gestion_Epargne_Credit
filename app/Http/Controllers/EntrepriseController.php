<?php

namespace App\Http\Controllers;

use App\Models\Achat;
use App\Models\CategProduit;
use App\Models\Vente;
use App\Models\ModePaiement;
use App\Models\Entreprise;
use App\Models\Sexe;
use App\Models\Employe;
use App\Models\User;
use App\Models\Poste;
use App\Models\Produit;
use App\Models\Client;
use App\Models\Compte;
use App\Models\Depense;
use App\Models\DetailsVente;
use App\Models\Fournisseur;
use App\Models\TypeDepense;
use App\Models\TypeProduit;
use App\Models\Unite;
use App\Models\UniteProduit;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

use DataTables;

class EntrepriseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
        $this->middleware('permission:listentreprise|createentreprise|editentreprise|deleteentreprise', ['only' => ['index', 'show']]);
        $this->middleware('permission:createentreprise', ['only' => ['create', 'store']]);
        $this->middleware('permission:editentreprise', ['only' => ['edit', 'update']]);
        $this->middleware('permission:deleteentreprise', ['only' => ['destroy']]);
    }

    public function index()
    {

        $data = Entreprise::orderBy('id', 'DESC')->where('Supprimer', false)->get();

        $Breadcrumb = '<li class=\'breadcrumb-item\'><a href=\'/entreprises\'>Entreprises</a></li>';

        return view('entreprises.index', compact('data'))->with('Titre', 'Entreprises')->with('Breadcrumb', $Breadcrumb);
        // 'Breadcrumb'=> $Breadcrumb, // add as much as you want
        // ] );

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $MyrolePermissions = DB::table('role_has_permissions')->get();
        $permissions = Permission::distinct()->get();

        $numParents = Permission::wherein('id', $MyrolePermissions->pluck('permission_id'))
            ->orderBy('id', 'ASC')
            ->select('NumParent')
            ->groupBy('NumParent')
            ->get()
            ->pluck('NumParent');
        // User::select( 'id', DB::raw( "CONCAT(users.first_name,' ',users.last_name) as full_name" ) )
        // ->pluck( 'full_name', 'id' );
        $sexes =  Sexe::distinct()->get();
        // $html = view( 'entreprises.create', compact( 'permissions', 'numParents', 'sexes' ) )->render();
        $Breadcrumb = '<li class=\'breadcrumb-item\'><a href=\'/entreprises\'>Entreprises</a></li>';

        return view('entreprises.create', compact('permissions', 'numParents', 'sexes'))->with('Titre', 'Entreprises')->with('Breadcrumb', $Breadcrumb);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {

        $this->validate(
            $request,
            [
                'Nom' => 'required',
                // 'prenoms' => 'required',
                'sexe' => 'required',
                'email' => 'required|email',
                'telephone' => 'required',
                'dateNaissance' => 'required|date_format:"d/m/Y"',
                'nomReduit' => 'required',
                'nomEntreprise' => 'required',
                'emailEntreprise' => 'required|email',
                'telephoneEntreprise' => 'required',
                'password' => 'required|min:4',
                'confirmpassword' => 'required|same:password|min:4',
                'role' => 'required',
                'permission' => 'required',

            ],
            [
                'Nom.required' => 'Le champ Nom est obligatoire.',
                // 'prenoms.required' => 'Le champ Prénoms est obligatoire.',
                'sexe.required' => 'Le champ Sexe est obligatoire.',
                'email.required' => 'Le champ Email dirigeant est obligatoire.',
                'email.email' => 'Email dirigeant incorrect.',
                'email.unique' => 'Email dirigeant est déjà utilisé.',
                'telephone.required' => 'Le champ Téléphone dirigeant est obligatoire.',
                'dateNaissance.required' => 'Le champ Date naissance est obligatoire.',
                'dateNaissance.date_format' => 'Le format de Date naissance est incorrect (dd/mm/yyyy).',
                'nomEntreprise.required' => 'Le champ Nom entreprise est obligatoire.',
                'nomReduit.required' => 'Le champ Nom réduit est obligatoire.',
                'emailEntreprise.required' => 'Le champ Email entreprise est obligatoire.',
                'emailEntreprise.email' => 'Email entreprise est incorrect.',
                'emailEntreprise.unique' => 'Email entreprise est déjà utilisé.',
                'telephoneEntreprise.required' => 'Le champ Téléphone entreprise est obligatoire.',

                'password.required' => 'Le champ Mot de passe est obligatoire.',
                'password.min' => 'Le champ Mot de passe doit avoir au moins 4 caractères.',
                'confirmpassword.required' => 'Le champ Confirmation est obligatoire.',
                'confirmpassword.min' => 'Le champ Confirmation doit avoir au moins 4 caractères.',
                'confirmpassword.same' => 'Les champs Mot de passe et  Confirmation doivent être conformes.',

                'role.required' => 'Le champ Rôle est obligatoire.',
                'permission.required' => 'Veuillez choisir au moins une permission.',

            ]
        );

        $entreprise = Entreprise::create([
            'Nom' => $request->nomEntreprise,
            'NomReduit' => $request->nomReduit,
            'Email' => $request->emailEntreprise,
            'Telephone' => $request->telephoneEntreprise,
            'Adresse' => $request->adresseEntreprise,
            'Taille' => '14',
            'Police' => 'CoolJazz',
            'ColorEntete' => '#1D2B36',
            'ColorSidebar' => '#FFF',
            'Police' => 'CoolJazz',
            'Pays' => $request->paysEntreprise,
            'Ville' => $request->villeEntreprise,
            'Fax' => $request->faxEntreprise,
            'CodePostal' => $request->codePostalEntreprise,
        ]);

        $poste = new Poste;
        $poste->Libelle = 'Directeur général';
        $poste->DateCreation = Carbon::now();
        $poste->EntrepriseId = $entreprise->id;
        $poste->Create_user= Auth::user()->id;
        $poste->Status = '1';
        $poste->save();

        $client = new Client;
        $client->Nom = 'Autre client';
        $client->Email = 'autreclient@gmail.com';
        $client->Telephone = '+22811111111';
        $client->Adresse = '';
        $client->Status = '1';
        // $client->DateCreation = Carbon::now();
        // $client->DateNaissance = Carbon::now();
        $client->EntrepriseId = $entreprise->id;
        $client->Create_user= Auth::user()->id;
        $client->save();

        $fr = new Fournisseur;
        $fr->Nom = 'Autre frs';
        $fr->Email = 'autrefrs@gmail.com';
        $fr->Telephone = '';
        $fr->Adresse = '';
        $fr->Status = '1';
        //  $fr->DateCreation = Carbon::now();
        $fr->EntrepriseId = $entreprise->id;
        $fr->Create_user= Auth::user()->id;
        $fr->save();

        $typeproduit = new TypeProduit;
        $typeproduit->Nom = 'Stockable';
        $typeproduit->EntrepriseId = $entreprise->id;
        $typeproduit->Create_user= Auth::user()->id;
        $typeproduit->Status = '1';
        $typeproduit->save();

        $unite = new Unite;
        $unite->Nom = 'Boîte';
        $unite->EntrepriseId = $entreprise->id;
        $unite->Create_user= Auth::user()->id;
        $unite->Status = '1';
        $unite->save();

        $modepaiment1 = new ModePaiement();
        $modepaiment1->Nom = 'Espèce';
        $modepaiment1->EntrepriseId = $entreprise->id;
        $modepaiment1->Create_user= Auth::user()->id;
        $modepaiment1->Status = '1';
        $modepaiment1->save();

        $modepaiment2 = new ModePaiement;
        $modepaiment2->Nom = 'Chèques';
        $modepaiment2->EntrepriseId = $entreprise->id;
        $modepaiment2->Create_user= Auth::user()->id;
        $modepaiment2->Status = '1';
        $modepaiment2->save();

        $categproduit = new CategProduit();
        $categproduit->Nom = 'Générale';
        $categproduit->EntrepriseId = $entreprise->id;
        $categproduit->Create_user= Auth::user()->id;
        $categproduit->Status = '1';
        $categproduit->save();


        $produit = new Produit;
        $produit->Libelle = 'Autre produit';
        $produit->Prod_Logo = '';
        $produit->Qte = 0;
        $produit->PU = 0;
        $produit->TypeProduitId = $typeproduit->id;
        $produit->CategProduitId = $categproduit->id;
        $produit->UniteId = $unite->id;
        // $produit->DateCreation = Carbon::now();
        $produit->EntrepriseId = $entreprise->id;
        $produit->Create_user= Auth::user()->id;
        $produit->Status = '1';
        $produit->save();


        $uniteproduit = new UniteProduit;
        $uniteproduit->ProduitId = $produit->id;
        $uniteproduit->UniteId = $unite->id;
        $uniteproduit->PrixVente = '300';
        $uniteproduit->PrixAchat = '250';
        $uniteproduit->Qte = '1';
        $uniteproduit->save();

        //Compte
        $compte = new Compte;
        $compte->Libelle = 'Caisse principale';
        $compte->Solde = 0;
        $compte->SoldeInitial = 0;
        // $compte->DateCreation = Carbon::now();
        $compte->EntrepriseId = $entreprise->id;
        $compte->Create_user= Auth::user()->id;
        $compte->Status = '1';
        $compte->save();

        //employe
        $employe = new Employe;
        $employe->Nom = $request->Nom;
        $employe->Email = $request->email;
        $employe->Sexe = $request->sexe;
        $employe->Telephone = $request->telephone;
        $employe->adresse = $request->adresse;
        $employe->Pays = $request->paysDirigeant;
        $employe->Ville = $request->villeDirigeant;
        $employe->Fax = $request->faxDirigeant;
        $employe->codePostal = $request->codePostalDirigeant;
        $employe->PosteId = $poste->id;
        $employe->DateNaissance = date('Y-m-d', strtotime(str_replace('/', '-', $request->dateNaissance)));
        // $employe->DateCreation = Carbon::now();
        $employe->EntrepriseId = $entreprise->id;
        $employe->Create_user= Auth::user()->id;
        $employe->Status = '1';
        $employe->save();

        //utilisateur
        $user = new User;
        $user->email = $request->email;
        $user->SuperAdmin = false;
        $user->password = Hash::make($request->password);
        $user->EmployeId = $employe->id;
        $user->ImageUser = '';
        // $user->DateCreation = Carbon::now();
        $user->EntrepriseId = $entreprise->id;
        $user->Create_user= Auth::user()->id;
        $user->Status = '1';
        $user->save();

        $role = new Role;
        $role->Nom = $request->role;
        $role->EntrepriseId = $entreprise->id;
        $role->Create_user= Auth::user()->id;
        $role->save();

        $role->syncPermissions($request->input('permission'));

        $user->assignRole([$role->id]);

        // $request->session()->flash( 'success', 'Entreprise créée avec succès.' );


        return redirect()->route('entreprises.index')
            ->with('success', 'Entreprise créée avec succès');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Entreprise  $entreprise
     * @return \Illuminate\Http\Response
     */

    public function show($entreprise_id)
    {
        //
        $entreprise = Entreprise::find($entreprise_id);
        $Breadcrumb = '<li class=\'breadcrumb-item\'><a href=\'/entreprises\'>Entreprises</a></li>';
        return view('entreprises.show', compact('entreprise'))->with('Titre', 'Entreprises')->with('Breadcrumb', $Breadcrumb);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Entreprise  $entreprise
     * @return \Illuminate\Http\Response
     */

    public function edit($entreprise_id)
    {
        //
        $entreprise = Entreprise::find($entreprise_id);
        $Breadcrumb = '<li class=\'breadcrumb-item\'><a href=\'/entreprises\'>Entreprises</a></li>';

        return view('entreprises.edit', compact('entreprise'))->with('Titre', 'Entreprises')->with('Breadcrumb', $Breadcrumb);

        // $html = view( 'entreprises.edit', compact( 'entreprise' ) )->render();
        // return response()->json( array( 'success' => true, 'html'=>$html ) );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Entreprise  $entreprise
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        //
        $this->validate(
            $request,
            [
                'Nom' => 'required',
                'NomReduit' => 'required',
                'Email' => 'required|email',
                'Telephone' => 'required',


            ],
            [
                'Nom.required' => 'Le champ Nom est obligatoire.',

                'NomReduit.required' => 'Le champ Nom Réduit est obligatoire.',

                'Email.required' => 'Le champ Adresse éléctronique est obligatoire.',
                'Email.email' => 'Adresse éléctronique incorrecte.',
                'Telephone.required' => 'Le champ Téléphone  est obligatoire.',
            ]
        );

        $entreprise = Entreprise::find($id);
        $entreprise->Code = $request->Code;
        $entreprise->Nom = $request->Nom;
        $entreprise->NomReduit = $request->NomReduit;
        $entreprise->Email = $request->Email;
        $entreprise->Telephone = $request->Telephone;
        $entreprise->Fax = $request->Fax;
        $entreprise->Pays = $request->Pays;
        $entreprise->Ville = $request->Ville;
        $entreprise->CodePostal = $request->CodePostal;
        $entreprise->Adresse = $request->Adresse;
        $entreprise->Edit_user= Auth::user()->id;
        // $entreprise->Status=$request->Status == 'on' ? 1 : 0; 
        $entreprise->save();



        return redirect()->route('entreprises.index')
            ->with('success', 'Entreprise modifiée avec succès');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Entreprise  $entreprise
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {


        foreach (Vente::latest()->where('Supprimer', false)->where('EntrepriseId', $id) as $item) {


            $vente = Vente::find($item['id']);
            $vente->Supprimer = true;
            $vente->Delete_user= Auth::user()->id;
            $vente->save();
            // Code Here
        }


        foreach (Achat::latest()->where('Supprimer', false)->where('EntrepriseId', $id) as $item) {


            $achat = Achat::find($item['id']);
            $achat->Supprimer = true;
            $achat->Delete_user= Auth::user()->id;
            $achat->save();
            // Code Here
        }

        foreach (Depense::latest()->where('Supprimer', false)->where('EntrepriseId', $id) as $item) {


            $depense = Depense::find($item['id']);
            $depense->Supprimer = true;
            $depense->Delete_user= Auth::user()->id;
            $depense->save();
            // Code Here
        }

        foreach (TypeDepense::latest()->where('Supprimer', false)->where('EntrepriseId', $id) as $item) {


            $typedepense = TypeDepense::find($item['id']);
            $typedepense->Supprimer = true;
            $typedepense->Delete_user= Auth::user()->id;
            $typedepense->save();
            // Code Here
        }
// $produits=Produit::all()->where('Supprimer', false)->where('EntrepriseId', $id);
// dd($produits);
        foreach (Produit::all()->where('Supprimer', false)->where('EntrepriseId', $id) as $produit) {

            $produit = Produit::find($produit['id']);            
            $produit->Supprimer = true;
            $produit->Delete_user= Auth::user()->id;
            $produit->save();
            // Code Here
        }

        foreach (Unite::all()->where('Supprimer', false)->where('EntrepriseId', $id) as $item) {


            $unite = Unite::find($item['id']);
            $unite->Supprimer = true;
            $unite->Delete_user= Auth::user()->id;
            $unite->save();
            // Code Here
        }

        foreach (CategProduit::all()->where('Supprimer', false)->where('EntrepriseId', $id) as $item) {


            $categproduit = CategProduit::find($item['id']);
            $categproduit->Supprimer = true;
            $categproduit->Delete_user= Auth::user()->id;
            $categproduit->save();
            // Code Here
        }

        foreach (Role::all()->where('Supprimer', false)->where('EntrepriseId', $id) as $item) {


            $role = Role::find($item['id']);
            $role->Supprimer = true;
            $role->Delete_user= Auth::user()->id;
            $role->save();
            // Code Here
        }

        foreach (User::all()->where('Supprimer', false)->where('EntrepriseId', $id) as $item) {


            $user = User::find($item['id']);
            $user->Supprimer = true;
            $user->Delete_user= Auth::user()->id;
            $user->save();
            // Code Here
        }

        foreach (TypeProduit::all()->where('Supprimer', false)->where('EntrepriseId', $id) as $item) {


            $typeproduit = TypeProduit::find($item['id']);
            $typeproduit->Supprimer = true;
            $typeproduit->Delete_user= Auth::user()->id;
            $typeproduit->save();
            // Code Here
        }


        foreach (Poste::all()->where('Supprimer', false)->where('EntrepriseId', $id) as $item) {


            $poste = Poste::find($item['id']);
            $poste->Supprimer = true;
            $poste->Delete_user= Auth::user()->id;
            $poste->save();
            // Code Here
        }

        foreach (ModePaiement::all()->where('Supprimer', false)->where('EntrepriseId', $id) as $item) {


            $modepaiement = ModePaiement::find($item['id']);
            $modepaiement->Supprimer = true;
            $modepaiement->Delete_user= Auth::user()->id;
            $modepaiement->save();
            // Code Here
        }

        foreach (Fournisseur::all()->where('Supprimer', false)->where('EntrepriseId', $id) as $item) {


            $fournisseur = Fournisseur::find($item['id']);
            $fournisseur->Supprimer = true;
            $fournisseur->Delete_user= Auth::user()->id;
            $fournisseur->save();
            // Code Here
        }

        foreach (Compte::all()->where('Supprimer', false)->where('EntrepriseId', $id) as $item) {


            $compte = Compte::find($item['id']);
            $compte->Supprimer = true;
            $compte->Delete_user= Auth::user()->id;
            $compte->save();
            // Code Here
        }

        foreach (Client::all()->where('Supprimer', false)->where('EntrepriseId', $id) as $item) {


            $client = Client::find($item['id']);
            $client->Supprimer = true;
            $client->Delete_user= Auth::user()->id;
            $client->save();
            // Code Here
        }

        $entreprise = Entreprise::find( $id );
          $entreprise->Supprimer = true;
          $entreprise->Delete_user= Auth::user()->id;
        $entreprise->save();


        return redirect('/entreprises')->with('success', 'Entreprise supprimée avec succès.');

        // $employes = DB::table( 'employes' )->where( 'PosteId', $request->id )->get();
        // if ( count( DB::table( 'achats' )->where( 'EntrepriseId', $id )->get() )>0 ) {
        //     return redirect( '/entreprises' )->with( 'danger', "Cette entreprise ne peut être supprimée car elle est utilisée par d'autres données." );

        // } elseif ( count( DB::table( 'ventes' )->where( 'EntrepriseId', $id )->get() )>0 ) {
        //     return redirect( '/entreprises' )->with( 'danger', "Cette entreprise ne peut être supprimée car elle est utilisée par d'autres données." );

        // } else {
        //     // Entreprise::where( 'id', $request->deleteentrepriseid )->delete();
        //     $entreprise = Entreprise::find( $id );
        //     $entreprise->Supprimer = true;
        //     $entreprise->save();
        //     return redirect( '/entreprises' )->with( 'success', 'Entreprise supprimée avec succès.' );
        // }
    }
}
