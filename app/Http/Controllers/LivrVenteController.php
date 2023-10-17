<?php

namespace App\Http\Controllers;

use App\Helpers\helpers;

use App\Models\LivraisonVente;
use App\Models\Vente;
use App\Models\Client;
use App\Models\Unite;
use App\Models\Produit;
use App\Models\ModePaiement;
use Brick\Math\BigInteger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LivrVenteController extends Controller
{

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:listvlivr|createvlivr|editvlivr|deletevlivr', ['only' => ['index','show']]);
        $this->middleware('permission:createvlivr', ['only' => ['create','store']]);
        $this->middleware('permission:editvlivr', ['only' => ['edit','update']]);
        $this->middleware('permission:deletevlivr', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $data = LivraisonVente::orderBy('id', 'DESC')->where('Supprimer', false)
            ->where('EntrepriseId', Auth::user()->EntrepriseId)
            ->where('MontantLivre', '>', 0)
            ->get();
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Vente</a></li><li class=\"breadcrumb-item\"><a href=\"/vente/livrs\">Livraison commande </a></li>";

        return view('vente.livrs.index', compact('data'))->with('Titre', 'Livraison commande')->with('Breadcrumb', $Breadcrumb);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Cmdes = Vente::where('Status', '0')->whereRaw('MontantTTC-MontantLivre')->get();
        $cmde = $Cmdes->first();

        // dd($Cmdes);
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Vente</a></li><li class=\"breadcrumb-item\"><a href=\"/vente/livrs\">Livraison client </a></li>";
        return view('vente.livrs.create', compact('Cmdes', 'cmde'))->with('Titre', 'Livraison client')->with('Breadcrumb', $Breadcrumb);
    }

    public function addlivr($id)
    {
        $cmde = Vente::find($id);
        if ($cmde == null) {
            return redirect()->route('livrs.index');
        }
        $Cmdes = Vente::where('Status', '0')->whereRaw('MontantTTC-MontantLivre')->get();
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Vente</a></li><li class=\"breadcrumb-item\"><a href=\"/vente/livrs\">Livraison client </a></li>";
        return view('vente.livrs.create', compact('Cmdes', 'cmde'))->with('Titre', 'Livraison client')->with('Breadcrumb', $Breadcrumb);
    }



    public function getDetailsLivr(Request $request)
    {

        if (!$request->id) {
            $htmlTable = '';
            $htmlDetailsCmde = '';
            $reference = '';
        } else {
            $htmlTable = '';
            $htmlDetailsCmde = '';
            $cmde = Vente::find($request->id);
            $htmlTable = view('vente.livrs.tableCmde', compact('cmde'))->render();
            $htmlDetailsCmde = view('vente.livrs.detailCmde', compact('cmde'))->render();
            $reference = generateLivrVente();
        }
        // dd($html);

        return response()->json(array('success' => true, 'htmlTable' => $htmlTable, 'htmlDetailsCmde' => $htmlDetailsCmde, 'reference' => $reference));
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
                'DateLivraison' => 'required|date_format:"d/m/Y"',
                'CommandId' => 'required',
            ],
            [
                'DateLivraison.required' => 'Le champ Date livraison est obligatoire.',
                'DateLivraison.date_format' => 'Le format de Date livraison est incorrecte (dd/mm/yyyy).',
                'CommandId.required' => 'Le choix de réference commande est obligatoire.',
                // 'ModePaiementId.required' => 'Le choix du mode paiement est obligatoire.',
            ]
        );


        if (in_array(null, $request->input('Qte', [])) || in_array('', array_map('trim', $request->input('Qte', []))) || empty($request->input('Qte', []))) {
            return redirect()->route('livrs.index')
                ->with('danger', 'Des données vides ont été saisies.');
        }

        $livr = new LivraisonVente();
        $livr->DateLivraison = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateLivraison)));
        $livr->VenteId = $request->CommandId;
        $livr->Reference = $request->Reference;
        $livr->EntrepriseId = Auth::user()->EntrepriseId;
        $livr->Create_user = Auth::user()->id;
        $livr->save();

        $unites = $request->input('Unite', []);
        $produits = $request->input('Produit', []);
        $qtes = $request->input('Qte', []);

        $cmde = Vente::find($request->CommandId);

        for ($produit = 0; $produit < count($produits); $produit++) {
            if ($produits[$produit] != '' && $qtes[$produit] != '' && $qtes[$produit] != '0') {
                $cmdeproduit = $cmde->produits()->where('id', $produits[$produit])->wherePivot('UniteId', '=', $unites[$produit])->get()->first();


                if ($cmdeproduit != null) {
                    $montantHt = $qtes[$produit] * $cmdeproduit->pivot->Prix;
                    $remise = round(($montantHt * $cmdeproduit->pivot->Remise) / 100);
                    $tva = round(($montantHt * $cmdeproduit->pivot->Tva) / 100);
                    $montantttc = (int)($montantHt + $tva - $remise);
                    $cmde->MontantLivre = $cmde->MontantLivre + $montantttc;
                    $livr->MontantLivre = $livr->MontantLivre + $montantttc;

                    $livr->produits()->attach(
                        $produits[$produit],
                        [
                            'Qte' => $qtes[$produit],
                            'UniteId' => $unites[$produit],
                        ]
                    );
                }

                AddLivraisonDetailsProduit($produits[$produit], $unites[$produit], $qtes[$produit], $livr->VenteId);
            }
        }
        // dd($livr,$cmde);
        $livr->save();
        $cmde->save();

        return redirect()->route('livrs.index')
            ->with('success', 'Livraison créée avec succès.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vente  $vente
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $livr = LivraisonVente::find($id);

        if ($livr == null) {
            return redirect()->route('livrs.index');
        }
        $cmde = Vente::find($livr->VenteId);
        $Cmdes = Vente::all()->where('id', $cmde->id);
        $produits = Produit::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Vente</a></li><li class=\"breadcrumb-item\"><a href=\"/vente/livrs\">Livraison commande </a></li>";
        return view('vente.livrs.show', compact('cmde', 'livr', 'Cmdes','produits'))->with('Titre', 'Livraison commande')->with('Breadcrumb', $Breadcrumb);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vente  $vente
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $livr = LivraisonVente::find($id);
        if ($livr == null) {
            return redirect()->route('livrs.index');
        }
        $cmde = Vente::find($livr->VenteId);
        $Cmdes = Vente::all()->where('id', $cmde->id);
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Vente</a></li><li class=\"breadcrumb-item\"><a href=\"/vente/livrs\">Livraison commande </a></li>";
        return view('vente.livrs.edit', compact('cmde', 'livr', 'Cmdes'))->with('Titre', 'Livraison commande')->with('Breadcrumb', $Breadcrumb);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vente  $vente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate(
            $request,
            [
                'DateLivraison' => 'required|date_format:"d/m/Y"',
                'CommandId' => 'required',
            ],
            [
                'DateLivraison.required' => 'Le champ Date livraison est obligatoire.',
                'DateLivraison.date_format' => 'Le format de Date livraison est incorrecte (dd/mm/yyyy).',
                'CommandId.required' => 'Le choix de réference commande est obligatoire.',
                // 'ModePaiementId.required' => 'Le choix du mode paiement est obligatoire.',
            ]
        );

        if (in_array(null, $request->input('Qte', [])) || in_array('', array_map('trim', $request->input('Qte', [])))   || empty($request->input('Qte', []))) {
            return redirect()->route('livrs.index')
                ->with('danger', 'Des données vides ont été saisies.');
        }

        $livr =  LivraisonVente::find($request->id);
        if ($livr == null) {
            return redirect()->route('livrs.index');
        }


        removeLivraisonDetailsProduit($request->id);

        $cmde =  Vente::find($request->CommandId);
        $livr->DateLivraison = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateLivraison)));
        $livr->Reference = $request->Reference;
        $livr->Edit_user = Auth::user()->id;
        $livr->save();

        $livr->produits()->detach();


        $unites = $request->input('Unite', []);
        $produits = $request->input('Produit', []);
        $qtes = $request->input('Qte', []);

        $cmde = Vente::find($request->CommandId);

        for ($produit = 0; $produit < count($produits); $produit++) {
            if ($produits[$produit] != '' && $qtes[$produit] != '' && $qtes[$produit] != '0') {
                $cmdeproduit = $cmde->produits()->where('id', $produits[$produit])->wherePivot('UniteId', '=', $unites[$produit])->get()->first();


                if ($cmdeproduit != null) {
                    $montantHt = $qtes[$produit] * $cmdeproduit->pivot->Prix;
                    $remise = round(($montantHt * $cmdeproduit->pivot->Remise) / 100);
                    $tva = round(($montantHt * $cmdeproduit->pivot->Tva) / 100);
                    $montantttc = (int)($montantHt + $tva - $remise);
                    $cmde->MontantLivre = $cmde->MontantLivre + $montantttc;
                    $livr->MontantLivre = $livr->MontantLivre + $montantttc;

                    $livr->produits()->attach(
                        $produits[$produit],
                        [
                            'Qte' => $qtes[$produit],
                            'UniteId' => $unites[$produit],
                        ]
                    );
                }

                AddLivraisonDetailsProduit($produits[$produit], $unites[$produit], $qtes[$produit], $livr->VenteId);
                // UpdateMontantLivre($livr->VenteId,$produits[$produit], $unites[$produit], $qtes[$produit],$qtereçus[$produit]);
            }
        }
        // dd($livr,$cmde);
        $livr->save();
        $cmde->Edit_user = Auth::user()->id;
        $cmde->save();

        return redirect()->route('livrs.index')
            ->with('success', 'Livraison modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vente  $vente
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $livr = LivraisonVente::find($id);
        if ($livr == null) {
            return redirect()->route('livrs.index');
        }

        if (count(DB::table('factureventes')->where('LivraisonId', $id)->where('Supprimer', '0')->get()) > 0) {
            return redirect('/vente/livrs')->with('danger', "Cette livraison ne peut être supprimée car elle a déjà subi des facturations.");
        }

        removeLivraisonDetailsProduit($id);
        $livr->produits()->detach();

        $livr->Supprimer = true;
        $livr->Delete_user = Auth::user()->id;
        $livr->save();

        return redirect()->route('livrs.index')
            ->with('success', 'Livraison supprimée avec succès.');
    }
}
