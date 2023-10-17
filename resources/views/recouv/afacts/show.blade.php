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


<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
        <div class="EnteteContent">
            <div class="row">

                <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 styleEntete">
                    <i class=" icon-books"></i> Gestion factures
                </div>

                <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 text-right styleAction">
                    Aperçu d'une facture
                </div>
            </div>
            <hr class="hrEntete">
            <!-- <p><button class="btn btn-sm btn-primary"><span class=" glyphicon glyphicon-plus"></span> Nouveau</button></p> -->

            <!-- <a class="btn btn-success" href="javascript:void(0)" id="createNewProduct"> Create New Product</a> -->
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group row showStyle">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Réference réception: </strong> {{$afact->reception->Reference}}
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Réference: </strong> {{$afact->Reference}}
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Date facture: </strong> {{\Carbon\Carbon::parse($afact->DateFacture)->format('d/m/Y')}}
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Date échéance: </strong> {{\Carbon\Carbon::parse($afact->DateEcheance)->format('d/m/Y')}}
                        </div>
                    </div>
                </div>


            </div>
        </div>


        <div class="EnteteContent">
    <div class="form-group row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="overflow-y: scroll;max-height:200px;">
            <table class="table table-bordered" id="TableOfData" style="font-size:80%;">
                <tr style="background:#006fcf;color:#FFF;font-weight:bold;">
                    <td>Produit</td>
                    <td>Stock</td>
                    <td>Qté facturée</td>
                </tr>
                <tbody id="DetailsUnites">
                    @if($afact!='')
                    @foreach ($afact->produits as $afact_produit)
                    <tr class=''>
                        <td>{{$afact_produit->Libelle}}</td>                       
                        <td>{{number_format($afact_produit->Qte,0,',',' ') }} {{$afact_produit->unite->Nom}}(s)</td>
                       <td> {{number_format($afact_produit->pivot->Qte,0,',',' ') }} {{$afact_produit->unites->where('id',$afact_produit->pivot->UniteId)->first()->Nom}}(s)</td>
                        </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
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
            <div class="row detailsCmde">
                <table>
                    <tr>
                        <td>
                            <strong>Date commande:</strong> {{ \Carbon\Carbon::parse($afact->reception->commande->DateAchat)->format('d/m/Y')}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>Montant TTC:</strong> {{number_format($afact->reception->MontantReçu,0,',',' ') }} {{ auth()->user()->entreprise->Devise}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>Fournisseur:</strong> {{$afact->reception->commande->fournisseur->Nom}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>Status réception:</strong>
                            @php

if(($afact->reception->commande->MontantReçu/$afact->reception->commande->MontantTTC)*100 < 99.9999 && ($afact->reception->commande->MontantReçu/$afact->reception->commande->MontantTTC)*100 > 99.9)
    {
    $StatusReception=99;
    }
    else
    {
    $StatusReception=round(($afact->reception->commande->MontantReçu/$afact->reception->commande->MontantTTC)*100);
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





<div class="form-group" style="float:right;margin:15px;">
    <a href="{{url('/recouv/afact_produits')}}" class="btn btn-success btn-sm"><span class="dark bolder"><i class="glyphicon glyphicon-list"></i> Liste des factures</span></a>

</div>


<script type="text/javascript">
    $(document).ready(function() {

        localStorage.setItem("myclass", 'afact');
    localStorage.setItem("father", 'recouv');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
</script>
@endsection