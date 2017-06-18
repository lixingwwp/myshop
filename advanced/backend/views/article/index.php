<?=\yii\bootstrap\Html::a('新增',['article/add'],['class'=>'btn btn-info'])?>
<table class="table table-hover table-striped">
    <tr>
        <td>文章ID</td>
        <td>标题</td>
        <td>简介</td>
        <td>所属分类</td>
        <td>排序</td>
        <td>状态</td>
        <td>添加时间</td>
        <td>操作</td>
    </tr>
    <?php foreach($models as $model):?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->name?></td>
            <td><?=$model->intro?></td>
            <td><?=$model->articleCategory->name?></td>
            <td><?=$model->sort?></td>
            <td><?=\backend\models\Article::$status[$model->status]?></td>
            <td><?=date('Y年m月d日 H:i:s',$model->create_time)?></td>
            <td>
                <?php
//                    if(Yii::$app->user->can('article/edit')){
                        echo \yii\bootstrap\Html::a('编辑',['article/edit','id'=>$model->id],['class'=>'btn btn-warning btn-xs']);
//                    }
//                    if(Yii::$app->user->can('article/del')){
//                        echo 'aaa';exit;
                        echo \yii\bootstrap\Html::a('删除',['article/del','id'=>$model->id],['class'=>'btn btn-danger btn-xs']);
//                    }
//                    if(Yii::$app->user->can('article/detail')){
                        echo \yii\bootstrap\Html::a('详情',['article/detail','id'=>$model->id],['class'=>'btn btn-info btn-xs']);
//                    }

                ?>
            </td>

        </tr>
    <?php endforeach;?>
</table>
    <?=\yii\widgets\LinkPager::widget([
        'pagination'=>$pages,
        'nextPageLabel'=>'下一页',
        'prevPageLabel'=>'上一页',
    ])?>

