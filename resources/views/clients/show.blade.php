@extends('layouts.master')
@section('content')
<div class="EnteteContent">
    <div class="row">

        <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 styleEntete">
            <i class="menu-icon icon-group bigger-130"></i> Gestion des clients
        </div>

        <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 text-right styleAction">
            Aperçu d'un client
        </div>
    </div>
    <hr class="hrEntete">
    <!-- <p><button class="btn btn-sm btn-primary"><span class=" glyphicon glyphicon-plus"></span> Nouveau</button></p> -->

    <!-- <a class="btn btn-success" href="javascript:void(0)" id="createNewProduct"> Create New Product</a> -->
    <div class="row ">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 showStyle">

            <div class="form-group row">
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <strong>Code: </strong> {{$client->Code}}
                </div>

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <strong>Nom:</strong> {{$client->Nom}}
                </div>

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <strong>Adresse Electronique:</strong> {{$client->Email}}
                </div>

            </div>

            <div class="form-group row">

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <strong>Adresse:</strong> {{$client->Adresse}}
                </div>

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <strong>Téléphone:</strong> {{$client->Telephone}}
                </div>

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <strong>Fax:</strong> {{$client->Fax}}
                </div>

            </div>

            <div class="form-group row">
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <strong>Pays:</strong> {{$client->Pays}}
                </div>

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <strong>Ville:</strong> {{$client->Ville}}
                </div>

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <strong>Code Postal:</strong> {{$client->CodePostal}}
                </div>

            </div>

            <div class="form-group row">
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <strong>Status:</strong> @if ($client->Status == 1)
                    <span class="badge badge-success">Actif</span>
                    @else
                    <span class="badge badge-danger">Inactif</span>
                    @endif
                </div>

            </div>

           
        </div>
    </div>
</div>
<div class="form-group " style="float:right;">
                <a href="{{url('/clients')}}" class="btn btn-success btn-sm"><span class="dark bolder"><i
                            class="glyphicon glyphicon-list"></i> Liste des clients</span></a>
            </div>
@endsection