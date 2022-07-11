@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title">Редактировать</h3>

                    <?php
                    if (isset($res['error'])) :
                    ?>
                    <div class=" alert alert-danger ">
                        <?= $res['error']?>
                    </div>
                    <?php   else:
                    $flower = $res['flower'];
                    ?>
                    <form class="edit-fl form-horizontal form-material" id="edit-flower-form">
                        <div class="row">
                            <div class="col-xs-12 col-sm-3">
                                <div class="form-group border-bottom ">
                                    <label for="name">Название</label>
                                    <input name="name" class="form-control p-0 border-0" value="<?=$flower->name?>"
                                           id="name"/>
                                    <input type="hidden" name="itemId" value="<?=$flower->id?>"/>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-2">
                                <div class="form-group ">
                                    <div class="border-bottom ">
                                        <label for="uri">Ссилка</label>
                                        <input name="uri" class="form-control p-0 border-0" value="<?=$flower->uri?>"
                                               id="uri"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-2">
                                <div class="form-group border-bottom ">
                                    <label for="price">Цена</label>
                                    <input name="price" class="form-control p-0 border-0" value="<?=$flower->price?>"
                                           id="price"/>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-2">
                                <div class="form-group border-bottom ">
                                    <label for="status">Статус</label>
                                    <select name="status" class="form-control" id="status">
                                        <option value="visible" <?= ($flower->status === 'visible' ? 'selected' : '') ?>>Активный</option>
                                        <option value="inactive" <?= ($flower->status === 'inactive' ? 'selected' : '') ?>>Неактивный</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-3">
                                <div class="form-group border-bottom ">
                                    <label  for="collection">Коллекция</label>
                                    <select name="collection" class="form-control" id="collection">
                                        <?php
                                        foreach ($collections as $key => $collection) : ?>
                                        <option
                                            <?= (intval($collection['id']) === intval($flower->rubricId) ? 'selected' : '') ?> value="<?=$collection['id']?>"><?=$collection['name']?></option>
                                        <?php  endforeach; ?>

                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-3">
                                <div class="d-flex">
                                    <div class="w-25">
                                        <img style="width: 60px"
                                             src="<?=$flower->preview?>">
                                    </div>
                                    <div class="w-75">
                                        <div class="form-group border-bottom ">
                                            <input type="hidden" name="preview" value="<?=$flower->preview?>"/>
                                            <input type="hidden" name="item_id" value="<?=$flower->id?>"/>
                                            <label for="preview_file">Изображение</label>
                                            <input name="preview_file" type="file" class="form-control p-0 border-0"
                                                   value="<?=$flower->price?>" id="preview_file"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-3">
                                <div class="form-group border-bottom ">
                                    <label for="text">Текст</label>
                                    <textarea name="text" class="form-control p-0 border-0"
                                              id="text">
                                        <?=trim($flower->text)?>
                                    </textarea>
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
                                                if (in_array(intval($flowersItem->id), $variantsIds)) {
                                                    echo ' selected ';
                                                }
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
                                            Редактировать
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </form>
                    <?php  endif;?>
                </div>
            </div>
            <div class="card">
                <div class="card-title">
                    Варианты
                </div>
                <div class="card-body">
                    <?php $flowers = $res['variants']; ?>
                    @include('admin.flowers.table')
                </div>
            </div>


        </div>
    </div>
@endsection
@section('scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="/js/adminFlower.js"></script>
@endsection
