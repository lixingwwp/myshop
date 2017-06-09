<?=\yii\bootstrap\Html::a('新增文章分类',['article-category/add'],['class'=>'btn btn-info '])?>
<table class="table table-bordered table-striped table-hover">
    <tr>
        <td>ID</td>
        <td>分类名称</td>
        <td>分类介绍</td>
        <td>分类排序</td>
        <td>状态</td>
        <td>类型</td>
        <td>操作</td>
    </tr>
    <?php foreach($models as $model):?>
    <tr>
        <td><?=$model->id?></td>
        <td><?=$model->name?></td>
        <td><?=$model->intro?></td>
        <td><?=$model->sort?></td>
        <td><?=\app\models\ArticleCategory::$status[$model->status]?></td>
        <td><?=\app\models\ArticleCategory::$is_help[$model->is_help]?></td>
        <td>
            <?=\yii\bootstrap\Html::a('编辑',['article-category/edit','id'=>$model->id],['class'=>'btn btn-warning btn-xs'])?>
            <?=\yii\bootstrap\Html::a('删除',['article-category/del','id'=>$model->id],['class'=>'btn btn-danger btn-xs'])?>
        </td>
    </tr>
    <?php endforeach;?>
</table>
<?=\yii\widgets\LinkPager::widget([
    'pagination'=>$pages,
    'nextPageLabel'=>'下一页',
    'prevPageLabel'=>'上一页',
])?>

