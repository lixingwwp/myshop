<?php
namespace backend\controllers;

use backend\components\RbacFilter;
use frontend\models\Order;
use yii\data\Pagination;
use yii\web\Controller;

class OrderController extends Controller{
    public function behaviors()
    {
        return [
            'rbac' =>[
                'class' => RbacFilter::className(),
                'only'=>['index','edit'],
            ],

        ];
    }
    public function actionIndex(){
        $query = Order::find();
        $total = $query->count();
        $page = new Pagination([
            'totalCount'=>$total,
            'pageSize'=>2
        ]);
        $models = $query->limit($page->limit)->offset($page->offset)->all();
        return $this->render('index',['models'=>$models,'page'=>$page]);
    }
    public function actionEdit(){
        $data = \Yii::$app->request->post();
        $model = Order::findOne(['id'=>$data['id']]);
        $model->status = $data['status'];
        if($model->save()){
            return $this->redirect(['order/index']);
        }
    }
}