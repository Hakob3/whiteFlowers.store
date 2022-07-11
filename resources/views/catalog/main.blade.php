@extends('welcome')
@section('main')

    <main id="main">

        <!-- ======= Наша коллекция Section ======= -->
        <section class="breadcrumbs">
            <div class="container">

                <div class="d-flex justify-content-between align-items-center">
                    <h2>Наша коллекция</h2>
                    <ol>
                        <li><a href="index.html">Главная</a></li>
                        <li>Наша коллекция</li>
                    </ol>
                </div>

            </div>
        </section><!-- End Наша коллекция Section -->

        <!-- ======= Portfolio Section ======= -->
        <section class="portfolio">
            <div class="container">

                <div class="row">
                    <div class="col-lg-12">
                        <ul id="portfolio-flters">
                            <li data-filter="*" class="filter-active">Все</li>

                            <?php
                            foreach ($welcomeData['rubrics'] as $key => $v):
                            ?>
                            <li data-filter=".fl_<?=$v->id?>"><?=$v->name?></li>
                            <?php
                            endforeach;
                            ?>
                        </ul>
                    </div>
                </div>

                <div class="row portfolio-container" data-aos="fade-up" data-aos-easing="ease-in-out"
                     data-aos-duration="500">

                    <?php

                    foreach ($flowersByRubric as $k => $value) :
                    $rubTitle = isset($welcomeData['rubrics'][$k]['id']) ? 'fl_' . $welcomeData['rubrics'][$k]['id'] : '-';
                    foreach ($value as $key => $flower):
                    ?>
                    <div class="col-lg-4 col-md-6 portfolio-wrap <?=$rubTitle?>">
                        <div class="portfolio-item ">

                            <a class="d-block" href="https://whitestudios.ru/images/content/<?=$flower->preview?>">
                                <img src="https://whitestudios.ru/images/content/<?=$flower->preview?>"
                                     class="img-fluid" alt="">
                            </a>
                            <div class="portfolio-info">
                                <div class="bottom">
                                    <div class="pr ">
                                         <span><?=$flower->price?> р.</span>


                                    </div>
                                    <div class="basket add-in-cart" data-id="<?=$flower->id?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php  endforeach;
                    endforeach;
                    ?>


                </div>

            </div>
        </section><!-- End Portfolio Section -->

    </main><!-- End #main -->
@endsection
@section('scripts')
    <link rel="stylesheet" href="/js/slider/dist/simple-lightbox.css"/>
    <script src="/js/slider/dist/simple-lightbox.js"></script>
    <script>
        setTimeout(function () {
            // var lightbox = $('.swiper-slide-duplicate img').simpleLightbox({ /* options */});
            var lightbox = new SimpleLightbox('.portfolio-item a', { /* options */});
        }, 200)
    </script>
@endsection
