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
    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">

        <div class="EnteteContent">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group row ">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Date commande: </strong> {{\Carbon\Carbon::parse($afact->reception->commande->DateAchat)->format('d/m/Y')}}
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Réference commande: </strong> {{$afact->reception->commande->Reference}}
                        </div>
                    </div>

                    <div class="form-group row ">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Montant commande: </strong> {{number_format($afact->reception->commande->MontantTTC,0,',',' ') }} {{ auth()->user()->entreprise->Devise}}
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Date réception: </strong> {{\Carbon\Carbon::parse($afact->reception->DateReception)->format('d/m/Y')}}
                        </div>
                    </div>


                    <div class="form-group row ">

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Réference réception: </strong> {{$afact->reception->Reference}}
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Montant réception: </strong> {{number_format($afact->reception->MontantReçu,0,',',' ') }} {{ auth()->user()->entreprise->Devise}}
                        </div>
                    </div>
                </div>


            </div>
        </div>

        <div class="EnteteContent">
            <div class="row">

                <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 styleEntete">
                    <i class=" icon-money"></i> Gestion paiements
                </div>

                <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 text-right styleAction">
                    Aperçu d'un paiement
                </div>
            </div>
            <hr class="hrEntete">

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group row ">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Date facture: </strong> {{\Carbon\Carbon::parse($paiement->DatePaiement)->format('d/m/Y')}}
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Réference facture: </strong> {{$paiement->Reference}}
                        </div>
                    </div>

                    <div class="form-group row ">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Compte: </strong> {{$paiement->compte->Libelle}}
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Mode paiement: </strong> {{$paiement->modepaiement->Nom}}
                        </div>
                    </div>

                    <div class="form-group row ">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Montant: </strong> {{number_format($paiement->Montant,0,',',' ') }} {{ auth()->user()->entreprise->Devise}}
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Remise: </strong> {{number_format($paiement->Remise,0,',',' ') }} {{ auth()->user()->entreprise->Devise}}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
        <div class="EnteteContent">
            <div class="row">
                <div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
                    Détails de la facture
                </div>
            </div>
            <hr class="hrEntete">
            <div class=" detailsfact">
                @if($afact !='')
                <table>

                    <tr>
                        <td>
                            <strong>Réference facture:</strong> {{$afact->Reference}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>Fournisseur:</strong> {{$afact->reception->commande->fournisseur->Nom}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>Date facture:</strong>
                            {{ \Carbon\Carbon::parse($afact->DateFacture)->format('d/m/Y')}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>Date échéance:</strong>
                            {{ \Carbon\Carbon::parse($afact->DateEcheance)->format('d/m/Y')}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>Montant facture:</strong> {{number_format($afact->MontantFacture,0,',',' ') }}
                            {{ auth()->user()->entreprise->Devise}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>Montant payé:</strong> {{number_format($afact->MontantPaye,0,',',' ') }}
                            {{ auth()->user()->entreprise->Devise}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>Reste à payer:</strong> {{number_format($afact->MontantFacture-$afact->MontantPaye,0,',',' ') }}
                            {{ auth()->user()->entreprise->Devise}}
                        </td>
                    </tr>


                </table>
                @endif
            </div>
        </div>
    </div>

    <div class="form-group" style="float:right;margin:15px;">
        <a href="{{ route('apaiements.facture',$afact->id) }}" class="btn btn-success btn-sm"><span class="dark bolder"><i class="glyphicon glyphicon-list"></i> Liste des paiements</span></a>
    </div>
</div>



<div hidden>
    {!! Form::open(['method' => 'DELETE','style'=>'display:inline','id'=>'SubmitForm']) !!}

    {!! Form::close() !!}

</div>





<script type="text/javascript">
    $(document).ready(function() {

        localStorage.setItem("myclass", 'apaiement');
        localStorage.setItem("father", 'recouv');

        $('.show_confirm').click(function(event) {
            var id = $(this).data('id');
            var name = $(this).attr('role');
            $("#SubmitForm").attr('action', '/recouv/apaiements/' + id);

            event.preventDefault();
            swal({
                    title: `Etes-vous sûr de vouloir annuler le paiement : ` + name + '?',
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


        function toNumberFormat(value) {
            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
        }




        $(document).on('keyup', 'input[name="Qte[]"]', function() {
            var _this = $(this);
            var min = parseInt(_this.attr('min')); // if min attribute is not defined, 1 is default
            var max = parseInt(_this.attr('max')); // if max attribute is not defined, 100 is default
            var val = parseInt(_this.val()) || (min -
                1
            ); // if input char is not a number the value will be (min - 1) so first condition will be true
            if (val < min)
                _this.val(min);
            if (val > max)
                _this.val(max);
        });

        $('.datepicker').datepicker({
            autoclose: true,
            format: 'dd/mm/yyyy',
            todayHighlight: true
        })

        $(document).on('click', '.remove_this', function() {
            var DivName = $(this).attr("name");
            $("." + DivName).remove();
            CalculeSumChamps("TableOfData");
            return false;
        });



        function CalculeSumChamps(tableID) {
            var montantht = 0;
            var montantremise = 0;
            var montanttva = 0;
            var montantttc = 0;
            var qte = 0;
            $("#" + tableID + " tbody#DetailsUnites tr").each(function() {
                // alert($(this).html());
                var Qte = parseFloat(($(this).find("td").eq(2).find("input").val()).replace(/ /g, ''));
                var Prix = parseFloat(($(this).find("td").eq(3).html()).replace(/ /g, ''));
                var remise = parseFloat(($(this).find("td").eq(4).html()).replace(/ /g, ''));
                var tva = parseFloat(($(this).find("td").eq(5).html()).replace(/ /g, ''));


                var mtht = parseFloat(Qte * Prix);
                var mtremise = Math.round(parseFloat((mtht * remise) / 100));
                var mttva = Math.round(parseFloat(((mtht * tva) / 100)));

                qte += parseFloat(Qte);
                montantht += mtht;
                montantremise += mtremise;
                montanttva += mttva;
                montantttc += parseFloat(parseFloat(mtht) + parseFloat(mttva) - parseFloat(mtremise));
                $(this).find("td").eq(6).html(toNumberFormat(parseFloat(parseFloat(mtht) + parseFloat(mttva) - parseFloat(mtremise))));
            });

            $(".mtht").html(toNumberFormat(montantht));
            $(".mtremise").html(toNumberFormat(montantremise));
            $(".mttva").html(toNumberFormat(montanttva));
            $(".mtttc").html(toNumberFormat(montantttc));

            $("#TotalQte").val(qte);
            $("#TotalRemise").val(montantremise);
            $("#TotalTva").val(montanttva);
            $("#TotalMontantTTC").val(montantttc);
            $("#TotalMontantHT").val(montantht);

        }


        // $('input[name="Qte[]"]').on('change', function(e) {
        $(document).on("change", 'input[name="Qte[]"]', function() {
            if ($(this).val() == "") {
                $(this).val(0);
            }

            var id = $(this).attr("list");
            Qte = parseFloat(($("#TableOfData tbody tr." + id).find("td").eq(2).find("input").val())
                .replace(/ /g, ''));
            Prix = parseFloat(($("#TableOfData tbody tr." + id).find("td").eq(3).html())
                .replace(/ /g, ''));
            remise = parseFloat(($("#TableOfData tbody tr." + id).find("td").eq(4).html())
                .replace(/ /g, ''));
            tva = parseFloat(($("#TableOfData tbody tr." + id).find("td").eq(5).html())
                .replace(/ /g, ''));

            MontantHT = Qte * Prix;
            Remise = Math.round((MontantHT * remise / 100).toFixed(0));
            TVA = Math.round((MontantHT * tva) / 100);
            MontantTTC = parseFloat(MontantHT) + parseFloat(TVA) - parseFloat(Remise);
            $("#TableOfData tbody tr." + id).find("td").eq(6).html(toNumberFormat(MontantTTC));

            CalculeSumChamps("TableOfData");
        });



        $('#ReceptionId').chosen();
        $("#ReceptionId_chosen").css("width", "100%");


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });




        $(document).on('change', '#ReceptionId', function() {
            var ReceptionId = $("#ReceptionId").val();
            if (ReceptionId != "") {
                $.ajax({
                    url: "{{url('recouv/apaiements/getDetailsfact')}}",
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        id: ReceptionId
                    },
                    success: function(data) {
                        $('.detailsfact').html(data.htmlDetailsRecep);
                        $('#DetailsUnites').empty();
                        // $("#TableOfData tbody").append(data.htmlTable);
                        $('#DetailsUnites').append(data.htmlTable);
                    },
                });

            }
        });
    });
</script>
@endsection