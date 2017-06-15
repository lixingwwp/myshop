<?php
/**
 * @var $this \yii\web\View
 */
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
//echo $form->field($model,'parent_id');
echo $form->field($model,'parent_id')->hiddenInput();
echo '<ul id="treeDemo" class="ztree"></ul>';
echo $form->field($model,'intro');
echo yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);

//<link rel="stylesheet" href="/zTree/css/zTreeStyle/zTreeStyle.css" type="text/css">
//  <script type="text/javascript" src="/zTree/js/jquery-1.4.4.min.js"></script>
//  <script type="text/javascript" src="/zTree/js/jquery.ztree.core.js"></script>

\yii\bootstrap\ActiveForm::end();
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);
$nodes = \yii\helpers\Json::encode($options);
$js = new \yii\web\JsExpression(
    <<<JS
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
                onClick: function(event, treeId, treeNode){//通过点击事件,给隐藏域传递当前点击节点的parent_id值 以便保存此值到数据库
                    $('#goodscategory-parent_id').val(treeNode.id);
                }
            }
   };
   // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
   var zNodes = {$nodes};
  
   zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
   
    zTreeObj.expandAll(true);//展开树的所有分支
    var node = zTreeObj.getNodeByParam("id", $('#goodscategory-parent_id').val(), null);//获取选中节点的父节点
    zTreeObj.selectNode(node);//通过父节点找到,当前选中的节点,
 
JS

);

$this->registerJs($js);