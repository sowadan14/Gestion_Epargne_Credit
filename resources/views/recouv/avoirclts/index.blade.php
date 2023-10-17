@extends('layouts.master')
@section('content')
<div class="EnteteContent">
    <div class="row">

        <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 styleEntete">
            <i class="icon-money bigger-130"></i> Gestion avoirs client
        </div>

        <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6">

        </div>
    </div>

    <hr class="hrEntete">
    <!-- <p><button class="btn btn-sm btn-primary"><span class=" glyphicon glyphicon-plus"></span> Nouveau</button></p> -->

    <!-- <a class="btn btn-success" href="javascript:void(0)" id="createNewProduct"> Create New Product</a> -->
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-stripped data-table" style="font-size:80%;">
                            <thead>
                                <tr>
                                    <th hidden>N°</th>
                                    <th class="text-center">
                                        {{ Form::checkbox('SelectedAll[]',false,false, array('class' =>
											'checkbox','id' => 'SelectedAll')) }}
                                    </th>                                    
                                    <th>Client</th>                                    
                                    <th class="text-right">Montant</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($data) > 0)

                                @foreach($data as $row)
                                <tr>
                                    <!-- <td><img src="{{ asset('images/' . $row->student_image) }}" width="75" /></td> -->
                                    <td hidden>{{ $row->id }}</td>
                                    <td class="text-center">{{ Form::checkbox('Selected[]',$row->id, false)}}</td>
                                     <td>{{ $row->client->Nom }}</td>
                                    <td class="text-right">{{number_format($row->Montant,0,',',' ') }}</td>                                    
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    localStorage.setItem("myclass", 'avoirclt');
    localStorage.setItem("father", 'recouv');


    $(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        /*------------------------------------------
        --------------------------------------------
        Render DataTable
        --------------------------------------------
        --------------------------------------------*/
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
            ordering: false,
            // serverSide: true,
            "order": [
                [0, 'desc']
            ],
        });


    });
</script>
@endsection