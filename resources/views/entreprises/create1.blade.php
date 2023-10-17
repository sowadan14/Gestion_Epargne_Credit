<link href="assets/css/chosen.min.css" rel="stylesheet" />
<script src="assets/js/chosen.jquery.min.js"></script>

<form id="entrepriseForm" name="entrepriseForm" method="post" class="form-horizontal">
    <input type="hidden" name="id" id="id">
    <div style="overflow-y:auto;max-height:450px;margin:15px;">
        <div class="alert alert-danger" style="display:none"></div>

        <div class="form-group row">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="col-xs-11 label label-lg label-info  arrowed-right">
                    <label style="text-align:left">Infos Dirigeant:</label>
                </div>
                <div style="margin-bottom:10px;margin-top:28px;">
                    <div class="col-xs-12 col-sm-12 col-md-11 col-lg-11" style="font-weight:bold;font-size:15px;">
                        <strong>Nom:</strong>
                        <input class="form-control" name="Nom" value="{{old('Nom')}}" type="text">
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-11 col-lg-11" style="font-weight:bold;font-size:15px;">
                        <strong>Prenoms:</strong>
                        <input class="form-control" name="prenoms" type="text">
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-11 col-lg-11" style="font-weight:bold;font-size:15px;">
                        <strong>Sexe:</strong>
                        <div>
                            <select name="sexe" id="sexe">
                                <option value="">Séléctionner un sexe</option>
                                @foreach($sexes as $sexe)
                                <option value="{{$sexe->value}}">{{$sexe->text}}</option>
                                @endforeach
                            </select>




                        </div>

                        <!-- <input class="form-control" name="sexe" type="text"> -->
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-11 col-lg-11" style="font-weight:bold;font-size:15px;">
                        <strong>Email:</strong>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-envelope-o bigger-110"></i>
                            </span>
                            <input class="form-control" name="email" type="text">
                        </div>

                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-11 col-lg-11" style="font-weight:bold;font-size:15px;">
                        <strong>Adresse:</strong>
                        <input class="form-control" name="adresse" type="text">
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-11 col-lg-11" style="font-weight:bold;font-size:15px;">
                        <strong>Telephone:</strong>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="ace-icon fa fa-phone"></i>
                            </span>
                            <input class="form-control" name="telephone" type="text">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-11 col-lg-11" style="font-weight:bold;font-size:15px;">
                        <strong>Date naissance:</strong>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-calendar bigger-110"></i>
                            </span>
                            <input class="form-control datepicker" name="dateNaissance" type="text">
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="col-xs-11 label label-lg label-info  arrowed-right">
                    <label style="text-align:left">Infos Entreprise:</label>
                </div>
                <div style="margin-bottom:10px;margin-top:28px;">
                    <div class="col-xs-12 col-sm-12 col-md-11 col-lg-11" style="font-weight:bold;font-size:15px;">
                        <strong>Nom:</strong>
                        <input class="form-control" name="nomEntreprise" type="text">
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-11 col-lg-11" style="font-weight:bold;font-size:15px;">
                        <strong>Nom réduit:</strong>
                        <input class="form-control" name="nomReduit" type="text">
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-11 col-lg-11" style="font-weight:bold;font-size:15px;">
                        <strong>Email:</strong>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-envelope-o bigger-110"></i>
                            </span>
                            <input class="form-control" name="emailEntreprise" type="text">
                        </div>

                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-11 col-lg-11" style="font-weight:bold;font-size:15px;">
                        <strong>Adresse:</strong>
                        <input class="form-control" name="adresseEntreprise" type="text">
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-11 col-lg-11" style="font-weight:bold;font-size:15px;">
                        <strong>Telephone:</strong>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="ace-icon fa fa-phone"></i>
                            </span>
                            <input class="form-control" name="telephoneEntreprise" type="text">
                        </div>

                    </div>
                </div>

                <div class="col-xs-11 label label-lg label-info  arrowed-right" style="margin-top:5px;">
                    <label style="text-align:left">Compte utilisateur:</label>
                </div>
                <div style="margin-bottom:10px;margin-top:28px;">
                    <div class="col-xs-12 col-sm-12 col-md-11 col-lg-11" style="font-weight:bold;font-size:15px;">
                        <strong>Mot de passe:</strong>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-key bigger-110"></i>
                            </span>
                            <input class="form-control" name="password" type="password">
                        </div>

                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-11 col-lg-11" style="font-weight:bold;font-size:15px;">
                        <strong>Confirmation:</strong>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-key bigger-110"></i>
                            </span>
                            <input class="form-control" name="confirmpassword" type="password">
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="width-95 label label-lg label-info  arrowed-right">
                    <label style="text-align:left">Rôle:</label>
                </div>
                <div style="margin-bottom:10px;margin-top:28px;">

                    <div class="form-group row">

                        <div class="col-xs-12 col-sm-12 col-md-11 col-lg-11" style="font-weight:bold;font-size:15px;">
                            <strong>Libellé:</strong>
                            <input class="form-control" name="role" type="text">
                        </div>
                        <br>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="">
                                <br>
                                <div class="form-group row" style="padding-left:15px;">
                                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                        <p style="font-weight:bold;font-size:15px;margin-bottom: 0.1rem">Permissions:
                                            <label style="color:#313cba;"><input type="checkbox" id="checkAllUser" />
                                                Choisir Tous</label>
                                        </p>
                                    </div>
                                </div>

                                <div>
                                    @foreach($numParents as $numParent)
                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="col-xs-11 label label-lg label-success  arrowed-right">
                                            <label style="text-align:left">
                                                {{ Form::checkbox('Parent', $numParent, false, array('class' => 'name','id'=>'Parent','role'=>$permissions->where('NumParent',$numParent)->first()->TypeParent)) }}
                                                {{$permissions->where('NumParent',$numParent)->first()->Parent}}</label>
                                        </div>
                                        <div style="margin-bottom:10px;margin-top:28px;">
                                            @foreach($permissions->where('NumParent',$numParent) as $value)
                                            <label>{{ Form::checkbox('permission[]', $value->id, false, array('class' => 'name','class' => $value->TypeParent)) }}
                                                {{ $value->Libelle }} {!!$value->Lien!!} </label>
                                            <br />
                                            @endforeach
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {

    $('.datepicker').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        todayHighlight: true
    })

    $('#sexe').chosen();
    $("#sexe_chosen").css("width", "100%");

});
</script>