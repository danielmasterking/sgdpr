<?php

namespace app\controllers;

use Yii;
use app\models\DispositivoAdmin;
use app\models\DispositivoAdminSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Usuario;
use yii\helpers\ArrayHelper;
use app\models\CentroCosto;
use app\models\DetalleDispAdmin;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
/**
 * DispositivoadminController implements the CRUD actions for DispositivoAdmin model.
 */
class DispositivoadminController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [

            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['index', 'view','create','update'],
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['index', 'view','create','update'],
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
     * Lists all DispositivoAdmin models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DispositivoAdminSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $usuario = Usuario::findOne(Yii::$app->session['usuario-exito']);
        if($usuario != null){
          $empresasUsuario = $usuario->empresas;
        }
        $in=" IN(";

        foreach ($empresasUsuario as $key => $value) {
             $in.=" '".$value->empresa->nit."',";    
        }
        $in_final = substr($in, 0, -1).")";

        //echo $in_final;
       $dataProvider=new ActiveDataProvider([
            'query' =>DispositivoAdmin::find()->where(' nit_empresa '.$in_final.' '),
            'pagination' => [
            'pageSize' => 20,
            ],
        ]);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            
        ]);
    }

    /**
     * Displays a single DispositivoAdmin model.
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
     * Creates a new DispositivoAdmin model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DispositivoAdmin();
        $usuario = Usuario::findOne(Yii::$app->session['usuario-exito']);
        $dependencias = CentroCosto::find()->where(['not in', 'estado', ['C']])->orderBy(['nombre' => SORT_ASC])->all();
        if($usuario != null){
                
          $zonasUsuario = $usuario->zonas;
          $marcasUsuario = $usuario->marcas;
          $distritosUsuario = $usuario->distritos;            
          $empresasUsuario = $usuario->empresas;
        }

        $list_empresas=ArrayHelper::map($empresasUsuario,'nit','empresa.nombre');
        // echo "<pre>";
        // print_r($list_empresas);
        // echo "</pre>";
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $dependencias=$_POST['dependencias'];

            foreach ($dependencias as $key => $value) {
                $detalle=new DetalleDispAdmin;

                $detalle->setAttribute('id_disp_admin', $model->id);
                $detalle->setAttribute('cod_dependencia', $value);

                $detalle->save();

            }


            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'list_empresas'=>$list_empresas,
                'zonasUsuario' => $zonasUsuario,
                'marcasUsuario' => $marcasUsuario,
                'distritosUsuario' => $distritosUsuario,
                'dependencias' => $dependencias,
                'empresasUsuario'=>$empresasUsuario
            ]);
        }
    }

    /**
     * Updates an existing DispositivoAdmin model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $usuario = Usuario::findOne(Yii::$app->session['usuario-exito']);
        $dependencias = CentroCosto::find()->where(['not in', 'estado', ['C']])->orderBy(['nombre' => SORT_ASC])->all();
        if($usuario != null){
                
          $zonasUsuario = $usuario->zonas;
          $marcasUsuario = $usuario->marcas;
          $distritosUsuario = $usuario->distritos;            
          $empresasUsuario = $usuario->empresas;
        }

        $list_empresas=ArrayHelper::map($empresasUsuario,'nit','empresa.nombre');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            DetalleDispAdmin::deleteAll(['id_disp_admin' => $model->id]);
            $dependencias=$_POST['dependencias'];

            foreach ($dependencias as $key => $value) {
                $detalle=new DetalleDispAdmin;

                $detalle->setAttribute('id_disp_admin', $model->id);
                $detalle->setAttribute('cod_dependencia', $value);

                $detalle->save();

            }
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'list_empresas'=>$list_empresas,
                'zonasUsuario' => $zonasUsuario,
                'marcasUsuario' => $marcasUsuario,
                'distritosUsuario' => $distritosUsuario,
                'dependencias' => $dependencias,
                'empresasUsuario'=>$empresasUsuario
            ]);
        }
    }

    /**
     * Deletes an existing DispositivoAdmin model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the DispositivoAdmin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DispositivoAdmin the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DispositivoAdmin::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
