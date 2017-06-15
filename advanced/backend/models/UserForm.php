<?php
namespace backend\models;

use yii\base\Model;

class UserForm extends Model{
    public $username;
    public $password;
    public $remember;
    public $code;

    public function rules()
    {
        return [
            [['username','password','code'],'required'],
            ['code','captcha','captchaAction'=>'account/captcha'],
            ['username','validateUsername']
        ];

    }

    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password'=>'密码',
            'remember'=>'记住登录',
            'code'=>'验证码'
        ];
    }

    public function validateUsername(){
        $account = User::findOne(['username'=>$this->username]);
        if($account){
            if(\Yii::$app->security->validatePassword($this->password,$account->password_hash)){
                $time = 3600;
                \Yii::$app->user->login($account,$time = 3600);

            }else{
                $this->addError('password','账号或密码错误');
            }
        }else{
            $this->addError('password','账号或密码错误');
        }
    }

//    public function beforeSave($insert)
//    {
//        if($insert){
//            $this->created_at = time();
//        }else{
//            $this->updated_at = time();
//        }
//        return parent::beforeSave($insert);
//    }


}