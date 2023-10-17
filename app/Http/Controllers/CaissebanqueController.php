<?php

namespace App\Http\Controllers;

use App\Models\Compte;
use App\Models\DetailsCompte;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use DateTime;


class CaissebanqueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:listcompte|createcompte|editcompte|deletecompte', ['only' => ['index', 'show']]);
        $this->middleware('permission:createcompte', ['only' => ['create', 'store']]);
        $this->middleware('permission:editcompte', ['only' => ['edit', 'update']]);
        $this->middleware('permission:deletecompte', ['only' => ['destroy']]);
    }

    public function index()
    {
        $data = Compte::orderBy('id', 'DESC')->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"/comptes\">Comptes</a></li>";
        return view('caissebanque.index', compact('data'))->with('Titre', 'Caisses/Banque')->with('Breadcrumb', $Breadcrumb);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/config/comptes\">Comptes</a></li>";
        return view('config.comptes.create')->with('Titre', 'Comptes')->with('Breadcrumb', $Breadcrumb);
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
                'DateDebut' => 'required|date_format:"d/m/Y"',
                'DateFin' => 'required|date_format:"d/m/Y"|after_or_equal:DateDebut',
            ],
            [
                'DateDebut.required' => 'Le champ Date début est obligatoire.',
                'DateDebut.date_format' => 'Le format de Date début est incorrect (dd/mm/yyyy).',
                'DateFin.required' => 'Le champ Date fin est obligatoire.',
                'DateFin.date_format' => 'Le format de Date fin est incorrect (dd/mm/yyyy).',
                'DateFin.after_or_equal' => 'La date fin doit être supérieure ou  égale à la date début.',
            ]
        );

        return redirect()->route('caissebanque.details', ['id' => $request->compteId, 'datedebut' => $request->DateDebut, 'datefin' => $request->DateFin])
            ->with('success', 'Compte créé avec succès');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Compte  $compte
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $compte = Compte::find($id);
        $collection = collect([]);

        $details = DetailsCompte::orderBy('id', 'DESC')->where('Supprimer', false)->where('CompteId', $compte->id)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();

        foreach ($details as $detail) {
            $collection->push([
                'Libelle' => $detail->Libelle,
                'DateOperation' => $detail->DateOperation,
                'Debit' => $detail->Debit,
                'Credit' => $detail->Credit,
            ]);
        }


        $collection->all();

        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/config/comptes\">Postes</a></li>";
        return view('caissebanque.show', compact('compte'))->with('Titre', 'Comptes')->with('Breadcrumb', $Breadcrumb);
    }


    public function details($id, Request $request)
    {
        $compte = Compte::find($id);
        $collection = collect([]);

        $date = date($request->datedebut);
        // dd(Carbon::parse(date_format($request->datedebut,'d/m/Y H:i:s')));


        if ($request->datedebut != '' && $request->datefin != '') {
            $details = DetailsCompte::whereDate('DateOperation', '>=', date('Y-m-d', strtotime(str_replace('/', '-', $request->datedebut))))
                ->whereDate('DateOperation', '<=', date('Y-m-d', strtotime(str_replace('/', '-', $request->datefin))))
                ->orderBy('id', 'asc')->where('Supprimer', false)
                ->where('CompteId', $compte->id)->where('EntrepriseId', Auth::user()->EntrepriseId)->get();


            $debit = DB::table('detailscomptes')->whereDate('DateOperation', '<', date('Y-m-d', strtotime(str_replace('/', '-', $request->datedebut))))
                ->where('CompteId', '=', $compte->id)->sum(DB::raw('Debit'));

            $credit = DB::table('detailscomptes')->whereDate('DateOperation', '<', date('Y-m-d', strtotime(str_replace('/', '-', $request->datedebut))))
                ->where('CompteId', '=', $compte->id)->sum(DB::raw('Credit'));

            $counter = 1;
            $solde=$credit-$debit;

            $collection->push([
                'id' => $counter,
                'Libelle' => 'Solde initial',
                'DateOperation' => '',
                'Debit' => $debit,
                'Credit' => $credit,
                'Solde' => $credit-$debit,
            ]);
            $counter = $counter + 1;

            foreach ($details as $detail) {
                $collection->push([
                    'id' => $counter,
                    'Libelle' => $detail->Libelle,
                    'DateOperation' => $detail->DateOperation,
                    'Debit' => $detail->Debit,
                    'Credit' => $detail->Credit,
                    'Solde' => $solde+$detail->Credit- $detail->Debit,
                ]);

                $counter = $counter + 1;
                $solde=$solde+$detail->Credit- $detail->Debit;
            }

          


            $collection->all();
            // dd($collection);
        }

        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/config/comptes\">Postes</a></li>";
        return view('caissebanque.details', compact('compte', 'collection'))->with('Titre', 'Comptes')->with('Breadcrumb', $Breadcrumb);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Compte  $compte
     * @return \Illuminate\Http\Response
     */
    public function edit($compte_id)
    {

        $compte = Compte::find($compte_id);
        $Breadcrumb = "<li class=\"breadcrumb-item\"><a href=\"#\">Configuration</a></li><li class=\"breadcrumb-item\"><a href=\"/config/comptes\">Comptes</a></li>";
        return view('config.comptes.edit', compact('compte'))->with('Titre', 'Comptes')->with('Breadcrumb', $Breadcrumb);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Compte  $compte
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Compte $compte)
    {
        $this->validate(
            $request,
            [
                'Libelle' => [
                    'required',
                    Rule::unique('comptes')->ignore($request->id, 'id')->where(function ($query) {
                        $query->where('EntrepriseId', Auth::user()->EntrepriseId);
                    }),
                ],
                'SoldeInitial' => "numeric",
            ],
            [
                'Libelle.required' => 'Le champ Libellé est obligatoire.',
                'Libelle.unique' => 'Ce compte existe déjà.',
                // 'soldeInitial.required' => 'Le champ Solde initial est obligatoire.',
                'SoldeInitial.numeric' => 'Le champ Solde initial doit être un nombre.',
            ]
        );

        $compte = Compte::find($request->id);
        $compte->Status = $request->Status == 'on' ? 1 : 0;
        $compte->Code = $request->Code;
        $compte->Libelle = $request->Libelle;
        $compte->SoldeInitial = $request->SoldeInitial;
        $compte->Edit_user = Auth::user()->id;
        $compte->save();

        return redirect()->route('comptes.index')
            ->with('success', 'Compte modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Compte  $compte
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        // $comptes= DB::table('comptes')->where('PosteId',$request->id)->get();
        if (count(DB::table('ventes')->where('CompteId', $id)->get()) > 0) {
            return redirect('/config/comptes')->with('danger', "Ce compte ne peut être supprimé car elle est utilisée par d'autres données.");
        } else {
            // Compte::where('id',$id)->delete();
            $compte = Compte::find($id);
            $compte->Supprimer = true;
            $compte->Delete_user = Auth::user()->id;
            $compte->save();
            return redirect('/config/comptes')->with('success', 'Compte supprimé avec succès.');
        }
    }
}
