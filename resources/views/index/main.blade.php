@extends('welcome')
@section('main')
    {{--    <section id="hero" class="d-flex justify-cntent-center align-items-center">--}}
    {{--        <div id="heroCarousel" class="container carousel carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">--}}
    {{--            <!-- Slide 1 -->--}}
    {{--            <?php--}}
    {{--            $i = 0;--}}
    {{--            foreach ($welcomeData['rubrics'] as $key => $rubric) :--}}

    {{--            ?>--}}
    {{--            <div class="carousel-item <?= ($i === 0? 'active' : '')?>">--}}
    {{--                <div class="carousel-container">--}}
    {{--                    <h2 class="animate__animated animate__fadeInDown"><?=$rubric['name']?></h2>--}}
    {{--                    <p class="animate__animated animate__fadeInUp">Ut velit est quam dolor ad a aliquid qui aliquid.--}}
    {{--                        Sequi--}}
    {{--                        ea ut et est quaerat sequi nihil ut aliquam. Occaecati alias dolorem mollitia ut. Similique ea--}}
    {{--                        voluptatem. Esse doloremque accusamus repellendus deleniti vel. Minus et tempore modi--}}
    {{--                        architecto.</p>--}}
    {{--                    <a href="/#<?=$rubric['id']?>" class="btn-get-started animate__animated animate__fadeInUp">Посмотреть</a>--}}
    {{--                </div>--}}
    {{--            </div>--}}

    {{--            <?php $i++;--}}
    {{--            endforeach;--}}
    {{--            ?>--}}


    {{--            <a class="carousel-control-prev" href="#heroCarousel" role="button" data-bs-slide="prev">--}}
    {{--                <span class="carousel-control-prev-icon bx bx-chevron-left" aria-hidden="true"></span>--}}
    {{--            </a>--}}

    {{--            <a class="carousel-control-next" href="#heroCarousel" role="button" data-bs-slide="next">--}}
    {{--                <span class="carousel-control-next-icon bx bx-chevron-right" aria-hidden="true"></span>--}}
    {{--            </a>--}}

    {{--        </div>--}}
    {{--    </section><!-- End Hero -->--}}
    <main id="main">
        <!-- ======= Why Us Section ======= -->

        <div class="container mob-p-0">
            <?php

            $bannerKey = 0;
            foreach ($flowersByRubric as $k => $value):
            $rubTitle = isset($welcomeData['rubrics'][$k]['name']) ? $welcomeData['rubrics'][$k]['name'] : '-';
            if ($bannerKey === 0 && isset($banners[0])):?>
            <section class="why-us section-bg p-0 rel-pos">
                <a href="/personal/<?php echo $banners[0]->link?>" class="banner-personal">
                    <div class="max-img ">
                        <img src="<?php echo $banners[0]->image?>" class="img-fluid" alt="img1"/>
                    </div>
                    <button class="btn btn-get abs"><?php echo $banners[0]->button_text?></button>
                </a>
            </section>
            <?php endif;?>
            <section class="team" id="collection<?=$k?>" data-aos="fade-up" data-aos-easing="ease-in-out"
                     data-aos-duration="500">

                <div class="section-title">
                    <h2><?=$rubTitle?></h2>
                    <?php
                    if(isset($welcomeData['rubrics'][$k]['text']) && !empty($welcomeData['rubrics'][$k]['text'])) :
                    ?>
                    <p><?=$welcomeData['rubrics'][$k]['text']?></p>
                    <?php endif; ?>
                </div>
                <div class="row">
                    <?php
                    foreach ($value as $key => $flower):

                    ?>
                    <div class="col-lg-4 col-md-6 d-flex align-items-stretch">
                        <div class="member">
                            <div class="member-img">
                                <a href="/flower/<?=$flower->uri?>" data-id="<?=$flower->id?>">
                                    <img class="img-fluid"
                                         src="<?=$flower->preview?>">
                                </a>
                                <div class="social">
                                    <div class="pr">
                                        <span class="rub-icon-price"><?=$flower->price?></span>

                                    </div>
                                    <div class="basket add-in-cart" data-id="<?=$flower->id?>">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <?php  endforeach;?>
                </div>
            </section>


            <?php


            if ($bannerKey === (count($flowersByRubric) - 1) && isset($banners[1]) ) {
            ?>
            <section class="why-us section-bg p-0 rel-pos mb-5">
                <a href="/personal/<?php echo $banners[1]->link?>" class="banner-personal">
                    <div class="max-img ">
                        <img src="<?php echo $banners[1]->image?>" class="img-fluid" alt="img1"/>
                    </div>
                    <button class="btn btn-get abs"><?php echo $banners[1]->button_text?></button>
                </a>
            </section>
            <?php
            }
                $bannerKey ++;
            endforeach; ?>

        </div>
    </main>
@endsection
