
<?=\yii\bootstrap\Html::a('添加',['add-permission'],['class'=>'btn btn-info'])?>
<table class="table table-bordered table-hover">
    <tr>
        <td>权限名称</td>
        <td>权限描述</td>
        <td>操作</td>
    </tr>

    <?php foreach($models as $model):?>
    <tr>
        <td><?=$model->name?></td>
        <td><?=$model->description?></td>
        <td>
            <?=\yii\bootstrap\Html::a('编辑',['rbac/edit-permission','name'=>$model->name],['class'=>'btn btn-warning btn-xs'])?>

            <?php
            if(Yii::$app->user->can('rbac/del-permission')){
                echo  \yii\bootstrap\Html::a('删除',['rbac/del-permission','name'=>$model->name],['class'=>'btn btn-danger btn-xs']);
            }
            ?>

        </td>
    </tr>
    <?php endforeach;?>

</table>