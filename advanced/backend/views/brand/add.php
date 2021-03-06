
<?php
use yii\web\JsExpression;
use xj\uploadify\Uploadify;
use yii\helpers\Html;
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'intro')->textarea();
echo $form->field($model,'sort');
echo $form->field($model,'status')->radioList(\app\models\Brand::$status);
echo $form->field($model,'logo')->hiddenInput();
//echo $form->field($model,'logo')->fileInput(['id'=>'test']);
echo Html::fileInput('test', NULL, ['id' => 'test']);
echo Uploadify::widget([
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
        alert('上传格式不正确');
    } else {
        console.log(data.fileUrl);
        //将上传成功后的图片地址(data.fileUrl)写入img标签
        $('#img_logo').attr("src",data.fileUrl).show();
        //将上传成功后的图片地址(data.fileUrl)写入logo字段
        $('#brand-logo').val(data.fileUrl);
        $('#new').attr("src",data.fileUrl);
    }
}
EOF
        ),
    ]
]);
if($model->logo){
    echo Html::img($model->logo,['style'=>'width:100px','id'=>'new']);
}else{
    echo Html::img('',['style'=>'display:none','id'=>'img_logo','height'=>'50px']);
}
?>
<div style="margin-top:20px;">
<?php echo \yii\bootstrap\Html::submitButton('确定',['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();?>
</div>