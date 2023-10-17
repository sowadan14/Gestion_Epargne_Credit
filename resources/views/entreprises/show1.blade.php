
<link  href="assets/css/chosen.min.css" rel="stylesheet" />
    <script src="assets/js/chosen.jquery.min.js"></script>
                <div style="margin:15px;font-size:15px;">

                <div class="form-group row">
                  <label class="col-sm-3 control-label no-padding-right" style="font-weight:bold;"> Nom: </label>
                  <div class="col-sm-9">
                  {{$entreprise->Libelle}}
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-sm-3 control-label no-padding-right" style="font-weight:bold;"> Nom Réduit: </label>
                  <div class="col-sm-9">
                  {{$entreprise->NomReduit}}
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-sm-3 control-label no-padding-right" style="font-weight:bold;"> Email: </label>
                  <div class="col-sm-9">
                  {{$entreprise->Email}}
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-sm-3 control-label no-padding-right" style="font-weight:bold;"> Téléphone: </label>
                  <div class="col-sm-9">
                  {{$entreprise->Telephone}}
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-sm-3 control-label no-padding-right" style="font-weight:bold;"> Adresse: </label>
                  <div class="col-sm-9">
                  {{$entreprise->Adresse}}
                  </div>
                </div>
</div>  

<script>
    $(document).ready(function () {

$('.datepicker').datepicker({
                  autoclose: true,
                  format: 'dd/mm/yyyy',
                  todayHighlight: true
              })

              $('#sexe').chosen();
        $("#sexe_chosen").css("width", "100%");

            });
</script>