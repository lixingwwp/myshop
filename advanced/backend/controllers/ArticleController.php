<?php
namespace backend\controllers;

use app\models\ArticleCategory;
use backend\models\Article;
use backend\models\ArticleDetail;
use yii\data\Pagination;
use yii\web\Controller;

class ArticleController extends Controller{

    public function actionAdd(){
        //实例化文章对象
        $article = new Article();
        //查找所有分类用于在视图中提供下拉选择框
        $article_category = ArticleCategory::find()->all();
        //实例化文章详情对象
        $article_detail = new ArticleDetail();
        $request=\Yii::$app->request;
        if($request->isPost){
            $article_detail->load($request->post());
            $article->load($request->post());

            if($article->validate()&&$article_detail->validate()){
                //保存2个模型对象属性
                //先保存文章表
                $article->save();
                //获得上次插入的id,将此id保存到详情表的关联字段
                $article_detail->article_id = \Yii::$app->db->getLastInsertID();
                $article_detail->save();
                \Yii::$app->session->setFlash('success','新增文章成功');
                return $this->redirect(['article/index']);
            }else{
                \Yii::$app->session->setFlash('success','新增文章失败');
                return $this->redirect(['article/add']);
            }
        }
        return $this->render('add',['article'=>$article,'article_detail'=>$article_detail,'article_category'=>$article_category]);
    }

    public function actionIndex(){
        $query = Article::find();
        $cate = ArticleCategory::find()->all();
        $total = $query->count();
        $pages = new Pagination([
           'totalCount'=>$total,
            'pageSize'=>2
        ]);
        $models = $query->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('index',['models'=>$models,'pages'=>$pages,'cate'=>$cate]);
    }

    public function actionEdit($id){
        $article = Article::findOne(['id'=>$id]);
        //查找所有分类用于在视图中提供下拉选择框
        $article_category = ArticleCategory::find()->all();
        //实例化文章详情对象
        $article_detail =ArticleDetail::findOne(['article_id'=>$id]);
        $request=\Yii::$app->request;
        if($request->isPost){
            $article_detail->load($request->post());
            if($article->validate()&&$article_detail->validate()){
                //保存2个模型对象属性
                //先保存文章表
                $article->save();
                $article_detail->save();
                \Yii::$app->session->setFlash('success','新增文章成功');
                return $this->redirect(['article/index']);
            }else{
                \Yii::$app->session->setFlash('success','新增文章失败');
                return $this->redirect(['article/add']);
            }
        }

        return $this->render('add',['article'=>$article,'article_detail'=>$article_detail,'article_category'=>$article_category]);
    }
    public function actionDel($id){
        Article::deleteAll(['id'=>$id]);
        \Yii::$app->session->setFlash(['success','文章删除成功']);
        return $this->redirect(['article/index']);
    }

    public function actionDetail($id){
        $article = Article::findOne(['id'=>$id]);
        $detail = ArticleDetail::findOne(['article_id'=>$id]);
        return $this->render('detail',['article'=>$article,'detail'=>$detail]);
    }

    public function actions()
    {
        return [

            'ueditor' => [
                'class' => 'crazyfd\ueditor\Upload',
                'config'=>[
                    'uploadDir'=>date('Y/m/d')
                ]

            ],
        ];
    }
}
