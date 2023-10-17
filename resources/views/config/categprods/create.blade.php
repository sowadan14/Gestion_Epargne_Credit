@extends('layouts.master')
@section('content')
<div class="col-sm-12 col-xs-12 col-md-2 col-lg-2">
    @include('layouts.configmenu')
</div>
<div class="col-sm-12 col-xs-12 col-md-10 col-lg-10">
{!! Form::open(array('route' => 'categprods.store','method'=>'POST')) !!}

    <div class="EnteteContent">
        <div class="row">

            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 styleEntete">
                <i class="icon-home3   bigger-130"></i> Gestion des familles produit
            </div>

            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 text-right styleAction">
                Création d'une famille produit
            </div>
        </div>
        <hr  class="hrEntete">
        <!-- <p><button class="btn btn-sm btn-primary"><span class=" glyphicon glyphicon-plus"></span> Nouveau</button></p> -->

        <!-- <a class="btn btn-success" href="javascript:void(0)" id="createNewProduct"> Create New Product</a> -->
        <div class="row">
            <input type="text" name="poste_id" id="poste_id" hidden>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding:0 25px 0 25px;">
                <div class="form-group row">
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Code</strong>
                        <input class="form-control" name="Code"  value="{{old('Code')}}"  type="text">
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Libellé</strong>
                        <input class="form-control" name="Nom"  value="{{old('Nom')}}"  type="text">
                        @if ($errors->has('Nom'))
                        <span class="red" style="font-weight:bold;">{{ $errors->first('Nom') }}</span>
                        @endif
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong></strong>
                        <label style="margin-top:25px;"><input name="Status" type="checkbox" {{ !old() ? 'checked' : ((old() && old('Status') == "on") ? 'checked' : '') }}>
                       
                            Actif</label>
                    </div>
                </div>

               
            </div>
        </div>
    </div>
    <div class="form-group " style="float:right;">
                    <a href="{{url('/config/categprods')}}" class="btn btn-success btn-sm"><span class="dark bolder"><i class="glyphicon glyphicon-list"></i> Liste des familles produit</span></a>

                    <button type="submit" value="Create" class="btn btn-primary btn-sm bolder">
                        <i class="glyphicon glyphicon-plus"></i> Créer
                    </button>
                </div>
            {!! Form::close() !!}

</div>
@endsection