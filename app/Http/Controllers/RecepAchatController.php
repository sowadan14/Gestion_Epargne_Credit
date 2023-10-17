<?php

namespace App\Http\Controllers;

use App\Helpers\helpers;

use App\Models\ReceptionAchat;
use App\Models\Achat;
use App\Models\Fournisseur;
use App\Models\Unite;
use App\Models\Produit;
use App\Models\ModePaiement;
use Brick\Math\BigInteger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RecepAchatController extends Controller
{

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:listarecep|createarecep|editarecep|deletearecep', ['only' => ['index','show']]);
        $this->middleware('permission:createarecep', ['only' => ['create','store']]);
        $this->middleware('permission:editarecep', ['only' => ['edit','update']]);
        $this->middleware('permission:deletearecep', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $data = ReceptionAchat::orderBy('id', 'DESC')->where('Supprimer', false)
            ->where('EntrepriseId', Auth::user()->EntrepriseId)
            ->where('MontantReçu', '>', 0)
            ->get();
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Achat</a></li><li class=\"breadcrumb-item\"><a href=\"/achat/receps\">Réception commande </a></li>";

        return view('achat.receps.index', compact('data'))->with('Titre', 'Réception commande')->with('Breadcrumb', $Breadcrumb);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Cmdes = Achat::where('Status', '0')->whereRaw('MontantTTC-MontantReçu')->get();
        $cmde = $Cmdes->first();

        // dd($Cmdes);
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Achat</a></li><li class=\"breadcrumb-item\"><a href=\"/achat/receps\">Réception fournisseur </a></li>";
        return view('achat.receps.create', compact('Cmdes', 'cmde'))->with('Titre', 'Réception fournisseur')->with('Breadcrumb', $Breadcrumb);
    }

    public function addrecept($id)
    {
        $cmde = Achat::find($id);
        if ($cmde == null) {
            return redirect()->route('receps.index');
        }
        // $Cmdes = Achat::all()->where('Status', '0');
        $Cmdes = Achat::where('Status', '0')->whereRaw('MontantTTC-MontantReçu')->get();
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Achat</a></li><li class=\"breadcrumb-item\"><a href=\"/achat/receps\">Réception fournisseur </a></li>";
        return view('achat.receps.create', compact('Cmdes', 'cmde'))->with('Titre', 'Réception fournisseur')->with('Breadcrumb', $Breadcrumb);
    }



    public function getDetailsRecept(Request $request)
    {

        if (!$request->id) {
            $htmlTable = '';
            $htmlDetailsCmde = '';
            $reference = '';
        } else {
            $htmlTable = '';
            $htmlDetailsCmde = '';
            $cmde = Achat::find($request->id);
            $htmlTable = view('achat.receps.tableCmde', compact('cmde'))->render();
            $htmlDetailsCmde = view('achat.receps.detailCmde', compact('cmde'))->render();
            $reference = generateRecepAchat();
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
                'DateReception' => 'required|date_format:"d/m/Y"',
                'CommandId' => 'required',
            ],
            [
                'DateReception.required' => 'Le champ Date réception est obligatoire.',
                'DateReception.date_format' => 'Le format de Date réception est incorrecte (dd/mm/yyyy).',
                'CommandId.required' => 'Le choix de réference commande est obligatoire.',
                // 'ModePaiementId.required' => 'Le choix du mode paiement est obligatoire.',
            ]
        );


        if (in_array(null, $request->input('Qte', [])) || in_array('', array_map('trim', $request->input('Qte', []))) || empty($request->input('Qte', []))) {
            return redirect()->route('receps.index')
                ->with('danger', 'Des données vides ont été saisies.');
        }

        $recep = new ReceptionAchat();
        $recep->DateReception = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateReception)));
        $recep->AchatId = $request->CommandId;
        $recep->Reference = $request->Reference;
        $recep->EntrepriseId = Auth::user()->EntrepriseId;
        $recep->Create_user = Auth::user()->id;
        $recep->save();

        $unites = $request->input('Unite', []);
        $produits = $request->input('Produit', []);
        $qtes = $request->input('Qte', []);

        $cmde = Achat::find($request->CommandId);

        for ($produit = 0; $produit < count($produits); $produit++) {
            if ($produits[$produit] != '' && $qtes[$produit] != '' && $qtes[$produit] != '0') {
                $cmdeproduit = $cmde->produits()->where('id', $produits[$produit])->wherePivot('UniteId', '=', $unites[$produit])->get()->first();


                if ($cmdeproduit != null) {
                    $montantHt = $qtes[$produit] * $cmdeproduit->pivot->Prix;
                    $remise = round(($montantHt * $cmdeproduit->pivot->Remise) / 100);
                    $tva = round(($montantHt * $cmdeproduit->pivot->Tva) / 100);
                    $montantttc = (int)($montantHt + $tva - $remise);
                    $cmde->MontantReçu = $cmde->MontantReçu + $montantttc;
                    $recep->MontantReçu = $recep->MontantReçu + $montantttc;

                    $recep->produits()->attach(
                        $produits[$produit],
                        [
                            'Qte' => $qtes[$produit],
                            'UniteId' => $unites[$produit],
                        ]
                    );
                }

                AddReceptionDetailsProduit($produits[$produit], $unites[$produit], $qtes[$produit], $recep->AchatId);
            }
        }
        // dd($recep,$cmde);
        $recep->save();
        $cmde->save();

        return redirect()->route('receps.index')
            ->with('success', 'Commande créée avec succès.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Achat  $achat
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $recep = ReceptionAchat::find($id);

        if ($recep == null) {
            return redirect()->route('receps.index');
        }
        $cmde = Achat::find($recep->AchatId);
        $Cmdes = Achat::all()->where('id', $cmde->id);
        $produits = Produit::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Achat</a></li><li class=\"breadcrumb-item\"><a href=\"/achat/receps\">Réception commande </a></li>";
        return view('achat.receps.show', compact('cmde', 'recep', 'Cmdes','produits'))->with('Titre', 'Réception commande')->with('Breadcrumb', $Breadcrumb);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Achat  $achat
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $recep = ReceptionAchat::find($id);
        if ($recep == null) {
            return redirect()->route('receps.index');
        }
        $cmde = Achat::find($recep->AchatId);
        $Cmdes = Achat::all()->where('id', $cmde->id);
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Achat</a></li><li class=\"breadcrumb-item\"><a href=\"/achat/receps\">Réception commande </a></li>";
        return view('achat.receps.edit', compact('cmde', 'recep', 'Cmdes'))->with('Titre', 'Réception commande')->with('Breadcrumb', $Breadcrumb);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Achat  $achat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate(
            $request,
            [
                'DateReception' => 'required|date_format:"d/m/Y"',
                'CommandId' => 'required',
            ],
            [
                'DateReception.required' => 'Le champ Date réception est obligatoire.',
                'DateReception.date_format' => 'Le format de Date réception est incorrecte (dd/mm/yyyy).',
                'CommandId.required' => 'Le choix de réference commande est obligatoire.',
                // 'ModePaiementId.required' => 'Le choix du mode paiement est obligatoire.',
            ]
        );

        if (in_array(null, $request->input('Qte', [])) || in_array('', array_map('trim', $request->input('Qte', [])))   || empty($request->input('Qte', []))) {
            return redirect()->route('receps.index')
                ->with('danger', 'Des données vides ont été saisies.');
        }

        $recep =  ReceptionAchat::find($request->id);
        if ($recep == null) {
            return redirect()->route('receps.index');
        }


        removeReceptionDetailsProduit($request->id);

        $cmde =  Achat::find($request->CommandId);
        $recep->DateReception = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateReception)));
        $recep->Reference = $request->Reference;
        $recep->Edit_user = Auth::user()->id;
        $recep->save();

        $recep->produits()->detach();


        $unites = $request->input('Unite', []);
        $produits = $request->input('Produit', []);
        $qtes = $request->input('Qte', []);

        $cmde = Achat::find($request->CommandId);

        for ($produit = 0; $produit < count($produits); $produit++) {
            if ($produits[$produit] != '' && $qtes[$produit] != '' && $qtes[$produit] != '0') {
                $cmdeproduit = $cmde->produits()->where('id', $produits[$produit])->wherePivot('UniteId', '=', $unites[$produit])->get()->first();


                if ($cmdeproduit != null) {
                    $montantHt = $qtes[$produit] * $cmdeproduit->pivot->Prix;
                    $remise = round(($montantHt * $cmdeproduit->pivot->Remise) / 100);
                    $tva = round(($montantHt * $cmdeproduit->pivot->Tva) / 100);
                    $montantttc = (int)($montantHt + $tva - $remise);
                    $cmde->MontantReçu = $cmde->MontantReçu + $montantttc;
                    $recep->MontantReçu = $recep->MontantReçu + $montantttc;

                    $recep->produits()->attach(
                        $produits[$produit],
                        [
                            'Qte' => $qtes[$produit],
                            'UniteId' => $unites[$produit],
                        ]
                    );
                }

                AddReceptionDetailsProduit($produits[$produit], $unites[$produit], $qtes[$produit], $recep->AchatId);
                // UpdateMontantReçu($recep->AchatId,$produits[$produit], $unites[$produit], $qtes[$produit],$qtereçus[$produit]);
            }
        }
        // dd($recep,$cmde);
        $recep->save();
        $cmde->Edit_user = Auth::user()->id;
        $cmde->save();

        return redirect()->route('receps.index')
            ->with('success', 'Réception modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Achat  $achat
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $recep = ReceptionAchat::find($id);
        if ($recep == null) {
            return redirect()->route('receps.index');
        }

        if (count(DB::table('factureachats')->where('ReceptionId', $id)->where('Supprimer', '0')->get()) > 0) {
            return redirect('/achat/receps')->with('danger', "Cette réception ne peut être supprimée car elle a déjà subi des facturations.");
        }

        removeReceptionDetailsProduit($id);
        $recep->produits()->detach();

        $recep->Supprimer = true;
        $recep->Delete_user = Auth::user()->id;
        $recep->save();

        return redirect()->route('receps.index')
            ->with('success', 'Réception supprimée avec succès.');
    }
}
