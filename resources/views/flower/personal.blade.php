@extends('welcome')
@section('main')
    <main id="main">
        <!-- ======= Contact Section ======= -->
        <?php
        if (isset($resPersonal, $resPersonal['error'])) :?>
        <div class="container pt-4 min-h-250">
            <div class="alert alert-danger text-center d-block mt-4 mb-4"><?php echo $resPersonal['error']?></div>
        </div>
        <?php else :?>
        @include('flower.form')
        <?php endif; ?>
    </main>

    @include('orderForm')
@endsection

@section('scripts')
    <script>
        let bouquetPrice = <?=$flower->price?>;
    </script>
    <script src="/js/flower.js"></script>
@endsection
