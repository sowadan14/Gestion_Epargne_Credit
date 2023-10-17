@extends('layouts.master')
@section('content')

<style type="text/css">
    input[type=file]::file-selector-button {
        margin-right: 5px;
        border: none;
        background: #084cdf;
        padding: 10px 5px;
        border-radius: 10px;
        color: #fff;
        cursor: pointer;
        transition: background .2s ease-in-out;
    }

    input[type=file]::file-selector-button:hover {
        background: #0d45a5;
    }

    .drop-container {
        position: relative;
        display: flex;
        margin: 10px;
        gap: 10px;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: inherit;
        padding: 5px;
        border-radius: 10px;
        border: 2px dashed #555;
        color: #444;
        cursor: pointer;
        transition: background .2s ease-in-out, border .2s ease-in-out;
    }

    .drop-container:hover {
        background: #eee;
        border-color: #111;
    }

    .drop-container:hover .drop-title {
        color: #222;
    }

    .drop-title {
        color: #444;
        font-size: 20px;
        font-weight: bold;
        text-align: center;
        transition: color .2s ease-in-out;
    }

    td,
    th {
        padding: 5px;
    }

    .innerTd {
        width: 100px;
    }

    .active {
        background-color: aqua;
    }

    #navMenus {
        list-style: none;
    }

    li {
        cursor: pointer;
        margin-bottom: 5px;
    }

    ul {
        margin-left: 0px;
    }

    .tableRecap td {
        white-space: nowrap;
        border-top: 0px solid #ddd;
    }

    .tableRecap td:first-child {
        width: 100%;
        border-top: 0px solid #ddd;
        padding-top: 1px;
        padding-bottom: 1px;
    }

    .tableRecap td:last-child {
        border-top: 0px solid #ddd;
        padding-top: 1px;
        padding-bottom: 1px;
    }
</style>

{!! Form::model($regulcompte, ['method' => 'PATCH','route' => ['regulcomptes.update', $regulcompte->id]]) !!}

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

    <div class="EnteteContent">
            <div class="row">
                <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 styleEntete">
                    <i class="menu-icon icon-android-compass  bigger-130"></i> Régularisation compte
                </div>

                <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 text-right styleAction">
                    Modification d'une régularisation compte
                </div>
            </div>
            <hr class="hrEntete">
            <div class="row">
            <input type="text" name="id" id="id" value="{{$regulcompte->id}}" hidden>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <strong>Date opération</strong>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-calendar bigger-110"></i>
                        </span>
                        <input class="form-control datepicker" name="DateOperation" value="{{old('DateOperation',\Carbon\Carbon::parse($regulcompte->DateOperation)->format('d/m/Y'))}}" type="text" required>
                    </div>
                    @if ($errors->has('DateOperation'))
                    <span class="red" style="font-weight:bold;">{{ $errors->first('DateOperation') }}</span>
                    @endif
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <strong>Compte</strong>
                    <div>
                    <select name="CompteId" id="CompteId">
                            <option value="">Séléctionner un compte</option>
                            @foreach($comptes as $compte)
                            <option value="{{$compte->id}}" {{ ($compte->id==$regulcompte->CompteId) ? 'selected' : ''}} {{ (old('CompteId')==$compte->id) ? 'selected' : ''}}>
                                {{$compte->Libelle}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @if ($errors->has('CompteId'))
                    <span class="red" style="font-weight:bold;">{{ $errors->first('CompteId') }}</span>
                    @endif
                </div>
            </div>
            <div class="row">

                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <strong>Montant </strong>
                    <input class="form-control text-right" min='0' id="Montant" name="Montant" value="{{old('Montant',round($regulcompte->Montant))}}" type="text">
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <label style="margin-top:25px;">
                    @if($regulcompte->Entree==1)
                    <input name="Entree" checked type="checkbox">
                    @else
                    <input name="Entree" type="checkbox">
                    @endif
                        <strong> En crédit</strong></label>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="form-group" style="float:right;margin:15px;">
    <a href="{{ route('regulcomptes.index') }}" class="btn btn-success btn-sm"><span class="dark bolder"><i class="glyphicon glyphicon-list"></i> Liste des régularisations compte</span></a>

    <button type="submit" value="Create" class="btn btn-warning btn-sm bolder">
        <i class="glyphicon glyphicon-edit"></i> Modifier
    </button>
</div>
{!! Form::close() !!}


<script type="text/javascript">
    $(document).ready(function() {

        localStorage.setItem("myclass", 'regulcompte');
        localStorage.setItem("father", 'regul');

       
        function toNumberFormat(value) {
            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
        }

        $('#CompteId').chosen();
        $("#CompteId_chosen").css("width", "100%");

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