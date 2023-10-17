<?php

namespace App\Http\Controllers;

use App\Helpers\helpers;

use App\Models\ReceptionAchat;
use App\Models\PaiementAchat;
use App\Models\FactureAchat;
use App\Models\AvoirFr;
use Illuminate\Support\Carbon;
use App\Models\Achat;
use App\Models\Compte;
use App\Models\Fournisseur;
use App\Models\Unite;
use App\Models\Produit;
use App\Models\ModePaiement;
use Brick\Math\BigInteger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaiementAchatController extends Controller
{

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:listapaiement|createapaiement|editapaiement|deleteapaiement', ['only' => ['index','show']]);
        $this->middleware('permission:createapaiement', ['only' => ['create','store']]);
        $this->middleware('permission:editapaiement', ['only' => ['edit','update']]);
        $this->middleware('permission:deleteapaiement', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data = FactureAchat::orderBy('id', 'DESC')->where('Supprimer', false)
            ->where('EntrepriseId', Auth::user()->EntrepriseId)
            ->where('MontantFacture', '>', 0)
            ->get();

        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Recouvrement</a></li><li class=\"breadcrumb-item\"><a href=\"/recouv/apaiements\">Paiement fournisseur </a></li>";
        return view('recouv.apaiements.index', compact('data'))->with('Titre', 'Paiement fournisseur')->with('Breadcrumb', $Breadcrumb);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $Cmdes = Recouvrement::all()->where('Status', '1');
        $Receps = ReceptionAchat::all()->where('Status', '0');
        $recep = $Receps->first();

        $frId = $recep->commande->FournisseurId;
        $montantavoir = 0;
        $avoirfr = AvoirFr::where('FournisseurId', $frId)->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->first();
        if ($avoirfr != null) {
            $montantavoir = $avoirfr->Montant;
        }
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Recouvrement</a></li><li class=\"breadcrumb-item\"><a href=\"/recouv/apaiements\">Paiement fournisseur </a></li>";
        return view('recouv.apaiements.create', compact('recep', 'Receps','montantavoir'))->with('Titre', 'Paiement fournisseur')->with('Breadcrumb', $Breadcrumb);
    }

    public function addpaiement($id)
    {
        $afact = FactureAchat::find($id);
        if ($afact == null) {
            return redirect()->route('apaiements.index');
        }

        $modepaiements = ModePaiement::where('Supprimer', false)
            ->where('EntrepriseId', Auth::user()->EntrepriseId)
            ->get();

        $comptes = Compte::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();

        $frId = $afact->reception->commande->FournisseurId;
        $montantavoir = 0;
        $avoirfr = AvoirFr::where('FournisseurId', $frId)->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->first();
        if ($avoirfr != null) {
            $montantavoir = $avoirfr->Montant;
        }

        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Recouvrement</a></li><li class=\"breadcrumb-item\"><a href=\"/recouv/apaiements\">Paiement fournisseur </a></li>";
        return view('recouv.apaiements.create', compact('afact', 'modepaiements', 'comptes','montantavoir'))->with('Titre', 'Paiement fournisseur')->with('Breadcrumb', $Breadcrumb);
    }

    public function details()
    {
        // $Cmdes = Recouvrement::all()->where('Status', '1');
        $Receps = ReceptionAchat::all()->where('Status', '0');
        $recep = $Receps->first();
        // $cmde = Recouvrement::find($recep->AchatId);
        // dd($recep);
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Recouvrement</a></li><li class=\"breadcrumb-item\"><a href=\"/recouv/apaiements\">Paiement fournisseur </a></li>";
        return view('recouv.apaiements.create', compact('recep', 'Receps'))->with('Titre', 'Paiement fournisseur')->with('Breadcrumb', $Breadcrumb);
    }


    public function facture($id)
    {
        $afact = FactureAchat::find($id);

        if ($afact == null) {
            return redirect()->route('apaiements.index');
        }

        $data = PaiementAchat::where('FactureId', $id)
            ->where('Supprimer', false)
            ->where('EntrepriseId', Auth::user()->EntrepriseId)
            ->get();
       
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Recouvrement</a></li><li class=\"breadcrumb-item\"><a href=\"/recouv/apaiements\">Paiement fournisseur </a></li>";
        return view('recouv.apaiements.details', compact('afact', 'data'))->with('Titre', 'Paiement fournisseur')->with('Breadcrumb', $Breadcrumb);
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
            $recep = ReceptionAchat::find($request->id);
            $htmlTable = view('recouv.apaiements.tableCmde', compact('recep'))->render();
            $htmlDetailsRecep = view('recouv.apaiements.detailCmde', compact('recep'))->render();
            $reference = generateFactAchat();
        }
        // dd($html);

        return response()->json(array('success' => true, 'htmlTable' => $htmlTable, 'htmlDetailsRecep' => $htmlDetailsRecep, 'reference' => $reference));
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
                'DatePaiement.required' => 'Le champ Date réception est obligatoire.',
                'DatePaiement.date_format' => 'Le format de Date réception est incorrecte (dd/mm/yyyy).',
                'ModePaiementId.required' => 'Le choix de mode paiement est obligatoire.',
                'CompteId.required' => 'Le choix de compte est obligatoire.',
                // 'ModePaiementId.required' => 'Le choix du mode paiement est obligatoire.',
            ]
        );

        // dd($request);

        if ($request->input('MontantRemis') == null || $request->input('MontantRemis') == '') {
            return redirect()->route('apaiements.index')
                ->with('danger', 'Saisie d\'un montant incorrect.');
        }
        // dd($request);

        $afact =  FactureAchat::find($request->FactureId);
        $recep = ReceptionAchat::find($afact->ReceptionId);
        $cmde = Achat::find($recep->AchatId);
        $compte = Compte::find($request->CompteId);

        if ($afact != null && $recep != null && $cmde != null && $compte != null) {
            if ($request->input('MontantRemis') != 0) {
                $paiement = new PaiementAchat();
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
                $avoirfr = AvoirFr::where('FournisseurId', $cmde->FournisseurId)->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->first();
                if ($request->PaidWithAvoir == 'on') {
                    if ($avoirfr != null) {
                        $montantavoir = $avoirfr->Montant;
                    }
                }

                $resteapayer= $afact->MontantFacture- $afact->MontantPaye;
                $montantpayer= $request->MontantRemis + $montantavoir + $request->RemiseGlobale;
                
                if($montantpayer>=$resteapayer)
                {
                    $paiement->Montant=$resteapayer;
                }
                else{
                    $paiement->Montant=$montantpayer;
                }
                $monnaie = ($paiement->Montant - ($afact->MontantFacture- $afact->MontantPaye)) > 0 ? $paiement->Montant - ($afact->MontantFacture- $afact->MontantPaye) : 0;
                $paiement->Monnaie = $monnaie;
                $paiement->save();

                // $compte->Solde = $compte->Solde + $paiement->Montant;
                // $compte->save();

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
                            $compte->Solde = $compte->Solde - $montantavoir;
                            $compte->save();
        
                            AddDetailsCompte($compte->id, $libelle, $montantavoir, 0);
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

              
                $afact->MontantPaye = $afact->MontantPaye + $paiement->Montant;

                if ($afact->MontantPaye == $afact->MontantFacture) {
                    $afact->Status = 1;
                } else {
                    $afact->Status = 0;
                }
                $afact->save();


                $cmde->MontantPaye = $cmde->MontantPaye + $paiement->Montant;
                // if ($cmde->MontantTTC == $cmde->MontantPaye + $cmde->RemiseGlobale) {
                //     $cmde->Status = 1;
                // }
                $cmde->save();
            }
        }
        return redirect()->route('apaiements.facture', ['id' => $request->FactureId])
            ->with('success', 'Paiement créé avec succès.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Achat  $recouv
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $paiement = PaiementAchat::find($id);

        if ($paiement == null) {
            return redirect()->route('apaiements.index');
        }

        $afact = FactureAchat::find($paiement->FactureId);
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Recouvrement</a></li><li class=\"breadcrumb-item\"><a href=\"/recouv/apaiements\">Paiement fournisseur </a></li>";
        return view('recouv.apaiements.show', compact('afact', 'paiement'))->with('Titre', 'Paiement fournisseur')->with('Breadcrumb', $Breadcrumb);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Achat  $recouv
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $paiement = PaiementAchat::find($id);
        if ($paiement != null) {
            $afact = FactureAchat::find($paiement->FactureId);
            $modepaiements = ModePaiement::where('Supprimer', false)
                ->where('EntrepriseId', Auth::user()->EntrepriseId)
                ->get();

            $comptes = Compte::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
            $frId = $afact->reception->commande->FournisseurId;
            $montantavoir = 0;
            $avoirfr = AvoirFr::where('FournisseurId', $frId)->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->first();
            if ($avoirfr != null) {
                $montantavoir = $avoirfr->Montant;
            }

            $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Recouvrement</a></li><li class=\"breadcrumb-item\"><a href=\"/recouv/apaiements\">Paiement fournisseur </a></li>";
            return view('recouv.apaiements.edit', compact('afact', 'paiement', 'modepaiements', 'comptes','montantavoir'))->with('Titre', 'Paiement fournisseur')->with('Breadcrumb', $Breadcrumb);
        } else {
            return redirect()->route('apaiements.index')
                ->with('danger', 'Ce Paiement n\'existe pas.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Achat  $recouv
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
                'DatePaiement.required' => 'Le champ Date réception est obligatoire.',
                'DatePaiement.date_format' => 'Le format de Date réception est incorrecte (dd/mm/yyyy).',
                'ModePaiementId.required' => 'Le choix de mode paiement est obligatoire.',
                'CompteId.required' => 'Le choix de compte est obligatoire.',
                // 'ModePaiementId.required' => 'Le choix du mode paiement est obligatoire.',
            ]
        );



        if ($request->input('MontantRemis') == null || $request->input('MontantRemis') == '') {
            return redirect()->route('apaiements.facture', ['id' => $request->FactureId])
                ->with('danger', 'Saisie d\'un montant incorrect.');
        }

        $paiement =  PaiementAchat::find($request->id);
        if ($paiement != null) {
            $afact =  FactureAchat::find($request->FactureId);
            $recep = ReceptionAchat::find($afact->ReceptionId);
            $cmde = Achat::find($recep->AchatId);
            $compte = Compte::find($paiement->CompteId);
            if ($afact != null && $recep != null && $cmde != null && $compte != null) {
                if ($request->input('MontantRemis') != 0) {


                    removePaiement($request->id);
                  
                    $cmde->MontantPaye = $cmde->MontantPaye - $paiement->Montant;
                    $cmde->save();

                    $afact->MontantPaye = $afact->MontantPaye - $paiement->Montant;
                    $afact->save();

                    $paiement->DatePaiement = date('Y-m-d', strtotime(str_replace('/', '-', $request->DatePaiement)));
                    $paiement->ModePaiementId = $request->ModePaiementId;
                    $paiement->CompteId = $request->CompteId;
                    $paiement->Montant = $request->Montant;
                    $paiement->Edit_user = Auth::user()->id;
                    $paiement->save();

                  
                    $montantavoir = 0;
                    $avoirfr = AvoirFr::where('FournisseurId', $cmde->FournisseurId)->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->first();
                    if ($request->PaidWithAvoir == 'on') {
                        if ($avoirfr != null) {
                            $montantavoir = $avoirfr->Montant;
                        }
                    }
    
                    $resteapayer= $afact->MontantFacture- $afact->MontantPaye;
                    $montantpayer= $request->MontantRemis + $montantavoir + $request->RemiseGlobale;
                    if($montantpayer>=$resteapayer)
                    {
                        $paiement->Montant=$resteapayer;
                    }
                    else{
                        $paiement->Montant=$montantpayer;
                    }
                    $monnaie = ($paiement->Montant - ($afact->MontantFacture- $afact->MontantPaye)) > 0 ? $paiement->Montant - ($afact->MontantFacture- $afact->MontantPaye) : 0;
                    $paiement->Monnaie = $monnaie;
                    $paiement->save();
    
                    // $compte->Solde = $compte->Solde + $paiement->Montant;
                    // $compte->save();
    
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
                                $compte->Solde = $compte->Solde - $montantavoir;
                                $compte->save();
            
                                AddDetailsCompte($compte->id, $libelle, $montantavoir, 0);
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
    
                  
                    $afact->MontantPaye = $afact->MontantPaye + $paiement->Montant;
    
                    if ($afact->MontantPaye == $afact->MontantFacture) {
                        $afact->Status = 1;
                    } else {
                        $afact->Status = 0;
                    }
                    $afact->save();
    
    
                    $cmde->MontantPaye = $cmde->MontantPaye + $paiement->Montant;
                    // if ($cmde->MontantTTC == $cmde->MontantPaye + $cmde->RemiseGlobale) {
                    //     $cmde->Status = 1;
                    // }
                    $cmde->save();


                    
                }
            }

            return redirect()->route('apaiements.facture', ['id' => $request->FactureId])
                ->with('success', 'Paiement modifié avec succès.');
        } else {
            return redirect()->route('apaiements.facture', ['id' => $request->FactureId])
                ->with('danger', 'Ce Paiement n\'existe pas.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Achat  $recouv
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $paiement =  PaiementAchat::find($id);

        if ($paiement != null) {
            $afact =  FactureAchat::find($paiement->FactureId);
            $recep = ReceptionAchat::find($afact->ReceptionId);
            $cmde = Achat::find($recep->AchatId);
            $compte = Compte::find($paiement->CompteId);
            if ($afact != null && $recep != null && $cmde != null && $compte != null) {

                removePaiement($id);

                $afact->MontantPaye = $afact->MontantPaye - $paiement->Montant;

                if ($afact->MontantPaye == $afact->MontantFacture) {
                    $afact->Status = 1;
                } else {
                    $afact->Status = 0;
                }
                $afact->save();


                $cmde->MontantPaye = $cmde->MontantPaye - $paiement->Montant;
                // if ($cmde->MontantTTC == $cmde->MontantPaye + $cmde->RemiseGlobale) {
                //     $cmde->Status = 1;
                // }
                $cmde->save();

                $paiement->Supprimer = true;
                $paiement->Delete_user = Auth::user()->id;
                $paiement->save();
            }
            return redirect()->route('apaiements.facture', ['id' => $paiement->FactureId])
                ->with('success', 'Paiement supprimé avec succès.');
        } else {
            return redirect()->route('apaiements.index')
                ->with('danger', 'Ce paiement n\'existe pas.');
        }
    }
}
