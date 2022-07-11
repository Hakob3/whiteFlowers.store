<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo csrf_token(); ?>">
    <meta name="keywords"
          content="wrappixel, admin dashboard, html css dashboard, web dashboard, bootstrap 5 admin, bootstrap 5, css3 dashboard, bootstrap 5 dashboard, Ample lite admin bootstrap 5 dashboard, frontend, responsive bootstrap 5 admin template, Ample admin lite dashboard bootstrap 5 dashboard template">
    <meta name="description"
          content="Ample Admin Lite is powerful and clean admin dashboard template, inpired from Bootstrap Framework">
    <meta name="robots" content="noindex,nofollow">
    <title>WFlowers Admin</title>



    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <link rel="canonical" href="https://www.wrappixel.com/templates/ample-admin-lite/"/>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="/admin/plugins/images/favicon.png">
    <!-- Custom CSS -->
    <link href="/admin/plugins/bower_components/chartist/dist/chartist.min.css" rel="stylesheet">
    <link rel="stylesheet"
          href="/admin/plugins/bower_components/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.css">
    <!-- Custom CSS -->
    <link href="/admin/css/style.min.css" rel="stylesheet">
    <link href="/css/adminStyles.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
</head>

<body>
<!-- ============================================================== -->
<!-- Preloader - style you can find in spinners.css -->
<!-- ============================================================== -->
<div class="preloader">
    <div class="lds-ripple">
        <div class="lds-pos"></div>
        <div class="lds-pos"></div>
    </div>
</div>
<!-- ============================================================== -->
<!-- Main wrapper - style you can find in pages.scss -->
<!-- ============================================================== -->
<div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full"
     data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">
    <!-- ============================================================== -->
    <!-- Topbar header - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <header class="topbar" data-navbarbg="skin5">
        <nav class="navbar top-navbar navbar-expand-md navbar-dark">
            <div class="navbar-header" data-logobg="skin6">

                <a class="navbar-brand" href="/home">

                    <span class="logo-text">
                            <!-- dark Logo text -->
                            <img style="max-width: 100%" src="/images/logo-flowers-black.svg" alt="homepage"/>
                        </span>
                </a>

                <a class="nav-toggler waves-effect waves-light text-dark d-block d-md-none"
                   href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
            </div>
            <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">

                <ul class="navbar-nav ms-auto d-flex align-items-center">
                    <li class=" in">
                        <div class="app-search d-none d-md-block me-3">
                            <input type="text" id="searchByOrderId" placeholder="Поиск по номеру заказа..."
                                   class="form-control mt-0">
                            <a href="" class="active">
                                <i class="fa fa-search"></i>
                            </a>
                        </div>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="bg-transparent no-border">
                                <img src="/images/logout.svg?v=3" alt="user-img" width="36"
                                     class="img-circle"><span class="text-white font-medium"></button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <aside class="left-sidebar" data-sidebarbg="skin6">
        <!-- Sidebar scroll-->
        <div class="scroll-sidebar">
            <!-- Sidebar navigation-->
            <nav class="sidebar-nav">
                <ul id="sidebarnav">
                    <!-- User Profile-->
                    <li class="sidebar-item pt-2">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/admin/orders"
                           aria-expanded="false">
                            <i class="fas fas fa-align-left" aria-hidden="true"></i>
                            <span class="hide-menu">Заказы</span>
                        </a>
                    </li>


                    <li class="sidebar-item pt-2">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/admin/flowers"
                           aria-expanded="false">
                            <i class="fas fa-asterisk" aria-hidden="true"></i>
                            <span class="hide-menu">Цветы</span>
                        </a>
                    </li>

                    <li class="sidebar-item pt-2">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/admin/collections"
                           aria-expanded="false">
                            <i class="fas fa-indent" aria-hidden="true"></i>
                            <span class="hide-menu">Рубрики</span>
                        </a>
                    </li>
                    <li class="sidebar-item pt-2">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/admin/couriers"
                           aria-expanded="false">
                            <i class="fas fa-indent" aria-hidden="true"></i>
                            <span class="hide-menu">Курьеры</span>
                        </a>
                    </li>

                </ul>

            </nav>
        </div>
    </aside>
    <div class="page-wrapper">
        @yield('content')
        <footer class="footer text-center"> 2021 © WhiteFlowers Admin brought to you by
        </footer>
    </div>
</div>

<script src="/admin/plugins/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="/admin/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="/admin/js/app-style-switcher.js"></script>
<script src="/admin/plugins/bower_components/jquery-sparkline/jquery.sparkline.min.js"></script>
<!--Wave Effects -->
<script src="/admin/js/waves.js"></script>
<!--Menu sidebar -->
<script src="/admin/js/sidebarmenu.js"></script>
<!--Custom JavaScript -->
<script src="/admin/js/custom.js"></script>
<!--This page JavaScript -->
<!--chartis chart-->
<script src="/admin/plugins/bower_components/chartist/dist/chartist.min.js"></script>
<script src="/admin/plugins/bower_components/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js"></script>

<script src="/js/notify.js"></script>
<script src="/js/main_functions.js"></script>

<script>

    $('#searchByOrderId').on('change', function () {
        location.replace('/admin/order/' + $(this).val())
    })

</script>


@yield('scripts')
</body>

</html>
