<?php

namespace app\controllers;

use app\models\ArchivoVisitaMensual;
use app\models\CentroCosto;
use app\models\Usuario;
use app\models\VisitaMensual;
use app\models\VisitaDia;
use app\models\Novedad;
use app\models\DetallePedido;
use app\models\DetallePedidoEspecial;
use app\models\CategoriaVisita;
use app\models\NovedadCategoriaVisita;
use app\models\VisitaMensualDetalle;
use app\models\AdjuntoVisitaDetalle;
use app\models\AdjuntoNovedadCapacitacion;
use app\models\AdjuntoNovedadPedido;
use app\models\NovedadCapacitacion;
use app\models\NovedadPedido;
use app\models\PlanesAccionVisitas;
use kartik\mpdf\Pdf;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use app\models\GraficosVisitasMensuales;

/**
 * VisitaMensualController implements the CRUD actions for VisitaMensual model.
 */
class VisitaMensualController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['index', 'view', 'ViewFromCordinador', 'Create', 'Update', 'delete','createVisita'],
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['index', 'view', 'ViewFromCordinador', 'Create', 'Update', 'delete','createVisita'],
                        'roles'   => ['@'], //para usuarios logueados
                    ],
                ],  
            ],
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['GET'],
                ],
            ],
        ];
    }

    /**
     * Lists all VisitaMensual models.
     * @return mixed
     */
    public function actionIndex()
    {
       $dependencias_user=$this->dependencias_usuario(Yii::$app->session['usuario-exito']);

        $in=" IN(";

        foreach ($dependencias_user as $value) {
            
            $in.=" '".$value."',";    
        }

        $in_final = substr($in, 0, -1).")";

        $visitas=VisitaMensual::find()
        ->leftJoin('centro_costo cc', '`visita_mensual`.`centro_costo_codigo` = `cc`.`codigo`')
        ->where('`visita_mensual`.`centro_costo_codigo` '.$in_final.' ');

        $searchDep='';
        if (isset($_POST['buscar'])) {
            if($_POST['buscar']!='')
                $visitas->andwhere('cc.nombre like "%'.$_POST['buscar'].'%" ');
            $searchDep=$_POST['buscar'];
        }

        if (isset($_GET['sort'])) {
     
           switch ($_GET['sort']) {
                case 'usuario':
                   
                   $visitas->orderBy(['usuario' => SORT_ASC]);
                break;

                case '-usuario':
                   
                   $visitas->orderBy(['usuario' => SORT_DESC]);
                break;
                
                case 'atendio':
                   
                   $visitas->orderBy(['atendio' => SORT_ASC]);
                break;

                case '-atendio':
                   
                   $visitas->orderBy(['atendio' => SORT_DESC]);
                break;

                case 'fecha_visita':
                   
                   $visitas->orderBy(['fecha_visita' => SORT_ASC]);
                break;

                case '-fecha_visita':
                   
                   $visitas->orderBy(['fecha_visita' => SORT_DESC]);
                break;

                case 'semestre':
                   
                   $visitas->orderBy(['semestre' => SORT_ASC]);
                break;

                case '-semestre':
                   
                   $visitas->orderBy(['semestre' => SORT_DESC]);
                break;

                case 'estado':
                   
                   $visitas->orderBy(['estado' => SORT_ASC]);
                break;

                case '-estado':
                   
                   $visitas->orderBy(['estado' => SORT_DESC]);
                break;

                default:
                   # code...
                break;
           }

        }else{

            $visitas->orderBy(['id' => SORT_DESC]);
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $visitas,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $dependencias     = CentroCosto::find()->where(['not in', 'estado', ['C']])->orderBy(['nombre' => SORT_ASC])->all();
        $usuario          = Usuario::findOne(Yii::$app->session['usuario-exito']);
        $zonasUsuario     = array();
        $marcasUsuario    = array();
        $distritosUsuario = array();
        


        if ($usuario != null) {

            $zonasUsuario     = $usuario->zonas;
            $marcasUsuario    = $usuario->marcas;
            $distritosUsuario = $usuario->distritos;

        }

        return $this->render('index', [
            'visitas' => $dataProvider,
            'dependencias'     => $dependencias,
            'marcasUsuario'    => $marcasUsuario,
            'distritosUsuario' => $distritosUsuario,
            'zonasUsuario'     => $zonasUsuario,
            'searchDep'       =>$searchDep
        ]);
    }


    public function actionCreateVisita(){

        $model=new VisitaMensual;
        date_default_timezone_set ( 'America/Bogota');
        $fecha = date('Y-m-d');
        $dependencias     = CentroCosto::find()->where(['not in', 'estado', ['C']])->orderBy(['nombre' => SORT_ASC])->all();
        $usuario          = Usuario::findOne(Yii::$app->session['usuario-exito']);
        $zonasUsuario     = array();
        $marcasUsuario    = array();
        $distritosUsuario = array();
        


        if ($usuario != null) {

            $zonasUsuario     = $usuario->zonas;
            $marcasUsuario    = $usuario->marcas;
            $distritosUsuario = $usuario->distritos;

        }

        if ($model->load(Yii::$app->request->post()) ) {
            $model->setAttribute('fecha',$fecha);
            $model->setAttribute('usuario', Yii::$app->session['usuario-exito']);
            $explod_fecha=explode('-', $_POST['VisitaMensual']['fecha_visita']);

           /* $visitas=$model->find()->where('centro_costo_codigo="'.$_POST['VisitaMensual']['centro_costo_codigo'].'" AND YEAR(fecha_visita)="'.$explod_fecha[0].'" AND semestre="'.$_POST['VisitaMensual']['semestre'].'" ')->count();
            if ($visitas==1) {
                Yii::$app->session->setFlash('danger','Ya existe una visita en este semestre en el año seleccionado');
                return $this->redirect(['create-visita']);
            }else{*/
                
                /**********************************************************************************************************/
                $model->save();

                $visita_anterior=VisitaMensual::find()->where('centro_costo_codigo="'.$model->centro_costo_codigo.'" AND  id < '.$model->id.'  ORDER BY id DESC LIMIT 1')->one();
                $id_visita_anterior= $visita_anterior->id;//OBTENEMOS LA VISITA ANTERIOR

                if($id_visita_anterior!=''):

                    /*if ($visita_anterior->estado=='abierta') {
                        $model->delete();
                        Yii::$app->session->setFlash('danger','La visita anterior aun esta abierta');
                        return $this->redirect(['create-visita']);
                    }*/

                    /*** SE OBTIENEN LOS PLANES DE ACCION DE LAS VISITAS*/
                    $planes_de_accion_anteriores=VisitaMensualDetalle::find()
                    ->where('visita_mensual_id='.$id_visita_anterior.' AND aplica_plan="S" ')
                    ->orderBy(['id' => SORT_ASC])
                    ->all();

                    
                    $planes_de_accion=PlanesAccionVisitas::find()
                    ->where(' visita_mensual_id=  '.$id_visita_anterior.' AND cumplimiento="N"')
                    ->orderBy(['id' => SORT_ASC])
                    ->all();

                    foreach($planes_de_accion_anteriores as $pla):
                        $plan_de_accion1=new PlanesAccionVisitas;
                        $plan_de_accion1->setAttribute('tipo','Visita');
                        $plan_de_accion1->setAttribute('plan_de_accion',$pla->plan_de_accion);
                        $plan_de_accion1->setAttribute('cumplimiento',$pla->cumplimiento);
                        $plan_de_accion1->setAttribute('fecha',$pla->fecha_novedad);
                        $plan_de_accion1->setAttribute('observacion',$pla->observacion);
                        $plan_de_accion1->setAttribute('visita_mensual_id',$model->id);
                        $plan_de_accion1->setAttribute('usuario',$pla->usuario);
                        $plan_de_accion1->save();

                    endforeach;

                    foreach($planes_de_accion as $pl):
                        $plan_de_accion2=new PlanesAccionVisitas;
                        $plan_de_accion2->setAttribute('tipo',$pl->tipo);
                        $plan_de_accion2->setAttribute('plan_de_accion',$pl->plan_de_accion);
                        $plan_de_accion2->setAttribute('cumplimiento',$pl->cumplimiento);
                        $plan_de_accion2->setAttribute('fecha',$pl->fecha);
                        $plan_de_accion2->setAttribute('observacion',$pl->observacion);
                        $plan_de_accion2->setAttribute('visita_mensual_id',$model->id);
                        $plan_de_accion2->setAttribute('usuario',$pl->usuario);
                        $plan_de_accion2->save();

                    endforeach;

                    /*** SE OBTIENEN LOS PLANES DE ACCION DE LAS CAPACITACIONES*/
                    $planes_de_accion_capacitacion_anterior=NovedadCapacitacion::find()
                    ->where('visita_mensual_id='.$id_visita_anterior.' AND aplica_plan="S" ')
                    ->orderBy(['id' => SORT_ASC])
                    ->all();
                    


                    foreach($planes_de_accion_capacitacion_anterior as $plca):
                        $plan_de_accion3=new PlanesAccionVisitas;
                        $plan_de_accion3->setAttribute('tipo','Capacitacion');
                        $plan_de_accion3->setAttribute('plan_de_accion',$plca->plan_de_accion);
                        $plan_de_accion3->setAttribute('cumplimiento',$plca->cumplimiento);
                        $plan_de_accion3->setAttribute('fecha',$plca->fecha_novedad);
                        $plan_de_accion3->setAttribute('observacion',$plca->observacion);
                        $plan_de_accion3->setAttribute('visita_mensual_id',$model->id);
                        $plan_de_accion3->setAttribute('usuario',$plca->usuario);
                        $plan_de_accion3->save();

                    endforeach;


                    /*** SE OBTIENEN LOS PLANES DE ACCION DE LOS PEDIDOS***/

                    $planes_de_accion_pedido_anterior=NovedadPedido::find()
                    ->where('visita_mensual_id='.$id_visita_anterior.' AND aplica_plan="S" ')
                    ->orderBy(['id' => SORT_ASC])
                    ->all();


                    foreach($planes_de_accion_pedido_anterior as $plpa):
                        $plan_de_accion5=new PlanesAccionVisitas;
                        $plan_de_accion5->setAttribute('tipo','Pedido');
                        $plan_de_accion5->setAttribute('plan_de_accion',$plpa->plan_de_accion);
                        $plan_de_accion5->setAttribute('cumplimiento',$plpa->cumplimiento);
                        $plan_de_accion5->setAttribute('fecha',$plpa->fecha_novedad);
                        $plan_de_accion5->setAttribute('observacion',$plpa->observacion);
                        $plan_de_accion5->setAttribute('visita_mensual_id',$model->id);
                        $plan_de_accion5->setAttribute('usuario',$plpa->usuario);
                        $plan_de_accion5->save();

                    endforeach;
                endif;
                /************************************************************************************************************/
                Yii::$app->session->setFlash('success','Visita creada exitosamente');

                return $this->redirect(['view', 'id' => $model->id,'dependencia'=>$model->centro_costo_codigo]);
            //}
        }else{
            return $this->render('createvisita', [
                    'model'            => $model,   
                    'dependencias'     => $dependencias,
                    'marcasUsuario'    => $marcasUsuario,
                    'distritosUsuario' => $distritosUsuario,
                    'zonasUsuario'     => $zonasUsuario,
                    
                    
                ]);
        }
    }

    public function actionCreateNovedad($id,$dependencia){
        $model=new VisitaMensualDetalle;
        $model2=new NovedadCapacitacion;
        $model3=new NovedadPedido;
        $categorias=CategoriaVisita::find()->where(' estado="A" ')->all();
        $list_categorias=ArrayHelper::map($categorias,'id','nombre');
        $novedades = Novedad::find()->where('tipo="C" AND estado="A" ')->orderBy(['nombre' => SORT_ASC])->all();
        $list_tema=ArrayHelper::map($novedades,'id','nombre');

        if ($model->load(Yii::$app->request->post())) {

            $model->setAttribute('visita_mensual_id',$id);
            $model->setAttribute('usuario', Yii::$app->session['usuario-exito']);
            $model->setAttribute('fecha', date('Y-m-d'));
            $model->setAttribute('aplica_plan',$_POST['aplica_plan']);
            $model->save();

            Yii::$app->params['uploadPath'] = Yii::$app->basePath . '/web/uploads/VisitaMensual/';
            $shortPath                      = '/uploads/VisitaMensual/';
            $files = UploadedFile::getInstances($model, 'file');

            foreach ($files as $file) {

                if ($file !== null) {
                    $archivo = new AdjuntoVisitaDetalle();
                    $ext     = end((explode(".", $file->name)));
                    $name    = date('Ymd') . rand(1, 10000) . '' . $file->name;
                    $path    = Yii::$app->params['uploadPath'] . $name;
                    $archivo->setAttribute('archivo', $shortPath . $name);
                    $archivo->setAttribute('visita_detalle_id', $model->id);
                    $file->saveAs($path);
                    $verifica_imagen=Yii::$app->verificar_imagen->esImagen($path);
                    if ($verifica_imagen) {       
                       Yii::$app->verificar_imagen->Redimenzionar($path,$file->type);
                       //unlink($path);
                    }
                    $archivo->save();

                }

            }

             Yii::$app->session->setFlash('success','Novedad creada exitosamente');
            return $this->redirect(['view', 'id' => $id,'dependencia'=>$dependencia]);

        }elseif ($model2->load(Yii::$app->request->post())) {

            $model2->setAttribute('visita_mensual_id',$id);
            $model2->setAttribute('usuario', Yii::$app->session['usuario-exito']);
            $model2->setAttribute('fecha', date('Y-m-d'));
            $model2->setAttribute('aplica_plan', $_POST['aplica_plan']);
            $model2->save();

            Yii::$app->params['uploadPath'] = Yii::$app->basePath . '/web/uploads/novedad_capacitacion/';
            $shortPath                      = '/uploads/novedad_capacitacion/';
            $files = UploadedFile::getInstances($model2, 'file');

            foreach ($files as $file) {

                if ($file !== null) {
                    $archivo = new AdjuntoNovedadCapacitacion();
                    $ext     = end((explode(".", $file->name)));
                    $name    = date('Ymd') . rand(1, 10000) . '' . $file->name;
                    $path    = Yii::$app->params['uploadPath'] . $name;
                    $archivo->setAttribute('archivo', $shortPath . $name);
                    $archivo->setAttribute('novedad_capacitacion_id', $model2->id);
                    $file->saveAs($path);
                    $verifica_imagen=Yii::$app->verificar_imagen->esImagen($path);
                    if ($verifica_imagen) {       
                       Yii::$app->verificar_imagen->Redimenzionar($path,$file->type);
                       //unlink($path);
                    }
                    $archivo->save();

                }

            }

            Yii::$app->session->setFlash('success','Novedad creada exitosamente');
            return $this->redirect(['view', 'id' => $id,'dependencia'=>$dependencia]);


        }elseif ($model3->load(Yii::$app->request->post())) {

            $model3->setAttribute('visita_mensual_id',$id);
            $model3->setAttribute('usuario', Yii::$app->session['usuario-exito']);
            $model3->setAttribute('fecha', date('Y-m-d'));
            $model3->setAttribute('aplica_plan', $_POST['aplica_plan']);
            $model3->save();

            Yii::$app->params['uploadPath'] = Yii::$app->basePath . '/web/uploads/novedad_pedido/';
            $shortPath                      = '/uploads/novedad_pedido/';
            $files = UploadedFile::getInstances($model3, 'file');

            foreach ($files as $file) {

                if ($file !== null) {
                    $archivo = new AdjuntoNovedadPedido();
                    $ext     = end((explode(".", $file->name)));
                    $name    = date('Ymd') . rand(1, 10000) . '' . $file->name;
                    $path    = Yii::$app->params['uploadPath'] . $name;
                    $archivo->setAttribute('archivo', $shortPath . $name);
                    $archivo->setAttribute('novedad_pedido_id', $model3->id);
                    $file->saveAs($path);
                    $verifica_imagen=Yii::$app->verificar_imagen->esImagen($path);
                    if ($verifica_imagen) {       
                       Yii::$app->verificar_imagen->Redimenzionar($path,$file->type);
                       //unlink($path);
                    }
                    $archivo->save();

                }

            }

            Yii::$app->session->setFlash('success','Novedad creada exitosamente');
            return $this->redirect(['view', 'id' => $id,'dependencia'=>$dependencia]);

        }else{

            return $this->render('_create_novedad', [
                        'model'            => $model,
                        'model2'            => $model2,
                        'model3'            => $model3,   
                        'dependencia'     => $dependencia,
                        'id'=>$id,
                        'list_categorias'=>$list_categorias,
                        'list_tema'=>$list_tema
                    ]);
        }
    }
    /**
     * Displays a single VisitaMensual model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id, $dependencia)
    {
        //$this->layout = 'main_sin_menu';
        $categorias=CategoriaVisita::find()->all();
        $NovedadesMensual=VisitaMensualDetalle::find()->where('visita_mensual_id='.$id)->all();
        $NovedadesCapacitacion=NovedadCapacitacion::find()->where('visita_mensual_id='.$id)->all();
        $NovedadPedido=NovedadPedido::find()->where('visita_mensual_id='.$id)->all();

        $model=$this->findModel($id);

        $planes_de_accion=PlanesAccionVisitas::find()->where('visita_mensual_id='.$id)->all();

       /* $visita_anterior=VisitaMensual::find()->where('centro_costo_codigo="'.$dependencia.'" AND  id < '.$id.'  ORDER BY id DESC LIMIT 1')->one();
        $id_visita_anterior= $visita_anterior->id;

        if($id_visita_anterior!=''):

        $planes_de_accion_anteriores=VisitaMensualDetalle::find()
        ->where('visita_mensual_id='.$id_visita_anterior)
        ->orderBy(['id' => SORT_ASC])
        ->all();

        $planes_de_accion=VisitaMensualDetalle::find()
        ->leftJoin('visita_mensual', ' visita_mensual_detalle.visita_mensual_id= visita_mensual.id')
        ->where(' centro_costo_codigo="'.$dependencia.'" AND  cumplimiento ="N"  AND visita_mensual.id NOT IN('.$id.','.$id_visita_anterior.')
        AND visita_mensual.id <  '.$id.' ')
        ->orderBy(['visita_mensual.id' => SORT_ASC])
        ->all();

        
        $planes_de_accion_capacitacion_anterior=NovedadCapacitacion::find()
        ->where('visita_mensual_id='.$id_visita_anterior)
        ->orderBy(['id' => SORT_ASC])
        ->all();
        

        $planes_de_accion_capacitacion=NovedadCapacitacion::find()
        ->leftJoin('visita_mensual', ' novedad_capacitacion.visita_mensual_id= visita_mensual.id')
        ->where(' centro_costo_codigo="'.$dependencia.'" AND  cumplimiento ="N"  AND visita_mensual.id NOT IN('.$id.','.$id_visita_anterior.')
        AND visita_mensual.id <  '.$id.' ')
        ->orderBy(['visita_mensual.id' => SORT_ASC])
        ->all();

        

        $planes_de_accion_pedido_anterior=NovedadPedido::find()
        ->where('visita_mensual_id='.$id_visita_anterior)
        ->orderBy(['id' => SORT_ASC])
        ->all();

        $planes_de_accion_pedido=NovedadPedido::find()
        ->leftJoin('visita_mensual', ' novedad_pedido.visita_mensual_id= visita_mensual.id')
        ->where(' centro_costo_codigo="'.$dependencia.'" AND  cumplimiento ="N"  AND visita_mensual.id NOT IN('.$id.','.$id_visita_anterior.')
        AND visita_mensual.id <  '.$id.' ')
        ->orderBy(['visita_mensual.id' => SORT_ASC])
        ->all();

        endif;*/

        if (isset($_POST['id_novedad'])) {

            $update_plan=PlanesAccionVisitas::find()->where(' id='.$_POST['id_novedad'])->one();
            $update_plan->setAttribute('cumplimiento',$_POST['cumplimiento']);
            $update_plan->setAttribute('observacion',$_POST['observacion']);
            $update_plan->save();
            /*switch ($_POST['tipo_novedad']) {
                case 'visita':
                 
                    $update_plan=VisitaMensualDetalle::find()->where(' id='.$_POST['id_novedad'])->one();
                    $update_plan->setAttribute('cumplimiento',$_POST['cumplimiento']);
                    $update_plan->setAttribute('observacion',$_POST['observacion']);
                    $update_plan->save();

                break;

                case 'capacitacion':
                    $update_plan=NovedadCapacitacion::find()->where(' id='.$_POST['id_novedad'])->one();
                    $update_plan->setAttribute('cumplimiento',$_POST['cumplimiento']);
                    $update_plan->setAttribute('observacion',$_POST['observacion']);
                    $update_plan->save();
                break;   

                case 'pedido':
                    $update_plan=NovedadPedido::find()->where(' id='.$_POST['id_novedad'])->one();
                    $update_plan->setAttribute('cumplimiento',$_POST['cumplimiento']);
                    $update_plan->setAttribute('observacion',$_POST['observacion']);
                    $update_plan->save();
                break;               
            }*/    

            Yii::$app->session->setFlash('success','Plan de accion editado exitosamente');
            return $this->redirect(['view', 'id' => $id,'dependencia'=>$dependencia]);
        }

        $connection = \Yii::$app->db;
        $model_visita=new VisitaDia;
        $arr_meses=array('01'=>'Enero','02'=>'Febrero','03'=>'Marzo','04'=>'Abril','05'=>'Mayo','06'=>'Junio','07'=>'Julio','08'=>'Agosto',
            '09'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre'
        );
        $dependencia=$model->centro_costo_codigo;
            $ano=date('Y');
            ///VISITA QUINCENAL
            $fecha_inicio=isset($_POST['fecha_inicial'])?$_POST['fecha_inicial']:'';
            $fecha_final=isset($_POST['fecha_final'])?$_POST['fecha_final']:'';

            if ($fecha_inicio!='' AND $fecha_final!='' ) {

                $filtro_fecha=" AND DATE(fecha) between '".$_POST['fecha_inicial']."' AND '".$_POST['fecha_final']."' ";
            }else{

                $filtro_fecha=" AND (YEAR(fecha)='".$ano."')";
            }

            $rows_bueno = (new \yii\db\Query())
            ->select(['COUNT(dvd.id)AS total','resultado.nombre'])
            ->from('detalle_visita_dia AS dvd')
            ->leftJoin(['visita_dia AS vd'], 'dvd.visita_dia_id=vd.id')
            ->leftJoin(['resultado'], 'dvd.resultado_id=resultado.id')
            ->where("(resultado.nombre='Bueno' OR resultado.nombre='Malo'  OR resultado.nombre='Regular')  AND (vd.centro_costo_codigo='".$dependencia."') ");
            if ($fecha_inicio!='' AND $fecha_final!='' ) {
                $rows_bueno->andWhere("DATE(vd.fecha) between '".$_POST['fecha_inicial']."' AND '".$_POST['fecha_final']."'");
            }else{

                $rows_bueno->andWhere(" (YEAR(fecha)='".$ano."') ");
            }
            
            $rows_bueno->groupBy(['resultado.nombre']);

            $command_bueno = $rows_bueno->createCommand();
            
            $resultado_bueno = $command_bueno->queryAll();

            $arreglo_bueno=array();
            foreach ($resultado_bueno as $key => $value) {
                
                $arreglo_bueno[]=array('name'=>(string)$value['nombre'],'y'=>(int)$value['total'] );
            }

            $json_bueno=json_encode($arreglo_bueno);




            $rows_negativo = (new \yii\db\Query())
            ->select(['COUNT(dvd.id)AS total','ct.nombre'])
            ->from('detalle_visita_dia AS dvd')
            ->leftJoin(['visita_dia AS vd'], 'dvd.visita_dia_id=vd.id')
            ->leftJoin(['resultado'], 'dvd.resultado_id=resultado.id')
            ->leftJoin(['novedad_categoria_visita AS nc'], 'dvd.novedad_categoria_visita_id=nc.id')
            ->leftJoin(['categoria_visita AS ct'], 'nc.categoria_visita_id=ct.id')
            ->where("( resultado.nombre='Malo' OR resultado.nombre='Regular') AND (vd.centro_costo_codigo='".$dependencia."') ");

            if ($fecha_inicio!='' AND $fecha_final!='' ) {

                $rows_negativo->andWhere("DATE(vd.fecha) between '".$_POST['fecha_inicial']."' AND '".$_POST['fecha_final']."'");
            }else{

                $rows_negativo->andWhere(" (YEAR(fecha)='".$ano."') ");

            }

            $rows_negativo->groupBy(['ct.nombre']);

            $command_negativo = $rows_negativo->createCommand();
            
            $resultado_negativo = $command_negativo->queryAll();

            $arreglo_negativo=array();
            foreach ($resultado_negativo as $key1 => $value1) {
                
                $arreglo_negativo[]=array('name'=>(string)$value1['nombre'],'y'=>(int)$value1['total'] );
            }

            $json_negativo=json_encode($arreglo_negativo);


            $novedades=Novedad::find()->where('tipo="C" AND estado="A"')->all();
            
            $torta=array();
            $capacitaciones_tema=array();
            

            foreach ($novedades as  $value) {
                $sql='SELECT SUM(cd.cantidad) as cantidad,COUNT(capacitacion.id) as capacitaciones FROM capacitacion  
                inner join capacitacion_dependencia as cd on cd.capacitacion_id=capacitacion.id
                where cd.centro_costo_codigo=:dependencia AND capacitacion.novedad_id=:novedad  AND (YEAR(fecha_capacitacion)=:ano)
                ';
                $capDep= $connection->createCommand($sql, [
                    ':dependencia' => $dependencia,
                    ':novedad'=>$value->id,
                    ':ano'=>$ano
                ])->queryOne();
                $torta[]=array('name'=>(string)$value->nombre,'y'=>(int)$capDep['cantidad']);
                $capacitaciones_tema[]=array('name'=>$value->nombre,'y'=>(int)$capDep['cantidad'],'capacitaciones'=>$capDep['capacitaciones']);
            }
        
            $torta=json_encode($torta);

            $novedades_seleccionadas=["Seguridad-en-Retail"=>20,"Vigías-Protección-de-Recursos"=>21];
            $array_semestre=[];
            $array_semestre2=[];
            foreach ($novedades_seleccionadas as $key=>$nov) {
                
                $sqlSem='SELECT COUNT(capacitacion.id) as cantidad FROM capacitacion  
                inner join capacitacion_dependencia as cd on cd.capacitacion_id=capacitacion.id
                where cd.centro_costo_codigo=:dependencia AND capacitacion.novedad_id=:novedad 
                ';

                $consultaSem=$sqlSem.'AND (fecha_capacitacion BETWEEN "'.$ano.'-01-01" AND
                "'.$ano.'-06-31")';
                
                $capSem= $connection->createCommand($consultaSem, [
                    ':dependencia' => $dependencia,
                    ':novedad'=>$nov
                ])->queryOne();

                if($capSem['cantidad']!=0){
                    $califSem=($capSem['cantidad']/1)*100;
                    if ($califSem>100) {
                       $califSem=100; 
                    }
                }else{

                    $califSem=0;
                }

                $array_semestre[]=['novedad'=>$key,'cantidad'=>$capSem['cantidad'],'calif'=>$califSem];

                $consultaSem2=$sqlSem.'AND (fecha_capacitacion BETWEEN "'.$ano.'-07-01" AND
                "'.$ano.'-12-31")';

                $capSem2= $connection->createCommand($consultaSem2, [
                    ':dependencia' =>$dependencia,
                    ':novedad'=>$nov
                ])->queryOne();

                if($capSem2['cantidad']!=0 ){
                    $califSem2=($capSem2['cantidad']/1)*100;

                    if ($califSem2>100) {
                       $califSem2=100; 
                    }
                }else{
                    $califSem2=0;
                }

                $array_semestre2[]=['novedad'=>$key,'cantidad'=>$capSem2['cantidad'],'calif'=>$califSem2];
                
            }

            if ($fecha_inicio!='' AND $fecha_final!='' ) {

                $filtro_fechaPedido=" AND DATE(pedido.fecha) between '".$_POST['fecha_inicial']."' AND '".$_POST['fecha_final']."' ";
            }else{

                $filtro_fechaPedido=" AND (YEAR(pedido.fecha)='".$ano."')";
            }


            $pedidos = DetallePedido::find()
             ->leftJoin('pedido', 'detalle_pedido.pedido_id = pedido.id')
            ->where('pedido.centro_costo_codigo="'.$dependencia.'" AND  detalle_pedido.estado="C" '.$filtro_fechaPedido.' ')
            ->orderBy(['id_pedido' => SORT_ASC, 'posicion' => SORT_ASC])
            ->all();

            $pedidos_especial=DetallePedidoEspecial::find()
             ->leftJoin('pedido', 'detalle_pedido_especial.pedido_id = pedido.id')
             ->where('pedido.centro_costo_codigo="'.$dependencia.'" AND  detalle_pedido_especial.estado="C" '.$filtro_fechaPedido.' ')
            ->orderBy(['id_pedido' => SORT_ASC, 'posicion' => SORT_ASC])
            ->all();

        return $this->render('view', [
            'model'       => $model,
            //'dependencia' => $dependencia,
            'id_visita'=>$id,
            'categorias'      =>$categorias,
            'json_bueno'         =>$json_bueno,
            'json_negativo'      =>$json_negativo,
            'fecha_inicio'       =>$fecha_inicio,
            'fecha_final'        =>$fecha_final,
            'model_visita'       =>$model_visita,
            'arr_meses'          =>$arr_meses,
            'codigo_dependencia' => $dependencia,
            'torta'              =>$torta,
            'capacitaciones_tema'=>$capacitaciones_tema,
            'array_semestre'     =>$array_semestre,
            'array_semestre2'    =>$array_semestre2,
            'pedidos'            =>$pedidos,
            'NovedadesMensual'=>$NovedadesMensual,
             'planes_de_accion'=>$planes_de_accion,
            // 'planes_de_accion_anteriores'=>$planes_de_accion_anteriores,
            // 'planes_de_accion_capacitacion'=>$planes_de_accion_capacitacion,
            // 'planes_de_accion_capacitacion_anterior'=>$planes_de_accion_capacitacion_anterior,
            // 'planes_de_accion_pedido'=>$planes_de_accion_pedido,
            // 'planes_de_accion_pedido_anterior'=>$planes_de_accion_pedido_anterior,
            'pedidos_especial'=>$pedidos_especial,
            'NovedadesCapacitacion'=>$NovedadesCapacitacion,
            'NovedadPedido'=>$NovedadPedido,
            //'id_visita_anterior'=>$id_visita_anterior
        ]);
    }


    public function actionNovedadCategoria(){

        $novedadCategoria=NovedadCategoriaVisita::find()->where('categoria_visita_id='.$_POST['categoria'].' AND estado="A" ')->all();

        $opt='';

        foreach ($novedadCategoria as $key => $value) {
            $opt.="<option value='".$value->id."'>".$value->nombre."</option>";
        }

        echo json_encode(array('resp'=>$opt));
    }

    public function actionInfoNovedad(){
        $id=$_POST['id'];
        $adjuntosVisita=AdjuntoVisitaDetalle::find()->where('visita_detalle_id='.$id)->all(); 
        $adjuntos = $this->renderPartial('_adjuntos', ['adjuntosVisita'=>$adjuntosVisita,'tipo'=>'visita'], true);
        $novedades=VisitaMensualDetalle::find()->where('id='.$id)->one();

        $data=array('adjuntos'=>$adjuntos,'plan'=>$novedades->plan_de_accion);
        echo json_encode($data);
    }

    public function actionInfoNovedadCapacitacion(){
        $id=$_POST['id'];
        $adjuntosVisita=AdjuntoNovedadCapacitacion::find()->where('novedad_capacitacion_id='.$id)->all(); 
        $adjuntos = $this->renderPartial('_adjuntos', ['adjuntosVisita'=>$adjuntosVisita,'tipo'=>'capacitacion'], true);
        //$novedades=VisitaMensualDetalle::find()->where('id='.$id)->one();

        $data=array('adjuntos'=>$adjuntos/*,'plan'=>$novedades->plan_de_accion*/);
        echo json_encode($data);
    }

    public function actionInfoNovedadPedido(){
        $id=$_POST['id'];
        $adjuntosVisita=AdjuntoNovedadPedido::find()->where('novedad_pedido_id='.$id)->all(); 
        $adjuntos = $this->renderPartial('_adjuntos', ['adjuntosVisita'=>$adjuntosVisita,'tipo'=>'pedido'], true);
        //$novedades=VisitaMensualDetalle::find()->where('id='.$id)->one();

        $data=array('adjuntos'=>$adjuntos/*,'plan'=>$novedades->plan_de_accion*/);
        echo json_encode($data);
    }

    public function actionDeleteNovedad($id,$visita,$dependencia){
        $novedades=VisitaMensualDetalle::find()->where('id='.$id)->one();
       
        $archivos=AdjuntoVisitaDetalle::find()->where('visita_detalle_id='.$novedades->id)->all();

        foreach ($archivos as $key1 => $value1) {
            unlink(Yii::$app->basePath.'/web'.$value1->archivo);
        }
        AdjuntoVisitaDetalle::deleteAll('visita_detalle_id = :id', [':id' => $novedades->id]); 
           

        $novedades->delete();

        Yii::$app->session->setFlash('success','Novedad eliminada exitosamente');

        return $this->redirect(['view', 'id' => $visita,'dependencia'=>$dependencia]);

    }

    public function actionDeleteNovedadCapacitacion($id,$visita,$dependencia){
        $novedades=NovedadCapacitacion::find()->where('id='.$id)->one();
       
        $archivos=AdjuntoNovedadCapacitacion::find()->where('novedad_capacitacion_id='.$novedades->id)->all();

        foreach ($archivos as $key1 => $value1) {
            unlink(Yii::$app->basePath.'/web'.$value1->archivo);
        }
        AdjuntoNovedadCapacitacion::deleteAll('novedad_capacitacion_id = :id', [':id' => $novedades->id]); 
           

        $novedades->delete();

        Yii::$app->session->setFlash('success','Novedad eliminada exitosamente');

        return $this->redirect(['view', 'id' => $visita,'dependencia'=>$dependencia]);
    }

    public function actionDeleteNovedadPedido($id,$visita,$dependencia){
        $novedades=NovedadPedido::find()->where('id='.$id)->one();
       
        $archivos=AdjuntoNovedadPedido::find()->where('novedad_pedido_id='.$novedades->id)->all();

        foreach ($archivos as $key1 => $value1) {
            unlink(Yii::$app->basePath.'/web'.$value1->archivo);
        }
        AdjuntoNovedadPedido::deleteAll('novedad_pedido_id = :id', [':id' => $novedades->id]); 
           

        $novedades->delete();

        Yii::$app->session->setFlash('success','Novedad eliminada exitosamente');

        return $this->redirect(['view', 'id' => $visita,'dependencia'=>$dependencia]);
    }

    public function actionViewFromCordinador($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            

        ]);
    }

    public function actionPdf($id)
    {

        $model     = $this->findModel($id);
       
        $categorias=CategoriaVisita::find()->all();
        $NovedadesMensual=VisitaMensualDetalle::find()->where('visita_mensual_id='.$id)->all();
        $NovedadesCapacitacion=NovedadCapacitacion::find()->where('visita_mensual_id='.$id)->all();
        $NovedadPedido=NovedadPedido::find()->where('visita_mensual_id='.$id)->all();

         $connection = \Yii::$app->db;
        $model_visita=new VisitaDia;
        $arr_meses=array('01'=>'Enero','02'=>'Febrero','03'=>'Marzo','04'=>'Abril','05'=>'Mayo','06'=>'Junio','07'=>'Julio','08'=>'Agosto',
            '09'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre'
        );
        $dependencia=$model->centro_costo_codigo;
            $ano=date('Y');
            ///VISITA QUINCENAL
            $fecha_inicio=isset($_POST['fecha_inicial'])?$_POST['fecha_inicial']:'';
            $fecha_final=isset($_POST['fecha_final'])?$_POST['fecha_final']:'';

            if ($fecha_inicio!='' AND $fecha_final!='' ) {

                $filtro_fecha=" AND DATE(fecha) between '".$_POST['fecha_inicial']."' AND '".$_POST['fecha_final']."' ";
            }else{

                $filtro_fecha=" AND (YEAR(fecha)='".$ano."')";
            }

            $rows_bueno = (new \yii\db\Query())
            ->select(['COUNT(dvd.id)AS total','resultado.nombre'])
            ->from('detalle_visita_dia AS dvd')
            ->leftJoin(['visita_dia AS vd'], 'dvd.visita_dia_id=vd.id')
            ->leftJoin(['resultado'], 'dvd.resultado_id=resultado.id')
            ->where("(resultado.nombre='Bueno' OR resultado.nombre='Malo'  OR resultado.nombre='Regular')  AND (vd.centro_costo_codigo='".$dependencia."') ");
            if ($fecha_inicio!='' AND $fecha_final!='' ) {
                $rows_bueno->andWhere("DATE(vd.fecha) between '".$_POST['fecha_inicial']."' AND '".$_POST['fecha_final']."'");
            }else{

                $rows_bueno->andWhere(" (YEAR(fecha)='".$ano."') ");
            }
            
            $rows_bueno->groupBy(['resultado.nombre']);

            $command_bueno = $rows_bueno->createCommand();
            
            $resultado_bueno = $command_bueno->queryAll();

            $arreglo_bueno=array();
            foreach ($resultado_bueno as $key => $value) {
                
                $arreglo_bueno[]=array('name'=>(string)$value['nombre'],'y'=>(int)$value['total'] );
            }

            $json_bueno=json_encode($arreglo_bueno);




            $rows_negativo = (new \yii\db\Query())
            ->select(['COUNT(dvd.id)AS total','ct.nombre'])
            ->from('detalle_visita_dia AS dvd')
            ->leftJoin(['visita_dia AS vd'], 'dvd.visita_dia_id=vd.id')
            ->leftJoin(['resultado'], 'dvd.resultado_id=resultado.id')
            ->leftJoin(['novedad_categoria_visita AS nc'], 'dvd.novedad_categoria_visita_id=nc.id')
            ->leftJoin(['categoria_visita AS ct'], 'nc.categoria_visita_id=ct.id')
            ->where("( resultado.nombre='Malo' OR resultado.nombre='Regular') AND (vd.centro_costo_codigo='".$dependencia."') ");

            if ($fecha_inicio!='' AND $fecha_final!='' ) {

                $rows_negativo->andWhere("DATE(vd.fecha) between '".$_POST['fecha_inicial']."' AND '".$_POST['fecha_final']."'");
            }else{

                $rows_negativo->andWhere(" (YEAR(fecha)='".$ano."') ");

            }

            $rows_negativo->groupBy(['ct.nombre']);

            $command_negativo = $rows_negativo->createCommand();
            
            $resultado_negativo = $command_negativo->queryAll();

            $arreglo_negativo=array();
            foreach ($resultado_negativo as $key1 => $value1) {
                
                $arreglo_negativo[]=array('name'=>(string)$value1['nombre'],'y'=>(int)$value1['total'] );
            }

            $json_negativo=json_encode($arreglo_negativo);


            $novedades=Novedad::find()->where('tipo="C" AND estado="A"')->all();
            
            $torta=array();
            $capacitaciones_tema=array();
            

            foreach ($novedades as  $value) {
                $sql='SELECT SUM(cd.cantidad) as cantidad,COUNT(capacitacion.id) as capacitaciones FROM capacitacion  
                inner join capacitacion_dependencia as cd on cd.capacitacion_id=capacitacion.id
                where cd.centro_costo_codigo=:dependencia AND capacitacion.novedad_id=:novedad  AND (YEAR(fecha_capacitacion)=:ano)
                ';
                $capDep= $connection->createCommand($sql, [
                    ':dependencia' => $dependencia,
                    ':novedad'=>$value->id,
                    ':ano'=>$ano
                ])->queryOne();
                $torta[]=array('name'=>$value->nombre,'y'=>(int)$capDep['cantidad']);
                $capacitaciones_tema[]=array('name'=>$value->nombre,'y'=>(int)$capDep['cantidad'],'capacitaciones'=>$capDep['capacitaciones']);
            }
        
            $torta=json_encode($torta);

            $novedades_seleccionadas=["Seguridad-en-Retail"=>20,"Vigías-Protección-de-Recursos"=>21];
            $array_semestre=[];
            $array_semestre2=[];
            foreach ($novedades_seleccionadas as $key=>$nov) {
                
                $sqlSem='SELECT COUNT(capacitacion.id) as cantidad FROM capacitacion  
                inner join capacitacion_dependencia as cd on cd.capacitacion_id=capacitacion.id
                where cd.centro_costo_codigo=:dependencia AND capacitacion.novedad_id=:novedad 
                ';

                $consultaSem=$sqlSem.'AND (fecha_capacitacion BETWEEN "'.$ano.'-01-01" AND
                "'.$ano.'-06-31")';
                
                $capSem= $connection->createCommand($consultaSem, [
                    ':dependencia' => $dependencia,
                    ':novedad'=>$nov
                ])->queryOne();

                if($capSem['cantidad']!=0){
                    $califSem=($capSem['cantidad']/1)*100;
                    if ($califSem>100) {
                       $califSem=100; 
                    }
                }else{

                    $califSem=0;
                }

                $array_semestre[]=['novedad'=>$key,'cantidad'=>$capSem['cantidad'],'calif'=>$califSem];

                $consultaSem2=$sqlSem.'AND (fecha_capacitacion BETWEEN "'.$ano.'-07-01" AND
                "'.$ano.'-12-31")';

                $capSem2= $connection->createCommand($consultaSem2, [
                    ':dependencia' => $dependencia,
                    ':novedad'=>$nov
                ])->queryOne();

                if($capSem2['cantidad']!=0 ){
                    $califSem2=($capSem2['cantidad']/1)*100;

                    if ($califSem2>100) {
                       $califSem2=100; 
                    }
                }else{
                    $califSem2=0;
                }

                $array_semestre2[]=['novedad'=>$key,'cantidad'=>$capSem2['cantidad'],'calif'=>$califSem2];
                
            }

            if ($fecha_inicio!='' AND $fecha_final!='' ) {

                $filtro_fechaPedido=" AND DATE(pedido.fecha) between '".$_POST['fecha_inicial']."' AND '".$_POST['fecha_final']."' ";
            }else{

                $filtro_fechaPedido=" AND (YEAR(pedido.fecha)='".$ano."')";
            }


            $pedidos = DetallePedido::find()
             ->leftJoin('pedido', 'detalle_pedido.pedido_id = pedido.id')
            ->where('pedido.centro_costo_codigo="'.$dependencia.'" AND  detalle_pedido.estado="C" '.$filtro_fechaPedido.' ')
            ->orderBy(['id_pedido' => SORT_ASC, 'posicion' => SORT_ASC])
            ->all();

        $planes_de_accion=PlanesAccionVisitas::find()->where('visita_mensual_id='.$id)->all();

        $pdf = Yii::$app->pdf;

        $pdf->filename = 'Visita_Semestral_' . $model->fecha_visita . '_' . $model->dependencia->nombre . '.pdf';
         $grafico=GraficosVisitasMensuales::find()->where('visita_id='.$id)->all();
        $pdf->content = $this->renderPartial('_pdf', [
            'model' => $model, 
            'categorias'      =>$categorias,
            'json_bueno'         =>$json_bueno,
            'json_negativo'      =>$json_negativo,
            'fecha_inicio'       =>$fecha_inicio,
            'fecha_final'        =>$fecha_final,
            'model_visita'       =>$model_visita,
            'arr_meses'          =>$arr_meses,
            'codigo_dependencia' => $dependencia,
            'torta'              =>$torta,
            'capacitaciones_tema'=>$capacitaciones_tema,
            'array_semestre'     =>$array_semestre,
            'array_semestre2'    =>$array_semestre2,
            'pedidos'            =>$pedidos,
            'NovedadesMensual'=>$NovedadesMensual,
            'NovedadesCapacitacion'=>$NovedadesCapacitacion,
            'NovedadPedido'=>$NovedadPedido,
            'planes_de_accion'=>$planes_de_accion,
            'grafico'=>$grafico
        ], true);

        $pdf->destination = Pdf::DEST_DOWNLOAD;

        return $pdf->render();

        //return $this->redirect('view', ['id' => $id,]);

    }

     public function actionGuardar_grafico(){

        $graficos=GraficosVisitasMensuales::find()->where('visita_id='.$_POST['id_visita'])->count();
        if($graficos==0):
        $model=new GraficosVisitasMensuales;
        $model->setAttribute('visita_id', $_POST['id_visita']);
        $model->setAttribute('data', $_POST['imagen_visita1']);
        $model->setAttribute('tipo','Visita');
        $model->save();

        $model=new GraficosVisitasMensuales;
        $model->setAttribute('visita_id', $_POST['id_visita']);
        $model->setAttribute('data', $_POST['imagen_visita2']);
        $model->setAttribute('tipo','Visita');
        $model->save();

        $model=new GraficosVisitasMensuales;
        $model->setAttribute('visita_id', $_POST['id_visita']);
        $model->setAttribute('data', $_POST['imagen_capacitacion']);
        $model->setAttribute('tipo','Capacitacion');
        $model->save();

        endif;
        
    }
    
    /**
     * Creates a new VisitaMensual model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->layout = 'main_sin_menu';
        $model                          = new VisitaMensual();
        $array_post                     = Yii::$app->request->post();
        Yii::$app->params['uploadPath'] = Yii::$app->basePath . '/web/uploads/';
        $shortPath                      = '/uploads/';
        Yii::$app->session->setTimeout(5400);
        $dependencias     = CentroCosto::find()->where(['not in', 'estado', ['C']])->orderBy(['nombre' => SORT_ASC])->all();
        $usuario          = Usuario::findOne(Yii::$app->session['usuario-exito']);
        $zonasUsuario     = array();
        $marcasUsuario    = array();
        $distritosUsuario = array();

        if ($usuario != null) {

            $zonasUsuario     = $usuario->zonas;
            $marcasUsuario    = $usuario->marcas;
            $distritosUsuario = $usuario->distritos;

        }

        $connection = \Yii::$app->db;
        $model_visita=new VisitaDia;
        $arr_meses=array('01'=>'Enero','02'=>'Febrero','03'=>'Marzo','04'=>'Abril','05'=>'Mayo','06'=>'Junio','07'=>'Julio','08'=>'Agosto',
            '09'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre'
        );
        if(isset($_POST['VisitaMensual']['centro_costo_codigo'])){
            $dependencia=$_POST['VisitaMensual']['centro_costo_codigo'];
            $ano=date('Y');
            ///VISITA QUINCENAL
            $fecha_inicio=isset($_POST['fecha_inicial'])?$_POST['fecha_inicial']:'';
            $fecha_final=isset($_POST['fecha_final'])?$_POST['fecha_final']:'';

            if ($fecha_inicio!='' AND $fecha_final!='' ) {

                $filtro_fecha=" AND DATE(fecha) between '".$_POST['fecha_inicial']."' AND '".$_POST['fecha_final']."' ";
            }else{

                $filtro_fecha=" AND (YEAR(fecha)='".$ano."')";
            }

            $rows_bueno = (new \yii\db\Query())
            ->select(['COUNT(dvd.id)AS total','resultado.nombre'])
            ->from('detalle_visita_dia AS dvd')
            ->leftJoin(['visita_dia AS vd'], 'dvd.visita_dia_id=vd.id')
            ->leftJoin(['resultado'], 'dvd.resultado_id=resultado.id')
            ->where("(resultado.nombre='Bueno' OR resultado.nombre='Malo'  OR resultado.nombre='Regular')  AND (vd.centro_costo_codigo='".$dependencia."') ");
            if ($fecha_inicio!='' AND $fecha_final!='' ) {
                $rows_bueno->andWhere("DATE(vd.fecha) between '".$_POST['fecha_inicial']."' AND '".$_POST['fecha_final']."'");
            }else{

                $rows_bueno->andWhere(" (YEAR(fecha)='".$ano."') ");
            }
            
            $rows_bueno->groupBy(['resultado.nombre']);

            $command_bueno = $rows_bueno->createCommand();
            
            $resultado_bueno = $command_bueno->queryAll();

            $arreglo_bueno=array();
            foreach ($resultado_bueno as $key => $value) {
                
                $arreglo_bueno[]=array('name'=>(string)$value['nombre'],'y'=>(int)$value['total'] );
            }

            $json_bueno=json_encode($arreglo_bueno);




            $rows_negativo = (new \yii\db\Query())
            ->select(['COUNT(dvd.id)AS total','ct.nombre'])
            ->from('detalle_visita_dia AS dvd')
            ->leftJoin(['visita_dia AS vd'], 'dvd.visita_dia_id=vd.id')
            ->leftJoin(['resultado'], 'dvd.resultado_id=resultado.id')
            ->leftJoin(['novedad_categoria_visita AS nc'], 'dvd.novedad_categoria_visita_id=nc.id')
            ->leftJoin(['categoria_visita AS ct'], 'nc.categoria_visita_id=ct.id')
            ->where("( resultado.nombre='Malo' OR resultado.nombre='Regular') AND (vd.centro_costo_codigo='".$dependencia."') ");

            if ($fecha_inicio!='' AND $fecha_final!='' ) {

                $rows_negativo->andWhere("DATE(vd.fecha) between '".$_POST['fecha_inicial']."' AND '".$_POST['fecha_final']."'");
            }else{

                $rows_negativo->andWhere(" (YEAR(fecha)='".$ano."') ");

            }

            $rows_negativo->groupBy(['ct.nombre']);

            $command_negativo = $rows_negativo->createCommand();
            
            $resultado_negativo = $command_negativo->queryAll();

            $arreglo_negativo=array();
            foreach ($resultado_negativo as $key1 => $value1) {
                
                $arreglo_negativo[]=array('name'=>(string)$value1['nombre'],'y'=>(int)$value1['total'] );
            }

            $json_negativo=json_encode($arreglo_negativo);
            /////////////////////////////////////////////////////////////////////////////////////////////////
            //CAPACITACIONES
            $novedades=Novedad::find()->where('tipo="C" AND estado="A"')->all();
            
            $torta=array();
            $capacitaciones_tema=array();
            

            foreach ($novedades as  $value) {
                $sql='SELECT SUM(cd.cantidad) as cantidad,COUNT(capacitacion.id) as capacitaciones FROM capacitacion  
                inner join capacitacion_dependencia as cd on cd.capacitacion_id=capacitacion.id
                where cd.centro_costo_codigo=:dependencia AND capacitacion.novedad_id=:novedad  AND (YEAR(fecha_capacitacion)=:ano)
                ';
                $capDep= $connection->createCommand($sql, [
                    ':dependencia' => $dependencia,
                    ':novedad'=>$value->id,
                    ':ano'=>$ano
                ])->queryOne();
                $torta[]=array('name'=>$value->nombre,'y'=>(int)$capDep['cantidad']);
                $capacitaciones_tema[]=array('name'=>$value->nombre,'y'=>(int)$capDep['cantidad'],'capacitaciones'=>$capDep['capacitaciones']);
            }
        
            $torta=json_encode($torta);

            $novedades_seleccionadas=["Seguridad-en-Retail"=>20,"Vigías-Protección-de-Recursos"=>21];
            $array_semestre=[];
            $array_semestre2=[];
            foreach ($novedades_seleccionadas as $key=>$nov) {
                
                $sqlSem='SELECT COUNT(capacitacion.id) as cantidad FROM capacitacion  
                inner join capacitacion_dependencia as cd on cd.capacitacion_id=capacitacion.id
                where cd.centro_costo_codigo=:dependencia AND capacitacion.novedad_id=:novedad 
                ';

                $consultaSem=$sqlSem.'AND (fecha_capacitacion BETWEEN "'.$ano.'-01-01" AND
                "'.$ano.'-06-31")';
                
                $capSem= $connection->createCommand($consultaSem, [
                    ':dependencia' => $dependencia,
                    ':novedad'=>$nov
                ])->queryOne();

                if($capSem['cantidad']!=0){
                    $califSem=($capSem['cantidad']/1)*100;
                    if ($califSem>100) {
                       $califSem=100; 
                    }
                }else{

                    $califSem=0;
                }

                $array_semestre[]=['novedad'=>$key,'cantidad'=>$capSem['cantidad'],'calif'=>$califSem];

                $consultaSem2=$sqlSem.'AND (fecha_capacitacion BETWEEN "'.$ano.'-07-01" AND
                "'.$ano.'-12-31")';

                $capSem2= $connection->createCommand($consultaSem2, [
                    ':dependencia' => $id,
                    ':novedad'=>$nov->id_novedad
                ])->queryOne();

                if($capSem2['cantidad']!=0 ){
                    $califSem2=($capSem2['cantidad']/1)*100;

                    if ($califSem2>100) {
                       $califSem2=100; 
                    }
                }else{
                    $califSem2=0;
                }

                $array_semestre2[]=['novedad'=>$key,'cantidad'=>$capSem2['cantidad'],'calif'=>$califSem2];
                
            }

            if ($fecha_inicio!='' AND $fecha_final!='' ) {

                $filtro_fechaPedido=" AND DATE(pedido.fecha) between '".$_POST['fecha_inicial']."' AND '".$_POST['fecha_final']."' ";
            }else{

                $filtro_fechaPedido=" AND (YEAR(pedido.fecha)='".$ano."')";
            }


            $pedidos = DetallePedido::find()
             ->leftJoin('pedido', 'detalle_pedido.pedido_id = pedido.id')
            ->where('pedido.centro_costo_codigo="'.$dependencia.'" AND  detalle_pedido.estado="C" '.$filtro_fechaPedido.' ')
            ->orderBy(['id_pedido' => SORT_ASC, 'posicion' => SORT_ASC])
            ->all();
            
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $fecha_real_visita = strtotime('+1 day', strtotime($model->fecha_visita));
            $fecha_real_visita = date('Y-m-d', $fecha_real_visita);
            $model->setAttribute('fecha_visita', $fecha_real_visita);
            $model->save();

            $files = UploadedFile::getInstances($model, 'file');

            foreach ($files as $file) {

                if ($file !== null) {
                    $archivo = new ArchivoVisitaMensual();
                    $ext     = end((explode(".", $file->name)));
                    $name    = date('Ymd') . rand(1, 10000) . '' . $file->name;
                    $path    = Yii::$app->params['uploadPath'] . $name;
                    $archivo->setAttribute('archivo', $shortPath . $name);
                    $archivo->setAttribute('visita_mensual_id', $model->id);
                    $file->saveAs($path);
                    $archivo->save();

                }

            }

            $model = new VisitaMensual();
            return $this->render('create', [
                'model'            => $model,
                'dependencias'     => $dependencias,
                'marcasUsuario'    => $marcasUsuario,
                'distritosUsuario' => $distritosUsuario,
                'zonasUsuario'     => $zonasUsuario,
                'mensual'          => 'active',
                'done'             => '200',
            ]);

        } else {
            return $this->render('create', [
                'model'            => $model,
                'dependencias'     => $dependencias,
                'marcasUsuario'    => $marcasUsuario,
                'distritosUsuario' => $distritosUsuario,
                'mensual'          => 'active',
                'zonasUsuario'     => $zonasUsuario,
                'json_bueno'         =>$json_bueno,
                'json_negativo'      =>$json_negativo,
                'fecha_inicio'       =>$fecha_inicio,
                'fecha_final'        =>$fecha_final,
                'model_visita'       =>$model_visita,
                'arr_meses'          =>$arr_meses,
                'codigo_dependencia' => $dependencia,
                'torta'              =>$torta,
                'capacitaciones_tema'=>$capacitaciones_tema,
                'array_semestre'     =>$array_semestre,
                'array_semestre2'    =>$array_semestre2,
                'pedidos'            =>$pedidos
                //'zonas' => $zonas,
            ]);
        }
    }

    /**
     * Updates an existing VisitaMensual model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing VisitaMensual model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $detalles=VisitaMensualDetalle::find()->where('visita_mensual_id ='.$id)->all();

        foreach ($detalles as $key => $value) {
            $archivos=AdjuntoVisitaDetalle::find()->where('visita_detalle_id='.$value->id)->all();

            foreach ($archivos as $key1 => $value1) {
                unlink(Yii::$app->basePath.'/web'.$value1->archivo);
            }
            AdjuntoVisitaDetalle::deleteAll('visita_detalle_id = :id', [':id' => $value->id]); 
        }   
        
        VisitaMensualDetalle::deleteAll('visita_mensual_id = :id', [':id' => $id]);

        $this->findModel($id)->delete();

        Yii::$app->session->setFlash('success','Visita eliminada exitosamente');

        return $this->redirect(['index']);
    }

    public function actionCerrarVisita($id,$dependencia){

        $model=$this->findModel($id);

        $model->setAttribute('estado', 'cerrado');

        $model->save();

        Yii::$app->session->setFlash('success','Visita cerrada exitosamente');
        return $this->redirect(['view', 'id' => $id,'dependencia'=>$dependencia]);
    }


    public function actionAbrirVisita($id,$dependencia){

        $model=$this->findModel($id);

        $model->setAttribute('estado', 'abierta');

        $model->save();

        Yii::$app->session->setFlash('success','Visita abierta exitosamente');
        return $this->redirect(['view', 'id' => $id,'dependencia'=>$dependencia]);
    }

    public function actionDeleteFromCordinador($id, $usuario)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['usuario/mensual?id=' . $usuario]);
    }

    /**
     * Finds the VisitaMensual model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return VisitaMensual the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VisitaMensual::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function dependencias_usuario($id){

        $usuario= Usuario::findOne($id);
        $zonasUsuario     = array();
        $marcasUsuario    = array();
        $distritosUsuario = array();
        $dependencias     = CentroCosto::find()->where(['not in', 'estado', ['C']])->orderBy(['nombre' => SORT_ASC])->all();

        if ($usuario != null) {

            $zonasUsuario     = $usuario->zonas;
            $marcasUsuario    = $usuario->marcas;
            $distritosUsuario = $usuario->distritos;

        }


        $ciudades_zonas = array();

            foreach($zonasUsuario as $zona){
                
                 $ciudades_zonas [] = $zona->zona->ciudades;    
                
            }

            $ciudades_permitidas = array();

            foreach($ciudades_zonas as $ciudades){
                
                foreach($ciudades as $ciudad){
                    
                    $ciudades_permitidas [] = $ciudad->ciudad->codigo_dane;
                    
                }
                
            }

            $marcas_permitidas = array();

            foreach($marcasUsuario as $marca){
                
                    
                    $marcas_permitidas [] = $marca->marca_id;

            }

            $dependencias_distritos = array();

            foreach($distritosUsuario as $distrito){
                
                 $dependencias_distritos [] = $distrito->distrito->dependencias;    
                
            }

            $dependencias_permitidas = array();

            foreach($dependencias_distritos as $dependencias0){
                
                foreach($dependencias0 as $dependencia0){
                    
                    $dependencias_permitidas [] = $dependencia0->dependencia->codigo;
                    
                }
                
            }


            foreach($dependencias as $value){
    
                if(in_array($value->ciudad_codigo_dane,$ciudades_permitidas)){
                    
                    if(in_array($value->marca_id,$marcas_permitidas)){
                        
                       if($tamano_dependencias_permitidas > 0){
                           
                           if(in_array($value->codigo,$dependencias_permitidas)){
                               
                             $data_dependencias[$value->codigo] =  $value->nombre;
                               
                           }else{
                               //temporal mientras se asocian distritos
                               $data_dependencias[] =  $value->codigo;
                           }
                           
                           
                       }else{
                           
                           $data_dependencias[] =  $value->codigo;
                       }    
                   
                    }

                }
            }
            return $data_dependencias;



    }

     public function actionCumplimiento(){
        $id=$_POST['id'];
        $tipo=$_POST['tipo'];
        $solucion=$_POST['solucion'];
        $view=$_POST['view'];
        $dependencia=$_POST['dependencia'];
        $cumple=$_POST['cumple'];

        switch ($tipo) {
            case 'visita':
            
                $model=VisitaMensualDetalle::findOne($id);
                $model->setAttribute('observacion',$solucion);
                $model->setAttribute('cumplimiento',$cumple);
                $model->save();
            break;
            case 'capacitacion':
                $model=NovedadCapacitacion::findOne($id);
                $model->setAttribute('observacion',$solucion);
                $model->setAttribute('cumplimiento',$cumple);
                $model->save();

            break;
            case 'pedido':
                $model=NovedadPedido::findOne($id);
                $model->setAttribute('observacion',$solucion);
                $model->setAttribute('cumplimiento',$cumple);
                $model->save();

            break;
        }

        Yii::$app->session->setFlash('success','Plan de accion editado exitosamente');
        return $this->redirect(['view', 'id' => $view,'dependencia'=>$dependencia]);
    }
}
