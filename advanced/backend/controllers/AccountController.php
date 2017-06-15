<?php
/**
 * Created by PhpStorm.
 * User: le
 * Date: 2017/6/14
 * Time: 16:35
 */
namespace backend\controllers;

use backend\models\PwdForm;
use backend\models\User;
use backend\models\UserForm;
use common\models\LoginForm;
use yii\web\Controller;

class AccountController extends Controller{
    public function actionLogin(){
        $model = new UserForm();
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            $id = \Yii::$app->user->id;
            $user = User::findOne(['id'=>$id]);
            $user->login_ip= \Yii::$app->request->userIP;
            $user->updated_at=time();
            $user->status = 1;
            $user->save(false);
            \Yii::$app->session->setFlash('success','恭喜你登录成功');
            \Yii::$app->user->isGuest;
            return $this->redirect(['user/index']);
        }else{
            $model->getErrors();
        }
        return $this->render('login',['model'=>$model]);
    }

    public function actionLogout(){
        $id = \Yii::$app->user->id;
        $user = User::findOne(['id'=>$id]);
        $user->status = 0;
        $user->save(false);
        \Yii::$app->user->logout();
        return $this->redirect(['account/login']);
    }
    public function actionRepwd(){
         $id = \Yii::$app->user->id;
        $user = User::findOne(['id'=>$id]);
        $model = new PwdForm();
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            $user->password_hash = $model->password;
            $user->password_hash = \Yii::$app->security->generatePasswordHash($user->password_hash);
            if($user->save(false)){
                \Yii::$app->session->setFlash('success','密码修改成功');
            }
            return $this->redirect(['user/index']);
        }
        return $this->render('repwd',['model'=>$model]);
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

}

