
<?=\yii\helpers\Html::a('新增',['goods-category/add'],['class'=>'btn btn-info'])
/**
 * @var $this \yii\web\View
 */
?>
<table class="table table-hover table-striped table-bordered">
    <tr>
        <th>ID</th>
        <th>分类名</th>
        <th>简介</th>
        <th>操作</th>
    </tr>
    <?php foreach($categorys as $category):?>
        <tr date-lft="<?=$category->lft?>" date-rgt="<?=$category->rgt?>" date-tree="<?=$category->tree?>">
            <td><?=$category->id?></td>
            <td><?=str_repeat('★',$category->depth).$category->name?><span class="glyphicon glyphicon-plus expand" style="float:right;"></span></td>
            <td><?=$category->intro?></td>
            <td>
                <?=\yii\bootstrap\Html::a('修改',['goods-category/edit','id'=>$category->id],['class'=>'btn btn-warning btn-xs'])?>


                <?php
                if(Yii::$app->user->can('goods-category/del')){
                    echo  \yii\bootstrap\Html::a('删除',['goods-category/del','id'=>$category->id],['class'=>'btn btn-danger btn-xs']);
                }
                ?>
            </td>
        </tr>

    <?php endforeach;?>
</table>
<?php
    $js =<<<JS
    $('.expand').click(function(){
       $(this).toggleClass("glyphicon glyphicon-plus");
       $(this).toggleClass("glyphicon glyphicon-minus");
       var current_left = $(this).closest('tr').attr('date-lft');
       var current_right = $(this).closest('tr').attr('date-rgt');
       var current_tree = $(this).closest('tr').attr('date-tree');
       $('tr').each(function(){
           var self_left = $(this).attr('date-lft');
           var self_right = $(this).attr('date-rgt');
           var self_tree = $(this).attr('date-tree');
           if(self_left>current_left&&self_right<current_right&&current_tree==self_tree){
               $(this).toggle();
           }
       })
    })
JS;
$this->registerJs($js);




