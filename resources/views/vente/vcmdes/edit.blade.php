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
        border-top: 0px solid #ddd;
        padding-top: 1px;
        padding-bottom: 1px;
    }

    .tableRecap td:last-child {
        border-top: 0px solid #ddd;
        padding-top: 1px;
        padding-bottom: 1px;
    }

    .tableRecap>tbody>tr>td,
    .tableRecap>tbody>tr>th,
    .tableRecap>tfoot>tr>td,
    .tabtableRecaple>tfoot>tr>th,
    .tableRecap>thead>tr>td,
    .tableRecap>thead>tr>th {
        padding: 2px;
        line-height: 1.42857143;
        vertical-align: middle;
        border-top: 1px solid #ddd0;
    }


    .row {
        margin-bottom: 2px;
    }
</style>

{!! Form::model($cmde, ['method' => 'PATCH','route' => ['vcmdes.update', $cmde->id]]) !!}

<div class="EnteteContent">
    <div class="row">

        <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 styleEntete">
            <i class=" icon-cart32 bigger-130"></i> Gestion commandes client
        </div>

        <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 text-right styleAction">
            Modification d'une commande client
        </div>
    </div>
    <hr class="hrEntete">
    <!-- <p><button class="btn btn-sm btn-primary"><span class=" glyphicon glyphicon-plus"></span> Nouveau</button></p> -->

    <!-- <a class="btn btn-success" href="javascript:void(0)" id="createNewProduct"> Create New Product</a> -->
    <div class="row">
        <input type="text" name="id" id="id" value="{{$cmde->id}}" hidden>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">


            <div class="row">

                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                    <strong>Client</strong>
                    <div>
                        <select name="ClientId" id="ClientId">
                            <option value="">Séléctionner un client</option>
                            @foreach($clients as $client)
                            <option value="{{$client->id}}" {{ (old('ClientId',$cmde->ClientId)==$client->id) ? 'selected' : ''}}>
                                {{$client->Nom}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @if ($errors->has('ClientId'))
                    <span class="red" style="font-weight:bold;">{{ $errors->first('ClientId') }}</span>
                    @endif
                </div>

                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                    <strong>Mode paiement</strong>
                    <div>
                        <select name="ModePaiementId" id="ModePaiementId">
                            <option value="">Séléctionner un mode paiement</option>
                            @foreach($modepaiements as $modepaiement)
                            <option value="{{$modepaiement->id}}" {{ (old('ModePaiementId',$cmde->ModePaiementId)==$modepaiement->id) ? 'selected' : ''}}>
                                {{$modepaiement->Nom}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @if ($errors->has('ModePaiementId'))
                    <span class="red" style="font-weight:bold;">{{ $errors->first('ModePaiementId') }}</span>
                    @endif
                </div>


                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                    <strong>Compte</strong>
                    <div>
                        <select name="CompteId" id="CompteId">
                            <option value="">Séléctionner un compte</option>
                            @foreach($comptes as $compte)
                            <option value="{{$compte->id}}" {{ (old('CompteId',$cmde->CompteId)==$compte->id) ? 'selected' : ''}}>
                                {{$compte->Libelle}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @if ($errors->has('CompteId'))
                    <span class="red" style="font-weight:bold;">{{ $errors->first('CompteId') }}</span>
                    @endif
                </div>

                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                    <strong>Référence</strong>
                    <input class="form-control" name="Reference" value="{{old('Reference',$cmde->Reference)}}" type="text">

                    @if ($errors->has('Reference'))
                    <span class="red" style="font-weight:bold;">{{ $errors->first('Reference') }}</span>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                    <strong>Date commande</strong>
                    <input class="form-control datepicker text-center" name="DateVente" value="{{old('DateVente',\Carbon\Carbon::parse($cmde->DateVente)->format('d/m/Y'))}}" type="text">
                    @if ($errors->has('DateVente'))
                    <span class="red" style="font-weight:bold;">{{ $errors->first('DateVente') }}</span>
                    @endif
                </div>

                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                    <strong>Date livraison</strong>
                    <input class="form-control datepicker text-center" name="DateLivraison" value="{{old('DateLivraison',\Carbon\Carbon::parse($cmde->DateLivraison)->format('d/m/Y'))}}" type="text">
                    @if ($errors->has('DateLivraison'))
                    <span class="red" style="font-weight:bold;">{{ $errors->first('DateLivraison') }}</span>
                    @endif
                </div>
                @if(Auth()->user()->entreprise->AvoirCltManager)
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">

                    <label style="margin-top:25px;"><input name="IsAvoir" type="checkbox">
                        <strong> Mettre la monnaie en avoir</strong></label>
                </div>
                @endif
            </div>
        </div>


    </div>
</div>

<div class="EnteteContent">
    <div class="form-group row">
        <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 table-responsive" style="overflow-y: scroll;max-height:250px;font-weight:bold;font-size:80%;">
            Produits:
            <!-- <input type="text" id="myInput" class="form-control FilterSearch"> -->

            <ul id="navMenus">
                <li>
                    <div><input class="FilterSearch form-control" type="text" /></div>
                </li>
                @foreach($produits as $produit)
                <li id="{{$produit->id}}" class="SelectProduit selector">{{$produit->Libelle}}</li>
                @endforeach
            </ul>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" style="padding:5px;">
                    <div>
                        <select name="UniteId" id="UniteId">
                            <option value="">Séléctionner une unité</option>
                            <!-- @foreach($unites as $unite)
                            <option value="{{$unite->id}}" {{ (old('UniteId')==$unite->id) ? 'selected' : ''}}>
                                {{$unite->Nom}}
                            </option>
                            @endforeach -->
                        </select>
                    </div>
                    @if ($errors->has('UniteId'))
                    <span class="red" style="font-weight:bold;">{{ $errors->first('UniteId') }}</span>
                    @endif
                </div>
                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8" style="margin-top:5px;">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 bolder">
                        @if(Auth()->user()->entreprise->AvoirCltManager)
                        <input class="form-control hidden" name="MontantAvoir" id="MontantAvoir" val="0" type="text">
                        <label><input name="PaidWithAvoir" id="PaidWithAvoir" type="checkbox">
                            <strong> Payer avec avoir (<span class="avoir">0</span>)</strong></label>
                        @endif
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 bolder">Remise globale:</div>
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3"><input class="form-control text-right remiseglobale"  id="Remiseglobale" name="Remiseglobale" value="{{old('Remiseglobale',$cmde->RemiseGlobale)}}" type="number" style="min-width:100px;height:23px;background:gainsboro;border: 2px solid maroon;"></div>
                    <!-- </tr>
                        <tr> -->
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="overflow-y: scroll;max-height:180px;">
                    <table class="table table-bordered " id="TableOfData" style="font-size:90%;">
                        <tr style="background:#006fcf;color:#FFF;font-weight:bold;">

                            <td hidden>Unité</td>
                            <td>Produit</td>
                            <td>Unité</td>
                            <td class="text-right innerTd">Stock</td>
                            <td class="text-right innerTd">Qté cmdée</td>
                            <td class="text-right innerTd">Qté reçue</td>
                            <td class="text-right innerTd">Prix Vente</td>
                            <td class="text-right innerTd">Remise (%)</td>
                            <td class="text-right innerTd">TVA (%)</td>
                            <td class="text-right">Montant TTC</td>
                            <td class="text-center">Action</td>
                        </tr>
                        <tbody id="DetailsUnites">
                            @if($cmde->produits->count()>0)
                            @foreach ($cmde->produits as $cmde_produit)
                            @foreach ($produits->where('id',$cmde_produit->id)->first()->unites as $unite)
                            @if( $unite!='' && $cmde_produit->pivot->UniteId==$unite->id)
                            <tr class='PUnite{{$cmde_produit->pivot->UniteId}}{{$cmde_produit->id}}'>
                                <td>{{$cmde_produit->Libelle}}</td>
                                <td>{{$cmde_produit->unites->where('id',$cmde_produit->pivot->UniteId)->first()->Nom}}</td>
                                <td hidden><input class='text-right form-control' name='Produit[]' value='{{$cmde_produit->id}}' type='number'></td>
                                <td hidden><input class='text-right form-control' name='Unite[]' value='{{$cmde_produit->pivot->UniteId}}' type='number'></td>
                                <td style='text-align:right;'>{{number_format($cmde_produit->pivot->Qte+$unite->pivot->Qte,0,',',' ')}}</td>
                                <td><input class='text-right form-control' list='PUnite{{$cmde_produit->pivot->UniteId}}{{$cmde_produit->id}}' name='Qte[]' min='0' value="{{$cmde_produit->pivot->Qte}}" type='number' required /></td>
                                <td><input class='text-right form-control' list='PUnite{{$cmde_produit->pivot->UniteId}}{{$cmde_produit->id}}' name='QteLivres[]' min='0' value="{{$cmde_produit->pivot->QteLivre}}" type='number' required /></td>
                                <td><input class='text-right form-control' list='PUnite{{$cmde_produit->pivot->UniteId}}{{$cmde_produit->id}}' name='PrixVente[]' min='0' value='{{$cmde_produit->pivot->Prix}}' type='number' required /></td>
                                <td><input class='text-right form-control' list='PUnite{{$cmde_produit->pivot->UniteId}}{{$cmde_produit->id}}' name='Remise[]' min='0' value='{{$cmde_produit->pivot->Remise}}' type='number' required /></td>
                                <td><input class='text-right form-control' list='PUnite{{$cmde_produit->pivot->UniteId}}{{$cmde_produit->id}}' name='TVA[]' min='0' value='{{$cmde_produit->pivot->Tva}}' type='number' required /></td>
                                <td class='text-right'>{{number_format($cmde_produit->pivot->Montant,0,',',' ') }}</td>
                                <td hidden><input class='text-right form-control' name='MontantTTC[]' value='{{$cmde_produit->pivot->Montant}}' type='number'></td>
                                <td style='text-align:center;'><button class='btn btn-danger btn-sm remove_this' style='width:25px;height:25px;border-radius:100px;' type='button' name='PUnite{{$cmde_produit->pivot->UniteId}}{{$cmde_produit->id}}'><span class='fa fa-trash'></span></button></td>
                            </tr>
                            @endif
                            @endforeach
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                @if(Auth()->user()->entreprise->AvoirCltManager)

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

                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <strong>FACTURE :</strong> <span class="mtfacture">0</span>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <strong>AVOIR :</strong> <span class="mtavoir">0</span>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3" style="background-color:#cad7fa;">
                                <strong>RESTE A PAYER :</strong> <span class="resteapayer">0</span>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
                                    <strong>MT REMIS :</strong>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                                    <input class="form-control text-right" id="MontantRemis" name="MontantRemis" value="{{old('MontantRemis',$cmde->MontantPaye)}}" type="number" style="min-width:100px;height:23px;background:gainsboro;border: 2px solid maroon;">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">

                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">

                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">

                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
                                    <strong>MONNAIE :</strong>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                                    <input class="form-control text-right" id="Monnaie" name="Monnaie" readonly="readonly" value="0" type="number" style="min-width:100px;height:23px;background:gainsboro;border: 2px solid maroon;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @else
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

                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <strong>FACTURE :</strong> <span class="mtfacture">0</span>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3" style="background-color:#cad7fa;">
                                <strong>RESTE A PAYER :</strong> <span class="resteapayer">0</span>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
                                    <strong>MT REMIS :</strong>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                                    <input class="form-control text-right" id="MontantRemis" name="MontantRemis" value="{{old('MontantRemis',round($cmde->MontantPaye,0))}}" type="number" style="min-width:100px;height:23px;background:gainsboro;border: 2px solid maroon;">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
                                    <strong>MONNAIE :</strong>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                                    <input class="form-control text-right" id="Monnaie" name="Monnaie" readonly="readonly" value="0" type="number" style="min-width:100px;height:23px;background:gainsboro;border: 2px solid maroon;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

            </div>

        </div>

    </div>
</div>

<div class="form-group" style="float:right;margin:15px;">
    <a href="{{url('/vente/vcmdes')}}" class="btn btn-success btn-sm"><span class="dark bolder"><i class="glyphicon glyphicon-list"></i> Liste commandes client</span></a>

    <button type="submit" value="Create" class="btn btn-warning btn-sm bolder">
        <i class="glyphicon glyphicon-edit"></i> Modifier
    </button>
</div>
{!! Form::close() !!}


<script type="text/javascript">
    $(document).ready(function() {

        localStorage.setItem("myclass", 'ventecmde');
        localStorage.setItem("father", 'vente');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        CalculeSumChamps("TableOfData");

        getCltAvoir();

        $(document).on("input propertychange paste change", '.FilterSearch', function(e) {
            var value = $(this).val().toLowerCase();
            $(this).parents("ul").find('li:not(li:first-of-type)').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });

        });

        function toNumberFormat(value) {
            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
        }

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

        $('#ModePaiementId').chosen();
        $("#ModePaiementId_chosen").css("width", "100%");
        $('#CompteId').chosen();
        $("#CompteId_chosen").css("width", "100%");

        $('#ClientId').chosen();
        $("#ClientId_chosen").css("width", "100%");

        $('#UniteId').chosen();
        $("#UniteId_chosen").css("width", "100%");

        $("#navMenus").on('click', 'li.selector', function() {
            var produitId = $(this).attr('id');
            $("#navMenus li.active").removeClass("active");
            // adding classname 'active' to current click li 
            $(this).addClass("active");
            if (produitId != "") {
                $('#UniteId').empty();
                $("#UniteId").append("<option value=''>Séléctionnez une unité</option>");
                $.ajax({
                    url: "{{url('vente/vcmdes/getUnites')}}",
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        id: produitId
                    },
                    success: function(data) {
                        $.each(data.split('|'), function(index, value) {
                            if (value != "") {
                                $("#UniteId").append("<option value='" + value.split(
                                        '~')[0] + "' " + value.split('~')[2] + ">" +
                                    value.split('~')[1] + "</option>");
                            }
                        });
                        $('#UniteId').trigger("chosen:updated");
                        let UniteId = $('#UniteId').val();
                        setLineProduit(produitId, UniteId);
                    }
                });
            }


        });


        function setLineProduit(ProduitId, UniteId) {
            if (ProduitId != "" && UniteId != "") {
                if ($('.PUnite' + UniteId.split('/')[0] + "" + ProduitId).length < 1) {
                    $("#DetailsUnites").append("<tr  class='PUnite" + UniteId.split('/')[0] + "" + ProduitId + "'  style='background-color:aqua;'>\
                      <td>" + $("#navMenus li.active").html() + "</td>\
                      <td>" + $('#UniteId option:selected').text() + "</td>\
                      <td hidden><input class='text-right form-control' name='Produit[]' value='" + ProduitId + "'  type='number'></td>\
                      <td hidden><input class='text-right form-control' name='Unite[]' value='" + UniteId.split('/')[
                            0] + "'  type='number'></td>\
                            <td  style='text-align:right;'>"+ toNumberFormat(parseFloat(UniteId.split('/')[2])) +"</td>\
                      <td><input class='text-right form-control'  list='PUnite" + UniteId.split('/')[0] + "" +
                        ProduitId + "'  name='Qte[]' min='0' value='0' type='number' required /></td>\
                        <td><input class='text-right form-control'  list='PUnite" + UniteId.split('/')[0] + "" +
                        ProduitId + "'  name='QteLivres[]' min='0' value='0' type='number' required /></td>\
                     <td><input class='text-right form-control'  list='PUnite" + UniteId.split('/')[0] + "" +
                        ProduitId + "'  name='PrixVente[]' value='" + UniteId.split('/')[1] + "'  min='0' type='number' required /></td>\
                      <td><input class='text-right form-control' list='PUnite" + UniteId.split('/')[0] + "" +
                        ProduitId + "'  name='Remise[]'  value='{{ auth()->user()->entreprise->Remise}}'  min='0' type='number' required /></td>\
                      <td><input class='text-right form-control'  list='PUnite" + UniteId.split('/')[0] + "" +
                        ProduitId + "'  name='TVA[]'  value='{{ auth()->user()->entreprise->TVA}}'  min='0' type='number' required /></td>\
                      <td  class='text-right'>0</td>\
                      <td hidden><input class='text-right form-control' name='MontantTTC[]'  type='number'></td>\
                      <td style='text-align:center;'><button class='btn btn-danger btn-sm remove_this'\
                      style='width:25px;height:25px;border-radius:100px;' type='button' name='PUnite" + UniteId.split(
                            '/')[0] + "" + ProduitId + "'><span class='fa fa-trash'></span></button></td>\
                      </tr>");
                }
            }

        }

        function CalculeSumChamps(tableID) {
            var montantht = 0;
            var montantremise = 0;
            var montanttva = 0;
            var montantttc = 0;

            var montanthtRecu = 0;
            var montantremiseRecu = 0;
            var montanttvaRecu = 0;
            var montantttcRecu = 0;


            var qte = 0;
            $("#" + tableID + " tbody#DetailsUnites tr").each(function() {
                var Qte = parseFloat(($(this).find("td").eq(5).find("input").val()).replace(/ /g, ''));
                var Qterecu = parseFloat(($(this).find("td").eq(6).find("input").val()).replace(/ /g, ''));
                var Prix = parseFloat(($(this).find("td").eq(7).find("input").val()).replace(/ /g, ''));
                var remise = parseFloat(($(this).find("td").eq(8).find("input").val()).replace(/ /g, ''));
                var tva = parseFloat(($(this).find("td").eq(9).find("input").val()).replace(/ /g, ''));


                var mtht = parseFloat(Qte * Prix);
                var mtremise = Math.round(parseFloat((mtht * remise) / 100));
                var mttva = Math.round(parseFloat(((mtht * tva) / 100)));

                var mthtRecu = parseFloat(Qterecu * Prix);
                var mtremiseRecu = Math.round(parseFloat((mthtRecu * remise) / 100));
                var mttvaRecu = Math.round(parseFloat(((mthtRecu * tva) / 100)));


                montantht += mtht;
                montantremise += mtremise;
                montanttva += mttva;
                montantttc += parseFloat(parseFloat(mtht) + parseFloat(mttva) - parseFloat(mtremise));

                montanthtRecu += mthtRecu;
                montantremiseRecu += mtremiseRecu;
                montanttvaRecu += mttvaRecu;
                montantttcRecu += parseFloat(parseFloat(mthtRecu) + parseFloat(mttvaRecu) - parseFloat(mtremiseRecu));


                $(this).find("td").eq(10).html(toNumberFormat(parseFloat(parseFloat(mtht) + parseFloat(mttva) - parseFloat(mtremise))));
            });

            if(montantttcRecu<=0)
            {
                $("#Remiseglobale").val(0);
                $("#Remiseglobale").attr('readonly',true);
                $("input[name='IsAvoir']").attr('checked',false);
                $("input[name='PaidWithAvoir']").attr('checked',false);
                $("#MontantRemis").val(0);
                $("#MontantRemis").attr('readonly',true);
                $("#Monnaie").val(0);
            }
            else
            {
                $("#Remiseglobale").attr('readonly',false);
                $("#MontantRemis").attr('readonly',false); 
            }

            var remiseglobale = $(".remiseglobale").val();
            var montantremis = $("#MontantRemis").val();
            $(".mtht").html(toNumberFormat(montantht));
            $(".mtremise").html(toNumberFormat(montantremise + parseFloat(remiseglobale)));
            $(".mttva").html(toNumberFormat(montanttva));
            $(".mtttc").html(toNumberFormat(montantttc - parseFloat(remiseglobale)));
            $(".MontantPaye").val(montantttcRecu - parseFloat(remiseglobale));
            $(".mtfacture").html(toNumberFormat(montantttcRecu - parseFloat(remiseglobale)));

            if ($('#PaidWithAvoir').prop("checked") == true) {
                var montantavoir = $("#MontantAvoir").val();
                $(".mtavoir").html(toNumberFormat(parseFloat(montantavoir).toFixed(0)));
                var resteapayer = montantttcRecu - parseFloat(remiseglobale);
                var montantpayer = parseFloat(montantremis) + parseFloat(montantavoir);

                if (montantpayer >= parseFloat(resteapayer)) {
                    $(".resteapayer").html(0);
                    $("#Monnaie").val(parseFloat(montantpayer) - parseFloat(resteapayer))
                    // $("#MontantRemis").val(0);
                } else {
                    $(".resteapayer").html(toNumberFormat(montantttcRecu - parseFloat(remiseglobale) - parseFloat(montantpayer)));
                    // $("#MontantRemis").val(montantttc - parseFloat(remiseglobale) - parseFloat(montantpayer));
                    $("#Monnaie").val(0)
                }

            } else {
                // $("#MontantAvoir").val(0);
                $(".mtavoir").html(0);
                var resteapayer = montantttcRecu - parseFloat(remiseglobale);
                var montantpayer = parseFloat(montantremis);
                if (montantpayer >= parseFloat(resteapayer)) {
                    $(".resteapayer").html(0);
                    $("#Monnaie").val(parseFloat(montantpayer) - parseFloat(resteapayer))
                } else {
                    // $("#MontantRemis").val(montantttc - parseFloat(remiseglobale) - parseFloat(montantpayer));
                    $(".resteapayer").html(toNumberFormat(montantttcRecu - parseFloat(remiseglobale) - parseFloat(montantpayer)));
                    $("#Monnaie").val(0)
                }
            }

        }


        function CalculeSumPayerChamps(tableID) {
            var montantht = 0;
            var montantremise = 0;
            var montanttva = 0;
            var montantttc = 0;
            var qte = 0;
            $("#" + tableID + " tbody#DetailsUnites tr").each(function() {
                var Qte = parseFloat(($(this).find("td").eq(5).find("input").val()).replace(/ /g, ''));
                var Prix = parseFloat(($(this).find("td").eq(6).find("input").val()).replace(/ /g, ''));
                var remise = parseFloat(($(this).find("td").eq(7).find("input").val()).replace(/ /g, ''));
                var tva = parseFloat(($(this).find("td").eq(8).find("input").val()).replace(/ /g, ''));


                var mtht = parseFloat(Qte * Prix);
                var mtremise = Math.round(parseFloat((mtht * remise) / 100));
                var mttva = Math.round(parseFloat(((mtht * tva) / 100)));

                montantht += mtht;
                montantremise += mtremise;
                montanttva += mttva;
                montantttc += parseFloat(parseFloat(mtht) + parseFloat(mttva) - parseFloat(mtremise));
            });
            var remiseglobale = $(".remiseglobale").val();
            $(".MontantPaye").val(montantttc - parseFloat(remiseglobale));

        }


        $('#UniteId').on('change', function(e) {
            let ProduitId = $("#navMenus li.active").attr('id');
            let UniteId = $('#UniteId').val();
            setLineProduit(ProduitId, UniteId);
        });

        function getCltAvoir() {
            let ClientId = $("#ClientId").val();
            if (ClientId != "") {
                $.ajax({
                    url: "{{url('vente/vcmdes/getCltAvoir')}}",
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        id: ClientId
                    },
                    success: function(data) {
                        $("#MontantAvoir").val(data);
                        $(".avoir").html(toNumberFormat(parseFloat(data).toFixed(0)));
                    }
                });
            }
        }

        $('#ClientId').on('change', function(e) {
            if ({{Auth::user()->entreprise->AvoirCltManager}} == true) {
                getCltAvoir();
            }
        });

        $('#MontantRemis').on('change', function(e) {
            if ($(this).val() == "") {
                $(this).val(0);
            }
            CalculeSumChamps("TableOfData");
        });


        $(document).on('keyup', 'input[name="Qte[]"]', function() {
            var _this = $(this);
            var min = parseInt(_this.attr('min')); // if min attribute is not defined, 1 is default
            var max = parseInt(_this.attr('max')); // if max attribute is not defined, 100 is default
            var val = parseInt(_this.val()) || (min - 1); // if input char is not a number the value will be (min - 1) so first condition will be true
            if (val < min)
                _this.val(min);
            if (val > max)
                _this.val(max);
        });


        $('#PaidWithAvoir').on('change', function(e) {
            CalculeSumChamps("TableOfData");
        });

        // $('input[name="Qte[]"]').on('change', function(e) {
        $(document).on("change", 'input[name="Remiseglobale"]', function() {
            if ($(this).val() == "") {
                $(this).val(0);
            }

            CalculeSumChamps("TableOfData");
        });

        // $('input[name="Qte[]"]').on('change', function(e) {
        $(document).on("change", 'input[name="Qte[]"]', function() {
            if ($(this).val() == "") {
                $(this).val(0);
            }

            var id = $(this).attr("list");
            Qte = parseFloat(($("#TableOfData tbody tr." + id).find("td").eq(5).find("input").val())
                .replace(/ /g, ''));
            Prix = parseFloat(($("#TableOfData tbody tr." + id).find("td").eq(7).find("input").val())
                .replace(/ /g, ''));
            remise = parseFloat(($("#TableOfData tbody tr." + id).find("td").eq(8).find("input").val())
                .replace(/ /g, ''));
            tva = parseFloat(($("#TableOfData tbody tr." + id).find("td").eq(9).find("input").val())
                .replace(/ /g, ''));

            MontantHT = Qte * Prix;
            Remise = Math.round((MontantHT * remise / 100).toFixed(0));
            TVA = Math.round((MontantHT * tva) / 100);
            MontantTTC = parseFloat(MontantHT) + parseFloat(TVA) - parseFloat(Remise);
            $("#TableOfData tbody tr." + id).find("td").eq(11).find("input").val(MontantTTC);
            $("#TableOfData tbody tr." + id).find("td").eq(10).html(toNumberFormat(MontantTTC));

            $("#TableOfData tbody#DetailsUnites tr." + $(this).attr('list')).find("td").eq(6).find("input").val(Qte);
            CalculeSumChamps("TableOfData");
            $("#TableOfData tbody tr." + id).css('background-color', '#FFF');
        });

        // $('input[name="Qte[]"]').on('change', function(e) {
        $(document).on("change", 'input[name="Remise[]"]', function() {
            if ($(this).val() == "") {
                $(this).val(0);
            }

            var id = $(this).attr("list");
            Qte = parseFloat(($("#TableOfData tbody tr." + id).find("td").eq(5).find("input").val())
                .replace(/ /g, ''));
            Prix = parseFloat(($("#TableOfData tbody tr." + id).find("td").eq(7).find("input").val())
                .replace(/ /g, ''));
            remise = parseFloat(($("#TableOfData tbody tr." + id).find("td").eq(8).find("input").val())
                .replace(/ /g, ''));
            tva = parseFloat(($("#TableOfData tbody tr." + id).find("td").eq(9).find("input").val())
                .replace(/ /g, ''));

            MontantHT = Qte * Prix;
            Remise = Math.round((MontantHT * remise / 100).toFixed(0));
            TVA = Math.round((MontantHT * tva) / 100);
            MontantTTC = parseFloat(MontantHT) + parseFloat(TVA) - parseFloat(Remise);
            $("#TableOfData tbody tr." + id).find("td").eq(11).find("input").val(MontantTTC);
            $("#TableOfData tbody tr." + id).find("td").eq(10).html(toNumberFormat(MontantTTC));

            CalculeSumChamps("TableOfData");
            $("#TableOfData tbody tr." + id).css('background-color', '#FFF');
        });

        $(document).on("change", 'input[name="QteLivres[]"]', function() {
            if ($(this).val() == "") {
                $(this).val(0);
            }

            CalculeSumChamps("TableOfData");
            $("#TableOfData tbody tr." + id).css('background-color', '#FFF');
        });

        $(document).on("change", 'input[name="PrixVente[]"]', function() {
            if ($(this).val() == "") {
                $(this).val(0);
            }

            var id = $(this).attr("list");
            Qte = parseFloat(($("#TableOfData tbody tr." + id).find("td").eq(5).find("input").val())
                .replace(/ /g, ''));
            Prix = parseFloat(($("#TableOfData tbody tr." + id).find("td").eq(7).find("input").val())
                .replace(/ /g, ''));
            remise = parseFloat(($("#TableOfData tbody tr." + id).find("td").eq(8).find("input").val())
                .replace(/ /g, ''));
            tva = parseFloat(($("#TableOfData tbody tr." + id).find("td").eq(9).find("input").val())
                .replace(/ /g, ''));

            MontantHT = Qte * Prix;
            Remise = Math.round((MontantHT * remise / 100).toFixed(0));
            TVA = Math.round((MontantHT * tva) / 100);
            MontantTTC = parseFloat(MontantHT) + parseFloat(TVA) - parseFloat(Remise);
            $("#TableOfData tbody tr." + id).find("td").eq(11).find("input").val(MontantTTC);
            $("#TableOfData tbody tr." + id).find("td").eq(10).html(toNumberFormat(MontantTTC));
            CalculeSumChamps("TableOfData");
            $("#TableOfData tbody tr." + id).css('background-color', '#FFF');
        });

        $(document).on("change", 'input[name="TVA[]"]', function() {
            if ($(this).val() == "") {
                $(this).val(0);
            }

            var id = $(this).attr("list");
            Qte = parseFloat(($("#TableOfData tbody tr." + id).find("td").eq(5).find("input").val())
                .replace(/ /g, ''));
            Prix = parseFloat(($("#TableOfData tbody tr." + id).find("td").eq(7).find("input").val())
                .replace(/ /g, ''));
            remise = parseFloat(($("#TableOfData tbody tr." + id).find("td").eq(8).find("input").val())
                .replace(/ /g, ''));
            tva = parseFloat(($("#TableOfData tbody tr." + id).find("td").eq(9).find("input").val())
                .replace(/ /g, ''));

            MontantHT = Qte * Prix;
            Remise = Math.round((MontantHT * remise / 100).toFixed(0));
            TVA = Math.round((MontantHT * tva) / 100);
            MontantTTC = parseFloat(MontantHT) + parseFloat(TVA) - parseFloat(Remise);
            $("#TableOfData tbody tr." + id).find("td").eq(11).find("input").val(MontantTTC);
            $("#TableOfData tbody tr." + id).find("td").eq(10).html(toNumberFormat(MontantTTC));
            CalculeSumChamps("TableOfData");
            $("#TableOfData tbody tr." + id).css('background-color', '#FFF');
        });


    });
</script>
@endsection