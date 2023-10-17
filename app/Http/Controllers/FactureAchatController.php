<?php

namespace App\Http\Controllers;

use App\Helpers\helpers;

use App\Models\ReceptionAchat;
use App\Models\FactureAchat;
use App\Models\Achat;
use App\Models\Fournisseur;
use App\Models\Unite;
use App\Models\Produit;
use App\Models\ModePaiement;
use Brick\Math\BigInteger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FactureAchatController extends Controller
{

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:listafact|createafact|editafact|deleteafact', ['only' => ['index','show']]);
        $this->middleware('permission:createafact', ['only' => ['create','store']]);
        $this->middleware('permission:editafact', ['only' => ['edit','update']]);
        $this->middleware('permission:deleteafact', ['only' => ['destroy']]);
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

        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Recouvrement</a></li><li class=\"breadcrumb-item\"><a href=\"/recouv/afacts\">Facture fournisseur </a></li>";
        return view('recouv.afacts.index', compact('data'))->with('Titre', 'Facture fournisseur')->with('Breadcrumb', $Breadcrumb);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $Cmdes = Achat::all()->where('Status', '1');
        $Receps = ReceptionAchat::where('Status', '0')->whereRaw('MontantReçu-MontantFacture')->get();;
        $recep = $Receps->first();
        // $cmde = Achat::find($recep->AchatId);
        // dd($recep);
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Recouvrement</a></li><li class=\"breadcrumb-item\"><a href=\"/recouv/afacts\">Facture fournisseur </a></li>";
        return view('recouv.afacts.create', compact('recep', 'Receps'))->with('Titre', 'Facture fournisseur')->with('Breadcrumb', $Breadcrumb);
    }

    public function addfact($id)
    {
        $recep = ReceptionAchat::find($id);
        if ($recep == null) {
            return redirect()->route('afacts.index');
        }
        $Receps = ReceptionAchat::where('Status', '0')->whereRaw('MontantReçu-MontantFacture')->get();
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Recouvrement</a></li><li class=\"breadcrumb-item\"><a href=\"/recouv/afacts\">Facture fournisseur </a></li>";
        return view('recouv.afacts.create', compact('recep', 'Receps'))->with('Titre', 'Facture fournisseur')->with('Breadcrumb', $Breadcrumb);
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
            $recep = ReceptionAchat::find($request->id);
            $htmlTable = view('recouv.afacts.tableCmde', compact('recep'))->render();
            $htmlDetailsRecep = view('recouv.afacts.detailCmde', compact('recep'))->render();
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
                'DateFacture' => 'required|date_format:"d/m/Y"',
                'DateEcheance' => 'required|date_format:"d/m/Y"',
                'ReceptionId' => 'required',
            ],
            [
                'DateFacture.required' => 'Le champ Date réception est obligatoire.',
                'DateFacture.date_format' => 'Le format de Date réception est incorrecte (dd/mm/yyyy).',
                'DateEcheance.required' => 'Le champ Date d\'échéance est obligatoire.',
                'DateEcheance.date_format' => 'Le format de Date d\'échéance est incorrecte (dd/mm/yyyy).',
                'ReceptionId.required' => 'Le choix de réference réception est obligatoire.',
                // 'ModePaiementId.required' => 'Le choix du mode paiement est obligatoire.',
            ]
        );

        // dd($request);

        if (in_array(null, $request->input('Qte', [])) || in_array('', array_map('trim', $request->input('Qte', [])))  || empty($request->input('Qte', []))) {
            return redirect()->route('afacts.index')
                ->with('danger', 'Des données vides ont été saisies.');
        }
        // dd($request);
        $afact = new FactureAchat();
        $afact->DateFacture = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateFacture)));
        $afact->DateEcheance = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateEcheance)));
        $afact->ReceptionId = $request->ReceptionId;
        $afact->Reference = $request->Reference;
        $afact->EntrepriseId = Auth::user()->EntrepriseId;
        $afact->Create_user = Auth::user()->id;
        $afact->save();

        $unites = $request->input('Unite', []);
        $produits = $request->input('Produit', []);
        $qtes = $request->input('Qte', []);

        // dd($unites,$produits,$qtes);

        $recep = ReceptionAchat::find($request->ReceptionId);
        $cmde = Achat::find($recep->AchatId);

        for ($produit = 0; $produit < count($produits); $produit++) {
            if ($produits[$produit] != '' && $qtes[$produit] != '' && $qtes[$produit] != '0') {
                $cmdeproduit = $cmde->produits()->where('id', $produits[$produit])->wherePivot('UniteId', '=', $unites[$produit])->get()->first();

                if ($cmdeproduit != null) {
                    $montantHt = $qtes[$produit] * $cmdeproduit->pivot->Prix;
                    $remise = round(($montantHt * $cmdeproduit->pivot->Remise) / 100);
                    $tva = round(($montantHt * $cmdeproduit->pivot->Tva) / 100);
                    $montantttc = (int)($montantHt + $tva - $remise);
                    $afact->MontantFacture = $afact->MontantFacture + $montantttc;
                    $recep->MontantFacture = $recep->MontantFacture + $montantttc;

                    $afact->produits()->attach(
                        $produits[$produit],
                        [
                            'Qte' => $qtes[$produit],
                            'UniteId' => $unites[$produit],
                        ]
                    );
                }

                AddFacturationDetailsProduit($produits[$produit], $unites[$produit], $qtes[$produit], $recep->id);
            }
        }
        // dd($recep,$afact);

        if ($recep->MontantReçu == $recep->MontantFacture) {
            $recep->Status = 1;
        }
        $recep->save();
        $afact->save();

        return redirect()->route('afacts.index')
            ->with('success', 'Facture créée avec succès.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Achat  $recouv
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $afact = FactureAchat::find($id);
        if ($afact == null) {
            return redirect()->route('afacts.index');
        }
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Recouvrement</a></li><li class=\"breadcrumb-item\"><a href=\"/recouv/afacts\">Facture fournisseur </a></li>";
        return view('recouv.afacts.show', compact('afact'))->with('Titre', 'Facture fournisseur')->with('Breadcrumb', $Breadcrumb);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Achat  $recouv
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $afact = FactureAchat::find($id);
        if ($afact == null) {
            return redirect()->route('afacts.index');
        }
        $recep = ReceptionAchat::find($afact->ReceptionId);
        $Receps = ReceptionAchat::all()->where('id', $recep->id);
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Recouvrement</a></li><li class=\"breadcrumb-item\"><a href=\"/recouv/afacts\">Facture fournisseur </a></li>";
        return view('recouv.afacts.edit', compact('recep', 'Receps', 'afact'))->with('Titre', 'Facture fournisseur')->with('Breadcrumb', $Breadcrumb);
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
                'DateFacture' => 'required|date_format:"d/m/Y"',
                'DateEcheance' => 'required|date_format:"d/m/Y"',
                'ReceptionId' => 'required',
            ],
            [
                'DateFacture.required' => 'Le champ Date réception est obligatoire.',
                'DateFacture.date_format' => 'Le format de Date réception est incorrecte (dd/mm/yyyy).',
                'DateEcheance.required' => 'Le champ Date d\'échéance est obligatoire.',
                'DateEcheance.date_format' => 'Le format de Date d\'échéance est incorrecte (dd/mm/yyyy).',
                'ReceptionId.required' => 'Le choix de réference réception est obligatoire.',
                // 'ModePaiementId.required' => 'Le choix du mode paiement est obligatoire.',
            ]
        );

        if (in_array(null, $request->input('Qte', [])) || in_array('', array_map('trim', $request->input('Qte', [])))  || empty($request->input('Qte', []))) {
            return redirect()->route('afacts.index')
                ->with('danger', 'Des données vides ont été saisies.');
        }



        $afact =  FactureAchat::find($request->id);
        if ($afact == null) {
            return redirect()->route('afacts.index');
        }
        removeFacturationDetailsProduit($request->id);
        $recep =  ReceptionAchat::find($request->ReceptionId);
        $afact->DateFacture = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateFacture)));
        $afact->DateEcheance = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateEcheance)));
        $afact->Reference = $request->Reference;
        $afact->Edit_user = Auth::user()->id;
        $afact->save();

        $afact->produits()->detach();

        $unites = $request->input('Unite', []);
        $produits = $request->input('Produit', []);
        $qtes = $request->input('Qte', []);

        // dd($unites,$produits,$qtes);
        $cmde = Achat::find($recep->AchatId);

        for ($produit = 0; $produit < count($produits); $produit++) {
            if ($produits[$produit] != '' && $qtes[$produit] != '' && $qtes[$produit] != '0') {
                $cmdeproduit = $cmde->produits()->where('id', $produits[$produit])->wherePivot('UniteId', '=', $unites[$produit])->get()->first();

                if ($cmdeproduit != null) {
                    $montantHt = $qtes[$produit] * $cmdeproduit->pivot->Prix;
                    $remise = round(($montantHt * $cmdeproduit->pivot->Remise) / 100);
                    $tva = round(($montantHt * $cmdeproduit->pivot->Tva) / 100);
                    $montantttc = (int)($montantHt + $tva - $remise);
                    $afact->MontantFacture = $afact->MontantFacture + $montantttc;
                    $recep->MontantFacture = $recep->MontantFacture + $montantttc;

                    $afact->produits()->attach(
                        $produits[$produit],
                        [
                            'Qte' => $qtes[$produit],
                            'UniteId' => $unites[$produit],
                        ]
                    );
                }

                AddFacturationDetailsProduit($produits[$produit], $unites[$produit], $qtes[$produit], $recep->id);
            }
        }
        // dd($recep,$afact);

        $afact->save();

        if ($recep->MontantReçu == $recep->MontantFacture) {
            $recep->Status = 1;
        }

        $recep->Edit_user = Auth::user()->id;
        $recep->save();

        return redirect()->route('afacts.index')
            ->with('success', 'Facture modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Achat  $recouv
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $afact = FactureAchat::find($id);
        if ($afact == null) {
            return redirect()->route('afacts.index');
        }
        if (count(DB::table('paiementachats')->where('FactureId', $id)->where('Supprimer', '0')->get()) > 0) {
            return redirect('/recouv/afacts')->with('danger', "Cette facture ne peut être supprimée car elle a déjà subi des paiements.");
        }


        removeFacturationDetailsProduit($id);
        $afact->produits()->detach();
        $recep = ReceptionAchat::find($afact->ReceptionId);
        if ($recep != null) {
            $recep->MontantFacture = $recep->MontantFacture - $afact->MontantFacture;
            if ($recep->MontantReçu == $recep->MontantFacture) {
                $recep->Status = 1;
            } else {
                $recep->Status = 0;
            }
            $recep->save();
        }



        $afact->Supprimer = true;
        $afact->Delete_user = Auth::user()->id;
        $afact->save();

        return redirect()->route('afacts.index')
            ->with('success', 'Facture supprimée avec succès.');
    }
}
