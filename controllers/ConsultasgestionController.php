<?php

namespace app\controllers;

use Yii;
use app\models\ConsultasGestion;
use app\models\ConsultasGestionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ConsultasgestionController implements the CRUD actions for ConsultasGestion model.
 */
class ConsultasgestionController extends Controller
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
     * Lists all ConsultasGestion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ConsultasGestionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ConsultasGestion model.
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
     * Creates a new ConsultasGestion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ConsultasGestion();
        $consulta=$model->find()->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	$model->setAttribute('orden',$_POST['ConsultasGestion']['orden']);
        	$model->save();
            Yii::$app->session->setFlash('success','Creado correctamente');
            return $this->redirect(['create']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'preguntas'=>'active',
                'consulta'=>$consulta
            ]);
        }
    }

    /**
     * Updates an existing ConsultasGestion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	$model->setAttribute('orden',$_POST['ConsultasGestion']['orden']);
        	$model->save();
            Yii::$app->session->setFlash('success','Actualizado correctamente');
            return $this->redirect(['create']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'preguntas'=>'active',
                'actualizar'=>true
            ]);
        }
    }

    /**
     * Deletes an existing ConsultasGestion model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success','Eliminado correctamente');
        return $this->redirect(['create']);
    }

    /**
     * Finds the ConsultasGestion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ConsultasGestion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ConsultasGestion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionCambiarEstado($id,$estado){
        $model = $this->findModel($id);
        $model->setAttribute('estado',$estado);
        $model->save();
        return $this->redirect(['create']);
    }
}
