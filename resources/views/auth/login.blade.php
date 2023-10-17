<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Connexion - MSN</title>

		<meta name="description" content="User login page" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
        <link rel="stylesheet" href="assets/css/bootstrap.4.3.min.css" />
        <link rel="stylesheet" href="assets/font-awesome/4.5.0/css/font-awesome.min.css" />
        <script src="assets/js/jquery-3.2.2.min.js"></script>
		<style type="text/css">
		body {
    color: #000;
    overflow-x: hidden;
    height: 100%;
    background-color: #B0BEC5;
    background-repeat: no-repeat
}

.card0 {
    box-shadow: 0px 4px 8px 0px #757575;
    border-radius: 0px
}

.card2 {
    margin: 0px 40px
}

.logo {
    width: 60px;
    height: 60px;
    margin-top: -20px;
}

.image {
    width: 300px;
    height: 200px
}

.border-line {
    border-right: 1px solid #EEEEEE
}

.facebook {
    background-color: #3b5998;
    color: #fff;
    font-size: 18px;
    padding-top: 5px;
    border-radius: 50%;
    width: 35px;
    height: 35px;
    cursor: pointer
}

.twitter {
    background-color: #1DA1F2;
    color: #fff;
    font-size: 18px;
    padding-top: 5px;
    border-radius: 50%;
    width: 35px;
    height: 35px;
    cursor: pointer
}

.linkedin {
    background-color: #2867B2;
    color: #fff;
    font-size: 18px;
    padding-top: 5px;
    border-radius: 50%;
    width: 35px;
    height: 35px;
    cursor: pointer
}

.line {
    height: 2px;
    width: 40%;
    background-color: #E0E0E0;
    margin-top: 10px
}

.or {
    width: 20%;
    font-weight: bold
}

.text-sm {
    font-size: 14px !important
}

::placeholder {
    color: #BDBDBD;
    opacity: 1;
    font-weight: 300
}

:-ms-input-placeholder {
    color: #BDBDBD;
    font-weight: 300
}

::-ms-input-placeholder {
    color: #BDBDBD;
    font-weight: 300
}

input,
textarea {
    padding: 10px 12px 10px 12px;
    border: 1px solid lightgrey;
    border-radius: 2px;
    margin-bottom: 5px;
    margin-top: 2px;
    width: 100%;
    box-sizing: border-box;
    color: #2C3E50;
    font-size: 14px;
    letter-spacing: 1px
}

input:focus,
textarea:focus {
    -moz-box-shadow: none !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
    border: 1px solid #304FFE;
    outline-width: 0
}

button:focus {
    -moz-box-shadow: none !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
    outline-width: 0
}

a {
    color: inherit;
    cursor: pointer
}

.btn-blue {
    background-color: #1A237E;
    width: 150px;
    color: #fff;
    border-radius: 2px
}

.btn-blue:hover {
    background-color: #000;
    cursor: pointer
}

.bg-blue {
    color: #fff;
    background-color: #1A237E;
	margin-right:40px;
	margin-left:40px;
    height: 20px;
}

@media screen and (max-width: 991px) {
    .logo {
        margin-left: 0px
    }

    .image {
        width: 300px;
        height: 220px
    }

    .border-line {
        border-right: none
    }

    .card2 {
        border-top: 1px solid #EEEEEE !important;
        margin: 0px 15px
    }
}
</style>
	</head>

	<body class="login-layout">
	<div class="container-fluid px-1 px-md-5 px-lg-1 px-xl-5 py-5 mx-auto">
    <div class="">
        <div class="row d-flex">
            <div class="col-lg-3"></div>
            <div class="col-lg-6">
                <div class="card2 card border-0 px-4 py-5">
					<div class="row px-3 mb-4" style="margin-top:-40px;">
                        <small class="" style="font-family:'Brush Script MT';font-size:30px;text-align:center;">Bienvenue! Merci de saissir vos informations pour continuer</small>
                    </div>
                    <div class="row px-3 mb-4">
                        <div class="line"></div> <small class="or text-center"><img class="logo" src="{{ url('assets/images/Files/LogoMSM.png') }}" style="border-radius:100px;border:3px solid red;" /></small>
                        <div class="line"></div>
                    </div>
                    <div style="margin-top:-40px;margin-bottom:-40px;">
					<form method="POST" action="{{ route('login') }}">
                        <!-- @csrf -->
                        {{ csrf_field() }}
                    <div class="row px-3"> 
					<label class="mb-1">
                            <h6 class="mb-0 text-sm">{{ __('Email Adresse') }}</h6>
                        </label> <input class="mb-4  @error('email') is-invalid @enderror" type="text" name="email" placeholder="Entrer {{ __('Email Adresse') }} valide">
						@error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
						 </div>
                    <div class="row px-3"> <label class="mb-1">
                            <h6 class="mb-0 text-sm">{{ __('Mot de passe') }}</h6>
                        </label> <input type="password" name="password" placeholder="Entrer {{ __('Mot de passe') }}"> 
						@error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
						</div>
                    <div class="row px-3 mb-4">
                        <div class="custom-control custom-checkbox custom-control-inline"> <input id="chk1" type="checkbox" name="chk" class="custom-control-input"> <label for="chk1" class="custom-control-label text-sm">{{ __('Se souvenir de moi') }}</label> </div>
						
						 @if (Route::has('password.request'))
						 <a href="#" class="ml-auto mb-0 text-sm">{{ __('Mot de passe oublié') }}?
						 </a>
                                @endif
                    </div>
                    <div class="row mb-3 px-3" style="float:right;margin-bottom:-10px;">
                     <!-- <button type="submit" class="btn btn-blue text-center">{{ __('Se Connecter') }}</button>  -->
                     <button type="submit" class="btn btn-primary"  style="margin-bottom: -15px;margin-top: -15px;">
                                    {{ __('Se Connecter') }}
                                </button>
                    </div>
                    </div>
					</form>
                  </div>
					<div class="bg-blue py-4">
            <div class="row px-3" style="margin-top:-10px;margin-bottom:2px;"> <small class="ml-4 ml-sm-5 mb-2">Copyright &copy; 2019. All rights reserved.</small>
                <div class="social-contact ml-4 ml-sm-auto"> <span class="fa fa-facebook mr-4 text-sm"></span> <span class="fa fa-google-plus mr-4 text-sm"></span> <span class="fa fa-linkedin mr-4 text-sm"></span> <span class="fa fa-twitter mr-4 mr-sm-5 text-sm"></span> </div>
            </div>
        </div>
            </div>
        </div>
       
    </div>
</div>
	</body>
</html>
