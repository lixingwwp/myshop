<?php
namespace backend\controllers;

use backend\models\Menus;
use Symfony\Component\Yaml\Yaml;
use yii\web\Controller;

class MenusController extends Controller{
    public function actionAddMenus(){
        $model = new Menus();
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            if($model->save()){
                \Yii::$app->session->setFlash('success','添加成功');
            }else{
                \Yii::$app->session->setFlash('danger','添加失败');
            }
            return $this->redirect(['menus/index']);
        }
        return $this->render('add',['model'=>$model]);
    }

    public function actionIndex(){
        $models = Menus::find()->all();
        return $this->render('index',['models'=>$models]);
    }

    public function actionEdit($id){
        $model = Menus::findOne(['id'=>$id]);
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            if($model->save()){
                \Yii::$app->session->setFlash('success','修改成功');
            }else{
                \Yii::$app->session->setFlash('danger','修改失败');
            }
            return $this->redirect(['menus/index']);
        }
        return $this->render('add',['model'=>$model]);
    }

    public function actionDel($id){
        //如果该id下有子类不能删除
        if(Menus::find()->where(['parent_id'=>$id])->all()){
            \Yii::$app->session->setFlash('danger','该类有子分类不能删除');
        }else{
            $model = Menus::deleteAll(['id'=>$id]);
            \Yii::$app->session->setFlash('access','删除成功');
        }
        return $this->redirect(['menus/index']);
    }
}