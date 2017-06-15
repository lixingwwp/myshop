<div class="container" style="margin-bottom:10px;">
    <ul class="nav nav-pills">
        <li role="presentation" class="active"><a href="#">新增商品</a></li>
        <li role="presentation"><a href="#">商品列表</a></li>
    </ul>
</div>
<div class="container">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">商品添加</h3>
        </div>
        <div class="panel-body">
            <?php
            /**
             * @var $this  \yii\web\View
             */
//            <link rel="stylesheet" href="/zTree/css/zTreeStyle/zTreeStyle.css" type="text/css">
//            <script type="text/javascript" src="/zTree/js/jquery-1.4.4.min.js"></script>
//            <script type="text/javascript" src="/zTree/js/jquery.ztree.core.js"></script>4
            $this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
            $this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);
            $notes = json_encode($goodsCategorys);
//            var_dump($notes);exit;
            use yii\web\JsExpression;
            $form = \yii\bootstrap\ActiveForm::begin();
            echo $form->field($model,'name');
            echo $form->field($model,'brand_id')->dropDownList(\yii\helpers\ArrayHelper::map($brands,'id','name'),['prompt'=>'请选择品牌']);

            echo '<ul id="treeDemo" class="ztree"></ul>';
            $js=<<<JS
var zTreeObj;
   // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
   var setting = {
       data: {
            simpleData: {
                enable: true,
                idKey: "id",
                pIdKey: "parent_id",
                rootPId: 0
            }
	    },
        callback: {
                onClick: function(event, treeId, treeNode){
                  $('#goods_category_id').val(treeNode.id);
                   
                }
         }
   };
   // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
   var zNodes = {$notes};
   $(document).ready(function(){
       zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
       zTreeObj.expandAll(true);
   });
JS;
            echo $form->field($model,'goods_category_id')->hiddenInput(['id'=>'goods_category_id']);
            echo $form->field($model,'logo')->hiddenInput(['id'=>'img_file'])->label(null);
            echo $form->field($model,'market_price');
            echo $form->field($model,'shop_price');
            echo $form->field($model,'sort');
            echo $form->field($model,'stock');
            echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
            echo \xj\uploadify\Uploadify::widget([
                'url' => yii\helpers\Url::to(['s-upload']),
                'id' => 'test',
                'csrf' => true,
                'renderTag' => false,
                'jsOptions' => [

                    'width' => 120,
                    'height' => 40,

                    'onUploadError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
                    ),
                    'onUploadSuccess' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        $('#img1').attr('src',data.fileUrl).show();
        $('#img2').attr('src',data.fileUrl);
        $('#img_file').val(data.fileUrl);
        console.log(data.fileUrl);
    }
}
EOF
                    ),
               ]
            ]);
            if($model->logo){
                echo \yii\bootstrap\Html::img($model->logo,['id'=>'img2']);
            }else{
                echo \yii\bootstrap\Html::img('',['style'=>'display:none','id'=>'img1','width'=>'200px']);
            }

            echo $form->field($model,'is_on_sale')->radioList(\backend\models\Goods::$is_on_sale);
            echo $form->field($model,'status')->radioList(\backend\models\Goods::$status);
            echo $form->field($goodsIntro, 'content')->widget(\crazyfd\ueditor\Ueditor::className(),[]);
            echo $form->field($goodsPhotos, 'photo[]')->widget(\kartik\file\FileInput::classname(), [
                'options' => ['multiple' => true],
//                'pluginOptions' => [
//                    'uploadExtraData' => [
//                        'goods_id' => $model->id,
//                    'uploadUrl' => \yii\helpers\Url::toRoute('@web/goods/photo/async-image')
//                    ],
//                ]

            ]);
            echo \yii\bootstrap\Html::submitButton('确认',['class'=>'btn btn-info']);

            \yii\bootstrap\ActiveForm::end();
            $this->registerJs($js);
            ?>
        </div>
    </div>
</div>

