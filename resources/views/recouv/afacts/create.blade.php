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

{!! Form::open(array('route' => 'afacts.store','method'=>'POST')) !!}

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
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <strong>Réference réception</strong>
                                <div>
                                    <select name="ReceptionId" id="ReceptionId">
                                        <option value="">Séléctionner une réference</option>
                                        @foreach($Receps as $Recep)
                                        <option value="{{$Recep->id}}" {{ ($Recep->id==$recep->id) ? 'selected' : ''}}
                                            {{ (old('ReceptionId')==$Recep->id) ? 'selected' : ''}}>
                                            {{$Recep->Reference}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($errors->has('ReceptionId'))
                                <span class="red" style="font-weight:bold;">{{ $errors->first('ReceptionId') }}</span>
                                @endif
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <strong>Référence</strong>
                                <input class="form-control" name="Reference"
                                    value="{{old('Reference',generateFactAchat())}}" type="text">
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
                                    <input class="form-control datepicker" name="DateFacture"
                                        value="{{old('DateFacture')}}" type="text" required>
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
                                    <input class="form-control datepicker" name="DateEcheance"
                                        value="{{old('DateEcheance')}}" type="text" required>
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
                    Détails de la réception
                </div>
            </div>
            <hr class="hrEntete">
            <div class=" detailsfact">
            @if($recep !='')
                <table>

                    <tr>
                        <td>
                            <strong>Date réception:</strong>
                            {{ \Carbon\Carbon::parse($recep->DateReception)->format('d/m/Y')}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>Date commande:</strong>
                            {{ \Carbon\Carbon::parse($recep->commande->DateAchat)->format('d/m/Y')}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>Montant réception:</strong> {{number_format($recep->MontantReçu,0,',',' ') }}
                            {{ auth()->user()->entreprise->Devise}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>Fournisseur:</strong> {{$recep->commande->fournisseur->Nom}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>Status réception:</strong>
                           
                            @php

if(($recep->commande->MontantReçu/$recep->commande->MontantTTC)*100 < 99.9999 && ($recep->commande->MontantReçu/$recep->commande->MontantTTC)*100 > 99.9)
    {
    $StatusReception=99;
    }
    else
    {
    $StatusReception=round(($recep->commande->MontantReçu/$recep->commande->MontantTTC)*100);
    }
    @endphp

                            <div class="progress pos-rel" data-percent="{{$StatusReception}}%">
                                <div class="progress-bar" style="width:{{$StatusReception}}%;"></div>
                            </div>
                        </td>
                    </tr>
                </table>
                @endif
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
                        <td class="text-right innerTd">Prix Achat</td>
                        <td class="text-right innerTd">Remise (%)</td>
                        <td class="text-right innerTd">TVA (%)</td>
                        <td class="text-right">Montant TTC</td>
                        <td class="text-center">Action</td>
                    </tr>
                    <tbody id="DetailsUnites">

                        @if($recep!='')
                        @foreach ($recep->produits as $recep_produit)

                        @foreach ($recep->commande->produits->where('id',$recep_produit->id) as $cmde_produit)

                        @if($recep_produit->pivot->UniteId==$cmde_produit->pivot->UniteId)
                        <tr class='PUnite{{$recep_produit->pivot->UniteId}}{{$recep_produit->id}}'>
                            <td>{{$cmde_produit->Libelle}}</td>
                            <td>{{$cmde_produit->unites->where('id',$cmde_produit->pivot->UniteId)->first()->Nom}}</td>
                            <td><input class='text-right form-control'
                                    list='PUnite{{$cmde_produit->pivot->UniteId}}{{$cmde_produit->id}}' name='Qte[]'
                                    min='0' max="{{$recep_produit->pivot->Qte-$recep_produit->pivot->QteFacture}}" value="{{old('Qte',$recep_produit->pivot->Qte-$recep_produit->pivot->QteFacture)}}" type='number' required />
                            </td>
                            <td class='text-right'>{{number_format($cmde_produit->pivot->Prix,0,',',' ') }}</td>
                            <td class='text-right'>{{number_format($cmde_produit->pivot->Remise,0,',',' ') }}</td>
                            <td class='text-right'>{{number_format($cmde_produit->pivot->Tva,0,',',' ') }}</td>
                            <td class='text-right'>{{number_format($cmde_produit->pivot->Montant,0,',',' ') }}</td>
                            <td style='text-align:center;'><button class='btn btn-danger btn-sm remove_this'
                                    style='width:25px;height:25px;border-radius:100px;' type='button'
                                    name='PUnite{{$cmde_produit->pivot->UniteId}}{{$cmde_produit->id}}'><span
                                        class='fa fa-trash'></span></button>
                                    </td>
                                    <td hidden><input class='text-right form-control'
                                    list='PUnite{{$cmde_produit->pivot->UniteId}}{{$cmde_produit->id}}' name='Produit[]'
                                    min='0' value="{{old('Produit',$recep_produit->id)}}" type='number' />
                            </td>

                            <td hidden><input class='text-right form-control'
                                    list='PUnite{{$cmde_produit->pivot->UniteId}}{{$cmde_produit->id}}' name='Unite[]'
                                    min='0' value="{{old('Unite',$recep_produit->pivot->UniteId)}}" type='number' />
                            </td>
                        </tr>
                        @break
                        @endif
                        @endforeach
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pull-right" style="margin-top:15px;">
                <table class="table tableRecap" style="font-size:80%;text-align:right;border-top:0px solid ;">
                    <tr>
                        <td class="bolder">TOTAL HT :</td>
                        <td class=""><span class="mtht">0</span></td>
                    </tr>
                    <tr>
                        <td class="bolder">TOTAL REMISE :</td>
                        <td class=""><span class="mtremise">0</span></td>

                    </tr>

                    <tr>
                        <td class="bolder">TOTAL TVA : </td>
                        <td class=""><span class="mttva">0</span></td>
                    </tr>
                    <tr>
                        <td class="bolder">TOTAL TTC : </td>
                        <td class=""><span class="mtttc">0</span></td>
                    </tr>
                    </tr>
                </table>
            </div>


        </div>
    </div>
</div>

<div class="form-group" style="float:right;margin:15px;">
    <a href="{{url('/recouv/afacts')}}" class="btn btn-success btn-sm"><span class="dark bolder"><i
                class="glyphicon glyphicon-list"></i> Liste des factures</span></a>

    <button type="submit" value="Create" class="btn btn-primary btn-sm bolder">
        <i class="glyphicon glyphicon-plus"></i> Créer
    </button>
</div>
{!! Form::close() !!}


<script type="text/javascript">
$(document).ready(function() {

    localStorage.setItem("myclass", 'afact');
    localStorage.setItem("father", 'recouv');

    function toNumberFormat(value) {
        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    }


    CalculeSumChamps("TableOfData");


    $(document).on('keyup', 'input[name="Qte[]"]', function() {
        var _this = $(this);
        var min = parseInt(_this.attr('min')); // if min attribute is not defined, 1 is default
        var max = parseInt(_this.attr('max')) ; // if max attribute is not defined, 100 is default
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
        $(document).on("change", 'input[name="Qte[]"]', function () {
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



    $('#ReceptionId').chosen();
    $("#ReceptionId_chosen").css("width", "100%");


    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });




    $(document).on('change', '#ReceptionId', function() {
        var ReceptionId = $("#ReceptionId").val();
        if (ReceptionId != "") {
            $.ajax({
                url: "{{url('achat/afacts/getDetailsfact')}}",
                method: 'POST',
                dataType: 'json',
                data: {
                    id: ReceptionId
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