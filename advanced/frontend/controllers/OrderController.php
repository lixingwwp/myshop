<?php
/**
 * Created by PhpStorm.
 * User: le
 * Date: 2017/6/25
 * Time: 11:58
 */
namespace frontend\controllers;

use backend\models\Goods;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\base\Model;
use yii\db\Exception;
use yii\web\Controller;

class OrderController extends Controller{

    public function actionIndex(){
        $this->layout = 'cart';
        $model = new Order();

        $member_id = \Yii::$app->user->id;
        $goods = $goods_list = [];
        //用户没有联系地址跳转至联系地址填写页面
       if(!Address::findOne(['member_id'=>$member_id])){

            return $this->redirect(['address/index']);
       }
       //调用此方法,查找商品信息
        $goods = $this->selectGoods($member_id);
        if($model->load(\Yii::$app->request->post())){
            var_dump($model);exit;
        }

        return $this->render('order',['model'=>$model,'goods'=>$goods]);
    }

    public function actionSave(){
//        public $address;
//        public $delivery_id;
//        public $payment_id;
        $tr = \Yii::$app->db->beginTransaction();
        try{
            //处理订单
            $model = new Order();
            $address = \Yii::$app->request->post('address');
            $delivery_id = \Yii::$app->request->post('delivery_id');
            $payment_id = \Yii::$app->request->post('payment_id');
            $total = \Yii::$app->request->post('total');
            $data = ['address'=>$address,'delivery_id'=>$delivery_id,'payment_id'=>$payment_id,'total'=>$total];
            $model -> loadData($data);
            if($model->validate()) {
                $model->save();
                $id = \Yii::$app->db->getLastInsertID();
                $orderGoods = new OrderGoods();
                $member_id = \Yii::$app->user->id;
                $goods = $this->selectGoods($member_id);
                //处理order_goods数据表
                foreach ($goods as  $gs){
                    //由于AR模型不能一次插入多条数据,使用克隆完成
                    $_orderGoods = clone $orderGoods;
                    $_orderGoods->order_id = $id;
                    $_orderGoods->goods_id = $gs['id'];
                    $_orderGoods->goods_name = $gs['name'];
                    $_orderGoods->goods_logo = $gs['logo'];
                    $_orderGoods->price =$gs['shop_price'];
                    $_orderGoods->amount =$gs['amount'];
                    $_orderGoods->total = $gs['amount']*$gs['shop_price'];
                    //处理库存情况
                    $amount = $gs['amount'];
                    $good_one = Goods::findOne(['id'=>$gs['id']]);
                    $goods_stock = $good_one->stock;
                    //商品数量不足,抛出异常
                    if($goods_stock<$amount){
                        throw new \Exception('库存不足');

                    }else{
                        $good_one->stock -= $amount;
                        $good_one->save();
                    }
                    if($_orderGoods->validate()){
                        $_orderGoods->save();
                     }
                    Cart::deleteAll(['member_id'=>$member_id]);
                }
            }
            //删除购物车
            $tr->commit();
        }catch (Exception $e){
            $tr->rollBack();
        }
    }
    public function selectGoods($member_id){
        $goods=[];
        $carts = Cart::find()->where(['member_id'=>$member_id])->asArray()->all();
        foreach ($carts as $cart){
            $goods_one = Goods::findOne(['id'=>$cart['goods_id']])->attributes;
            if($goods_one){
                $goods_one['amount']=$cart['amount'];
                $goods[] = $goods_one;
            }
        }
        return $goods;
    }


}