<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Employe;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller {

    function __construct()
    {
        $this->middleware('auth');
         $this->middleware('permission:listuser|createuser|edituser|deleteuser', ['only' => ['index','show']]);
         $this->middleware('permission:createuser', ['only' => ['create','store']]);
         $this->middleware('permission:edituser', ['only' => ['edit','update']]);
         $this->middleware('permission:deleteuser', ['only' => ['destroy']]);
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function index( Request $request ) {
        $data = User::orderBy( 'id', 'DESC' )->where('Supprimer',false)->where( 'EntrepriseId', Auth::user()->EntrepriseId )->get();
        $Breadcrumb = '<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\'breadcrumb-item\'><a href=\'/config/users\'>Utilisateurs</a></li>';

        return view( 'config.users.index', compact( 'data' ) )
        ->with( 'Titre', 'Utilisateurs' )
        ->with( 'Breadcrumb', $Breadcrumb );
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function create() {
        $roles = Role::where( 'EntrepriseId', Auth::user()->EntrepriseId )->pluck( 'Nom', 'Nom' )->all();

        $employes = Employe::where( 'EntrepriseId', Auth::user()->EntrepriseId )->get();
        $Breadcrumb = '<li class=\'breadcrumb-item\'><a href=\'#\'>Configuration</a></li><li class=\'breadcrumb-item\'><a href=\'/config/users\'>Utilisateurs</a></li>';

        return view( 'config.users.create', compact( 'roles', 'employes' ) )->with( 'Titre', 'Utilisateurs' )->with( 'Breadcrumb', $Breadcrumb );
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */

    public function store( Request $request ) {
        $this->validate( $request, [
            'Email' => ['required','email',
                Rule::unique('users')->ignore($request->id, 'id')->where(function ($query) {
                    $query->where('EntrepriseId', Auth::user()->EntrepriseId);
                }),],
            'EmployeId' => 'required',
            'ImageUser' =>'mimes:jpeg,png,jpg,gif|max:1024',
            'Password' =>'required|min:4',
            'Confirmpassword' => 'required|same:Password|min:4',
            'Roles' => 'required',
        ],
        [
            'Email.required' => 'Le champ Adresse éléctronique est obligatoire.',
            'Email.email' => 'Adresse éléctronique incorrect',
            'Email.unique' => 'Cette Adresse éléctronique est déjà utilisée.',
            'EmployeId.required' => 'Le champ Employé est obligatoire.',
            'ImageUser.mimes' => 'Le format de la photo profil doit être jpeg,png,jpg ou gif.',
            'ImageUser.max' => "La taille de l'image ne doit pas dépasser 1MO .",
            'Password.required' => 'Le champ Mot de passe est obligatoire.',
            'Password.min' => 'Le champ Mot de passe doit avoir au moins 4 caractères.',
            'Confirmpassword.required' => 'Le champ Confirmation est obligatoire.',
            'Confirmpassword.min' => 'Le champ Confirmation doit avoir au moins 4 caractères.',
            'Confirmpassword.same' => 'Les champs Mot de passe et  Confirmation doivent être conformes.',
            'Roles.required' => 'Veuillez choisir au moins un rôle.',
        ] );

        $fileName = '';

        if ( $file = $request->hasFile( 'ImageUser' ) ) {

            $file = $request->file( 'ImageUser' ) ;
            $fileName = Auth::user()->EntrepriseId.''.time().'.'.$file->extension();
            $path= "images/".$fileName;
            Storage::disk('public')->put($path, file_get_contents($file));

        }

        $user = new User;
        $user->Email = $request->Email;
        $user->SuperAdmin = false;
        $user->password = Hash::make( $request->Password );
        $user->EmployeId = $request->EmployeId;
        $user->ImageUser = $fileName;
        // $user->DateCreation = Carbon::now();
        $user->EntrepriseId = Auth::user()->EntrepriseId;
        $user->Create_user = Auth::user()->id;
        $user->save();

        $user->assignRole( $request->input( 'Roles' ) );
        return redirect()->route( 'users.index' )
        ->with( 'success', 'Utilisateur créé avec succès.' );
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function show( $id ) {
        $user = User::find( $id );
        $userRoles = $user->roles->pluck( 'Nom', 'Nom' )->all();
        $Breadcrumb = '<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\'breadcrumb-item\'><a href=\'/config/users\'>Utilisateurs</a></li>';
        return view( 'config.users.show', compact( 'user','userRoles' ) )
        ->with( 'Titre', 'Utilisateurs' )
        ->with( 'Breadcrumb', $Breadcrumb );
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function edit( $id ) {
        $user = User::find( $id );
        $roles = Role::pluck( 'Nom', 'Nom' )->all();
        $userRoles = $user->roles->pluck( 'Nom', 'Nom' )->all();
        $employes = Employe::where( 'EntrepriseId', Auth::user()->EntrepriseId )->get();
        $Breadcrumb = '<li class=\'breadcrumb-item\'><a href=\'/roles\'>Rôles</a></li>';

        return view( 'config.users.edit', compact( 'user', 'roles', 'userRoles', 'employes' ) )
        ->with( 'Titre', 'Utilisateurs' )
        ->with( 'Breadcrumb', $Breadcrumb );
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function update( Request $request, $id ) {
       
        $this->validate( $request, [
            'Email' => ['required','email',
                Rule::unique('users')->ignore($id, 'id')->where(function ($query) {
                    $query->where('EntrepriseId', Auth::user()->EntrepriseId);
                }),],
            
            'EmployeId' => 'required',
            'ImageUser' =>'mimes:jpeg,png,jpg,gif|max:1024',
            'Roles' => 'required',
        ],
        [
            'Email.required' => 'Le champ Adresse éléctronique est obligatoire.',
            'Email.email' => 'Adresse éléctronique incorrect',
            'Email.unique' => 'Cette Adresse éléctronique est déjà utilisée.',
            'EmployeId.required' => 'Le champ Employé est obligatoire.',
            'ImageUser.mimes' => 'Le format de la photo profil doit être jpeg,png,jpg ou gif.',
            'ImageUser.max' => "La taille de l'image ne doit pas dépasser 1MO .",
            'Roles.required' => 'Veuillez choisir au moins un rôle.',
        ] );

        $fileName = '';

        $user = User::find( $id );

        if ($request->hasFile( 'ImageUser' ) ) {
            $file = $request->file( 'ImageUser' ) ;
            $fileName = Auth::user()->EntrepriseId.''.time().'.'.$file->extension();
            $path= "images/".$fileName;
            Storage::disk('public')->put($path, file_get_contents($file));

            if(Storage::exists("public/images/".$user->ImageUser)){
                Storage::delete("public/images/".$user->ImageUser);
            }
        } else {
            $fileName = $user->ImageUser;
        }

        $user->Email = $request->Email;
        $user->ImageUser = $fileName;
        $user->Edit_user= Auth::user()->id;
        $user->save();

        DB::table( 'model_has_roles' )->where( 'model_id', $id )->delete();
        $user->assignRole( $request->input( 'Roles' ) );
        return redirect()->route( 'users.index' )
        ->with( 'success', 'Utilisateur modifié avec succès.' );

    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function destroy( $id ) {

        $user = User::find($id);
        $user->Supprimer =true;
        $user->Delete_user= Auth::user()->id;
        $user->save();
        // User::find( $id )->delete();
        return redirect()->route( 'users.index' )
        ->with( 'success', 'Utilisateur supprimé avec succès.' );
    }
}