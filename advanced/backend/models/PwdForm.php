<?php
namespace backend\models;

use yii\base\Model;

class PwdForm extends Model{
    public $oldpassword;
    public $password;
    public $repassword;

    public function rules()
    {
        return [
          [['oldpassword','password','repassword'],'required'],
            ['repassword','compare','compareAttribute'=>'password'],
            ['oldpassword','validateOldpassword']
        ];


    }

    public function attributeLabels()
    {
        return [
          'oldpassword'=>'旧密码',
            'password'=>'新密码',
            'repassword'=>'确认新密码'
        ];
    }

    public function validateOldpassword(){
       $id = \Yii::$app->user->id;
       $old = User::findOne(['id'=>$id]);
       if(!\Yii::$app->security->validatePassword($this->oldpassword,$old->password_hash)){
           $this->addError('oldpassword','旧密码不正确');
       }
    }

}


