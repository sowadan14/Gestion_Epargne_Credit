<?php

namespace App\Http\Controllers;

use App\Helpers\helpers;

use App\Models\LivraisonVente;
use App\Models\PaiementVente;
use App\Models\FactureVente;
use App\Models\AvoirClt;
use Illuminate\Support\Carbon;
use App\Models\Vente;
use App\Models\Compte;
use App\Models\Fournisseur;
use App\Models\Unite;
use App\Models\Produit;
use App\Models\ModePaiement;
use Brick\Math\BigInteger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaiementVenteController extends Controller
{

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:listvpaiement|createvpaiement|editvpaiement|deletevpaiement', ['only' => ['index','show']]);
        $this->middleware('permission:createvpaiement', ['only' => ['create','store']]);
        $this->middleware('permission:editvpaiement', ['only' => ['edit','update']]);
        $this->middleware('permission:deletevpaiement', ['only' => ['destroy']]);
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

        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Recouvrement</a></li><li class=\"breadcrumb-item\"><a href=\"/recouv/vpaiements\">Paiement client </a></li>";
        return view('recouv.vpaiements.index', compact('data'))->with('Titre', 'Paiement client')->with('Breadcrumb', $Breadcrumb);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $Cmdes = Recouvrement::all()->where('Status', '1');
        $Livrs = LivraisonVente::all()->where('Status', '0');
        $livr = $Livrs->first();

        $frId = $livr->commande->ClientId;
        $montantavoir = 0;
        $avoirclt = AvoirClt::where('ClientId', $frId)->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->first();
        if ($avoirclt != null) {
            $montantavoir = $avoirclt->Montant;
        }
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Recouvrement</a></li><li class=\"breadcrumb-item\"><a href=\"/recouv/vpaiements\">Paiement client </a></li>";
        return view('recouv.vpaiements.create', compact('livr', 'Livrs','montantavoir'))->with('Titre', 'Paiement client')->with('Breadcrumb', $Breadcrumb);
    }

    public function addpaiement($id)
    {
        $vfact = FactureVente::find($id);
        if ($vfact == null) {
            return redirect()->route('vpaiements.index');
        }

        $modepaiements = ModePaiement::where('Supprimer', false)
            ->where('EntrepriseId', Auth::user()->EntrepriseId)
            ->get();

        $comptes = Compte::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();

        $frId = $vfact->livraison->commande->ClientId;
        $montantavoir = 0;
        $avoirclt = AvoirClt::where('ClientId', $frId)->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->first();
        if ($avoirclt != null) {
            $montantavoir = $avoirclt->Montant;
        }

        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Recouvrement</a></li><li class=\"breadcrumb-item\"><a href=\"/recouv/vpaiements\">Paiement client </a></li>";
        return view('recouv.vpaiements.create', compact('vfact', 'modepaiements', 'comptes','montantavoir'))->with('Titre', 'Paiement client')->with('Breadcrumb', $Breadcrumb);
    }

    public function details()
    {
        // $Cmdes = Recouvrement::all()->where('Status', '1');
        $Livrs = LivraisonVente::all()->where('Status', '0');
        $livr = $Livrs->first();
        // $cmde = Recouvrement::find($livr->VenteId);
        // dd($livr);
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Recouvrement</a></li><li class=\"breadcrumb-item\"><a href=\"/recouv/vpaiements\">Paiement client </a></li>";
        return view('recouv.vpaiements.create', compact('livr', 'Livrs'))->with('Titre', 'Paiement client')->with('Breadcrumb', $Breadcrumb);
    }


    public function facture($id)
    {
        $vfact = FactureVente::find($id);

        if ($vfact == null) {
            return redirect()->route('vpaiements.index');
        }

        $data = PaiementVente::where('FactureId', $id)
            ->where('Supprimer', false)
            ->where('EntrepriseId', Auth::user()->EntrepriseId)
            ->get();
       
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Recouvrement</a></li><li class=\"breadcrumb-item\"><a href=\"/recouv/vpaiements\">Paiement client </a></li>";
        return view('recouv.vpaiements.details', compact('vfact', 'data'))->with('Titre', 'Paiement client')->with('Breadcrumb', $Breadcrumb);
    }


    public function getDetailspaiement(Request $request)
    {

        if (!$request->id) {
            $htmlTable = '';
            $htmlDetailsCmde = '';
            $reference = '';
        } else {
            $htmlTable = '';
            $htmlDetailsCmde = '';
            $livr = LivraisonVente::find($request->id);
            $htmlTable = view('recouv.vpaiements.tableCmde', compact('livr'))->render();
            $htmlDetailsLivr = view('recouv.vpaiements.detailCmde', compact('livr'))->render();
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


        $this->validate(
            $request,
            [
                'DatePaiement' => 'required|date_format:"d/m/Y"',
                'ModePaiementId' => 'required',
                'CompteId' => 'required',
            ],
            [
                'DatePaiement.required' => 'Le champ Date paiement est obligatoire.',
                'DatePaiement.date_format' => 'Le format de Date paiement est incorrecte (dd/mm/yyyy).',
                'ModePaiementId.required' => 'Le choix de mode paiement est obligatoire.',
                'CompteId.required' => 'Le choix de compte est obligatoire.',
                // 'ModePaiementId.required' => 'Le choix du mode paiement est obligatoire.',
            ]
        );

        // dd($request);

        if ($request->input('MontantRemis') == null || $request->input('MontantRemis') == '') {
            return redirect()->route('vpaiements.index')
                ->with('danger', 'Saisie d\'un montant incorrect.');
        }
        // dd($request);

        $vfact =  FactureVente::find($request->FactureId);
        $livr = LivraisonVente::find($vfact->LivraisonId);
        $cmde = Vente::find($livr->VenteId);
        $compte = Compte::find($request->CompteId);

        if ($vfact != null && $livr != null && $cmde != null && $compte != null) {
            if ($request->input('MontantRemis') != 0) {
                $paiement = new PaiementVente();
                $paiement->DatePaiement = date('Y-m-d', strtotime(str_replace('/', '-', $request->DatePaiement)));
                $paiement->FactureId = $request->FactureId;
                $paiement->Reference = $request->Reference;
                $paiement->CompteId = $request->CompteId;
                $paiement->ModePaiementId = $request->ModePaiementId;
                $paiement->Remise = $request->RemiseGlobale;
                $paiement->PaidWithAvoir = $request->PaidWithAvoir == 'on' ? 1 : 0;
                $paiement->IsAvoir = $request->IsAvoir == 'on' ? 1 : 0;
                $paiement->EntrepriseId = Auth::user()->EntrepriseId;
                $paiement->Create_user = Auth::user()->id;
                $paiement->save();

                $montantavoir = 0;
                $avoirclt = AvoirClt::where('ClientId', $cmde->ClientId)->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->first();
                if ($request->PaidWithAvoir == 'on') {
                    if ($avoirclt != null) {
                        $montantavoir = $avoirclt->Montant;
                    }
                }

                $resteapayer= $vfact->MontantFacture- $vfact->MontantPaye;
                $montantpayer= $request->MontantRemis + $montantavoir + $request->RemiseGlobale;
                
                if($montantpayer>=$resteapayer)
                {
                    $paiement->Montant=$resteapayer;
                }
                else{
                    $paiement->Montant=$montantpayer;
                }
                $monnaie = ($paiement->Montant - ($vfact->MontantFacture- $vfact->MontantPaye)) > 0 ? $paiement->Montant - ($vfact->MontantFacture- $vfact->MontantPaye) : 0;
                $paiement->Monnaie = $monnaie;
                $paiement->save();

                // $compte->Solde = $compte->Solde + $paiement->Montant;
                // $compte->save();

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
                            AddDetailsCompte($compte->id, $libelle, 0, $paiement->Montant-$montantavoir);
                        }
                    }
                   
                }

              
                $vfact->MontantPaye = $vfact->MontantPaye + $paiement->Montant;

                if ($vfact->MontantPaye == $vfact->MontantFacture) {
                    $vfact->Status = 1;
                } else {
                    $vfact->Status = 0;
                }
                $vfact->save();


                $cmde->MontantPaye = $cmde->MontantPaye + $paiement->Montant;
                // if ($cmde->MontantTTC == $cmde->MontantPaye + $cmde->RemiseGlobale) {
                //     $cmde->Status = 1;
                // }
                $cmde->save();
            }
        }
        return redirect()->route('vpaiements.facture', ['id' => $request->FactureId])
            ->with('success', 'Paiement créé avec succès.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vente  $recouv
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $paiement = PaiementVente::find($id);

        if ($paiement == null) {
            return redirect()->route('vpaiements.index');
        }

        $vfact = FactureVente::find($paiement->FactureId);
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Recouvrement</a></li><li class=\"breadcrumb-item\"><a href=\"/recouv/vpaiements\">Paiement client </a></li>";
        return view('recouv.vpaiements.show', compact('vfact', 'paiement'))->with('Titre', 'Paiement client')->with('Breadcrumb', $Breadcrumb);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vente  $recouv
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $paiement = PaiementVente::find($id);
        if ($paiement != null) {
            $vfact = FactureVente::find($paiement->FactureId);
            $modepaiements = ModePaiement::where('Supprimer', false)
                ->where('EntrepriseId', Auth::user()->EntrepriseId)
                ->get();

            $comptes = Compte::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
            $frId = $vfact->livraison->commande->ClientId;
            $montantavoir = 0;
            $avoirclt = AvoirClt::where('ClientId', $frId)->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->first();
            if ($avoirclt != null) {
                $montantavoir = $avoirclt->Montant;
            }

            $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Recouvrement</a></li><li class=\"breadcrumb-item\"><a href=\"/recouv/vpaiements\">Paiement client </a></li>";
            return view('recouv.vpaiements.edit', compact('vfact', 'paiement', 'modepaiements', 'comptes','montantavoir'))->with('Titre', 'Paiement client')->with('Breadcrumb', $Breadcrumb);
        } else {
            return redirect()->route('vpaiements.index')
                ->with('danger', 'Ce Paiement n\'existe pas.');
        }
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
                'DatePaiement' => 'required|date_format:"d/m/Y"',
                'ModePaiementId' => 'required',
                'CompteId' => 'required',
            ],
            [
                'DatePaiement.required' => 'Le champ Date paiement est obligatoire.',
                'DatePaiement.date_format' => 'Le format de Date paiement est incorrecte (dd/mm/yyyy).',
                'ModePaiementId.required' => 'Le choix de mode paiement est obligatoire.',
                'CompteId.required' => 'Le choix de compte est obligatoire.',
                // 'ModePaiementId.required' => 'Le choix du mode paiement est obligatoire.',
            ]
        );



        if ($request->input('MontantRemis') == null || $request->input('MontantRemis') == '') {
            return redirect()->route('vpaiements.facture', ['id' => $request->FactureId])
                ->with('danger', 'Saisie d\'un montant incorrect.');
        }

        $paiement =  PaiementVente::find($request->id);
        if ($paiement != null) {
            $vfact =  FactureVente::find($request->FactureId);
            $livr = LivraisonVente::find($vfact->LivraisonId);
            $cmde = Vente::find($livr->VenteId);
            $compte = Compte::find($paiement->CompteId);
            if ($vfact != null && $livr != null && $cmde != null && $compte != null) {
                if ($request->input('MontantRemis') != 0) {


                    removePaiement($request->id);
                  
                    $cmde->MontantPaye = $cmde->MontantPaye - $paiement->Montant;
                    $cmde->save();

                    $vfact->MontantPaye = $vfact->MontantPaye - $paiement->Montant;
                    $vfact->save();

                    $paiement->DatePaiement = date('Y-m-d', strtotime(str_replace('/', '-', $request->DatePaiement)));
                    $paiement->ModePaiementId = $request->ModePaiementId;
                    $paiement->CompteId = $request->CompteId;
                    $paiement->Montant = $request->Montant;
                    $paiement->Edit_user = Auth::user()->id;
                    $paiement->save();

                  
                    $montantavoir = 0;
                    $avoirclt = AvoirClt::where('ClientId', $cmde->ClientId)->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->first();
                    if ($request->PaidWithAvoir == 'on') {
                        if ($avoirclt != null) {
                            $montantavoir = $avoirclt->Montant;
                        }
                    }
    
                    $resteapayer= $vfact->MontantFacture- $vfact->MontantPaye;
                    $montantpayer= $request->MontantRemis + $montantavoir + $request->RemiseGlobale;
                    if($montantpayer>=$resteapayer)
                    {
                        $paiement->Montant=$resteapayer;
                    }
                    else{
                        $paiement->Montant=$montantpayer;
                    }
                    $monnaie = ($paiement->Montant - ($vfact->MontantFacture- $vfact->MontantPaye)) > 0 ? $paiement->Montant - ($vfact->MontantFacture- $vfact->MontantPaye) : 0;
                    $paiement->Monnaie = $monnaie;
                    $paiement->save();
    
                    // $compte->Solde = $compte->Solde + $paiement->Montant;
                    // $compte->save();
    
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
                                AddDetailsCompte($compte->id, $libelle, 0, $paiement->Montant-$montantavoir);
                            }
                        }
                       
                    }
    
                  
                    $vfact->MontantPaye = $vfact->MontantPaye + $paiement->Montant;
    
                    if ($vfact->MontantPaye == $vfact->MontantFacture) {
                        $vfact->Status = 1;
                    } else {
                        $vfact->Status = 0;
                    }
                    $vfact->save();
    
    
                    $cmde->MontantPaye = $cmde->MontantPaye + $paiement->Montant;
                    // if ($cmde->MontantTTC == $cmde->MontantPaye + $cmde->RemiseGlobale) {
                    //     $cmde->Status = 1;
                    // }
                    $cmde->save();


                    
                }
            }

            return redirect()->route('vpaiements.facture', ['id' => $request->FactureId])
                ->with('success', 'Paiement modifié avec succès.');
        } else {
            return redirect()->route('vpaiements.facture', ['id' => $request->FactureId])
                ->with('danger', 'Ce Paiement n\'existe pas.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vente  $recouv
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $paiement =  PaiementVente::find($id);

        if ($paiement != null) {
            $vfact =  FactureVente::find($paiement->FactureId);
            $livr = LivraisonVente::find($vfact->LivraisonId);
            $cmde = Vente::find($livr->VenteId);
            $compte = Compte::find($paiement->CompteId);
            if ($vfact != null && $livr != null && $cmde != null && $compte != null) {

                removePaiement($id);

                $vfact->MontantPaye = $vfact->MontantPaye - $paiement->Montant;

                if ($vfact->MontantPaye == $vfact->MontantFacture) {
                    $vfact->Status = 1;
                } else {
                    $vfact->Status = 0;
                }
                $vfact->save();


                $cmde->MontantPaye = $cmde->MontantPaye - $paiement->Montant;
                // if ($cmde->MontantTTC == $cmde->MontantPaye + $cmde->RemiseGlobale) {
                //     $cmde->Status = 1;
                // }
                $cmde->save();

                $paiement->Supprimer = true;
                $paiement->Delete_user = Auth::user()->id;
                $paiement->save();
            }
            return redirect()->route('vpaiements.facture', ['id' => $paiement->FactureId])
                ->with('success', 'Paiement supprimé avec succès.');
        } else {
            return redirect()->route('vpaiements.index')
                ->with('danger', 'Ce paiement n\'existe pas.');
        }
    }
}
