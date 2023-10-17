<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:listrole|createrole|editrole|deleterole', ['only' => ['index', 'show']]);
        $this->middleware('permission:createrole', ['only' => ['create', 'store']]);
        $this->middleware('permission:editrole', ['only' => ['edit', 'update']]);
        $this->middleware('permission:deleterole', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Role::where('EntrepriseId', Auth::user()->EntrepriseId)->where('Supprimer',false)->orderBy('id', 'DESC')->get();
        $user =  Auth::user();
        // $view=view('roles.create',compact('permissions'))->render();
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"/#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/roles\">Rôles et permissions</a></li>";
        return view('config.roles.index', compact('data'))
            ->with('Titre', 'Rôles et permissions')
            // ->with('toto',$view)
            ->with('Breadcrumb', $Breadcrumb);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $MyrolePermissions = DB::table("role_has_permissions")->wherein("role_has_permissions.role_id", auth()->user()->roles->pluck("id"))->get();
        $permissions = Permission::wherein("id", $MyrolePermissions->pluck("permission_id"))->distinct()->get();

        $numParents = Permission::wherein("id", $MyrolePermissions->pluck("permission_id"))
            ->orderBy('id', 'ASC')
            ->select('NumParent')
            ->groupBy('NumParent')
            ->get()
            ->pluck("NumParent");
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/config/roles\">Rôles</a></li>";

        return view('config.roles.create', compact('permissions', 'numParents'))->with('Titre', 'Rôles')->with('Breadcrumb', $Breadcrumb);
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
                'Nom' => ['required',
                // Rule::unique('roles', 'Nom')->where('EntrepriseId','<>',Auth::user()->EntrepriseId)->ignore($request->id),
             Rule::unique('roles')->ignore($request->id, 'id')->where(function ($query) {
                    $query->where('EntrepriseId', Auth::user()->EntrepriseId);
                }),],
                'permission' => 'required',
            ],
            [
                'Nom.required' => 'Le champ Libellé est obligatoire.',
                'Nom.unique' => 'Ce rôle a été déjà pris.',
                'permission.required' => 'Veuillez séléctionner au moins une permission.',

            ]
        );

        $role =  Role::updateOrCreate(['id' => $request->id], ['Nom' => $request->input('Nom'), 'EntrepriseId' => Auth::user()->EntrepriseId,'Create_user' => Auth::user()->id]);
        $role->syncPermissions($request->input('permission'));

        return redirect()->route('roles.index')
            ->with('success', 'Rôle et permissions créés avec succès');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);
        $MyrolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)->get();
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $id)
            ->get();


        // $numParents=DB::table('permissions')->get()->pluck("NumParent");
        $numParents = Permission::wherein("id", $MyrolePermissions->pluck("permission_id"))
            ->orderBy('id', 'ASC')
            ->select('NumParent')
            ->groupBy('NumParent')
            ->get()
            ->pluck("NumParent");
           
            $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/config/roles\">Rôles et permissions</a></li>";
            return view('config.roles.show', compact('rolePermissions', 'role', 'numParents'))->with('Titre', 'Rôles et permissions')->with('Breadcrumb', $Breadcrumb);
    //   dd(count($role->users));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find($id);
        $MyrolePermissions = DB::table("role_has_permissions")->wherein("role_has_permissions.role_id", auth()->user()->roles->pluck("id"))->get();
        $permissions = Permission::wherein("id", $MyrolePermissions->pluck("permission_id"))->distinct()->get();

        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();


        // $numParents=DB::table('permissions')->get()->pluck("NumParent");
        $numParents = Permission::wherein("id", $MyrolePermissions->pluck("permission_id"))
            ->orderBy('id', 'ASC')
            ->select('NumParent')
            ->groupBy('NumParent')
            ->get()
            ->pluck("NumParent");


        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/config/roles\">Rôles et permissions</a></li>";
        return view('config.roles.edit', compact('role', 'permissions', 'numParents', 'rolePermissions'))->with('Titre', 'Rôles et permissions')->with('Breadcrumb', $Breadcrumb);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'Nom' => ['required',
                // Rule::unique('roles', 'Nom')->where('EntrepriseId','<>',Auth::user()->EntrepriseId)->ignore($request->id),
             Rule::unique('roles')->ignore($id, 'id')->where(function ($query) {
                    $query->where('EntrepriseId', Auth::user()->EntrepriseId);
                }),],
                'permission' => 'required',
            ],
            [
                'Nom.required' => 'Le champ Libellé est obligatoire.',
                'Nom.unique' => 'Ce rôle a été déjà pris.',
                'permission.required' => 'Veuillez séléctionner au moins une permission.',

            ]
        );

        $role = Role::find($id);
        $role->Nom = $request->input('Nom');
        $role->Edit_user= Auth::user()->id;
        $role->save();

        $role->syncPermissions($request->input('permission'));

        return redirect()->route('roles.index')
            ->with('success', 'Rôle et permissions modifiés avec succès');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::find($id);

        if(count($role->users)>0)
        {
             return redirect('/config/roles')->with('danger', 'Ce rôle ne peut être supprimé car il est attribué à un utilisateur.');
              }
        else
        {
            // Poste::where('id',$id)->delete();
            // $poste = Poste::find($id);
            $role->Supprimer =true;
            $role->Delete_user= Auth::user()->id;
            $role->save();
            return redirect()->route('roles.index')
            ->with('success', 'Rôle supprimé avec succès.');
        }
    }
}
