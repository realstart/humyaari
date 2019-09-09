<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Fly Solution | Billing</title>

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <meta name="robots" content="noindex, follow">

  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <!-- Bucket styles start -->
  {{--<link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">--}}
  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
  
  <link href="{{ asset('js/jquery-ui/jquery-ui-1.10.1.custom.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/bootstrap-reset.css') }}" rel="stylesheet">
  <link href="{{ asset('css/font-awesome.css') }}" rel="stylesheet">
  <link href="{{ asset('js/jvector-map/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet">
  <link href="{{ asset('css/clndr.css') }}" rel="stylesheet">
  <link href="{{ asset('css/ba-styles.css') }}" rel="stylesheet">
  <link href="{{ asset('libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" >
  <!-- <link href="{{ asset('js/morris-chart/morris.css') }}" rel="stylesheet" > -->


  <!-- Bucket styles end -->

  <!-- App -->
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">

  <style>   
      .edit-modal .v--modal-box .v--modal{
         top: 38px;
         height: 300px;
         left: 18%;
        
      }
  </style>
 
</head>
<body class="login-body">
  <div id="app">
    @yield('content')
  </div>
  <!-- ./wrapper -->


  <!-- Bucket styles start -->

  <script src="{{ asset('js/jquery.js') }}"></script>
  <script src="{{ asset('js/jquery-ui/jquery-ui-1.10.1.custom.min.js') }}"></script>
  <script src="{{ asset('js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('js/jquery.dcjqaccordion.2.7.js') }}"></script>
  <script src="{{ asset('js/jquery.scrollTo.min.js') }}"></script>
  <script src="{{ asset('js/jQuery-slimScroll-1.3.0/jquery.slimscroll.js') }}"></script>
  <script src="{{ asset('js/jquery.nicescroll.js') }}"></script>
  <!--[if lte IE 8]><script language="javascript" type="text/javascript" src="js/flot-chart/excanvas.min.js"></script><![endif]-->
  <script src="{{ asset('js/skycons/skycons.js') }}"></script>
  <script src="{{ asset('js/jquery.scrollTo/jquery.scrollTo.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
  <script src="{{ asset('js/calendar/clndr.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.5.2/underscore-min.js"></script>
  <script src="{{ asset('js/calendar/moment-2.2.1.js') }}"></script>
  {{--<script src="{{ asset('js/evnt.calendar.init.js') }}"></script>--}}
  <script src="{{ asset('js/jvector-map/jquery-jvectormap-1.2.2.min.js') }}"></script>
  <script src="{{ asset('js/jvector-map/jquery-jvectormap-us-lcc-en.js') }}"></script>
  <script src="{{ asset('js/gauge/gauge.js') }}"></script>
  <script src="{{ asset('js/scripts.js') }}"></script>
  <script src="{{ asset('libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.js') }}"></script>
  <!-- Bucket styles end -->

  <script src="https://cdn.jsdelivr.net/npm/vue@2.5.17/dist/vue.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/vee-validate/2.1.1/vee-validate.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

  <!--Easy Pie Chart-->
<script src="{{ asset('js/easypiechart/jquery.easypiechart.js') }}"></script>
<!--Sparkline Chart-->
<!-- <script src="{{ asset('js/sparkline/jquery.sparkline.js') }}"></script> -->
<!--Morris Chart-->
<!-- <script src="{{ asset('js/morris-chart/morris.js') }}"></script>
<script src="{{ asset('js/morris-chart/raphael-min.js') }}"></script>  -->
<!--jQuery Flot Chart-->
<!-- <script src="{{ asset('js/flot-chart/jquery.flot.js') }}"></script>
<script src="{{ asset('js/flot-chart/jquery.flot.tooltip.min.js') }}"></script>
<script src="{{ asset('js/flot-chart/jquery.flot.resize.js') }}"></script>
<script src="{{ asset('js/flot-chart/jquery.flot.pie.js') }}"></script>
<script src="{{ asset('js/flot-chart/jquery.flot.animator.min.js') }}"></script>
 <script src="{{ asset('js/flot-chart/jquery.flot.growraf.js') }}"></script> -->

  <script src="{{ asset('public/js/app.js') }}"></script>

</body>
</html>
