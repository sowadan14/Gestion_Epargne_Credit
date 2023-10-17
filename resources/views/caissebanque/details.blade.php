@extends('layouts.master')
@section('content')

<style type="text/css">
    fieldset {
        padding: 10px 10px 30px 10px;
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
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-stripped data-table">
                                    <thead>
                                        <tr>
                                            <th hidden>N°</th>
                                            <th>Date</th>
                                            <th>Libellé</th>
                                            <th class="text-right">Débit</th>
                                            <th class="text-right">Crédit</th>
                                            <th class="text-right">Solde</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($collection) > 0)
                                        @php
                                        $counter=1;
                                        @endphp

                                        @foreach($collection as $row)
                                        @if($counter==1)
                                        <tr style="font-weight:bold;">
                                            <td hidden>{{ $row['id'] }}</td>
                                            <td>@if($row['DateOperation']!='') {{ \Carbon\Carbon::parse($row['DateOperation'])->format('d/m/Y')}} @endif</td>
                                            <td>{{ $row['Libelle'] }}</td>
                                            <td class="text-right">{{ number_format($row['Debit'],0,',',' ')  }}</td>
                                            <td class="text-right">{{ number_format($row['Credit'],0,',',' ')  }}</td>
                                            <td class="text-right"  style="font-weight:bold;">{{ number_format($row['Credit']-$row['Debit'],0,',',' ')  }}</td>
                                        </tr>
                                        @else
                                        <tr>
                                            <td hidden>{{ $row['id'] }}</td>
                                            <td>@if($row['DateOperation']!='') {{ \Carbon\Carbon::parse($row['DateOperation'])->format('d/m/Y')}} @endif</td>
                                            <td>{{ $row['Libelle'] }}</td>
                                            <td class="text-right">{{ number_format($row['Debit'],0,',',' ')  }}</td>
                                            <td class="text-right">{{ number_format($row['Credit'],0,',',' ')  }}</td>
                                            <td class="text-right"  style="font-weight:bold;">{{ number_format($row['Solde'],0,',',' ')  }}</td>
                                        </tr>
                                        @endif
                                        @php
                                        $counter=$counter+1;
                                        @endphp
                                        @endforeach

                                        @else
                                        <tr>
                                            <td colspan="3" class="text-center">No Data Found</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

        var table = $('.data-table').DataTable({
            "language": {
                "thousands": ' ',
                "decimal": ",",
                "thousands": " ",
                "sProcessing": "Traitement en cours...",
                "sSearch": "Rechercher&nbsp;:",
                "sLengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
                "sInfo": "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
                "sInfoEmpty": "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
                "sInfoFiltered": "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
                "sInfoPostFix": "",
                "sLoadingRecords": "Chargement en cours...",
                "sZeroRecords": "Aucun &eacute;l&eacute;ment &agrave; afficher",
                "sEmptyTable": "Aucune donn&eacute;e disponible dans le tableau",
                "oPaginate": {
                    "sFirst": "Premier",
                    "sPrevious": "Pr&eacute;c&eacute;dent",
                    "sNext": "Suivant",
                    "sLast": "Dernier"
                },
                "oAria": {
                    "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                    "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
                }
            },
            processing: true,
            // ordering: true,
            // serverSide: true,

            columnDefs: [
            { orderable: true, className: 'reorder', targets: 0 },
            { orderable: false, targets: '_all' }
        ],

            "order": [
                [0, 'desc']
            ],

            // ajax: "{{ route('prodts.index') }}",
            // columns: [
            //     // {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            //     {data: 'id', name: 'id'},
            //     {data: 'Nom', name: 'Nom'},
            //     {data: 'action', name: 'action', orderable: false, searchable: false,class:'text-center'},
            // ]
        });

    });
</script>
@endsection