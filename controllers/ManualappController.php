<?php

namespace app\controllers;

use Yii;
use app\models\ManualApp;
use app\models\ManualAppSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ManualappController implements the CRUD actions for ManualApp model.
 */
class ManualappController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ManualApp models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ManualAppSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ManualApp model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ManualApp model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ManualApp();

        $query=$model->find()->all();

        if ($model->load(Yii::$app->request->post()) /*&& $model->save()*/) {
            Yii::$app->params['uploadPath'] = Yii::$app->basePath . '/web/uploads/manual/';
            $shortPath = '/uploads/manual/';       
            $pdf = UploadedFile::getInstances($model, 'pdf');
            $cantidad=count($pdf);

            if ($cantidad>0) {

                $ext = end((explode(".", $pdf[0]->name)));
                $name = date('Ymd').rand(1, 10000).''.$pdf[0]->name;
                $path = Yii::$app->params['uploadPath'] . $name;
                $model->setAttribute('archivo',$shortPath. $name);
                $pdf[0]->saveAs($path);
            }

            $model->save();

            return $this->redirect(['create']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'query'=>$query
            ]);
        }
    }

    /**
     * Updates an existing ManualApp model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) /*&& $model->save()*/) {
            Yii::$app->params['uploadPath'] = Yii::$app->basePath . '/web/uploads/manual/';
            $shortPath = '/uploads/manual/';       
            $pdf = UploadedFile::getInstances($model, 'pdf');
            $cantidad=count($pdf);

            if ($cantidad>0) {
                if($model->archivo!=''){
                    unlink(Yii::$app->basePath .'/web'.$model->archivo);
                }
                $ext = end((explode(".", $pdf[0]->name)));
                $name = date('Ymd').rand(1, 10000).''.$pdf[0]->name;
                $path = Yii::$app->params['uploadPath'] . $name;
                $pdf[0]->saveAs($path);
                $model->setAttribute('archivo',$shortPath. $name);
            }

            
          
           
            $model->save();


            return $this->redirect(['create']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ManualApp model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
       $model=$this->findModel($id);

        if($model->archivo!=''){

            unlink(Yii::$app->basePath .'/web'.$model->archivo);
        }
        
        $model->delete();

        return $this->redirect(['create']);

        
    }

    public function actionManual(){
        $model = new ManualApp();

        $query=$model->find()->all();

        return $this->render('manual', [
                'model' => $model,
                'query'=>$query
            ]);
    }

    /**
     * Finds the ManualApp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ManualApp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ManualApp::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
