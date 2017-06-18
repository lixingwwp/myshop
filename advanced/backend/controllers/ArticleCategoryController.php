<?php
namespace backend\controllers;

use app\models\ArticleCategory;
use backend\components\RbacFilter;
use yii\data\Pagination;
use yii\web\Controller;

class ArticleCategoryController extends Controller{
    public function behaviors()
    {
        return [
            'rbac' => RbacFilter::className(),
        ];
    }

    public function actionAdd(){
        $model = new ArticleCategory();
        $result = \Yii::$app->request;
        if($result->isPost){
            $model->load($result->post());
            //验证数据
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','恭喜你添加文章分类成功');
                return $this->redirect(['article-category/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    public function actionIndex(){
        $query = ArticleCategory::find();
        $total = $query->count();
        //实例分页对象
        $pages = new Pagination([
           'totalCount'=> $total,
            'pageSize'=>2
        ]);
        $models = $query->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('index',['models'=>$models,'pages'=>$pages]);
    }

    public function actionEdit($id){
        $model = ArticleCategory::findOne(['id'=>$id]);
        $result = \Yii::$app->request;
        if($result->isPost){
            $model->load($result->post());
            //验证数据
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','恭喜你修改文章分类成功');
                return $this->redirect(['article-category/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    public function actionDel($id){
       if(ArticleCategory::deleteAll(['id'=>$id])){
           \Yii::$app->session->setFlash('success','删除成功');

       }else{
           \Yii::$app->session->setFlash('danger','删除失败');
       }
        return $this->redirect(['article-category/index']);

    }
}
