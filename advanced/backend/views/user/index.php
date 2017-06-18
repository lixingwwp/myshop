
<div style="margin-bottom: 15px;">
    <?=\yii\bootstrap\Html::a('添加',['user/add'],['class'=>'btn btn-info'])?>
</div>
<div>
    <table class="table table-bordered table-hover">
        <tr>
            <td>ID</td>
            <td>用户名</td>
            <td>邮箱</td>
            <td>状态</td>
            <td>创建时间</td>
            <td>上次登录</td>
            <td>上次登录IP</td>
            <td>角色</td>
            <td>操作</td>
        </tr>

        <?php foreach($models as $model):?>
            <tr>
                <td><?=$model->id?></td>
                <td><?=$model->username?></td>
                <td><?=$model->email?></td>
                <td><?=$model->status==0?'<span class="glyphicon glyphicon-remove btn btn-danger btn-sm"></span>':'<span class="glyphicon glyphicon-ok btn btn-success btn-sm"></span>'?></td>
                <td><?=date('Y-m-d H:i:s',$model->created_at)?></td>
                <td><?=$model->updated_at==0?'无':date('Y-m-d H:i:s',$model->updated_at)?></td>
                <td><?=$model->login_ip?$model->login_ip:'无'?></td>
                <td>
                    <?php
                        $authManager = Yii::$app->authManager;
                        $roles = $authManager->getRolesByUser($model->id);
                        foreach($roles as $role){
                            echo $role->description.'|';
                        }
                    ?>
                </td>
                <td>
                    <?=\yii\bootstrap\Html::a('编辑',['user/edit','id'=>$model->id],['class'=>'btn btn-warning btn-xs'])?>

                    <?php
                    if(Yii::$app->user->can('user/del')){
                        echo \yii\bootstrap\Html::a('删除',['user/del','id'=>$model->id],['class'=>'btn btn-danger btn-xs']);
                    }
                    ?>
                </td>

            </tr>

        <?php endforeach;?>
    </table>

</div>
<div>
    <?=\yii\widgets\LinkPager::widget([
        'pagination'=>$pages,
        'nextPageLabel'=>'下一页',
        'prevPageLabel'=>'上一页'
    ])?>
</div>
