<?php
namespace backend\controllers;
use backend\components\RbacFilter;
use xj\uploadify\UploadAction;
use app\models\Brand;
use yii\data\Pagination;
use crazyfd\qiniu\Qiniu;

class BrandController extends \yii\web\Controller{

    public function behaviors()
    {
        return [
            'rbac' => RbacFilter::className(),
        ];
    }

    public function actionAdd(){
        $model = new Brand();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
           // $model->imgFile = \yii\web\UploadedFile::getInstance($model,'imgFile');
            if($model->validate()){
//                if($model->imgFile){
//                    $fileName = '/images/brand/'.uniqid().'.'.$model->imgFile->extension;
//                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
//                    $model->logo = $fileName;
//                }
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['brand/index']);
            }

        }
        return $this->render('add',['model'=>$model]);
    }

    public function actionIndex(){
        $query = Brand::find();
        $total = $query->count();
        $page = new Pagination([
            'totalCount'=>$total,
            'pageSize'=>2,
        ]);
        $models = $query->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['models'=>$models,'page'=>$page]);
    }

    public function actionEdit($id){
        $model = Brand::findOne(['id'=>$id]);
//        var_dump($model);exit;
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            // $model->imgFile = \yii\web\UploadedFile::getInstance($model,'imgFile');
            if($model->validate()){
//                if($model->imgFile){
//                    $fileName = '/images/brand/'.uniqid().'.'.$model->imgFile->extension;
//                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
//                    $model->logo = $fileName;
//                }
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['brand/index']);
            }
        }

        return $this->render('add',['model'=>$model]);
    }

    public function actionDel($id){
         Brand::deleteAll(['id'=>$id]);
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['brand/index']);
    }


    public function actions() {
        return [
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

                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                    $url = $action->getWebUrl();
                    $qiniu = \Yii::$app->niu;
                    $qiniu->uploadFile(\Yii::getAlias('@webroot').$url,$url);
                    $res_url = $qiniu->getLink($url);
                    $action->output['fileUrl'] =  $res_url;
                },
            ],
        ];
    }

    public function actionTest(){
        $ak = 'OwC1zk6YU4cmtR4hQc5jD77jQRF6iahDwPECS0OU';
        $sk = 'PiCoiSC961uJ7MHi5lyg0RexDLBEznIVi9nb-zEw';
        $domain = 'http://or9rzgn4u.bkt.clouddn.com/';
        $bucket = 'leephp';
        $qiniu = new Qiniu($ak, $sk,$domain, $bucket);
        $fileNmmes = \Yii::getAlias('@webroot').'/upload/uEtY-fycstxp4898764.jpg';
        $key = 'uEtY-fycstxp4898764.jpg';
        $qiniu->uploadFile($fileNmmes,$key);
        $url = $qiniu->getLink($key);
        var_dump($url);
    }


}
