<?php
echo '<h2>修改密码</h2>';
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'oldpassword')->passwordInput();
echo $form->field($model,'password')->passwordInput();
echo $form->field($model,'repassword')->passwordInput();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();