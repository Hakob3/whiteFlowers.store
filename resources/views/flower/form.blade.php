{{--personal-abs class for absalute designe--}}
<section class="flower-cart <?php echo $flower->isPersonal ? 'personal-abs- t2t' : '' ?>"
         data-aos="fade-up"
         data-aos-easing="ease-in-out"
         data-aos-duration="500">
    <div class="container mob-p-0">
        <div class="row-form" id="rowForm">
            <div class="main-img max-img ">
                <img src="<?=$flower->image?>" alt="kolbaB"/>
            </div>
            <div class="form">
                <form id="flowerOrderForm">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <?php
                            if ($flower->isPersonal) : ?>
                            <div class="pt-5">
                                <?php
                                echo $flower->banner_text
                                ?>
                                <div class="form-group" style="width: 320px;">
                                    <div class="input-group mt-3">
                                        <input type="number" class="form-control-sm form-control" name="your_price" id="your_price"/>
                                        <span class="input-group-text input-group-text-sm" id="basic-addon2">РУБ.</span>
                                    </div>
                                    <label for="your_price">установите бюджет/не менее 500 руб.</label>
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="pricing pt-5">
                                <div class="pricing pt-5">
                                    <h4><b id="bouquetPrice"><?=$flower->price?></b> руб. </h4>
                                    <h6>СТОИМОСТЬ ДОСТАВКИ
                                        <span id="deliveryPrice"><?= $deliveryPriceInMKAD ?></span> руб.
                                    </h6>
                                </div>
                            </div>
                            <?php endif; ?>

                        </div>
                    </div>
                    <?php
                    if (isset($variants) && count($variants) != 0) :
                    $colClass = ' col-sm-4 ';
                    if (count($variants) === 2) {
                        $colClass = ' col-sm-6 ';
                    }
                    ?>
                    <div class="row">
                        <?php
                        foreach ($variants as $k => $variant):
                        ?>
                        <div class="col-xs-12 {{$colClass}}}">
                            <div class="hidden-f-radio">
                                <div class="form-control form-control-sm">
                                    <input type="radio"
                                           <?= ($k === 0 ? 'checked' : '')?> value="<?= $variant->id?>"
                                           name="variant"
                                           data-price="<?= $variant->price?>"
                                           id="variant<?= $variant->id?>"/>
                                    <label for="variant<?= $variant->id?>"><?=$variant->name?></label>
                                </div>
                            </div>
                        </div>
                        <?php
                        endforeach;
                        ?>
                        <hr class="mb-2 mt-3">
                    </div>
                    <?php
                    endif;
                    ?>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label class="forField" for="flowersName1">Имя</label>
                                <input type="text" class="form-control form-control-sm"
                                       id="flowersName1"
                                       name="name1" value="">
                                <input type="hidden"
                                       id="item_id"
                                       name="item_id" value="<?=$flower->id?>">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label class="forField" for="flowersName2">Фамилия</label>
                                <input class="form-control form-control-sm"
                                       type="text"
                                       id="flowersName2"
                                       name="name2" value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label class="forField" for="flowersEmail">Эл. почта</label>
                                <input type="text" class="form-control form-control-sm"
                                       id="flowersEmail"
                                       name="email" value="">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            {{--petqa lini phone mask - ov rusakan hamarneri formatov +7 ### ### ## ##--}}
                            <div class="form-group">
                                <label class="forField" for="flowersPhone">Телефон</label>
                                <input type="text" class="form-control form-control-sm"
                                       id="flowersPhone"
                                       name="phone" value="">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label class="forField" for="flowersDate">Дата</label>
                                <input type="date" class="form-control form-control-sm"
                                       id="flowersDate"
                                       name="date"
                                       value=""
                                >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <div><label class="forField" for="flowersTime">Время</label></div>
                                <div class="input-group">
                                    <input
                                            type="time" class="form-control form-control-sm"
                                            id="flowersTime"
                                            name="time" value="17:00"
                                            placeholder="с"
                                            maxlength="5">
                                    <span class="input-group-addon">-</span>
                                    <input class="form-control form-control-sm"
                                           type="time"
                                           id="flowersTimeTo"
                                           name="timeTo" value="22:00"
                                           placeholder="до"
                                    >
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-xs-12 col-sm-12 col-md-6 form-group">
                            <div class="form-control form-control-sm hidden-f-radio">
                                <input type="radio" checked="true"
                                       id="delivery_1"
                                       name="delivery_type" value="delivery"/>
                                <label for="delivery_1">Доставка</label>
                            </div>

                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 form-group">
                            <div class="form-control form-control-sm hidden-f-radio">
                                <input type="radio"
                                       id="delivery_pickup"
                                       name="delivery_type" value="pickup"/>
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

                                    <select class="form-control form-control-sm"
                                            id="flowersMetro" name="metro">
                                        <option value=""></option>
                                        <?php
                                        foreach ($metroStations as $key => $val):
                                        ?>
                                        <option value="<?=$val?>"><?=$val?></option>
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
                                    <input type="radio"
                                           id="flowersDel1"
                                           name="deliveryWhere"
                                           value="in"
                                           checked="checked">
                                    <label class="inLine" for="flowersDel1">Внутри МКАД</label>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 form-group">
                                <div class="form-control form-control-sm hidden-f-radio">
                                    <input type="radio"
                                           id="flowersDel2"
                                           name="deliveryWhere"
                                           value="out">
                                    <label for="flowersDel2">За пределами МКАД</label>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="form-group"><label class="flowered" for="flowersToWhom">Контакт
                                        получателя</label><input
                                            type="text" id="flowersToWhom" class="form-control form-control-sm"
                                            name="toWhom" value=""></div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="form-group"><label class="flowered" for="flowersToWhomName">Имя
                                        получателя</label><input
                                            type="text" class="form-control form-control-sm"
                                            id="flowersToWhomName"
                                            name="toWhomName" value="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="form-group">


                                    <label class="flowered" for="flowersCardText">Подпись к
                                        открытке</label>
                                    <input
                                            type="text"
                                            class="flowersCardText form-control form-control-sm"
                                            id="flowersCardText"
                                            name="cardText"
                                            value="">
                                    <div class="popup text-sm">
                                        <div class="inFrame"><p>Вы можете отправить букет близкому
                                                человеку.</p>
                                            <p>Мы доставим ваше внимание!</p>
                                            <p>C подписью или анонимно!</p>
                                            <img src="/images/flowers.svg"/>
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
                                                                                         value=""
                                    ><span
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

                </form>
            </div>
        </div>
    </div>
</section>
