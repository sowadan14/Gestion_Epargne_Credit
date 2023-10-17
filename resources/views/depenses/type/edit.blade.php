@extends('layouts.master')
@section('content')
<div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
{!! Form::model($typedepense, ['method' => 'PATCH','route' => ['type.update', $typedepense->id]]) !!}
    <div class="EnteteContent">
        <div class="row">

            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 styleEntete">
                <i class="menu-icon icon-money bigger-130"></i> Gestion des types dépense
            </div>

            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 text-right styleAction">
                Modification d'un type dépense
            </div>
        </div>
        <hr  class="hrEntete">
        <!-- <p><button class="btn btn-sm btn-primary"><span class=" glyphicon glyphicon-plus"></span> Nouveau</button></p> -->

        <!-- <a class="btn btn-success" href="javascript:void(0)" id="createNewProduct"> Create New Product</a> -->
        <div class="row">
           
            <input type="text" name="id" id="id" value="{{$typedepense->id}}" hidden>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding:0 25px 0 25px;">
                <div class="form-group row">
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Code</strong>
                        <input class="form-control" name="Code"  value="{{old('Code',$typedepense->Code)}}"  type="text">
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong>Libellé</strong>
                        <input class="form-control" name="Libelle" value="{{old('Libelle',$typedepense->Libelle)}}" type="text" required>
                        @if ($errors->has('Libelle'))
                        <span class="red" style="font-weight:bold;">{{ $errors->first('Libelle') }}</span>
                        @endif
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <strong></strong>
                        <label style="margin-top:25px;"><input name="Status" value="on"    @if((!old() && $typedepense->Status) || old('Status') == 'on') checked="checked" @endif type="checkbox">
                            Actif</label>
                    </div>
                </div>

               
            </div>
          
        </div>
    </div>
    <div class="form-group " style="float:right;">
                    <a href="{{url('/depenses/type')}}" class="btn btn-success btn-sm"><span class="dark bolder"><i class="glyphicon glyphicon-list"></i> Liste des types dépense</span></a>

                    <button type="submit" value="Create" class="btn btn-warning btn-sm bolder">
                        <i class="glyphicon glyphicon-edit"></i> Modifier
                    </button>
                </div>
    {!! Form::close() !!}
</div>

<script type="text/javascript">
    $(document).ready(function() {

        localStorage.setItem("myclass", 'typedepense');
        localStorage.setItem("father", 'depense');
     
    });
</script>
@endsection