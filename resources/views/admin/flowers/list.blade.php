@extends('layouts.app')
@section('content')
    <div class="container-fluid">

        <div class="white-box">
            <div class="box-title">
                Добавить
            </div>
            <div class="card-body">
                <form class="edit-fl form-horizontal form-material" id="add-flower-form">
                    <div class="row">
                        <div class="col-xs-12 col-sm-3">
                            <div class="form-group border-bottom ">
                                <label for="name">Название</label>
                                <input name="name" class="form-control p-0 border-0" value=""
                                       id="name"/>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-3">
                            <div class="form-group ">
                                <div class="border-bottom ">
                                    <label for="uri">Ссилка</label>
                                    <input name="uri" class="form-control p-0 border-0" value=""
                                           id="uri"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-3">
                            <div class="form-group border-bottom ">
                                <label for="price">Цена</label>
                                <input name="price" class="form-control p-0 border-0" value=""
                                       id="price"/>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-3">
                            <div class="form-group border-bottom ">
                                <label for="text" for="collection">Коллекция</label>
                                <select name="collection" class="form-control" id="collection">
                                    <?php
                                    foreach ($collections as $key => $collection) : ?>
                                    <option
                                            value="<?=$collection['id']?>"><?=$collection['name']?></option>
                                    <?php  endforeach; ?>

                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-3">
                            <div class="form-group border-bottom ">
                                <label for="preview_file">Изображение</label>
                                <input name="preview_file" type="file" class="form-control p-0 border-0"
                                       value="" id="preview_file"/>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-3">
                            <div class="form-group border-bottom ">
                                <label for="text">Текст</label>
                                <textarea name="text" class="form-control p-0 border-0"
                                          id="text"></textarea>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-3">
                            <div class="form-group border-bottom ">
                                <label for="variants">Варианты</label>
                                <select id="variants" name="variants[]" class="form-control" multiple>
                                    <?php
                                    foreach ($flowersItems as $key => $flowersItem):
                                    ?>
                                    <option <?php

                                            ?> value="<?=$flowersItem->id?>"><?=$flowersItem->id . ' ' . $flowersItem->name?></option>
                                    <?php
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-3">
                            <div class="form-group border-bottom ">
                                <div class="text-center p-20 upgrade-btn">
                                    <button id="updateFlower" class="btn d-inline-block btn-danger text-white">
                                        Добавить
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>

                </form>
            </div>
        </div>
        @include('admin.flowers.table')


    </div>
@endsection
@section('scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="/js/adminFlower.js"></script>
@endsection