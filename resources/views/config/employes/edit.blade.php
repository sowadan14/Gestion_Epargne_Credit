@extends('layouts.master')
@section('content')
<div class="col-sm-12 col-xs-12 col-md-2 col-lg-2">
    @include('layouts.configmenu')
</div>
<div class="col-sm-12 col-xs-12 col-md-10 col-lg-10">
    {!! Form::model($employe, ['method' => 'PATCH','route' => ['employes.update', $employe->id]]) !!}

    <div class="EnteteContent">
        <div class="row">

            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 styleEntete">
                <i class="icon-android-contacts bigger-130"></i> Gestion des employés
            </div>

            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 text-right styleAction">
                Modification d'un employé
            </div>
        </div>
        <hr class="hrEntete">
        <!-- <p><button class="btn btn-sm btn-primary"><span class=" glyphicon glyphicon-plus"></span> Nouveau</button></p> -->

        <!-- <a class="btn btn-success" href="javascript:void(0)" id="createNewProduct"> Create New Product</a> -->
        <div class="row">
            <input type="text" name="id" id="id" value="{{$employe->id}}" hidden>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding:0 25px 0 25px;">

                <div class="form-group row">
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Code</strong>
                        <input class="form-control" name="Code" value="{{old('Code',$employe->Code)}}" type="text">
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Nom</strong>
                        <input class="form-control" name="Nom" type="text" value="{{ old('Nom',$employe->Nom) }}" required>
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
                                <option value="{{$sexe->value}}" {{ (old('Sexe',$employe->Sexe)==$sexe->value) ? 'selected' : ''}}>{{$sexe->text}}</option>
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
                        <strong>Adresse Electronique {{old('Email')}}</strong>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-envelope-o bigger-110"></i>
                            </span>
                            <input class="form-control" name="Email" type="text" value="{{old('Email',$employe->Email)}}" required>
                        </div>
                        @if ($errors->has('Email'))
                        <span class="red" style="font-weight:bold;">{{ $errors->first('Email') }}</span>
                        @endif
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Adresse</strong>
                        <input class="form-control" name="Adresse" value="{{old('Adresse',$employe->Adresse)}}" type="text">
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
                                <option value="{{$poste->id}}" {{ (old('PosteId',$employe->PosteId)==$poste->id) ? 'selected' : ''}}>{{$poste->Libelle}} </option>
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
                            <input class="form-control" name="Telephone" value="{{old('Telephone',$employe->Telephone)}}" type="text" required>
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
                            <input class="form-control datepicker" name="DateNaissance" value="{{ old('DateNaissance',\Carbon\Carbon::parse($employe->DateNaissance)->format('d/m/Y'))}}" type="text" required>
                        </div>
                        @if ($errors->has('DateNaissance'))
                        <span class="red" style="font-weight:bold;">{{ $errors->first('DateNaissance') }}</span>
                        @endif
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Fax</strong>
                        <input class="form-control" name="Fax" value="{{old('Fax',$employe->Fax)}}" type="text">
                        @if ($errors->has('Fax'))
                        <span class="red" style="font-weight:bold;">{{ $errors->first('Fax') }}</span>
                        @endif
                    </div>

                </div>

                <div class="form-group row">
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Pays</strong>
                        <input class="form-control" name="Pays" value="{{old('Pays',$employe->Pays)}}" type="text">
                        @if ($errors->has('Pays'))
                        <span class="red" style="font-weight:bold;">{{ $errors->first('Pays') }}</span>
                        @endif
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Ville</strong>
                        <input class="form-control" name="Ville" value="{{old('Ville',$employe->Ville)}}" type="text">
                        @if ($errors->has('Ville'))
                        <span class="red" style="font-weight:bold;">{{ $errors->first('Ville') }}</span>
                        @endif
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Code Postal</strong>
                        <input class="form-control" name="CodePostal" value="{{old('CodePostal',$employe->CodePostal)}}" type="text">
                        @if ($errors->has('CodePostal'))
                        <span class="red" style="font-weight:bold;">{{ $errors->first('CodePostal') }}</span>
                        @endif
                    </div>

                </div>

                <div class="form-group row">


                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">

                        <label><input name="Status" type="checkbox" value="on" @if((!old() && $employe->Status) || old('Status') == 'on') checked="checked" @endif>
                            <strong> Actif</strong></label>
                    </div>

                </div>

                
            </div>
        </div>
    </div>
    <div class="form-group " style="float:right;">
                    <a href="{{url('/config/employes')}}" class="btn btn-success btn-sm"><span class="dark bolder"><i class="glyphicon glyphicon-list"></i> Liste des employés</span></a>

                    <button type="submit" value="Create" class="btn btn-warning btn-sm bolder">
                        <i class="glyphicon glyphicon-edit"></i> Modifier
                    </button>
                </div>
            {!! Form::close() !!}

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