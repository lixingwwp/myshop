
<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'intro')->textarea();
echo $form->field($model,'sort');
echo $form->field($model,'status')->dropDownList(\app\models\ArticleCategory::$status);
echo $form->field($model,'is_help')->dropDownList(\app\models\ArticleCategory::$is_help);
echo \yii\bootstrap\Html::submitButton('添加',['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();
