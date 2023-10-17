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
</style>
<div class="EnteteContent">
    <div class="row">

        <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 styleEntete">
            <i class="menu-icon icon-product-hunt   bigger-130"></i> Gestion commande fournisseur
        </div>

        <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 text-right styleAction">
        Aperçu d'une commande fournisseur
        </div>
    </div>
    <hr class="hrEntete">
    <!-- <p><button class="btn btn-sm btn-primary"><span class=" glyphicon glyphicon-plus"></span> Nouveau</button></p> -->

    <!-- <a class="btn btn-success" href="javascript:void(0)" id="createNewProduct"> Create New Product</a> -->
    <div class="row">
        <input type="text" name="id" id="id" value="{{$cmde->id}}" hidden>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 showStyle">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding:0 25px 0 25px;">
                <div class="form-group row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <strong>Fournisseur: </strong> {{$cmde->fournisseur->Nom}}
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <strong>Réference: </strong> {{$cmde->Reference}}
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <strong>Date commande: </strong> {{\Carbon\Carbon::parse($cmde->DateCommande)->format('d/m/Y')}}
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <strong>Date réception: </strong> {{\Carbon\Carbon::parse($cmde->DateReception)->format('d/m/Y')}}
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>

<div class="EnteteContent">
    <div class="form-group row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10" style="overflow-y: scroll;max-height:250px;">
                    <table class="table table-bordered" id="TableOfData">
                        <tr style="background:#006fcf;color:#FFF;font-weight:bold;">

                            <td hidden>Unité </td>
                            <td>Produit</td>
                            <td>Unité</td>
                            <td class="text-right innerTd">Qté Cmdée</td>
                            <td class="text-right innerTd">Qté Reçue</td>
                            <td class="text-right innerTd">Prix Achat</td>
                            <td class="text-right innerTd">Remise (%)</td>
                            <td class="text-right innerTd">TVA (%)</td>
                            <td class="text-right">Montant TTC</td>
                        </tr>
                        <tbody id="DetailsUnites">
                            @if($cmde->produits->count()>0)
                            @foreach ($cmde->produits as $cmde_produit)
                            <tr class='PUnite{{$cmde_produit->pivot->UniteId}}{{$cmde_produit->id}}'>
                                <td>{{$cmde_produit->Libelle}}</td>
                                <td>{{$cmde_produit->unites->where('id',$cmde_produit->pivot->UniteId)->first()->Nom}}</td>
                                <td class='text-right'>{{number_format($cmde_produit->pivot->Qte,0,',',' ')}}</td>
                                <td class='text-right'>{{number_format($cmde_produit->pivot->QteReçu,0,',',' ')}}</td>
                                <td class='text-right'>{{number_format($cmde_produit->pivot->Prix,0,',',' ')}}</td>
                                <td class='text-right'>{{number_format($cmde_produit->pivot->Remise,0,',',' ')}}</td>
                                <td class='text-right'>{{number_format($cmde_produit->pivot->Tva,0,',',' ')}}</td>
                                <td class='text-right'>{{number_format($cmde_produit->pivot->Montant,0,',',' ') }}</td>
                                </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                    <table class="table" style="font-size:80%;">
                    @if($cmde->RemiseGlobale>0)
                    <tr>
                            <td class="bolder">REMISE GLOBALE :</td>
                            <td class="text-right"><span>{{number_format($cmde->RemiseGlobale,0,',',' ')}}</span></td>
                        </tr>
                        @endif
                        <tr>
                            <td class="bolder">TOTAL HT :</td>
                            <td class="text-right"><span class="mtht">0</span></td>
                        </tr>

                        <tr>
                            <td class="bolder">TOTAL REMISE :</td>
                            <td class="text-right"><span class="mtremise">0</span></td>
                        </tr>

                        <tr>
                            <td class="bolder">TOTAL TVA :</td>
                            <td class="text-right"><span class="mttva">0</span></td>
                        </tr>

                        <tr>
                            <td class="bolder">TOTAL TTC :</td>
                            <td class="text-right"><span class="mtttc">0</span></td>
                        </tr>
                    </table>
                </div>
            </div>
        
    </div>
</div>

<div class="form-group" style="float:right;margin:15px;">
    <a href="{{url('/achat/acmdes')}}" class="btn btn-success btn-sm"><span class="dark bolder"><i class="glyphicon glyphicon-list"></i> Liste commandes fournisseur</span></a>
@if($cmde->Status=='0')
    <a href="{{ route('acmdes.cloturer',$cmde->id) }}" class="btn btn-primary btn-sm bolder"><span class=" bolder"><i class="fa fa-check-circle-o"></i> Clôturer</span></a>
@endif
</div>



<script type="text/javascript">
    $(document).ready(function() {

        localStorage.setItem("myclass", 'achatcmde');
        localStorage.setItem("father", 'achat');

        CalculeSumChamps("TableOfData");

        function toNumberFormat(value) {
            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
        }

     



        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

    
        function CalculeSumChamps(tableID) {
            var montantht = 0;
            var montantremise = 0;
            var montanttva = 0;
            var montantttc = 0;
            var qte = 0;
            $("#" + tableID + " tbody#DetailsUnites tr").each(function() {
                // alert($(this).html());
                var Qte = parseFloat(($(this).find("td").eq(2).html()).replace(/ /g, ''));
                var Prix = parseFloat(($(this).find("td").eq(4).html()).replace(/ /g, ''));
                var remise = parseFloat(($(this).find("td").eq(5).html()).replace(/ /g, ''));
                var tva = parseFloat(($(this).find("td").eq(6).html()).replace(/ /g, ''));


                var mtht = parseFloat(Qte * Prix);
                var mtremise = parseFloat(((mtht * remise) / 100).toFixed(0));
                var mttva = parseFloat(((mtht * tva) / 100).toFixed(0));

                qte += parseFloat(Qte);
                montantht += mtht;
                montantremise += mtremise;
                montanttva += mttva;
                montantttc += parseFloat(parseFloat(mtht) + parseFloat(mttva) - parseFloat(mtremise));
            });

            $(".mtht").html(toNumberFormat(montantht));
            $(".mtremise").html(toNumberFormat(montantremise));
            $(".mttva").html(toNumberFormat(montanttva));
            $(".mtttc").html(toNumberFormat(montantttc));
        }
    });
</script>
@endsection