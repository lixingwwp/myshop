<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($goodsPhotos, 'photo[]')->widget(\kartik\file\FileInput::classname(), [
    'options' => [
        'multiple' => true,
//     'accept' => 'image/*',
    ],


//

]);
echo \yii\bootstrap\Html::submitButton('确认',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();