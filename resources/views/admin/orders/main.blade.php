@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title">Заказы</h3>
                    <div class="card">
                        <div class="bg-body p-3">
                            <div class="row">
                                <div class="col-xs-12 col-sm-3">
                                    <label for="filter-status">Статусы</label>
                                    <select id="filter-status" class="form-control-sm form-control">
                                        <option value="0">ВСЕ</option>
                                        <option value="payed">Оплачен</option>
                                        <option value="in_ms">В складе</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm" id="ordersDataTable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>ИМЯ ФАМИЛИЯ</th>
                                <th>почта</th>
                                <th>ТЕЛЕФОН</th>
                                <th>ДАТА ВРЕМЯ</th>
                                <th>ТИП ДОСТАВКИ</th>
                                <th>сумма</th>
                                <th>Статус оплаты</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($orders as $key => $order):
                            $bgClass = '';
                            if ($order->status === 'payed') {
                                $bgClass = ' bg-success ';
                            }
                            ?>
                            <tr class="<?=$bgClass?>">
                                <td><?= $order->id?></td>
                                <td><?= $order->first_name . ' ' . $order->last_name?></td>
                                <td><?= $order->email?></td>
                                <td><?= $order->phone?></td>
                                <td><?= $order->order_date?> |
                                    <span class="date-from"><?=  rtrim($order->time_from, ':00')?>:00 </span>
                                    -
                                    <span class="date-to"><?=  rtrim($order->time_to, ':00') ?>:00</span>
                                </td>
                                <td>
                                    <?=  $order->delivery_type === 'pickup' ? 'Самовывоз' : 'Доставка'?>
                                </td>
                                <td>
                                    <?= $order->item_price?> руб.
                                </td>
                                <td>
                                    <?= ($order->status === 'payed' ? 'Оплачен' : 'Ждет оплаты') ?>
                                </td>
                                <td>
                                    <a href="/admin/order/<?= $order->id?>">посматреть</a>
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

    <script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#ordersDataTable').DataTable({
                dom: 'Bfrtip',
                pageLength: 250,
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ]
            });
        });
    </script>
    <script src="/js/orders.js"></script>
@endsection
