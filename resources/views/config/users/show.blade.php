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
</style>
<div class="col-sm-12 col-xs-12 col-md-2 col-lg-2">
    @include('layouts.configmenu')
</div>
<div class="col-sm-12 col-xs-12 col-md-10 col-lg-10">
    <div class="EnteteContent">
        <div class="row">

            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 styleEntete">
                <i class="icon-android-contacts bigger-130"></i> Gestion des utilisateurs
            </div>

            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 text-right styleAction">
                Aperçu d'un utilisateur
            </div>
        </div>
        <hr class="hrEntete">
        <!-- <p><button class="btn btn-sm btn-primary"><span class=" glyphicon glyphicon-plus"></span> Nouveau</button></p> -->

        <!-- <a class="btn btn-success" href="javascript:void(0)" id="createNewProduct"> Create New Product</a> -->
        <div class="row">
            <div class="form-group showStyle">
                <div class="form-group col-xs-12 col-sm-12 col-md-8 col-lg-8 ">
                    <div class="col-xs-12 label label-lg label-info  arrowed-right"
                        style="padding: 0.em 0.6em 0.4em;margin-bottom:15px">
                        <label style="text-align:left">Paramètres de connexion</label>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <strong>Nom:</strong> {{$user->employe->Nom}}
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <strong>Adresse éléctronique: </strong> {{$user->email}}
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <strong>Status:</strong>  @if ($user->Status == 1)
											<span class="badge badge-success">Actif</span>
											@else
											<span class="badge badge-danger">Inactif</span>
											@endif
                    </div>

               

                    <div class="col-xs-12 label label-lg label-info  arrowed-right"
                        style="padding: 0.em 0.6em 0.4em;margin-top:15px;">
                        <label style="text-align:left">Rôles</label>
                    </div>
                    <div class="form-group showStyle">
                            <div style="text-align:left;margin-left:0px;">
                                @foreach($userRoles as $role)
                                <label style="font-size:110%;"><i class="glyphicon glyphicon-ok"></i>
                                    {{ $role }} </label>
                                <br />
                                @endforeach
                            </div>
                    </div>
                </div>

                <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">

                    <div class="col-xs-12 label label-lg label-info  arrowed-right"
                        style="padding: 0.em 0.6em 0.4em;margin-bottom:15px">
                        <label style="text-align:left">Photo profil</label>
                    </div>

                    <div class="form-group row text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                      
                            <img id="blah" src="{{ asset('storage/images/'.$user->ImageUser) }}" alt="User photo"
                                width="200px" height="200px" style="border-radius:100px;" />
                    </div>

                </div>
            </div>
        </div>
       
    </div>
    <div class="form-group " style="float:right;">
            <a href="{{url('/config/users')}}" class="btn btn-success btn-sm"><span class="dark bolder"><i
                        class="glyphicon glyphicon-list"></i> Liste des utilisateurs</span></a>
        </div>
</div>
@endsection