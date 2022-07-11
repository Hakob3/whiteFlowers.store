@extends('welcome')
@section('main')
    <main id="main">
        <!-- ======= Contact Section ======= -->
        <section class="breadcrumbs">
            <div class="container">

                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="tx45">Заказать</h2>
                    <ol>
                        <li><a href="/" class="tx45">Назад</a></li>
                    </ol>
                </div>

            </div>
        </section>
        <section id="errorMessages" class="p-0">
        </section>


        @include('flower.form')


    </main>

    @include('orderForm')
@endsection

@section('scripts')
    <script>
        let bouquetPrice = <?=$flower->price?>;
    </script>
    <script src="/js/flower.js"></script>
@endsection
