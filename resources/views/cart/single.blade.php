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
            <?php

            if(count($cartItems) > 0 ):
            ?>
            <form class="" id="singleForm">
                <div class="container">
                    <div class="row" id="rowForm">
                        <div class="col-xs-12 col-sm-12 col-md-4">
                            <?php
                            $classes = [
                                "icon-box-pink",
                                "icon-box-blue",
                                "icon-box-cyan",
                                "icon-box-green",
                            ];
                            $totalPrice = 0;
                            foreach ($cartItems as $key => $val) :
                            ?>
                            <input type="hidden" value="<?=$val->id?>" id="data-item<?=$val->id?>" name="cart_id[]"/>
                            <input type="hidden" value="<?=$val->item_id?>" id="data-item<?=$val->id?>"
                                   name="item_id[]"/>
                            <div class="height170 d-flex p-item align-items-stretch aos-init aos-animate"
                                 data-aos="fade-up">
                                <div class="icon-box p-0 flower-cart cart-item <?= $classes[$key % 4]?>">
                                    <div class="cancel-div">
                                        <span class="cancel-item" data-id="<?=$val->id?>"></span>
                                    </div>
                                    <div class="top-img max-img">
                                        <img src="<?=$val->preview?>"
                                             alt="item"/>
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
                                                                       <?php echo($i === 0 ? 'checked' : '') ?>
                                                                       value="<?= $variant->id ?>"
                                                                       name="variant-single<?= $val->id ?>"
                                                                       class="radio-variant-single"
                                                                       data-price="<?= $variant->price?>"
                                                                       data-variantid="<?= $val->id?>"
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

                                            <tr>
                                                <td class="text-left">
                                                    Цена букета
                                                </td>
                                                <td class="text-right">
                                                    <strong>
                                                    <span
                                                            data-id="<?=$val->id?>"
                                                            class="fl-item-price">
                                                        <?php
                                                        $total = intval($val->price);
                                                        $totalPrice += $total;
                                                        echo $total?> </span> руб.</strong>
                                                </td>

                                            </tr>

                                        </table>
                                    </div>
                                </div>
                            </div>
                            <?php
                            endforeach;
                            ?>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-8">
                            <div class="service-details">
                                <div class="card m-0">
                                    {{--                        <div class="card-img">--}}
                                    {{--                            <img src="/assets/img/service-details-3.jpg" alt="...">--}}
                                    {{--                        </div>--}}
                                    <div class="card-body m-0">
                                        <h5 class="card-title">ДАННЫЕ ЗАКАЗЧИКА</h5>
                                        <div class="row pt-3 section-bg mt-4 mb-3">
                                            <div class="col-xs-12 col-sm-6">
                                                <h6 class="text-left"> Стоимость доставки
                                                    <span class="total-price">
                                                    <span id="delivery-price-single" class="delivery-price">
                                                        <?php echo $deliveryPriceInMKAD?> </span>
                                                        руб.
                                                    </span>
                                                </h6>
                                                <h6 class="text-left  pb-3">
                                                    Общая сумма
                                                    <span id="totalPrice" class="total-price">
                                                        <?= $deliveryPriceInMKAD + $totalPrice?>
                                                    </span>
                                                    <span class="total-price"> руб.</span>
                                                </h6>

                                            </div>
                                            <div class="col-xs-12 col-sm-6 ">
                                                <?php
                                                if (count($cartItems) > 1 ) :

                                                ?>
                                                <a href="/cart/multy" class="delivery-a text-right ">
                                                    <img src="/images/junction.svg" style="
    width: 20px;
    margin: 0 10px 0px 0;
" alt="junction "/>
                                                    Заказать по разным адресам
                                                </a>
                                                <?php
                                                endif;
                                                ?>

                                            </div>
                                        </div>
                                        <div class="row">


                                            <div class="col-xs-12 col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label class="forField" for="flowersName1">Имя</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                           id="flowersName1" name="name1"
                                                           value="">
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-6">
                                                <div class="form-group">

                                                    <label class="forField" for="flowersName2">Фамилия</label>
                                                    <input class="form-control form-control-sm" type="text"
                                                           id="flowersName2" name="name2"
                                                           value="">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-6">
                                                <div class="form-group">

                                                    <label class="forField" for="flowersEmail">Эл. почта</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                           id="flowersEmail" name="email"
                                                           value="">

                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-6">

                                                <div class="form-group">
                                                    <label class="forField" for="flowersPhone">Телефон</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                           id="flowersPhone" name="phone"
                                                           value="">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-6">
                                                <div class="form-group">

                                                    <label class="forField" for="flowersDate">Дата</label>
                                                    <input type="date" class="form-control form-control-sm"
                                                           id="flowersDate" name="date"
                                                           value="" min="2021-04-22">
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <div><label class="forField" for="flowersTime">Время</label></div>

                                                    <div class="input-group">
                                                        <input type="time" class="form-control form-control-sm"
                                                               id="flowersTime" name="time"
                                                               value="17:00" placeholder="с" maxlength="5">
                                                        <span class="input-group-addon">-</span>
                                                        <input class="form-control form-control-sm" type="time"
                                                               id="flowersTimeTo"
                                                               name="timeTo" value="22:00" placeholder="до">
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-xs-12 col-sm-12 col-md-6 form-group">
                                                <div class="form-control form-control-sm hidden-f-radio">
                                                    <input type="radio" checked="true" id="delivery_1"
                                                           name="delivery_type"
                                                           value="delivery">
                                                    <label for="delivery_1">Доставка</label>
                                                </div>

                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-6 form-group">
                                                <div class="form-control form-control-sm hidden-f-radio">
                                                    <input type="radio" id="delivery_pickup" name="delivery_type"
                                                           value="pickup">
                                                    <label for="delivery_pickup">Самовывоз</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="addresses">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-6">
                                                    <div class="form-group">
                                                        <label for="flowersAddress">Адрес доставки</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                               id="flowersAddress"
                                                               name="address" value="">
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-12 col-md-6">


                                                    <div class="form-group">
                                                        <label for="flowersMetro">Ближайшая станция метро</label>

                                                        <select class="form-control form-control-sm" id="flowersMetro"
                                                                name="metro">
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


                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-xs-12 col-sm-12 col-md-6 form-group">
                                                    <div class="form-control form-control-sm hidden-f-radio">
                                                        <input type="radio" id="flowersDel1" name="deliveryWhereSingle"
                                                               value="in"
                                                               checked="checked">
                                                        <label class="inLine" for="flowersDel1">Внутри МКАД</label>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-12 col-md-6 form-group">
                                                    <div class="form-control form-control-sm hidden-f-radio">
                                                        <input type="radio" id="flowersDel2" name="deliveryWhereSingle"
                                                               value="out">
                                                        <label for="flowersDel2">За пределами МКАД</label>
                                                    </div>
                                                </div>

                                            </div>


                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-6">
                                                    <div class="form-group"><label class="flowered" for="flowersToWhom">Контакт
                                                            получателя</label><input type="text" id="flowersToWhom"
                                                                                     class="form-control form-control-sm"
                                                                                     name="toWhom"
                                                                                     value=""></div>
                                                </div>
                                                <div class="col-xs-12 col-sm-12 col-md-6">
                                                    <div class="form-group"><label class="flowered"
                                                                                   for="flowersToWhomName">Имя
                                                            получателя</label><input type="text"
                                                                                     class="form-control form-control-sm"
                                                                                     id="flowersToWhomName"
                                                                                     name="toWhomName" value="">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-6">
                                                    <div class="form-group position-relative">
                                                        <label class="flowered" for="flowersCardText">Подпись к
                                                            открытке</label>
                                                        <input type="text"
                                                               class="flowersCardText form-control form-control-sm"
                                                               id="flowersCardText" name="cardText" value="">
                                                        <div class="popup text-sm">
                                                            <div class="inFrame"><p>Вы можете отправить букет близкому
                                                                    человеку.</p>
                                                                <p>Мы доставим ваше внимание!</p>
                                                                <p>C подписью или анонимно!</p>
                                                                <img src="/images/flowers.svg">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-12 col-md-6">
                                                    <div class="form-group">
                                                        <label for="flowersPromocode">Промокод</label><input type="text"
                                                                                                             id="flowersPromocode"
                                                                                                             class="form-control form-control-sm"
                                                                                                             name="promocode"
                                                                                                             value=""><span
                                                                id="promocodeRes"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group">

                                            <p class="rules f-13">
                                                <label for="flowersOk"> <span id="flowersOkLabel">Я соглашаюсь с
                                            <a data-toggle="modal" data-target="#flowersRules">условиями покупки</a> и
                                            <a data-toggle="modal" data-target="#personalRules">условиями хранения персональных данных</a></span>
                                                </label>
                                                <input class="ml-2" type="checkbox" value="ok" id="flowersOk" name="ok">
                                            </p>
                                        </div>
                                        <div class="button-bar text-center pt-2 pb-5">
                                            <button class="btn btn-blue" id="orderBtn">
                                                Купить
                                            </button>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>

                    </div>
                </div>


            </form>
            <?php
            else: ?>
            <div class="container">
                <div class="alert alert-warning">
                    <div class="text-center"> Ваша корзина пуста</div>
                </div>
            </div>
            <?php
            endif;
            ?>
        </section>
    </main>

    @include('orderForm')
@endsection

@section('scripts')
    <script src="/js/cart.js?v=2"></script>
@endsection
