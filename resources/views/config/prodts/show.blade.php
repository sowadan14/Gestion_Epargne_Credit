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
</style>

<div class="col-sm-12 col-xs-12 col-md-2 col-lg-2">
    @include('layouts.configmenu')
</div>
<div class="col-sm-12 col-xs-12 col-md-10 col-lg-10">
    <div class="EnteteContent">
        <div class="row">

            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 styleEntete">
                <i class="menu-icon icon-product-hunt   bigger-130"></i> Gestion des produits
            </div>

            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 text-right styleAction">
                Aperçu d'un produit
            </div>
        </div>
        <hr class="hrEntete">
        <!-- <p><button class="btn btn-sm btn-primary"><span class=" glyphicon glyphicon-plus"></span> Nouveau</button></p> -->

        <!-- <a class="btn btn-success" href="javascript:void(0)" id="createNewProduct"> Create New Product</a> -->
        <div class="row showStyle">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9" style="padding:0 25px 0 25px;">
                    <div class="form-group row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <strong>Type produit: </strong> {{$produit->typeproduit->Nom}}
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <strong>Code:</strong> {{$produit->Code}}
                           
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <strong>Libellé</strong> {{$produit->Libelle}}
                            
                        </div>
                   

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <strong>Famille:</strong>{{$produit->categproduit->Nom}}
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <strong>Unité de gestion:</strong>{{$produit->unite->Nom}}

                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <strong>Code barre:</strong>{{$produit->CodeBar}}
                        </div>
                    
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <strong>CUMP:</strong>{{$produit->CUMP}}
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                            <strong>Stock sécurité:</strong>{{$produit->StockSecu}}
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <strong>Status:</strong>  @if ($produit->Status == 1)
											<span class="badge badge-success">Actif</span>
											@else
											<span class="badge badge-danger">Inactif</span>
											@endif
                    </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <p style="font-size:120%;color:#3f6ad8;font-weight:bold;"><span class="icon-cart4 bigger-150"></span> Conditionnement vente et achat</p>
                            
                            <div>
                                <table class="table table-bordered">
                                    <tr style="background:#006fcf;color:#FFF;font-weight:bold;">
                                        <td>Unité</td>
                                        <td class="text-right">Quantité</td>
                                        <td class="text-right">Prix Achat HT</td>
                                        <td class="text-right">Prix vente HT</td>
                                    </tr>
                                    <tbody id="DetailsUnites">
                                        @if($produit->unites->count()>0)
                                        @foreach ($produit->unites as $unite_product)
                                        <tr class='Unite{{$unite_product->id}}'>
                                            <td>{{$unite_product->Nom}}</td>
                                            <td  class="text-right">
                                            {{$unite_product->pivot->Qte}}
                                            </td>

                                            <td  class="text-right">
                                            {{$unite_product->pivot->PrixAchat}}
                                            </td>

                                            <td  class="text-right">
                                                {{$unite_product->pivot->PrixVente}}
                                            </td>
                                           </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3" style="padding:0 25px 0 25px;">
                    <strong>Logo produit</strong>
                    <div class="form-group row text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">

                        <label for="images" class="drop-container">
                            
                            <img id="blah" src="{{ asset('storage/images/'.$produit->Prod_logo) }}" alt="User photo"
                                width="100px" height="100px" style="border-radius:100px;" />
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group" style="float:right;margin:15px;">
                <a href="{{url('/config/prodts')}}" class="btn btn-success btn-sm"><span class="dark bolder"><i class="glyphicon glyphicon-list"></i> Liste des produits</span></a>

                          </div>
           
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {

        imgInp.onchange = evt => {
            const [file] = imgInp.files
            if (file) {
                blah.src = URL.createObjectURL(file)
            }
        }

        $(document).on('click', '.remove_this', function() {
            var DivName = $(this).attr("name");
            $("." + DivName).remove();
            return false;
        });



        $('#TypeProduitId').chosen();
        $("#TypeProduitId_chosen").css("width", "100%");

        $('#CategProduitId').chosen();
        $("#CategProduitId_chosen").css("width", "100%");

        $('#UniteId').chosen();
        $("#UniteId_chosen").css("width", "100%");

        $('#SelectUniteId').chosen();
        $("#SelectUniteId_chosen").css("width", "100%");




        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#SelectUniteId').on('change', function(e) {
            var cat_id = e.target.value;
            if ($('.Unite' + cat_id).length < 1 && cat_id != "") {
                $("#DetailsUnites").append("<tr  class='Unite" + cat_id + "'>\
                      <td>" + $('#SelectUniteId option:selected').text() + "</td>\
                      <td hidden><input class='text-right' name='Unite[]' value='" + cat_id + "'  type='number'></td>\
                      <td><input class='text-right' name='Qte[]' min='0'  type='number' required /></td>\
                     <td><input class='text-right' name='PrixVente[]'   min='0' type='number' required /></td>\
                      <td><input class='text-right' name='PrixAchat[]'  min='0' type='number' required /></td>\
                      <td style='text-align:center;'><button class='btn btn-danger btn-sm remove_this'\
                      style='width:25px;height:25px;border-radius:100px;' type='button' name='Unite" + cat_id + "'><span class='fa fa-trash'></span></button></td>\
                      </tr>");
            }


        });


        $('#UniteId').on('change', function(e) {
            $("#DetailsUnites").empty();
        });


    });
</script>
@endsection