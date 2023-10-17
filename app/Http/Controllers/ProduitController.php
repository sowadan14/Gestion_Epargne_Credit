<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\TypeProduit;
use App\Models\CategProduit;
use App\Models\Unite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ProduitController extends Controller
{


    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:listproduit|createproduit|editproduit|deleteproduit', ['only' => ['index', 'show']]);
        $this->middleware('permission:createproduit', ['only' => ['create', 'store']]);
        $this->middleware('permission:editproduit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:deleteproduit', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $data = Produit::orderBy('id', 'DESC')->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/config/prodts\">Produits</a></li>";

        return view('config.prodts.index', compact('data'))->with('Titre', 'Produits')->with('Breadcrumb', $Breadcrumb);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $typeproduits = TypeProduit::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $unites = Unite::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $categproduits = CategProduit::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/config/categprods\">Produits</a></li>";

        return view('config.prodts.create', compact('typeproduits', 'unites', 'categproduits'))->with('Titre', 'Produits')->with('Breadcrumb', $Breadcrumb);
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
                'Libelle' => [
                    'required',
                    // Rule::unique('roles', 'Nom')->where('EntrepriseId','<>',Auth::user()->EntrepriseId)->ignore($request->id),
                    Rule::unique('produits')->ignore($request->id, 'id')->where(function ($query) {
                        $query->where('EntrepriseId', Auth::user()->EntrepriseId);
                    }),
                ],

                'UniteId' => 'required',
                'TypeProduitId' => 'required',
                'CategProduitId' => 'required',
                'Prod_logo' => 'mimes:jpeg,png,jpg,gif|max:1024',
            ],
            [
                'Libelle.required' => 'Le champ Libellé est obligatoire.',
                'Libelle.unique' => 'Ce produit existe déjà.',
                'UniteId.required' => 'Le choix d\'unité est obligatoire.',
                'TypeProduitId.required' => 'Le choix du type produit est obligatoire.',
                'CategProduitId.required' => 'Le choix de famille produit est obligatoire.',
                'Prod_logo.mimes' => 'Le format du logo produit doit être jpeg,png,jpg ou gif.',
                'Prod_logo.max' => "La taille de l'image ne doit pas dépasser 1MO .",
            ]
        );



        $fileName = '';

        if ($file = $request->hasFile('Prod_logo')) {
            // dd($request);
            $file = $request->file('Prod_logo');
            $fileName = Auth::user()->EntrepriseId . '' . time() . '.' . $file->extension();
            $path = "images/" . $fileName;
            Storage::disk('public')->put($path, file_get_contents($file));
        }






        $produit = Produit::updateOrCreate(
            ['id' => $request->id],
            [
                'Libelle' => $request->Libelle,
                'CUMP' => $request->CUMP,
                'StockSecu' => $request->StockSecu,
                'TypeProduitId' => $request->TypeProduitId,
                'UniteId' => $request->UniteId,
                'Status' => $request->Status == 'on' ? 1 : 0,
                'CategProduitId' => $request->CategProduitId,
                'PU' => '0',
                'Qte' => '0',
                'Prod_logo' => $fileName,
                'Code' => $request->Code,
                'CodeBar' => $request->CodeBar,
                'EntrepriseId' => Auth::user()->EntrepriseId, 'Create_user' => Auth::user()->id,
            ]
        );

        // dd($produit);


        $unites = $request->input('Unite', []);
        $qtes = $request->input('Qte', []);
        $prixventes = $request->input('PrixVente', []);
        $prixachats = $request->input('PrixAchat', []);

        // dd($unites,$qtes ,$prixventes,$prixachats);
        for ($unite = 0; $unite < count($unites); $unite++) {
            if ($unites[$unite] != '') {
                // dd($unite,count($unites),$unites[$unite] ,$prixventes[$unite],$prixachats[$unite]);
                $produit->unites()->attach($unites[$unite], ['Coef' => $qtes[$unite], 'PrixVente' => $prixventes[$unite], 'PrixAchat' => $prixachats[$unite]]);
            }
        }

        return redirect()->route('prodts.index')
            ->with('success', 'Produit créé avec succès');
        //
        // dd($request->input('Unites', []));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Produit  $produit
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $produit = Produit::find($id);
        $unites = Unite::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $produit->load('unites');
        $typeproduits = TypeProduit::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $categproduits = CategProduit::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/config/categprods\">Produits</a></li>";

        return view('config.prodts.show', compact('typeproduits', 'unites', 'categproduits', 'produit'))->with('Titre', 'Produits')->with('Breadcrumb', $Breadcrumb);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Produit  $produit
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $produit = Produit::find($id);
        $unites = Unite::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $produit->load('unites');
        $typeproduits = TypeProduit::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $categproduits = CategProduit::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/config/categprods\">Produits</a></li>";

        return view('config.prodts.edit', compact('typeproduits', 'unites', 'categproduits', 'produit'))->with('Titre', 'Produits')->with('Breadcrumb', $Breadcrumb);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Produit  $produit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // dd($request);
        $this->validate(
            $request,
            [
                'Libelle' => [
                    'required',
                    // Rule::unique('roles', 'Nom')->where('EntrepriseId','<>',Auth::user()->EntrepriseId)->ignore($request->id),
                    Rule::unique('produits')->ignore($request->id, 'id')->where(function ($query) {
                        $query->where('EntrepriseId', Auth::user()->EntrepriseId);
                    }),
                ],

                'UniteId' => 'required',
                'TypeProduitId' => 'required',
                'CategProduitId' => 'required',
                'Prod_logo' => 'mimes:jpeg,png,jpg,gif|max:1024',
            ],
            [
                'Libelle.required' => 'Le champ Libellé est obligatoire.',
                'Libelle.unique' => 'Ce produit existe déjà.',
                'UniteId.required' => 'Le choix d\'unité est obligatoire.',
                'TypeProduitId.required' => 'Le choix du type produit est obligatoire.',
                'CategProduitId.required' => 'Le choix de famille produit est obligatoire.',
                'Prod_logo.mimes' => 'Le format du logo produit doit être jpeg,png,jpg ou gif.',
                'Prod_logo.max' => "La taille de l'image ne doit pas dépasser 1MO .",
            ]
        );



        $fileName = '';
        $produit = Produit::find($request->id);

        if ($request->hasFile('Prod_logo')) {
            $file = $request->file('Prod_logo');
            $fileName = Auth::user()->EntrepriseId . '' . time() . '.' . $file->extension();
            $path = "images/" . $fileName;
            Storage::disk('public')->put($path, file_get_contents($file));

            if (Storage::exists("public/images/" . $produit->Prod_logo)) {
                Storage::delete("public/images/" . $produit->Prod_logo);
            }
        } else {
            $fileName = $produit->Prod_logo;
        }



        $produit->Libelle = $request->Libelle;
        $produit->CUMP = $request->CUMP;
        $produit->StockSecu = $request->StockSecu;
        $produit->TypeProduitId = $request->TypeProduitId;
        $produit->UniteId = $request->UniteId;
        $produit->Status = $request->Status == 'on' ? 1 : 0;
        $produit->CategProduitId = $request->CategProduitId;
        $produit->Code = $request->Code;
        $produit->CodeBar = $request->CodeBar;
        $produit->Prod_logo = $fileName;
        $produit->Edit_user = Auth::user()->id;
        $produit->save();

        // $produit->unites()->detach();

        $unites = $request->input('Unite', []);
        $qtes = $request->input('Qte', []);
        $prixventes = $request->input('PrixVente', []);
        $prixachats = $request->input('PrixAchat', []);

        // dd($unites,$qtes ,$prixventes,$prixachats);
        for ($unite = 0; $unite < count($unites); $unite++) {
            if ($unites[$unite] != '') {
                // dd($unite,count($unites),$unites[$unite] ,$prixventes[$unite],$prixachats[$unite]);
                $uniteproduit = $produit->unites()->wherePivot('UniteId', '=', $unites[$unite])->get()->first();
                if ($uniteproduit == null) {
                    $produit->unites()->attach(
                        $unites[$unite],
                        [
                            'Coef' => $qtes[$unite],
                            'PrixVente' => $prixventes[$unite],
                            'PrixAchat' => $prixachats[$unite]
                        ]
                    );
                } else {
                    DB::table('uniteproduits')
                        ->where('ProduitId', $produit->id)
                        ->where('UniteId', $unites[$unite])
                        ->update([
                            'Coef' => $qtes[$unite],
                            'PrixVente' => $prixventes[$unite],
                            'PrixAchat' => $prixachats[$unite]
                        ]);
                }
            }
        }

        return redirect()->route('prodts.index')
            ->with('success', 'Produit modifié avec succès');

        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Produit  $produit
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (count(DB::table('detailsventes')->where('ProduitId', $id)->get()) > 0) {
            return redirect('/config/prodts')->with('danger', "Ce produit ne peut être supprimé car elle est utilisée par d'autres données.");
        } elseif (count(DB::table('detailsachats')->where('ProduitId', $id)->get()) > 0) {
            return redirect('/config/prodts')->with('danger', "Ce produit ne peut être supprimé car elle est utilisée par d'autres données.");
        } else {
            $produit = Produit::find($id);
            $produit->Supprimer = true;
            $produit->Delete_user = Auth::user()->id;
            $produit->save();
            return redirect('/config/prodts')->with('success', 'Produit supprimé avec succès.');
        }
    }
}
