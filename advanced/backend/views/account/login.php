<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username');
echo $form->field($model,'password')->passwordInput();
echo $form->field($model,'remember')->checkbox();
echo $form->field($model,'code')->widget(\yii\captcha\Captcha::className(),[
    'captchaAction' =>'account/captcha',
    'template'=>'<div class="row"><div class="col-sm-1">{image}</div><div class="col-sm-2">{input}</div></div>'
]);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();