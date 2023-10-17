
<link  href="assets/css/chosen.min.css" rel="stylesheet" />
    <script src="assets/js/chosen.jquery.min.js"></script>

<form id="entrepriseForm" name="entrepriseForm" method="post" class="form-horizontal">
                <input type="hidden" name="id" id="id" value="{{$entreprise->id}}">
                <div style="overflow-y:auto;max-height:450px;margin:15px;">
            <div class="alert alert-danger" style="display:none"></div>
            <div style="margin-bottom:10px;margin-top:28px;">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="font-weight:bold;font-size:15px;">
            <strong>Nom:</strong>
            <input class="form-control" name="nomEntreprise" type="text" value="{{$entreprise->Libelle}}">
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="font-weight:bold;font-size:15px;">
        <strong>Nom réduit:</strong>
        <input class="form-control" name="nomReduit" type="text" value="{{$entreprise->NomReduit}}">
</div>
<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="font-weight:bold;font-size:15px;">
<strong>Email:</strong>
<div class="input-group">
    <span class="input-group-addon">
        <i class="fa fa-envelope-o bigger-110"></i>
    </span>
    <input class="form-control" name="emailEntreprise" type="text" value="{{$entreprise->Email}}">
</div>
</div>

<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="font-weight:bold;font-size:15px;">
<strong>Adresse:</strong>
<input class="form-control" name="adresseEntreprise" type="text" value="{{$entreprise->Adresse}}">
</div>
<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="font-weight:bold;font-size:15px;">
<strong>Telephone:</strong>
<div class="input-group">
    <span class="input-group-addon">
        <i class="ace-icon fa fa-phone"></i>
    </span>
    <input class="form-control" name="telephoneEntreprise" type="text" value="{{$entreprise->Telephone}}">
</div>

</div>
           </div>
</div>  
</form>

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