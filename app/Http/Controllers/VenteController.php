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
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VenteController extends Controller
{


  /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:listvlist|createvlist|editvlist|deletevlist', ['only' => ['index','show']]);
        $this->middleware('permission:createvlist', ['only' => ['create','store']]);
        $this->middleware('permission:editvlist', ['only' => ['edit','update']]);
        $this->middleware('permission:deletevlist', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Vente::orderBy('id', 'DESC')->where('Supprimer', false)->where('Status', '1')->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Vente</a></li><li class=\"breadcrumb-item\"><a href=\"/vente/vlists\">Listes</a></li>";

        return view('vente.vlists.index', compact('data'))->with('Titre', 'Ventes')->with('Breadcrumb', $Breadcrumb);
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
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Vente</a></li><li class=\"breadcrumb-item\"><a href=\"/vente/vlists\">Ventes </a></li>";
        return view('vente.vlists.create', compact('clients', 'modepaiements', 'comptes', 'unites', 'produits'))->with('Titre', 'Ventes')->with('Breadcrumb', $Breadcrumb);
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
                'ClientId.required' => 'Le choix du fournisseur est obligatoire.',
                'CompteId.required' => 'Le choix du compte est obligatoire.',
                'ModePaiementId.required' => 'Le choix du mode paiement est obligatoire.',
                // 'ModePaiementId.required' => 'Le choix du mode paiement est obligatoire.',
            ]
        );

        if (
            in_array(null, $request->input('Qte', [])) || in_array('', array_map('trim', $request->input('Qte', [])))  || empty($request->input('Qte', []))
            || in_array(null, $request->input('Remise', [])) || in_array('', array_map('trim', $request->input('Remise', [])))  || empty($request->input('Remise', []))
            || in_array(null, $request->input('TVA', [])) || in_array('', array_map('trim', $request->input('TVA', [])))  || empty($request->input('TVA', []))
            || in_array(null, $request->input('PrixVente', [])) || in_array('', array_map('trim', $request->input('PrixVente', [])))  || empty($request->input('PrixVente', []))

        ) {
            return redirect()->route('vlists.index')
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
        // $cmde->MontantPaye = $request->MontantPaye;
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
        // $livr->MontantLivre = $request->MontantPaye;
        // $livr->MontantFacture = $request->MontantPaye;
        $livr->save();

        $vfact = new FactureVente();
        $vfact->DateFacture = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateLivraison)));
        $vfact->DateEcheance = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateLivraison)));
        $vfact->LivraisonId = $livr->id;
        $vfact->Reference = generateFactVente();

        // $vfact->MontantFacture = $request->MontantPaye;
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
        $prixventes = $request->input('PrixVente', []);

        $iscmde = true;

        // dd($unites,$qtes ,$prixventes,$prixventes);
        for ($produit = 0; $produit < count($produits); $produit++) {
            if ($produits[$produit] != '' && $qtes[$produit] != '' && $qtes[$produit] != '0' && $prixventes[$produit] != '' && $prixventes[$produit] != '0') {
                $montantHt = $qtes[$produit] * $prixventes[$produit];
                $remise = round(($montantHt * $remises[$produit]) / 100);
                $tva = round(($montantHt * $tvas[$produit]) / 100);
                $montantttc = (int)($montantHt + $tva - $remise);

                $cmde->Qte = $cmde->Qte + $qtes[$produit];
                $cmde->MontantHT = $cmde->MontantHT + $montantHt;
                $cmde->MontantTTC = $cmde->MontantTTC  + $montantttc;
                $cmde->Remise =  $cmde->Remise + $remise;
                $cmde->Tva = $cmde->Tva +  $tva;
                $cmde->MontantLivre = $cmde->MontantLivre  + $montantttc;

                $cmde->produits()->attach(
                    $produits[$produit],
                    [
                        'Qte' => $qtes[$produit],
                        'QteLivre' => $qtes[$produit],
                        'MontantLivre' => $montantttc,
                        'Remise' => $remises[$produit],
                        'Tva' => $tvas[$produit],
                        'UniteId' => $unites[$produit],
                        'Prix' => $prixventes[$produit],
                        'Montant' => $montantttc
                    ]
                );




                // if ($produit != '') {
                $livr->produits()->attach(
                    $produits[$produit],
                    [
                        'Qte' => $qtes[$produit],
                        'UniteId' => $unites[$produit],
                        'QteFacture' => $qtes[$produit],
                    ]
                );


                DetailsVenteProduit($produits[$produit], $unites[$produit], $qtes[$produit]);

                $vfact->produits()->attach(
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

      if($cmde->MontantTTC>0)
      {

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
            $cmde->MontantPaye=$cmde->MontantLivre;
        } else {
            $vfact->MontantPaye = $paiement->MontantRemis;
            $cmde->MontantPaye=$paiement->MontantRemis;
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

            if($request->IsAvoir == 'on')
            {
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
            
            if($montantavoir>0)
            {
                $compte->Solde = $compte->Solde + $montantavoir;
                $compte->save();
                $libelle = "Paiement en avoir facture " . $paiement->Reference;
                AddDetailsCompte($compte->id, $libelle, 0, $montantavoir);
            }

            if($paiement->Montant>$montantavoir)
            {
                $compte->Solde = $compte->Solde + $paiement->Montant-$montantavoir;
                $compte->save();
                $modepaiement = ModePaiement::find($paiement->ModePaiementId);
                if ($modepaiement != null) {
                    $libelle = "Paiement en " . $modepaiement->Nom . " facture " . $paiement->Reference;
                    AddDetailsCompte($compte->id, $libelle, 0, $paiement->Montant);
                }
            }
           
        }

        return redirect()->route('vcmdes.index')
            ->with('success', 'Vente créée avec succès.');
      }
      else{
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
            return redirect()->route('vlists.index');
        }
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Vente</a></li><li class=\"breadcrumb-item\"><a href=\"/vente/cmdes\">Ventes </a></li>";
        return view('vente.vlists.show', compact('cmde'))->with('Titre', 'Ventes')->with('Breadcrumb', $Breadcrumb);
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
                if($unite->pivot->Qte>0)
                {
                    if ($unite->id == $produit->UniteId) {
                        $html .= $unite->id . '/' . $unite->pivot->PrixVente .'/'.$unite->pivot->Qte.'~' . $unite->Nom . '~selected|';
                    } else {
                        $html .= $unite->id . '/' . $unite->pivot->PrixVente  .'/'.$unite->pivot->Qte.'~' . $unite->Nom . '~~'.$unite->pivot->Qte.'|';
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
            return redirect()->route('vlists.index');
        }
        $unites = Unite::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $produits = Produit::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();

        $clients = Client::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $modepaiements = ModePaiement::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $comptes = Compte::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Vente</a></li><li class=\"breadcrumb-item\"><a href=\"/vente/vlists\">Ventes </a></li>";
        return view('vente.vlists.edit', compact('clients', 'modepaiements', 'comptes', 'unites', 'produits', 'cmde'))->with('Titre', 'Ventes')->with('Breadcrumb', $Breadcrumb);
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
                'ClientId.required' => 'Le choix du fournisseur est obligatoire.',
                'CompteId.required' => 'Le choix du compte est obligatoire.',
                'ModePaiementId.required' => 'Le choix du mode paiement est obligatoire.',
                // 'ModePaiementId.required' => 'Le choix du mode paiement est obligatoire.',
            ]
        );

        // dd($request->input('Unite', []),$request->input('Produit', []),$request->input('TVA', []),$request->input('PrixVente', []),$request->input('QteRecus', []));

        if (
            in_array(null, $request->input('Qte', [])) || in_array('', array_map('trim', $request->input('Qte', [])))  || empty($request->input('Qte', []))
            || in_array(null, $request->input('Remise', [])) || in_array('', array_map('trim', $request->input('Remise', [])))  || empty($request->input('Remise', []))
            || in_array(null, $request->input('TVA', [])) || in_array('', array_map('trim', $request->input('TVA', [])))  || empty($request->input('TVA', []))
            || in_array(null, $request->input('PrixVente', [])) || in_array('', array_map('trim', $request->input('PrixVente', [])))  || empty($request->input('PrixVente', []))

        ) {
            return redirect()->route('vlists.index')
                ->with('danger', 'Des données vides ont été saisies.');
        }

        DeleteVente($request->id);
        //    $cmde->id = $request->id;
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
        // $cmde->MontantPaye = $request->MontantPaye;
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
        // $livr->MontantLivre = $request->MontantPaye;
        // $livr->MontantFacture = $request->MontantPaye;
        $livr->save();

        $vfact = new FactureVente();
        $vfact->DateFacture = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateLivraison)));
        $vfact->DateEcheance = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateLivraison)));
        $vfact->LivraisonId = $livr->id;
        $vfact->Reference = generateFactVente();

        // $vfact->MontantFacture = $request->MontantPaye;
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
        $prixventes = $request->input('PrixVente', []);

        $iscmde = true;

        // dd($unites,$qtes ,$prixventes,$prixventes);
        for ($produit = 0; $produit < count($produits); $produit++) {
            if ($produits[$produit] != '' && $qtes[$produit] != '' && $qtes[$produit] != '0' && $prixventes[$produit] != '' && $prixventes[$produit] != '0') {
                $montantHt = $qtes[$produit] * $prixventes[$produit];
                $remise = round(($montantHt * $remises[$produit]) / 100);
                $tva = round(($montantHt * $tvas[$produit]) / 100);
                $montantttc = (int)($montantHt + $tva - $remise);

                $cmde->Qte = $cmde->Qte + $qtes[$produit];
                $cmde->MontantHT = $cmde->MontantHT + $montantHt;
                $cmde->MontantTTC = $cmde->MontantTTC  + $montantttc;
                $cmde->Remise =  $cmde->Remise + $remise;
                $cmde->Tva = $cmde->Tva +  $tva;
                $cmde->MontantLivre = $cmde->MontantLivre  + $montantttc;

                $cmde->produits()->attach(
                    $produits[$produit],
                    [
                        'Qte' => $qtes[$produit],
                        'QteLivre' => $qtes[$produit],
                        'MontantLivre' => $montantttc,
                        'Remise' => $remises[$produit],
                        'Tva' => $tvas[$produit],
                        'UniteId' => $unites[$produit],
                        'Prix' => $prixventes[$produit],
                        'Montant' => $montantttc
                    ]
                );




                // if ($produit != '') {
                $livr->produits()->attach(
                    $produits[$produit],
                    [
                        'Qte' => $qtes[$produit],
                        'UniteId' => $unites[$produit],
                        'QteFacture' => $qtes[$produit],
                    ]
                );


                DetailsVenteProduit($produits[$produit], $unites[$produit], $qtes[$produit]);

                $vfact->produits()->attach(
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

      if($cmde->MontantTTC>0)
      {

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
            $cmde->MontantPaye=$cmde->MontantLivre;
        } else {
            $vfact->MontantPaye = $paiement->MontantRemis;
            $cmde->MontantPaye=$paiement->MontantRemis;
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

            if($request->IsAvoir == 'on')
            {
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
            
            if($montantavoir>0)
            {
                $compte->Solde = $compte->Solde + $montantavoir;
                $compte->save();
                $libelle = "Paiement en avoir facture " . $paiement->Reference;
                AddDetailsCompte($compte->id, $libelle, 0, $montantavoir);
            }

            if($paiement->Montant>$montantavoir)
            {
                $compte->Solde = $compte->Solde + $paiement->Montant-$montantavoir;
                $compte->save();
                $modepaiement = ModePaiement::find($paiement->ModePaiementId);
                if ($modepaiement != null) {
                    $libelle = "Paiement en " . $modepaiement->Nom . " facture " . $paiement->Reference;
                    AddDetailsCompte($compte->id, $libelle, 0, $paiement->Montant);
                }
            }
           
        }

        return redirect()->route('vcmdes.index')
            ->with('success', 'Vente créée avec succès.');
      }
      else{
        $cmde->delete();
        $livr->delete();
        $vfact->delete();
        $paiement->delete();

        return redirect()->route('vcmdes.index')
            ->with('danger', 'Des données vides ont été saisies.');
      }
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
            return redirect('/vente/vlists')->with('danger', "Cette vente ne peut être supprimée car elle a déjà subi des livraisons.");
        }

        $cmde = Vente::find($id);
        if ($cmde != null) {
            DeleteVente($cmde->id);
            return redirect()->route('vlists.index')
                ->with('success', 'Vente supprimée avec succès.');
        } else {
            return redirect()->route('vlists.index')
                ->with('danger', 'Cette vente n\'existe pas.');
        }
    }
}
