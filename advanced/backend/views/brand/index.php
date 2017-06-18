<div class="container">
    <?=\yii\bootstrap\Html::a('新增',['brand/add'],['class'=>'btn btn-info'])?>
</div>
<div class="container">
    <table class="table table-striped table-hover">
        <tr>
            <th>ID</th>
            <th>品牌名称</th>
            <th>LOGO</th>
            <th>介绍</th>
            <th>排序</th>
            <th>状态</th>
            <th>操作</th>
        </tr>

        <?php foreach($models as $model):?>
            <tr>
                <td><?=$model->id?></td>
                <td><?=$model->name?></td>
                <td><?=\yii\bootstrap\Html::img($model->logo,['width'=>50])?></td>
                <td><?=$model->intro?></td>
                <td><?=$model->sort?></td>
                <td><?=\app\models\Brand::$status[$model->status]?></td>
                <td>
                    <?=\yii\bootstrap\Html::a('编辑',['brand/edit','id'=>$model->id],['class'=>'btn btn-warning btn-xs'])?>


                    <?php
                    if(Yii::$app->user->can('brand/del')){
                        echo \yii\bootstrap\Html::a('删除',['brand/del','id'=>$model->id],['class'=>'btn btn-danger btn-xs']);
                    }
                    ?>
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
