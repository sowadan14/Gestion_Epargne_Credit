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
{!! Form::model($role, ['method' => 'PATCH','route' => ['roles.update', $role->id]]) !!}

    <div class="EnteteContent">

        <div class="row">

            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 styleEntete">
                <i class="icon-check-square bigger-130"></i> Gestion des rôles et permissions
            </div>

            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 text-right styleAction">
                Modification d'un rôle et permissions
            </div>
        </div>


        <hr class="hrEntete">

        <!-- <p><button class="btn btn-sm btn-primary"><span class=" glyphicon glyphicon-plus"></span> Nouveau</button></p> -->

        <!-- <a class="btn btn-success" href="javascript:void(0)" id="createNewProduct"> Create New Product</a> -->
        <div class="row">
            <input type="text" name="role_id" id="role_id" hidden>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding:0 25px 0 25px;">
                <div class="form-group row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left:25px;font-weight:bold;font-size:15px;">
                        <strong>Libellé</strong>
                        {!! Form::text('Nom', $role->Nom, array('class' => 'form-control')) !!}
                        @if ($errors->has('Nom'))
                        <span class="red" style="font-weight:bold;">{{ $errors->first('Nom') }}</span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="" style="overflow-y:auto;max-height:350px;margin:15px;">
                            <br>
                            <div class="form-group row">
                                <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                    <p style="font-weight:bold;font-size:15px;margin-bottom: 0.1rem">Permissions: <label style="color:#313cba;"><input type="checkbox" id="checkAllUser" /> Choisir Tous</label></p>
                                    @if ($errors->has('permission'))
                                    <span class="red" style="font-weight:bold;">{{ $errors->first('permission') }}</span>
                                    @endif
                                </div>
                            </div>
                            @php
                            $i=1;
                            @endphp

                            <div>
                                @foreach($numParents as $numParent)
                                @if($i==1)
                                <div class="row">
                                    @endif
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                    <div class="col-xs-11 label label-lg label-info  arrowed-right">
                                        <label style="text-align:left"> {{ Form::checkbox('Parent', $numParent, false, array('class' => 'name','id'=>'Parent','role'=>$permissions->where('NumParent',$numParent)->first()->TypeParent)) }} {{$permissions->where('NumParent',$numParent)->first()->Parent}}</label>
                                    </div>
                                    <div style="margin-bottom:10px;margin-top:28px;">
                                        @foreach($permissions->where('NumParent',$numParent) as $value)

                                        <label>{{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'name','class' => $value->TypeParent)) }}
                                            {{ $value->Libelle }} {!!$value->Lien!!} </label>
                                        <br />
                                        @endforeach
                                    </div>
                                </div>
                                @php
                                    $i=$i+1;
                                    @endphp

                                    @if($i==4)
                                </div>
                                @php
                                $i=1;
                                @endphp
                                @endif
                                

                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

               
            </div>
           
        </div>
    </div>
    <div class="form-group " style="float:right;">
                    <a href="{{url('/config/roles')}}" class="btn btn-success btn-sm"><span class="dark bolder"><i class="glyphicon glyphicon-list"></i> Liste des rôles et permissions</span></a>

                    <button type="submit" value="Create" class="btn btn-warning btn-sm bolder">
                        <i class="glyphicon glyphicon-edit"></i> Modifier
                    </button>
                </div>
    {!! Form::close() !!}
</div>

<script type="text/javascript">
    $(document).ready(function() {

        $(document).on('change', 'input:checkbox', function() {
            var id = $(this).attr('role');
            $("input:checkbox." + id).prop('checked', $(this).prop("checked"));
        });

        // $("#checkAllUser").change(function () {
        $(document).on('change', '#checkAllUser', function() {
            $("input:checkbox").prop('checked', $(this).prop("checked"));
        });

    });
</script>
@endsection