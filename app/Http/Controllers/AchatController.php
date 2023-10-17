<?php

namespace App\Http\Controllers;

use App\Models\Achat;
use App\Models\Compte;
use App\Models\Fournisseur;
use App\Models\ReceptionAchat;
use App\Models\Unite;
use App\Models\Produit;
use App\Models\ModePaiement;
use App\Models\FactureAchat;
use App\Models\PaiementAchat;
use App\Models\AvoirFr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AchatController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:listalist|createalist|editalist|deletealist', ['only' => ['index', 'show']]);
        $this->middleware('permission:createalist', ['only' => ['create', 'store']]);
        $this->middleware('permission:editalist', ['only' => ['edit', 'update']]);
        $this->middleware('permission:deletealist', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Achat::orderBy('id', 'DESC')->where('Supprimer', false)->where('Status', '1')->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Achat</a></li><li class=\"breadcrumb-item\"><a href=\"/achat/alists\">Listes</a></li>";

        return view('achat.alists.index', compact('data'))->with('Titre', 'Achats')->with('Breadcrumb', $Breadcrumb);
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
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Achat</a></li><li class=\"breadcrumb-item\"><a href=\"/achat/alists\">Achats </a></li>";
        return view('achat.alists.create', compact('fournisseurs', 'modepaiements', 'comptes', 'unites', 'produits'))->with('Titre', 'Achats')->with('Breadcrumb', $Breadcrumb);
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
                'DateAchat' => 'required|date_format:"d/m/Y"',
                'DateReception' => 'required|date_format:"d/m/Y"',
                'FournisseurId' => 'required',
                'CompteId' => 'required',
                'ModePaiementId' => 'required',
            ],
            [
                'DateAchat.required' => 'Le champ Date commande est obligatoire.',
                'DateAchat.date_format' => 'Le format de Date commande est incorrecte (dd/mm/yyyy).',
                'DateReception.required' => 'Le champ Date réception est obligatoire.',
                'DateReception.date_format' => 'Le format de Date réception est incorrecte (dd/mm/yyyy).',
                'FournisseurId.required' => 'Le choix du fournisseur est obligatoire.',
                'CompteId.required' => 'Le choix du compte est obligatoire.',
                'ModePaiementId.required' => 'Le choix du mode paiement est obligatoire.',
                // 'ModePaiementId.required' => 'Le choix du mode paiement est obligatoire.',
            ]
        );

        if (
            in_array(null, $request->input('Qte', [])) || in_array('', array_map('trim', $request->input('Qte', [])))  || empty($request->input('Qte', []))
            || in_array(null, $request->input('Remise', [])) || in_array('', array_map('trim', $request->input('Remise', [])))  || empty($request->input('Remise', []))
            || in_array(null, $request->input('TVA', [])) || in_array('', array_map('trim', $request->input('TVA', [])))  || empty($request->input('TVA', []))
            || in_array(null, $request->input('PrixAchat', [])) || in_array('', array_map('trim', $request->input('PrixAchat', [])))  || empty($request->input('PrixAchat', []))

        ) {
            return redirect()->route('alists.index')
                ->with('danger', 'Des données vides ont été saisies.');
        }

        $montantavoir = 0;
        $avoirfr = AvoirFr::where('FournisseurId', $request->FournisseurId)->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->first();
        if ($request->PaidWithAvoir == 'on') {
            if ($avoirfr != null) {
                $montantavoir = $avoirfr->Montant;
            }
        }

        $cmde = new Achat();
        //    $cmde->id = $request->id;
        $cmde->DateAchat = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateAchat)));
        $cmde->DateReception = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateReception)));
        $cmde->FournisseurId = $request->FournisseurId;
        $cmde->CompteId = $request->CompteId;
        $cmde->ModePaiementId = $request->ModePaiementId;
        $cmde->Reference = generateCmdeAchat();
        // $cmde->MontantPaye = $request->MontantPaye;
        $cmde->RemiseGlobale = $request->Remiseglobale;
        $cmde->EntrepriseId = Auth::user()->EntrepriseId;
        $cmde->Create_user = Auth::user()->id;
        $cmde->save();



        $recep = new ReceptionAchat();
        $recep->DateReception = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateReception)));
        $recep->AchatId = $cmde->id;
        $recep->Reference = generateRecepAchat();
        $recep->EntrepriseId = Auth::user()->EntrepriseId;
        $recep->Create_user = Auth::user()->id;
        // $recep->MontantReçu = $request->MontantPaye;
        // $recep->MontantFacture = $request->MontantPaye;
        $recep->save();

        $afact = new FactureAchat();
        $afact->DateFacture = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateReception)));
        $afact->DateEcheance = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateReception)));
        $afact->ReceptionId = $recep->id;
        $afact->Reference = generateFactAchat();

        // $afact->MontantFacture = $request->MontantPaye;
        $afact->EntrepriseId = Auth::user()->EntrepriseId;
        $afact->Create_user = Auth::user()->id;
        $afact->save();


        $paiement = new PaiementAchat();
        $paiement->DatePaiement = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateReception)));
        $paiement->FactureId = $afact->id;
        $paiement->Reference = generatePaiementAchat();
        $paiement->ModePaiementId = $request->ModePaiementId;
        $paiement->CompteId = $request->CompteId;
        $paiement->Remise = $request->Remiseglobale;
        $paiement->MontantAvoir = $montantavoir;
        $paiement->PaidWithAvoir = $request->PaidWithAvoir == 'on' ? 1 : 0;
        $paiement->IsAvoir = $request->IsAvoir == 'on' ? 1 : 0;
        $paiement->EntrepriseId = Auth::user()->EntrepriseId;
        $paiement->Create_user = Auth::user()->id;
        $paiement->save();

        $unites = $request->input('Unite', []);
        $produits = $request->input('Produit', []);
        $qtes = $request->input('Qte', []);
        $remises = $request->input('Remise', []);
        $tvas = $request->input('TVA', []);
        $prixachats = $request->input('PrixAchat', []);

        // dd($unites,$qtes ,$prixventes,$prixachats);
        for ($produit = 0; $produit < count($produits); $produit++) {
            if ($produits[$produit] != '' && $qtes[$produit] != '' && $qtes[$produit] != '0' && $prixachats[$produit] != '' && $prixachats[$produit] != '0') {
                $montantHt = $qtes[$produit] * $prixachats[$produit];
                $remise = round(($montantHt * $remises[$produit]) / 100);
                $tva = round(($montantHt * $tvas[$produit]) / 100);
                $montantttc = (int)($montantHt + $tva - $remise);

                $cmde->Qte = $cmde->Qte + $qtes[$produit];
                $cmde->MontantHT = $cmde->MontantHT + $montantHt;
                $cmde->MontantTTC = $cmde->MontantTTC  + $montantttc;
                $cmde->Remise =  $cmde->Remise + $remise;
                $cmde->Tva = $cmde->Tva +  $tva;
                $cmde->MontantReçu = $cmde->MontantReçu  + $montantttc;

                $cmde->produits()->attach(
                    $produits[$produit],
                    [
                        'Qte' => $qtes[$produit],
                        'QteReçu' => $qtes[$produit],
                        'MontantReçu' => $montantttc,
                        'Remise' => $remises[$produit],
                        'Tva' => $tvas[$produit],
                        'UniteId' => $unites[$produit],
                        'Prix' => $prixachats[$produit],
                        'Montant' => $montantttc
                    ]
                );




                // if ($produit != '') {
                $recep->produits()->attach(
                    $produits[$produit],
                    [
                        'Qte' => $qtes[$produit],
                        'UniteId' => $unites[$produit],
                        'QteFacture' => $qtes[$produit],
                    ]
                );


                DetailsProduit($produits[$produit], $unites[$produit], $qtes[$produit]);

                $afact->produits()->attach(
                    $produits[$produit],
                    [
                        'Qte' => $qtes[$produit],
                        'UniteId' => $unites[$produit],
                    ]
                );

                // }

                // addDetailsProduit($produits[$produit], $unites[$produit], $qtes[$produit]);
            }
        }

        if ($cmde->MontantTTC > 0) {
            $recep->MontantReçu = $cmde->MontantReçu;
            $recep->MontantFacture = $cmde->MontantReçu;
            if ($recep->MontantReçu == $recep->MontantFacture) {
                $recep->Status = 1;
            }
            $recep->save();


            $paiement->MontantRemis = $request->MontantRemis + $montantavoir + $request->Remiseglobale;
            $monnaie = ($paiement->MontantRemis - $cmde->MontantReçu) > 0 ? $paiement->MontantRemis - $cmde->MontantReçu : 0;
            if ($monnaie > 0) {
                $afact->MontantPaye = $cmde->MontantReçu;
                $cmde->MontantPaye = $cmde->MontantReçu;
            } else {
                $afact->MontantPaye = $paiement->MontantRemis;
                $cmde->MontantPaye = $paiement->MontantRemis;
            }

            // $afact->MontantPaye = $cmde->MontantReçu;
            $afact->MontantFacture = $cmde->MontantReçu;
            if ($afact->MontantPaye == $afact->MontantFacture) {
                $afact->Status = 1;
            }

            $afact->save();

            if ($cmde->MontantTTC == $cmde->MontantPaye) {
                $cmde->Status = 1;
                $cmde->RefAchat = generateRefAchat($cmde->id);
            }
            $cmde->save();


            $paiement->Montant = $afact->MontantPaye;
            $paiement->Monnaie = $monnaie;
            $paiement->save();


            $compte = Compte::find($request->CompteId);

            // dd($avoirfr);

            if ($avoirfr != null) {
                $avoirfr = AvoirFr::find($avoirfr->id);
                if ($montantavoir > 0) {
                    //retrait ancien montant avoir
                    $avoirfr->Montant = $avoirfr->Montant - $montantavoir;
                    $avoirfr->Edit_user = Auth::user()->id;
                    $avoirfr->Modif_util = Carbon::now();
                    $avoirfr->save();


                    $libelle = "Paiement en avoir facture " . $paiement->Reference;
                    AddDetailsAvoirFr($avoirfr->id, $libelle, -1, $montantavoir);

                    if ($compte != null) {
                        $compte->Solde = $compte->Solde + $montantavoir;
                        $compte->save();

                        AddDetailsCompte($compte->id, $libelle,0, $montantavoir);
                    }
                }

                if ($request->IsAvoir == 'on') {
                    if ($monnaie > 0) {
                        //ajout nouveau montant avoir
                        $avoirfr->Montant = $avoirfr->Montant + $monnaie;
                        $avoirfr->Edit_user = Auth::user()->id;
                        $avoirfr->Modif_util = Carbon::now();
                        $avoirfr->save();

                        $libelle = "Avoir sur facture " . $paiement->Reference;
                        AddDetailsAvoirFr($avoirfr->id, $libelle, 1, $monnaie);

                        if ($compte != null) {
                            $compte->Solde = $compte->Solde - $monnaie;
                            $compte->save();

                            AddDetailsCompte($compte->id, $libelle, 0, $monnaie);
                        }
                    }
                }
            }


            if ($compte != null) {

                if ($montantavoir > 0) {
                    $compte->Solde = $compte->Solde - $montantavoir;
                    $compte->save();
                    $libelle = "Paiement en avoir facture " . $paiement->Reference;
                    AddDetailsCompte($compte->id, $libelle, $montantavoir,0);
                }

                if ($paiement->Montant > $montantavoir) {
                    $compte->Solde = $compte->Solde - $paiement->Montant + $montantavoir;
                    $compte->save();
                    $modepaiement = ModePaiement::find($paiement->ModePaiementId);
                    if ($modepaiement != null) {
                        $libelle = "Paiement en " . $modepaiement->Nom . " facture " . $paiement->Reference;
                        AddDetailsCompte($compte->id, $libelle,$paiement->Montant-$montantavoir, 0 );
                    }
                }
            }

            return redirect()->route('alists.index')
                ->with('success', 'Achat créé avec succès.');
        } else {
            $cmde->delete();
            $recep->delete();
            $afact->delete();
            $paiement->delete();

            return redirect()->route('alists.index')
                ->with('danger', 'Des données vides ont été saisies.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Achat  $achat
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cmde = Achat::find($id);
        if ($cmde == null) {
            return redirect()->route('alists.index');
        }
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Achat</a></li><li class=\"breadcrumb-item\"><a href=\"/achat/cmdes\">Achats </a></li>";
        return view('achat.alists.show', compact('cmde'))->with('Titre', 'Achats')->with('Breadcrumb', $Breadcrumb);
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
                if ($unite->id == $produit->UniteId) {
                    $html .= $unite->id . '/' . $unite->pivot->PrixAchat . '~' . $unite->Nom . '~selected|';
                } else {
                    $html .= $unite->id . '/' . $unite->pivot->PrixAchat . '~' . $unite->Nom . '~|';
                }
            }
        }
        // dd($html);

        return response()->json($html);
    }


    public function getFrAvoir(Request $request)
    {

        if (!$request->id) {
            $avoir = 0;
        } else {
            $avoir = 0;
            $avoirfr = AvoirFr::where('FournisseurId', $request->id)->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->first();
            if ($avoirfr != null) {
                $avoir = $avoirfr->Montant;
            }
        }
        // dd($html);

        return response()->json($avoir);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Achat  $achat
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cmde = Achat::find($id);
        $cmde = Achat::find($id);
        if ($cmde == null) {
            return redirect()->route('alists.index');
        }
        $unites = Unite::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $produits = Produit::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();

        $fournisseurs = Fournisseur::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $modepaiements = ModePaiement::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $comptes = Compte::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Achat</a></li><li class=\"breadcrumb-item\"><a href=\"/achat/alists\">Achats </a></li>";
        return view('achat.alists.edit', compact('fournisseurs', 'modepaiements', 'comptes', 'unites', 'produits', 'cmde'))->with('Titre', 'Achats')->with('Breadcrumb', $Breadcrumb);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Achat  $achat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'DateAchat' => 'required|date_format:"d/m/Y"',
                'DateReception' => 'required|date_format:"d/m/Y"',
                'FournisseurId' => 'required',
                'CompteId' => 'required',
                'ModePaiementId' => 'required',
            ],
            [
                'DateAchat.required' => 'Le champ Date commande est obligatoire.',
                'DateAchat.date_format' => 'Le format de Date commande est incorrecte (dd/mm/yyyy).',
                'DateReception.required' => 'Le champ Date réception est obligatoire.',
                'DateReception.date_format' => 'Le format de Date réception est incorrecte (dd/mm/yyyy).',
                'FournisseurId.required' => 'Le choix du fournisseur est obligatoire.',
                'CompteId.required' => 'Le choix du compte est obligatoire.',
                'ModePaiementId.required' => 'Le choix du mode paiement est obligatoire.',
                // 'ModePaiementId.required' => 'Le choix du mode paiement est obligatoire.',
            ]
        );

        // dd($request->input('Unite', []),$request->input('Produit', []),$request->input('TVA', []),$request->input('PrixAchat', []),$request->input('QteRecus', []));

        if (
            in_array(null, $request->input('Qte', [])) || in_array('', array_map('trim', $request->input('Qte', [])))  || empty($request->input('Qte', []))
            || in_array(null, $request->input('Remise', [])) || in_array('', array_map('trim', $request->input('Remise', [])))  || empty($request->input('Remise', []))
            || in_array(null, $request->input('TVA', [])) || in_array('', array_map('trim', $request->input('TVA', [])))  || empty($request->input('TVA', []))
            || in_array(null, $request->input('PrixAchat', [])) || in_array('', array_map('trim', $request->input('PrixAchat', [])))  || empty($request->input('PrixAchat', []))

        ) {
            return redirect()->route('alists.index')
                ->with('danger', 'Des données vides ont été saisies.');
        }

        DeleteAchat($request->id);
        //    $cmde->id = $request->id;
        $montantavoir = 0;
        $avoirfr = AvoirFr::where('FournisseurId', $request->FournisseurId)->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->first();
        if ($request->PaidWithAvoir == 'on') {
            if ($avoirfr != null) {
                $montantavoir = $avoirfr->Montant;
            }
        }

        $cmde = new Achat();
        //    $cmde->id = $request->id;
        $cmde->DateAchat = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateAchat)));
        $cmde->DateReception = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateReception)));
        $cmde->FournisseurId = $request->FournisseurId;
        $cmde->CompteId = $request->CompteId;
        $cmde->ModePaiementId = $request->ModePaiementId;
        $cmde->Reference = generateCmdeAchat();
        // $cmde->MontantPaye = $request->MontantPaye;
        $cmde->RemiseGlobale = $request->Remiseglobale;
        $cmde->EntrepriseId = Auth::user()->EntrepriseId;
        $cmde->Create_user = Auth::user()->id;
        $cmde->save();



        $recep = new ReceptionAchat();
        $recep->DateReception = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateReception)));
        $recep->AchatId = $cmde->id;
        $recep->Reference = generateRecepAchat();
        $recep->EntrepriseId = Auth::user()->EntrepriseId;
        $recep->Create_user = Auth::user()->id;
        // $recep->MontantReçu = $request->MontantPaye;
        // $recep->MontantFacture = $request->MontantPaye;
        $recep->save();

        $afact = new FactureAchat();
        $afact->DateFacture = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateReception)));
        $afact->DateEcheance = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateReception)));
        $afact->ReceptionId = $recep->id;
        $afact->Reference = generateFactAchat();

        // $afact->MontantFacture = $request->MontantPaye;
        $afact->EntrepriseId = Auth::user()->EntrepriseId;
        $afact->Create_user = Auth::user()->id;
        $afact->save();


        $paiement = new PaiementAchat();
        $paiement->DatePaiement = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateReception)));
        $paiement->FactureId = $afact->id;
        $paiement->Reference = generatePaiementAchat();
        $paiement->ModePaiementId = $request->ModePaiementId;
        $paiement->CompteId = $request->CompteId;
        $paiement->Remise = $request->Remiseglobale;
        $paiement->MontantAvoir = $montantavoir;
        $paiement->PaidWithAvoir = $request->PaidWithAvoir == 'on' ? 1 : 0;
        $paiement->IsAvoir = $request->IsAvoir == 'on' ? 1 : 0;
        $paiement->EntrepriseId = Auth::user()->EntrepriseId;
        $paiement->Create_user = Auth::user()->id;
        $paiement->save();

        $unites = $request->input('Unite', []);
        $produits = $request->input('Produit', []);
        $qtes = $request->input('Qte', []);
        $remises = $request->input('Remise', []);
        $tvas = $request->input('TVA', []);
        $prixachats = $request->input('PrixAchat', []);

        // dd($unites,$qtes ,$prixventes,$prixachats);
        for ($produit = 0; $produit < count($produits); $produit++) {
            if ($produits[$produit] != '' && $qtes[$produit] != '' && $qtes[$produit] != '0' && $prixachats[$produit] != '' && $prixachats[$produit] != '0') {
                $montantHt = $qtes[$produit] * $prixachats[$produit];
                $remise = round(($montantHt * $remises[$produit]) / 100);
                $tva = round(($montantHt * $tvas[$produit]) / 100);
                $montantttc = (int)($montantHt + $tva - $remise);

                $cmde->Qte = $cmde->Qte + $qtes[$produit];
                $cmde->MontantHT = $cmde->MontantHT + $montantHt;
                $cmde->MontantTTC = $cmde->MontantTTC  + $montantttc;
                $cmde->Remise =  $cmde->Remise + $remise;
                $cmde->Tva = $cmde->Tva +  $tva;
                $cmde->MontantReçu = $cmde->MontantReçu  + $montantttc;

                $cmde->produits()->attach(
                    $produits[$produit],
                    [
                        'Qte' => $qtes[$produit],
                        'QteReçu' => $qtes[$produit],
                        'MontantReçu' => $montantttc,
                        'Remise' => $remises[$produit],
                        'Tva' => $tvas[$produit],
                        'UniteId' => $unites[$produit],
                        'Prix' => $prixachats[$produit],
                        'Montant' => $montantttc
                    ]
                );




                // if ($produit != '') {
                $recep->produits()->attach(
                    $produits[$produit],
                    [
                        'Qte' => $qtes[$produit],
                        'UniteId' => $unites[$produit],
                        'QteFacture' => $qtes[$produit],
                    ]
                );


                DetailsProduit($produits[$produit], $unites[$produit], $qtes[$produit]);

                $afact->produits()->attach(
                    $produits[$produit],
                    [
                        'Qte' => $qtes[$produit],
                        'UniteId' => $unites[$produit],
                    ]
                );

                // }

                // addDetailsProduit($produits[$produit], $unites[$produit], $qtes[$produit]);
            }
        }

        if ($cmde->MontantTTC > 0) {
            $recep->MontantReçu = $cmde->MontantReçu;
            $recep->MontantFacture = $cmde->MontantReçu;
            if ($recep->MontantReçu == $recep->MontantFacture) {
                $recep->Status = 1;
            }
            $recep->save();


            $paiement->MontantRemis = $request->MontantRemis + $montantavoir + $request->Remiseglobale;
            $monnaie = ($paiement->MontantRemis - $cmde->MontantReçu) > 0 ? $paiement->MontantRemis - $cmde->MontantReçu : 0;
            if ($monnaie > 0) {
                $afact->MontantPaye = $cmde->MontantReçu;
                $cmde->MontantPaye = $cmde->MontantReçu;
            } else {
                $afact->MontantPaye = $paiement->MontantRemis;
                $cmde->MontantPaye = $paiement->MontantRemis;
            }

            // $afact->MontantPaye = $cmde->MontantReçu;
            $afact->MontantFacture = $cmde->MontantReçu;
            if ($afact->MontantPaye == $afact->MontantFacture) {
                $afact->Status = 1;
            }

            $afact->save();

            if ($cmde->MontantTTC == $cmde->MontantPaye) {
                $cmde->Status = 1;
                $cmde->RefAchat = generateRefAchat($cmde->id);
            }
            $cmde->save();


            $paiement->Montant = $afact->MontantPaye;
            $paiement->Monnaie = $monnaie;
            $paiement->save();


            $compte = Compte::find($request->CompteId);

            // dd($avoirfr);

           
            if ($avoirfr != null) {
                $avoirfr = AvoirFr::find($avoirfr->id);
                if ($montantavoir > 0) {
                    //retrait ancien montant avoir
                    $avoirfr->Montant = $avoirfr->Montant - $montantavoir;
                    $avoirfr->Edit_user = Auth::user()->id;
                    $avoirfr->Modif_util = Carbon::now();
                    $avoirfr->save();


                    $libelle = "Paiement en avoir facture " . $paiement->Reference;
                    AddDetailsAvoirFr($avoirfr->id, $libelle, -1, $montantavoir);

                    if ($compte != null) {
                        $compte->Solde = $compte->Solde + $montantavoir;
                        $compte->save();

                        AddDetailsCompte($compte->id, $libelle,0, $montantavoir);
                    }
                }

                if ($request->IsAvoir == 'on') {
                    if ($monnaie > 0) {
                        //ajout nouveau montant avoir
                        $avoirfr->Montant = $avoirfr->Montant + $monnaie;
                        $avoirfr->Edit_user = Auth::user()->id;
                        $avoirfr->Modif_util = Carbon::now();
                        $avoirfr->save();

                        $libelle = "Avoir sur facture " . $paiement->Reference;
                        AddDetailsAvoirFr($avoirfr->id, $libelle, 1, $monnaie);

                        if ($compte != null) {
                            $compte->Solde = $compte->Solde - $monnaie;
                            $compte->save();

                            AddDetailsCompte($compte->id, $libelle, 0, $monnaie);
                        }
                    }
                }
            }


            if ($compte != null) {

                if ($montantavoir > 0) {
                    $compte->Solde = $compte->Solde - $montantavoir;
                    $compte->save();
                    $libelle = "Paiement en avoir facture " . $paiement->Reference;
                    AddDetailsCompte($compte->id, $libelle, $montantavoir,0);
                }

                if ($paiement->Montant > $montantavoir) {
                    $compte->Solde = $compte->Solde - $paiement->Montant + $montantavoir;
                    $compte->save();
                    $modepaiement = ModePaiement::find($paiement->ModePaiementId);
                    if ($modepaiement != null) {
                        $libelle = "Paiement en " . $modepaiement->Nom . " facture " . $paiement->Reference;
                        AddDetailsCompte($compte->id, $libelle,$paiement->Montant-$montantavoir, 0 );
                    }
                }
            }

            return redirect()->route('alists.index')
                ->with('success', 'Achat créé avec succès.');
        } else {
            $cmde->delete();
            $recep->delete();
            $afact->delete();
            $paiement->delete();

            return redirect()->route('alists.index')
                ->with('danger', 'Des données vides ont été saisies.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Achat  $achat
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (count(DB::table('receptionachats')->where('AchatId', $id)->where('Supprimer', '0')->get()) > 0) {
            return redirect('/achat/alists')->with('danger', "Cet achat ne peut être supprimé car il a déjà subi des réceptions.");
        }

        $cmde = Achat::find($id);
        if ($cmde != null) {
            DeleteAchat($cmde->id);
            return redirect()->route('alists.index')
                ->with('success', 'Achat supprimé avec succès.');
        } else {
            return redirect()->route('alists.index')
                ->with('danger', 'Cet achat n\'existe pas.');
        }
    }
}
