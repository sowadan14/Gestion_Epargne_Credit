<?php

namespace App\Http\Controllers;

use App\Helpers\helpers;

use App\Models\Stock;
use App\Models\AvoirFr;
use App\Models\Compte;
use App\Models\ConvertStock;
use App\Models\DetailsConversion;
use App\Models\Fournisseur;
use App\Models\ReceptionStock;
use App\Models\Unite;
use App\Models\Produit;
use App\Models\ModePaiement;
use App\Models\FactureStock;
use App\Models\PaiementStock;
use App\Models\UniteProduit;
use Brick\Math\BigInteger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ConvertStockController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // function __construct()
    // {
    //     $this->middleware('auth');
    //     $this->middleware('permission:listconvertstock|createconvertstock|editconvertstock|deleteconvertstock', ['only' => ['index', 'show']]);
    //     $this->middleware('permission:createconvertstock', ['only' => ['create', 'store']]);
    //     $this->middleware('permission:editconvertstock', ['only' => ['edit', 'update']]);
    //     $this->middleware('permission:deleteconvertstock', ['only' => ['destroy']]);
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $unites = Unite::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $data = ConvertStock::orderBy('id', 'DESC')->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Stock</a></li><li class=\"breadcrumb-item\"><a href=\"/stock/convertstocks\">Convertion stock</a></li>";

        return view('stock.convertstocks.index', compact('data', 'unites'))->with('Titre', 'Convertion stock')->with('Breadcrumb', $Breadcrumb);
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

        $fournisseurs = Fournisseur::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $modepaiements = ModePaiement::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $comptes = Compte::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Stock</a></li><li class=\"breadcrumb-item\"><a href=\"/stock/convertstocks\">Convertion stock </a></li>";
        return view('stock.convertstocks.create', compact('fournisseurs', 'modepaiements', 'comptes', 'unites', 'produits'))->with('Titre', 'Convertion stock')->with('Breadcrumb', $Breadcrumb);
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
            || in_array(null, $request->input('FromUniteId', [])) || in_array('', array_map('trim', $request->input('FromUniteId', [])))  || empty($request->input('FromUniteId', []))
            || in_array(null, $request->input('ToUniteId', [])) || in_array('', array_map('trim', $request->input('ToUniteId', [])))  || empty($request->input('ToUniteId', []))
            || in_array(null, $request->input('Produit', [])) || in_array('', array_map('trim', $request->input('Produit', [])))  || empty($request->input('Produit', []))

        ) {
            return redirect()->route('convertstocks.index')
                ->with('danger', 'Des données vides ont été saisies.');
        }


        $produits = $request->input('Produit', []);
        $qtes = $request->input('Qte', []);
        $fromunites = $request->input('FromUniteId', []);
        $tounites = $request->input('ToUniteId', []);


        for ($produit = 0; $produit < count($produits); $produit++) {
            if ($produits[$produit] != '' && $qtes[$produit] != '' && $qtes[$produit] != '0' && $fromunites[$produit] != '' && $fromunites[$produit] != '0' && $tounites[$produit] != '' && $tounites[$produit] != '0') {
                $prodt = Produit::find($produits[$produit]);
                $from = UniteProduit::where('ProduitId', $produits[$produit])->where('UniteId', $fromunites[$produit])->first();
                if ($from != null) {
                    $to = UniteProduit::where('ProduitId', $produits[$produit])->where('UniteId', $tounites[$produit])->first();
                    if ($to != null) {
                        $cstock = new ConvertStock();
                        $cstock->ProduitId = $produits[$produit];
                        $cstock->FromUniteId = $fromunites[$produit];
                        $cstock->ToUniteId = $tounites[$produit];
                        $cstock->FromQte = $qtes[$produit];
                        $cstock->ToQte = 0;
                        $cstock->EntrepriseId = Auth::user()->EntrepriseId;
                        $cstock->Create_user = Auth::user()->id;
                        $cstock->save();


                        DB::table('uniteproduits')
                            ->where('ProduitId', $produits[$produit])
                            ->where('UniteId', $fromunites[$produit])
                            ->update(['Qte' => DB::raw('Qte-' . (int)$qtes[$produit])]);

                        $QteTotal = (int)$qtes[$produit] * (int)$from->Coef;
                        if ($qtes[$produit] > $to->Coef) {

                            $QteConvertie = intdiv($QteTotal, (int)$to->Coef);

                            if ($QteConvertie > 0) {

                                $detailconversion = new DetailsConversion();
                                $detailconversion->ConvertStockId = $cstock->id;
                                $detailconversion->Qte = $QteConvertie;
                                $detailconversion->UniteId = $to->UniteId;
                                $detailconversion->Create_user = Auth::user()->id;
                                $detailconversion->save();

                                DB::table('uniteproduits')
                                    ->where('ProduitId', $produits[$produit])
                                    ->where('UniteId', $tounites[$produit])
                                    ->update(['Qte' => DB::raw('Qte+' . $QteConvertie)]);
                            }

                            $qteRestant = $QteTotal - ($QteConvertie * $to->Coef);

                            if ($qteRestant > 0) {
                                if ($qteRestant > $from->Coef) {
                                    $restant = intdiv($qteRestant, (int)$from->Coef);

                                    $detailconversion = new DetailsConversion();
                                    $detailconversion->ConvertStockId = $cstock->id;
                                    $detailconversion->Qte = $restant;
                                    $detailconversion->UniteId = $from->UniteId;
                                    $detailconversion->Create_user = Auth::user()->id;
                                    $detailconversion->save();


                                    DB::table('uniteproduits')
                                        ->where('ProduitId', $produits[$produit])
                                        ->where('UniteId', $from->UniteId)
                                        ->update(['Qte' => DB::raw('Qte+' . $restant)]);

                                    $qteUnite = $qteRestant - ($restant * $from->Coef);
                                    if($qteUnite>0)
                                    {
                                        $detailconversion = new DetailsConversion();
                                        $detailconversion->ConvertStockId = $cstock->id;
                                        $detailconversion->Qte = $qteUnite;
                                        $detailconversion->UniteId = $prodt->UniteId;
                                        $detailconversion->Create_user = Auth::user()->id;
                                        $detailconversion->save();
    
                                        DB::table('uniteproduits')
                                            ->where('ProduitId', $produits[$produit])
                                            ->where('UniteId', $prodt->UniteId)
                                            ->update(['Qte' => DB::raw('Qte+' . $qteUnite)]);
                                    }
                                   
                                } else {
                                    $detailconversion = new DetailsConversion();
                                    $detailconversion->ConvertStockId = $cstock->id;
                                    $detailconversion->Qte = $qteRestant;
                                    $detailconversion->UniteId = $prodt->UniteId;
                                    $detailconversion->Create_user = Auth::user()->id;
                                    $detailconversion->save();

                                    DB::table('uniteproduits')
                                        ->where('ProduitId', $produits[$produit])
                                        ->where('UniteId', $prodt->UniteId)
                                        ->update(['Qte' => DB::raw('Qte+' . $qteRestant)]);
                                }
                            }
                        }
                    }
                }
            }
        }

        return redirect()->route('convertstocks.index')
            ->with('success', 'Conversion stock effectuée avec succès.');
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }


    public function getUnites(Request $request)
    {

        if (!$request->id) {
            $html = '';
        } else {
            $html = '';
            $produit = Produit::find($request->id);
            $produit->load('unites');

            $html = view('stock.convertstocks.tablestock', compact('produit'))->render();
        }
        return response()->json(array('success' => true, 'html' => $html));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }



    public function cloturer($id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    { 

        $conversion = ConvertStock::find($id);
        if ($conversion != null) {
           
            $qtetotal = 0;
            foreach ($conversion->detailconversions as $detail) {
                $to = UniteProduit::where('ProduitId', $conversion->ProduitId)->where('UniteId', $detail->UniteId)->first();
                if ($to != null) {
                    $qtetotal += $detail->Qte * $to->Coef;

                    DB::table('uniteproduits')
                        ->where('ProduitId', $conversion->ProduitId)
                        ->where('UniteId', $to->UniteId)
                        ->update(['Qte' => DB::raw('Qte-' . $detail->Qte)]);
                }
            }

            $from = UniteProduit::where('ProduitId', $conversion->ProduitId)->where('UniteId', $conversion->FromUniteId)->first();
            if ($from != null) {
                $qte=intdiv($qtetotal , $from->Coef);
                DB::table('uniteproduits')
                    ->where('ProduitId', $conversion->ProduitId)
                    ->where('UniteId', $conversion->FromUniteId)
                    ->update(['Qte' => DB::raw('Qte+' . $qte)]);
            }

            $conversion->detailconversions()->delete();
           

            $conversion->Supprimer = true;
            $conversion->Delete_user = Auth::user()->id;
            $conversion->save();

            return redirect()->route('convertstocks.index')
                ->with('success', 'Conversion supprimée avec succès.');
        } else {
            return redirect()->route('alists.index')
                ->with('danger', 'Cette conversion n\'existe pas.');
        }
    }
}
