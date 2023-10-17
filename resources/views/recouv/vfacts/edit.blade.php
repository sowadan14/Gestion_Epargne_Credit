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

{!! Form::model($vfact, ['method' => 'PATCH','route' => ['vfacts.update', $vfact->id]]) !!}

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
        <div class="EnteteContent">
            <div class="row">

                <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 styleEntete">
                    <i class=" icon-books"></i> Gestion factures
                </div>

                <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 text-right styleAction">
                    Création d'une facture
                </div>
            </div>
            <hr class="hrEntete">
            <!-- <p><button class="btn btn-sm btn-primary"><span class=" glyphicon glyphicon-plus"></span> Nouveau</button></p> -->

            <!-- <a class="btn btn-success" href="javascript:void(0)" id="createNewProduct"> Create New Product</a> -->
            <div class="row">
                <input type="text" name="id" id="id" value="{{$vfact->id}}" hidden>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <strong>Réference livraison</strong>
                                <div>
                                    <select name="LivraisonId" id="LivraisonId">
                                        @foreach($Livrs as $Livr)
                                        <option value="{{$Livr->id}}" {{ ($Livr->id==$livr->id) ? 'selected' : ''}} {{ (old('LivraisonId')==$Livr->id) ? 'selected' : ''}}> {{$Livr->Reference}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($errors->has('LivraisonId'))
                                <span class="red" style="font-weight:bold;">{{ $errors->first('LivraisonId') }}</span>
                                @endif
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <strong>Référence</strong>
                                <input class="form-control" name="Reference" value="{{old('Reference',$vfact->Reference)}}" type="text">
                                @if ($errors->has('Reference'))
                                <span class="red" style="font-weight:bold;">{{ $errors->first('Reference') }}</span>
                                @endif
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <strong>Date facture</strong>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar bigger-110"></i>
                                    </span>
                                    <input class="form-control datepicker" name="DateFacture" value="{{old('DateFacture',\Carbon\Carbon::parse($vfact->DateFacture)->format('d/m/Y'))}}" type="text" required>
                                </div>
                                @if ($errors->has('DateFacture'))
                                <span class="red" style="font-weight:bold;">{{ $errors->first('DateFacture') }}</span>
                                @endif
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <strong>Date échéance</strong>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar bigger-110"></i>
                                    </span>
                                    <input class="form-control datepicker" name="DateEcheance" value="{{old('DateEcheance',\Carbon\Carbon::parse($vfact->DateEcheance)->format('d/m/Y'))}}" type="text" required>
                                </div>
                                @if ($errors->has('DateEcheance'))
                                <span class="red" style="font-weight:bold;">{{ $errors->first('DateEcheance') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>

    </div>
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
        <div class="EnteteContent">
            <div class="row">
                <div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
                    Détails de la livraison
                </div>
            </div>
            <hr class="hrEntete">
            <div class=" detailsfact">
                <table>

                    <tr>
                        <td>
                            <strong>Date livraison:</strong>
                            {{ \Carbon\Carbon::parse($livr->DateReception)->format('d/m/Y')}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>Date commande:</strong>
                            {{ \Carbon\Carbon::parse($livr->commande->DateAchat)->format('d/m/Y')}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>Montant livraison:</strong> {{number_format($livr->MontantLivre,0,',',' ') }}
                            {{ auth()->user()->entreprise->Devise}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>Fournisseur:</strong> {{$livr->commande->client->Nom}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>Status livraison:</strong>
                          
        @php

if(($livr->commande->MontantLivre/$livr->commande->MontantTTC)*100 < 99.9999 && ($livr->commande->MontantLivre/$livr->commande->MontantTTC)*100 > 99.9)
    {
    $StatusReception=99;
    }
    else
    {
    $StatusReception=round(($livr->commande->MontantLivre/$livr->commande->MontantTTC)*100);
    }
    @endphp

                            <div class="progress pos-rel" data-percent="{{$StatusReception}}%">
                                <div class="progress-bar" style="width:{{$StatusReception}}%;"></div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>




<div class="EnteteContent">
    <div class="form-group row">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="overflow-y: scroll;max-height:200px;">
                <table class="table table-bordered " id="TableOfData" style="font-size:90%;">
                    <tr style="background:#006fcf;color:#FFF;font-weight:bold;">

                        <td hidden>Unité</td>
                        <td>Produit</td>
                        <td>Unité</td>
                        <td class="text-right innerTd">Quantité</td>
                        <td class="text-right innerTd">Prix Vente</td>
                        <td class="text-right innerTd">Remise (%)</td>
                        <td class="text-right innerTd">TVA (%)</td>
                        <td class="text-right">Montant TTC</td>
                        <td class="text-center">Action</td>
                    </tr>
                    <tbody id="DetailsUnites">
                        @if($vfact->produits->count()>0)
                        @foreach ($vfact->produits as $vfact_produit)
                        @foreach ($vfact->livraison->produits as $livr_produit)
                        @foreach ($vfact->livraison->commande->produits->where('id',$livr_produit->id) as $cmde_produit)

                        @if($vfact_produit->pivot->UniteId==$cmde_produit->pivot->UniteId)

                        @php
                        $Quantite= $livr_produit->pivot->Qte - $livr_produit->pivot->QteFacture + $vfact_produit->pivot->Qte;
                        @endphp
                        
                        <tr class='PUnite{{$livr_produit->pivot->UniteId}}{{$livr_produit->id}}'>
                            <td>{{$cmde_produit->Libelle}} {{$livr_produit->pivot->Qte}} {{$livr_produit->pivot->QteFacture}} {{$vfact_produit->pivot->Qte}}</td>
                            <td>{{$cmde_produit->unites->where('id',$cmde_produit->pivot->UniteId)->first()->Nom}}</td>
                            <td><input class='text-right form-control' list='PUnite{{$cmde_produit->pivot->UniteId}}{{$cmde_produit->id}}' name='Qte[]' min='0' max="{{$Quantite}}" value="{{old('Qte',$Quantite)}}" type='number' required />
                            </td>
                            <td class='text-right'>{{number_format($cmde_produit->pivot->Prix,0,',',' ') }}</td>
                            <td class='text-right'>{{number_format($cmde_produit->pivot->Remise,0,',',' ') }}</td>
                            <td class='text-right'>{{number_format($cmde_produit->pivot->Tva,0,',',' ') }}</td>
                            <td class='text-right'>{{number_format($cmde_produit->pivot->Montant,0,',',' ') }}</td>
                            <td style='text-align:center;'><button class='btn btn-danger btn-sm remove_this' style='width:25px;height:25px;border-radius:100px;' type='button' name='PUnite{{$cmde_produit->pivot->UniteId}}{{$cmde_produit->id}}'><span class='fa fa-trash'></span></button>
                            </td>
                            <td hidden><input class='text-right form-control' list='PUnite{{$cmde_produit->pivot->UniteId}}{{$cmde_produit->id}}' name='Produit[]' min='0' value="{{old('Produit',$livr_produit->id)}}" type='number' />
                            </td>

                            <td hidden><input class='text-right form-control' list='PUnite{{$cmde_produit->pivot->UniteId}}{{$cmde_produit->id}}' name='Unite[]' min='0' value="{{old('Unite',$livr_produit->pivot->UniteId)}}" type='number' />
                            </td>
                        </tr>
                        @break
                        @endif
                        @endforeach
                        @endforeach
                        @endforeach
                        @endif
                    </tbody>
                </table>


                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="font-size:80%;">

                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <strong>TOTAL HT :</strong> <span class="mtht">0</span>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <strong>TOTAL REMISE :</strong> <span class="mtremise">0</span>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <strong>TOTAL TVA :</strong> <span class="mttva">0</span>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <strong>TOTAL COMMANDE :</strong> <span class="mtttc">0</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="form-group" style="float:right;margin:15px;">
    <a href="{{url('/recouv/vfacts')}}" class="btn btn-success btn-sm"><span class="dark bolder"><i class="glyphicon glyphicon-list"></i> Liste des factures</span></a>

    <button type="submit" value="Create" class="btn btn-primary btn-sm bolder">
        <i class="glyphicon glyphicon-plus"></i> Créer
    </button>
</div>
{!! Form::close() !!}


<script type="text/javascript">
    $(document).ready(function() {

        localStorage.setItem("myclass", 'vfact');
        localStorage.setItem("father", 'recouv');

        function toNumberFormat(value) {
            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
        }


        CalculeSumChamps("TableOfData");


        $(document).on('keyup', 'input[name="Qte[]"]', function() {
            var _this = $(this);
            var min = parseInt(_this.attr('min')); // if min attribute is not defined, 1 is default
            var max = parseInt(_this.attr('max')); // if max attribute is not defined, 100 is default
            var val = parseInt(_this.val()) || (min -
                1
            ); // if input char is not a number the value will be (min - 1) so first condition will be true
            if (val < min)
                _this.val(min);
            if (val > max)
                _this.val(max);
        });

        $('.datepicker').datepicker({
            autoclose: true,
            format: 'dd/mm/yyyy',
            todayHighlight: true
        })

        $(document).on('click', '.remove_this', function() {
            var DivName = $(this).attr("name");
            $("." + DivName).remove();
            CalculeSumChamps("TableOfData");
            return false;
        });



        function CalculeSumChamps(tableID) {
            var montantht = 0;
            var montantremise = 0;
            var montanttva = 0;
            var montantttc = 0;
            var qte = 0;
            $("#" + tableID + " tbody#DetailsUnites tr").each(function() {
                // alert($(this).html());
                var Qte = parseFloat(($(this).find("td").eq(2).find("input").val()).replace(/ /g, ''));
                var Prix = parseFloat(($(this).find("td").eq(3).html()).replace(/ /g, ''));
                var remise = parseFloat(($(this).find("td").eq(4).html()).replace(/ /g, ''));
                var tva = parseFloat(($(this).find("td").eq(5).html()).replace(/ /g, ''));


                var mtht = parseFloat(Qte * Prix);
                var mtremise = Math.round(parseFloat((mtht * remise) / 100));
                var mttva = Math.round(parseFloat(((mtht * tva) / 100)));

                qte += parseFloat(Qte);
                montantht += mtht;
                montantremise += mtremise;
                montanttva += mttva;
                montantttc += parseFloat(parseFloat(mtht) + parseFloat(mttva) - parseFloat(mtremise));
                $(this).find("td").eq(6).html(toNumberFormat(parseFloat(parseFloat(mtht) + parseFloat(mttva) - parseFloat(mtremise))));
            });

            $(".mtht").html(toNumberFormat(montantht));
            $(".mtremise").html(toNumberFormat(montantremise));
            $(".mttva").html(toNumberFormat(montanttva));
            $(".mtttc").html(toNumberFormat(montantttc));

            $("#TotalQte").val(qte);
            $("#TotalRemise").val(montantremise);
            $("#TotalTva").val(montanttva);
            $("#TotalMontantTTC").val(montantttc);
            $("#TotalMontantHT").val(montantht);

        }


        // $('input[name="Qte[]"]').on('change', function(e) {
        $(document).on("change", 'input[name="Qte[]"]', function() {
            if ($(this).val() == "") {
                $(this).val(0);
            }

            var id = $(this).attr("list");
            Qte = parseFloat(($("#TableOfData tbody tr." + id).find("td").eq(2).find("input").val())
                .replace(/ /g, ''));
            Prix = parseFloat(($("#TableOfData tbody tr." + id).find("td").eq(3).html())
                .replace(/ /g, ''));
            remise = parseFloat(($("#TableOfData tbody tr." + id).find("td").eq(4).html())
                .replace(/ /g, ''));
            tva = parseFloat(($("#TableOfData tbody tr." + id).find("td").eq(5).html())
                .replace(/ /g, ''));

            MontantHT = Qte * Prix;
            Remise = Math.round((MontantHT * remise / 100).toFixed(0));
            TVA = Math.round((MontantHT * tva) / 100);
            MontantTTC = parseFloat(MontantHT) + parseFloat(TVA) - parseFloat(Remise);
            $("#TableOfData tbody tr." + id).find("td").eq(6).html(toNumberFormat(MontantTTC));

            CalculeSumChamps("TableOfData");
        });



        $('#LivraisonId').chosen();
        $("#ReceptionId_chosen").css("width", "100%");


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });




        $(document).on('change', '#LivraisonId', function() {
            var LivraisonId = $("#LivraisonId").val();
            if (LivraisonId != "") {
                $.ajax({
                    url: "{{url('vente/vfacts/getDetailsfact')}}",
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        id: LivraisonId
                    },
                    success: function(data) {
                        $('.detailsfact').html(data.htmlDetailsRecep);
                        $('#DetailsUnites').empty();
                        // $("#TableOfData tbody").append(data.htmlTable);
                        $('#DetailsUnites').append(data.htmlTable);
                    },
                });

            }
        });
    });
</script>
@endsection