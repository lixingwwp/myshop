<?php
namespace backend\controllers;

use backend\models\User;
use yii\data\Pagination;
use yii\web\Controller;

class UserController extends Controller{
    public function actionAdd(){
        $model = new User();
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password);
            $model->save(false);
            \Yii::$app->session->setFlash('success','添加用户成功');
            return $this->redirect(['user/index']);
        }
        return $this->render('add',['model'=>$model]);
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength'=>4,
                'maxLength'=>4
            ],
        ];
    }

    public function actionIndex(){
        $query = User::find();
        $count = $query->count();
        $pages = new Pagination([
            'totalCount'=>$count,
            'pageSize'=>2
        ]);
        $models = $query->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('index',['models'=>$models,'pages'=>$pages]);
    }

    public function actionEdit($id){
        $model =User::findOne(['id'=>$id]);
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password);
            $model->save(false);
            \Yii::$app->session->setFlash('success','修改用户成功');
            return $this->redirect(['user/index']);
        }
        return $this->render('add',['model'=>$model]);
    }

    public function actionDel($id){
         if(User::deleteAll(['id'=>$id])){
             \Yii::$app->session->setFlash('success','删除用户成功');
         }else{
             \Yii::$app->session->setFlash('danger','删除用户失败');
         }
         return $this->redirect(['user/index']);

    }
}