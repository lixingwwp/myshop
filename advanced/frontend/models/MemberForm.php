<?php
namespace frontend\models;

use yii\base\Model;

class MemberForm extends Model{
    public $code;
    public $rememberMe;
    public $username;
    public $password;
    public $last_login_time;
    public $last_login_ip;
    public $repassword;
    public $oldpassword;


    public function rules()
    {
        return [
          [['username','password','code'],'required'],
            ['username','checkUsername'],
            ['rememberMe','boolean'],
            ['code','captcha','captchaAction'=>'site/captcha'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'code'=>'验证码: ',
            'username'=>'用户名: ',
            'password'=>'密码: ',
            'rememberMe'=>'记住我',
        ];
    }


    public function checkUsername(){
        $member = Member::findOne(['username'=>$this->username]);
        if(!$member->status){
            $this->addError('username','您的账号被封,请联系管理员申诉');
        }
        if($member){
            if(\Yii::$app->security->validatePassword($this->password,$member->password_hash)){
               $time = $this->rememberMe?3600*24*7:0;
               \Yii::$app->user->login($member,$time);
               $member->last_login_ip = ip2long( \Yii::$app->request->userIP);
                $member->last_login_time = time();
                $member->save(false);

            }else{
                $this->addError('password','密码错误');
            }
        }else{
            $this->addError('username','账号不存在');
        }
    }
}