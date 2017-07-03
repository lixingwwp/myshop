<?php
namespace frontend\controllers;

use frontend\models\Address;
use frontend\models\Locations;
use yii\web\Controller;

class AddressController extends Controller{
    public $layout = 'address';
    public function actionIndex(){
        $model = new Address();
        $locations = new Locations();
       if($model->load(\Yii::$app->request->post())&&$model->validate()){
           $model->makeDefault();
           $model->save();
           echo '添加地址成功';
       }
       return $this->render('index',['model'=>$model,'locations'=>$locations]);
    }
    public function actionEdit($id){
        $model = Address::findOne(['id'=>$id]);
        $locations = new Locations();
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            $model->makeDefault();
            $model->save();
            echo '添加地址成功';
        }
        return $this->render('index',['model'=>$model,'locations'=>$locations]);

    }


    //json获取第一个省级下拉数据
    public function actionProvence(){
        $model = Locations::find()->where(['parent_id'=>0])->asArray()->all();
        echo json_encode($model);
    }
    //json获取第二第三个下拉数据
    public function actionArea(){
        //接收id
        $id = \Yii::$app->request->post();
        $model = Locations::find()->where(['parent_id'=>$id])->asArray()->all();
        echo json_encode($model);
    }
}