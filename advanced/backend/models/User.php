<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public $password;
    public $code;
    public $roles=[];

    //获得全部角色,分配到视图的多选按钮,供用户选择
    static public function role(){
        $authManager = Yii::$app->authManager;
        $roles = $authManager->getRoles();
        return ArrayHelper::map($roles,'name','description');
    }

    //获得修改传过来的id的数据(作为回显);
    public function getOptions($id){
        $authManager = Yii::$app->authManager;
        $roles = $authManager->getRolesByUser($id);
        foreach($roles as $roleOne){
            if($roleOne){
                $roles[]=$roleOne->description;
            }
        }
        $this->roles = $roles;
    }

    //修改分配角色
    public function editRole($id){
        $authManager = Yii::$app->authManager;
        //删除当前对象原来的角色
        $authManager->revokeAll($id);
        //添加新角色
        foreach($this->roles as $roleOne){
//            if($roleOne){
                $role = $authManager->getRole($roleOne);
                $authManager->assign($id,$role);
//            }
        }
        return true;

    }

    public function addUser($id){
        $authManager = Yii::$app->authManager;
        if($this->roles){
            $authManager->revokeAll($id);
            foreach ($this->roles as $roleName){
                $role = $authManager->getRole($roleName);
                if($role) $authManager->assign($role,$id);
            }
            return true;
        }
        return false;
    }
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email','password','roles'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['username'], 'unique'],
            ['email','email'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
            ['code','captcha','captchaAction'=>'user/captcha']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => '邮箱',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'password'=>'密码',
            'code'=>'验证码',
            'roles'=>'角色'
        ];
    }
    public function beforeSave($insert)
    {
        if($insert){
            $this->created_at = time();
//            var_dump(Yii::$app->security->generateRandomString());
            $this->auth_key = Yii::$app->security->generateRandomString();

        }else{
            $this->updated_at = time();
        }
        return parent::beforeSave($insert);
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id'=>$id]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
       return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() == $authKey;
    }
}
