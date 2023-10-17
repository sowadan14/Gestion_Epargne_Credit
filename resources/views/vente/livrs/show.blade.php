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

{!! Form::model($livr, ['method' => 'PATCH','route' => ['livrs.update', $livr->id]]) !!}

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
        <div class="EnteteContent">
            <div class="row">

                <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 styleEntete">
                    <i class=" fa fa-truck"></i> Gestion livraisons
                </div>

                <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 text-right styleAction">
                    Aperçu d'une livraison
                </div>
            </div>
            <hr class="hrEntete">
            <!-- <p><button class="btn btn-sm btn-primary"><span class=" glyphicon glyphicon-plus"></span> Nouveau</button></p> -->

            <!-- <a class="btn btn-success" href="javascript:void(0)" id="createNewProduct"> Create New Product</a> -->
            <div class="row">
                <input type="text" name="id" id="id" value="{{$livr->id}}" hidden>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group row showStyle">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Réference commande: </strong> {{$livr->commande->Reference}}
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Réference: </strong> {{$livr->Reference}}
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Date livraison: </strong> {{\Carbon\Carbon::parse($cmde->DateLivraison)->format('d/m/Y')}}
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
                    <td>Qté livrée</td>
                </tr>
                <tbody id="DetailsUnites">
                @if($livr->produits->count()>0)
                    @foreach ($livr->produits as $livr_produit)
                    @foreach ($produits as $produit)
                    @foreach ($produit->unites as $unite)
                    @if($livr_produit->id==$produit->id && $livr_produit->pivot->UniteId==$unite->id)
                    <tr class=''>
                        <td>{{$livr_produit->Libelle}}</td>
                        <td> {{number_format($livr_produit->pivot->Qte*$unite->pivot->Coef,0,',',' ') }}  {{$livr_produit->unite->Nom}}(s)</td>
                        <td>{{number_format($livr_produit->pivot->Qte,0,',',' ') }} {{$livr_produit->unites->where('id',$livr_produit->pivot->UniteId)->first()->Nom}}(s)</td>
                    </tr>
                    @break
                    @endif
                    @endforeach
                    @break
                    @endforeach
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
                    Détails de la commande
                </div>
            </div>
            <hr class="hrEntete">
            <div class="row detailsCmde">
                <table>
                    <tr>
                        <td>
                            <strong>Date commande:</strong> {{ \Carbon\Carbon::parse($cmde->DateAchat)->format('d/m/Y')}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>Montant TTC:</strong> {{number_format($cmde->MontantTTC,0,',',' ') }} {{ auth()->user()->entreprise->Devise}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>Client:</strong> {{$cmde->client->Nom}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>Status livraison:</strong>

                            @php

                            if(($cmde->MontantLivre/$cmde->MontantTTC)*100 < 99.9999 && ($cmde->MontantLivre/$cmde->MontantTTC)*100 > 99.9)
                                {
                                $StatusReception=99;
                                }
                                else
                                {
                                $StatusReception=round(($cmde->MontantLivre/$cmde->MontantTTC)*100);
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
    <a href="{{url('/vente/livrs')}}" class="btn btn-success btn-sm"><span class="dark bolder"><i class="glyphicon glyphicon-list"></i> Liste des livraisons</span></a>

    <button type="submit" value="Create" class="btn btn-primary btn-sm bolder">
        <i class="glyphicon glyphicon-plus"></i> Créer
    </button>
</div>
{!! Form::close() !!}


<script type="text/javascript">
    $(document).ready(function() {

        localStorage.setItem("myclass", 'livr');
        localStorage.setItem("father", 'vente');


        $(document).on('keyup', 'input[name="Qte[]"]', function() {
            var _this = $(this);
            var min = parseInt(_this.attr('min')) || 0; // if min attribute is not defined, 1 is default
            var max = parseInt(_this.attr('max')) || 100; // if max attribute is not defined, 100 is default
            var val = parseInt(_this.val()) || (min - 1); // if input char is not a number the value will be (min - 1) so first condition will be true
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



        $(document).on('change', '#CommandIdhhhh', function() {
            var id = $("#CommandId").val();
            $("#CreateForm").attr('action', '/vente/livrs/Addlivrt/' + id);
            event.preventDefault();
            $("#CreateForm").submit();
        });



        $('#CommandId').chosen();
        $("#CommandId_chosen").css("width", "100%");


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });




        $(document).on('change', '#CommandId', function() {
            var AchatId = $("#CommandId").val();
            if (AchatId != "") {
                $.ajax({
                    url: "{{url('vente/livrs/getDetailsRecept')}}",
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        id: AchatId
                    },
                    success: function(data) {
                        $('.detailsCmde').html(data.htmlDetailsCmde);
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