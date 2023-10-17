<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <title>Dashboard - MSN</title>

  <meta name="description" content="overview &amp; stats" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

  <link rel="shortcut icon" href="{{ asset('/favicon.ico') }}">

  <!-- bootstrap & fontawesome -->
  <!--   <link rel="stylesheet" href="{{config('app.url').'/'.config('app.app_ctrl_url').'/resources/css/assets/css/bootstrap.min.css'}}" /> -->
  <link rel="stylesheet" href="/assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="/assets/font-awesome/4.5.0/css/font-awesome.min.css" />
  <link rel="stylesheet" href="/assets/icomoon.css" />
  <link rel="stylesheet" href="/assets/flag-icon-css/css/flag-icon.min.css" />
  <link rel="stylesheet" href="/assets/fonts/MyFonts.css" />



  <!-- page specific plugin styles -->

  <!-- text fonts -->
  <link rel="stylesheet" href="/assets/css/fonts.googleapis.com.css" />

  <!-- ace styles -->
  <link rel="stylesheet" href="/assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />
  <link rel="stylesheet" href="/assets/css/bootstrap-datepicker3.min.css" />

  <!--[if lte IE 9]>
      <link rel="stylesheet" href="assets/css/ace-part2.min.css" class="ace-main-stylesheet" />
    <![endif]-->
  <script src="/assets/js/jquery-2.1.4.min.js"></script>
  <script src="/assets/js/bootstrap.min.js"></script>
  <script src="/assets/js/jquery.dataTables.min.js"></script>

  <link href="/assets/css/chosen.min.css" rel="stylesheet" />
  <script src="/assets/js/chosen.jquery.min.js"></script>

  <script src="/assets/js/bootstrap-datepicker.min.js"></script>
  <script src="/assets/js/jquery.dataTables.bootstrap.min.js"></script>
  <script src="/assets/js/sweetalert.min.js"></script>
  <script src="/assets/js/ace.min.js"></script> <!-- <![endif]-->
  <script src="/assets/js/chosen.jquery.min.js"></script>
  <link href="/assets/css/chosen.min.css" rel="stylesheet" />

  <!--[if lte IE 9]>
      <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
    <![endif]-->

  <!-- inline styles related to this page -->

  <!-- ace settings handler -->

  <!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

  <!--[if lte IE 8]>
    <script src="assets/js/html5shiv.min.js"></script>
    <script src="assets/js/respond.min.js"></script>
    <![endif]-->
  <style type="text/css">
    .form-control {
      height: 28px;
    }
  </style>

</head>

<body class="no-skin"
  style="font-family:{{auth()->user()->entreprise->Police}}; font-size:{{auth()->user()->entreprise->Taille}}px;">
  <div id="navbar" class="navbar navbar-default ace-save-state"
    style="background-color:{{auth()->user()->entreprise->ColorEntete}}">
    <div class="navbar-container ace-save-state" id="navbar-container">
      @include('layouts.header')
    </div><!-- /.navbar-container -->
  </div>

  <div class="main-container ace-save-state" id="main-container" style="height:100%;">

    @include('layouts.sidebar')
    <div class="main-content">
      @include('layouts.master-mini')
      <div class="main-content-inner">
        <div class="row">

          <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="page-header">
              <h1 style="color:black;font-weight:bold;">
                @if($Titre){{ $Titre }}@endif
              </h1>
            </div><!-- /.page-header -->
          </div>


          <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
              <ul class="breadcrumb">
                <!-- <li>
                <i class="ace-icon glyphicon glyphicon-th-list"></i>
                <a href="#">Tables</a>
              </li>
              <li class="active">Table basique </li> -->
                <li class="breadcrumb-item"><a href="home"><i class="ace-icon fa fa-home home-icon"></i> Accueil</a>
                </li>
                @if($Breadcrumb)
                {!!$Breadcrumb!!}
                @endif

              </ul>
            </div>
          </div>

        </div>


        <div class="page-content">
          @yield('content')


        </div>
      </div><!-- /.main-content -->

      @include('layouts.footer')

      <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
        <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
      </a>
    </div>
    <!-- basic scripts -->

    <!--[if !IE]> -->




    <!--[if IE]>
<script src="assets/js/jquery-1.11.3.min.js"></script>
<![endif]-->
    <!-- <script type="text/javascript">
        if ('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>" + "<" + "/script>");
      </script> -->


    <!-- page specific plugin scripts -->

    <!--[if lte IE 8]>
		  <script src="assets/js/excanvas.min.js"></script>
		<![endif]-->

    <!-- ace scripts -->

    <!-- page specific plugin scripts -->

    <!--[if lte IE 8]>
      <script src="assets/js/excanvas.min.js"></script>
    <![endif]-->

    <script type="text/javascript">
      $(document).ready(function (e) {


        let pass = '';

        const AmagiLoader = {
          __loader: null,
          show: function () {

            if (this.__loader == null) {
              var divContainer = document.createElement('div');
              divContainer.style.position = 'fixed';
              divContainer.style.left = '0';
              divContainer.style.top = '0';
              divContainer.style.width = '100%';
              divContainer.style.height = '100%';
              divContainer.style.zIndex = '9998';
              divContainer.style.backgroundColor = 'rgba(4, 32, 251, 0.8)';

              var div = document.createElement('div');
              div.style.position = 'absolute';
              div.style.left = '50%';
              div.style.top = '50%';
              div.style.zIndex = '9999';
              div.style.height = '64px';
              div.style.width = '64px';
              div.style.margin = '-76px 0 0 -76px';
              div.style.border = '8px solid #e1e1e1';
              div.style.borderRadius = '50%';
              div.style.borderTop = '8px solid #003580';
              div.animate([{
                transform: 'rotate(0deg)'
              },
              {
                transform: 'rotate(360deg)'
              }
              ], {
                duration: 2000,
                iterations: Infinity
              });
              divContainer.appendChild(div);
              this.__loader = divContainer
              document.body.appendChild(this.__loader);
            }
            this.__loader.style.display = "";
          },
          hide: function () {
            if (this.__loader != null) {
              this.__loader.style.display = "none";
            }
          }
        }

        // Get
        // Get



        $('.nav-list li.active').removeClass('active');
        $('.nav-list li.open').removeClass('active');
        $('.nav-list li.open').removeClass('open');

        if(localStorage.getItem("father")!='')
        {
          $('.' + localStorage.getItem("father")).addClass('active');
          $('.' + localStorage.getItem("father")).addClass('open');
        }

        $('.' + localStorage.getItem("myclass")).addClass('active');
       
        $(".nav>li").click(function () {
          if (pass == '') {
            localStorage.setItem("myclass", $(this).attr('rel'));
            localStorage.setItem("father", '');
          }
        });



        $(".nav>li>ul>li").click(function () {
          pass = ''
          localStorage.setItem("myclass", $(this).attr('rel'));
          localStorage.setItem("father", $(this).attr('role'));
          pass = '1'
          //  return false;
        });

        // $("#checkAllUser").change(function () {
        $(document).on('change', '#SelectedAll', function () {
          $("input:checkbox").prop('checked', $(this).prop("checked"));
        });


        function myFunction() {
          document.getElementById("SubmitForm").submit();
        }





      });
    </script>

</body>

</html>