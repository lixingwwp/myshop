<?php
namespace backend\controllers;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsIntro;
use backend\models\GoodsPhotos;
use backend\models\GoodsSearchForm;
use Behat\Gherkin\Loader\YamlFileLoader;
use Codeception\Lib\Driver\PostgreSql;
use xj\uploadify\UploadAction;
use app\models\Brand;
use backend\models\Goods;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class GoodsController extends Controller{
    //添加商品
    public function actionAdd(){
        $model = new Goods();
        $brands = Brand::find()->asArray()->all();
        $goodsCategorys = GoodsCategory::find()->asArray()->all();
        $goodsCategorys[]=['id'=>0,'name'=>'顶级分类','parent_id'=>0];
        $goodsIntro = new GoodsIntro();
        $goodsPhotos = new GoodsPhotos();
        if($model->load(\Yii::$app->request->post())&&$goodsIntro->load(\Yii::$app->request->post())&&$model->validate()){
            //添加sn编号
            //获取当前日期
            $now = date('Ymd',time());
//            var_dump($now);exit;
            //在goods_day_count表中查找是否有这条记录
            $list = GoodsDayCount::findOne(['day'=>$now]);
            $counts = new GoodsDayCount();
            if(!$list){
               $counts->day=$now;
               $counts->count++;
               $counts->save();
            }else{
                $list->count++;
                $list->save();
            }
            $model->sn = $now.str_pad($list->count,5,'0',STR_PAD_LEFT);
            $model->save();
            var_dump($goodsPhotos->photo);
            $last_id = \Yii::$app->db->getLastInsertID();

            $goodsIntro->goods_id = $last_id;
            $goodsIntro->save();
            \Yii::$app->session->setFlash('success','商品添加成功');
            return $this->redirect(['goods/index']);
        }else{
            var_dump($model->getErrors());
        }

        return $this->render('add',['model'=>$model,'brands'=>$brands,'goodsCategorys'=>$goodsCategorys,'goodsIntro'=>$goodsIntro,'goodsPhotos'=>$goodsPhotos]);
    }

    public function actions() {
        return [
            'ueditor' => [
                'class' => 'crazyfd\ueditor\Upload',
                'config'=>[
                    'uploadDir'=>date('Y/m/d')
                ]

            ],
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filename = sha1_file($action->uploadfile->tempName);
                    return "{$filename}.{$fileext}";
                },
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png','gif'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    $action->output['fileUrl'] = $action->getWebUrl();
                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                },
            ],
        ];
    }

    public function actionIndex(){
        $model = new GoodsSearchForm();
        $query =  Goods::find();
        $model->search($query);
        $count = $query->count();
        $pages = new Pagination([
           'totalCount' => $count,
            'pageSize' => 5,
        ]);
        $goods = $query->offset($pages->offset)->limit($pages->limit)->all();
        $brands = Brand::find()->all();
        $goodscategory = GoodsCategory::find()->all();
        return $this->render('index',['goods'=>$goods,'brands'=>$brands,
            'goodscategory'=>$goodscategory,'pages'=>$pages,'model'=>$model]);

    }

    public function actionEdit($id){
        $model =  Goods::findOne(['id'=>$id]);
        $goodsIntro = GoodsIntro::findOne(['goods_id'=>$id]);
        $brands = Brand::find()->asArray()->all();
        $goodsCategorys = GoodsCategory::find()->asArray()->all();
        $goodsCategorys[]=['id'=>0,'name'=>'顶级分类','parent_id'=>0];
        $goodsPhotos = new GoodsPhotos();

        if($model->load(\Yii::$app->request->post())&&$goodsIntro->load(\Yii::$app->request->post())&&$model->validate()){
            //添加sn编号
            //获取当前日期
            $now = date('Ymd',time());
//            var_dump($now);exit;
            //在goods_day_count表中查找是否有这条记录

            $model->save();

            $last_id = \Yii::$app->db->getLastInsertID();

            $goodsIntro->goods_id = $last_id;
            $goodsIntro->save();
            \Yii::$app->session->setFlash('success','商品修改成功');
            return $this->redirect(['goods/index']);
        }else{
            var_dump($model->getErrors());
        }

        return $this->render('add',['model'=>$model,'brands'=>$brands,'goodsCategorys'=>$goodsCategorys,'goodsIntro'=>$goodsIntro,'goodsPhotos'=>$goodsPhotos]);
    }

    public function actionDel($id){
        $model = Goods::findOne(['id'=>$id]);
        $model->status=0;
        $model->save();
        return $this->redirect(['goods/index']);
    }

    public function actionTest(){
        $goodsPhotos = new GoodsPhotos();

        if(\Yii::$app->request->post()){
            var_dump(\Yii::$app->request->post());
            $goodsPhotos->imgFiles = UploadedFile::getInstances($goodsPhotos,'photo');
            var_dump($goodsPhotos->imgFiles);exit;
        }
        return $this->render('test',['goodsPhotos'=>$goodsPhotos]);
    }

    public function actionGallery($id){
        $goods = Goods::findOne(['id'=>$id]);
        if($goods==null){
            throw new NotFoundHttpException('商品不存在');
        }
        return $this->render('gallery',['goods'=>$goods]);
    }
}