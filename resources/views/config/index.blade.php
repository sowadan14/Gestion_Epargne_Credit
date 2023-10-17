@extends('layouts.master')
@section('content')

<div class="col-sm-12 col-xs-12 col-md-2 col-lg-2">
    @include('layouts.configmenu')
</div>
<div class="col-sm-12 col-xs-12 col-md-10 col-lg-10">
    <div class="EnteteContent">
        <div class="row">

            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 styleEntete">
                <i class="menu-icon icon-cogs2 bigger-130"></i> Configuration
            </div>

            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 text-right styleAction">
                Aperçu général
            </div>
        </div>
        <hr class="hrEntete">
        <!-- <p><button class="btn btn-sm btn-primary"><span class=" glyphicon glyphicon-plus"></span> Nouveau</button></p> -->

        <!-- <a class="btn btn-success" href="javascript:void(0)" id="createNewProduct"> Create New Product</a> -->
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 showStyle">
                <div class="form-group row col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="col-xs-11 label label-lg label-info  arrowed-right"
                            style="padding: 0.em 0.6em 0.4em;">
                            <label style="text-align:left">Infos de la société</label>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <strong>Code:</strong> {{$data->Code}}
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <strong>Nom:</strong> {{$data->Nom}}
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <strong>Nom réduit:</strong> {{$data->NomReduit}}
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <strong>Téléphone:</strong> {{$data->Telephone}}
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <strong>Adresse éléctronique:</strong> {{$data->Email}}
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <strong>Adresse:</strong> {{$data->Adresse}}
                        </div>
                    </div>


                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="col-xs-11 label label-lg label-info  arrowed-right"
                            style="padding: 0.em 0.6em 0.4em;">
                            <label style="text-align:left">Mise en forme de la société</label>
                        </div>
                        <div style="margin-bottom:10px;margin-top:28px;">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <strong>Taille: </strong> {{$data->Taille}}px
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <strong>Police: </strong> {{$data->Police}}
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <strong>Couleur entête: </strong>  <span
                                    style="background-color:{{$data->ColorEntete}};width:100px;height:15px;color:{{$data->ColorEntete}};border:1px solid #100f0f;">Header </span>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <strong>Couleur menu: </strong> <span style="background-color:{{$data->ColorSidebar}};width:100px;height:15px;color:{{$data->ColorSidebar}};border:1px solid #100f0f;">Header </span>
                        </div>

                        </div>
                    </div>

                  

                </div>

                <div class="form-group row col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="col-xs-11 label label-lg label-info  arrowed-right"
                            style="padding: 0.em 0.6em 0.4em;">
                            <label style="text-align:left">Logo de la société</label>
                        </div>
                        <div style="margin-bottom:10px;margin-top:28px; text-align:center;">
                        <img id="blah" src="{{ asset('storage/images/'.$data->LogoEntreprise) }}" alt="User photo"
                                width="300px" height="300px" style="border-radius:200px;" />

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection