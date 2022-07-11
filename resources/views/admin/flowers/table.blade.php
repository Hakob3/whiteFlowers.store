<div class="white-box">
    <?php
    if(count($flowers) > 0) :
    ?>

        <div class="d-flex justify-content-between">
            <h3 class="box-title">Цветы </h3>
            <ul class="list-inline">
                <li>
                    <a href="/admin/flowers/?status=visible" class="btn btn-success">Активы</a>
                    <a href="/admin/flowers/?status=inactive" class="btn btn-success">Неактивные</a>
                </li>
            </ul>
        </div>
    <div class="table-responsive">
        <table class="table text-nowrap">
            <thead>
            <tr>
                <th>#</th>
                <th>букет</th>
                <th>цена</th>
                <th>коллекция</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($flowers as $flower)
                <tr <?= ($flower->status === 'inactive' ? ' class="bg-inverse text-white " ' : '')?>>
                    <th scope="row">{{$flower->id}}</th>
                    <td>
                        <img style="width: 60px"
                             src="<?=$flower->preview?>">
                        <?= $flower->name?>
                    </td>
                    <td class=""><?= $flower->price?> руб.</td>
                    <td><?= $flower->rub_name?></td>
                    <td><a href="/admin/flowers/<?= $flower->id?>">Редактировать</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <?php
    else:
        echo 'пусто'; endif;
    ?>
</div>