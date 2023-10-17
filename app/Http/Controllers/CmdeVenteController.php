<?php

namespace App\Http\Controllers;

use App\Helpers\helpers;

use App\Models\Vente;
use App\Models\AvoirClt;
use App\Models\Compte;
use App\Models\Client;
use App\Models\LivraisonVente;
use App\Models\Unite;
use App\Models\Produit;
use App\Models\ModePaiement;
use App\Models\FactureVente;
use App\Models\PaiementVente;
use Brick\Math\BigInteger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CmdeVenteController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:listvcmde|createvcmde|editvcmde|deletevcmde', ['only' => ['index', 'show']]);
        $this->middleware('permission:createvcmde', ['only' => ['create', 'store']]);
        $this->middleware('permission:editvcmde', ['only' => ['edit', 'update']]);
        $this->middleware('permission:deletevcmde', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Vente::orderBy('id', 'DESC')->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Vente</a></li><li class=\"breadcrumb-item\"><a href=\"/vente/vcmdes\">Commande client</a></li>";

        return view('vente.vcmdes.index', compact('data'))->with('Titre', 'Commande client')->with('Breadcrumb', $Breadcrumb);
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
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Vente</a></li><li class=\"breadcrumb-item\"><a href=\"/vente/vcmdes\">Commande client </a></li>";
        return view('vente.vcmdes.create', compact('clients', 'modepaiements', 'comptes', 'unites', 'produits'))->with('Titre', 'Commande client')->with('Breadcrumb', $Breadcrumb);
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
                'DateVente' => 'required|date_format:"d/m/Y"',
                'DateLivraison' => 'required|date_format:"d/m/Y"',
                'ClientId' => 'required',
                'CompteId' => 'required',
                'ModePaiementId' => 'required',
            ],
            [
                'DateVente.required' => 'Le champ Date commande est obligatoire.',
                'DateVente.date_format' => 'Le format de Date commande est incorrecte (dd/mm/yyyy).',
                'DateLivraison.required' => 'Le champ Date livraison est obligatoire.',
                'DateLivraison.date_format' => 'Le format de Date livraison est incorrecte (dd/mm/yyyy).',
                'ClientId.required' => 'Le choix du client est obligatoire.',
                'CompteId.required' => 'Le choix du compte est obligatoire.',
                'ModePaiementId.required' => 'Le choix du mode paiement est obligatoire.',
                // 'ModePaiementId.required' => 'Le choix du mode paiement est obligatoire.',
            ]
        );

        if (
            in_array(null, $request->input('Qte', [])) || in_array('', array_map('trim', $request->input('Qte', [])))  || empty($request->input('Qte', []))
            || in_array(null, $request->input('QteLivres', [])) || in_array('', array_map('trim', $request->input('QteLivres', [])))  || empty($request->input('QteLivres', []))
            || in_array(null, $request->input('Remise', [])) || in_array('', array_map('trim', $request->input('Remise', [])))  || empty($request->input('Remise', []))
            || in_array(null, $request->input('TVA', [])) || in_array('', array_map('trim', $request->input('TVA', [])))  || empty($request->input('TVA', []))
            || in_array(null, $request->input('PrixVente', [])) || in_array('', array_map('trim', $request->input('PrixVente', [])))  || empty($request->input('PrixVente', []))

        ) {
            return redirect()->route('vcmdes.index')
                ->with('danger', 'Des données vides ont été saisies.');
        }

        $montantavoir = 0;
        $avoirclt = AvoirClt::where('ClientId', $request->ClientId)->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->first();
        if ($request->PaidWithAvoir == 'on') {
            if ($avoirclt != null) {
                $montantavoir = $avoirclt->Montant;
            }
        }

        $cmde = new Vente();
        //    $cmde->id = $request->id;
        $cmde->DateVente = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateVente)));
        $cmde->DateLivraison = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateLivraison)));
        $cmde->ClientId = $request->ClientId;
        $cmde->CompteId = $request->CompteId;
        $cmde->ModePaiementId = $request->ModePaiementId;
        $cmde->Reference = generateCmdeVente();
        $cmde->MontantPaye = 0;
        $cmde->MontantLivre = 0;
        // $cmde->MontantFacture = 0;
        $cmde->RemiseGlobale = $request->Remiseglobale;
        $cmde->EntrepriseId = Auth::user()->EntrepriseId;
        $cmde->Create_user = Auth::user()->id;
        $cmde->save();



        $livr = new LivraisonVente();
        $livr->DateLivraison = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateLivraison)));
        $livr->VenteId = $cmde->id;
        $livr->Reference = generateLivrVente();
        $livr->EntrepriseId = Auth::user()->EntrepriseId;
        $livr->Create_user = Auth::user()->id;
        $livr->MontantLivre = 0;
        $livr->MontantFacture = 0;
        $livr->save();

        $vfact = new FactureVente();
        $vfact->DateFacture = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateLivraison)));
        $vfact->DateEcheance = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateLivraison)));
        $vfact->LivraisonId = $livr->id;
        $vfact->Reference = generateFactVente();

        $vfact->MontantFacture = 0;
        $vfact->MontantPaye = 0;
        $vfact->EntrepriseId = Auth::user()->EntrepriseId;
        $vfact->Create_user = Auth::user()->id;
        $vfact->save();


        $paiement = new PaiementVente();
        $paiement->DatePaiement = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateLivraison)));
        $paiement->FactureId = $vfact->id;
        $paiement->Reference = generatePaiementVente();
        $paiement->ModePaiementId = $request->ModePaiementId;
        $paiement->CompteId = $request->CompteId;
        $paiement->Remise = $request->Remiseglobale;
        $paiement->MontantAvoir = $montantavoir;
        $paiement->Montant = 0;
        $paiement->PaidWithAvoir = $request->PaidWithAvoir == 'on' ? 1 : 0;
        $paiement->IsAvoir = $request->IsAvoir == 'on' ? 1 : 0;
        $paiement->EntrepriseId = Auth::user()->EntrepriseId;
        $paiement->Create_user = Auth::user()->id;
        $paiement->save();

        $unites = $request->input('Unite', []);
        $produits = $request->input('Produit', []);
        $qtelivres = $request->input('QteLivres', []);
        $qtes = $request->input('Qte', []);
        $remises = $request->input('Remise', []);
        $tvas = $request->input('TVA', []);
        $prixventes = $request->input('PrixVente', []);
        // dd( $qtelivres);


        for ($produit = 0; $produit < count($produits); $produit++) {
            if ($produits[$produit] != '' && $qtes[$produit] != '' && $qtes[$produit] != '0' && $prixventes[$produit] != '' && $prixventes[$produit] != '0') {
                $montantHt = $qtes[$produit] * $prixventes[$produit];
                $remise = round(($montantHt * $remises[$produit]) / 100);
                $tva = round(($montantHt * $tvas[$produit]) / 100);
                $montantttc = (int)($montantHt + $tva - $remise);

                $montantHtRecu = $qtelivres[$produit] * $prixventes[$produit];
                $remiseRecu  = round(($montantHtRecu * $remises[$produit]) / 100);
                $tvaRecu  = round(($montantHtRecu * $tvas[$produit]) / 100);
                $montantttcRecu  = (int)($montantHtRecu + $tvaRecu - $remiseRecu);

                $cmde->Qte = $cmde->Qte + $qtes[$produit];
                $cmde->MontantHT = $cmde->MontantHT + $montantHt;
                $cmde->MontantTTC = $cmde->MontantTTC  + $montantttc;
                $cmde->Remise =  $cmde->Remise + $remise;
                $cmde->Tva = $cmde->Tva +  $tva;
                $cmde->MontantLivre = $cmde->MontantLivre    + $montantttcRecu;

                $cmde->produits()->attach(
                    $produits[$produit],
                    [
                        'Qte' => $qtes[$produit],
                        'QteLivre' => $qtelivres[$produit],
                        'MontantLivre' => $montantttc,
                        'Remise' => $remises[$produit],
                        'Tva' => $tvas[$produit],
                        'UniteId' => $unites[$produit],
                        'Prix' => $prixventes[$produit],
                        'Montant' => $montantttcRecu
                    ]
                );


                if ($qtelivres[$produit] > 0) {
                    // if ($produit != '') {
                    $livr->produits()->attach(
                        $produits[$produit],
                        [
                            'Qte' => $qtes[$produit],
                            'UniteId' => $unites[$produit],
                            'QteFacture' => $qtelivres[$produit],
                        ]
                    );

                    DetailsVenteProduit($produits[$produit], $unites[$produit], $qtelivres[$produit]);

                    $vfact->produits()->attach(
                        $produits[$produit],
                        [
                            'Qte' => $qtes[$produit],
                            'UniteId' => $unites[$produit],
                        ]
                    );
                }
            }
        }

        if ($cmde->MontantTTC > 0) {
            $cmde->save();
            if ($cmde->MontantLivre > 0) {
                $livr->MontantLivre = $cmde->MontantLivre;
                $livr->MontantFacture = $cmde->MontantLivre;
                if ($livr->MontantLivre == $livr->MontantFacture) {
                    $livr->Status = 1;
                }
                $livr->save();


                $paiement->MontantRemis = $request->MontantRemis + $montantavoir + $request->Remiseglobale;
                $monnaie = ($paiement->MontantRemis - $cmde->MontantLivre) > 0 ? $paiement->MontantRemis - $cmde->MontantLivre : 0;
                if ($monnaie > 0) {
                    $vfact->MontantPaye = $cmde->MontantLivre;
                    $cmde->MontantPaye = $cmde->MontantLivre;
                } else {
                    $vfact->MontantPaye = $paiement->MontantRemis;
                    $cmde->MontantPaye = $paiement->MontantRemis;
                }

                // $vfact->MontantPaye = $cmde->MontantLivre;
                $vfact->MontantFacture = $cmde->MontantLivre;
                if ($vfact->MontantPaye == $vfact->MontantFacture) {
                    $vfact->Status = 1;
                }

                $vfact->save();

                if ($cmde->MontantTTC == $cmde->MontantPaye) {
                    $cmde->Status = 1;
                    $cmde->RefVente = generateRefVente($cmde->id);
                }
                $cmde->save();


                $paiement->Montant = $vfact->MontantPaye;
                $paiement->Monnaie = $monnaie;
                $paiement->save();


                $compte = Compte::find($request->CompteId);

                // dd($avoirclt);

                if ($avoirclt != null) {
                    $avoirclt = AvoirClt::find($avoirclt->id);
                    if ($montantavoir > 0) {
                        //retrait ancien montant avoir
                        $avoirclt->Montant = $avoirclt->Montant - $montantavoir;
                        $avoirclt->Edit_user = Auth::user()->id;
                        $avoirclt->Modif_util = Carbon::now();
                        $avoirclt->save();


                        $libelle = "Paiement en avoir facture " . $paiement->Reference;
                        AddDetailsAvoirClt($avoirclt->id, $libelle, -1, $montantavoir);

                        if ($compte != null) {
                            $compte->Solde = $compte->Solde - $montantavoir;
                            $compte->save();

                            AddDetailsCompte($compte->id, $libelle, $montantavoir, 0);
                        }
                    }

                    if ($request->IsAvoir == 'on') {
                        if ($monnaie > 0) {
                            //ajout nouveau montant avoir
                            $avoirclt->Montant = $avoirclt->Montant + $monnaie;
                            $avoirclt->Edit_user = Auth::user()->id;
                            $avoirclt->Modif_util = Carbon::now();
                            $avoirclt->save();

                            $libelle = "Avoir sur facture " . $paiement->Reference;
                            AddDetailsAvoirClt($avoirclt->id, $libelle, 1, $monnaie);

                            if ($compte != null) {
                                $compte->Solde = $compte->Solde + $monnaie;
                                $compte->save();

                                AddDetailsCompte($compte->id, $libelle, 0, $monnaie);
                            }
                        }
                    }
                }

                if ($compte != null) {

                    if ($montantavoir > 0) {
                        $compte->Solde = $compte->Solde + $montantavoir;
                        $compte->save();
                        $libelle = "Paiement en avoir facture " . $paiement->Reference;
                        AddDetailsCompte($compte->id, $libelle, 0, $montantavoir);
                    }

                    if ($paiement->Montant > $montantavoir) {
                        $compte->Solde = $compte->Solde + $paiement->Montant - $montantavoir;
                        $compte->save();
                        $modepaiement = ModePaiement::find($paiement->ModePaiementId);
                        if ($modepaiement != null) {
                            $libelle = "Paiement en " . $modepaiement->Nom . " facture " . $paiement->Reference;
                            AddDetailsCompte($compte->id, $libelle, 0, $paiement->Montant);
                        }
                    }
                }
            } else {

                $livr->delete();
                $vfact->delete();
                $paiement->delete();
            }





            return redirect()->route('vcmdes.index')
                ->with('success', 'Commande créée avec succès.');
        } else {
            $cmde->delete();
            $livr->delete();
            $vfact->delete();
            $paiement->delete();

            return redirect()->route('vcmdes.index')
                ->with('danger', 'Des données vides ont été saisies.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vente  $vente
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cmde = Vente::find($id);
        if ($cmde == null) {
            return redirect()->route('vcmdes.index');
        }
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Vente</a></li><li class=\"breadcrumb-item\"><a href=\"/vente/cmdes\">Commande client </a></li>";
        return view('vente.vcmdes.show', compact('cmde'))->with('Titre', 'Commande client')->with('Breadcrumb', $Breadcrumb);
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
                if ($unite->pivot->Qte > 0) {
                    if ($unite->id == $produit->UniteId) {
                        $html .= $unite->id . '/' . $unite->pivot->PrixVente . '/' . $unite->pivot->Qte . '~' . $unite->Nom . '~selected|';
                    } else {
                        $html .= $unite->id . '/' . $unite->pivot->PrixVente  . '/' . $unite->pivot->Qte . '~' . $unite->Nom . '~~' . $unite->pivot->Qte . '|';
                    }
                }
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
     * @param  \App\Models\Vente  $vente
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cmde = Vente::find($id);
        $cmde = Vente::find($id);
        if ($cmde == null) {
            return redirect()->route('vcmdes.index');
        }
        $unites = Unite::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $produits = Produit::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();

        $clients = Client::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $modepaiements = ModePaiement::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $comptes = Compte::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Vente</a></li><li class=\"breadcrumb-item\"><a href=\"/vente/vcmdes\">Commande client </a></li>";
        return view('vente.vcmdes.edit', compact('clients', 'modepaiements', 'comptes', 'unites', 'produits', 'cmde'))->with('Titre', 'Commande client')->with('Breadcrumb', $Breadcrumb);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vente  $vente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'DateVente' => 'required|date_format:"d/m/Y"',
                'DateLivraison' => 'required|date_format:"d/m/Y"',
                'ClientId' => 'required',
                'CompteId' => 'required',
                'ModePaiementId' => 'required',
            ],
            [
                'DateVente.required' => 'Le champ Date commande est obligatoire.',
                'DateVente.date_format' => 'Le format de Date commande est incorrecte (dd/mm/yyyy).',
                'DateLivraison.required' => 'Le champ Date livraison est obligatoire.',
                'DateLivraison.date_format' => 'Le format de Date livraison est incorrecte (dd/mm/yyyy).',
                'ClientId.required' => 'Le choix du client est obligatoire.',
                'CompteId.required' => 'Le choix du compte est obligatoire.',
                'ModePaiementId.required' => 'Le choix du mode paiement est obligatoire.',
                // 'ModePaiementId.required' => 'Le choix du mode paiement est obligatoire.',
            ]
        );

        // dd($request->input('Unite', []),$request->input('Produit', []),$request->input('TVA', []),$request->input('PrixVente', []),$request->input('QteLivres', []));

        if (
            in_array(null, $request->input('Qte', [])) || in_array('', array_map('trim', $request->input('Qte', [])))  || empty($request->input('Qte', []))
            || in_array(null, $request->input('QteLivres', [])) || in_array('', array_map('trim', $request->input('QteLivres', [])))  || empty($request->input('QteLivres', []))
            || in_array(null, $request->input('Remise', [])) || in_array('', array_map('trim', $request->input('Remise', [])))  || empty($request->input('Remise', []))
            || in_array(null, $request->input('TVA', [])) || in_array('', array_map('trim', $request->input('TVA', [])))  || empty($request->input('TVA', []))
            || in_array(null, $request->input('PrixVente', [])) || in_array('', array_map('trim', $request->input('PrixVente', [])))  || empty($request->input('PrixVente', []))

        ) {
            return redirect()->route('vcmdes.index')
                ->with('danger', 'Des données vides ont été saisies.');
        }
        DeleteVente($request->id);

        $montantavoir = 0;
        $avoirclt = AvoirClt::where('ClientId', $request->ClientId)->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->first();
        if ($request->PaidWithAvoir == 'on') {
            if ($avoirclt != null) {
                $montantavoir = $avoirclt->Montant;
            }
        }

        $cmde = new Vente();
        //    $cmde->id = $request->id;
        $cmde->DateVente = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateVente)));
        $cmde->DateLivraison = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateLivraison)));
        $cmde->ClientId = $request->ClientId;
        $cmde->CompteId = $request->CompteId;
        $cmde->ModePaiementId = $request->ModePaiementId;
        $cmde->Reference = generateCmdeVente();
        $cmde->MontantPaye = 0;
        $cmde->MontantLivre = 0;
        // $cmde->MontantFacture = 0;
        $cmde->RemiseGlobale = $request->Remiseglobale;
        $cmde->EntrepriseId = Auth::user()->EntrepriseId;
        $cmde->Create_user = Auth::user()->id;
        $cmde->save();



        $livr = new LivraisonVente();
        $livr->DateLivraison = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateLivraison)));
        $livr->VenteId = $cmde->id;
        $livr->Reference = generateLivrVente();
        $livr->EntrepriseId = Auth::user()->EntrepriseId;
        $livr->Create_user = Auth::user()->id;
        $livr->MontantLivre = 0;
        $livr->MontantFacture = 0;
        $livr->save();

        $vfact = new FactureVente();
        $vfact->DateFacture = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateLivraison)));
        $vfact->DateEcheance = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateLivraison)));
        $vfact->LivraisonId = $livr->id;
        $vfact->Reference = generateFactVente();

        $vfact->MontantFacture = 0;
        $vfact->MontantPaye = 0;
        $vfact->EntrepriseId = Auth::user()->EntrepriseId;
        $vfact->Create_user = Auth::user()->id;
        $vfact->save();


        $paiement = new PaiementVente();
        $paiement->DatePaiement = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateLivraison)));
        $paiement->FactureId = $vfact->id;
        $paiement->Reference = generatePaiementVente();
        $paiement->ModePaiementId = $request->ModePaiementId;
        $paiement->CompteId = $request->CompteId;
        $paiement->Remise = $request->Remiseglobale;
        $paiement->MontantAvoir = $montantavoir;
        $paiement->Montant = 0;
        $paiement->PaidWithAvoir = $request->PaidWithAvoir == 'on' ? 1 : 0;
        $paiement->IsAvoir = $request->IsAvoir == 'on' ? 1 : 0;
        $paiement->EntrepriseId = Auth::user()->EntrepriseId;
        $paiement->Create_user = Auth::user()->id;
        $paiement->save();

        $unites = $request->input('Unite', []);
        $produits = $request->input('Produit', []);
        $qtelivres = $request->input('QteLivres', []);
        $qtes = $request->input('Qte', []);
        $remises = $request->input('Remise', []);
        $tvas = $request->input('TVA', []);
        $prixventes = $request->input('PrixVente', []);
        // dd( $qtelivres);


        for ($produit = 0; $produit < count($produits); $produit++) {
            if ($produits[$produit] != '' && $qtes[$produit] != '' && $qtes[$produit] != '0' && $prixventes[$produit] != '' && $prixventes[$produit] != '0') {
                $montantHt = $qtes[$produit] * $prixventes[$produit];
                $remise = round(($montantHt * $remises[$produit]) / 100);
                $tva = round(($montantHt * $tvas[$produit]) / 100);
                $montantttc = (int)($montantHt + $tva - $remise);

                $montantHtRecu = $qtelivres[$produit] * $prixventes[$produit];
                $remiseRecu  = round(($montantHtRecu * $remises[$produit]) / 100);
                $tvaRecu  = round(($montantHtRecu * $tvas[$produit]) / 100);
                $montantttcRecu  = (int)($montantHtRecu + $tvaRecu - $remiseRecu);

                $cmde->Qte = $cmde->Qte + $qtes[$produit];
                $cmde->MontantHT = $cmde->MontantHT + $montantHt;
                $cmde->MontantTTC = $cmde->MontantTTC  + $montantttc;
                $cmde->Remise =  $cmde->Remise + $remise;
                $cmde->Tva = $cmde->Tva +  $tva;
                $cmde->MontantLivre = $cmde->MontantLivre    + $montantttcRecu;

                $cmde->produits()->attach(
                    $produits[$produit],
                    [
                        'Qte' => $qtes[$produit],
                        'QteLivre' => $qtelivres[$produit],
                        'MontantLivre' => $montantttc,
                        'Remise' => $remises[$produit],
                        'Tva' => $tvas[$produit],
                        'UniteId' => $unites[$produit],
                        'Prix' => $prixventes[$produit],
                        'Montant' => $montantttcRecu
                    ]
                );


                if ($qtelivres[$produit] > 0) {
                    // if ($produit != '') {
                    $livr->produits()->attach(
                        $produits[$produit],
                        [
                            'Qte' => $qtes[$produit],
                            'UniteId' => $unites[$produit],
                            'QteFacture' => $qtelivres[$produit],
                        ]
                    );

                    DetailsVenteProduit($produits[$produit], $unites[$produit], $qtelivres[$produit]);

                    $vfact->produits()->attach(
                        $produits[$produit],
                        [
                            'Qte' => $qtes[$produit],
                            'UniteId' => $unites[$produit],
                        ]
                    );
                }
            }
        }

        if ($cmde->MontantTTC > 0) {
            $cmde->save();
            if ($cmde->MontantLivre > 0) {
                $livr->MontantLivre = $cmde->MontantLivre;
                $livr->MontantFacture = $cmde->MontantLivre;
                if ($livr->MontantLivre == $livr->MontantFacture) {
                    $livr->Status = 1;
                }
                $livr->save();


                $paiement->MontantRemis = $request->MontantRemis + $montantavoir + $request->Remiseglobale;
                $monnaie = ($paiement->MontantRemis - $cmde->MontantLivre) > 0 ? $paiement->MontantRemis - $cmde->MontantLivre : 0;
                if ($monnaie > 0) {
                    $vfact->MontantPaye = $cmde->MontantLivre;
                    $cmde->MontantPaye = $cmde->MontantLivre;
                } else {
                    $vfact->MontantPaye = $paiement->MontantRemis;
                    $cmde->MontantPaye = $paiement->MontantRemis;
                }

                // $vfact->MontantPaye = $cmde->MontantLivre;
                $vfact->MontantFacture = $cmde->MontantLivre;
                if ($vfact->MontantPaye == $vfact->MontantFacture) {
                    $vfact->Status = 1;
                }

                $vfact->save();

                if ($cmde->MontantTTC == $cmde->MontantPaye) {
                    $cmde->Status = 1;
                    $cmde->RefVente = generateRefVente($cmde->id);
                }
                $cmde->save();


                $paiement->Montant = $vfact->MontantPaye;
                $paiement->Monnaie = $monnaie;
                $paiement->save();


                $compte = Compte::find($request->CompteId);

                // dd($avoirclt);

                if ($avoirclt != null) {
                    $avoirclt = AvoirClt::find($avoirclt->id);
                    if ($montantavoir > 0) {
                        //retrait ancien montant avoir
                        $avoirclt->Montant = $avoirclt->Montant - $montantavoir;
                        $avoirclt->Edit_user = Auth::user()->id;
                        $avoirclt->Modif_util = Carbon::now();
                        $avoirclt->save();


                        $libelle = "Paiement en avoir facture " . $paiement->Reference;
                        AddDetailsAvoirClt($avoirclt->id, $libelle, -1, $montantavoir);

                        if ($compte != null) {
                            $compte->Solde = $compte->Solde - $montantavoir;
                            $compte->save();

                            AddDetailsCompte($compte->id, $libelle, $montantavoir, 0);
                        }
                    }

                    if ($request->IsAvoir == 'on') {
                        if ($monnaie > 0) {
                            //ajout nouveau montant avoir
                            $avoirclt->Montant = $avoirclt->Montant + $monnaie;
                            $avoirclt->Edit_user = Auth::user()->id;
                            $avoirclt->Modif_util = Carbon::now();
                            $avoirclt->save();

                            $libelle = "Avoir sur facture " . $paiement->Reference;
                            AddDetailsAvoirClt($avoirclt->id, $libelle, 1, $monnaie);

                            if ($compte != null) {
                                $compte->Solde = $compte->Solde + $monnaie;
                                $compte->save();

                                AddDetailsCompte($compte->id, $libelle, 0, $monnaie);
                            }
                        }
                    }
                }

                if ($compte != null) {

                    if ($montantavoir > 0) {
                        $compte->Solde = $compte->Solde + $montantavoir;
                        $compte->save();
                        $libelle = "Paiement en avoir facture " . $paiement->Reference;
                        AddDetailsCompte($compte->id, $libelle, 0, $montantavoir);
                    }

                    if ($paiement->Montant > $montantavoir) {
                        $compte->Solde = $compte->Solde + $paiement->Montant - $montantavoir;
                        $compte->save();
                        $modepaiement = ModePaiement::find($paiement->ModePaiementId);
                        if ($modepaiement != null) {
                            $libelle = "Paiement en " . $modepaiement->Nom . " facture " . $paiement->Reference;
                            AddDetailsCompte($compte->id, $libelle, 0, $paiement->Montant);
                        }
                    }
                }
            } else {

                $livr->delete();
                $vfact->delete();
                $paiement->delete();
            }





            return redirect()->route('vcmdes.index')
                ->with('success', 'Commande créée avec succès.');
        } else {
            $cmde->delete();
            $livr->delete();
            $vfact->delete();
            $paiement->delete();

            return redirect()->route('vcmdes.index')
                ->with('danger', 'Des données vides ont été saisies.');
        }
       
    }



    public function cloturer($id)
    {
        $cmde = Vente::find($id);
        if ($cmde == null) {
            return redirect()->route('vcmdes.index');
        }
        $cmde->Status = 1;
        $cmde->Edit_user = Auth::user()->id;
        $cmde->RefVente = generateRefVente($cmde->id);
        $cmde->save();

        return redirect()->route('vcmdes.index')
            ->with('success', 'Commande clôturée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vente  $vente
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (count(DB::table('livraisonventes')->where('VenteId', $id)->where('Supprimer', '0')->get()) > 0) {
            return redirect('/vente/vcmdes')->with('danger', "Cette commande ne peut être supprimée car elle a déjà subi des livraisons.");
        }

        $cmde = Vente::find($id);
        if ($cmde != null) {
            DeleteVente($cmde->id);
            return redirect()->route('vcmdes.index')
                ->with('success', 'Commande supprimée avec succès.');
        } else {
            return redirect()->route('vcmdes.index')
                ->with('danger', 'Commande n\'existe pas.');
        }
    }
}
