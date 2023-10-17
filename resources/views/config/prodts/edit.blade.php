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
    {!! Form::model($produit, ['method' => 'PATCH','route' => ['prodts.update', $produit->id],
    'enctype'=>'multipart/form-data']) !!}

    <div class="EnteteContent">
        <div class="row">

            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 styleEntete">
                <i class="menu-icon icon-product-hunt   bigger-130"></i> Gestion des produits
            </div>

            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 text-right styleAction">
                Modification d'un produit
            </div>
        </div>
        <hr class="hrEntete">
        <!-- <p><button class="btn btn-sm btn-primary"><span class=" glyphicon glyphicon-plus"></span> Nouveau</button></p> -->

        <!-- <a class="btn btn-success" href="javascript:void(0)" id="createNewProduct"> Create New Product</a> -->
        <div class="row">
            <!-- <input type="text" name="poste_id" id="poste_id" hidden> -->
            <input type="text" name="id" id="id" value="{{$produit->id}}" hidden>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9" style="padding:0 25px 0 25px;">
                    <div class="form-group row">
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                            <strong>Type produit</strong>
                            <div>
                                <select name="TypeProduitId" id="TypeProduitId">
                                    <option value="">Séléctionner un type produit</option>
                                    @foreach($typeproduits as $typeproduit)
                                    <option value="{{$typeproduit->id}}"
                                        {{ (old('TypeProduitId',$produit->TypeProduitId)==$typeproduit->id) ? 'selected' : ''}}>
                                        {{$typeproduit->Nom}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($errors->has('TypeProduitId'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('TypeProduitId') }}</span>
                            @endif

                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                            <strong>Code</strong>
                            <input class="form-control" name="Code"
                                value="{{ old('Code', isset($produit) ? $produit->Code : '') }}" type="text">
                            @if ($errors->has('Code'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('Code') }}</span>
                            @endif
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                            <strong>Libellé</strong>
                            <input class="form-control" name="Libelle"
                                value="{{ old('Libelle', isset($produit) ? $produit->Libelle : '') }}" type="text">
                            @if ($errors->has('Libelle'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('Libelle') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">

                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                            <strong>Famille</strong>
                            <div>
                                <select name="CategProduitId" id="CategProduitId">
                                    <option value="">Séléctionner une famille</option>
                                    @foreach($categproduits as $categproduit)
                                    <option value="{{$categproduit->id}}"
                                        {{ (old('CategProduitId',$produit->CategProduitId)==$categproduit->id) ? 'selected' : ''}}>
                                        {{$categproduit->Nom}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($errors->has('CategProduitId'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('CategProduitId') }}</span>
                            @endif

                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                            <strong>Unité de gestion</strong>
                            <div>
                                <select name="UniteId" id="UniteId">
                                    <option value="">Séléctionner une unité</option>
                                    @foreach($unites as $unite)
                                    <option value="{{$unite->id}}"
                                        {{ (old('UniteId',$produit->UniteId)==$unite->id) ? 'selected' : ''}}>
                                        {{$unite->Nom}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($errors->has('UniteId'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('UniteId') }}</span>
                            @endif

                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                            <strong>Code barre</strong>
                            <input class="form-control" name="CodeBar"
                                value="{{ old('CodeBar', isset($produit) ? $produit->CodeBar : '') }}" type="text">
                            @if ($errors->has('CodeBar'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('CodeBar') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                            <strong>CUMP</strong>
                            <input class="form-control  text-right" min='0' name="CUMP"
                                value="{{ old('CUMP', isset($produit) ? $produit->CUMP : '') }}" type="number">
                            @if ($errors->has('CUMP'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('CUMP') }}</span>
                            @endif
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                            <strong>Stock sécurité</strong>
                            <input class="form-control text-right" min='0' name="StockSecu"
                                value="{{ old('StockSecu', isset($produit) ? $produit->StockSecu : '') }}"
                                type="number">
                            @if ($errors->has('StockSecu'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('StockSecu') }}</span>
                            @endif
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                            <strong></strong>
                            <label style="margin-top:25px;"><input name="Status" type="checkbox" value="on" @if((!old()
                                    && $produit->Status) || old('Status') == 'on') checked="checked" @endif>

                                Actif</label>
                        </div>
                    </div>
                 
                </div>

                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3" style="padding:0 25px 0 25px;">
                    <strong>Logo produit</strong>
                    <div class="form-group row text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">

                        <label for="images" class="drop-container">
                            <input type="file" class="form-control form-control-sm" accept="image/*"
                                style="height: 50px;" id="imgInp" value="{{ old('Prod_logo')}}" name="Prod_logo"
                                onchange="preview()">

                            <img id="blah" src="{{ asset('storage/images/'.$produit->Prod_logo) }}" alt="User photo"
                                width="100px" height="100px" style="border-radius:100px;" />
                        </label>
                        @if ($errors->has('Prod_logo'))
                        <span class="red" style="font-weight:bold;">{{ $errors->first('Prod_logo') }}</span>
                        @endif
                    </div>
                </div>

               
            </div>


        </div>
    </div>

    <div class="EnteteContent">
                        <div class="form-group row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <p style="font-size:120%;color:#3f6ad8;font-weight:bold;"><span
                                        class="icon-cart4 bigger-150"></span> Conditionnement vente et achat</p>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding:5px;">
                                    <!-- <span class="col-xs-12 col-sm-12 col-md-4 col-lg-4">Séléctionnez les unités:</span> -->
                                    <!-- <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6"> -->
                                    <select name="SelectUniteId" id="SelectUniteId">
                                        <option value="">Aucune séléction</option>
                                        @foreach($unites as $unite)
                                        <option value="{{$unite->id}}"
                                            {{ (old('SelectUniteId')==$unite->id) ? 'selected' : ''}}>
                                            {{$unite->Nom}}
                                        </option>
                                        @endforeach
                                    </select>
                                    <!-- </div> -->
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                                    style="overflow-y:scroll;max-height:200px;">
                                    <table class="table table-bordered">
                                        <tr style="background:#006fcf;color:#FFF;font-weight:bold;">
                                            <td>Unité</td>
                                            <td hidden>Unité</td>
                                            <td class="text-right">Quantité</td>
                                            <td class="text-right">Prix Achat HT</td>
                                            <td class="text-right">Prix vente HT</td>
                                            <td>Action</td>
                                        </tr>
                                        <tbody id="DetailsUnites">
                                            @if($produit->unites->count()>0)
                                            @foreach ($produit->unites as $unite_product)
                                            <tr class="Unite{{$unite_product->id}}">
                                                <td>{{$unite_product->Nom}}</td>
                                                <td hidden>
                                                    <input type="number" name="Unite[]" class="form-control text-right"
                                                        value="{{ old('Unite[]',$unite_product->id)}}" />
                                                </td>
                                                <td>
                                                    <input type="text" name="Qte[]" class="form-control text-right"
                                                        value="{{ old('Qte[]',$unite_product->pivot->Coef)}}"
                                                        required />
                                                </td>

                                                <td>
                                                    <input type="number" name="PrixAchat[]"
                                                        class="form-control text-right"
                                                        value="{{ old('PrixAchat[]',$unite_product->pivot->PrixAchat)}}"
                                                        required />
                                                </td>

                                                <td>
                                                    <input type="number" name="PrixVente[]"
                                                        class="form-control text-right"
                                                        value="{{ old('PrixVente[]',$unite_product->pivot->PrixVente)}}"
                                                        required />
                                                </td>
                                                @if(count($unite_product->detailachats)==0 && count($unite_product->detailventes)==0 && count($unite_product->regulstocks)==0)
                                                <td style='text-align:center;'><button
                                                        class='btn btn-danger btn-sm remove_this'
                                                        style='width:25px;height:25px;border-radius:100px;'
                                                        type='button' name='Unite{{$unite_product->id}}'><span
                                                            class='fa fa-trash'></span></button></td>
                                                            @endif
                                            </tr>
                                            @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>

    <div class="form-group" style="float:right;margin:15px;">
        <a href="{{url('/config/prodts')}}" class="btn btn-success btn-sm"><span class="dark bolder"><i
                    class="glyphicon glyphicon-list"></i> Liste des produits</span></a>

        <button type="submit" value="Create" class="btn btn-warning btn-sm bolder">
            <i class="glyphicon glyphicon-edit"></i> Modifier
        </button>
    </div>
    {!! Form::close() !!}

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
                      <td><input class='form-control text-right' name='Qte[]' min='0'  type='text'  /></td>\
                     <td><input class='form-control text-right' name='PrixAchat[]'   min='0' type='number' required /></td>\
                      <td><input class='form-control text-right' name='PrixVente[]'  min='0' type='number' required /></td>\
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