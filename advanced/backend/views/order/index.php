<h2>订单详情表</h2>
<div class="container">
    <table class="table table-striped table-hover">
        <tr>
            <th>ID</th>
            <th>收货人</th>
            <th>配送方式</th>
            <th>支付方式</th>
            <th>交易号</th>
            <th>状态</th>
            <th>操作</th>
        </tr>

        <?php foreach($models as $model):?>
            <tr>
                <td><?=$model->id?></td>
                <td><?=$model->name?></td>
                <td><?=$model->delivery_name?></td>
                <td><?=$model->payment_name?></td>
                <td><?=$model->trade_no?></td>
                <td><?php foreach (\frontend\models\Order::status() as $key=> $sta){
                        echo $sta .'&nbsp;<input type="radio" name="sc" ';echo $key==$model->status?'checked':'' ;echo ">&emsp;";
                    }?></td>
                <td>
                    <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#myModal">
                        编辑
                    </button>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
</div>

<div class="container">
    <?=\yii\widgets\LinkPager::widget([
        'pagination'=>$page,
        'nextPageLabel'=>'下一页',
        'prevPageLabel'=>'上一页',
    ])?>
</div>

<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <form action="<?=\yii\helpers\Url::to(['order/edit'])?>" method="post">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">修改状态</h4>
            </div>
            <div class="modal-body">


                <?php foreach (\frontend\models\Order::status() as $key=> $sta){?>
                    <?=$sta?><input type="radio" name="status" <?=$key==$model->status?'checked':''?> value="<?=$key?>">&emsp;
                <?php };?>
                <input type="hidden" name="id" value="<?=$model->id?>">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="submit" class="btn btn-primary" id="sure">确定</button>
                <input type="hidden" id="_csrf-backend" name="_csrf-backend" value="<?=Yii::$app->request->csrfToken?>">
            </div>
        </div>
        </form>
    </div>
</div>

