@extends('layouts.master')
@section('content')
<div class="EnteteContent">
    <div class="row">

        <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 styleEntete">
            <i class="menu-icon icon-group bigger-130"></i> Gestion des entreprises
        </div>

        <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 text-right styleAction">
            Aperçu d'une entreprise
        </div>
    </div>
    <hr class="hrEntete">
    <!-- <p><button class="btn btn-sm btn-primary"><span class=" glyphicon glyphicon-plus"></span> Nouveau</button></p> -->

    <!-- <a class="btn btn-success" href="javascript:void(0)" id="createNewProduct"> Create New Product</a> -->
    <div class="row ">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 showStyle">

            <div class="form-group row">
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <strong>Code: </strong> {{$entreprise->Code}}
                </div>

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <strong>Nom:</strong> {{$entreprise->Nom}}
                </div>

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <strong>Nom réduit:</strong> {{$entreprise->NomReduit}}
                </div>



            </div>

            <div class="form-group row">

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <strong>Adresse Electronique:</strong> {{$entreprise->Email}}
                </div>

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <strong>Adresse:</strong> {{$entreprise->Adresse}}
                </div>

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <strong>Téléphone:</strong> {{$entreprise->Telephone}}
                </div>



            </div>

            <div class="form-group row">

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <strong>Fax:</strong> {{$entreprise->Fax}}
                </div>

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <strong>Pays:</strong> {{$entreprise->Pays}}
                </div>

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <strong>Ville:</strong> {{$entreprise->Ville}}
                </div>
               
            </div>
            <div class="form-group row">

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <strong>Code Postal:</strong> {{$entreprise->CodePostal}}
                </div>

            </div>



            <div class="form-group " style="float:right;">
                <a href="{{url('/entreprises')}}" class="btn btn-success btn-sm"><span class="dark bolder"><i class="glyphicon glyphicon-list"></i> Liste des entreprises</span></a>
            </div>
        </div>
    </div>
</div>
@endsection