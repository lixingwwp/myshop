<?php
namespace backend\controllers;
use backend\models\GoodsCategory;
use Symfony\Component\Yaml\Yaml;
use yii\web\Controller;

/**
 * Created by PhpStorm.
 * User: le
 * Date: 2017/6/11
 * Time: 14:31
 */
class GoodsCategoryController extends Controller{

    public function actionAdd(){
        $options = GoodsCategory::find()->asArray()->all();
        //添加顶级分类
        $options[]=['parent_id'=>0,'id'=>0,'name'=>'顶级分类'];
        $model = new GoodsCategory();
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            if($model->parent_id){//如果父id==0 添加一级目录
                $parent = GoodsCategory::findOne(['id'=>$model->parent_id]);//找到父节点
                $model->prependTo($parent);
            }else{//否则添加子目录
                 $model->makeRoot();
            }
            \Yii::$app->session->setFlash('success','分类添加成功');
            return $this->redirect(['goods-category/index']);
        }else{
            var_dump($model->getErrors());
        }

        return $this->render('add',['model'=>$model,'options'=>$options]);
    }

    public function actionEdit($id){
        $options = GoodsCategory::find()->asArray()->all();
        //添加顶级分类
        $options[]=['parent_id'=>0,'id'=>0,'name'=>'顶级分类'];
        $model = GoodsCategory::findOne(['id'=>$id]);
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            if($model->parent_id){//如果父id==0 添加一级目录
                $parent = GoodsCategory::findOne(['id'=>$model->parent_id]);//找到父节点
                $model->prependTo($parent);
            }else{//否则添加子目录
                $model->makeRoot();
            }
            \Yii::$app->session->setFlash('success','分类添加成功');
            return $this->redirect(['goods-category/index']);
        }else{
            var_dump($model->getErrors());
        }

        return $this->render('add',['model'=>$model,'options'=>$options]);
    }
    public function actionTest(){
        $fi = new GoodsCategory();
        $fi->parent_id = 0;
        $fi->name = "大家电";
        $fi->makeRoot();

        $parent = GoodsCategory::findOne(['id'=>1]);
        $se = new GoodsCategory();
        $se->name = '小家电';
        $se->parent_id = $parent->id;
        $se->prependTo($parent);
    }
    public function actionZtree(){
        $options = GoodsCategory::find()->asArray()->all();

        return $this->renderPartial('ztree');
    }

    public function actionIndex(){
        $categorys = GoodsCategory::find()->orderBy('tree,lft')->all();
        return $this->render('index',['categorys'=>$categorys]);

    }

    public function actionDel($id){
        //查找当前要删除的分类
       $category = GoodsCategory::findOne(['id'=>$id]);
       $categorys = GoodsCategory::find()->all();
       $total=0;
       //循环遍历所有分类,如果有子分类就不能删除
       foreach($categorys as $v){
           if($category->tree==$v->tree&&$v->lft>$category->lft&&$v->rgt<$category->rgt){
               $total++;
               if($total>1){
                   break;
               }
           }
       }
       if(!$total){
           GoodsCategory::deleteAll(['id'=>$id]);
               \Yii::$app->session->setFlash('warning',$category->name.'分类删除成功');
       }else{
           \Yii::$app->session->setFlash('danger','对不起,该分类下有子分类,不能删除');
       }
        return $this->redirect(['goods-category/index']);
    }

}