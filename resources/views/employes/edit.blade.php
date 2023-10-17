@extends('layouts.master')
@section('content')
<div class="col-sm-12 col-xs-12 col-md-2 col-lg-2">
    @include('layouts.configmenu')
</div>
<div class="col-sm-12 col-xs-12 col-md-10 col-lg-10">
    <div class="EnteteContent">
        <div class="row" >

            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 styleEntete">
                <i class="icon-android-contacts bigger-130"></i> Gestion des employés
            </div>

            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 text-right styleAction">
                Modification d'un employé
            </div>
        </div>
        <hr  class="hrEntete">
        <!-- <p><button class="btn btn-sm btn-primary"><span class=" glyphicon glyphicon-plus"></span> Nouveau</button></p> -->

        <!-- <a class="btn btn-success" href="javascript:void(0)" id="createNewProduct"> Create New Product</a> -->
        <div class="row">
            {!! Form::model($employe, ['method' => 'PATCH','route' => ['employes.update', $employe->id]]) !!}
            <input type="text" name="id" id="id" value="{{$employe->id}}" hidden>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding:0 25px 0 25px;">

                <div class="form-group row">
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Code</strong>
                        <input class="form-control" name="Code"  value="{{old('Code')}}"  type="text" value="{{$employe->Code}}">
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Nom</strong>
                        <input class="form-control" name="Nom"  value="{{old('Nom')}}"  type="text" value="{{$employe->Nom}}" required>
                        @if ($errors->has('Nom'))
                        <span class="red" style="font-weight:bold;">{{ $errors->first('Nom') }}</span>
                        @endif
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Sexe</strong>
                        <div>
                            <select name="Sexe" id="Sexe">
                                <option value="">Séléctionner un sexe</option>
                                @foreach($sexes as $sexe)
                                <option value="{{$sexe->value}}"  {{ ($sexe->value==$employe->Sexe) ? 'selected' : ''}}>{{$sexe->text}}</option>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->has('Sexe'))
                        <span class="red" style="font-weight:bold;">{{ $errors->first('Sexe') }}</span>
                        @endif
                    </div>

                </div>

                <div class="form-group row">

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Adresse Electronique</strong>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-envelope-o bigger-110"></i>
                            </span>
                            <input class="form-control" name="Email" type="text" value="{{$employe->Email}}" required>
                        </div>
                        @if ($errors->has('Email'))
                        <span class="red" style="font-weight:bold;">{{ $errors->first('Email') }}</span>
                        @endif
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Adresse</strong>
                        <input class="form-control" name="Adresse" value="{{old('Adresse'}}" type="text" value="{{$employe->Adresse}}">
                        @if ($errors->has('Adresse'))
                        <span class="red" style="font-weight:bold;">{{ $errors->first('Adresse') }}</span>
                        @endif
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Poste</strong>
                        <div>
                            <select name="PosteId" id="PosteId">
                                <option value="">Séléctionner un poste</option>
                                @foreach($postes as $poste)
                                <option value="{{$poste->id}}" {{ ($poste->id==$employe->PosteId) ? 'selected' : ''}}>{{$poste->Libelle}}</option>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->has('PosteId'))
                        <span class="red" style="font-weight:bold;">{{ $errors->first('PosteId') }}</span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Téléphone</strong>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="ace-icon fa fa-phone"></i>
                            </span>
                            <input class="form-control" name="Telephone" value="{{old('Telephone'}}" type="text" value="{{$employe->Telephone}}"
                                required>
                        </div>
                        @if ($errors->has('Telephone'))
                        <span class="red" style="font-weight:bold;">{{ $errors->first('Telephone') }}</span>
                        @endif
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Date Naissance</strong>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-calendar bigger-110"></i>
                            </span>
                            <input class="form-control datepicker" name="DateNaissance" value="{{ \Carbon\Carbon::parse($employe->DateNaissance)->format('d/m/Y')}}"
                                type="text" required>
                        </div>
                        @if ($errors->has('DateNaissance'))
                        <span class="red" style="font-weight:bold;">{{ $errors->first('DateNaissance') }}</span>
                        @endif
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Fax</strong>
                        <input class="form-control" name="Fax" value="{{old('Fax'}}" type="text" value="{{$employe->Fax}}">
                        @if ($errors->has('Fax'))
                        <span class="red" style="font-weight:bold;">{{ $errors->first('Fax') }}</span>
                        @endif
                    </div>

                </div>

                <div class="form-group row">
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Pays</strong>
                        <input class="form-control" name="Pays" value="{{old('Pays'}}" type="text" value="{{$employe->Pays}}">
                        @if ($errors->has('Pays'))
                        <span class="red" style="font-weight:bold;">{{ $errors->first('Pays') }}</span>
                        @endif
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Ville</strong>
                        <input class="form-control" name="Ville" value="{{old('Ville'}}" type="text"  value="{{$employe->Ville}}">
                        @if ($errors->has('Ville'))
                        <span class="red" style="font-weight:bold;">{{ $errors->first('Ville') }}</span>
                        @endif
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Code Postal</strong>
                        <input class="form-control" name="CodePostal" value="{{old('CodePostal'}}" type="text" value="{{$employe->CodePostal}}">
                        @if ($errors->has('CodePostal'))
                        <span class="red" style="font-weight:bold;">{{ $errors->first('CodePostal') }}</span>
                        @endif
                    </div>

                </div>

                <div class="form-group row">
                   

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                       
                        <label><input name="Status" type="checkbox" {{ $employe->Status ? 'checked' : '' }}>
                        <strong> Actif</strong></label>
                    </div>

                </div>

                <div class="form-group " style="float:right;">
                    <a href="{{url('/config/employes')}}" class="btn btn-success btn-sm"><span class="dark bolder"><i
                                class="glyphicon glyphicon-list"></i> Liste des employés</span></a>

                    <button type="submit" value="Create" class="btn btn-warning btn-sm bolder">
                        <i class="glyphicon glyphicon-edit"></i> Modifier
                    </button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>

    <script>
    $(document).ready(function() {

        $('.datepicker').datepicker({
            autoclose: true,
            format: 'dd/mm/yyyy',
            todayHighlight: true
        })

        $('#PosteId').chosen();
        $("#PosteId_chosen").css("width", "100%");

        $('#Sexe').chosen();
        $("#Sexe_chosen").css("width", "100%");

    });
    </script>
    @endsection