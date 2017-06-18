
<?=\yii\bootstrap\Html::a('添加角色',['add-role'],['class'=>'btn btn-info'])?>
<table class="table table-bordered table-hover">
    <tr>
        <td>角色名称</td>
        <td>角色描述</td>
        <td>权限</td>
        <td>操作</td>
    </tr>

    <?php foreach($models as $model):?>
        <tr>
            <td><?=$model->name?></td>
            <td><?=$model->description?></td>
            <td>
                <?php
                    foreach(Yii::$app->authManager->getPermissionsByRole($model->name) as $permission){
                        echo $permission->description.'/';
                    }

                ?>
            </td>
            <td>
                <?=\yii\bootstrap\Html::a('编辑',['rbac/role-edit','name'=>$model->name],['class'=>'btn btn-warning btn-xs'])?>

                <?php
                if(Yii::$app->user->can('rbac/role-del')){
                    echo  \yii\bootstrap\Html::a('删除',['rbac/role-del','name'=>$model->name],['class'=>'btn btn-danger btn-xs']);
                }
                ?>
            </td>
        </tr>
    <?php endforeach;?>

</table>