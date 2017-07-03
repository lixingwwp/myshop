<!-- 登录主体部分start -->
<div class="login w990 bc mt10 regist">
    <div class="login_hd">
        <h2>用户注册</h2>
        <b></b>
    </div>
    <div class="login_bd">
        <div class="login_form fl">
            <?php
                $form = \yii\widgets\ActiveForm::begin(
                    ['fieldConfig'=>[
                        'options'=>[
                            'tag'=>'li',
                        ],
                        'errorOptions'=>[
                            'tag'=>'p'
                        ],
                    ],
                     'action' => ['member/reguest'],]
                );
                echo '<ul>';
                echo $form->field($model,'username')->textInput(['class'=>'txt']);
                echo $form->field($model,'password')->passwordInput(['class'=>'txt']);
                echo $form->field($model,'repassword')->passwordInput(['class'=>'txt']);
                echo $form->field($model,'email')->textInput(['class'=>'txt']);
                echo $form->field($model,'tel')->textInput(['class'=>'txt']);
               /* echo ' <li>
                            <label for="">验证码：</label>
                            <input type="text" class="txt" value="" placeholder="请输入短信验证码" name="captcha" disabled="disabled" id="captcha"/> <input type="button" onclick="bindPhoneNum(this)" id="get_captcha" value="获取验证码" style="height: 25px;padding:3px 8px"/>
    1
                        </li>';*/
               $button = \yii\helpers\Html::button('发送短信验证码',['id'=>'send_sms_button']);
               echo $form->field($model,'smsCode',['options'=>['class'=>'checkcode'],'template'=> "{label}\n{input}$button\n{hint}\n{error}"])->input(['class'=>'txt']);
                 echo $form->field($model,'code',['options'=>['class'=>'checkcode']])->widget(yii\captcha\Captcha::className(),['template'=>'{input}{image}']);
                echo '<li>' ;
                echo '<label for="">&nbsp;</label>';
                echo ' <input type="checkbox" class="chb" /> 我已阅读并同意《用户注册协议》';
                echo '</li>';

                echo '<li>';
                echo '<label for="">&nbsp;</label>' ;

                echo '<input type="submit" value="" class="login_btn" />'  ;
                echo '</li>';
                echo '</ul>';
                \yii\widgets\ActiveForm::end();
            ?>
<!--
            <script>-->
                <!--                   if($('.chb').prop('checked',true)){-->
                <!--                        $('.login_btn').attr('disabled');-->
                <!--                   }-->
                <!--                </script>-->

        </div>

        <div class="mobile fl">
            <h3>手机快速注册</h3>
            <p>中国大陆手机用户，编辑短信 “<strong>XX</strong>”发送到：</p>
            <p><strong>1069099988</strong></p>
        </div>

    </div>
</div>
<!-- 登录主体部分end -->
<?php
    /**
     * @var $this \yii\web\View
     */
    $url = \yii\helpers\Url::to(['member/send-sms']);
    $this->registerJs(new \yii\web\JsExpression(
            <<<JS
        $('#send_sms_button').click(function(){
            var tel = $('#member-tel').val();
            $.post("$url",{'tel':tel},function(data){
                console.debug(data)
             })
        })  
JS
    ))
?>