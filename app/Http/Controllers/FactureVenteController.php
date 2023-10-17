<?php

namespace App\Http\Controllers;

use App\Helpers\helpers;

use App\Models\LivraisonVente;
use App\Models\FactureVente;
use App\Models\Vente;
use Brick\Math\BigInteger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FactureVenteController extends Controller
{

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:listvfact|createvfact|editvfact|deletevfact', ['only' => ['index','show']]);
        $this->middleware('permission:createvfact', ['only' => ['create','store']]);
        $this->middleware('permission:editvfact', ['only' => ['edit','update']]);
        $this->middleware('permission:deletevfact', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data = FactureVente::orderBy('id', 'DESC')->where('Supprimer', false)
            ->where('EntrepriseId', Auth::user()->EntrepriseId)
            ->where('MontantFacture', '>', 0)
            ->get();

        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Recouvrement</a></li><li class=\"breadcrumb-item\"><a href=\"/recouv/vfacts\">Facture client </a></li>";
        return view('recouv.vfacts.index', compact('data'))->with('Titre', 'Facture client')->with('Breadcrumb', $Breadcrumb);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $Cmdes = Vente::all()->where('Status', '1');
        $Livrs = LivraisonVente::all()->where('Status', '0');
        $livr = $Livrs->first();
        // $cmde = Vente::find($livr->VenteId);
        // dd($livr);
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Recouvrement</a></li><li class=\"breadcrumb-item\"><a href=\"/recouv/vfacts\">Facture client </a></li>";
        return view('recouv.vfacts.create', compact('livr', 'Livrs'))->with('Titre', 'Facture client')->with('Breadcrumb', $Breadcrumb);
    }

    public function addfact($id)
    {
        $livr = LivraisonVente::find($id);
        if ($livr == null) {
            return redirect()->route('vfacts.index');
        }
        $Livrs = LivraisonVente::where('Status', '0')->whereRaw('MontantLivre-MontantFacture')->get();
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Recouvrement</a></li><li class=\"breadcrumb-item\"><a href=\"/recouv/vfacts\">Facture client </a></li>";
        return view('recouv.vfacts.create', compact('livr', 'Livrs'))->with('Titre', 'Facture client')->with('Breadcrumb', $Breadcrumb);
    }



    public function getDetailsfact(Request $request)
    {

        if (!$request->id) {
            $htmlTable = '';
            $htmlDetailsCmde = '';
            $reference = '';
        } else {
            $htmlTable = '';
            $htmlDetailsCmde = '';
            $livr = LivraisonVente::find($request->id);
            $htmlTable = view('recouv.vfacts.tableCmde', compact('livr'))->render();
            $htmlDetailsLivr = view('recouv.vfacts.detailCmde', compact('livr'))->render();
            $reference = generateFactVente();
        }
        // dd($html);

        return response()->json(array('success' => true, 'htmlTable' => $htmlTable, 'htmlDetailsLivr' => $htmlDetailsLivr, 'reference' => $reference));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // dd($request);
        $this->validate(
            $request,
            [
                'DateFacture' => 'required|date_format:"d/m/Y"',
                'DateEcheance' => 'required|date_format:"d/m/Y"',
                'LivraisonId' => ['required','nullable'],
            ],
            [
                'DateFacture.required' => 'Le champ Date réception est obligatoire.',
                'DateFacture.date_format' => 'Le format de Date réception est incorrecte (dd/mm/yyyy).',
                'DateEcheance.required' => 'Le champ Date d\'échéance est obligatoire.',
                'DateEcheance.date_format' => 'Le format de Date d\'échéance est incorrecte (dd/mm/yyyy).',
                'LivraisonId.required' => 'Le choix de réference facture est obligatoire.',
                'LivraisonId.nullable' => 'Le choix de réference facture est obligatoire.',
                // 'ModePaiementId.required' => 'Le choix du mode paiement est obligatoire.',
            ]
        );

     

        if (in_array(null, $request->input('Qte', [])) || in_array('', array_map('trim', $request->input('Qte', [])))  || empty($request->input('Qte', []))) {
            return redirect()->route('vfacts.index')
                ->with('danger', 'Des données vides ont été saisies.');
        }
        // dd($request);
        $vfact = new FactureVente();
        $vfact->DateFacture = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateFacture)));
        $vfact->DateEcheance = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateEcheance)));
        $vfact->LivraisonId = $request->LivraisonId;
        $vfact->Reference = $request->Reference;
        $vfact->EntrepriseId = Auth::user()->EntrepriseId;
        $vfact->Create_user = Auth::user()->id;
        $vfact->save();

        $unites = $request->input('Unite', []);
        $produits = $request->input('Produit', []);
        $qtes = $request->input('Qte', []);

        // dd($unites,$produits,$qtes);

        $livr = LivraisonVente::find($request->LivraisonId);
        $cmde = Vente::find($livr->VenteId);

        for ($produit = 0; $produit < count($produits); $produit++) {
            if ($produits[$produit] != '' && $qtes[$produit] != '' && $qtes[$produit] != '0') {
                $cmdeproduit = $cmde->produits()->where('id', $produits[$produit])->wherePivot('UniteId', '=', $unites[$produit])->get()->first();

                if ($cmdeproduit != null) {
                    $montantHt = $qtes[$produit] * $cmdeproduit->pivot->Prix;
                    $remise = round(($montantHt * $cmdeproduit->pivot->Remise) / 100);
                    $tva = round(($montantHt * $cmdeproduit->pivot->Tva) / 100);
                    $montantttc = (int)($montantHt + $tva - $remise);
                    $vfact->MontantFacture = $vfact->MontantFacture + $montantttc;
                    $livr->MontantFacture = $livr->MontantFacture + $montantttc;

                    $vfact->produits()->attach(
                        $produits[$produit],
                        [
                            'Qte' => $qtes[$produit],
                            'UniteId' => $unites[$produit],
                        ]
                    );
                }

                AddFacturationVenteDetailsProduit($produits[$produit], $unites[$produit], $qtes[$produit], $livr->id);
            }
        }
        // dd($livr,$vfact);

        if ($livr->MontantReçu == $livr->MontantFacture) {
            $livr->Status = 1;
        }
        $livr->save();
        $vfact->save();

        return redirect()->route('vfacts.index')
            ->with('success', 'Facture créée avec succès.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vente  $recouv
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $vfact = FactureVente::find($id);
        if ($vfact == null) {
            return redirect()->route('vfacts.index');
        }
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Recouvrement</a></li><li class=\"breadcrumb-item\"><a href=\"/recouv/vfacts\">Facture client </a></li>";
        return view('recouv.vfacts.show', compact('vfact'))->with('Titre', 'Facture client')->with('Breadcrumb', $Breadcrumb);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vente  $recouv
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $vfact = FactureVente::find($id);
        if ($vfact == null) {
            return redirect()->route('vfacts.index');
        }
        $livr = LivraisonVente::find($vfact->LivraisonId);
        $Livrs = LivraisonVente::all()->where('id', $livr->id);
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Recouvrement</a></li><li class=\"breadcrumb-item\"><a href=\"/recouv/vfacts\">Facture client </a></li>";
        return view('recouv.vfacts.edit', compact('livr', 'Livrs', 'vfact'))->with('Titre', 'Facture client')->with('Breadcrumb', $Breadcrumb);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vente  $recouv
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate(
            $request,
            [
                'DateFacture' => 'required|date_format:"d/m/Y"',
                'DateEcheance' => 'required|date_format:"d/m/Y"',
                'LivraisonId' => 'required',
            ],
            [
                'DateFacture.required' => 'Le champ Date facture est obligatoire.',
                'DateFacture.date_format' => 'Le format de Date facture est incorrecte (dd/mm/yyyy).',
                'DateEcheance.required' => 'Le champ Date d\'échéance est obligatoire.',
                'DateEcheance.date_format' => 'Le format de Date d\'échéance est incorrecte (dd/mm/yyyy).',
                'LivraisonId.required' => 'Le choix de réference livraison est obligatoire.',
                // 'ModePaiementId.required' => 'Le choix du mode paiement est obligatoire.',
            ]
        );

        if (in_array(null, $request->input('Qte', [])) || in_array('', array_map('trim', $request->input('Qte', [])))  || empty($request->input('Qte', []))) {
            return redirect()->route('vfacts.index')
                ->with('danger', 'Des données vides ont été saisies.');
        }



        $vfact =  FactureVente::find($request->id);
        if ($vfact == null) {
            return redirect()->route('vfacts.index');
        }
        removeFacturationVenteDetailsProduit($request->id);
        $livr =  LivraisonVente::find($request->LivraisonId);
        $vfact->DateFacture = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateFacture)));
        $vfact->DateEcheance = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateEcheance)));
        $vfact->Reference = $request->Reference;
        $vfact->Edit_user = Auth::user()->id;
        $vfact->save();

        $vfact->produits()->detach();

        $unites = $request->input('Unite', []);
        $produits = $request->input('Produit', []);
        $qtes = $request->input('Qte', []);

        // dd($unites,$produits,$qtes);
        $cmde = Vente::find($livr->VenteId);

        for ($produit = 0; $produit < count($produits); $produit++) {
            if ($produits[$produit] != '' && $qtes[$produit] != '' && $qtes[$produit] != '0') {
                $cmdeproduit = $cmde->produits()->where('id', $produits[$produit])->wherePivot('UniteId', '=', $unites[$produit])->get()->first();

                if ($cmdeproduit != null) {
                    $montantHt = $qtes[$produit] * $cmdeproduit->pivot->Prix;
                    $remise = round(($montantHt * $cmdeproduit->pivot->Remise) / 100);
                    $tva = round(($montantHt * $cmdeproduit->pivot->Tva) / 100);
                    $montantttc = (int)($montantHt + $tva - $remise);
                    $vfact->MontantFacture = $vfact->MontantFacture + $montantttc;
                    $livr->MontantFacture = $livr->MontantFacture + $montantttc;

                    $vfact->produits()->attach(
                        $produits[$produit],
                        [
                            'Qte' => $qtes[$produit],
                            'UniteId' => $unites[$produit],
                        ]
                    );
                }

                AddFacturationDetailsProduit($produits[$produit], $unites[$produit], $qtes[$produit], $livr->id);
            }
        }
        // dd($livr,$vfact);

        $vfact->save();

        if ($livr->MontantReçu == $livr->MontantFacture) {
            $livr->Status = 1;
        }

        $livr->Edit_user = Auth::user()->id;
        $livr->save();

        return redirect()->route('vfacts.index')
            ->with('success', 'Facture modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vente  $recouv
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $vfact = FactureVente::find($id);
        if ($vfact == null) {
            return redirect()->route('vfacts.index');
        }
        if (count(DB::table('paiementventes')->where('FactureId', $id)->where('Supprimer', '0')->get()) > 0) {
            return redirect('/recouv/vfacts')->with('danger', "Cette facture ne peut être supprimée car elle a déjà subi des paiements.");
        }


        removeFacturationVenteDetailsProduit($id);
        $vfact->produits()->detach();
        $livr = LivraisonVente::find($vfact->LivraisonId);
        if ($livr != null) {
            $livr->MontantFacture = $livr->MontantFacture - $vfact->MontantFacture;
            if ($livr->MontantReçu == $livr->MontantFacture) {
                $livr->Status = 1;
            } else {
                $livr->Status = 0;
            }
            $livr->save();
        }



        $vfact->Supprimer = true;
        $vfact->Delete_user = Auth::user()->id;
        $vfact->save();

        return redirect()->route('vfacts.index')
            ->with('success', 'Facture supprimée avec succès.');
    }
}
