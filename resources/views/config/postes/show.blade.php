@extends('layouts.master')
@section('content')
<div class="col-sm-12 col-xs-12 col-md-2 col-lg-2">
    @include('layouts.configmenu')
</div>
<div class="col-sm-12 col-xs-12 col-md-10 col-lg-10">
    <div class="EnteteContent">
        <div class="row">

            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 styleEntete">
            <i class="icon-ios-chatboxes bigger-130"></i>	Gestion des postes
            </div>

            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 text-right styleAction">
                Aperçu d'un poste
            </div>
        </div>
        <hr  class="hrEntete">
        <!-- <p><button class="btn btn-sm btn-primary"><span class=" glyphicon glyphicon-plus"></span> Nouveau</button></p> -->

        <!-- <a class="btn btn-success" href="javascript:void(0)" id="createNewProduct"> Create New Product</a> -->
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 showStyle">
                <div class="form-group row">
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Code: </strong> {{$poste->Code}}
                        <!-- <input class="form-control" name="Code"  value="{{old('Code')}}"  type="text" value="{{$poste->Code}}"> -->
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <strong>Libellé: </strong> {{$poste->Libelle}}
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <strong>Status: </strong> 
                    @if ($poste->Status == 1)
											<span class="badge badge-success">Actif</span>
											@else
											<span class="badge badge-danger">Inactif</span>
											@endif
                    </div>
                    
                </div>

               
            </div>
        </div>
    </div>
    <div class="form-group " style="float:right;">
                    <a href="{{url('/config/postes')}}" class="btn btn-success btn-sm"><span class="dark bolder"><i
                                class="glyphicon glyphicon-list"></i> Liste des postes</span></a>
                </div>
</div>
@endsection