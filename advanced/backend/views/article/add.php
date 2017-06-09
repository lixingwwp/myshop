<h1>添加文章</h1>
<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($article,'name');
echo $form->field($article,'article_category_id')->dropDownList(\yii\helpers\ArrayHelper::map($article_category,'id','name'),['prompt'=>'请选择分类']);
echo $form->field($article,'intro')->textarea();
echo $form->field($article,'sort');
echo $form->field($article,'status')->dropDownList(\backend\models\Article::$status,['prompt'=>'请选择状态']);
echo $form->field($article_detail,'content')->textarea();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
