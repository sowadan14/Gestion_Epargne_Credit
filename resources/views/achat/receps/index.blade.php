@extends('layouts.master')
@section('content')
<div class="EnteteContent">
    <div class="row">

        <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 styleEntete">
            <i class=" fa fa-truck"></i> Gestion réceptions
        </div>

        <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6">

            <div class="btn-group dropleft" style="float: right;">
                <button data-toggle="dropdown" class="btn btn-primary btn-white btn-xs dropdown-toggle blue" aria-expanded="false">
                    <span class="blue" style="font-weight:bold;">
                        <i class="glyphicon glyphicon-align-justify"></i> Option
                        <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                    </span>

                </button>


                <ul class="dropleft dropdown-menu dropdown-inverse">
                    @can('createarecep')
                    <li>
                        <a href="{{ route('receps.create') }}" style="vertical-align: inherit;"><span class="glyphicon glyphicon-plus blue"></span> Nouvelle réception</a>
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

    <hr class="hrEntete">
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
                                    <th class="text-center">Date</th>
                                    <th>Réference</th>
                                    <th>Fournisseur</th>
                                    <th>Commande</th>
                                    <th class="text-right">Montant réception</th>
                                    <th class="text-center">Facturé?</th>
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
                                    <td class="text-center">{{ \Carbon\Carbon::parse($row->DateAchat)->format('d/m/Y')}}</td>
                                    <td>{{ $row->Reference }}</td>
                                    <td>@if( $row->commande) {{ $row->commande->fournisseur->Nom }} @endif</td>
                                    <td>@if( $row->commande){{ $row->commande->Reference }} @endif</td>
                                    <td class="text-right">{{number_format($row->MontantReçu,0,',',' ') }}</td>
                                    <td class="text-center">@if ($row->MontantReçu == $row->MontantFacture)
                                        <span class="badge badge-success">Oui</span>
                                        @else
                                        <span class="badge badge-danger">Non</span>
                                        @endif
                                    </td>
                                    <td class="text-center">

                                        <div class="btn-group dropleft" style="float: center;">
                                            <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle blue" aria-expanded="false">
                                                <span style="font-weight:bold;">
                                                    Actions
                                                    <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                                                </span>

                                            </button>


                                            <ul class="dropleft dropdown-menu dropdown-inverse">
                                                <li>
                                                    <p style="min-width:250px;margin:0 0 0px;">
                                                        <a href="{{ route('receps.show',$row->id) }}" style="padding-bottom:4px;" class="btn btn-white btn-xs bolder"><span class="ace-icon fa fa-eye dark"></span> Aperçu</a>

                                                        @can('editarecep')

                                                        <a href="{{ route('receps.edit',$row->id) }}" style="padding-bottom:0px;" class="btn btn-warning btn-xs bolder"><span class="glyphicon glyphicon-edit"></span>
                                                            Modifier</a>

                                                        @endcan
                                                        @if ($row->MontantReçu != $row->MontantFacture)
                                                        @can('createafact')

                                                        <a href="{{ route('afacts.addfact',$row->id) }}" style="padding-bottom:0px;" class="btn btn-info btn-xs bolder"><span class="icon-books"></span>
                                                            Facture</a>

                                                        @endcan
                                                        @endIF

                                                        @can('deletearecep')
                                                        <a data-id="{{ $row->id }}" role="{{ $row->Reference }}" class="show_confirm btn btn-danger btn-xs bolder"><span class="ace-icon fa fa-trash-o bigger-120"></span>
                                                            Supprimer</a>
                                                        @endcan


                                                    </p>
                                                </li>
                                            </ul>
                                        </div>

                                    </td>
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

<div hidden>
    {!! Form::open(['method' => 'DELETE','style'=>'display:inline','id'=>'SubmitForm']) !!}

    {!! Form::close() !!}

</div>

<script type="text/javascript">
    localStorage.setItem("myclass", 'recep');
    localStorage.setItem("father", 'achat');


    $('.show_confirm').click(function(event) {
        var id = $(this).data('id');
        var name = $(this).attr('role');
        $("#SubmitForm").attr('action', '/achat/receps/' + id);

        event.preventDefault();
        swal({
                title: `Etes-vous sûr de vouloir supprimer la réception: ` + name + '?',
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
            // order: [[0, 'desc']],
        });


    });
</script>
@endsection