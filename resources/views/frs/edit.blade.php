@extends('layouts.master')
@section('content')
{!! Form::model($frs, ['method' => 'PATCH','route' => ['frs.update', $frs->id]]) !!}
    <div class="EnteteContent">
        <div class="row" >

            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 styleEntete">
                <i class="menu-icon icon-group bigger-130"></i> Gestion des fournisseurs
            </div>

            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 text-right styleAction">
                Modification d'un fournisseur
            </div>
        </div>
        <hr  class="hrEntete">
        <!-- <p><button class="btn btn-sm btn-primary"><span class=" glyphicon glyphicon-plus"></span> Nouveau</button></p> -->

        <!-- <a class="btn btn-success" href="javascript:void(0)" id="createNewProduct"> Create New Product</a> -->
        <div class="row">
            <input type="text" name="id" id="id" value="{{$frs->id}}" hidden>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding:0 25px 0 25px;">
                <div class="form-group row">
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Code</strong>
                        <input class="form-control" name="Code"  value="{{old('Code',$frs->Code)}}"  type="text">
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Nom</strong>
                        <input class="form-control" name="Nom"  type="text" value="{{ old('Nom',$frs->Nom) }}" required>
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
                            <input class="form-control" name="Email" type="text" value="{{old('Email',$frs->Email)}}" required>
                        </div>
                        @if ($errors->has('Email'))
                        <span class="red" style="font-weight:bold;">{{ $errors->first('Email') }}</span>
                        @endif
                    </div>

                </div>

                <div class="form-group row">

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Adresse</strong>
                        <input class="form-control" name="Adresse" value="{{old('Adresse',$frs->Adresse)}}" type="text">
                        @if ($errors->has('Adresse'))
                        <span class="red" style="font-weight:bold;">{{ $errors->first('Adresse') }}</span>
                        @endif
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Téléphone</strong>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="ace-icon fa fa-phone"></i>
                            </span>
                            <input class="form-control" name="Telephone" value="{{old('Telephone',$frs->Telephone)}}" type="text" required>
                        </div>
                        @if ($errors->has('Telephone'))
                        <span class="red" style="font-weight:bold;">{{ $errors->first('Telephone') }}</span>
                        @endif
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Fax</strong>
                        <input class="form-control" name="Fax" value="{{old('Fax',$frs->Fax)}}" type="text">
                        @if ($errors->has('Fax'))
                        <span class="red" style="font-weight:bold;">{{ $errors->first('Fax') }}</span>
                        @endif
                    </div>

                </div>

                <div class="form-group row">
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Pays</strong>
                        <input class="form-control" name="Pays" value="{{old('Pays',$frs->Pays)}}" type="text">
                        @if ($errors->has('Pays'))
                        <span class="red" style="font-weight:bold;">{{ $errors->first('Pays') }}</span>
                        @endif
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Ville</strong>
                        <input class="form-control" name="Ville" value="{{old('Ville',$frs->Ville)}}" type="text">
                        @if ($errors->has('Ville'))
                        <span class="red" style="font-weight:bold;">{{ $errors->first('Ville') }}</span>
                        @endif
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Code Postal</strong>
                        <input class="form-control" name="CodePostal" value="{{old('CodePostal',$frs->CodePostal)}}" type="text">
                        @if ($errors->has('CodePostal'))
                        <span class="red" style="font-weight:bold;">{{ $errors->first('CodePostal') }}</span>
                        @endif
                    </div>

                </div>

                <div class="form-group row">


                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <!-- <strong></strong> -->
                        <label><input name="Status" type="checkbox" value="on"    @if((!old() && $frs->Status) || old('Status') == 'on') checked="checked" @endif>
                            <strong> Actif</strong></label>
                    </div>

                </div>

              
            </div>
        </div>
    </div>
    <div class="form-group " style="float:right;padding: bottom 25px;">
                    <a href="{{url('/frs')}}" class="btn btn-success btn-sm"><span class="dark bolder"><i
                                class="glyphicon glyphicon-list"></i> Liste des fournisseurs</span></a>

                                <button type="submit" value="Create" class="btn btn-warning btn-sm bolder">
                        <i class="glyphicon glyphicon-edit"></i> Modifier
                    </button>
                </div>
            {!! Form::close() !!}

    @endsection