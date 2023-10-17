<?php

namespace App\Http\Controllers;

use App\Models\Depense;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Compte;
use App\Models\TypeDepense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // function __construct()
    // {
    //     $this->middleware('auth');
    //     $this->middleware('permission:listvdepense|createvdepense|editvdepense|deletevdepense', ['only' => ['index', 'show']]);
    //     $this->middleware('permission:createvdepense', ['only' => ['create', 'store']]);
    //     $this->middleware('permission:editvdepense', ['only' => ['edit', 'update']]);
    //     $this->middleware('permission:deletevdepense', ['only' => ['destroy']]);
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data = Depense::orderBy('id', 'DESC')->where('Supprimer', false)
            ->where('EntrepriseId', Auth::user()->EntrepriseId)
            ->get();

        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Dépense</a></li><li class=\"breadcrumb-item\"><a href=\"/depenses/list\">Liste </a></li>";
        return view('depenses.list.index', compact('data'))->with('Titre', 'Dépenses')->with('Breadcrumb', $Breadcrumb);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $comptes = Compte::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $typedepenses = TypeDepense::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Dépense</a></li><li class=\"breadcrumb-item\"><a href=\"/depenses/list\">Liste </a></li>";
        return view('depenses.list.create', compact('comptes', 'typedepenses'))->with('Titre', 'Dépenses')->with('Breadcrumb', $Breadcrumb);
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
                'Libelle' => 'required',
                'TypeDepenseId' => 'required',
            ],
            [
                'DateOperation.required' => 'Le champ Date depense est obligatoire.',
                'DateOperation.date_format' => 'Le format de Date depense est incorrecte (dd/mm/yyyy).',
                'Libelle.required' => 'Le champ libellé est obligatoire.',
                'CompteId.required' => 'Le choix de compte est obligatoire.',
                'TypeDepenseId.required' => 'Le choix du type dépense est obligatoire.',
            ]
        );

        // dd($request);

        if ($request->input('Montant') == null || $request->input('Montant') == '') {
            return redirect()->route('list.index')
                ->with('danger', 'Saisie d\'un montant incorrect.');
        }

        $compte = Compte::find($request->CompteId);

        if ($compte != null) {
            if ($request->input('Montant') != 0) {
                $depense = new Depense();
                $depense->DateOperation = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateOperation)));
                $depense->CompteId = $request->CompteId;
                $depense->TypeDepenseId = $request->TypeDepenseId;
                $depense->Montant = $request->Montant;
                $depense->Libelle = $request->Libelle;
                $depense->EntrepriseId = Auth::user()->EntrepriseId;
                $depense->Create_user = Auth::user()->id;
                $depense->save();

                $compte->Solde = $compte->Solde - $depense->Montant;
                $compte->save();

                $libelle = "Dépenses pour " . $depense->Libelle;
                AjoutDetailsCompte($compte->id, $libelle, $depense->Montant, 0, $depense->DateOperation);
            }
        }
        return redirect()->route('list.index')
            ->with('success', 'Dépense créée avec succès.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vente  $depenses
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $depense = Depense::find($id);
        if ($depense != null) {
            $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Dépense</a></li><li class=\"breadcrumb-item\"><a href=\"/depenses/list\">Liste </a></li>";
            return view('depenses.list.show', compact('depense'))->with('Titre', 'Dépenses')->with('Breadcrumb', $Breadcrumb);
        } else {
            return redirect()->route('depenses.index')
                ->with('danger', 'Cette dépense n\'existe pas.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vente  $depenses
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $depense = Depense::find($id);
        if ($depense != null) {
            $comptes = Compte::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
            $typedepenses = TypeDepense::where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
            $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Dépense</a></li><li class=\"breadcrumb-item\"><a href=\"/depenses/list\">Liste </a></li>";
            return view('depenses.list.edit', compact('depense', 'comptes', 'typedepenses'))->with('Titre', 'Dépenses')->with('Breadcrumb', $Breadcrumb);
        } else {
            return redirect()->route('list.index')
                ->with('danger', 'Cette dépense n\'existe pas.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vente  $depenses
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $this->validate(
            $request,
            [
                'DateOperation' => 'required|date_format:"d/m/Y"',
                'CompteId' => 'required',
                'Libelle' => 'required',
                'TypeDepenseId' => 'required',
            ],
            [
                'DateOperation.required' => 'Le champ Date depense est obligatoire.',
                'DateOperation.date_format' => 'Le format de Date depense est incorrecte (dd/mm/yyyy).',
                'Libelle.required' => 'Le champ libellé est obligatoire.',
                'CompteId.required' => 'Le choix de compte est obligatoire.',
                'TypeDepenseId.required' => 'Le choix du type dépense est obligatoire.',
            ]
        );

        // dd($request);

        if ($request->input('Montant') == null || $request->input('Montant') == '') {
            return redirect()->route('list.index')
                ->with('danger', 'Saisie d\'un montant incorrect.');
        }

        $depense =  Depense::find($request->id);
        if ($depense != null) {
            $compte = Compte::find($depense->CompteId);
            if ($compte != null) {
                if ($request->input('Montant') != 0) {


                    $compte->Solde = $compte->Solde + $depense->Montant;
                    $compte->save();

                    $libelle = "Annul dépense pour " . $depense->Libelle;
                    AjoutDetailsCompte($compte->id, $libelle, 0, $depense->Montant, $depense->DateOperation);

                    $depense->DateOperation = date('Y-m-d', strtotime(str_replace('/', '-', $request->DateOperation)));
                    $depense->CompteId = $request->CompteId;
                    $depense->TypeDepenseId = $request->TypeDepenseId;
                    $depense->Montant = $request->Montant;
                    $depense->Libelle = $request->Libelle;
                    $depense->Edit_user = Auth::user()->id;
                    $depense->save();

                    $compte->Solde = $compte->Solde - $depense->Montant;
                    $compte->save();

                    $libelle = "Dépense pour " . $depense->Libelle;
                    AjoutDetailsCompte($compte->id, $libelle, $depense->Montant, 0, $depense->DateOperation);
                }
            }

            return redirect()->route('list.index')
                ->with('success', 'Dépenses modifiée avec succès.');
        } else {
            return redirect()->route('list.index')
                ->with('danger', 'Cette dépense n\'existe pas.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vente  $depenses
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $depense =  Depense::find($id);

        if ($depense != null) {
            $compte = Compte::find($depense->CompteId);
            if ($compte != null) {

                $compte->Solde = $compte->Solde + $depense->Montant;
                $compte->save();

                $libelle = "Annul dépense pour " . $depense->Libelle;
                AjoutDetailsCompte($compte->id, $libelle, 0, $depense->Montant, $depense->DateOperation);
               
                $depense->Supprimer = true;
                $depense->Delete_user = Auth::user()->id;
                $depense->save();
            }

            return redirect()->route('list.index')
                ->with('success', 'Dépense supprimée avec succès.');
        } else {
            return redirect()->route('list.index')
                ->with('danger', 'Cette Dépense n\'existe pas.');
        }
    }
}
