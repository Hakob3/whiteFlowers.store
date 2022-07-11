@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <div class="white-box">
                    <div class="col-sm-6">
                        <h3 class="box-title">Добавить новый рубрик</h3>
                        <form action="/admin/add_rubric/" method="get">
                            <?php
                            if (isset($new_message)){
                            ?>
                            <p class="text-success"><?= $new_message?></p>
                            <?php
                            }
                            ?>
                            <div class="col-xs-12 col-md-6">
                                <label for="rubric_name">Название</label>
                                <input class="form-control" value="" name="rubric_name"
                                       id="rubric_name" />
                            </div>
                            <div class="col-xs-12 col-md-6">
                                <label for="rubric_desc">Описание</label>
                                <textarea class="form-control" name="rubric_desc"
                                          id="rubric_desc"></textarea>
                            </div>
                            <div class="col-xs-12 col-md-6">
                                <div>
                                    <label for="rubric_vis1">Показать</label>
                                    <input name="vis" type="radio"
                                           id="rubric_vis1"
                                           value="1"/>
                                </div>
                                <div>
                                    <label for="rubric_vis0">Скрыть</label>
                                    <input name="vis" type="radio"
                                           id="rubric_vis0"
                                           value="0"/>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-6">
                                <button class="btn btn-success">Добавить</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="white-box">

                    <h3 class="box-title">Рубрики</h3>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover ">
                            <thead>
                            <tr>
                                <th>Название</th>
                                <th>Порядок</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($collections as $key => $collection):
                            $bgClass = '';
                            if (intval($collection->vis) === 0) {
                                $bgClass = ' disabled ';
                            }
                            ?>
                            <tr class="<?=$bgClass?>">
                                <td><?= $collection->name?></td>
                                <td>
                                    <input value="<?= $collection->ordr?>"
                                           data-id="<?= $collection->id?>"
                                           class="collection_ordr form-control form-control-sm"/>
                                </td>
                                <td>
                                    <a href="/admin/collection/<?= $collection->id?>">посматреть</a>
                                </td>
                            </tr>
                            <?php endforeach;
                            ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php
            if (isset($col)) {            ?>
            <div class="col-sm-6">
                <form action="/admin/collectionEdit/<?=$col->id?>" method="get">
                    <?php
                    if (isset($message)) {
                    ?>
                    <p class="text-success"><?= $message?></p>
                    <?php
                    }
                    ?>

                    <div class="col-xs-12 col-md-6">
                        <label for="collection_name">Название</label>
                        <input class="form-control" value="<?=$col->name?>" name="collection_name"
                               id="collection_name"/>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <label for="collection_desc">Описание</label>
                        <textarea class="form-control" name="collection_desc"
                                  id="collection_desc"><?=$col->text ?></textarea>
                    </div>

                    <div class="col-xs-12 col-md-6">
                        <div>
                            <label for="collection_vis1">Показать</label>
                            <input name="vis" type="radio"
                                   <?php  echo intval($col->vis) === 1 ? 'checked' : '' ?> id="collection_vis1"
                                   value="1"/>
                        </div>
                        <div>
                            <label for="collection_vis0">Скрыть</label>
                            <input name="vis" type="radio"
                                   <?php  echo intval($col->vis) === 0 ? 'checked' : '' ?> id="collection_vis0"
                                   value="0"/>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <button class="btn btn-success">Сохранить</button>
                    </div>

                </form>
            </div>
            <?php
            }
            ?>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="/js/collections.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('.collection_ordr').on('change', function () {
            let $this = $(this);
            let sendData = {
                collection_ordr: $(this).val(),
                collection_id: $(this).data('id'),
            }
        $.ajax({
            method: 'POST',
            data: sendData,
            url: '/editRubrics',
            success: function (resp) {
                let data = $.parseJSON(resp);
                if (data.error) {
                    alert(data.error)
                } else {
                    if (data.success) {
                        $this.addClass('bg-success');
                        setTimeout(function () {
                            $('.bg-success').removeClass('bg-success')
                        }, 3500)
                    }
                }
            }
        })
        })
    </script>
@endsection
