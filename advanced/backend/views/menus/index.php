<div class="container">
    <?=\yii\bootstrap\Html::a('新增',['menus/add-menus'],['class'=>'btn btn-info'])?>
</div>
<div class="container">
    <table class="table table-striped table-hover">
        <tr>
            <th>ID</th>
            <th>名称</th>
            <th>父名称</th>
            <th>排序</th>
            <th>地址</th>
            <th>操作</th>
        </tr>

        <?php foreach($models as $model):?>
            <tr>
                <td><?=$model->id?></td>
                <td><?=$model->label?></td>
                <td><?=$model->parent_id?></td>
                <td><?=$model->sort?></td>
                <td><?=$model->url?></td>
                <td>
                    <?=\yii\bootstrap\Html::a('编辑',['menus/edit','id'=>$model->id],['class'=>'btn btn-warning btn-xs'])?>
                    <?php
                    if(Yii::$app->user->can('menus/del')){
                        echo  \yii\bootstrap\Html::a('删除',['menus/del','id'=>$model->id],['class'=>'btn btn-danger btn-xs']);
                    }
                    ?>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
</div>

<div class="container">

</div>
