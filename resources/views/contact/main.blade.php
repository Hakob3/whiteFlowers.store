@extends('welcome')
@section('main')
    <main id="main">

        <!-- ======= Contact Section ======= -->
        <section class="breadcrumbs">
            <div class="container">

                <div class="d-flex justify-content-between align-items-center">
                    <h2>Контакты</h2>
                </div>

            </div>
        </section><!-- End Contact Section -->

        <!-- ======= Contact Section ======= -->
        <section class="contact" data-aos="fade-up" data-aos-easing="ease-in-out" data-aos-duration="500">
            <div class="container">

                <div class="row">

                    <div class="col-lg-6">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="info-box">
                                    <i class="bx bx-map"></i>
                                    <h3>Наш адресс</h3>
                                    <p><?=$welcomeData['contacts']->address?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box">
                                    <i class="bx bx-envelope"></i>
                                    <h3>Напишите нам</h3>
                                    <p><?=$welcomeData['contacts']->email?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box">
                                    <i class="bx bx-phone-call"></i>
                                    <h3>Позвоните нам</h3>
                                    <p><?=$welcomeData['contacts']->phone?></p>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-lg-6">
                        <form method="post" role="form" class="php-email-form" id="message_form">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <input type="text" name="name" class="form-control" id="name"
                                           placeholder="Ваше имя" required>
                                    <p style="margin: 0" id="err_name"></p>
                                </div>
                                <div class="col-md-6 form-group mt-3 mt-md-0">
                                    <input type="email" class="form-control" name="email" id="email"
                                           placeholder="Ваш E-mail" required>
                                    <p style="margin: 0" id="err_email"></p>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <input type="text" class="form-control" name="subject" id="subject"
                                       placeholder="Тема сообщения" required>
                                <p style="margin: 0" id="err_subject"></p>

                            </div>
                            <div class="form-group mt-3">
                                <textarea class="form-control" name="text" rows="5" placeholder="Сообщение"
                                          required></textarea>
                                <p style="margin: 0" id="err_text"></p>

                            </div>
                            <div class="my-3">
                                <div class="loading">Загрузка...</div>
                                <div class="sent-message">Ваше сообщение отправлено. Спасибо!</div>
                            </div>
                            <div class="text-center">
                                <button type="submit">Отправить сообщение</button>
                            </div>
                        </form>
                    </div>

                </div>

            </div>
        </section><!-- End Contact Section -->

        <!-- ======= Map Section ======= -->
        <section class="map mt-2">
            <div class="container-fluid p-0">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2245.1561507189067!2d37.641064116088806!3d55.755789599228855!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46b54af501cb4b5f%3A0xfa6b6391f6247b33!2z0KXQvtGF0LvQvtCy0YHQutC40Lkg0L_QtdGALiwgNy85INGB0YLRgNC-0LXQvdC40LUgMywg0JzQvtGB0LrQstCwLCAxMDEwMDA!5e0!3m2!1sru!2sru!4v1618488320529!5m2!1sru!2sru" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </section><!-- End Map Section -->

    </main><!-- End #main -->
@endsection
