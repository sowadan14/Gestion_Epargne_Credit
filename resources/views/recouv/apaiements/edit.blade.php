@extends('layouts.master')
@section('content')

<style type="text/css">
    input[type=file]::file-selector-button {
        margin-right: 5px;
        border: none;
        background: #084cdf;
        padding: 10px 5px;
        border-radius: 10px;
        color: #fff;
        cursor: pointer;
        transition: background .2s ease-in-out;
    }

    input[type=file]::file-selector-button:hover {
        background: #0d45a5;
    }

    .drop-container {
        position: relative;
        display: flex;
        margin: 10px;
        gap: 10px;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: inherit;
        padding: 5px;
        border-radius: 10px;
        border: 2px dashed #555;
        color: #444;
        cursor: pointer;
        transition: background .2s ease-in-out, border .2s ease-in-out;
    }

    .drop-container:hover {
        background: #eee;
        border-color: #111;
    }

    .drop-container:hover .drop-title {
        color: #222;
    }

    .drop-title {
        color: #444;
        font-size: 20px;
        font-weight: bold;
        text-align: center;
        transition: color .2s ease-in-out;
    }

    td,
    th {
        padding: 5px;
    }

    .innerTd {
        width: 100px;
    }

    .active {
        background-color: aqua;
    }

    #navMenus {
        list-style: none;
    }

    li {
        cursor: pointer;
        margin-bottom: 5px;
    }

    ul {
        margin-left: 0px;
    }

    .tableRecap td {
        white-space: nowrap;
        border-top: 0px solid #ddd;
    }

    .tableRecap td:first-child {
        width: 100%;
        border-top: 0px solid #ddd;
        padding-top: 1px;
        padding-bottom: 1px;
    }

    .tableRecap td:last-child {
        border-top: 0px solid #ddd;
        padding-top: 1px;
        padding-bottom: 1px;
    }
</style>

{!! Form::model($paiement, ['method' => 'PATCH','route' => ['apaiements.update', $paiement->id]]) !!}

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">

        <div class="EnteteContent">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group row ">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Date commande: </strong> {{\Carbon\Carbon::parse($afact->reception->commande->DateAchat)->format('d/m/Y')}}
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Réference commande: </strong> {{$afact->reception->commande->Reference}}
                        </div>
                    </div>

                    <div class="form-group row ">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Montant commande: </strong> {{number_format($afact->reception->commande->MontantTTC,0,',',' ') }} {{ auth()->user()->entreprise->Devise}}
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Date réception: </strong> {{\Carbon\Carbon::parse($afact->reception->DateReception)->format('d/m/Y')}}
                        </div>
                    </div>


                    <div class="form-group row ">

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Réference réception: </strong> {{$afact->reception->Reference}}
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Montant réception: </strong> {{number_format($afact->reception->MontantReçu,0,',',' ') }} {{ auth()->user()->entreprise->Devise}}
                        </div>
                    </div>
                </div>


            </div>
        </div>

        <div class="EnteteContent">
            <div class="row">
                <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 styleEntete">
                    <i class=" icon-money"></i> Gestion paiements
                </div>

                <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 text-right styleAction">
                    Modification d'un paiement
                </div>
            </div>
            <hr class="hrEntete">
            <div class="row">
                <input type="text" name="id" id="id" value="{{$paiement->id}}" hidden>
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <strong>Référence facture</strong>
                    <input class="form-control" readonly name="ReferenceFacture" value="{{old('ReferenceFacture',$afact->Reference)}}" type="text">
                    <input class="form-control hidden" name="FactureId" value="{{old('FactureId',$afact->id)}}" type="text">
                </div>
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <strong>Mode paiement</strong>
                    <div>
                        <select name="ModePaiementId" id="ModePaiementId">
                            <option value="">Séléctionner un mode paiement</option>
                            @foreach($modepaiements as $modepaiement)
                            <option value="{{$modepaiement->id}}" {{ ($modepaiement->id==$paiement->ModePaiementId) ? 'selected' : ''}} {{ (old('ModePaiementId')==$modepaiement->id) ? 'selected' : ''}}>
                                {{$modepaiement->Nom}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @if ($errors->has('ModePaiementId'))
                    <span class="red" style="font-weight:bold;">{{ $errors->first('ModePaiementId') }}</span>
                    @endif
                </div>

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <strong>Compte</strong>
                    <div>
                        <select name="CompteId" id="CompteId">
                            <option value="">Séléctionner un compte</option>
                            @foreach($comptes as $compte)
                            <option value="{{$compte->id}}" {{ ($compte->id==$paiement->CompteId) ? 'selected' : ''}} {{ (old('CompteId')==$compte->id) ? 'selected' : ''}}>
                                {{$compte->Libelle}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @if ($errors->has('CompteId'))
                    <span class="red" style="font-weight:bold;">{{ $errors->first('CompteId') }}</span>
                    @endif
                </div>


            </div>

            @if(Auth()->user()->entreprise->AvoirFrManager)
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <strong>Référence</strong>
                    <input class="form-control" name="Reference" value="{{old('Reference',$paiement->Reference)}}" type="text">
                    @if ($errors->has('Reference'))
                    <span class="red" style="font-weight:bold;">{{ $errors->first('Reference') }}</span>
                    @endif
                </div>

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <strong>Date paiement</strong>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-calendar bigger-110"></i>
                        </span>
                        <input class="form-control datepicker" name="DatePaiement" value="{{old('DatePaiement',\Carbon\Carbon::parse($paiement->DatePaiement)->format('d/m/Y'))}}" type="text" required>
                    </div>
                    @if ($errors->has('DatePaiement'))
                    <span class="red" style="font-weight:bold;">{{ $errors->first('DatePaiement') }}</span>
                    @endif
                </div>

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <label style="margin-top:25px;"><input name="IsAvoir" type="checkbox">
                        <strong> Mettre la monnaie en avoir</strong></label>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 bolder">
                    <input class="form-control hidden montantavoir" name="MontantAvoir" id="MontantAvoir" value="{{old('MontantAvoir',$montantavoir)}}" type="text">
                    <label style="margin-top:15px;"> <input name="PaidWithAvoir" id="PaidWithAvoir" type="checkbox">
                        <strong> Payer avec avoir (<span class="avoir">{{number_format($montantavoir,0,',',' ') }}</span>)</strong></label>
                </div>


                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <strong>Montant Rémis</strong>
                    <input class="form-control text-right" min='0' id="MontantRemis" name="MontantRemis" value="{{old('MontantRemis',$afact->MontantFacture-$afact->MontantPaye+$paiement->Montant)}}" type="text">
                    <input class="form-control text-right hidden" min='0' id="TotalAPayer" name="TotalAPayer" value="{{old('TotalAPayer',$afact->MontantFacture-$afact->MontantPaye+$paiement->Montant)}}" type="text">

                </div>

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <strong>Remise</strong>
                    <input class="form-control text-right remiseglobale" min='0' id="RemiseGlobale" name="RemiseGlobale" value="{{old('RemiseGlobale',0)}}" type="text">
                </div>

            </div>

            <div class="row">

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <strong>Reste à payer</strong>
                    <input class="form-control text-right" min='0' id="ResteAPayer" name="ResteAPayer" style="background-color:#cad7fa;" value="{{old('ResteAPayer',0)}}" readonly="readonly" type="text">
                </div>

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <strong>Monnaie</strong>
                    <input class="form-control text-right" min='0' id="Monnaie" name="Monnaie" value="{{old('Monnaie',0)}}" readonly="readonly" type="text">
                </div>
            </div>
            @else
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <strong>Référence</strong>
                    <input class="form-control" name="Reference" value="{{old('Reference',$paiement->Reference)}}" type="text">
                    @if ($errors->has('Reference'))
                    <span class="red" style="font-weight:bold;">{{ $errors->first('Reference') }}</span>
                    @endif
                </div>

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <strong>Date paiement</strong>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-calendar bigger-110"></i>
                        </span>
                        <input class="form-control datepicker" name="DatePaiement" value="{{old('DatePaiement',\Carbon\Carbon::parse($paiement->DatePaiement)->format('d/m/Y'))}}" type="text" required>
                    </div>
                    @if ($errors->has('DatePaiement'))
                    <span class="red" style="font-weight:bold;">{{ $errors->first('DatePaiement') }}</span>
                    @endif
                </div>

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <strong>Montant Rémis</strong>
                    <input class="form-control text-right" min='0' id="MontantRemis" name="MontantRemis" value="{{old('MontantRemis',$afact->MontantFacture-$afact->MontantPaye+$paiement->Montant)}}" type="text">
                    <input class="form-control text-right hidden" min='0' id="TotalAPayer" name="TotalAPayer" value="{{old('TotalAPayer',$afact->MontantFacture-$afact->MontantPaye+$paiement->Montant)}}" type="text">

                </div>

            </div>

            <div class="row">

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <strong>Remise</strong>
                    <input class="form-control text-right remiseglobale" min='0' id="RemiseGlobale" name="RemiseGlobale" value="{{old('RemiseGlobale',0)}}" type="text">
                </div>

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <strong>Reste à payer</strong>
                    <input class="form-control text-right" min='0' id="ResteAPayer" name="ResteAPayer" style="background-color:#cad7fa;" value="{{old('ResteAPayer',0)}}" readonly="readonly" type="text">
                </div>

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <strong>Monnaie</strong>
                    <input class="form-control text-right" min='0' id="Monnaie" name="Monnaie" value="{{old('Monnaie',0)}}" readonly="readonly" type="text">
                </div>

            </div>
            @endif

        </div>


    </div>
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
        <div class="EnteteContent">
            <div class="row">
                <div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
                    Détails de la facture
                </div>
            </div>
            <hr class="hrEntete">
            <div class=" detailsfact">
                @if($afact !='')
                <table>

                    <tr>
                        <td>
                            <strong>Fournisseur:</strong> {{$afact->reception->commande->fournisseur->Nom}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>Date facture:</strong>
                            {{ \Carbon\Carbon::parse($afact->DateFacture)->format('d/m/Y')}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>Date échéance:</strong>
                            {{ \Carbon\Carbon::parse($afact->DateEcheance)->format('d/m/Y')}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>Montant facture:</strong> {{number_format($afact->MontantFacture,0,',',' ') }}
                            {{ auth()->user()->entreprise->Devise}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>Montant payé:</strong> {{number_format($afact->MontantPaye-$paiement->Montant,0,',',' ') }}
                            {{ auth()->user()->entreprise->Devise}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>Reste à payer:</strong> {{number_format($afact->MontantFacture-$afact->MontantPaye+$paiement->Montant,0,',',' ') }}
                            {{ auth()->user()->entreprise->Devise}}
                        </td>
                    </tr>


                </table>
                @endif
            </div>
        </div>
    </div>
</div>







<div class="form-group" style="float:right;margin:15px;">
    <a href="{{ route('apaiements.facture',$afact->id) }}" class="btn btn-success btn-sm"><span class="dark bolder"><i class="glyphicon glyphicon-list"></i> Liste des paiements</span></a>

    <button type="submit" value="Create" class="btn btn-warning btn-sm bolder">
        <i class="glyphicon glyphicon-edit"></i> Modifier
    </button>
</div>
{!! Form::close() !!}


<script type="text/javascript">
    $(document).ready(function() {

        localStorage.setItem("myclass", 'apaiement');
        localStorage.setItem("father", 'recouv');

        CalculeSumChamps();

        function toNumberFormat(value) {
            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
        }


        $('#ModePaiementId').chosen();
        $("#ModePaiementId_chosen").css("width", "100%");

        $('#CompteId').chosen();
        $("#CompteId_chosen").css("width", "100%");

        $('.datepicker').datepicker({
            autoclose: true,
            format: 'dd/mm/yyyy',
            todayHighlight: true
        })

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function CalculeSumChamps() {
            var remiseglobale = $("#RemiseGlobale").val();
            var montantremis = $("#MontantRemis").val();
            var totalapayer = $("#TotalAPayer").val();

            if ($('#PaidWithAvoir').prop("checked") == true) {
                var montantavoir = $("#MontantAvoir").val();
                var montantpayer = parseFloat(montantremis) + parseFloat(montantavoir) + parseFloat(remiseglobale);
                if (montantpayer >= parseFloat(totalapayer)) {
                    $("#Monnaie").val(parseFloat(montantpayer) - parseFloat(totalapayer));
                    $("#ResteAPayer").val(0);
                } else {
                    $("#Monnaie").val(0);
                    $("#ResteAPayer").val(toNumberFormat(parseFloat(totalapayer) - parseFloat(montantpayer)));
                }

            } else {
                var montantpayer = parseFloat(montantremis) + parseFloat(remiseglobale);;
                if (montantpayer >= parseFloat(totalapayer)) {
                    $("#Monnaie").val(parseFloat(montantpayer) - parseFloat(totalapayer));
                    $("#ResteAPayer").val(0);
                } else {
                    $("#Monnaie").val(0)
                    $("#ResteAPayer").val(toNumberFormat(parseFloat(totalapayer) - parseFloat(montantpayer)));
                }
            }

        }

        $('#MontantRemis').on('change', function(e) {
            if ($(this).val() == "") {
                $(this).val(0);
            }
            CalculeSumChamps();
        });


        $('#RemiseGlobale').on('change', function(e) {
            if ($(this).val() == "") {
                $(this).val(0);
            }
            CalculeSumChamps();
        });

        $('#PaidWithAvoir').on('change', function(e) {
            CalculeSumChamps();
        });

    });
</script>
@endsection