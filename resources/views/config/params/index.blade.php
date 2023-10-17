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
</style>
<div class="col-sm-12 col-xs-12 col-md-2 col-lg-2">
    @include('layouts.configmenu')
</div>
<div class="col-sm-12 col-xs-12 col-md-10 col-lg-10">
    {!! Form::model($param, ['method' => 'PATCH','route' => ['params.update', $param->id],
    'enctype'=>'multipart/form-data']) !!}
    <div class="EnteteContent">
        <div class="row">

            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 styleEntete">
                <i class="icon-cogs2 bigger-130"></i> Gestion des paramètres
            </div>

            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 text-right styleAction">
                Détails de paramètres
            </div>
        </div>
        <hr class="hrEntete">
        <!-- <p><button class="btn btn-sm btn-primary"><span class=" glyphicon glyphicon-plus"></span> Nouveau</button></p> -->

        <!-- <a class="btn btn-success" href="javascript:void(0)" id="createNewProduct"> Create New Product</a> -->
        <div class="row">
            <!-- {!! Form::open(array('route' => 'params.store','method'=>'POST', 'enctype'=>'multipart/form-data')) !!} -->

            <input type="text" name="id" id="id" value="{{$param->id}}" hidden>
            <div class="form-group">
                <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">

                    <div class="col-xs-12 label label-lg label-info  arrowed-right" style="padding: 0.em 0.6em 0.4em;margin-bottom:15px">
                        <label style="text-align:left">Informations de la société</label>
                    </div>

                    <div class="form-group">

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Code</strong>
                            <input class="form-control" name="Code" value="{{old('Code',$param->Code)}}" type="text">
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Nom</strong>
                            <input class="form-control" name="Nom" value="{{old('Nom',$param->Nom)}}" type="text">

                            @if ($errors->has('Nom'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('Nom') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Nom réduit</strong>

                            <input class="form-control" name="NomReduit" value="{{old('NomReduit',$param->NomReduit)}}" type="text">
                            @if ($errors->has('NomReduit'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('NomReduit') }}</span>
                            @endif
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Adresse éléctronique</strong>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-envelope-o bigger-110"></i>
                                </span>
                                <input class="form-control" name="Email" value="{{old('Email',$param->Email)}}" type="text">
                            </div>
                            @if ($errors->has('Email'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('Email') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Téléphone</strong>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="ace-icon fa fa-phone"></i>
                                </span>
                                <input class="form-control" name="Telephone" value="{{old('Telephone',$param->Telephone)}}" type="text">
                            </div>
                            @if ($errors->has('Telephone'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('Telephone') }}</span>
                            @endif
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Adresse</strong>
                            <input class="form-control" name="Adresse" value="{{old('Adresse',$param->Adresse)}}" type="text">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Fax</strong>

                            <input class="form-control" name="Fax" value="{{old('Fax',$param->Fax)}}" type="text">
                            @if ($errors->has('Fax'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('Fax') }}</span>
                            @endif
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Pays</strong>
                            <input class="form-control" name="Pays" value="{{old('Pays',$param->Pays)}}" type="text">
                            @if ($errors->has('Pays'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('Pays') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Ville</strong>

                            <input class="form-control" name="Ville" value="{{old('Ville',$param->Ville)}}" type="text">
                            @if ($errors->has('Ville'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('Ville') }}</span>
                            @endif
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Code Postal</strong>
                            <input class="form-control" name="CodePostal" value="{{old('CodePostal',$param->CodePostal)}}" type="text">
                            @if ($errors->has('CodePostal'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('CodePostal') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-xs-12 label label-lg label-info  arrowed-right" style="margin:5px 0 15px 0">
                        <label style="text-align:left">Notifications</label>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Email</strong>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-envelope-o bigger-110"></i>
                                </span>
                                <input class="form-control" name="EmailNotification" value="{{old('EmailNotification',$param->EmailNotification)}}" type="text">

                            </div>
                            @if ($errors->has('EmailNotification'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('EmailNotification') }}</span>
                            @endif
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Mot de passe</strong>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-key bigger-110"></i>
                                </span>
                                <input class="form-control" name="PasswordNotification" value="{{old('PasswordNotification',$param->PasswordNotification)}}" type="text">
                            </div>
                        </div>
                    </div>


                    <div class="col-xs-12 label label-lg label-info  arrowed-right"  style="margin:5px 0 15px 0">
                        <label style="text-align:left">Logo de la société</label>
                    </div>

                    <div class="form-group row text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <label for="images" class="drop-container">
                            <input type="file" class="form-control form-control-sm" accept="image/*" style="height: 50px;" id="imgInp" value="{{ old('LogoEntreprise')}}" name="LogoEntreprise" onchange="preview()">

                            <img id="blah" src="{{ asset('storage/images/'.$param->LogoEntreprise) }}" alt="User photo" width="100px" height="100px" style="border-radius:100px;" />
                        </label>
                        @if ($errors->has('LogoEntreprise'))
                        <span class="red" style="font-weight:bold;">{{ $errors->first('LogoEntreprise') }}</span>
                        @endif
                    </div>


                </div>

                <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">

                    <div class="col-xs-12 label label-lg label-info  arrowed-right" style="margin:5px 0 15px 0">
                        <label style="text-align:left">Mise en forme de la société</label>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Taille</strong>
                            <input class="form-control text-right" min='11' name="Taille" value="{{old('Taille',$param->Taille)}}" type="number">
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <strong>Police</strong>
                            <div>
                                <select name="Police" id="Police">
                                    <option value="">Séléctionner une police</option>
                                    @foreach($fonts as $font)
                                    <option value="{{$font->value}}" {{ (old('Police',$param->Police)==$font->value) ? 'selected' : ''}}>
                                        {{$font->text}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($errors->has('Police'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('Police') }}</span>
                            @endif
                        </div>
                    </div>



                    <div class="form-group">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:15px;">
                            <strong>Couleur Entête : </strong>

                            @foreach($couleurs as $couleur)
                            <label style="font-weight:bold;">
                                <input type="radio" name="ColorEntete" value="{{$couleur->value}}" @if((!old() && $param->ColorEntete==$couleur->value) || old('ColorEntete') == $couleur->value) checked="checked" @endif />
                                <span style="background-color:{{$couleur->value}};color:{{$couleur->value}};border:1px solid #100f0f;width:100px;height:15px;">Header </span>
                            </label>
                            @endforeach
                            @if ($errors->has('ColorEntete'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('ColorEntete') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:15px;">
                            <strong>Couleur Menu : </strong>
                            @foreach($couleurs as $couleur)
                            <label style="font-weight:bold;">
                                <input type="radio" name="ColorSidebar" value="{{$couleur->value}}" @if((!old() && $param->ColorSidebar==$couleur->value) || old('ColorSidebar') == $couleur->value) checked="checked" @endif />
                                <span style="background-color:{{$couleur->value}};width:100px;height:15px;color:{{$couleur->value}};border:1px solid #100f0f;">Sidebar</span>
                            </label>
                            @endforeach
                            @if ($errors->has('ColorSidebar'))
                            <span class="red" style="font-weight:bold;">{{ $errors->first('ColorSidebar') }}</span>
                            @endif
                        </div>
                    </div>


                    <div class="col-xs-12 label label-lg label-info  arrowed-right" style="margin:5px 0 15px 0">
                        <label style="text-align:left">Aperçu</label>
                    </div>

                    <div class="form-group row text-center col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin:15px;">
                        <div class="col-md-12 col-sm-12 HeaderColour" style="height:30px;background-color:{{$param->ColorEntete}}">Entête</div>
                        <div class="col-md-3 col-sm-3 SidebarColour" style="height:150px;background-color:{{$param->ColorSidebar}}">Menu</div>
                        <div class="col-md-9 col-sm-9" style="height:150px;"></div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <div class="form-group " style="float:right;">
        <button type="submit" value="Create" class="btn btn-warning btn-sm bolder">
            <i class="glyphicon glyphicon-edit"></i> Modifier
        </button>
    </div>

    {!! Form::close() !!}

</div>
<script>
    $(document).ready(function() {

        imgInp.onchange = evt => {
            const [file] = imgInp.files
            if (file) {
                blah.src = URL.createObjectURL(file)
            }
        }


        $('#Police').chosen();
        $("#Police_chosen").css("width", "100%");


        $("input[name='ColorEntete']").click(function() {
            var colour = $("input[name='ColorEntete']:checked").val();
            $(".HeaderColour").css("background-color", colour);
        });

        $("input[name='ColorSidebar']").click(function() {
            var colour = $("input[name='ColorSidebar']:checked").val();
            $(".SidebarColour").css("background-color", colour);
        });


    });
</script>
@endsection