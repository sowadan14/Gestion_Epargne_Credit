@extends('layouts.master')
@section('content')
<div class="EnteteContent">
    <div class="row">

        <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 styleEntete">
            <i class="menu-icon icon-android-compass  bigger-130"></i> Régularisation compte
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
        @can('createalist')
        <li>
            <a href="{{ route('regulcomptes.create') }}" style="vertical-align: inherit;"><span class="glyphicon glyphicon-plus blue"></span> Nouvelle régularisation compte</a>
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
                                    <th>Compte</th>
                                    <th  class="text-right">Débit</th>
                                    <th  class="text-right">Crédit</th>
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
                                    <td class="text-center">{{ \Carbon\Carbon::parse($row->DateOperation)->format('d/m/Y')}}</td>
                                    <td>{{ $row->compte->Libelle }}</td>
                                    @if( $row->Entree==1)
                                    <td class="text-right">0</td>
                                    <td class="text-right">{{number_format($row->Montant,0,',',' ') }}</td>                                   
                                    @else
                                    <td class="text-right">{{number_format($row->Montant,0,',',' ') }}</td> 
                                    <td class="text-right">0</td>
                                    @endif
                                    
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
                                                            <a href="{{ route('regulcomptes.show',$row->id) }}"
                                                                style="padding-bottom:4px;"
                                                                class="btn btn-white btn-xs bolder"><span
                                                                    class="ace-icon fa fa-eye dark"></span> Aperçu</a>

                                                            @can('editclient')

                                                            <a href="{{ route('regulcomptes.edit',$row->id) }}"
                                                                style="padding-bottom:0px;"
                                                                class="btn btn-warning btn-xs bolder"><span
                                                                    class="glyphicon glyphicon-edit"></span>
                                                                Modifier</a>

                                                            @endcan


                                                            @can('deleteclient')

                                                              <a data-id="{{ $row->id }}" 
                                                                class="show_confirm btn btn-danger btn-xs bolder"><span
                                                                    class="ace-icon fa fa-trash-o bigger-120"></span>
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

<script type="text/javascript">
    localStorage.setItem("myclass", 'regulcompte');
    localStorage.setItem("father", 'regul');

  
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