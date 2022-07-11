<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>{{ config('app.name', 'WhiteFlowers') }}</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <!-- Favicons -->
    <link href="/images/bullet-flower.png" rel="icon">
    <link href="/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,700,700i&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="/assets/vendor/animate.css/animate.min.css" rel="stylesheet">
    <link href="/assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <link rel="stylesheet" href="/css/bootstrap.css"/>
    <!-- Template Main CSS File -->
    <link href="/assets/css/style.css?v=<?php echo rand(1, 5000)?>" rel="stylesheet">

    <!-- =======================================================
    * Template Name: Moderna - v4.1.0
    * Template URL: https://bootstrapmade.com/free-bootstrap-template-corporate-moderna/
    * Author: BootstrapMade.com
    * License: https://bootstrapmade.com/license/
    ======================================================== -->

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo csrf_token(); ?>">

    <link rel="stylesheet" href="/css/styles.css?fg=<?= rand(1, 333)?>"/>
</head>

<body>

<!-- ======= Header ======= -->
<header id="header" class="fixed-top  align-items-center header-transparent">
    <div class="container d-flex justify-content-between align-items-center mob-p-0">

        <span></span>
        <div class="logo">
            <h1 class="text-light d-flex justify-content-center">
                <a href="/" class="text-center d-block w-100 m-0-auto mt-2">
                    <img src="/images/logo-flowers-white.svg?s=sa" class="logo-img"/>
                    {{--                    <img src="/images/logo-flowers-white.svg?s=sa" class="logo-img"/>--}}
                </a>
            </h1>
            <!-- Uncomment below if you prefer to use an image logo -->
            <!-- <a href="index.html"><img src="/assets/img/logo.png" alt="" class="img-fluid"></a>-->
        </div>
        <div class="row">
            <div class="whatsapp-icon col-6">
                <a href="">
                    <img src="/images/icons8-whatsapp.svg" class="header-icons" alt="">
                </a>
            </div>
            <div class="cart-icon col-6">
                <a href="">
                    <img src="/images/fi__cart.svg" class="header-icons" alt="">
                </a>
            </div>
        </div>
        {{--        <nav id="navbar" class="navbar">--}}
        {{--        <ul class="list-style-none">--}}

        {{--            --}}{{--                <li><a class="a" href="/catalog">Коллекция</a></li>--}}

        {{--            <li>--}}
        {{--                <div class="basket">--}}
        {{--                    <a href="/cart" class="cart-basket max-img d-block">--}}
        {{--                        <span id="cart_item_count">0</span>--}}
        {{--                        <img src="/images/icons8-whatsapp.svg" alt="">--}}
        {{--                        <img src="/images/fi__cart.svg" alt="">--}}
        {{--                    </a>--}}
        {{--                </div>--}}
        {{--            </li>--}}
        {{--        </ul>--}}
        {{--            <i class="bi bi-list mobile-nav-toggle"></i>--}}
        {{--        </nav>--}}

    </div>
    <div class="container">
        <div class="d-flex header-bottom  justify-content-center">
            <div class="header-bottom-box text-white">
                Букеты на любой<br>
                бюджет от 1.500р
            </div>
            <div class="header-bottom-box text-white">
                Быстря доставка по<br>
                Москве и МО
            </div>
            <div class="header-bottom-box text-white">
                Озеленение и декор<br>
                мероприятий
            </div>
        </div>
    </div>
</header><!-- End Header -->

<!-- ======= Hero Section ======= -->
@yield('main')
<!-- ======= Footer ======= -->
<footer id="footer" data-aos="fade-up" data-aos-easing="ease-in-out" data-aos-duration="500">

    {{--    <div class="footer-newsletter">--}}
    {{--        <div class="container">--}}
    {{--            <div class="row">--}}
    {{--                <div class="col-lg-6">--}}
    {{--                    <h4>Our Newsletter</h4>--}}
    {{--                    <p>Tamen quem nulla quae legam multos aute sint culpa legam noster magna</p>--}}
    {{--                </div>--}}
    {{--                <div class="col-lg-6">--}}
    {{--                    <form action="" method="post">--}}
    {{--                        <input type="email" name="email"><input type="submit" value="Subscribe">--}}
    {{--                    </form>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}

    <div class="footer-top">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-md-6 col-xs-6 col-sm-6 footer-address">
                    <h2>WHITE FLOWERS BAR</h2>
                    <p>
                        Хохловский переулок 7-9с3
                        <br>
                        Ежедневно 11-21:00
                        {{--                        <?=$welcomeData['contacts']->address?><br>--}}
                        {{--                        <strong>Тел.</strong> <?=$welcomeData['contacts']->phone?><br>--}}
                        {{--                        <strong>Email:</strong> <?=$welcomeData['contacts']->email?><br>--}}
                    </p>
                </div>
                <div class="col-lg-3 col-md-6  col-xs-6 col-sm-6 footer-contact">
                    <p>
                        8 (925) 002-96-36
                        <br>
                        wfbar@yandex.ru
                    </p>
                    <div class="d-flex footer-icons justify-content-right">
                        <div class="col-x">
                            <a href="">
                                <img src="/images/icons8-whatsapp.svg" alt="" class="">
                            </a>
                        </div>
                        <div class="col-x">
                            <a href="">
                                <img src="/images/icons8-instagram.svg" alt="" class="">
                            </a>
                        </div>
                    </div>

                    {{--                    <h4>Мы на связи</h4>--}}
                    {{--                    <ul>--}}
                    {{--                        <li><a class="a" href="/contact"><i class="bx bx-chevron-right"></i> Контакты</a></li>--}}
                    {{--                        <li><i class="bx bx-chevron-right"></i> пн - вс</li>--}}
                    {{--                        <li><i class="bx bx-chevron-right"></i> 11:00 - 21:00</li>--}}
                    {{--                    </ul>--}}
                </div>

                {{--                <div class="col-lg-3 col-md-6 col-xs-6 col-sm-6 footer-links">--}}
                {{--                    <h4>Наши коллекции</h4>--}}
                {{--                    <ul>--}}
                {{--                        <?php--}}
                {{--                        foreach ($welcomeData['rubrics'] as $key => $v):--}}
                {{--                        ?>--}}

                {{--                            <li><i class="bx bx-chevron-right"></i> <a href="/#collection<?=$v->id?>"><?=$v->name?></a></li>--}}
                {{--                        <?php--}}
                {{--                        endforeach;--}}
                {{--                        ?>--}}

                {{--                    </ul>--}}
                {{--                </div>--}}



                {{--                <div class="col-lg-3 col-md-6 col-xs-6 col-sm-6 footer-info">--}}
                {{--                    <h3>О нас </h3>--}}
                {{--                    <p>Выгодные цены. Быстрая доставка. Москва и МО. Заказывайте! Только свежие цветы. На любой бюджет. Безопасная оплата. Доставка 24 часа. Типы: Розы, Тюльпаны.</p>--}}
                {{--                    <div class="social-links mt-3">--}}
                {{--                        <a href="#" class="twitter"><i class="bx bxl-twitter"></i></a>--}}
                {{--                        <a href="#" class="facebook"><i class="bx bxl-facebook"></i></a>--}}
                {{--                        <a href="https://instagram.com/whiteflowers_bar/" class="instagram"><i class="bx bxl-instagram"></i></a>--}}
                {{--                        <a href="#" class="linkedin"><i class="bx bxl-linkedin"></i></a>--}}
                {{--                    </div>--}}
                {{--                </div>--}}

            </div>
        </div>
    </div>

    <div class="container">
        <div class="copyright">
            &copy; Copyright <strong><span>WhtieStudios</span></strong>. Все права защищены
        </div>
    </div>
</footer><!-- End Footer -->

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
        class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<script src="/assets/vendor/aos/aos.js"></script>
<script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/assets/vendor/glightbox/js/glightbox.min.js"></script>
<script src="/assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
<script src="/assets/vendor/php-email-form/validate.js"></script>
<script src="/assets/vendor/purecounter/purecounter.js"></script>
<script src="/assets/vendor/swiper/swiper-bundle.min.js"></script>
<script src="/assets/vendor/waypoints/noframework.waypoints.js"></script>

<!-- Template Main JS File -->
<script src="/assets/js/main.js"></script>


<script type="" src="/js/jquery-3.6.0.min.js"></script>
<script type="" src="/js/bootstrap.js"></script>
<script type="" src="/js/bootstrap.bundle.js"></script>
<script type="" src="/js/main_functions.js"></script>
<script type="" src="/js/main.js"></script>

@yield('scripts')

<script src="/js/message.js"></script>
</body>

</html>



