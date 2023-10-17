@extends('layouts.master')
@section('content')
<div class="col-sm-12 col-xs-12 col-md-2 col-lg-2">
    @include('layouts.configmenu')
</div>
<div class="col-sm-12 col-xs-12 col-md-10 col-lg-10">
    <div class="EnteteContent">
        <div class="row">

            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 styleEntete">
                <i class="icon-android-contacts bigger-130"></i> Gestion des employés
            </div>

            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 text-right styleAction">
                Aperçu d'un employé
            </div>
        </div>
        <hr  class="hrEntete">
        <!-- <p><button class="btn btn-sm btn-primary"><span class=" glyphicon glyphicon-plus"></span> Nouveau</button></p> -->

        <!-- <a class="btn btn-success" href="javascript:void(0)" id="createNewProduct"> Create New Product</a> -->
        <div class="row ">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 showStyle">

                <div class="form-group row">
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Code: </strong> {{$employe->Code}}
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Nom:</strong> {{$employe->Nom}}
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Sexe:</strong> {{$employe->Sexe}}
                    </div>

                </div>

                <div class="form-group row">

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Adresse Electronique:</strong> {{$employe->Email}}
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Adresse:</strong> {{$employe->Adresse}}
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Poste:</strong> {{$employe->poste->Libelle}}
                    </div>
                </div>

                <div class="form-group row">

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Téléphone:</strong> {{$employe->Telephone}}
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Date Naissance:</strong> {{ \Carbon\Carbon::parse($employe->DateNaissance)->format('d/m/Y')}}
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Fax:</strong> {{$employe->Fax}}
                    </div>

                </div>

                <div class="form-group row">
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Pays:</strong> {{$employe->Pays}}
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Ville:</strong> {{$employe->Ville}}
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Code Postal:</strong> {{$employe->CodePostal}}
                    </div>

                </div>

                <div class="form-group row">
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Status:</strong>  @if ($employe->Status == 1)
											<span class="badge badge-success">Actif</span>
											@else
											<span class="badge badge-danger">Inactif</span>
											@endif
                    </div>

                </div>

                <div class="form-group " style="float:right;">
                    <a href="{{url('/config/employes')}}" class="btn btn-success btn-sm"><span class="dark bolder"><i
                                class="glyphicon glyphicon-list"></i> Liste des employés</span></a>
                </div>
            </div>
        </div>
    </div>
    @endsection