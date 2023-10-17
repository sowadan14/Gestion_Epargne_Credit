@extends('layouts.master')
@section('content')
<style tyle="css/text">
.label {
    font-weight: 900;
    font-size: 12px;
    text-align: left;
}

label {
    font-weight: 900;
}

.form-control {
    width: 100%;
    height: 30px;
}

.label-lg {
    padding: 0.2em 0.6em 0.4em;
    font-size: 13px;
    line-height: 1.1;
    height: 24px;
}
</style>
<div class="EnteteContent">
    <div class="row">

        <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 styleEntete">
            <i class="menu-icon icon-group bigger-130"></i> Gestion des entreprises
        </div>

        <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 text-right styleAction">
            Création d'un entreprise
        </div>
    </div>
    <hr class="hrEntete">
    <!-- <p><button class="btn btn-sm btn-primary"><span class=" glyphicon glyphicon-plus"></span> Nouveau</button></p> -->

    <!-- <a class="btn btn-success" href="javascript:void(0)" id="createNewProduct"> Create New Product</a> -->
    <div class="row">
        {!! Form::open(array('route' => 'entreprises.store','method'=>'POST')) !!}
        <div style="overflow-y:auto;max-height:500px;margin:15px;">
            <div class="alert alert-danger" style="display:none"></div>

            <div class="form-group row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="col-xs-11 label label-lg label-info  arrowed-right">
                        <label style="text-align:left">Infos Dirigeant:</label>
                    </div>
                    <div style="margin-bottom:10px;margin-top:28px;">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="font-weight:bold;">
                            <strong>Nom:</strong>
                            <input class="form-control" name="Nom" value="{{old('Nom')}}" type="text">
                            @if ($errors->has('Nom'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('Nom') }}</span>
                            @endif
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="font-weight:bold;">
                            <strong>Sexe:</strong>
                            <div>
                                <select name="sexe" id="sexe">
                                    <option value="">Séléctionner un sexe</option>
                                    @foreach($sexes as $sexe)
                                    <option value="{{$sexe->value}}" {{ (old('sexe')==$sexe->value) ? 'selected' : ''}}>{{$sexe->text}}</option>
                                    @endforeach
                                </select>


                                @if ($errors->has('sexe'))
                                <span class="red" style="font-weight:bold;">{{ $errors->first('sexe') }}</span>
                                @endif

                            </div>

                            <!-- <input class="form-control" name="sexe" type="text"> -->
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="font-weight:bold;">
                            <strong>Email:</strong>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-envelope-o bigger-110"></i>
                                </span>
                                <input class="form-control" name="email" type="text" value="{{old('email')}}">
                            </div>
                            @if ($errors->has('email'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('email') }}</span>
                            @endif

                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="font-weight:bold;">
                            <strong>Adresse:</strong>
                            <input class="form-control" name="adresse" type="text" value="{{old('adresse')}}">
                            @if ($errors->has('adresse'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('adresse') }}</span>
                            @endif
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="font-weight:bold;">
                            <strong>Telephone:</strong>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="ace-icon fa fa-phone"></i>
                                </span>
                                <input class="form-control" name="telephone" type="text" value="{{old('telephone')}}">
                            </div>
                            @if ($errors->has('telephone'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('telephone') }}</span>
                            @endif
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="font-weight:bold;">
                            <strong>Date naissance:</strong>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar bigger-110"></i>
                                </span>
                                <input class="form-control datepicker" name="dateNaissance" type="text" value="{{old('dateNaissance')}}">
                            </div>
                            @if ($errors->has('dateNaissance'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('dateNaissance') }}</span>
                            @endif
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="font-weight:bold;">
                            <strong>Fax:</strong>
                            <input class="form-control" name="faxDirigeant" type="text" value="{{old('faxDirigeant')}}">
                            @if ($errors->has('faxDirigeant'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('faxDirigeant') }}</span>
                            @endif
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="font-weight:bold;">
                            <strong>Pays:</strong>
                            <input class="form-control" name="paysDirigeant" type="text" value="{{old('paysDirigeant')}}">
                            @if ($errors->has('paysDirigeant'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('paysDirigeant') }}</span>
                            @endif
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="font-weight:bold;">
                            <strong>Ville:</strong>
                            <input class="form-control" name="villeDirigeant" type="text" value="{{old('villeDirigeant')}}">
                            @if ($errors->has('villeDirigeant'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('villeDirigeant') }}</span>
                            @endif
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="font-weight:bold;">
                            <strong>Code Postal:</strong>
                            <input class="form-control" name="codePostalDirigeant" type="text" value="{{old('codePostalDirigeant')}}">
                            @if ($errors->has('codePostalDirigeant'))
                            <span class="red"
                                style="font-weight:bold;">{{ $errors->first('codePostalDirigeant') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-xs-11 label label-lg label-info  arrowed-right" style="margin-top:10px;">
                        <label style="text-align:left">Infos Entreprise:</label>
                    </div>
                    <div style="margin-bottom:10px;margin-top:28px;">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="font-weight:bold;">
                            <strong>Nom:</strong>
                            <input class="form-control" name="nomEntreprise" type="text" value="{{old('nomEntreprise')}}">
                            @if ($errors->has('nomEntreprise'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('nomEntreprise') }}</span>
                            @endif
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="font-weight:bold;">
                            <strong>Nom réduit:</strong>
                            <input class="form-control" name="nomReduit" type="text" value="{{old('nomReduit')}}">
                            @if ($errors->has('nomReduit'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('nomReduit') }}</span>
                            @endif
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="font-weight:bold;">
                            <strong>Email:</strong>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-envelope-o bigger-110"></i>
                                </span>
                                <input class="form-control" name="emailEntreprise" type="text" value="{{old('emailEntreprise')}}">
                            </div>
                            @if ($errors->has('emailEntreprise'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('emailEntreprise') }}</span>
                            @endif
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="font-weight:bold;">
                            <strong>Adresse:</strong>
                            <input class="form-control" name="adresseEntreprise" type="text" value="{{old('adresseEntreprise')}}">
                            @if ($errors->has('adresseEntreprise'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('adresseEntreprise') }}</span>
                            @endif
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="font-weight:bold;">
                            <strong>Telephone:</strong>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="ace-icon fa fa-phone"></i>
                                </span>
                                <input class="form-control" name="telephoneEntreprise" type="text" value="{{old('telephoneEntreprise')}}">
                            </div>
                            @if ($errors->has('telephoneEntreprise'))
                            <span class="red"
                                style="font-weight:bold;">{{ $errors->first('telephoneEntreprise') }}</span>
                            @endif
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="font-weight:bold;">
                            <strong>Fax:</strong>
                            <input class="form-control" name="faxEntreprise" type="text" value="{{old('faxEntreprise')}}">
                            @if ($errors->has('faxEntreprise'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('faxEntreprise') }}</span>
                            @endif
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="font-weight:bold;">
                            <strong>Pays:</strong>
                            <input class="form-control" name="paysEntreprise" type="text" value="{{old('paysEntreprise')}}">
                            @if ($errors->has('paysEntreprise'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('paysEntreprise') }}</span>
                            @endif
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="font-weight:bold;">
                            <strong>Ville:</strong>
                            <input class="form-control" name="villeEntreprise" type="text" value="{{old('villeEntreprise')}}">
                            @if ($errors->has('villeEntreprise'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('villeEntreprise') }}</span>
                            @endif
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="font-weight:bold;">
                            <strong>Code Postal:</strong>
                            <input class="form-control" name="codePostalEntreprise" type="text" value="{{old('codePostalEntreprise')}}">
                            @if ($errors->has('codePostalEntreprise'))
                            <span class="red"
                                style="font-weight:bold;">{{ $errors->first('codePostalEntreprise') }}</span>
                            @endif
                        </div>

                    </div>

                    <div class="col-xs-11 label label-lg label-info  arrowed-right" style="margin-top:5px;">
                        <label style="text-align:left">Compte utilisateur:</label>
                    </div>
                    <div style="margin-bottom:10px;margin-top:28px;">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="font-weight:bold;">
                            <strong>Mot de passe:</strong>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-key bigger-110"></i>
                                </span>
                                <input class="form-control" name="password" type="password" value="{{old('password')}}">
                            </div>
                            @if ($errors->has('password'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('password') }}</span>
                            @endif

                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="font-weight:bold;">
                            <strong>Confirmation:</strong>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-key bigger-110"></i>
                                </span>
                                <input class="form-control" name="confirmpassword" type="password" value="{{old('confirmpassword')}}">
                            </div>
                            @if ($errors->has('confirmpassword'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('confirmpassword') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="width-95 label label-lg label-info  arrowed-right">
                        <label style="text-align:left">Rôle:</label>
                    </div>
                    <div style="margin-bottom:10px;margin-top:28px;">

                        <div class="form-group row">

                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="font-weight:bold;">
                                <strong>Libellé:</strong>
                                <input class="form-control" name="role" type="text" value="{{old('role')}}">
                                @if ($errors->has('role'))
                                <span class="red" style="font-weight:bold;">{{ $errors->first('role') }}</span>
                                @endif
                            </div>
                            <br>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="">
                                    <br>
                                    <div class="form-group row" style="padding-left:15px;">
                                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                            <p style="font-weight:bold;margin-bottom: 0.1rem">Permissions:
                                                <label style="color:#313cba;"><input type="checkbox"
                                                        id="checkAllUser" />
                                                    Choisir Tous</label>
                                                @if ($errors->has('permission'))
                                                <span class="red"
                                                    style="font-weight:bold;">{{ $errors->first('permission') }}</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <div>
                                        @foreach($numParents as $numParent)
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            <div class="col-xs-11 label label-lg label-success  arrowed-right">
                                                <label style="text-align:left">
                                                    {{ Form::checkbox('Parent', $numParent, false, array('class' => 'name','id'=>'Parent','role'=>$permissions->where('NumParent',$numParent)->first()->TypeParent)) }}
                                                    {{$permissions->where('NumParent',$numParent)->first()->Parent}}</label>
                                            </div>
                                            <div style="margin-bottom:10px;margin-top:28px;">
                                                @foreach($permissions->where('NumParent',$numParent) as $value)
                                                <label>{{ Form::checkbox('permission[]', $value->id, false, array('class' => 'name','class' => $value->TypeParent)) }}
                                                    {{ $value->Libelle }} {!!$value->Lien!!} </label>
                                                <br />
                                                @endforeach
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-12 col-md-6 col-sm-6">

                </div>
            </div>
        </div>
        <div class="form-group " style="float:right;padding: bottom 25px;">
            <a href="{{url('/entreprises')}}" class="btn btn-success btn-sm"><span class="dark bolder"><i
                        class="glyphicon glyphicon-list"></i> Liste des entreprises</span></a>

            <button type="submit" value="Create" class="btn btn-primary btn-sm bolder">
                <i class="glyphicon glyphicon-plus"></i> Créer
            </button>
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

    $(document).on('change', 'input:checkbox', function() {
        var id = $(this).attr('role');
        $("input:checkbox." + id).prop('checked', $(this).prop("checked"));
    });

    // $("#checkAllUser").change(function () {
    $(document).on('change', '#checkAllUser', function() {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
    });

    $('#sexe').chosen();
    $("#sexe_chosen").css("width", "100%");

});
</script>
@endsection