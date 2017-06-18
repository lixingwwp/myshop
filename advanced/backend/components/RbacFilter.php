<?php
namespace backend\components;

use yii\base\ActionFilter;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\web\HttpException;

class RbacFilter extends ActionFilter{

    public function beforeAction($action)
    {
        $user = \Yii::$app->user;
        //这里的路由就是权限名称,如果没有权限报错
        if(!$user->can($action->uniqueId)){
            if($user->isGuest){
                $action->controller->redirect([$user->loginUrl]);
            }
            throw new HttpException('403','你没有权限访问');
            return false;
        }
        return parent::beforeAction($action);
    }
}
