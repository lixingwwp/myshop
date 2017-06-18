<?php
namespace backend\widgets;

use backend\models\Menus;
use yii\base\Widget;
use yii\bootstrap\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use Yii;

class NavWidget extends Widget{
    public function run(){
        NavBar::begin([
            'brandLabel' => '商城后台管理系统',
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);


        $menuItems = [
            ['label' => '首页', 'url' => ['brand/index']],
        ];
        if (Yii::$app->user->isGuest) {
            $menuItems[] = ['label' => '登录', 'url' => Yii::$app->user->loginUrl];
        } else {
            $menuItems[] =['label' => '注销('.Yii::$app->user->identity->username.')', 'url' => ['account/logout']];

            $menuItems[] =['label' => '修改密码', 'url' => ['account/repwd']];

            $menus = Menus::findAll(['parent_id'=>0]);
//            $menuItems[] = ['label'=>'用户管理','items'=>[
//               ['label'=>'添加用户','url'=>['admin/add']],
//               ['label'=>'用户列表','url'=>['admin/index']]
//           ]];

           // 遍历一级分类
            foreach($menus as $menu){
                $item =['label' =>$menu->label,'items'=>[]];
                //每个一级分类下面有二级分类,继续遍历二级分类
                foreach ($menu->children as $child){
                    if(Yii::$app->user->can($child->url)){
                        $item['items'][] = ['label'=>$child->label,'url'=>[$child->url]];
                    }
                }
                if(!empty($item['items'])){
                    $menuItems[] = $item;
                }

            }
        }
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => $menuItems,
        ]);
        NavBar::end();
    }
}