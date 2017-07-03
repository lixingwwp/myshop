
<!-- 页面主体 start -->
<div class="main w1210 bc mt10">
    <div class="crumb w1210">
        <h2><strong>我的XX </strong><span>> 我的订单</span></h2>
    </div>

    <!-- 左侧导航菜单 start -->
    <div class="menu fl">
        <h3>我的XX</h3>
        <div class="menu_wrap">
            <dl>
                <dt>订单中心 <b></b></dt>
                <dd><b>.</b><a href="">我的订单</a></dd>
                <dd><b>.</b><a href="">我的关注</a></dd>
                <dd><b>.</b><a href="">浏览历史</a></dd>
                <dd><b>.</b><a href="">我的团购</a></dd>
            </dl>

            <dl>
                <dt>账户中心 <b></b></dt>
                <dd class="cur"><b>.</b><a href="">账户信息</a></dd>
                <dd><b>.</b><a href="">账户余额</a></dd>
                <dd><b>.</b><a href="">消费记录</a></dd>
                <dd><b>.</b><a href="">我的积分</a></dd>
                <dd><b>.</b><a href="">收货地址</a></dd>
            </dl>

            <dl>
                <dt>订单中心 <b></b></dt>
                <dd><b>.</b><a href="">返修/退换货</a></dd>
                <dd><b>.</b><a href="">取消订单记录</a></dd>
                <dd><b>.</b><a href="">我的投诉</a></dd>
            </dl>
        </div>
    </div>
    <!-- 左侧导航菜单 end -->

    <!-- 右侧内容区域 start -->
    <div class="content fl ml10">
        <div class="address_hd">
            <h3>收货地址薄</h3>
<!--            查找当前登录用户的所有收货地址-->
            <?php $addresses = \frontend\models\Address::find()->where(['member_id'=>Yii::$app->user->id])->all();
                $count = 0;
                foreach ($addresses as $address){
                    $province = \frontend\models\Locations::findOne(['id'=>$address->provence])->name;
                    $city = \frontend\models\Locations::findOne(['id'=>$address->city])->name;
                    $area = \frontend\models\Locations::findOne(['id'=>$address->area])->name;

                echo '<dl>';
                echo '<dt>'.++$count.'&nbsp;'.$address->name.'&nbsp;'.$province.'&nbsp;'.$city.'&nbsp;'.$area.'&nbsp;'.$address->phone.'</dt>';
                echo '<dd>' ;
//                echo '<a href="'.['address/edit'].'">修改</a>&nbsp;';
                echo '<a href="">删除</a>&nbsp;';
                echo '<a href="">设为默认地址</a>&nbsp;';
                echo '</dd>';
            echo '</dl>';
                }
            ?>
            <dl>
                <dt>1.许坤 北京市 昌平区 仙人跳区 仙人跳大街 17002810530 </dt>
                <dd>
                    <a href="">修改</a>
                    <a href="">删除</a>
                    <a href="">设为默认地址</a>
                </dd>
            </dl>



        </div>

        <div class="address_bd mt10">
            <h4>新增收货地址</h4>
            <?php
                $form = \yii\widgets\ActiveForm::begin([
                    'fieldConfig'=>[
                        'options'=>['tag'=>'li']
                    ]
                ]);
                echo '<ul>';
                echo $form->field($model,'name')->textInput(['class'=>'txt']);
                echo '<input name="_csrf-frontend" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">';
                echo '<li>';
                echo $form->field($model,'provence',['options'=>['tag'=>false]])->dropDownList(['prompt'=>'--请选择城省--'],['id'=>'s_1']);
                echo $form->field($model,'city',['options'=>['tag'=>false]])->dropDownList(['prompt'=>'--请选择城市--'],['id'=>'s_2']);
                echo $form->field($model,'area',['options'=>['tag'=>false]])->dropDownList(['prompt'=>'--请选择地区--']);
                echo '</li>';
                echo $form->field($model,'detail')->textInput(['class'=>'txt address']);
                echo $form->field($model,'phone')->textInput(['class'=>'txt']);
                echo $form->field($model,'default')->checkbox(['class'=>'check']);
                echo '<li>';
                echo \yii\helpers\Html::submitButton('保存',['class'=>'btn']);
                 echo '<li>';
                echo '</ul>';
                \yii\widgets\ActiveForm::end();
            ?>

<!--            <form action="" name="address_form">-->
<!--                <ul>-->
<!--                    <li>-->
<!--                        <label for=""><span>*</span>收 货 人：</label>-->
<!--                        <input type="text" name="" class="txt" />-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <label for=""><span>*</span>所在地区：</label>-->
<!--                        <select name="" id="s_1">-->
<!--                            <option value="">请选择</option>-->
<!--                            <option value="1">四川省</option>-->
<!---->
<!--                        </select>-->
<!---->
<!--                        <select name="" id="s_2">-->
<!--                            <option value="">请选择</option>-->
<!--                            <option value="1">成都市</option>-->
<!---->
<!--                        </select>-->
<!---->
<!--                        <select name="" id="s_3">-->
<!--                            <option value="">请选择</option>-->
<!--                            <option value="1">高新区</option>-->
<!---->
<!--                        </select>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <label for=""><span>*</span>详细地址：</label>-->
<!--                        <input type="text" name="" class="txt address"  />-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <label for=""><span>*</span>手机号码：</label>-->
<!--                        <input type="text" name="" class="txt" />-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <label for="">&nbsp;</label>-->
<!--                        <input type="checkbox" name="" class="check" />设为默认地址-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <label for="">&nbsp;</label>-->
<!--                        <input type="submit" name="" class="btn" value="保存" />-->
<!--                    </li>-->
<!--                </ul>-->
<!--            </form>-->
        </div>

    </div>
    <!-- 右侧内容区域 end -->
</div>
<?php
$url = \yii\helpers\Url::to(['provence']);
$area = \yii\helpers\Url::to(['area']);
$js=<<<JS
    $.post("{$url}",'',function(response){
        //获得所有数据,遍历出来
        $.each(response,function(i,v){
           var html = '<option value="'+v.id+'">'+v.name+'</option>'
           $(html).appendTo($('#s_1'))
        })
      
    },'json')
    
     //当选了省,传对应的value 通过json 读取对应的二级数据
        $('#s_1').on('change',function(){
            //清理二级,三级的数据
            $('select:eq(1)').get(0).length = 1;
            $('select:eq(2)').get(0).length = 1;
            var id = $(this).val();
            $.post("{$area}",{'id':id},function(response){
                $.each(response,function(i,v){
                     var html = '<option value="'+v.id+'">'+v.name+'</option>';
                     $(html).appendTo('select:eq(1)');
                })
            },'json')
        })
        
        //三级下拉
        $('#s_2').on('change',function(){
            //清理二级,三级的数据
            var id = $(this).val();
            $.post("{$area}",{'id':id},function(response){
                $.each(response,function(i,v){
                     var html = '<option value="'+v.id+'">'+v.name+'</option>';
                     $(html).appendTo('select:eq(2)');
                })
            },'json')
        })
JS;

$this->registerJs($js);




//$url = \yii\helpers\Url::to(['del-gallery']);
//$this->registerJs(new \yii\web\JsExpression(
//    <<<EOT
//    $("table").on('click',".del_btn",function(){
//        if(confirm("确定删除该图片吗?")){
//        var id = $(this).closest("tr").attr("data-id");
//            $.post("{$url}",{id:id},function(data){
//                if(data=="success"){
//                    //alert("删除成功");
//                    $("#gallery_"+id).remove();
//                }
//            });
//        }
//    });
//EOT
//
//));