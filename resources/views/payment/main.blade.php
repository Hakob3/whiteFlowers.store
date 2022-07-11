@extends('welcome')
@section('main')
    <main id="main">
        <!-- ======= Contact Section ======= -->
        <section class="breadcrumbs">
            <div class="container">

                <div class="d-flex justify-content-between align-items-center">
                    <h2>Оплата</h2>
                </div>

            </div>
        </section>

        <section class="payment-cart" data-aos="fade-up" data-aos-easing="ease-in-out" data-aos-duration="500">
            <div class="container">

                <?php

                if (isset($res['success'])):?>

                <div class="alert alert-success">
                    <?=$res['success']?>
                </div>

                <?php elseif(isset($res['error'])): ?>

                <div class="alert alert-danger">
                    <?=$res['error']?>
                </div>
                <?php else:?>

                    <div class="alert alert-warning">
                        Неизвестная ошибка
                    </div>
                    <?php endif;
                ?>

            </div>
        </section>

    </main>

@endsection

@section('scripts')

@endsection
