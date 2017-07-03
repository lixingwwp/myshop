<?php
namespace frontend\controllers;

use Behat\Gherkin\Loader\YamlFileLoader;
use frontend\models\Cart;
use frontend\models\Member;
use frontend\models\MemberForm;
use yii\web\Controller;
use Flc\Alidayu\Client;
use Flc\Alidayu\App;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;
use Flc\Alidayu\Requests\IRequest;
class MemberController extends Controller{
    public $layout = 'login';
    public function actionReguest(){
        $model = new Member();
        $model->scenario='create';
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
//
            if($model->save(false)){
                \Yii::$app->session->setFlash('success','恭喜你注册成功');
                return $this->redirect(['member/reguest']);
            }
        }
        return $this->render('reguest',['model'=>$model]);
    }

    public function actionLogin(){
        $model = new MemberForm();
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            \Yii::$app->session->setFlash('success','恭喜你登录成功');

            //判断有没有购物车cookie
            $cookies = \Yii::$app->request->cookies;
            $member_id = \Yii::$app->user->id;
            $cookie = $cookies->get('cart');
//           $goods_ids=[];
            if($cookie){
                $cart = unserialize($cookie->value);
                foreach ($cart as $goods_id => $ct){
                    $goods = Cart::find()->where(['goods_id'=>$goods_id,'member_id'=>$member_id])->one();
                   if($goods){
                        $goods->amount +=$ct;
                        $goods->save();
                   }else{
                       $cart_table = new Cart();
                       $cart_table->goods_id = $goods_id;
                       $cart_table->amount = $ct;
                       $cart_table->member_id = $member_id;
                       $cart_table->save();
                   }
                }
               \Yii::$app->response->getCookies()->remove($cookie);

            }
           return $this->goBack();
        }
        \Yii::$app->user->setReturnUrl(\Yii::$app->request->referrer);
        return $this->render('login',['model'=>$model]);
    }

    public function actionLogout(){

        \Yii::$app->user->logout();
        return $this->redirect(['shop/index']);
    }

    public function actionIndex(){
        var_dump(\Yii::$app->user->identity);
    }

    public function actionTest(){
// 配置信息
        $config = [
            'app_key'    => '24479851',
            'app_secret' => '2b713cab344b71542cde7ae7d128fb6c',
            // 'sandbox'    => true,  // 是否为沙箱环境，默认false
        ];
// 使用方法一
        $client = new Client(new App($config));
        $req    = new AlibabaAliqinFcSmsNumSend;

        $req->setRecNum('18383891172') //电话号码
            ->setSmsParam([
                'name' => rand(100000, 999999)
            ])
            ->setSmsFreeSignName('XXXXX')//签名
            ->setSmsTemplateCode('SMS_71480218');

        $resp = $client->execute($req);
    }

    public function actionSendSms(){
        $tel = \Yii::$app->request->post('tel');
        if(!preg_match('/^1[34578]\d{9}$/',$tel)){
            echo '电话号码不正确';
            exit;
        }
        $code = mt_rand(100000,999999);
        $result = \Yii::$app->sms->setNum($tel)->setParam(['name' => $code])->send();
        if($result){
            \Yii::$app->cache->set('tel'.$tel,$code,5*60);
            echo 'success';
        }else{
            echo '发送失败';
        }
    }

}