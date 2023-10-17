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

{!! Form::open(array('route' => 'convertstocks.store','method'=>'POST')) !!}

<div class="EnteteContent">
    <div class="row">

        <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 styleEntete">
            <i class=" menu-icon icon-product-hunt   bigger-130"></i>  Conversion stock
        </div>

        <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 text-right styleAction">
            Création d'une  Conversion stock
        </div>
    </div>
    <hr class="hrEntete">
    <div class="form-group row">
        <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 table-responsive" style="overflow-y: scroll;max-height:350px;font-weight:bold;font-size:80%;">
            Produits:
            <!-- <input type="text" id="myInput" class="form-control FilterSearch"> -->

            <ul id="navMenus">
                <li>
                    <div><input class="FilterSearch form-control" type="text" /></div>
                </li>
                @foreach($produits as $produit)
                @if(count($produit->unites)>1)
                <li id="{{$produit->id}}" class="SelectProduit selector">{{$produit->Libelle}}</li>
                @endif
                @endforeach
            </ul>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="overflow-y: scroll;height:300px;">
                    <table class="table table-bordered " id="TableOfData"  style="font-size:90%;">
                        <tr style="background:#006fcf;color:#FFF;font-weight:bold;">

                            <td hidden>Unité</td>
                            <td>Produit</td>
                            <td>Unité</td>
                            <td>De</td>
                            <td>En</td>
                            <td class="text-right innerTd">Qté</td>
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
    <a href="{{url('/stock/convertstocks')}}" class="btn btn-success btn-sm"><span class="dark bolder"><i class="glyphicon glyphicon-list"></i> Liste commandes fournisseur</span></a>

    <button type="submit" value="Create" class="btn btn-primary btn-sm bolder">
        <i class="glyphicon glyphicon-plus"></i> Créer
    </button>
</div>
{!! Form::close() !!}


<script type="text/javascript">
    $(document).ready(function() {

        localStorage.setItem("myclass", 'convertstock');
        localStorage.setItem("father", 'stock');

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

        $('#FromUniteId').chosen();
        $("#FromUniteId_chosen").css("width", "100%");

        
        $('#ToUniteId').chosen();
        $("#ToUniteId_chosen").css("width", "100%");

       
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        $("#navMenus").on('click', 'li.selector', function() {
            var produitId = $(this).attr('id');
            $("#navMenus li.active").removeClass("active");
            // adding classname 'active' to current click li 
            $(this).addClass("active");
            if (produitId != "") {
                // $('#UniteId').empty();
                // $("#UniteId").append("<option value=''>Séléctionnez une unité</option>");
                $.ajax({
                    url: "{{url('stock/convertstocks/getUnites')}}",
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        id: produitId
                    },
                    success: function(data) {
// alert(data);
                        $("#DetailsUnites").append(data.html);
                        // let UniteId='1/2/1';
                        // setLineProduit(produitId, 1/5/4);
                        // $.each(data.split('|'), function(index, value) {
                        //     if (value != "") {
                        //         $("#UniteId").append("<option value='" + value.split(
                        //                 '~')[0] + "' " + value.split('~')[2] + ">" +
                        //             value.split('~')[1] + "</option>");
                        //     }
                        // });
                        // $('#UniteId').trigger("chosen:updated");
                        // let UniteId = $('#UniteId').val();
                      
                    }
                });
            }


        });

    });
</script>
@endsection