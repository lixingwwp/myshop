
<?php
echo '<h2>添加菜单</h2>';
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'label');
echo $form->field($model,'url');
echo $form->field($model,'sort');
echo $form->field($model,'parent_id')->dropDownList(\backend\models\Menus::menu(),['prompt'=>'请选择分类']);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();