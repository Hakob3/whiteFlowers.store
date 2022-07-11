@extends('welcome')
@section('main')
    <main id="main">
        <section class="breadcrumbs">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>Товары в корзине</h2>
                </div>
            </div>
        </section>
        <section class="services">
            <form>
                <div class="container">
                    <div class="">
                        <a href="/cart-single"> Заказ на общий адрес </a>
                        <a href="/cart-multy"> Заказать по разным адресам</a>
                    </div>
                    <div class="row">
                        <?php
                        $classes = [
                            "icon-box-pink",
                            "icon-box-blue",
                            "icon-box-cyan",
                            "icon-box-green",
                        ];
                        $totalPrice = 0;
                        $className = 'col-lg-6 height370';

                        if (count($cartItems) === 1) {
                            $className = ' col-lg-12 height370 ';
                        } elseif (count($cartItems) === 2) {
                            $className = ' col-lg-6 height370 ';
                        }
                        foreach ($cartItems as $key => $val) :
                        ?>
                        <div class="col-md-6 <?=$className?> d-flex p-item align-items-stretch aos-init aos-animate"
                             data-aos="fade-up">
                            <div class="icon-box p-0 flower-cart cart-item <?= $classes[$key % 4]?>">
                                <div class="cancel-div">
                                    <span class="cancel-item" data-id="<?=$val->id?>"></span>
                                </div>
                                <div class="top-img max-img">
                                    <img src="https://whitestudios.ru/images/content/<?=$val->preview?>" alt="item"/>
                                </div>
                                <div class="bottom-bar p-3">
                                    <table class="w-100 f-13 table table-sm table-borderless ">

                                        <?php
                                        if (isset($variants[$val->fId]) && !empty($variants[$val->fId]) ) :

                                        ?>

                                        <tr>
                                            <td colspan="100%">
                                                <div class="d-flex justify-content-center flex-wrap ">
                                                    <?php
                                                    $i = 0;
                                                    foreach ($variants[$val->fId] as $k => $variant):
                                                    ?>
                                                    <div class="d-inline-block l-200px ml-2 mr-2 mt-1 mb-1">
                                                        <div class="hidden-f-radio">
                                                            <input type="radio"
                                                                   <?php echo($i === 0 ? 'checked' : '') ?> value="<?= $variant->id ?>"
                                                                   name="variant<?= $val->id ?>"
                                                                   data-price="<?= $variant->price?>"
                                                                   id="variant<?= $variant->id?>"/>
                                                            <label class="text-left"
                                                                   for="variant<?= $variant->id?>"><?=$variant->name?></label>
                                                        </div>
                                                    </div>
                                                    <?php $i++;
                                                    endforeach;
                                                    ?>
                                                </div>
                                            </td>
                                            <?php
                                            endif;
                                            ?>
                                        </tr>
                                        <tr class="text-left">


                                            <td>
                                                <div class="form-group m-0">

                                                    <label class="forField"
                                                           for="flowersDate<?= $val->id ?>">Дата</label>
                                                    <input type="date" class="form-control form-control-sm"
                                                           id="flowersDate<?= $val->id ?>"
                                                           name="date<?= $val->id ?>" value="" min="2021-04-22">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group m-0">
                                                    <div><label class="forField"
                                                                for="flowersTime<?= $val->id ?>">Время</label></div>

                                                    <div class="input-group">
                                                        <input type="time" class="form-control form-control-sm"
                                                               id="flowersTime<?= $val->id ?>"
                                                               name="time<?= $val->id ?>"
                                                               value="17:00" placeholder="с"
                                                               maxlength="5">
                                                        <span class="input-group-addon">-</span>
                                                        <input class="form-control form-control-sm" type="time"
                                                               id="flowersTimeTo<?= $val->id ?>"
                                                               name="timeTo<?= $val->id ?>" value="22:00"
                                                               placeholder="до">
                                                    </div>

                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="100%">
                                                <div class="form-group m-0">
                                                    <select name="delivery_type<?= $val->id ?>"
                                                            class="form-control form-control-sm">
                                                        <option value="in" selected>Внутри МКАД</option>
                                                        <option value="common">На общий адрес</option>
                                                        <option value="showroom">Самовывоз</option>

                                                        <option value="out">За пределами МКАД</option>
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr data-toggle-address="<?=$val->id?>" class="text-left">
                                            <td>
                                                <div class="form-group m-0">
                                                    <label for="flowersAddress<?=$val->id?>">Адрес доставки</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                           id="flowersAddress<?=$val->id?>"
                                                           name="address<?=$val->id?>" value="">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group m-0">
                                                    <label for="flowersMetro<?=$val->id?>">Ближайшая станция
                                                        метро</label>
                                                    <select class="form-control form-control-sm"
                                                            id="flowersMetro<?=$val->id?>"
                                                            name="metro<?=$val->id?>">
                                                        <option value=""></option>
                                                        <?php
                                                        foreach ($metroStations as $m_key => $metroStation):
                                                        ?>
                                                        <option value="<?=$metroStation?>"><?=$metroStation?></option>
                                                        <?php
                                                        endforeach;
                                                        ?>
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="text-left">
                                            <td>
                                                <div class="form-group m-0">
                                                    <label class="flowered" for="flowersToWhom<?=$val->id?>">Контакт
                                                        получателя</label>
                                                    <input type="text" id="flowersToWhom<?=$val->id?>"
                                                           class="form-control form-control-sm"
                                                           name="toWhom<?=$val->id?>" value="">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group m-0">
                                                    <label class="flowered" for="flowersToWhomName<?=$val->id?>">Имя
                                                        получателя</label>
                                                    <input type="text"
                                                           class="form-control form-control-sm"
                                                           id="flowersToWhomName<?=$val->id?>"
                                                           name="toWhomName<?=$val->id?>" value="">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="text-left">
                                            <td colspan="100%">
                                                <div class="form-group m-0">
                                                    <label class="flowered"
                                                           for="flowersCardText<?=$val->id?>">
                                                        Подпись к открытке</label>
                                                    <input type="text"
                                                           class="flowersCardText form-control form-control-sm"
                                                           id="flowersCardText<?=$val->id?>"
                                                           name="cardText<?=$val->id?>"
                                                           value="">
                                                    <div class="popup text-sm">
                                                        <div class="inFrame"><p>Вы можете отправить букет близкому
                                                                человеку.</p>
                                                            <p>Мы доставим ваше внимание!</p>
                                                            <p>C подписью или анонимно!</p>
                                                            <img src="/images/flowers.svg" alt="icon"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td class="text-left">
                                                <h6> Стоимость доставки <strong>
                                                    <span
                                                        data-id="<?=$val->id?>"
                                                        class="delivery-price">
                                                        <?php

                                                        echo $deliveryPriceInMKAD?> </span>руб.</strong>
                                                </h6>
                                            </td>
                                            <td class="text-right">
                                                <h6> Цена букета

                                                    <strong>
                                                    <span
                                                        data-id="<?=$val->id?>"
                                                        class="fl-item-price">
                                                        <?php
                                                        $total = intval($val->price);
                                                        $totalPrice += $deliveryPriceInMKAD;
                                                        echo $total?> </span>руб.</strong>
                                                </h6>

                                            </td>
                                        </tr>

                                        <tr>
                                            <td></td>
                                            <td class="text-right"><small>Добавлен в <?=$val->created_at?></small></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php
                        endforeach;
                        ?>
                    </div>
                </div>
                <div class="section-bg m-m-60">
                    <div class="container">
                        <h4 class="text-right total-price p-3">
                            <i id="totalPrice"><?=$totalPrice?></i>
                            <span>руб.</span>
                        </h4>
                    </div>
                </div>
                <div class=" container form  ">
                    <div class="title">
                        ДАННЫЕ ЗАКАЗЧИКА
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label class="forField" for="flowersName1">Имя</label>
                                <input type="text" class="form-control form-control-sm" id="flowersName1" name="name1"
                                       value="">
                                <input type="hidden" id="item_id" name="item_id" value="861">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">

                                <label class="forField" for="flowersName2">Фамилия</label>
                                <input class="form-control form-control-sm" type="text" id="flowersName2" name="name2"
                                       value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">

                                <label class="forField" for="flowersEmail">Эл. почта</label>
                                <input type="text" class="form-control form-control-sm" id="flowersEmail" name="email"
                                       value="">

                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">

                            <div class="form-group">
                                <label class="forField" for="flowersPhone">Телефон</label>
                                <input type="text" class="form-control form-control-sm" id="flowersPhone" name="phone"
                                       value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">

                                <label class="forField" for="flowersDate">Дата</label>
                                <input type="date" class="form-control form-control-sm" id="flowersDate" name="date"
                                       value="" min="2021-04-22">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <div><label class="forField" for="flowersTime">Время</label></div>

                                <div class="input-group">
                                    <input type="time" class="form-control form-control-sm" id="flowersTime" name="time"
                                           value="17:00" placeholder="с" maxlength="5">
                                    <span class="input-group-addon">-</span>
                                    <input class="form-control form-control-sm" type="time" id="flowersTimeTo"
                                           name="timeTo"
                                           value="22:00" placeholder="до">
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="btn-form">
                        <button class=" btn btn-blue">Заказать</button>
                    </div>
                </div>
            </form>
        </section>
    </main>

    @include('orderForm')
@endsection

@section('scripts')
    <script src="/js/cart.js?v=2"></script>
@endsection
