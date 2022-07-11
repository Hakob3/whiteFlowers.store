@extends('welcome')
<?php
if (isset($order->multy_order) && !empty($order->multy_order)) :

    $orderId = $order->multy_order;
else:
    $orderId = isset($order->id) ? $order->id : 0;
endif;?>
@section('main')
    <main id="main">
        <section class="breadcrumbs">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>Оплата </h2>
                </div>
            </div>
        </section>
        <section id="errorMessages">

        </section>
        <section class="orderPay">
            <div class="container">
                <?php

                if (isset($res['error'])) : ?>
                <div class="alert-danger alert">
                    <?=$res['error']?>
                </div>
                <?php else : ?>
                <div class="row-form" id="rowForm">
                    <div class="success">
                        <h3>Спасибо <?=$order->first_name . ' ' . $order->last_name?></h3>
                        <p>Ваш заказ <strong> # <?= $orderId?> </strong>
                            успешно получен</p>
                        <p>Общая сумма заказа
                            <strong> <?= intval($order->item_price) + intval($order->delivery_price) ?></strong> руб.
                        </p>
                        <div class="btn-pay">
                            <button class="btn btn-blue" id="orderPayBtn">оплатить</button>
                        </div>
                        <div class="rulesSuccess">
                            <?= $rules?>
                        </div>
                        <div class="mb-4 text-right">
                            <img src="/images/cards.png" class="cardsImages" alt="visa, mastercard, мир">
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

@endsection

@section('scripts')

    <script>


        let orderId = '<?= $orderId?>';
        $('#orderPayBtn').on('click', function () {
            $.ajax({
                method: 'POST',
                data: {
                    fingerprint: setLocalStorageIfNotExist('fingerprint'),
                    orderId: orderId,
                    isSingle: <?= (isset($order->multy_order) && !empty($order->multy_order)) ? 'false':'true'?>
                },
                url: '/orderPay',
                success: function (data) {
                    let resp = $.parseJSON(data);
                    if (resp.error) {
                        if (typeof resp.error === 'string') {
                            $('#errorMessages').html(`<div class="alert alert-danger">${resp.error}</div>`)
                        }

                    } else {
                        if (resp.redirectLink) {
                            location.replace(resp.redirectLink);
                        } else {
                            $('#errorMessages').html(`<div class="alert alert-danger">
                        <p>К сожалению, у нас есть ошибка из банка.</p>
                        <p>Пожалуйста, свяжитесь с нашей службой поддержки, чтобы настроить оплату</p>
                        </div>`)
                        }
                    }
                    setTimeout(function () {
                        $('#errorMessages').html('')
                    }, 6600)
                }
            });
        })
    </script>
@endsection
