<?php
namespace backend\models;

use Codeception\Exception\ElementNotFound;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\rbac\Permission;
use yii\rbac\Role;
use yii\web\NotFoundHttpException;

class RoleForm extends Model{
    public $name;
    public $description;
    public $permissions=[];

    static public function getPermissions(){
        $authManager = \Yii::$app->authManager;
      return  ArrayHelper::map($authManager->getPermissions(),'name','description');
    }

    public function getOptions(Role $role){
        $this->name = $role->name;
        $this->description = $role->description;
        $authManager = \Yii::$app->authManager;

        foreach ($authManager->getPermissionsByRole($role->name) as $permission){
            $this->permissions[]=$permission->name;
        }
    }

    public function rules()
    {
        return [
            [['name','description','permissions'],'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
          'name'=>'角色名 ',
            'description'=>'描述',
            'permissions'=>'权限',

        ];
    }

    public function addRole(){
        $authManager = \Yii::$app->authManager;
        if($authManager->getRole($this->name)){
           $this->addError('name','该角色已存在');
        }else{
            $role = $authManager->createRole($this->name);
            $role->description = $this->description;
            if($authManager->add($role)){
                foreach($this->permissions as $permissionOne){
                    $permission = $authManager->getPermission($permissionOne);
                    if($permission){
                        $authManager->addChild($role,$permission);
                    }
                }
                return true;
            }
        }
        return false;

    }

    public function editRole($name){
        $authManager = \Yii::$app->authManager;
        $role = $authManager->getRole($name);
        $role->name =  $this->name;
        $role->description = $this->description;
        //如果数据修改过,并且数据库有数据,提示错误
        if($name!=$this->name && $authManager->getRole($name)){
            $this->addError('name','该角色已存在');
        }else{
            if($authManager->update($name,$role)){
                //移除权限
                $authManager->removeChildren($role);
                foreach ($this->permissions as $permissionName){
                    if($permissionName){
                        $permission = $authManager->getPermission($permissionName);
                        $authManager->addChild($role,$permission);
                    }
                }
            return true;
            }
        }
        return false;
    }
}