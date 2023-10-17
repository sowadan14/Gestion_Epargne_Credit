<?php

namespace App\Http\Controllers;

use App\Models\Vente;
use App\Models\Compte;
use App\Models\Client;
use App\Models\LivraisonVente;
use App\Models\Unite;
use App\Models\Produit;
use App\Models\ModePaiement;
use App\Models\FactureVente;
use App\Models\PaiementVente;
use App\Models\AvoirClt;
use App\Models\RegulStock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegulStockController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:listvlist|createvlist|editvlist|deletevlist', ['only' => ['index', 'show']]);
        $this->middleware('permission:createvlist', ['only' => ['create', 'store']]);
        $this->middleware('permission:editvlist', ['only' => ['edit', 'update']]);
        $this->middleware('permission:deletevlist', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = RegulStock::orderBy('id', 'DESC')->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Régularisation</a></li><li class=\"breadcrumb-item\"><a href=\"/regul/regulstocks\">Stock</a></li>";

        return view('regul.regulstocks.index', compact('data'))->with('Titre', 'Régularisations stock')->with('Breadcrumb', $Breadcrumb);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $unites = Unite::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $produits = Produit::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();

        $clients = Client::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $modepaiements = ModePaiement::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $comptes = Compte::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Régularisation</a></li><li class=\"breadcrumb-item\"><a href=\"/regul/regulstocks\">Régularisations stock </a></li>";
        return view('regul.regulstocks.create', compact('clients', 'modepaiements', 'comptes', 'unites', 'produits'))->with('Titre', 'Régularisations stock')->with('Breadcrumb', $Breadcrumb);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (
            in_array(null, $request->input('Qte', [])) || in_array('', array_map('trim', $request->input('Qte', [])))  || empty($request->input('Qte', []))
        ) {
            return redirect()->route('regulstocks.index')
                ->with('danger', 'Des données vides ont été saisies.');
        }



        $unites = $request->input('Unite', []);
        $produits = $request->input('Produit', []);
        $qtes = $request->input('Qte', []);
        $entrees = $request->input('Entree', []);

        // dd(count($entrees));

        for ($produit = 0; $produit < count($produits); $produit++) {
            if ($produits[$produit] != '' && $qtes[$produit] != '' && $qtes[$produit] != '0') {

                if ($qtes[$produit] > 0) {
                    $regulstock = new RegulStock();
                    $regulstock->ProduitId = $produits[$produit];
                    $regulstock->UniteId = $unites[$produit];
                    $regulstock->Qte = $qtes[$produit];
                    $regulstock->Entree = count($entrees) == 0 ? '0' : ($entrees[$produit] == 'on' ? '1' : '0');
                    $regulstock->EntrepriseId = Auth::user()->EntrepriseId;
                    $regulstock->Create_user = Auth::user()->id;
                    $regulstock->save();

                    if ($regulstock->Entree == '0') {
                        DB::table('uniteproduits')
                            ->where('ProduitId', $produits[$produit])
                            ->where('UniteId', $unites[$produit])
                            ->update(['Qte' => DB::raw('Qte-' . $qtes[$produit])]);
                    } else {
                        DB::table('uniteproduits')
                            ->where('ProduitId', $produits[$produit])
                            ->where('UniteId', $unites[$produit])
                            ->update(['Qte' => DB::raw('Qte+' . $qtes[$produit])]);
                    }
                }
            }
        }

        return redirect()->route('regulstocks.index')
            ->with('success', 'Régularisation stock créée avec succès.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vente  $regul
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cmde = Vente::find($id);
        if ($cmde == null) {
            return redirect()->route('regulstocks.index');
        }
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Régularisation</a></li><li class=\"breadcrumb-item\"><a href=\"/regul/cmdes\">Régularisations stock </a></li>";
        return view('regul.regulstocks.show', compact('cmde'))->with('Titre', 'Régularisations stock')->with('Breadcrumb', $Breadcrumb);
    }


    public function getUnites(Request $request)
    {

        if (!$request->id) {
            $html = '';
        } else {
            $html = '';
            $produit = Produit::find($request->id);
            $produit->load('unites');

            foreach ($produit->unites as $unite) {
                // if($unite->pivot->Qte>0)
                // {
                if ($unite->id == $produit->UniteId) {
                    $html .= $unite->id . '/' . $unite->pivot->PrixVente . '/' . $unite->pivot->Qte . '~' . $unite->Nom . '~selected|';
                } else {
                    $html .= $unite->id . '/' . $unite->pivot->PrixVente  . '/' . $unite->pivot->Qte . '~' . $unite->Nom . '~~' . $unite->pivot->Qte . '|';
                }
                // }
            }
        }
        // dd($html);

        return response()->json($html);
    }


    public function getCltAvoir(Request $request)
    {

        if (!$request->id) {
            $avoir = 0;
        } else {
            $avoir = 0;
            $avoirclt = AvoirClt::where('ClientId', $request->id)->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->first();
            if ($avoirclt != null) {
                $avoir = $avoirclt->Montant;
            }
        }
        // dd($html);

        return response()->json($avoir);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vente  $regul
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $regulstock = RegulStock::find($id);;
        if ($regulstock == null) {
            return redirect()->route('regulstocks.index');
        }
        $produits = Produit::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Régularisation</a></li><li class=\"breadcrumb-item\"><a href=\"/regul/regulstocks\">Régularisations stock </a></li>";
        return view('regul.regulstocks.edit', compact('regulstock', 'produits'))->with('Titre', 'Régularisations stock')->with('Breadcrumb', $Breadcrumb);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vente  $regul
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (
            in_array(null, $request->input('Qte', [])) || in_array('', array_map('trim', $request->input('Qte', [])))  || empty($request->input('Qte', []))
        ) {
            return redirect()->route('regulstocks.index')
                ->with('danger', 'Des données vides ont été saisies.');
        }

        $regul = RegulStock::find($id);
        if ($regul->Entree == '0') {
            DB::table('uniteproduits')
                ->where('ProduitId', $regul->ProduitId)
                ->where('UniteId', $regul->UniteId)
                ->update(['Qte' => DB::raw('Qte+' . $regul->Qte)]);
        } else {
            DB::table('uniteproduits')
                ->where('ProduitId', $regul->ProduitId)
                ->where('UniteId', $regul->UniteId)
                ->update(['Qte' => DB::raw('Qte-' . $regul->Qte)]);
        }

        $regul->Supprimer=true;
        $regul->Edit_user = Auth::user()->id;
        $regul->save();

        $unites = $request->input('Unite', []);
        $produits = $request->input('Produit', []);
        $qtes = $request->input('Qte', []);
        $entrees = $request->input('Entree', []);

        for ($produit = 0; $produit < count($produits); $produit++) {
            if ($produits[$produit] != '' && $qtes[$produit] != '' && $qtes[$produit] != '0') {

                if ($qtes[$produit] > 0) {
                    $regulstock = new RegulStock();
                    $regulstock->ProduitId = $produits[$produit];
                    $regulstock->UniteId = $unites[$produit];
                    $regulstock->Qte = $qtes[$produit];
                    $regulstock->Entree = count($entrees) == 0 ? '0' : ($entrees[$produit] == 'on' ? '1' : '0');
                    $regulstock->EntrepriseId = Auth::user()->EntrepriseId;
                    $regulstock->Create_user = Auth::user()->id;
                    $regulstock->save();

                    if ($regulstock->Entree == '0') {
                        DB::table('uniteproduits')
                            ->where('ProduitId', $produits[$produit])
                            ->where('UniteId', $unites[$produit])
                            ->update(['Qte' => DB::raw('Qte-' . $qtes[$produit])]);
                    } else {
                        DB::table('uniteproduits')
                            ->where('ProduitId', $produits[$produit])
                            ->where('UniteId', $unites[$produit])
                            ->update(['Qte' => DB::raw('Qte+' . $qtes[$produit])]);
                    }
                }
            }
        }

        return redirect()->route('regulstocks.index')
            ->with('success', 'Régularisation stock modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vente  $regul
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $regul = RegulStock::find($id);
       
        if ($regul != null) {
           
            if ($regul->Entree == '0') {
                DB::table('uniteproduits')
                    ->where('ProduitId', $regul->ProduitId)
                    ->where('UniteId', $regul->UniteId)
                    ->update(['Qte' => DB::raw('Qte+' . $regul->Qte)]);
            } else {
                DB::table('uniteproduits')
                    ->where('ProduitId', $regul->ProduitId)
                    ->where('UniteId', $regul->UniteId)
                    ->update(['Qte' => DB::raw('Qte-' . $regul->Qte)]);
            }

            $regul->Supprimer=true;
            $regul->Edit_user = Auth::user()->id;
            $regul->save();
            return redirect()->route('regulstocks.index')
                ->with('success', 'Régularisation stock supprimée avec succès.');
        } else {
            return redirect()->route('regulstocks.index')
                ->with('danger', 'Cette régularisation stock n\'existe pas.');
        }
    }
}
