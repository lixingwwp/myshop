<?php
namespace backend\controllers;

use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class RbacController extends Controller{

    public function actionAddPermission(){
       $model = new PermissionForm();
       if($model->load(\Yii::$app->request->post())&&$model->validate()){
           if($model->addPermission()){
               \Yii::$app->session->setFlash('success','添加成功');
               return $this->redirect(['permission-index']);
           }
       }

       return $this->render('add-permission',['model'=>$model]);
    }

    public function actionPermissionIndex(){
        $models = \Yii::$app->authManager->getPermissions();
        return $this->render('permission-index',['models'=>$models]);

    }

    public function actionEditPermission($name){
        $permission = \Yii::$app->authManager->getPermission($name);
        if(!$permission){
            throw new NotFoundHttpException('该权限不存在');
        }
        $model = new PermissionForm();
        $model->loader($permission);
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            if($model->update($name)){
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['permission-index']);
            }
        }

        return $this->render('add-permission',['model'=>$model]);
    }

    public function actionDelPermission($name){
        $authManager = \Yii::$app->authManager;
        $permission = $authManager->getPermission($name);
        if($authManager->getPermission($name)==null){
            throw new NotFoundHttpException('权限不存在');
        }
        $authManager->remove($permission);
        \Yii::$app->session->setFlash('success','删除权限成功');
        return $this->redirect(['permission-index']);

    }

    public function actionAddRole(){
        $model = new RoleForm();
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            if($model->addRole()){
//                var_dump($model->permissions);exit;
                \Yii::$app->session->setFlash('success','添加角色成功');
                return $this->redirect(['role-index']);
            }
        }
        return $this->render('add-role',['model'=>$model]);
    }

    public function actionRoleIndex(){
        $models = \Yii::$app->authManager->getRoles();
        return $this->render('role-index',['models'=>$models]);
    }

    public function actionRoleEdit($name){
        $authManager = \Yii::$app->authManager;
        $role = $authManager->getRole($name);
        if(!$role){
            throw new NotFoundHttpException('该角色不存在');
        }
        $model = new RoleForm();
        $model->getOptions($role);
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            if($model->editRole($name)){
                \Yii::$app->session->setFlash('success','修改角色成功');
            }else{
                \Yii::$app->session->setFlash('danger','修改角色失败');
            }
            return $this->redirect(['role-index']);
        }

        return $this->render('add-role',['model'=>$model]);
    }
    public function actionRoleDel($name){
        $authManager = \Yii::$app->authManager;
        $role = $authManager->getRole($name);
        if(!$role){
            throw new NotFoundHttpException('该角色不存在');
        }else{
            if($authManager->removeChildren($role)){
                $authManager->remove($role);
                \Yii::$app->session->setFlash('success','角色删除成功');
            }
            return $this->redirect(['role-index']);
        }

    }

}