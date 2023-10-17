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

{!! Form::open(array('route' => 'livrs.store','method'=>'POST')) !!}

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
        <div class="EnteteContent">
            <div class="row">

                <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 styleEntete">
                    <i class=" fa fa-truck"></i> Gestion livraisons
                </div>

                <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 text-right styleAction">
                    Création d'une livraison
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
                                <strong>Réference commande</strong>
                                <div>
                                    <select name="CommandId" id="CommandId">
                                        <option value="">Séléctionner une réference</option>
                                        @foreach($Cmdes as $Cmde)
                                        <option value="{{$Cmde->id}}" {{ ($Cmde->id==$cmde->id) ? 'selected' : ''}} {{ (old('CommandId')==$Cmde->id) ? 'selected' : ''}}> {{$Cmde->Reference}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($errors->has('CommandId'))
                                <span class="red" style="font-weight:bold;">{{ $errors->first('CommandId') }}</span>
                                @endif
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <strong>Référence</strong>
                                <input class="form-control" name="Reference" value="@if($cmde!='') {{old('Reference',generateLivrVente())}}  @endif" type="text">
                                @if ($errors->has('Reference'))
                                <span class="red" style="font-weight:bold;">{{ $errors->first('Reference') }}</span>
                                @endif
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <strong>Date livraison</strong>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar bigger-110"></i>
                                    </span>
                                    <input class="form-control datepicker" name="DateLivraison" value="{{old('DateLivraison')}}" type="text" required>
                                </div>
                                @if ($errors->has('DateLivraison'))
                                <span class="red" style="font-weight:bold;">{{ $errors->first('DateLivraison') }}</span>
                                @endif
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <strong>Note</strong>
                                <textarea class="form-control" name="Note" value="{{old('Note')}}" type="text"></textarea>
                                @if ($errors->has('Note'))
                                <span class="red" style="font-weight:bold;">{{ $errors->first('Note') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
        @if($cmde!='')
<div class="EnteteContent">
    <div class="form-group row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="overflow-y: scroll;max-height:200px;">
            <table class="table table-bordered" id="TableOfData" style="font-size:80%;">
                <tr style="background:#006fcf;color:#FFF;font-weight:bold;">

                    <td hidden>Unité </td>
                    <td>Produit</td>
                    <td>Stock</td>
                    <td>Qté commandée</td>
                    <td>Qté déjà livrée</td>
                    <td class="text-right innerTd">Qté à livrer</td>
                    <td class="text-center">Action</td>
                </tr>
                <tbody id="DetailsUnites">
                    @if($cmde->produits->count()>0)
                    @foreach ($cmde->produits as $cmde_produit)
                    @php
                    $Quantite= $cmde_produit->pivot->Qte - $cmde_produit->pivot->QteLivre;
                    @endphp
                    @if($Quantite!='0')
                    <tr class='PUnite{{$cmde_produit->pivot->UniteId}}{{$cmde_produit->id}}'>
                        <td>{{$cmde_produit->Libelle}}</td>
                        <td> {{number_format($cmde_produit->unites->where('id',$cmde_produit->pivot->UniteId)->first()->pivot->Qte * $cmde_produit->unites->where('id',$cmde_produit->pivot->UniteId)->first()->pivot->Coef,0,',',' ')}} {{$cmde_produit->unite->Nom}}</td>
                        <td hidden><input class='text-right form-control' name='Produit[]' value='{{$cmde_produit->id}}' type='number'></td>
                        <td hidden><input class='text-right form-control' name='Unite[]' value='{{$cmde_produit->pivot->UniteId}}' type='number'></td>
                        <td>{{number_format($cmde_produit->pivot->Qte,0,',',' ')}} {{$cmde_produit->unites->where('id',$cmde_produit->pivot->UniteId)->first()->Nom}}(s)</td>
                        <td>{{number_format($cmde_produit->pivot->QteLivre,0,',',' ')}} {{$cmde_produit->unites->where('id',$cmde_produit->pivot->UniteId)->first()->Nom}}(s)</td>
                        <td><input class='text-right form-control' list='PUnite{{$cmde_produit->pivot->UniteId}}{{$cmde_produit->id}}' name='Qte[]' min='0' max='{{$Quantite}}' value='{{$Quantite}}' type='number' required /></td>
                        <td style='text-align:center;'><button class='btn btn-danger btn-sm remove_this' style='width:25px;height:25px;border-radius:100px;' type='button' name='PUnite{{$cmde_produit->pivot->UniteId}}{{$cmde_produit->id}}'><span class='fa fa-trash'></span></button></td>
                    </tr>
                    @endif
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
    </div>
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
        <div class="EnteteContent">
            <div class="row">
                <div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
                    Détails de la commande
                </div>
            </div>
            <hr class="hrEntete">
            @if($cmde!='')
            <div class=" detailsCmde">
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
    $StatusLivraison=99;
    }
    else
    {
    $StatusLivraison=round(($cmde->MontantLivre/$cmde->MontantTTC)*100);
    }
    @endphp


                          
                            <div class="progress pos-rel" data-percent="{{$StatusLivraison}}%">
                                <div class="progress-bar" style="width:{{$StatusLivraison}}%;"></div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            @endif
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
            var min = parseInt(_this.attr('min')) || 1; // if min attribute is not defined, 1 is default
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



        $(document).on('change', '#CommandIdeeee', function() {
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
                    url: "{{url('vente/livrs/getDetailsLivr')}}",
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