<?php

namespace app\controllers;

use Yii;
use app\models\NovedadCategoriaVisita;
use app\models\CategoriaVisita;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * NovedadCategoriaVisitaController implements the CRUD actions for NovedadCategoriaVisita model.
 */
class NovedadCategoriaVisitaController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['index', 'view', 'create', 'update', 'delete','disabled'],
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['index', 'view', 'create', 'update', 'delete','disabled'],
                        'roles'   => ['@'], //para usuarios logueados
                    ],
                ],  
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all NovedadCategoriaVisita models.
     * @return mixed
     */
    public function actionIndex()
    {
        $novedades = NovedadCategoriaVisita::find()->orderBy(['nombre' => SORT_ASC])->all();
		
        return $this->render('index', [
            'novedades' => $novedades,
        ]);
    }

    /**
     * Displays a single NovedadCategoriaVisita model.
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
     * Creates a new NovedadCategoriaVisita model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new NovedadCategoriaVisita();
		$categorias = CategoriaVisita::find()->orderBy(['nombre' => SORT_ASC])->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        } else {
            return $this->render('create', [
                'model' => $model,
				'categorias' => $categorias,
            ]);
        }
    }

    /**
     * Updates an existing NovedadCategoriaVisita model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$categorias = CategoriaVisita::find()->orderBy(['nombre' => SORT_ASC])->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        } else {
            return $this->render('update', [
                'model' => $model,
				'categorias' => $categorias,
				
            ]);
        }
    }

    /**
     * Deletes an existing NovedadCategoriaVisita model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionDisabled($id){

        $model = $this->findModel($id);
        $model->setAttribute('estado', 'I');
        $model->save();
        return $this->redirect(['novedad-categoria-visita/index']);
    }

    public function actionEnabled($id){

        $model = $this->findModel($id);
        $model->setAttribute('estado', 'A');
        $model->save();
        return $this->redirect(['novedad-categoria-visita/index']);
    }
    /**
     * Finds the NovedadCategoriaVisita model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return NovedadCategoriaVisita the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = NovedadCategoriaVisita::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
