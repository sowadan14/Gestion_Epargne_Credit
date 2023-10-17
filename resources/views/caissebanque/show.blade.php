@extends('layouts.master')
@section('content')

<style type="text/css">
    fieldset {
        margin: 0 0 30px 0;
        border: 1px solid #ccc;
    }

    legend {
        width: auto;
        display: block;
        border: unset;
    }

    .inputWrap {
        margin: 0 0 12px 0;
        width: 100%;
    }
</style>
<div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
    <div class="EnteteContent">
        <div class="row">

            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 styleEntete">
                <i class="menu-icon icon-institution"></i> Caisses/Banques
            </div>

            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 text-right styleAction">
                Détails d'un compte
            </div>
        </div>
        <hr class="hrEntete">

        <div class="row">
            {!! Form::open(array('route' => 'caissebanque.store','method'=>'POST')) !!}
            <input type="text" name="compteId" id="compteId" value="{{$compte->id}}" hidden>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <strong>Date début</strong>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-calendar bigger-110"></i>
                    </span>
                    <input class="form-control datepicker" name="DateDebut" value="{{old('DateDebut')}}" type="text" required>
                </div>
                @if ($errors->has('DateDebut'))
                <span class="red" style="font-weight:bold;">{{ $errors->first('DateDebut') }}</span>
                @endif
            </div>

            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <strong>Date fin</strong>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-calendar bigger-110"></i>
                    </span>
                    <input class="form-control datepicker" name="DateFin" value="{{old('DateFin')}}" type="text" required>
                </div>
                @if ($errors->has('DateFin'))
                <span class="red" style="font-weight:bold;">{{ $errors->first('DateFin') }}</span>
                @endif
            </div>

            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="form-group " style="margin-top:22px;">
                    <button type="submit" value="Create" class="btn btn-primary btn-sm bolder">
                        <i class="glyphicon glyphicon-ok"></i> valider
                    </button>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>


    <div class="EnteteContent">
       
        <fieldset>
            <legend>
                coucou
            </legend>
        </fieldset>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {

        localStorage.setItem("myclass", 'caissebanque');

       
        $('.datepicker').datepicker({
            autoclose: true,
            format: 'dd/mm/yyyy',
            todayHighlight: true
        })

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

    });
</script>
    @endsection