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

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="EnteteContent">
            <div class="row">

                <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 styleEntete">
                    <i class="menu-icon icon-android-compass  bigger-130"></i> Régularisation compte
                </div>

                <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 text-right styleAction">
                    Aperçu d'une régularisation compte
                </div>
            </div>
            <hr class="hrEntete">

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group row ">
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <strong>Date Opération: </strong> {{\Carbon\Carbon::parse($regulcompte->DateOperation)->format('d/m/Y')}}
                        </div>
                       
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <strong>Compte: </strong> {{$regulcompte->compte->Libelle}}
                        </div>
                      
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <strong>Montant: </strong> {{number_format($regulcompte->Montant,0,',',' ') }} {{ auth()->user()->entreprise->Devise}}
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        @if ($regulcompte->Entree == 1)
                                        <span class="badge badge-success">Crédit</span>
                                        @else
                                        <span class="badge badge-danger">Débit</span>
                                        @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  

    <div class="form-group" style="float:right;margin:15px;">
        <a href="{{ route('regulcomptes.index',$regulcompte->id) }}" class="btn btn-success btn-sm"><span class="dark bolder"><i class="glyphicon glyphicon-list"></i> Liste des régularisations compte</span></a>
    </div>
</div>



<div hidden>
    {!! Form::open(['method' => 'DELETE','style'=>'display:inline','id'=>'SubmitForm']) !!}

    {!! Form::close() !!}

</div>





<script type="text/javascript">
    $(document).ready(function() {

        localStorage.setItem("myclass", 'regulcompte');
        localStorage.setItem("father", 'regul');

        $('.show_confirm').click(function(event) {
            var id = $(this).data('id');
            var name = $(this).attr('role');
            $("#SubmitForm").attr('action', '/regul/regulcomptes/' + id);

            event.preventDefault();
            swal({
                    title: `Etes-vous sûr de vouloir annuler cette régularisation compte ` + name + '?',
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

     
        function toNumberFormat(value) {
            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
        }


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

    });
</script>
@endsection