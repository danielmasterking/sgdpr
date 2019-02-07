<?php

namespace app\controllers;

use Yii;
use app\models\Notificacion;
use app\models\NotificacionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use app\models\Usuario;
use app\models\Zona;
use app\models\NotificacionUsuario;
use app\models\NotificacionZona;
/**
 * NotificacionController implements the CRUD actions for Notificacion model.
 */
class NotificacionController extends Controller
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
     * Lists all Notificacion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NotificacionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $count=Notificacion::find()->count();

        //echo $count;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'count'=>$count
        ]);
    }

    /**
     * Displays a single Notificacion model.
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
     * Creates a new Notificacion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Notificacion();
        $usuarios=Usuario::find()->all();
        $list_usuarios=ArrayHelper::map($usuarios,'usuario','usuario');

        $zonas=Zona::find()->all();
        $list_zonas=ArrayHelper::map($zonas,'id','nombre');

        if ($model->load(Yii::$app->request->post()) /*&& $model->save()*/) {
            $model->save();
            $usuarios=$_POST['usuarios'];
            $zonas=$_POST['zonas'];


            if (isset($usuarios)) {
               

                foreach ($usuarios as $user) {
                   $notificacion_usuario=new NotificacionUsuario();
                   $notificacion_usuario->setAttribute('id_notificacion',$model->id);
                   $notificacion_usuario->setAttribute('usuario',$user);
                   $notificacion_usuario->save();
                }
            }

            if (isset($zonas)) {
               
                foreach ($zonas as $zona) {
                   $notificacion_zona=new NotificacionZona();
                   $notificacion_zona->setAttribute('id_notificacion',$model->id);
                   $notificacion_zona->setAttribute('id_zona',$zona);
                   $notificacion_zona->save();
                }
            }

            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'usuarios'=>$list_usuarios,
                'zonas'=>$list_zonas
            ]);
        }
    }

    /**
     * Updates an existing Notificacion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $usuarios=Usuario::find()->all();
        $list_usuarios=ArrayHelper::map($usuarios,'usuario','usuario');

        $zonas=Zona::find()->all();
        $list_zonas=ArrayHelper::map($zonas,'id','nombre');

        $usuarios_notificacion=NotificacionUsuario::find()->where('id_notificacion='.$model->id)->all();
        $array_user=[];
        foreach ($usuarios_notificacion as $user) {
            $array_user[]=$user->usuario;
        }


        $zona_notificacion=NotificacionZona::find()->where('id_notificacion='.$model->id)->all();

        $array_zona=[];

        foreach ($zona_notificacion as $zona) {
            $array_zona[]=$zona->id_zona;            
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            NotificacionZona::deleteAll(['id_notificacion' =>$model->id]);
            NotificacionUsuario::deleteAll(['id_notificacion' => $model->id]);
            $usuarios=$_POST['usuarios'];
            $zonas=$_POST['zonas'];


            if (isset($usuarios)) {
               

                foreach ($usuarios as $user) {
                   $notificacion_usuario=new NotificacionUsuario();
                   $notificacion_usuario->setAttribute('id_notificacion',$model->id);
                   $notificacion_usuario->setAttribute('usuario',$user);
                   $notificacion_usuario->save();
                }
            }

            if (isset($zonas)) {
               
                foreach ($zonas as $zona) {
                   $notificacion_zona=new NotificacionZona();
                   $notificacion_zona->setAttribute('id_notificacion',$model->id);
                   $notificacion_zona->setAttribute('id_zona',$zona);
                   $notificacion_zona->save();
                }
            }
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'usuarios'=>$list_usuarios,
                'zonas'=>$list_zonas,
                'usuarios_not'=>$array_user,
                'zona_not'=>$array_zona
            ]);
        }
    }

    /**
     * Deletes an existing Notificacion model.
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
     * Finds the Notificacion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Notificacion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Notificacion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
