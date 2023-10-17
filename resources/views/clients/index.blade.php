@extends('layouts.master')
@section('content')
    <div class="EnteteContent">
        <div class="row">

            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 styleEntete">
                <i class="menu-icon icon-group bigger-130"></i> Gestion des clients

            </div>

            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6">

                <div class="btn-group dropleft" style="float: right;">
                    <button data-toggle="dropdown" class="btn btn-primary btn-white btn-xs dropdown-toggle blue"
                        aria-expanded="false">
                        <span class="blue" style="font-weight:bold;">
                            <i class="glyphicon glyphicon-align-justify"></i> Option
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </span>

                    </button>


                    <ul class="dropleft dropdown-menu dropdown-inverse">
                        @can('createclient')
                        <li>
                            <a href="{{ route('clients.create') }}" style="vertical-align: inherit;"><span
                                    class="glyphicon glyphicon-plus blue"></span> Nouveau client</a>
                        </li>
                        @endcan

                        <li>
                            <a href="#" id="dd"><span class="icon-file-excel-o green"></span> Exporter la séléction en
                                excel</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <hr  class="hrEntete">
        <!-- <p><button class="btn btn-sm btn-primary"><span class=" glyphicon glyphicon-plus"></span> Nouveau</button></p> -->

        @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
        @endif

        @if ($message = Session::get('danger'))
        <div class="alert alert-danger">
            <p>{{ $message }}</p>
        </div>
        @endif
        <!-- <a class="btn btn-success" href="javascript:void(0)" id="createNewProduct"> Create New Product</a> -->
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-stripped data-table">
                                <thead>
                                    <tr>
                                        <th hidden>N°</th>
                                        <th class="text-center">
                                            {{ Form::checkbox('SelectedAll[]',false,false, array('class' =>
											'checkbox','id' => 'SelectedAll')) }}
                                        </th>
										<th>Code</th>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Téléphone</th>
                                        <th>Adresse</th>
                                        <th>Pays</th>
                                        <th>Ville</th>
                                        <th>Code postal</th>
                                        <th>Fax</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($data) > 0)

                                    @foreach($data as $row)

                                    <tr>
                                        <!-- <td><img src="{{ asset('images/' . $row->student_image) }}" width="75" /></td> -->
                                        <td hidden>{{ $row->id }}</td>
                                        <td class="text-center">{{ Form::checkbox('Selected[]',$row->id, false)}}</td>
                                        <td>{{ $row->Code }}</td>
										<td>{{ $row->Nom }} {{ $row->Prenoms }}</td>          
            <td>{{ $row->Email }}</td>
            <td>{{ $row->Telephone }}</td>
            <td>{{ $row->Adresse }}</td>
            <td>{{ $row->Pays }}</td>
            <td>{{ $row->Ville }}</td>
            <td>{{ $row->CodePostal }}</td>
            <td>{{ $row->Fax }}</td>
            <td class="text-center">
											@if ($row->Status == 1)
											<span class="badge badge-success">Actif</span>
											@else
											<span class="badge badge-danger">Inactif</span>
											@endif

										</td>
                                        <td class="text-center">

                                            <div class="btn-group dropleft" style="float: center;">
                                                <button data-toggle="dropdown"
                                                    class="btn btn-primary btn-xs dropdown-toggle blue"
                                                    aria-expanded="false">
                                                    <span style="font-weight:bold;">
                                                        Actions
                                                        <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                                                    </span>

                                                </button>


                                                <ul class="dropleft dropdown-menu dropdown-inverse">
                                                    <li>
                                                        <p style="min-width:250px;margin:0 0 0px;">
                                                            <a href="{{ route('clients.show',$row->id) }}"
                                                                style="padding-bottom:4px;"
                                                                class="btn btn-white btn-xs bolder"><span
                                                                    class="ace-icon fa fa-eye dark"></span> Aperçu</a>

                                                            @can('editclient')

                                                            <a href="{{ route('clients.edit',$row->id) }}"
                                                                style="padding-bottom:0px;"
                                                                class="btn btn-warning btn-xs bolder"><span
                                                                    class="glyphicon glyphicon-edit"></span>
                                                                Modifier</a>

                                                            @endcan


                                                            @can('deleteclient')

                                                            <!-- {!! Form::open(['method' => 'DELETE','route' => ['clients.destroy',
													$row->id],'style'=>'display:inline','id'=>'SubmitForm']) !!} -->

                                                            <a data-id="{{ $row->id }}" role="{{ $row->Nom }}"
                                                                class="show_confirm btn btn-danger btn-xs bolder"><span
                                                                    class="ace-icon fa fa-trash-o bigger-120"></span>
                                                                Supprimer</a>

                                                            <!-- {!! Form::close() !!} -->

                                                            @endcan
                                                        </p>
                                                    </li>
                                                </ul>
                                            </div>

                                        </td>
                                    </tr>

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
    </div>

<div hidden>
    {!! Form::open(['method' => 'DELETE','style'=>'display:inline','id'=>'SubmitForm']) !!}

    {!! Form::close() !!}

</div>

<script type="text/javascript">
$('.show_confirm').click(function(event) {
    var id = $(this).data('id');
    var name = $(this).attr('role');
    $("#SubmitForm").attr('action', '/clients/' + id);

    event.preventDefault();
    swal({
            title: `Etes-vous sûr de vouloir supprimer : ` + name + '?',
            text: "Il n'y a plus de retour en arrière.",
            icon: "error",
            buttons: true,
            buttons: ["Annuler", "Supprimer"],
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                $("#SubmitForm").submit();
            }
        });
});
</script>



<script type="text/javascript">
$(function() {



    /*------------------------------------------
     --------------------------------------------
     Pass Header Token
     --------------------------------------------
     --------------------------------------------*/
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