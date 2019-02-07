<?php

namespace app\controllers;

use Yii;
use app\models\HelpConsultaGestion;
use app\models\HelpConsultaGestionSearch;
use app\models\ConsultasGestion;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * HelpconsultagestionController implements the CRUD actions for HelpConsultaGestion model.
 */
class HelpconsultagestionController extends Controller
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
     * Lists all HelpConsultaGestion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new HelpConsultaGestionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single HelpConsultaGestion model.
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
     * Creates a new HelpConsultaGestion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new HelpConsultaGestion();

        $preguntas=ConsultasGestion::find()->all();

        $list_preguntas=ArrayHelper::map($preguntas,'id','descripcion');



        $registros=$model->find()->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            Yii::$app->session->setFlash('success','Creado correctamente');
            return $this->redirect(['create']);

        } else {
            return $this->render('create', [
                'model' => $model,
                'help'=>'active',
                'preguntas'=>$list_preguntas,
                'registros'=>$registros
            ]);
        }
    }

    /**
     * Updates an existing HelpConsultaGestion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $preguntas=ConsultasGestion::find()->all();

        $list_preguntas=ArrayHelper::map($preguntas,'id','descripcion');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            Yii::$app->session->setFlash('success','Actualizado correctamente');
            return $this->redirect(['create']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'preguntas'=>$list_preguntas
            ]);
        }
    }

    /**
     * Deletes an existing HelpConsultaGestion model.
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
     * Finds the HelpConsultaGestion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return HelpConsultaGestion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = HelpConsultaGestion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
