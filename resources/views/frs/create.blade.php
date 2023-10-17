@extends('layouts.master')
@section('content')
{!! Form::open(array('route' => 'frs.store','method'=>'POST')) !!}

    <div class="EnteteContent">
        <div class="row">

            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 styleEntete">
                <i class="menu-icon icon-group bigger-130"></i> Gestion des fournisseurs
            </div>

            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 text-right styleAction">
                Création d'un fournisseur
            </div>
        </div>
        <hr class="hrEntete">
        <!-- <p><button class="btn btn-sm btn-primary"><span class=" glyphicon glyphicon-plus"></span> Nouveau</button></p> -->

        <!-- <a class="btn btn-success" href="javascript:void(0)" id="createNewProduct"> Create New Product</a> -->
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding:0 25px 0 25px;">
                <div class="form-group row">
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Code</strong>
                        {!! Form::text('Code', old('Code'), ['class' => 'form-control']) !!}
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Nom</strong>
                        <input class="form-control" name="Nom" value="{{old('Nom')}}" type="text">
                        @if ($errors->has('Nom'))
                        <span class="red" style="font-weight:bold;">{{ $errors->first('Nom') }}</span>
                        @endif
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Adresse Electronique</strong>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-envelope-o bigger-110"></i>
                            </span>
                            <input class="form-control" name="Email" type="text">
                            @if ($errors->has('Email'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('Email') }}</span>
                            @endif
                        </div>
                    </div>

                </div>

                <div class="form-group row">

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Adresse</strong>
                        <input class="form-control" name="Adresse" value="{{old('Adresse')}}" type="text">
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Téléphone</strong>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="ace-icon fa fa-phone"></i>
                            </span>
                            <input class="form-control" name="Telephone" value="{{old('Telephone')}}" type="text"
                                value="{{old('Telephone')}}">
                            <!-- {!! Form::text('Telephone', old('Telephone'), ['class' => 'form-control']) !!} -->
                        </div>
                        @if ($errors->has('Telephone'))
                        <span class="red" style="font-weight:bold;">{{ $errors->first('Telephone') }}</span>
                        @endif
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Fax</strong>
                        <input class="form-control" name="Fax" value="{{old('Fax')}}" type="text">
                        @if ($errors->has('Fax'))
                        <span class="red" style="font-weight:bold;">{{ $errors->first('Fax') }}</span>
                        @endif
                    </div>

                </div>

                <div class="form-group row">
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Pays</strong>
                        <input class="form-control" name="Pays" value="{{old('Pays')}}" type="text">
                        @if ($errors->has('Pays'))
                        <span class="red" style="font-weight:bold;">{{ $errors->first('Pays') }}</span>
                        @endif
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Ville</strong>
                        <input class="form-control" name="Ville" value="{{old('Ville')}}" type="text">
                        @if ($errors->has('Ville'))
                        <span class="red" style="font-weight:bold;">{{ $errors->first('Ville') }}</span>
                        @endif
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Code Postal</strong>
                        <input class="form-control" name="CodePostal" value="{{old('CodePostal')}}" type="text">
                        @if ($errors->has('CodePostal'))
                        <span class="red" style="font-weight:bold;">{{ $errors->first('CodePostal') }}</span>
                        @endif
                    </div>

                </div>

                <div class="form-group row">


                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <!-- <strong></strong> -->
                        <label><input name="Status" type="checkbox" {{ !old() ? 'checked' : ((old() && old('Status') == "on") ? 'checked' : '') }}>
                            <strong> Actif</strong></label>
                    </div>

                </div>

               
            </div>
            
        </div>
    </div>
    <div class="form-group " style="float:right;padding: bottom 25px;">
                    <a href="{{url('/frs')}}" class="btn btn-success btn-sm"><span class="dark bolder"><i
                                class="glyphicon glyphicon-list"></i> Liste des fournisseurs</span></a>

                    <button type="submit" value="Create" class="btn btn-primary btn-sm bolder">
                        <i class="glyphicon glyphicon-plus"></i> Créer
                    </button>
                </div>
                {!! Form::close() !!}
    @endsection