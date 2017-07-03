<?php
   $form = \yii\bootstrap\ActiveForm::begin([
           'method'=>'get',
           'action'=>\yii\helpers\Url::to(['goods/index']),
           'options'=>['class'=>'form-inline']
       ]
   );
   echo $form->field($model,'name')->textInput(['placeholder'=>'商品名'])->label(false);
   echo $form->field($model,'sn')->textInput(['placeholder'=>'编号'])->label(false);
   echo $form->field($model,'minPrice')->textInput(['placeholder'=>'最低价'])->label(false);
   echo $form->field($model,'maxPrice')->textInput(['placeholder'=>'最高价'])->label(false);
    echo \yii\bootstrap\Html::submitButton('搜索',['class'=>'btn btn-info']);
   \yii\bootstrap\ActiveForm::end();


?>


<table class="table table-bordered table-hover">
    <tr>
        <th>Id</th>
        <th>商品名称</th>
        <th>编号</th>
        <th>logo</th>
        <th>所属分类</th>
        <th>所属品牌</th>
        <th>市场售价</th>
        <th>本店售价</th>
        <th>库存</th>
        <th>是否在售</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>操作</th>
    </tr>

    <?php foreach($goods as $good):?>
        <tr>
            <td><?=$good->id?></td>
            <td><?=$good->name?></td>
            <td><?=$good->sn?></td>
            <td><?=\yii\helpers\Html::img($good->logo,['height'=>50])?></td>
            <td><?=$good->goodsCategory->name?></td>
            <td><?=$good->brand->name?></td>
            <td><?=$good->market_price?></td>
            <td><?=$good->shop_price?></td>
            <td><?=$good->stock?></td>
            <td><?=$good->is_on_sale?'<span class="glyphicon glyphicon-ok btn btn-success btn-xs"></span>':'<span class="glyphicon glyphicon-remove btn btn-danger btn-xs"></span>'?></td>
            <td><?=$good->status?'<span class="glyphicon glyphicon-ok btn btn-success btn-xs"></span>':'<span class="glyphicon glyphicon-remove btn btn-danger btn-xs"></span>'?></td>
            <td><?=date('Y年m月d日 H:i:s',$good->create_time)?></td>
            <td>
                <?php
                    echo \yii\bootstrap\Html::a('编辑',['goods/edit','id'=>$good->id],['class'=>'btn btn-warning btn-xs']);
                    if(Yii::$app->user->can('goods/del')){
                        echo \yii\bootstrap\Html::a('删除',['goods/del','id'=>$good->id],['class'=>'btn btn-danger btn-xs']);
                    }
                    echo  \yii\bootstrap\Html::a('图库',['gallery','id'=>$good->id],['class'=>'btn btn-info btn-xs']);

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
