<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>AdminLTE 2 | Dashboard</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="/css/app.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/dist/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="/dist/ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/dist/adminlte/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="/dist/adminlte/css/skins/skin-blue.min.css">
    <link rel="stylesheet" href="/dist/bootstrap/daterangepicker.css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="/dist/html5shiv.min.js"></script>
    <script src="/dist/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div id="app" class="guxy-app">

    <header class="main-header">
        <app-logo></app-logo>

        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <navbar ref="navbar"></navbar>
            </div>

        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- /.search form -->
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <side-menu></side-menu>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <router-view class="content-wrapper"></router-view>
    <!-- /.content-wrapper -->

    <page-footer></page-footer>

    <app-alert ref="app-alert"></app-alert>
</div>
<!-- ./wrapper -->


<script type="text/javascript" src="/js/{{$package}}/app.js"></script>
<script type="text/javascript" src="/dist/bootstrap/moment-with-locales.min.js"></script>
<script>
    moment.locale('zh-cn');
</script>
<script type="text/javascript" src="/dist/bootstrap/daterangepicker.js"></script>
<script type="text/javascript" src="/dist/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="/dist/ueditor/ueditor.all.min.js"></script>

<!-- FastClick -->
<script src="/dist/fastclick.min.js"></script>
<!-- AdminLTE App -->
<script src="/dist/adminlte/js/adminlte.js"></script>
<!-- SlimScroll 1.3.0 -->
<script src="/dist/jquery.slimscroll.min.js"></script>
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
{!! include_module_validators('admin', $page == 'index' ? '' : $page) !!}

<script type="text/javascript" src="/js/{{$package}}/{{$page}}.js"></script>

</body>
</html>
