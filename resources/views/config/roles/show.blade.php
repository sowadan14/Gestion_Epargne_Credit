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

    .label-lg {
        padding: 0.008em 0.6em 0.4em;
        font-size: 13px;
        line-height: 1.1;
        height: 24px;
    }
</style>
<div class="col-sm-12 col-xs-12 col-md-2 col-lg-2">
    @include('layouts.configmenu')
</div>
<div class="col-sm-12 col-xs-12 col-md-10 col-lg-10">
    <div class="EnteteContent">

        <div class="row">

            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 styleEntete">
                <i class="icon-check-square bigger-130"></i> Gestion des rôles et permissions
            </div>

            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 text-right styleAction">
                Aperçu d'un rôle et permissions
            </div>
        </div>


        <hr class="hrEntete">

        <!-- <p><button class="btn btn-sm btn-primary"><span class=" glyphicon glyphicon-plus"></span> Nouveau</button></p> -->

        <!-- <a class="btn btn-success" href="javascript:void(0)" id="createNewProduct"> Create New Product</a> -->
        <div class="row">
            <input type="text" name="role_id" id="role_id" hidden>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 showStyle">
                <div class="form-group row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" >
                        <strong>Libellé:</strong>
                        {{ $role->name }}
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="" style="overflow-y:auto;max-height:350px;margin:15px;">
                            <br>
                            <div class="form-group row">
                                <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                    <p style="font-weight:bold;font-size:15px;margin-bottom: 0.1rem">Permissions:</p>

                                </div>
                            </div>

                            <div>
                                @if(!empty($rolePermissions))
                                @foreach($numParents as $numParent)
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                    <div class="col-xs-11 label label-lg label-info  arrowed-right" style="padding: 0.em 0.6em 0.4em;">
                                        <label style="text-align:left">{{$rolePermissions->where('NumParent',$numParent)->first()->Parent}}</label>
                                    </div>
                                    <div style="margin-bottom:10px;margin-top:28px;">
                                        @foreach($rolePermissions->where('NumParent',$numParent) as $value)

                                        <label><i class="glyphicon glyphicon-ok"></i>
                                            {{ $value->Libelle }} {!!$value->Lien!!} </label>
                                        <br />
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

               
            </div>
        
        </div>
    </div>
    <div class="form-group " style="float:right;">
                    <a href="{{url('/config/roles')}}" class="btn btn-success btn-sm"><span class="dark bolder"><i class="glyphicon glyphicon-list"></i> Liste des rôles et permissions</span></a>
                </div>
</div>
@endsection