<?php
namespace backend\models;

use yii\base\Model;
use yii\rbac\Permission;
use yii\web\NotFoundHttpException;

class PermissionForm extends Model{

    public $name;
    public $description;

    public function rules()
    {
        return [
           [['name','description'],'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name'=>'权限名称',
            'description'=>'描述'
        ];
    }

    public function addPermission(){
        $authManager = \Yii::$app->authManager;
        //查找有没有当前的名称
        if($authManager->getPermission($this->name)){
            $this->addError('name','当前权限名已存在');
        }else{
            $permission = $authManager->createPermission($this->name);
            $permission->description = $this->description;
           return $authManager->add($permission);
        }
        return false;
    }

    //给当前模型对象的成员属性赋值
    public function loader(Permission $permission){
        $this->name = $permission->name;
        $this->description = $permission->description;
    }
    public function update($name){
        $authManager = \Yii::$app->authManager;
        $permission = $authManager->getPermission($name);
        //判断传过来的值不等于修改的值,并且现在修改的值存在
        if($name != $this->name && \Yii::$app->authManager->getPermission($this->name)){
            $this->addError('name','该权限已存在');
        }else{
            $permission->name = $this->name;
            $permission->description = $this->description;
            return $authManager->update($name,$permission);
        }
    }






}