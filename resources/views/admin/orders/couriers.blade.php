@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-4">
                <div class="white-box">
                    <h3 class="box-title">Курьеры</h3>

                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                            <tr>
                                <th>Имя</th>
                                <th>Количество заказов</th>
                                <th>Заказы</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($couriers as $key => $courier):

                            ?>
                            <tr>
                                <td>
                                    <input value="<?= $courier->name?>"
                                           class="courier_name form-control form-control-sm "
                                           data-id="<?= $courier->id?>"/>
                                </td>
                                <td><?= count($courier->orders)?></td>

                                <td>
                                    <?php
                                    foreach ($courier->orders as $k => $order):
                                    ?>
                                    <a class="btn btn-black" href="/admin/order/<?=$order?>">id: <?=$order?></a>
                                    <?php
                                    endforeach;
                                    ?>
                                </td>
                                <td>
                                    <a href="/admin/couriers/<?= $courier->id?>">посматреть</a>
                                </td>
                            </tr>
                            <?php endforeach;
                            ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('.courier_name').on('change', function () {

            let $this = $(this);
            let sendData = {
                courier_name: $(this).val(),
                courier_id: $(this).data('id'),
            };
            $.ajax({
                method: 'POST',
                data: sendData,
                url: '/editCourier',
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
            });
        })
    </script>
@endsection
