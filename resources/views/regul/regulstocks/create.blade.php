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
        margin-bottom: 5px;
    }

    input[type=checkbox] {
    margin: 0 auto;
    display: block;
}
</style>

{!! Form::open(array('route' => 'regulstocks.store','method'=>'POST')) !!}

<div class="EnteteContent">
    <div class="row">

        <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 styleEntete">
            <i class=" menu-icon icon-android-compass  bigger-130"></i> Régularisation stock
        </div>

        <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 text-right styleAction">
            Création d'une régularisation stock
        </div>
    </div>
    <hr class="hrEntete">
   
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
                   
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="overflow-y: scroll;max-height:180px;">
                    <table class="table table-bordered " id="TableOfData" style="font-size:90%;">
                        <tr style="background:#006fcf;color:#FFF;font-weight:bold;">

                            <td hidden>Unité</td>
                            <td>Produit</td>
                            <td>Unité</td>
                            <td class="text-right innerTd">Qté</td>
                            <td class="text-center innerTd">Entrée</td>
                            <td class="text-center">Action</td>
                        </tr>
                        <tbody id="DetailsUnites">

                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>
</div>


<div class="form-group" style="float:right;margin:15px;">
    <a href="{{url('/regul/regulstocks')}}" class="btn btn-success btn-sm"><span class="dark bolder"><i class="glyphicon glyphicon-list"></i> Liste des régularisation stock</span></a>

    <button type="submit" value="Create" class="btn btn-primary btn-sm bolder">
        <i class="glyphicon glyphicon-plus"></i> Créer
    </button>
</div>
{!! Form::close() !!}


<script type="text/javascript">
    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        localStorage.setItem("myclass", 'regulstock');
    localStorage.setItem("father", 'regul');

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
                    url: "{{url('regul/regulstocks/getUnites')}}",
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
                    $("#DetailsUnites").append("<tr  class='PUnite" + UniteId.split('/')[0] + "" + ProduitId + "' style='background-color:aqua;'>\
                      <td>" + $("#navMenus li.active").html() + "</td>\
                      <td>" + $('#UniteId option:selected').text() + "</td>\
                      <td hidden><input class='text-right form-control' name='Produit[]' value='" + ProduitId + "'  type='number'></td>\
                      <td hidden><input class='text-right form-control' name='Unite[]' value='" + UniteId.split('/')[
                            0] + "'  type='number'></td>\
                      <td><input class='text-right form-control'  list='PUnite" + UniteId.split('/')[0] + "" +
                        ProduitId + "'  name='Qte[]' min='0' value='0' type='number' required /></td>\
                        <td style='text-align:center;vertical-align:middle;'><input class='checkbox'  list='PUnite" + UniteId.split('/')[0] + "" +
                        ProduitId + "'  name='Entree[]'  type='checkbox' /></td>\
                          <td style='text-align:center;'><button class='btn btn-danger btn-sm remove_this'\
                      style='width:25px;height:25px;border-radius:100px;' type='button' name='PUnite" + UniteId.split(
                            '/')[0] + "" + ProduitId + "'><span class='fa fa-trash'></span></button></td>\
                      </tr>");
                }
            }

        }



        $('#UniteId').on('change', function(e) {
            let ProduitId = $("#navMenus li.active").attr('id');
            let UniteId = $('#UniteId').val();
            setLineProduit(ProduitId, UniteId);
        });


        // $(document).on('keyup', 'input[name="Qte[]"]', function() {
        //     var _this = $(this);
        //     var min = parseInt(_this.attr('min')); // if min attribute is not defined, 1 is default
        //     var max = parseInt(_this.attr('max')); // if max attribute is not defined, 100 is default
        //     var val = parseInt(_this.val()) || (min - 1); // if input char is not a number the value will be (min - 1) so first condition will be true
        //     if (val < min)
        //         _this.val(min);
        //     if (val > max)
        //         _this.val(max);
        // });

        $(document).on("change", 'input[name="Qte[]"]', function() {
            if ($(this).val() == "") {
                $(this).val(0);
            }

                     $("#TableOfData tbody tr." + id).css('background-color', '#FFF');
        });


    });
</script>
@endsection