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
use yii\filters\AccessControl;
use app\models\DetallePedido;
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
            /*'access' => [
                'class' => AccessControl::className(),
                'only'  => ['cron'],
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['cron'],
                        'roles'   => ['?'], //para usuarios logueados
                    ],
                ],  
            ],*/
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

    public function actionNotificacion(){
      date_default_timezone_set ( 'America/Bogota');
      $date=date('Y-m-d');
      $usuario = Usuario::findOne(Yii::$app->session['usuario-exito']);
      $zonasUsuario = $usuario->zonas;

      $in_zona=" IN(";

      $contador=0;
      foreach ($zonasUsuario as $value) {
        $in_zona.=" '".$value->zona_id."',";  
        $contador++;
      }

      if($contador!=0){
          $in_final = substr($in_zona, 0, -1).")";
      }else{
          $in_final = " IN('')";
      }

       $notificacion=Notificacion::find()
        ->leftJoin('notificacion_zona', ' notificacion_zona.id_notificacion= notificacion.id')
        ->leftJoin('notificacion_usuario', ' notificacion_usuario.id_notificacion= notificacion.id')
        ->where(' ( notificacion.fecha_final >= "'.$date.'" ) and (notificacion_zona.id_zona '.$in_final.' or notificacion_usuario.usuario IN("'.Yii::$app->session['usuario-exito'].'") ) AND (tipo IN(/*"C",*/"M"))
          AND ((select leido from notificacion_usuario where id_notificacion=notificacion.id AND usuario="'.Yii::$app->session['usuario-exito'].'" )=0)')
        ->orderBy('id DESC')
        ->limit(10)
        ->all();
        //$rows_not= clone $notificacion;
        //$notificacion2=$rows_not->andwhere('tipo="C"')->orderBy('id DESC')->limit(10)->all();
        //$notificacion1=$notificacion->andwhere('tipo="M"')->orderBy('id DESC')->all();


        /*$count=Notificacion::find()
        ->leftJoin('notificacion_zona', ' notificacion_zona.id_notificacion= notificacion.id')
        ->leftJoin('notificacion_usuario', ' notificacion_usuario.id_notificacion= notificacion.id')
        ->where(' (notificacion.fecha_final > "'.$date.'" OR notificacion.fecha_final = "'.$date.'" ) AND (notificacion_zona.id_zona '.$in_final.' OR notificacion_usuario.usuario IN("'.Yii::$app->session['usuario-exito'].'") )');

        $rows_count= clone $count;
        $count1=$count->andwhere('tipo="M"')->groupBy('notificacion.id')->count();
        $count2=$rows_count->andwhere('tipo="C"')->groupBy('notificacion.id')->count();*/
        $count1=0;
        $count2=0;
        foreach ($notificacion as $key => $value) {
          $count1=$value['tipo']=='M'?$count1+1:$count1;
          $count2=$value['tipo']=='C'?$count2+1:$count2;
        }


        $count_total=($count1+$count2);

        $res_not=$this->renderPartial('_notificacion',[
            
            'notificacion1'=>$notificacion,
            
        ]);

        /*$res_pedido=$this->renderPartial('_notificacion_pedido',[
            
            'notificacion2'=>$notificacion,
        ]);*/

        //Mysql_free_result();

        return json_encode([
            'res_not'=>$res_not,
            'res_pedido'=>$res_pedido,
            'total_not_mensaje'=>$count1,
            'total_not_pedido'=>$count2,
            'total_not_general'=>$count_total,
        ]);

    }

    public function actionLeido(){

      $model=NotificacionUsuario::find()->where('id_notificacion='.$_POST['id_not'].' AND usuario="'.$_POST['usuario'].'" ')->one();
      $model->setAttribute('leido',true);
      $model->save();
    }


    public function actionListadoNotificaciones(){
      date_default_timezone_set ( 'America/Bogota');
      $date=date('Y-m-d');
      $usuario = Usuario::findOne(Yii::$app->session['usuario-exito']);
      $zonasUsuario = $usuario->zonas;

      $in_zona=" IN(";

      $contador=0;
      foreach ($zonasUsuario as $value) {
        $in_zona.=" '".$value->zona_id."',";  
        $contador++;
      }

      if($contador!=0){
          $in_final = substr($in_zona, 0, -1).")";
      }else{
          $in_final = " IN('')";
      }

       $notificacion=Notificacion::find()
        ->leftJoin('notificacion_zona', ' notificacion_zona.id_notificacion= notificacion.id')
        ->leftJoin('notificacion_usuario', ' notificacion_usuario.id_notificacion= notificacion.id')
        ->where(' ( notificacion.fecha_final >= "'.$date.'" ) and (notificacion_zona.id_zona '.$in_final.' or notificacion_usuario.usuario IN("'.Yii::$app->session['usuario-exito'].'") )')
        ->andwhere('tipo="C"')->orderBy('id DESC')->all();

        return $this->render('listado-notificaciones', [
                'notificacion' => $notificacion,
            ]);
    }


    public function actionCron(){
    $model = new Notificacion();
    $model->setAttribute('titulo','Creada por cron');
    $model->setAttribute('descripcion','Fue creada por cron');
    $model->setAttribute('fecha_inicio',date('Y-m-d'));
    $model->setAttribute('fecha_final',date('Y-m-d'));
    $model->save();

    $model2=new NotificacionUsuario;
    $model2->setAttribute('id_notificacion',$model->id);
    $model2->setAttribute('usuario','administrador');
    $model2->save();

  }

  public function actionPedido(){
    date_default_timezone_set ( 'America/Bogota');
    $fecha="2019-06-11";//date('Y-m-d');
    $pendiente = DetallePedido::find()->where('fecha_revision_coordinador="'.$fecha.'"')->all();

    $titulo="Revision de pedidos";
    $tr="";
    foreach ($pendiente as $key => $value) {
      $tr.="<tr>";
      $tr.="<td>".$value->pedido->fecha."</td>
                        <td>".$value->pedido->dependencia->nombre."</td>
                        <td>".$value->producto->maestra->proveedor->nombre."</td>
                        <td>".$value->producto->material."</td>
                        <td>".$value->producto->texto_breve."</td>
                        <td>".$value->cantidad."</td>
                        <td>".$value->observaciones."</td>
                        <td>".$value->ordinario."</td>
                        <td>".strtoupper($value->pedido->solicitante)."</td>
                        <td>".$value->usuario_aprobador_revision."</td>
                        <td>".$value->fecha_revision_coordinador."</td>
                        ";
            $tr.="</tr>";
    }
        $descripcion="
            <table class='table table-bordered my-data'>
                <thead>
                    <tr>
                      <th>Fecha Pedido</th>
                      <th>Dependencia</th>
                      <th>Proveedor</th>
                      <th>Material</th>
                      <th>Texto breve</th>
                      <th>Cantidad</th>
                      <th>Observaciones</th>
                      <th>Ordinario</th>
                      <th>Solicitante</th>
                      <th>Usuario aprueba</th>
                      <th>Fecha aprueba</th>


                    </tr>
                </thead>
                <tbody>
                    ".$tr."
                </tbody>
            </table>


        ";
        $solicitantes=[$pendiente->pedido->solicitante];
        Notificacion::CrearNotificacion($titulo,$descripcion,$solicitantes);
    /*$model = new Notificacion();
      $model->setAttribute('titulo','Creada por cron');
      $model->setAttribute('descripcion','Fue creada por cron');
      $model->setAttribute('fecha_inicio',date('Y-m-d'));
      $model->setAttribute('fecha_final',date('Y-m-d'));
      $model->save();

      $model2=new NotificacionUsuario;
      $model2->setAttribute('id_notificacion',$model->id);
      $model2->setAttribute('usuario','administrador');
      $model2->save();*/
      //echo "entra aqui";
  }
}
