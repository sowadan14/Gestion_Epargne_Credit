<?php

namespace App\Http\Controllers;

use App\Helpers\helpers;
use Illuminate\Support\Carbon;
use App\Models\Compte;
use App\Models\RegulCompte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegulCompteController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // function __construct()
    // {
    //     $this->middleware('auth');
    //     $this->middleware('permission:listvregulcompte|createvregulcompte|editvregulcompte|deletevregulcompte', ['only' => ['index', 'show']]);
    //     $this->middleware('permission:createvregulcompte', ['only' => ['create', 'store']]);
    //     $this->middleware('permission:editvregulcompte', ['only' => ['edit', 'update']]);
    //     $this->middleware('permission:deletevregulcompte', ['only' => ['destroy']]);
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data = RegulCompte::orderBy('id', 'DESC')->where('Supprimer', false)
            ->where('EntrepriseId', Auth::user()->EntrepriseId)
            ->get();

        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Régularisation</a></li><li class=\"breadcrumb-item\"><a href=\"/regul/regulcomptes\">Compte </a></li>";
        return view('regul.regulcomptes.index', compact('data'))->with('Titre', 'Régularisation compte')->with('Breadcrumb', $Breadcrumb);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $comptes = Compte::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Régularisation</a></li><li class=\"breadcrumb-item\"><a href=\"/regul/regulcomptes\">Régularisation compte </a></li>";
        return view('regul.regulcomptes.create', compact('comptes'))->with('Titre', 'Régularisation compte')->with('Breadcrumb', $Breadcrumb);
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
                'DateOperation' => 'required|date_format:"d/m/Y"',
                'CompteId' => 'required',
            ],
            [
                'DateOperation.required' => 'Le champ Date regulcompte est obligatoire.',
                'DateOperation.date_format' => 'Le format de Date regulcompte est incorrecte (dd/mm/yyyy).',
                'CompteId.required' => 'Le choix de compte est obligatoire.',
            ]
        );

        // dd($request);

        if ($request->input('Montant') == null || $request->input('Montant') == '') {
            return redirect()->route('regulcomptes.index')
                ->with('danger', 'Saisie d\'un montant incorrect.');
        }

        $compte = Compte::find($request->CompteId);

        if ($compte != null) {
            if ($request->input('Montant') != 0) {
                $regulcompte = new RegulCompte();
                $regulcompte->DateOperation = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateOperation)));
                $regulcompte->CompteId = $request->CompteId;
                $regulcompte->Montant = $request->Montant;
                $regulcompte->Entree = $request->Entree == 'on' ? 1 : 0;
                $regulcompte->EntrepriseId = Auth::user()->EntrepriseId;
                $regulcompte->Create_user = Auth::user()->id;
                $regulcompte->save();

                if ($regulcompte->Entree == 1) {
                    $compte->Solde = $compte->Solde + $regulcompte->Montant;
                    $compte->save();

                    $libelle = "Régularisation compte au " . Carbon::parse($regulcompte->DateOperation)->format('d/m/Y');
                    AjoutDetailsCompte($compte->id, $libelle, 0, $regulcompte->Montant, $regulcompte->DateOperation);
                } else {
                    $compte->Solde = $compte->Solde - $regulcompte->Montant;
                    $compte->save();

                    $libelle = "Régularisation compte au " . Carbon::parse($regulcompte->DateOperation)->format('d/m/Y');
                    AjoutDetailsCompte($compte->id, $libelle, $regulcompte->Montant, 0, $regulcompte->DateOperation);
                }
            }
        }
        return redirect()->route('regulcomptes.index')
            ->with('success', 'Régularisation compte créée avec succès.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vente  $regul
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
        $regulcompte = RegulCompte::find($id);
        if ($regulcompte != null) {
             $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Régularisation</a></li><li class=\"breadcrumb-item\"><a href=\"/regul/regulcomptes\">Compte </a></li>";
            return view('regul.regulcomptes.show', compact('regulcompte'))->with('Titre', 'Régularisation compte')->with('Breadcrumb', $Breadcrumb);
        } else {
            return redirect()->route('regulcomptes.index')
                ->with('danger', 'Cette régularisation compte n\'existe pas.');
        }
        }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vente  $regul
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $regulcompte = RegulCompte::find($id);
        if ($regulcompte != null) {
            $comptes = Compte::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
            $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Régularisation</a></li><li class=\"breadcrumb-item\"><a href=\"/regul/regulcomptes\">Compte </a></li>";
            return view('regul.regulcomptes.edit', compact('regulcompte', 'comptes'))->with('Titre', 'Régularisation compte')->with('Breadcrumb', $Breadcrumb);
        } else {
            return redirect()->route('regulcomptes.index')
                ->with('danger', 'Cette régularisation compte n\'existe pas.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vente  $regul
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $this->validate(
            $request,
            [
                'DateOperation' => 'required|date_format:"d/m/Y"',
                'CompteId' => 'required',
            ],
            [
                'DateOperation.required' => 'Le champ Date regulcompte est obligatoire.',
                'DateOperation.date_format' => 'Le format de Date regulcompte est incorrecte (dd/mm/yyyy).',
                'CompteId.required' => 'Le choix de compte est obligatoire.',
            ]
        );



        if ($request->input('Montant') == null || $request->input('Montant') == '') {
            return redirect()->route('regulcomptes.index')
                ->with('danger', 'Saisie d\'un montant incorrect.');
        }

        $regulcompte =  RegulCompte::find($request->id);
        if ($regulcompte != null) {
            $compte = Compte::find($regulcompte->CompteId);
            if ($compte != null) {
                if ($request->input('Montant') != 0) {

                    if ($regulcompte->Entree == 1) {
                        $compte->Solde = $compte->Solde - $regulcompte->Montant;
                        $compte->save();

                        $libelle = "Annul Régul. compte au " . Carbon::parse($regulcompte->DateOperation)->format('d/m/Y');
                        AjoutDetailsCompte($compte->id, $libelle, $regulcompte->Montant, 0, $regulcompte->DateOperation);
                    } else {
                        $compte->Solde = $compte->Solde + $regulcompte->Montant;
                        $compte->save();

                        $libelle = "Annul Régul. compte au " . Carbon::parse($regulcompte->DateOperation)->format('d/m/Y');
                        AjoutDetailsCompte($compte->id, $libelle, 0, $regulcompte->Montant, $regulcompte->DateOperation);
                    }


                    $regulcompte->DateOperation = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateOperation)));
                    $regulcompte->CompteId = $request->CompteId;
                    $regulcompte->Montant = $request->Montant;
                    $regulcompte->Entree = $request->Entree == 'on' ? 1 : 0;
                    $regulcompte->Edit_user = Auth::user()->id;
                    $regulcompte->save();

                    if ($regulcompte->Entree == 1) {
                        $compte->Solde = $compte->Solde + $regulcompte->Montant;
                        $compte->save();

                        $libelle = "Régularisation compte au " . Carbon::parse($regulcompte->DateOperation)->format('d/m/Y');
                        AjoutDetailsCompte($compte->id, $libelle, 0, $regulcompte->Montant, $regulcompte->DateOperation);
                    } else {
                        $compte->Solde = $compte->Solde - $regulcompte->Montant;
                        $compte->save();

                        $libelle = "Régularisation compte au " . Carbon::parse($regulcompte->DateOperation)->format('d/m/Y');
                        AjoutDetailsCompte($compte->id, $libelle, $regulcompte->Montant, 0, $regulcompte->DateOperation);
                    }
                }
            }

            return redirect()->route('regulcomptes.index')
                ->with('success', 'Régularisation compte modifiée avec succès.');
        } else {
            return redirect()->route('regulcomptes.index')
                ->with('danger', 'Cette Régularisation compte n\'existe pas.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vente  $regul
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $regulcompte =  RegulCompte::find($id);

        if ($regulcompte != null) {
            $compte = Compte::find($regulcompte->CompteId);
            if ($compte != null) {
                if ($regulcompte->Entree == 1) {
                    $compte->Solde = $compte->Solde - $regulcompte->Montant;
                    $compte->save();

                    $libelle = "Annul Régul. compte au " . Carbon::parse($regulcompte->DateOperation)->format('d/m/Y');
                    AjoutDetailsCompte($compte->id, $libelle, $regulcompte->Montant, 0, $regulcompte->DateOperation);
                } else {
                    $compte->Solde = $compte->Solde + $regulcompte->Montant;
                    $compte->save();

                    $libelle = "Annul Régul. compte au " . Carbon::parse($regulcompte->DateOperation)->format('d/m/Y');
                    AjoutDetailsCompte($compte->id, $libelle, 0, $regulcompte->Montant, $regulcompte->DateOperation);
                }
            }

            return redirect()->route('regulcomptes.index')
                ->with('success', 'Régularisation compte supprimée avec succès.');
        } else {
            return redirect()->route('regulcomptes.index')
                ->with('danger', 'Cette Régularisation compte n\'existe pas.');
        }
    }
}
