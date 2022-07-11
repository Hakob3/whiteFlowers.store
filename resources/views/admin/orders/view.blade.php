@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <?php
        if (!empty($order) ) :
        ?>


        <div class="white-box">
            <h3 class="box-title">Заказ #<?= $order->id?></h3>

            <div class="row">
                <div class="col-xs-12 col-sm-5">
                    <div class="table-responsive ">
                        <table class="order-table">
                            <?php  $totalPrice = 0; foreach ($positions as $position) : $totalPrice += intval($position->price) ?>
                            <tr>
                                <td>
                                    <img src="/<?= $position->preview?>" alt="pr"
                                         style="width: 60px"/>
                                </td>
                                <td>
                                    <p><?= $position->name?></p>
                                    <p><?= $position->price?> руб.</p>
                                </td>
                            </tr>

                            <?php endforeach;

                            ?>

                            <tr>
                                <td>
                                    <img src="/images/customer.svg" alt="pr"
                                         style="width: 60px"/>
                                </td>
                                <td>
                                    <p><small>ИМЯ ФАМИЛИЯ</small><?= $order->first_name . ' ' . $order->last_name?></p>
                                    <p><small>E-MAIL</small><?= $order->email?> </p>
                                    <p><small>ТЕЛЕФОН</small><?= $order->phone?> </p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="/images/truck.svg" alt="pr"
                                         style="width: 60px"/>
                                </td>
                                <td>
                                    <p><small>ТИП
                                            ДОСТАВКИ</small><?=  $order->delivery_type === 'pickup' ? 'Самовывоз' : 'Доставка'?>
                                    </p>
                                    <p><small>ДАТА ВРЕМЯ </small><?=$order->order_date?> |
                                        <span class="date-from"><?=  rtrim($order->time_from, ':00')?>:00 </span>
                                        -
                                        <span class="date-to"><?=  rtrim($order->time_to, ':00') ?>:00</span></p>


                                    <p><small>АДРЕС ДОСТАВКИ</small><?= $order->delivery_address    ?> </p>
                                    <p><small>БЛИЖАЙШАЯ СТАНЦИЯ МЕТРО</small> <?= $order->nearest_m_station ?></p>
                                    <p> <?= (($order->mkad === 'in') ? 'Внутри МКАД' : 'За пределами МКАД') ?></p>
                                    <p><small>СТОИМОСТЬ ДОСТАВКИ</small> <?= $order->delivery_price ?></p>
                                </td>
                            </tr>
                            <?php
                            if($order->delivery_type !== 'pickup') :
                            ?>
                            <tr>
                                <td>
                                    <img src="/images/recipient.svg" alt="pr"
                                         style="width: 60px"/>
                                </td>
                                <td>
                                    <p><small>ИМЯ ПОЛУЧАТЕЛЯ</small><?=  $order->receiver_name?></p>
                                    <p><small>КОНТАКТ ПОЛУЧАТЕЛЯ</small><?=  $order->recipient_contact?></p>
                                    <p><small>ПОДПИСЬ К ОТКРЫТКЕ</small><?=  $order->postcard_signature?></p>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <img src="/images/bill.svg" alt="pr"
                                         style="width: 60px"/>

                                </td>
                                <td>
                                    <p>
                                        <small>Общая сумма</small>
                                        <?=$totalPrice?> + <?=$order->delivery_price?>
                                        = <?=intval($order->item_price) + intval($order->delivery_price)?> руб
                                    </p>
                                    <p>
                                        <small>
                                            Статус оплаты
                                        </small>
                                        <?= ($order->status === 'payed' ? 'Оплачен' : 'Ждет оплаты') ?>
                                    </p>
                                    <?php
                                    if ($order->status === 'payed'):
                                    ?>
                                    <p>
                                        <small>
                                            Дата платежа
                                        </small>
                                        <?= $order->pay_date ?>
                                    </p>
                                    <?php
                                    endif;
                                    ?>
                                </td>
                            </tr>

                            <?php
                            endif;
                            ?>

                        </table>


                    </div>
                </div>
                <div class="col-xs-12 col-sm-5">
                    <div class="card ">
                        <form id="editOrderForm">
                            <div class="form-group">
                                <label for="courier">Выберите курьера</label>
                                <input type="hidden" value="<?=  $order->id?>" id="order_id"/>
                                <select id="courier"
                                        name="courier"
                                        class="form-control form-control-sm">
                                    <?php
                                    $selected = false;
                                    foreach ($couriers as $key => $courier){
                                    ?>
                                    <option
                                        <?php
                                        if ($courier->id == $order->courier) {
                                            echo 'selected';
                                            $selected = true;
                                        }
                                        ?> value="<?=$courier->id?>"><?=$courier->name?></option>
                                    <?php
                                    }

                                    if(!$selected) {
                                    ?>
                                    <option selected value="0">Выберите курьера</option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="comment_for_manager">Комментарий для менеджера</label>
                                <textarea id="comment_for_manager"
                                          name="comment_manager"
                                          class="form-control form-control-sm"><?= $order->comment_manager?></textarea>
                            </div>
                            <div class="text-center">
                                <button class="btn btn-success" id="editOrder">Редактировать</button>
                            </div>
                        </form>
                    </div>

                </div>
                <div class="col-xs-12 col-sm-2">
                    <?php
                    if($order->ms_id !== '')  :
                    ?>
                    Успешно сохранено в
                    <a href="https://online.moysklad.ru/app/#customerorder/edit?id=<?=$order->ms_id?>">
                        <img width="80px"
                             src="https://www.moysklad.ru/local/templates/moysklad-new/images/header-logo.svg"></a>
                    <?php                  else : ?>
                    <div class="text-right">
                        <a class="btn btn-success" href="/admin/order-to-ms/<?=  $order->id?>"
                           data-id="<?=  $order->id?>">Занести в МойСклад</a>
                    </div>
                    <?php endif;
                    ?>
                </div>
            </div>
        </div>
        <?php
        else :
        ?>
        <div class="alert-danger alert text-center">пусто</div>
        <?php
        endif;
        ?>

    </div>
@endsection
@section('scripts')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#editOrderForm').on('submit', function (e) {
            e.preventDefault();
            let sendData = {
                courier: $('#courier').val(),
                comment_manager: $('#comment_for_manager').val(),
                order_id: $('#order_id').val(),
            }
            $.ajax({
                method: 'POST',
                data: sendData,
                url: '/editOrder',
                success: function (resp) {
                    let data = $.parseJSON(resp);
                    if (data.error) {
                        if (typeof data.error === 'object') {
                            $.each(data.error, function (key, val) {
                                if ($('#' + key).length > 0) {
                                    $('#' + key).addClass('error')
                                }
                            })
                        } else {
                            alert(data.error)
                        }
                    } else {
                        if (data.success) {
                            location.reload()
                        }
                    }
                }
            });
        })
    </script>
@endsection
