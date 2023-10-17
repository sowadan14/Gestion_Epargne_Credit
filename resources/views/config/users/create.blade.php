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
{!! Form::open(array('route' => 'users.store','method'=>'POST', 'enctype'=>'multipart/form-data')) !!}

    <div class="EnteteContent">
        <div class="row">

            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 styleEntete">
                <i class="icon-android-contacts bigger-130"></i> Gestion des utilisateurs
            </div>

            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 text-right styleAction">
                Création d'un utilisateur
            </div>
        </div>
        <hr class="hrEntete">
        <!-- <p><button class="btn btn-sm btn-primary"><span class=" glyphicon glyphicon-plus"></span> Nouveau</button></p> -->

        <!-- <a class="btn btn-success" href="javascript:void(0)" id="createNewProduct"> Create New Product</a> -->
        <div class="row">
            <div class="form-group">
                <div class="form-group col-xs-12 col-sm-12 col-md-8 col-lg-8">

                    <div class="col-xs-12 label label-lg label-info  arrowed-right"
                        style="padding: 0.em 0.6em 0.4em;margin-bottom:15px">
                        <label style="text-align:left">Paramètres de connexion</label>
                    </div>

                    <div class="form-group">

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Employé</strong>
                            <div>
                                <select name="EmployeId" id="EmployeId">
                                    <option value="">Séléctionner un employé</option>
                                    @foreach($employes as $employe)
                                    <option value="{{$employe->id}}" {{ (old('EmployeId')==$employe->id) ? 'selected' :
                                        ''}}>{{$employe->Nom}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($errors->has('EmployeId'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('EmployeId') }}</span>
                            @endif
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Adresse Eléctronique</strong>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-envelope-o bigger-110"></i>
                                </span>
                                <input class="form-control" name="Email" value="{{ old('Email')}}" type="text">
                            </div>
                            @if ($errors->has('Email'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('Email') }}</span>
                            @endif
                        </div>

                    </div>

                    <div class="form-group">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Mot de passe:</strong>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-key bigger-110"></i>
                                </span>
                                <input class="form-control" name="Password" type="password"
                                    value="{{ old('Password')}}">
                            </div>
                            @if ($errors->has('Password'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('Password') }}</span>
                            @endif
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Confirmation:</strong>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-key bigger-110"></i>
                                </span>
                                <input class="form-control" name="Confirmpassword" type="password"
                                    value="{{ old('Confirmpassword')}}">
                            </div>
                            @if ($errors->has('Confirmpassword'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('Confirmpassword') }}</span>
                            @endif
                        </div>

                    </div>

                    <div class="col-xs-12 label label-lg label-info  arrowed-right"
                        style="padding: 0.em 0.6em 0.4em;margin-top:15px;">
                        <label style="text-align:left">Rôles</label>
                    </div>
                    <div class="form-group">
                        <div class="form-group">
                            <div style="color:#111;text-align:left;margin-left: 15px;">
                                @foreach($roles as $role)
                                <label>{{ Form::checkbox('Roles[]', $role, false, array('class' => 'name')) }}
                                    {{ $role }} </label>
                                <br />
                                @endforeach
                                @if ($errors->has('Roles'))
                                <span class="red" style="font-weight:bold;">{{ $errors->first('Roles') }}</span>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>

                <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">

                    <div class="col-xs-12 label label-lg label-info  arrowed-right"
                        style="padding: 0.em 0.6em 0.4em;margin-bottom:15px">
                        <label style="text-align:left">Photo profil</label>
                    </div>

                    <div class="form-group row text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <label for="images" class="drop-container">
                            <input type="file" class="form-control form-control-sm" accept="image/*" style="height: 50px;" id="imgInp"
                                value="{{ old('ImageUser')}}" name="ImageUser" onchange="preview()">

                            <img id="blah" src="" alt="Photo profil"
                                width="100px" height="100px" style="border-radius:100px;" />
                        </label>
                        @if ($errors->has('ImageUser'))
                        <span class="red" style="font-weight:bold;">{{ $errors->first('ImageUser') }}</span>
                        @endif
                    </div>

                </div>
            </div>
        </div>
       

       
    </div>

    <div class="form-group " style="float:right;padding: 25px 50px 10px 30px;">
            <a href="{{url('/config/users')}}" class="btn btn-success btn-sm"><span class="dark bolder"><i
                        class="glyphicon glyphicon-list"></i> Liste des utilisateurs</span></a>

            <button type="submit" value="Create" class="btn btn-primary btn-sm bolder">
                <i class="glyphicon glyphicon-plus"></i> Créer
            </button>
        </div>
    {!! Form::close() !!}
</div>
<script>
    $(document).ready(function () {

        imgInp.onchange = evt => {
            const [file] = imgInp.files
            if (file) {
                blah.src = URL.createObjectURL(file)
            }
        }


        $('#EmployeId').chosen();
        $("#EmployeId_chosen").css("width", "100%");

    });
</script>
@endsection