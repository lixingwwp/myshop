<?php
namespace frontend\controllers;

use backend\components\SphinxClient;
use backend\models\Goods;
use backend\models\GoodsCategory;
use frontend\models\Cart;
use frontend\models\IndexCate;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class ShopController extends Controller{
    public function actionIndex(){
        $this->layout = 'index';
        $goodsCategorys = GoodsCategory::find()->where(['parent_id'=>0])->all();//查找所有顶级分类
        return $this->render('index',['goodsCategorys'=>$goodsCategorys]);
    }
    public function actionTest(){
        $redis=new \Redis();
    }

    public function actionList(){

        $this->layout = 'list';

        $id = \Yii::$app->request->get('id');
        $keywords =\Yii::$app->request->get('keywords');
        $newGoods = Goods::find()->orderBy(['create_time'=>'desc'])->limit(3)->all();
        $goodsCategories = GoodsCategory::find()->where(['parent_id'=>$id])->all();
        $top =[];
        $goods = Goods::find()->all();
        if(isset($keywords)){
            $cl = new \frontend\components\SphinxClient();
            $cl->SetServer ( '127.0.0.1', 9312);
//$cl->SetServer ( '10.6.0.6', 9312);
//$cl->SetServer ( '10.6.0.22', 9312);
//$cl->SetServer ( '10.8.8.2', 9312);
            $cl->SetConnectTimeout ( 10 );
            $cl->SetArrayResult ( true );
// $cl->SetMatchMode ( SPH_MATCH_ANY);
            $cl->SetMatchMode ( SPH_MATCH_ANY);
            $cl->SetLimits(0, 1000);

            $res = $cl->Query($keywords, 'goods');//shopstore_search
            if(!isset($res['matches'])){
                $cate_id=[];
            }else{
                $cate_id = ArrayHelper::getColumn($res['matches'],'id');

            }
//print_r($cl);

        }else{
            $cate_id=null;

            $newGoods = Goods::find()->orderBy(['create_time'=>'desc'])->limit(3)->all();
            $goodsCategories = GoodsCategory::find()->where(['parent_id'=>$id])->all();
            $top = GoodsCategory::findOne(['id'=>$id]);
            $goods = Goods::find()->all();

            $cages =GoodsCategory::find()->where([
                'AND',
                ['>','lft',$top->lft],
                ['<','rgt',$top->rgt],
                ['tree'=>$top->tree]
            ])->all();
            //遍历出传过来的id 对应分类的最下级分类
//        $arr=[];
//        $goods=[];
//        foreach ($cages as $ck){
//            if(!$ck->children){
//                $arr[]=$ck->id;
//            }
//
//        }
//
//        $query = Goods::find();
//        foreach ($arr as $cate_id){
//            $goods[] =$query->where(['goods_category_id'=>$cate_id])->asArray()->all();
//        }
//            var_dump($goods);exit;
            foreach ($cages as $cage){
                if($cage->depth==2){
                    $cate_id[]= $cage->id;
                }
            }
            if(count($cate_id)<=1){
                $cate_id[]= $id;
            }
        }


        return $this->render('list',['cate_id'=>$cate_id,'top'=>$top,'goods'=>$goods,'goodsCategories'=>$goodsCategories,'newGoods'=>$newGoods]);
    }

    public function actionGoods(){
        $goods = Goods::findOne(['id'=>\Yii::$app->request->get('id')]);
        $this->layout = 'list';
        return $this->render('goods',['goods'=>$goods]);
    }


    /**
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionAdd(){
        $goods_id = \Yii::$app->request->post('goods_id');
        $amount = \Yii::$app->request->post('amount');
        if(!Goods::findOne(['id'=>$goods_id])){
            throw new NotFoundHttpException('没有找到该商品');
        }
        //不是用户登录,保存到cookie
        if(\Yii::$app->user->isGuest){
            $cookies = \Yii::$app->request->cookies;
            //判断cookie中是否有cart的cookie
            $cookie = $cookies->get('cart');
            if($cookie==null){
                $cart=[];
            }else{
                //如果有就追加
                $cart = unserialize($cookie->value);
            }
            $cookies = \Yii::$app->response->cookies;
           if(key_exists($goods_id,$cart)){
               $cart[$goods_id]+=$amount;
           }else{
               $cart[$goods_id]=$amount;
           }
           $cookie = new Cookie([
               'name'=>'cart','value'=>serialize($cart)
           ]);
            $cookies->add($cookie);
        }else{
            //登录状态时直接存数据表,
            //如果数据库有该用户数据查找出来,数量更新
            $member_id = \Yii::$app->user->id;
            $user = Cart::find()->where(['member_id'=>$member_id,'goods_id'=>$goods_id])->one();
            $model = new Cart();
            //查找当前用户数据
            if($user){
                $user->amount += $amount;
                $user->save();
            }else{
                $model->member_id = $member_id;
                $model->goods_id = $goods_id;
                $model->amount = $amount;
                $model->save();
            }
        }
        return $this->redirect(['shop/cart']);
    }

    public function actionCart(){
        $this->layout = 'cart';
       $cookies = \Yii::$app->request->cookies;
       $cookie = $cookies->get('cart');
       $model = null;
           if(!$cookie==null){
            $cart = unserialize($cookie->value);
           foreach ($cart as $goods_id => $amount){
               $goods =  Goods::findOne(['id'=>$goods_id])->attributes;
               $goods['amount'] = $amount;
               $model[] = $goods;
           }
       }else{
           $model=null;
           $member_id = \Yii::$app->user->id;
           $carts = Cart::find()->where(['member_id'=>$member_id])->all();
           foreach ($carts as $cart){
               $goods =  Goods::findOne(['id'=>$cart->goods_id])->attributes;
               $goods['amount'] = $cart->amount;
               $model[]=$goods;
           }
       }
       return $this->render('cart',['model'=>$model]);
    }

    public function actionCartUpdate(){
        $goods_id = \Yii::$app->request->post('goods_id');
        $amount = \Yii::$app->request->post('amount');
        if(!Goods::findOne(['id'=>$goods_id])){
            throw new NotFoundHttpException('没有找到该商品');
        }
        //不是用户登录,保存到cookie
        if(\Yii::$app->user->isGuest){
            $cookies = \Yii::$app->request->cookies;
            //判断cookie中是否有cart的cookie
            $cookie = $cookies->get('cart');
            if($cookie==null){
                $cart = [];
            }else{
                //如果有就追加
                $cart = unserialize($cookie->value);
            }
            $cookies = \Yii::$app->response->cookies;
            if($amount){
                $cart[$goods_id]=$amount;
            }else{
                if(key_exists($goods_id,$cart)){
                    unset($cart[$goods_id]);
                }
            }
            $cookie = new Cookie([
                'name'=>'cart','value'=>serialize($cart)
            ]);
            $cookies->add($cookie);
        }else{
            //用户登录 保存到数据表
            $user = \Yii::$app->user->id;
            if($amount){
                $cart = Cart::find()->where(['goods_id'=>$goods_id,'member_id'=>$user])->one();
                $cart->amount = $amount;
                $cart->save();
            }else{
                Cart::deleteAll(['goods_id'=>$goods_id]);
            }

        }

    }

    public function actionDemo(){

        $cl = new \frontend\components\SphinxClient();
        $cl->SetServer ( '127.0.0.1', 9312);
//$cl->SetServer ( '10.6.0.6', 9312);
//$cl->SetServer ( '10.6.0.22', 9312);
//$cl->SetServer ( '10.8.8.2', 9312);
        $cl->SetConnectTimeout ( 10 );
        $cl->SetArrayResult ( true );
// $cl->SetMatchMode ( SPH_MATCH_ANY);
        $cl->SetMatchMode ( SPH_MATCH_ANY);
        $cl->SetLimits(0, 1000);
        $info = '联想笔记本';
        $res = $cl->Query($info, 'goods');//shopstore_search
//print_r($cl);
        print_r($res);
    }

































}