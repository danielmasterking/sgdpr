<?php

namespace app\controllers;

use Yii;
use app\models\Proyectos;
use app\models\ProyectoDependencia;
use app\models\ProyectoDependenciaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\CentroCosto;
use app\models\Usuario;
use app\models\ProyectoProvedor;
use app\models\ProyectoSeguimiento;
use app\models\ProyectoUsuarios;
use app\models\ProyectoSeguimientoArchivo;
use app\models\TipoReportes;
use app\models\ProyectosPresupuesto;
use app\models\Pedido;
use app\models\ProyectoPedidos;
use app\models\ProyectoPedidoEspecial;
use app\models\DetallePedido;
use app\models\SistemaProyectos;
use app\models\ProyectoSistema;
use app\models\ProyectosHistoricoPorcentaje;
use app\models\LogFechaProyectos;
use app\models\CronogramaProyecto;
use app\models\Empresa;
use app\models\ProyectosPresupuestoAdicional;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * ProyectoDependenciaController implements the CRUD actions for ProyectoDependencia model.
 */
class ProyectoDependenciaController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','view','create','update','delete','agregar','suma'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index','view','create','update','delete','agregar','suma'],
                        'roles' => ['@'],//para usuarios logueados
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['GET'],
                ],
            ],
        ];
    }

    /**
     * Lists all ProyectoDependencia models.
     * @return mixed
     */
    public function actionIndex($estado="A")
    {
       
       $model=new Proyectos;
        //////////DEPENDENCIAS DEL USUARIO
        $dependencias_user=$this->dependencias_usuario(Yii::$app->session['usuario-exito']);

        $permisos = array();
        if( isset(Yii::$app->session['permisos-exito']) ){
            $permisos = Yii::$app->session['permisos-exito'];
        }

        if(in_array("administrador", $permisos)){
            $rows=$model->find()->where('estado_proyecto IN("'.$estado.'")')->orderBy('id DESC')->all();
        }else{
            $numdep=count($dependencias_user);

            if($numdep>0){
                $in="(ceco IN(";
                foreach ($dependencias_user as $value) {
                    
                    $in.=" '".$value."',";    
                }

                $in_final = substr($in, 0, -1).")) OR ";
            }else{
                $in_final="";
            }

            $usuarios_asignados=ProyectoUsuarios::UsuariosAsignados(Yii::$app->session['usuario-exito']);

            $rows=$model->find()->where(' '.$usuarios_asignados.' (solicitante="'.Yii::$app->session['usuario-exito'].'") AND (estado_proyecto IN("'.$estado.'")) ')->all();
            
        }

        return $this->render('index', [
            'rows' => $rows,
            'model'=>$model,
            'permisos'=>$permisos,
            'estado'=>$estado
            
        ]);
    }

    /**
     * Displays a single ProyectoDependencia model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $model= $this->findModel($id);
        $model_adicional_proyecto=new ProyectosPresupuestoAdicional();
        $model_adicional_proyecto->id_proyecto=$id;
        $query_saldo_adicional=$model_adicional_proyecto->find()->where('id_proyecto='.$id)->orderby('fecha DESC')->all();

        if ($model_adicional_proyecto->load(Yii::$app->request->post()) ) {
            $suma_adicional_activo=($model->suma_adicional_activo+$model_adicional_proyecto->activo);
            $suma_adicional_gasto=($model->suma_adicional_gasto+$model_adicional_proyecto->gasto);
            date_default_timezone_set('America/Bogota');
            $fecha = date('Y-m-d H:i:s',time());
            $model->setAttribute('suma_adicional_activo',$suma_adicional_activo);
            $model->setAttribute('suma_adicional_gasto',$suma_adicional_gasto);
            $model_adicional_proyecto->setAttribute('fecha',$fecha);
            $model->save();
            $model_adicional_proyecto->save();
            return $this->redirect(['view', 'id' => $id ]);
        }else{
            $model_adicional_proyecto->activo=0;
            $model_adicional_proyecto->gasto=0;
        }

        if (isset($_POST['fecha_cambio'])) {
            $model_log=new LogFechaProyectos;
            $model_log->setAttribute('fecha',$_POST['fecha_fin']);
            $model_log->setAttribute('descripcion',$_POST['motivo']);
            $model_log->setAttribute('id_proyecto',$id);
            $model_log->save();
            return $this->redirect(['view','id'=>$id]);
        }
        $detalle=ProyectoSeguimiento::find()->where('id_proyecto='.$id)->orderby('fecha DESC')->all();
        $presupuestos = ProyectosPresupuesto::find()->where('fk_proyectos='.$id)->orderby('id DESC')->all();

        $log_fechas=LogFechaProyectos::find()->where('id_proyecto='.$id)->orderby('id Desc')->all();
        /*$porcentaje_total = (new \yii\db\Query())
        ->select('(SUM(avance)/COUNT(id)) as TOTAL')
        ->from('proyecto_seguimiento')
        ->where('id_proyecto='.$id)
        ->one();

        $porcentaje_total_sistema = (new \yii\db\Query())
        ->select('(SUM(p.avance)/COUNT(p.id)) as TOTAL,sistema.nombre as SISTEMA')
        ->from('proyecto_seguimiento as p')
        ->innerjoin('sistema_proyectos as sistema','p.id_sistema=sistema.id')
        ->where('id_proyecto='.$id)
        ->groupby('id_sistema')
        ->all();*/

        $sistemas=ProyectoSistema::find()->where('id_proyecto='.$id)->all();
        //calcular total de gastos de pedidos por sistema
        //$todos_sistemas=SistemaProyectos::find()->all();
        $array_gasto_pedidos=[];
        foreach ($sistemas as $value_st) {
            $query_costo = (new \yii\db\Query())
            ->select('SUM(precio_neto) as TOTAL,gasto_activo')
            ->from('proyecto_pedidos')
            ->where('proyecto_id='.$id.' AND sistema="'.$value_st->sistema->nombre.'" ')
            ->one();
            if ($query_costo['gasto_activo']=='activo') {
               $iva=$model->iva;
               $total_iva=($query_costo['TOTAL']*$iva)/100;
               $total_final=($query_costo['TOTAL']+$total_iva);
            }else{
                $total_final=$query_costo['TOTAL'];
            }

            $array_gasto_pedidos[]=['sistema'=>$value_st->sistema->nombre,'total'=>$total_final];
        }

       /* echo "<pre>";
        print_r($array_gasto_pedidos);
        echo "</pre>";*/
        /////////////////////////////////////////////////
        //print_r($porcentaje_total_sistema);
        $permisos = array();
		if( isset(Yii::$app->session['permisos-exito']) ){
		    $permisos = Yii::$app->session['permisos-exito'];
		}
        //para el formulario de seguimiento
        $model_seguimiento=new ProyectoSeguimiento();
        $model2=new ProyectoDependencia();
        $model3=new Proyectos();
        $tipo_reportes=TipoReportes::find()->all();
        $list_reportes = ArrayHelper::map($tipo_reportes, 'id', 'nombre');
        $list_sistemas=$model3->ProyectoSistemas($id);
        $list_sistemas[7]='General';
        $model_seguimiento->fecha=date('Y-m-d');
        $model_seguimiento->usuario=Yii::$app->session['usuario-exito'];
        $provedores=$model2->ProyectoProvedor($id);

        $array_porcentaje=[];

        for ($i=0; $i <= 100; $i++) { 
            $array_porcentaje[$i]=$i."%";
        }
        //////////////////////////////////////
        $historial=ProyectosHistoricoPorcentaje::find()
        ->where('id_proyecto='.$id)
        ->orderby('fecha DESC')
        ->all();

        $model_cronograma=new CronogramaProyecto; 
        $cronograma=$model_cronograma->find()
        ->where('id_proyecto='.$id)
        ->orderby('fecha_inicio DESC')
        ->all();

        $array_crono=[];
        foreach ($cronograma as $crono) {
            $array_crono[]=[
                'title'=>$crono->tipo_trabajo."  ".$crono->fecha_inicio." - ".$crono->fecha_fin,
                'start'=>$crono->fecha_inicio,
                'end'=>$crono->fecha_fin,
                'id'=>$crono->id,
                'color'=>(string)$crono->color
                ];
        }

        $json_crono=json_encode($array_crono);

        $usuarios=Usuario::find()->where('estado="A"')->all();
        $list_usuarios=ArrayHelper::map($usuarios, 'usuario', 'usuario');
        return $this->render('view', [
            'model' =>$model,
            'detalle'=>$detalle,
            'id'=>$id,
            'permisos'=>$permisos,
            'presupuestos' => $presupuestos,
            //'porcentaje_total'=>$porcentaje_total,
            //'porcentaje_total_sistema'=>$porcentaje_total_sistema,
            'sistemas'=>$sistemas,
            'historial'=>$historial,
            'model_seguimiento' => $model_seguimiento,
            'list_sistemas'=>$list_sistemas,
            'array_porcentaje'=>$array_porcentaje,
            'provedores'=>$provedores,
            'list_reportes'=>$list_reportes,
            'model_cronograma'=>$model_cronograma,
            'cronograma'=>$cronograma,
            'json_crono'=>$json_crono,
            'list_usuarios'=>$list_usuarios,
            'log_fechas'=>$log_fechas,
            'model_adicional_proyecto'=>$model_adicional_proyecto,
            'query_saldo_adicional'=>$query_saldo_adicional,
            'array_gasto_pedidos'=>$array_gasto_pedidos
        ]);
    }

    /**
     * Creates a new ProyectoDependencia model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Proyectos();
        $empresas=Empresa::find()->all();
        $array_empresas=ArrayHelper::map($empresas, 'nit', 'nombre');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->setAttribute('fecha_apertura',$_POST['Proyectos']['fecha_apertura']);
            $model->save();
            $provedores=$_POST['provedor'];

            if(count($provedores)>0){
                foreach ($provedores as $pr) {
                    $proyecto_provedor=new ProyectoProvedor();
                    $proyecto_provedor->setAttribute('id_proyecto',$model->id);
                    $proyecto_provedor->setAttribute('id_provedor',$pr);
                    $proyecto_provedor->save();
                }
            }

            $usuarios=$_POST['usuarios'];

            if(count($usuarios)>0){
                foreach ($usuarios as $us) {
                    $proyecto_usuario=new ProyectoUsuarios();
                    $proyecto_usuario->setAttribute('id_proyecto',$model->id);
                    $proyecto_usuario->setAttribute('usuario',$us);
                    $proyecto_usuario->save();
                }
            }

            $sistemas=$_POST['sistemas'];

            if(count($sistemas)>0){
                $i=0;
                foreach ($sistemas as $st) {
                    $proyecto_sistema=new ProyectoSistema();
                    $proyecto_sistema->setAttribute('id_proyecto',$model->id);
                    $proyecto_sistema->setAttribute('id_sistema',$st);
                    if(isset($_POST['check_otro'][$i])){
                        $proyecto_sistema->setAttribute('otro',(string)$_POST['otro'][$i]);
                        $proyecto_sistema->setAttribute('encargado','');
                    }else{
                        $proyecto_sistema->setAttribute('encargado',(string)$_POST['encargado'][$i]);
                        //echo $_POST['encargado'][$i]."<br>";
                    }
                    $proyecto_sistema->save();
                    $i++;
                }
            }

            return $this->redirect(['view','id'=>$model->id]);
        } else {

            $model->created_on=date('Y-m-d');
            $model->solicitante=Yii::$app->session['usuario-exito'];
            $usuario          = Usuario::findOne(Yii::$app->session['usuario-exito']);
            $distritosUsuario = array();
            $zonasUsuario     = array();
            $marcasUsuario    = array();

            if ($usuario != null) {

                $distritosUsuario = $usuario->distritos;
                $zonasUsuario     = $usuario->zonas;
                $marcasUsuario    = $usuario->marcas;
            }

            $dependencias     = CentroCosto::find()->where("estado IN('D','A')")->orderBy(['nombre' => SORT_ASC])->all();
            
            return $this->render('create', [
                'model' => $model,
                'dependencias'     => $dependencias,
                'distritosUsuario' => $distritosUsuario,
                'marcasUsuario'    => $marcasUsuario,
                'zonasUsuario'     => $zonasUsuario,
                'array_empresas'   => $array_empresas
            ]);
        }
    }


    public function actionAgregar($id){
        $model=new ProyectoSeguimiento();
        $model2=new ProyectoDependencia();
        $tipo_reportes=TipoReportes::find()->all();
        $list_reportes = ArrayHelper::map($tipo_reportes, 'id', 'nombre');
        $sistemas=$model->Sistemas();
        $model->fecha=date('Y-m-d');
        $model->usuario=Yii::$app->session['usuario-exito'];
        $provedores=$model2->ProyectoProvedor($id);
        $array_porcentaje=[];

        for ($i=0; $i <= 100; $i++) { 
            $array_porcentaje[$i]=$i."%";
        }

        Yii::$app->params['uploadPath'] = Yii::$app->basePath . '/web/uploads/seguimiento_proyecto/';
        $shortPath                      = '/uploads/seguimiento_proyecto/';

        if ($model->load(Yii::$app->request->post()) /*&& $model->save()*/) {
            $model->setAttribute('id_proyecto',$id);
            $model->setAttribute('id_tipo_reporte',$_POST['ProyectoSeguimiento']['id_tipo_reporte']);
            if($_POST['ProyectoSeguimiento']['id_tipo_reporte']!=6){
                $model->setAttribute('avance','');
            }

            //if($_POST['ProyectoSeguimiento']['id_sistema']==0){
              //  $model->setAttribute('id_sistema',7);
            //}

            if($model->save()){

                if($model->id_tipo_reporte==6){
                    $historico=new ProyectosHistoricoPorcentaje;
                    $historico->setAttribute('id_seguimiento',$model->id);
                    $historico->setAttribute('fecha',date('Y-m-d'));
                    $historico->setAttribute('porcentaje',$model->avance);
                    $historico->setAttribute('accion',"I");
                    $historico->setAttribute('usuario',Yii::$app->session['usuario-exito']);
                    $historico->setAttribute('id_sistema',$model->id_sistema);
                    $historico->setAttribute('id_reporte',$model->id_tipo_reporte);
                    $historico->setAttribute('id_proyecto',$id);
                    $historico->save();

                }

                $image = UploadedFile::getInstances($model, 'image');
                if ($image !== null) {
                    
                    foreach ($image as $img) {
                       

                        $archivo=new ProyectoSeguimientoArchivo();
                        $name    = date('Ymd') . rand(1, 10000) . '' . $img->name;
                        $path    = Yii::$app->params['uploadPath'] . $name;
                        $archivo->setAttribute('archivo', $shortPath . $name);
                        $archivo->setAttribute('seguimiento_id', $model->id);
                        $img->saveAs($path);
                        $verifica_imagen=Yii::$app->verificar_imagen->esImagen($path);
                        if ($verifica_imagen) {       
                           Yii::$app->verificar_imagen->Redimenzionar($path,$img->type);
                           //unlink($path);
                        }
                        $archivo->save();
                        
                    }

                }

                return $this->redirect(['view','id'=>$id]);
            }else{

                print_r($model->getErrors());
            }
        }else{
         return $this->render('proyecto_seguimiento', [
                'model' => $model,
                'sistemas'=>$sistemas,
                'id'=>$id,
                'array_porcentaje'=>$array_porcentaje,
                'provedores'=>$provedores,
                'list_reportes'=>$list_reportes
            ]);
        }

    }

    public function actionEditarseguimiento($id,$id_proyecto){
        $model=ProyectoSeguimiento::findOne($id);
        $model2=new ProyectoDependencia();
        $model3=new Proyectos();
        $sistemas=$model3->ProyectoSistemas($id_proyecto);

        $sistemas[7]='General';
        $tipo_reportes=TipoReportes::find()->all();
        $list_reportes = ArrayHelper::map($tipo_reportes, 'id', 'nombre');
        //$sistemas=$model->Sistemas();
        $model->fecha=date('Y-m-d');
        $model->usuario=Yii::$app->session['usuario-exito'];
        $array_porcentaje=[];
        $provedores=$model2->ProyectoProvedor($id_proyecto);

        for ($i=0; $i <= 100; $i++) { 
            $array_porcentaje[]=$i."%";
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //$model->setAttribute('id_proyecto',$id);

            if($_POST['ProyectoSeguimiento']['id_tipo_reporte']!=6){
                $model->setAttribute('avance','');
            }
            $model->save();
            if($model->id_tipo_reporte==6){
                $historico=new ProyectosHistoricoPorcentaje;
                $historico->setAttribute('id_seguimiento',$model->id);
                $historico->setAttribute('fecha',date('Y-m-d'));
                $historico->setAttribute('porcentaje',$model->avance);
                $historico->setAttribute('accion',"U");
                $historico->setAttribute('usuario',Yii::$app->session['usuario-exito']);
                $historico->setAttribute('id_sistema',$model->id_sistema);
                $historico->setAttribute('id_reporte',$model->id_tipo_reporte);
                $historico->setAttribute('id_proyecto',$id_proyecto);
                $historico->save();

            }
            
            return $this->redirect(['view','id'=>$id_proyecto]);
        }else{
         return $this->render('proyecto_seguimiento', [
                'model' => $model,
                'sistemas'=>$sistemas,
                'id'=>$id_proyecto,
                'array_porcentaje'=>$array_porcentaje,
                'provedores'=>$provedores,
                'list_reportes'=>$list_reportes
            ]);
        }

    }

    public function actionDeleteseguimiento($id,$id_proyecto){
        $model=ProyectoSeguimiento::findOne($id);
        $model->delete();
        return $this->redirect(['view','id'=>$id_proyecto]);
    }
    /**
     * Updates an existing ProyectoDependencia model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $usuario          = Usuario::findOne(Yii::$app->session['usuario-exito']);
        $distritosUsuario = array();
        $zonasUsuario     = array();
        $marcasUsuario    = array();

        if ($usuario != null) {

            $distritosUsuario = $usuario->distritos;
            $zonasUsuario     = $usuario->zonas;
            $marcasUsuario    = $usuario->marcas;
        }

        $dependencias     = CentroCosto::find()->where("estado IN('D','A')")->orderBy(['nombre' => SORT_ASC])->all();
        $proyecto_provedores=ProyectoProvedor::find()->where('id_proyecto='.$model->id)->all();
        $arrayProvedor=[];
        foreach ($proyecto_provedores as $key => $value) {
            $arrayProvedor[]=$value->id_provedor;
        }

        $arrayUsuarios=[];
        $proyectoUsuarios=ProyectoUsuarios::find()->where('id_proyecto='.$model->id)->all();
        foreach ($proyectoUsuarios as $pu) {
            $arrayUsuarios[]=$pu->usuario;
        }

        $arraySistema=[];
        $proyectoSistema=ProyectoSistema::find()->where('id_proyecto='.$model->id)->all();
        foreach ($proyectoSistema as $ps) {
            $arraySistema[]=$ps->id_sistema;
        }

        $empresas=Empresa::find()->all();
        $array_empresas=ArrayHelper::map($empresas, 'nit', 'nombre');


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->setAttribute('fecha_apertura',$_POST['Proyectos']['fecha_apertura']);
            $model->save();
            ProyectoProvedor::deleteAll('id_proyecto = :id ', [':id' =>$id]);
            $provedores=$_POST['provedor'];

            if(count($provedores)>0){
                foreach ($provedores as $pr) {
                    $proyecto_provedor=new ProyectoProvedor();
                    $proyecto_provedor->setAttribute('id_proyecto',$model->id);
                    $proyecto_provedor->setAttribute('id_provedor',$pr);
                    $proyecto_provedor->save();
                }
            }

            ProyectoUsuarios::deleteAll('id_proyecto = :id ', [':id' =>$id]);
            $usuarios=$_POST['usuarios'];
            if(count($usuarios)>0){
                foreach ($usuarios as $us) {
                    $proyecto_usuario=new ProyectoUsuarios();
                    $proyecto_usuario->setAttribute('id_proyecto',$model->id);
                    $proyecto_usuario->setAttribute('usuario',$us);
                    $proyecto_usuario->save();
                }
            }

            ProyectoSistema::deleteAll('id_proyecto = :id ', [':id' =>$id]);

            $sistemas=$_POST['sistemas'];

            if(count($sistemas)>0){
                $i=0;
                foreach ($sistemas as $st) {
                    $proyecto_sistema=new ProyectoSistema();
                    $proyecto_sistema->setAttribute('id_proyecto',$model->id);
                    $proyecto_sistema->setAttribute('id_sistema',$st);
                    if(isset($_POST['check_otro'][$i])){
                        $proyecto_sistema->setAttribute('otro',(string)$_POST['otro'][$i]);
                        $proyecto_sistema->setAttribute('encargado','');
                    }else{
                        $proyecto_sistema->setAttribute('encargado',(string)$_POST['encargado'][$i]);
                        //echo $_POST['encargado'][$i]."<br>";
                    }
                    $proyecto_sistema->save();
                    $i++;
                }
            }

            return $this->redirect(['view', 'id' => $model->id]);
        } else {

        	if($model->fecha_apertura=='0000-00-00'){
        		$model->fecha_apertura='';
        	}

            return $this->render('update', [
                'model' => $model,
                'dependencias'     => $dependencias,
                'distritosUsuario' => $distritosUsuario,
                'marcasUsuario'    => $marcasUsuario,
                'zonasUsuario'     => $zonasUsuario,
                'arrayProvedor'    =>$arrayProvedor,
                'arrayUsuarios'    =>$arrayUsuarios,
                'arraySistema'     =>$arraySistema,
                'array_empresas'   =>$array_empresas,
                'proyectoSistema'  =>$proyectoSistema
            ]);
        }
    }

    /**
     * Deletes an existing ProyectoDependencia model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        ProyectoProvedor::deleteAll('id_proyecto = :id ', [':id' =>$id]);

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    

    public function actionAgregarPresupuesto(){
    	$id=$_POST['id'];
        $actualizar=$_POST['actualizar'];
        echo "El id: ".$id;
        $model = $this->findModel($id);
        //if($model->estado=='ABIERTO'){
            $array_post = Yii::$app->request->post();
            // echo "<pre>";
            // print_r($array_post);
            // echo "</pre>";
            //if (isset($array_post['Proyectos']['presupuesto_seguridad'])) {
            if (isset($array_post['Proyectos']['presupuesto_activo'])) {
                //print_r(Yii::$app->request->post());exit();
                date_default_timezone_set('America/Bogota');
                $fecha = date('Y-m-d H:i:s',time());

                if($actualizar==0){
                    $model->setAttribute('presupuesto_activo', 
                        $model->presupuesto_activo+$array_post['Proyectos']['presupuesto_activo']
                    );
                    //echo $model->presupuesto_riesgo;exit();
                    $model->setAttribute('presupuesto_gasto', 
                        $model->presupuesto_gasto+$array_post['Proyectos']['presupuesto_gasto']
                    );
                    //$model->setAttribute('presupuesto_activo',0);
                    //$model->setAttribute('presupuesto_gasto', 0);
                    $model->setAttribute('presupuesto_total', 
                        // $model->presupuesto_seguridad+
                        // $model->presupuesto_riesgo
                        $model->presupuesto_activo+
                         $model->presupuesto_gasto
                    );
                    $model->setAttribute('modificado_por', Yii::$app->session['usuario-exito']);
                    $model->setAttribute('modified_in', $fecha);
                }else{

                    $model->setAttribute('presupuesto_activo', 
                        $array_post['Proyectos']['presupuesto_activo']
                    );
                    //echo $model->presupuesto_riesgo;exit();
                    $model->setAttribute('presupuesto_gasto', 
                        $array_post['Proyectos']['presupuesto_gasto']
                    );
                    //$model->setAttribute('presupuesto_activo',0);
                    //$model->setAttribute('presupuesto_gasto', 0);
                    $model->setAttribute('presupuesto_total', 
                        // $model->presupuesto_seguridad+
                        // $model->presupuesto_riesgo
                        $model->presupuesto_activo+
                         $model->presupuesto_gasto
                    );
                    $model->setAttribute('modificado_por', Yii::$app->session['usuario-exito']);
                    $model->setAttribute('modified_in', $fecha);
                    //ProyectosPresupuesto::deleteAll(['fk_proyectos' => $id]);

                }
                if ($model->save()) {
                    $model_preps = new ProyectosPresupuesto();
                    $model_preps->setAttribute('fk_proyectos', $id);
                    // $model_preps->setAttribute('presupuesto_seguridad', $array_post['Proyectos']['presupuesto_seguridad']);
                    // $model_preps->setAttribute('presupuesto_riesgo', $array_post['Proyectos']['presupuesto_riesgo']);
                    $model_preps->setAttribute('presupuesto_activo', $array_post['Proyectos']['presupuesto_activo']);
                    $model_preps->setAttribute('presupuesto_gasto', $array_post['Proyectos']['presupuesto_gasto']);
                    $model_preps->setAttribute('created_on', $fecha);
                    if($model_preps->save()){
                        return $this->redirect(['view','id'=>$id]);

                    }else{
                        print_r($model_preps->getErrors());
                    }
                }else{
                    print_r($model->getErrors());
                }
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        /*}else{
            return $this->redirect(['index', 
                'mensaje' => 'El proyecto se encuentra en estado '.$model->estado,
            ]);
        }*/
        
    }


    /**
     * Finds the ProyectoDependencia model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProyectoDependencia the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Proyectos::findOne($id)) !== null) {
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
                        
                       /*if($tamano_dependencias_permitidas > 0){
                           
                           if(in_array($value->codigo,$dependencias_permitidas)){
                               
                             $data_dependencias[$value->codigo] =  $value->nombre;
                               
                           }else{
                               //temporal mientras se asocian distritos
                               $data_dependencias[] =  $value->codigo;
                           }
                           
                           
                       }else{*/
                           
                           $data_dependencias[] =  $value->codigo;
                       //}    
                   
                    }

                }
            }
            return $data_dependencias;



    }


    public function actionEstadoPresupuesto(){
        $array_post = Yii::$app->request->post();
        //$presupuesto = Proyectos::findOne($array_post['presupuesto']);
        //if($presupuesto->estado=='ABIERTO'){
        //$presupuesto->setAttribute('estado',$array_post['estado']);
        //if($presupuesto->save()){
        //}else if($presupuesto->estado=='CERRADO'){
          //  $presupuesto->setAttribute('estado', 'ABIERTO');
            //$presupuesto->save();
        //}
        //$presupuesto->save();

        Proyectos::updateAll(['estado' => $array_post['estado']], ['=', 'id', $array_post['presupuesto']]);
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'respuesta' => $array_post['estado']/*$presupuesto->estado*/,
            ];
        /*}else{
            print_r($presupuesto->errors);
        }*/
    }


    public function actionCerrarPresupuesto(){
        $array_post = Yii::$app->request->post();
        $presupuesto = Proyectos::findOne($array_post['proyecto']);
        //crear un pedido
        $pedido = new Pedido();
        $proyecto=Proyectos::find()->where('id='.$array_post['proyecto'])->one();
        //$pedido->setAttribute('solicitante', Yii::$app->session['usuario-exito']);
        $pedido->setAttribute('solicitante', $proyecto->solicitante);
        $pedido->setAttribute('estado', 'P');
        $pedido->setAttribute('centro_costo_codigo', $presupuesto->ceco);
        date_default_timezone_set ( 'America/Bogota');
        $fecha = date('Y-m-d');
        $pedido->setAttribute('fecha', $fecha);
        $pedido->setAttribute('observaciones', 'X');
        $pedido->setAttribute('tipo', 'N');
        $pedido->setAttribute('especial', 'N');
        $pedido->setAttribute('orden_interna_gasto', $presupuesto->orden_interna_gasto);
        $pedido->setAttribute('orden_interna_activo', $presupuesto->orden_interna_activo);
        $pedido->setAttribute('fk_presupuesto_id', $presupuesto->id);
        $respuesta="";
        if ($pedido->save()) {
            //buscar los pedidos del proyecto
            $pedidos_normal = ProyectoPedidos::find()->where('proyecto_id='.$array_post['proyecto'])
            ->andWhere("estado_id<>2")
            ->all();
            $pedidos_especial = ProyectoPedidoEspecial::find()->where('proyecto_id='.$array_post['proyecto'])->all();
            //copiar 1 a 1 a las tablas de pedidos normal
            foreach ($pedidos_normal as $pn) {
                $modelo_detalle =new DetallePedido();
                $modelo_detalle->setAttribute('detalle_maestra_id', $pn->detalle_maestra_id);
                $modelo_detalle->setAttribute('pedido_id', $pedido->id);
                $modelo_detalle->setAttribute('estado', 'P');
                $modelo_detalle->setAttribute('cantidad', $pn->cantidad);
                $modelo_detalle->setAttribute('precio_neto', $pn->precio_neto);
                $modelo_detalle->setAttribute('observaciones', $pn->observaciones);
                $modelo_detalle->setAttribute('codigo_activo', '');
                $modelo_detalle->setAttribute('imputacion', $pn->detalleMaestra->imputacion);
                $modelo_detalle->setAttribute('ordinario', 'S');
                $modelo_detalle->setAttribute('cebe', $presupuesto->cecoo->cebe);
                $modelo_detalle->setAttribute('dep', $presupuesto->cecoo->nombre);
                $modelo_detalle->setAttribute('proveedor', $pn->detalleMaestra->maestra->proveedor->nombre);
                $modelo_detalle->setAttribute('repetido', $pn->repetido);
                $modelo_detalle->setAttribute('gasto_activo', $pn->gasto_activo);
                date_default_timezone_set('America/Bogota');
                $fecha_hora = date("Y-m-d H:i:s");
                $modelo_detalle->setAttribute('created_on', $fecha_hora);
                if(!$modelo_detalle->save()){
                    $respuesta="";print_r($modelo_detalle->getErrors());exit();
                }
            }
            //copiar 1 a 1 a las tablas de pedidos especial
           /* foreach ($pedidos_especial as $pe) {
                $modelo_detalle_esp = new DetallePedidoEspecial();
                $modelo_detalle_esp->setAttribute('producto_sugerido', $pe->producto_sugerido);
                $modelo_detalle_esp->setAttribute('cantidad', $pe->cantidad);
                $modelo_detalle_esp->setAttribute('proveedor_sugerido', $pe->proveedor_sugerido);
                $modelo_detalle_esp->setAttribute('precio_sugerido', $pe->precio_sugerido);
                $modelo_detalle_esp->setAttribute('precio_neto', $pe->precio_neto);
                $modelo_detalle_esp->setAttribute('observaciones', $pe->observaciones);
                $modelo_detalle_esp->setAttribute('archivo', $presupuesto->archivo);
                $modelo_detalle_esp->setAttribute('maestra_especial_id', $pe->maestra_especial_id);
                $modelo_detalle_esp->setAttribute('pedido_id', $pedido->id);
                $modelo_detalle_esp->setAttribute('estado', 'P');
                $modelo_detalle_esp->setAttribute('imputacion', $pe->maestraEspecial->imputacion);
                $modelo_detalle_esp->setAttribute('cebe', $presupuesto->cecoo->cebe);
                $modelo_detalle_esp->setAttribute('dep', $presupuesto->cecoo->nombre);
                $modelo_detalle_esp->setAttribute('repetido', $pe->repetido);
                date_default_timezone_set('America/Bogota');
                $fecha_hora = date("Y-m-d H:i:s");
                $modelo_detalle_esp->setAttribute('created_on', $fecha_hora);
                if(!$modelo_detalle_esp->save()){
                    $respuesta="";print_r($modelo_detalle_esp->getErrors());exit();
                }
            }*/
            $command = Yii::$app->db->createCommand(
                "UPDATE proyectos SET ".
                "estado='CERRADO'".
                " WHERE id=".$array_post['proyecto']);
            $command->execute();
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'respuesta' => '',
        ];
    }


    public function actionGuardarDatos(){
        $model = Proyectos::findOne($_POST['proyecto']);
        $model->setAttribute('iva', $_POST['iva']);
        $model->setAttribute('orden_interna_gasto', $_POST['orden_interna_gasto']);
        $model->setAttribute('orden_interna_activo', $_POST['orden_interna_activo']);
        $model->setAttribute('metros_cuadrados', $_POST['metros2']);
        if($model->save()){
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'respuesta' => true,
                'orden_interna_gasto'=>$_POST['orden_interna_gasto'],
                'orden_interna_activo'=>$_POST['orden_interna_activo']
            ];
        }else{
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'respuesta' => print_r($model->getErrors()),
            ];
        }
    }
     
    public function actionVerifica_asignado(){
        $array_post = Yii::$app->request->post();
        $pedidos_normal = ProyectoPedidos::find()->where('proyecto_id='.$array_post['proyecto'])
            ->andWhere("gasto_activo=''")
            ->andWhere("estado_id<>2")
            ->count();


        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'respuesta' => $pedidos_normal,
        ];

    }

    public function actionPedidosCreate($id="0",$ceco="0"){
        $presupuesto = $this->findModel($id);
        if($presupuesto->estado=='ABIERTO'){
            $array_post = Yii::$app->request->post();
            if (isset($array_post['cantidad-productos'])) {
                $cantidad    = array_key_exists('cantidad-productos', $array_post) ? $array_post['cantidad-productos'] : 0;
                $tipo_pedido = array_key_exists('tipo-pedido', $array_post) ? $array_post['tipo-pedido'] : '';
                //existen productos solicitados
                if ($cantidad > 0) {
                    $tipo = 'S';
                    if ($tipo_pedido == 'on') {
                        $tipo = 'N';
                    }
                    $proyecto = $this->findModel($array_post['pedido']);
                    //guardar productos solicitados
                    for ($i = 1; $i <= $cantidad; $i++) {
                        $model = new ProyectoPedidos();
                        $prod = array_key_exists('sel-produ-' . $i, $array_post) ? $array_post['sel-produ-' . $i] : 0;
                        if ($prod != 0) {
                            $cantidad_prod = $array_post['txt-cant-' . $i];
                            $obs_prod      = $array_post['txt-comentario-' . $i];
                            $precio_neto   = $array_post['price-' . $i];
                            $model->setAttribute('detalle_maestra_id', $prod);
                            $model->setAttribute('proyecto_id', $proyecto->id);
                            $model->setAttribute('estado_id', 1);
                            $model->setAttribute('cantidad', $cantidad_prod);
                            $model->setAttribute('precio_neto', $precio_neto);
                            $model->setAttribute('observaciones', $obs_prod);
                            $model->setAttribute('cebe', $proyecto->cecoo->cebe);
                            $model->setAttribute('tipo_presupuesto', $array_post['tipo_presupuesto']);
                            $model->setAttribute('solicitante', Yii::$app->session['usuario-exito']);
                            date_default_timezone_set('America/Bogota');
                            $fecha_hora = date("Y-m-d H:i:s");
                            $model->setAttribute('created_on', $fecha_hora);
                            //validar para repetido. buscar en la tabla por detalle_maestra_id, cantidad y dependencia hace 182 dias(6 meses)
                            $sql = "SELECT * FROM proyecto_pedidos WHERE created_on BETWEEN DATE_SUB('".$fecha_hora."', INTERVAL 182 DAY) AND '".$fecha_hora."' AND detalle_maestra_id=".$prod." AND cantidad=".$cantidad_prod.' ORDER BY created_on LIMIT 1';
                            $repetido = ProyectoPedidos::findBySql($sql);
                            $mrepetido = $repetido->count();
                            //echo $repetido->createCommand()->getRawSql();exit();
                            if($mrepetido>0){
                                $model->setAttribute('repetido', 'SI');
                            }
                            if($model->save()){
                                
                            }else{
                                print_r($model->getErrors());
                            }
                        }
                    }
                    return $this->redirect(['view','id'=>$id]);
                }
            } else {
                return $this->render('pedidos/create_pedido', [
                    'ceco' => $ceco,
                    'id' => $id,
                ]);
            }
        }else{
            return $this->redirect(['index', 
                'mensaje' => 'El proyecto se encuentra en estado '.$presupuesto->estado,
            ]);
        }
        
    }


    public function actionPedidosCreateEspecial($id="0",$ceco="0"){
        $presupuesto = $this->findModel($id);
        if($presupuesto->estado=='ABIERTO'){
            $array_post = Yii::$app->request->post();
            if (isset($array_post['cantidad-productos'])) {
                $cantidad    = array_key_exists('cantidad-productos', $array_post) ? $array_post['cantidad-productos'] : 0;
                $file = UploadedFile::getInstanceByName('file');
                $proyecto = $this->findModel($array_post['pedido']);
                $model = new ProyectoPedidoEspecial();
                if ($cantidad > 0) {
                    $filename='';
                    if ($file !== null) {
                        $shortPath = 'uploads/proyectos/';
                        $ext            = end((explode(".", $file->name)));
                        $name           = date('Ymd') . rand(1, 10000) . '' . $file->name;
                        $path           = Yii::$app->basePath . '/web/uploads/proyectos/' . $name;
                        $filename = $shortPath . $name;
                        $file->saveAs($path);
                    }
                    //guardar productos solicitados
                    for ($i = 1; $i <= $cantidad; $i++) {
                        $prod = array_key_exists('sel-produ-' . $i, $array_post) ? $array_post['sel-produ-' . $i] : 0;
                        if ($prod != 0) {
                            $cantidad_prod = $array_post['txt-cant-' . $i];
                            $obs_prod      = $array_post['txt-prod-' . $i];
                            $precio_neto   = $array_post['price-' . $i];
                            $precio='';
                            if($array_post['txt-precio-' . $i]!=''){
                                $precio        = str_replace(".", "", $array_post['txt-precio-' . $i]);
                            }else{
                                $precio        = $array_post['txt-precio-' . $i];
                            }
                            $proveedor     = $array_post['txt-proveedor-' . $i];
                            $model->setAttribute('maestra_especial_id', $prod);
                            $model->setAttribute('proyecto_id', $proyecto->id);
                            $model->setAttribute('estado_id', 1);
                            $model->setAttribute('cantidad', $cantidad_prod);
                            $model->setAttribute('producto_sugerido', $obs_prod);
                            $model->setAttribute('precio_sugerido', $precio);
                            $model->setAttribute('precio_neto', $precio_neto);
                            $model->setAttribute('proveedor_sugerido', $proveedor);
                            $model->setAttribute('cebe', $proyecto->cecoo->cebe);
                            $model->setAttribute('tipo_presupuesto', $array_post['tipo_presupuesto']);
                            $model->setAttribute('archivo', $filename);
                            $model->setAttribute('solicitante', Yii::$app->session['usuario-exito']);
                            date_default_timezone_set('America/Bogota');
                            $fecha_hora = date("Y-m-d H:i:s");
                            $model->setAttribute('created_on', $fecha_hora);
                            //validar para repetido. buscar en la tabla por detalle_maestra_id, cantidad y dependencia hace 182 dias(6 meses)
                            $stringPrecio='';
                            if($precio>0){
                                $stringPrecio=' AND precio_sugerido='.$precio;
                            }
                            $sql = "SELECT * FROM proyecto_pedido_especial WHERE created_on BETWEEN DATE_SUB('".$fecha_hora."', INTERVAL 182 DAY) AND '".$fecha_hora."' AND maestra_especial_id=".$prod." AND cantidad=".$cantidad_prod.$stringPrecio.' ORDER BY created_on LIMIT 1';
                            $repetido = ProyectoPedidoEspecial::findBySql($sql);
                            $mrepetido = $repetido->count();
                            //echo $repetido->createCommand()->getRawSql();exit();
                            if($mrepetido>0){
                                $model->setAttribute('repetido', 'SI');
                            }
                            if($model->save()){
                                $model = new ProyectoPedidoEspecial();
                            }else{
                                print_r($model->getErrors());
                            }
                        }
                    }
                }
               return $this->redirect(['view','id'=>$id]);
            } else {
                return $this->render('pedidos/create_pedido_especial', [
                    'ceco' => $ceco,
                    'id' => $id,
                ]);
            }
        }else{
            return $this->redirect(['index', 
                'mensaje' => 'El proyecto se encuentra en estado '.$presupuesto->estado,
            ]);
        }
    }

    public function actionPedidosNormales($estado=1){
        $array_post = Yii::$app->request->post();
        $page=0;$rowsPerPage=20;
        if(isset($_POST['page'])) {
            if($_POST['page']!=0){
                $page = (isset($_POST['page']) ? $_POST['page'] : 1);
                $cur_page = $page;
                $page -= 1;
                $per_page = $rowsPerPage; // Per page records
                $previous_btn = true;
                $next_btn = true;
                $first_btn = true;
                $last_btn = true;
                $start = $page * $per_page;
            }else{
                $per_page = $rowsPerPage; // Per page records
                $start = $page * $per_page;
                $cur_page = 1;
                $previous_btn = true;
                $next_btn = true;
                $first_btn = true;
                $last_btn = true;
            }
        }
        $rows = (new \yii\db\Query())
        ->select('pp.gasto_activo gasto_activo, pp.precio_neto precio_neto, pp.id id, dm.texto_breve material, pr.nombre proveedor, pp.cantidad cantidad, pp.solicitante solicitante, dm.material codigo, DATE(pp.created_on) fecha, pp.repetido repetido, pp.tipo_presupuesto tipo_presupuesto, pp.motivo_rechazo motivo_rechazo,pp.sistema')
        ->from('proyecto_pedidos pp, proyectos p, detalle_maestra dm, proyecto_estado_pedidos pe, proveedor pr, maestra_proveedor mp')
        ->where('pp.proyecto_id='.$array_post['proyecto'].' AND p.id=pp.proyecto_id AND pp.detalle_maestra_id=dm.id AND pp.estado_id=pe.id AND dm.maestra_proveedor_id=mp.id AND pr.id=mp.proveedor_id AND pp.estado_id='.$estado);
        // quite esto dm.proveedor=pr.codigo AND ** 5 de julio 2017
        if(isset($_POST['desde'])){
            if($_POST['desde']!="" && $_POST['hasta']!=""){
                $rows->andWhere("DATE(pp.created_on) between '".$_POST['desde']."' AND '".$_POST['hasta']."'");
            }
        }
        if(isset($_POST['buscar'])){
            if (trim($_POST['buscar'])!='') {
                $buscar=trim($_POST['buscar']);
                $rows->andWhere("pp.created_on like '%". $buscar."%' OR dm.texto_breve like '%".$buscar."%' OR pp.cantidad like '%".$buscar."%' OR pp.solicitante like '%".$buscar."%'");
            }
        }
        $rowsCount= clone $rows;
        $ordenado='dm.texto_breve';
        if(isset($_POST['ordenado'])){
            switch ($_POST['ordenado']) {
                case "fecha":
                    $ordenado='pp.created_on';
                    break;
                case "repetido":
                    $ordenado='pp.repetido';
                    break;
                case "producto":
                    $ordenado='dm.texto_breve';
                    break;
                case "cantidad":
                    $ordenado='pp.cantidad';
                    break;
                case "proveedor":
                    $ordenado='pr.nombre';
                    break;
                case "solicitante":
                    $ordenado='pp.solicitante';
                    break;
            }
        }
        if(isset($_POST['forma'])){
            if($_POST['forma']=='SORT_ASC'){
                $rows->orderBy([$ordenado => SORT_ASC]);
            }else{
                $rows->orderBy([$ordenado => SORT_DESC]);
            }
        }else{
            $rows->orderBy([$ordenado => SORT_DESC]);
        }
        if(!isset($_POST['excel'])){
            $rows->limit($rowsPerPage)->offset($start);
        }
        $command = $rows->createCommand();
        //echo $command->sql;exit();
        $model = $command->queryAll();
        if(isset($_POST['excel'])){
            \moonland\phpexcel\Excel::widget([
                'models' => $model,
                'mode' => 'export',
                'columns' => ['tipo_presupuesto','gasto_activo','codigo','material','proveedor','cantidad','precio_neto','solicitante','fecha','repetido','motivo_rechazo'],
                'headers' => [
                    'tipo_presupuesto' => 'TIPO PRESUPUESTO',
                    'gasto_activo' => 'GASTO/ACTIVO',
                    'codigo' => 'CODIGO',
                    'material' => 'MATERIAL',
                    'sistema'=>'Sistema',
                    'proveedor' => 'PROVEEDOR',
                    'cantidad'=>'CANTIDAD',
                    'precio_neto'=>'PRECIO NETO',
                    'solicitante'=>'SOLICITANTE',
                    'fecha'=>'FECHA',
                    'repetido'=>'REPETIDO',
                    'motivo_rechazo'=>'MOTIVO NO APROBADO'
                ], 
            ]);
        }
        $modelcount = $rowsCount->count();
        $no_of_paginations = ceil($modelcount / $per_page);
        $res='';
        if($modelcount > $rowsPerPage){
            $res.=$this->renderPartial('_paginacion_partial', array(
                'cur_page' => $cur_page,
                'no_of_paginations' => $no_of_paginations,
                'first_btn' => $first_btn,
                'previous_btn' => $previous_btn,
                'next_btn' => $next_btn,
                'last_btn' => $last_btn,
                'modelcount' => $modelcount
            ), true);
        }
       // $sistemas=SistemaProyectos::find()->all();//los pedidos seran marcados de acuerdo al sistema asignado
        $sistemas=ProyectoSistema::find()->where('id_proyecto='.$array_post['proyecto'])->all();
        if($estado==1){
            $res.= $this->renderPartial('pedidos/_normal_partial', array(
            'model' => $model,
            'estado' => $estado,
            'sistemas'=>$sistemas
                ), true);
        }else{
            $res.= $this->renderPartial('pedidos/_normal_partial_na', array(
            'model' => $model,
            'estado' => $estado,
                ), true);
        }
        
        
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'respuesta' => $res,
            'query' => $command->sql,
        ];
    }


    public function actionPedidosEspecial($estado=1){
        $array_post = Yii::$app->request->post();
        $page=0;$rowsPerPage=20;
        if(isset($_POST['page'])) {
            if($_POST['page']!=0){
                $page = (isset($_POST['page']) ? $_POST['page'] : 1);
                $cur_page = $page;
                $page -= 1;
                $per_page = $rowsPerPage; // Per page records
                $previous_btn = true;
                $next_btn = true;
                $first_btn = true;
                $last_btn = true;
                $start = $page * $per_page;
            }else{
                $per_page = $rowsPerPage; // Per page records
                $start = $page * $per_page;
                $cur_page = 1;
                $previous_btn = true;
                $next_btn = true;
                $first_btn = true;
                $last_btn = true;
            }
        }
        $rows = (new \yii\db\Query())
        ->select("pp.gasto_activo gasto_activo, (CASE WHEN pp.precio_sugerido>=0 THEN pp.precio_sugerido ELSE pp.precio_neto END) precio_neto, pp.id id, pp.archivo archivo, (CASE WHEN pp.producto_sugerido = '' THEN dm.texto_breve ELSE pp.producto_sugerido END) material, pp.cantidad cantidad, pp.solicitante solicitante, DATE(pp.created_on) fecha, pp.repetido repetido, pp.tipo_presupuesto tipo_presupuesto, pp.motivo_rechazo motivo_rechazo,dm.material AS num_material,pp.sistema")
        ->from('proyecto_pedido_especial pp, proyectos p, maestra_especial dm, proyecto_estado_pedidos pe')
        ->where('pp.proyecto_id='.$array_post['proyecto'].' AND p.id=pp.proyecto_id AND pp.maestra_especial_id=dm.id AND pp.estado_id=pe.id AND pp.estado_id='.$estado);
        if(isset($_POST['desde'])){
            if($_POST['desde']!="" && $_POST['hasta']!=""){
                $rows->andWhere("DATE(pp.created_on) between '".$_POST['desde']."' AND '".$_POST['hasta']."'");
            }
        }
        if(isset($_POST['buscar'])){
            if (trim($_POST['buscar'])!='') {
                $buscar=trim($_POST['buscar']);
                $rows->andWhere("pp.created_on like '%". $buscar."%' OR dm.texto_breve like '%".$buscar."%' OR pp.cantidad like '%".$buscar."%' OR pp.solicitante like '%".$buscar."%'");
            }
        }
        $rowsCount= clone $rows;
        $ordenado='dm.texto_breve';
        if(isset($_POST['ordenado'])){
            switch ($_POST['ordenado']) {
                case "fecha":
                    $ordenado='pp.created_on';
                    break;
                case "repetido":
                    $ordenado='pp.repetido';
                    break;
                case "producto":
                    $ordenado='dm.texto_breve';
                    break;
                case "cantidad":
                    $ordenado='pp.cantidad';
                    break;
                case "solicitante":
                    $ordenado='pp.solicitante';
                    break;
            }
        }
        if(isset($_POST['forma'])){
            if($_POST['forma']=='SORT_ASC'){
                $rows->orderBy([$ordenado => SORT_ASC]);
            }else{
                $rows->orderBy([$ordenado => SORT_DESC]);
            }
        }else{
            $rows->orderBy([$ordenado => SORT_DESC]);
        }
        if(!isset($_POST['excel'])){
            $rows->limit($rowsPerPage)->offset($start);
        }
        $command = $rows->createCommand();
        //echo $command->sql;exit();
        $model = $command->queryAll();
        if(isset($_POST['excel'])){
            \moonland\phpexcel\Excel::widget([
                'models' => $model,
                'mode' => 'export',
                'columns' => ['tipo_presupuesto','gasto_activo','material','cantidad','precio_neto','solicitante','fecha','repetido','motivo_rechazo'],
                'headers' => [
                    'tipo_presupuesto' => 'TIPO PRESUPUESTO',
                    'gasto_activo' => 'GASTO/ACTIVO',
                    'material' => 'MATERIAL',
                    'cantidad' => 'CANTIDAD',
                    'precio_neto'=>'PRECIO NETO',
                    'solicitante' => 'SOLICITANTE',
                    'fecha'=>'FECHA',
                    'repetido'=>'REPETIDO',
                    'motivo_rechazo'=>'MOTIVO NO APROBADO'
                ], 
            ]);
        }
        $modelcount = $rowsCount->count();
        $no_of_paginations = ceil($modelcount / $per_page);
        $res='';
        if($modelcount > $rowsPerPage){
            $res.=$this->renderPartial('_paginacion_partial', array(
                'cur_page' => $cur_page,
                'no_of_paginations' => $no_of_paginations,
                'first_btn' => $first_btn,
                'previous_btn' => $previous_btn,
                'next_btn' => $next_btn,
                'last_btn' => $last_btn,
                'modelcount' => $modelcount
            ), true);
        }
        //$sistemas=SistemaProyectos::find()->all();//los pedidos seran marcados de acuerdo al sistema asignado
        $sistemas=ProyectoSistema::find()->where('id_proyecto='.$array_post['proyecto'])->all();
        if($estado==1){
            $res.= $this->renderPartial('pedidos/_especial_partial', array(
            'model' => $model,
            'estado' => $estado,
            'sistemas'=>$sistemas,
            'presupuesto' => $presupuesto = Proyectos::findOne($_POST['proyecto'])
                ), true);
        }else{
            $res.= $this->renderPartial('pedidos/_especial_partial_na', array(
            'model' => $model,
            'estado' => $estado,
            'presupuesto' => $presupuesto = Proyectos::findOne($_POST['proyecto'])
                ), true);
        }
        
        
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'respuesta' => $res,
            'query' => $command->sql,
        ];
    }

    public function actionProcesarPresupuestos(){
        $array_post = Yii::$app->request->post();
        $suma_total = 0;
        $suma_seguridad = 0;
        $suma_riesgo = 0;
        $suma_heas = 0;
        $command = Yii::$app->db->createCommand("SELECT COALESCE(SUM(precio_neto*cantidad),0) FROM proyecto_pedidos WHERE proyecto_id=".$array_post['proyecto']." AND tipo_presupuesto='seguridad' AND estado_id=1"
            );
        //$query=$command->sql;
        $suma_seguridad = $command->queryScalar();

        $command = Yii::$app->db->createCommand("SELECT COALESCE(SUM(precio_neto*cantidad),0) FROM proyecto_pedidos WHERE proyecto_id=".$array_post['proyecto']." AND tipo_presupuesto='riesgo' AND estado_id=1");
        $suma_riesgo = $command->queryScalar();

        $command = Yii::$app->db->createCommand("SELECT COALESCE(SUM(precio_neto*cantidad),0) FROM proyecto_pedidos WHERE proyecto_id=".$array_post['proyecto']." AND gasto_activo='activo' AND estado_id=1");
        $suma_activo = $command->queryScalar();

        $command = Yii::$app->db->createCommand("SELECT COALESCE(SUM(precio_neto*cantidad),0) FROM proyecto_pedidos WHERE proyecto_id=".$array_post['proyecto']." AND gasto_activo='gasto' AND estado_id=1");
        $suma_gasto = $command->queryScalar();


        /*****************************/
        $command = Yii::$app->db->createCommand("SELECT COALESCE(SUM(CASE WHEN precio_neto>=0 THEN precio_neto*cantidad ELSE precio_sugerido*cantidad END),0) FROM proyecto_pedido_especial WHERE proyecto_id=".$array_post['proyecto']." AND tipo_presupuesto='seguridad' AND estado_id=1"
            );
        $suma_seguridad = $suma_seguridad+$command->queryScalar();

        $command = Yii::$app->db->createCommand("SELECT COALESCE(SUM(CASE WHEN precio_neto>=0 THEN precio_neto*cantidad ELSE precio_sugerido*cantidad END),0) FROM proyecto_pedido_especial WHERE proyecto_id=".$array_post['proyecto']." AND tipo_presupuesto='riesgo' AND estado_id=1");
        $suma_riesgo = $suma_riesgo+$command->queryScalar();

        $command = Yii::$app->db->createCommand("SELECT COALESCE(SUM(CASE WHEN precio_neto>=0 THEN precio_neto*cantidad ELSE precio_sugerido*cantidad END),0) FROM proyecto_pedido_especial WHERE proyecto_id=".$array_post['proyecto']." AND gasto_activo='activo' AND estado_id=1");
        $suma_activo = $suma_activo+$command->queryScalar();

        $command = Yii::$app->db->createCommand("SELECT COALESCE(SUM(CASE WHEN precio_neto>=0 THEN precio_neto*cantidad ELSE precio_sugerido*cantidad END),0) FROM proyecto_pedido_especial WHERE proyecto_id=".$array_post['proyecto']." AND gasto_activo='gasto' AND estado_id=1");
        $suma_gasto = $suma_gasto+$command->queryScalar();

        $suma_total = $suma_seguridad+$suma_riesgo;

        $command = Yii::$app->db->createCommand(
            "UPDATE proyectos SET ".
            "suma_total=".$suma_total.", ".
            "suma_seguridad=".$suma_seguridad.", ".
            "suma_riesgo=".$suma_riesgo.", ".
            "suma_activo=".$suma_activo.", ".
            "suma_gasto=".$suma_gasto.
            " WHERE id=".$array_post['proyecto']);
        $command->execute();
        
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'suma_total' => $suma_total,
            'suma_seguridad' => $suma_seguridad,
            'suma_riesgo' => $suma_riesgo,
            'suma_activo' => $suma_activo,
            'suma_gasto' => $suma_gasto,
        ];
    }


    public function actionCambiarEstado(){
        if ($_POST['tipo']=='normal') {
            $model = ProyectoPedidos::findOne($_POST['producto']);
            if($model->cantidad==$_POST['cantidad']){
                $model->setAttribute('estado_id', $_POST['estado']);
                if(isset($_POST['motivo'])){
                    $model->setAttribute('motivo_rechazo', $_POST['motivo']);
                }else{
                    $model->setAttribute('motivo_rechazo', '');
                }
                if($model->save()){
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return [
                        'respuesta' => true,
                    ];
                }else{
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return [
                        'respuesta' => print_r($model->getErrors()),
                    ];
                }
            }else{
                $model_NA = new ProyectoPedidos();
                $model_NA->setAttribute('detalle_maestra_id', $model->detalle_maestra_id);
                $model_NA->setAttribute('proyecto_id', $model->proyecto_id);
                $model_NA->setAttribute('estado_id', $_POST['estado']);
                $model_NA->setAttribute('cantidad', $_POST['cantidad']);
                $model_NA->setAttribute('precio_neto', $model->precio_neto);
                $model_NA->setAttribute('observaciones', $model->observaciones);
                $model_NA->setAttribute('observacion_coordinador', $model->observacion_coordinador);
                $model_NA->setAttribute('motivo_rechazo', $_POST['motivo']);
                $model_NA->setAttribute('cebe', $model->cebe);
                $model_NA->setAttribute('orden_interna_gasto', $model->orden_interna_gasto);
                $model_NA->setAttribute('orden_interna_activo', $model->orden_interna_activo);
                $model_NA->setAttribute('tipo_presupuesto', $model->tipo_presupuesto);
                $model_NA->setAttribute('fecha_revision_coordinador', $model->fecha_revision_coordinador);
                $model_NA->setAttribute('created_on', $model->created_on);
                $model_NA->setAttribute('repetido', $model->repetido);
                $model_NA->setAttribute('solicitante', $model->solicitante);
                $model_NA->setAttribute('gasto_activo', $model->gasto_activo);

                $model->setAttribute('cantidad', ($model->cantidad-$_POST['cantidad']));
                if($model->save()){
                    $model_NA->save();
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return [
                        'respuesta' => true,
                    ];
                }else{
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return [
                        'respuesta' => print_r($model->getErrors()),
                    ];
                }
            }
        }else if ($_POST['tipo']=='especial') {
            $model = ProyectoPedidoEspecial::findOne($_POST['producto']);
            if($model->cantidad==$_POST['cantidad']){
                $model->setAttribute('estado_id', $_POST['estado']);
                if(isset($_POST['motivo'])){
                    $model->setAttribute('motivo_rechazo', $_POST['motivo']);
                }else{
                    $model->setAttribute('motivo_rechazo', '');
                }
                if($model->save()){
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return [
                        'respuesta' => true,
                    ];
                }else{
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return [
                        'respuesta' => print_r($model->getErrors()),
                    ];
                }
            }else{
                $model_NA = new ProyectoPedidoEspecial();
                $model_NA->setAttribute('producto_sugerido', $model->producto_sugerido);
                $model_NA->setAttribute('cantidad', $_POST['cantidad']);
                $model_NA->setAttribute('proveedor_sugerido', $model->proveedor_sugerido);
                $model_NA->setAttribute('precio_sugerido', $model->precio_sugerido);
                $model_NA->setAttribute('precio_neto', $model->precio_neto);
                $model_NA->setAttribute('observaciones', $model->observaciones);
                $model_NA->setAttribute('archivo', $model->archivo);
                $model_NA->setAttribute('maestra_especial_id', $model->maestra_especial_id);
                $model_NA->setAttribute('proyecto_id', $model->proyecto_id);
                $model_NA->setAttribute('estado_id', $_POST['estado']);
                $model_NA->setAttribute('cebe', $model->cebe);
                $model_NA->setAttribute('observacion_coordinador', $model->observacion_coordinador);
                $model_NA->setAttribute('motivo_rechazo', $_POST['motivo']);
                $model_NA->setAttribute('orden_interna_gasto', $model->orden_interna_gasto);
                $model_NA->setAttribute('orden_interna_activo', $model->orden_interna_activo);
                $model_NA->setAttribute('tipo_presupuesto', $model->tipo_presupuesto);
                $model_NA->setAttribute('fecha_revision_coordinador', $model->fecha_revision_coordinador);
                $model_NA->setAttribute('created_on', $model->created_on);
                $model_NA->setAttribute('repetido', $model->repetido);
                $model_NA->setAttribute('solicitante', $model->solicitante);
                $model_NA->setAttribute('gasto_activo', $model->gasto_activo);

                $model->setAttribute('cantidad', ($model->cantidad-$_POST['cantidad']));
                if($model->save()){
                    $model_NA->save();
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return [
                        'respuesta' => true,
                    ];
                }else{
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return [
                        'respuesta' => print_r($model->getErrors()),
                    ];
                }
            }
        }
    }


    public function actionGastoActivo(){
        if ($_POST['tipo']=='normal') {
            $model = ProyectoPedidos::findOne($_POST['producto']);
            $model->setAttribute('gasto_activo', $_POST['estado']);
            if($model->save()){
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'respuesta' => true,
                ];
            }else{
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'respuesta' => print_r($model->getErrors()),
                ];
            }
        }else if ($_POST['tipo']=='especial') {
            $model = ProyectoPedidoEspecial::findOne($_POST['producto']);
            $model->setAttribute('gasto_activo', $_POST['estado']);
            if($model->save()){
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'respuesta' => true,
                ];
            }else{
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'respuesta' => print_r($model->getErrors()),
                ];
            }
        }
    }

    public function actionGastoActivoMultiple(){
        $count=0;
        if ($_POST['tipo']=='normal') {
            if(trim($_POST['productos_id'])!=''){
                $array = explode(",",$_POST['productos_id']);
                if(count($array)>0){
                    foreach ($array as $valor) {
                        $model = ProyectoPedidos::findOne($valor);
                        $model->setAttribute('gasto_activo', $_POST['estado']);
                        if($model->save()){
                            $count++;
                        }
                    }
                }
            }
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'respuesta' => $count,
            ];
        }else if ($_POST['tipo']=='especial') {
            if(trim($_POST['productos_id'])!=''){
               $array = explode(",",$_POST['productos_id']);
                if(count($array)>0){
                    foreach ($array as $valor) {
                        $model = ProyectoPedidoEspecial::findOne($valor);
                        $model->setAttribute('gasto_activo', $_POST['estado']);
                        if($model->save()){
                            $count++;
                        }
                    }
                } 
            }
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'respuesta' => $count,
            ];
        }
    }

    public function actionSubirCotizacion(){
        $array_post = Yii::$app->request->post();
        $file = UploadedFile::getInstanceByName('file');
        $filename='';$respuesta;
        if ($file !== null) {
            $shortPath = 'uploads/presupuestos/';
            $ext            = end((explode(".", $file->name)));
            $name           = date('Ymd') . rand(1, 10000) . '' . $file->name;
            $path           = Yii::$app->basePath . '/web/uploads/presupuestos/' . $name;
            $filename = $shortPath . $name;
            $respuesta=$file->saveAs($path);
            $model = $this->findModel($array_post['presupuesto']);
            $model->setAttribute('archivo', $filename);
            $model->save();
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'respuesta' => $respuesta,
        ];
    }

    public function actionFinalizar($id){
        $model=$this->findModel($id);
        $form=$_POST['form'];

        switch ($form) {
            case 'sala':
                $model->setAttribute('sala_control', true);
                if($_POST['check-sala']==1){
                    $model->setAttribute('na_sala', true);
                }else{

                    $model->setAttribute('fecha_sala_control', $_POST['fecha_sala']);
                   if($_FILES['file_sala']['name']!=''){
                       Yii::$app->params['uploadPath'] = Yii::$app->basePath . '/web/uploads/proyecto_finalizar/sala_control/';
                       $shortPath = '/uploads/proyecto_finalizar/sala_control/';
                       $name=$id."-".$_FILES['file_sala']['name'];
                       $path    = Yii::$app->params['uploadPath'] . $name;
                       $model->setAttribute('adjunto_sala_control', $shortPath.$name);
                       move_uploaded_file($_FILES['file_sala']['tmp_name'], $path);
                   }

                }

                if($model->save()){
                    echo "todo esta bien";
                }else{

                    echo "Hubo un error";
                }

            break;

            case 'acta':
                $model->setAttribute('acta_entrega', true);
                if($_POST['check-acta']==1){
                    $model->setAttribute('na_acta', true);
                }else{
                    $model->setAttribute('fecha_acta_entrega', $_POST['fecha_acta']);
                   if($_FILES['file_acta']['name']!=''){
                       Yii::$app->params['uploadPath'] = Yii::$app->basePath . '/web/uploads/proyecto_finalizar/acta_entrega/';
                       $shortPath = '/uploads/proyecto_finalizar/acta_entrega/';
                       $name=$id."-".$_FILES['file_acta']['name'];
                       $path    = Yii::$app->params['uploadPath'] . $name;
                       $model->setAttribute('adjunto_acta_entrega', $shortPath.$name);
                       move_uploaded_file($_FILES['file_acta']['tmp_name'], $path);
                   }
                }

                if($model->save()){
                    echo "todo esta bien";
                }else{

                    echo "Hubo un error";
                }

            break;

            case 'factura':
                $model->setAttribute('facturacion', true);
                $model->setAttribute('estado_proyecto', "F");
                $model->setAttribute('dias_seguidos', $_POST['dias_seguidos']);
                if($_POST['check-facturacion']==1){
                    $model->setAttribute('na_factura', true);
                }else{
                    $model->setAttribute('recibe_factura', $_POST['recibio_factura']);
                    $model->setAttribute('fecha_entrega', $_POST['fecha_factura']);
                    if($_FILES['file_factura']['name']!=''){
                        echo "<pre>";
                        print_r($_FILES['file_factura']);
                        echo "</pre>";
                       Yii::$app->params['uploadPath'] = Yii::$app->basePath . '/web/uploads/proyecto_finalizar/factura/';
                       $shortPath = '/uploads/proyecto_finalizar/factura/';
                       $name=$id."-".$_FILES['file_factura']['name'];
                       $path    = Yii::$app->params['uploadPath'] . $name;
                       $model->setAttribute('adjunto_factura', $shortPath.$name);
                       move_uploaded_file($_FILES['file_factura']['tmp_name'], $path);
                   }
                }

                if($model->save()){

                    echo "todo esta bien";
                }else{

                    echo "Hubo un error";
                }
            break;
          
        }

       /*if(isset($_POST['finalizar'])){
            $model->setAttribute('estado_proyecto', "F");
            $model->setAttribute('dias_seguidos', $_POST['dias_seguidos']);
            $model->save();
       }elseif(isset($_POST['abrir'])){
            $model->setAttribute('estado_proyecto', "A");
            $model->save();
       }else{
           if($_POST['check-sala']==1){
               $sala_control=$_POST['check-sala']==1?true:false;
               $model->setAttribute('sala_control', $sala_control);
               $model->setAttribute('fecha_sala_control', $_POST['fecha_sala']);
               if(isset($_FILES['file_sala'])){
                   Yii::$app->params['uploadPath'] = Yii::$app->basePath . '/web/uploads/proyecto_finalizar/sala_control/';
                   $shortPath = '/uploads/proyecto_finalizar/sala_control/';
                   $name=$id."-".$_FILES['file_sala']['name'];
                   $path    = Yii::$app->params['uploadPath'] . $name;
                   $model->setAttribute('adjunto_sala_control', $shortPath.$name);
                   move_uploaded_file($_FILES['file_sala']['tmp_name'], $path);
               }
               $model->save();

            }

            if($_POST['check-acta']==1){
               $sala_control=$_POST['check-acta']==1?true:false;
               $model->setAttribute('acta_entrega', $sala_control);
               $model->setAttribute('fecha_acta_entrega', $_POST['fecha_acta']);
               if(isset($_FILES['file_acta'])){
                   Yii::$app->params['uploadPath'] = Yii::$app->basePath . '/web/uploads/proyecto_finalizar/acta_entrega/';
                   $shortPath = '/uploads/proyecto_finalizar/acta_entrega/';
                   $name=$id."-".$_FILES['file_acta']['name'];
                   $path    = Yii::$app->params['uploadPath'] . $name;
                   $model->setAttribute('adjunto_acta_entrega', $shortPath.$name);
                   move_uploaded_file($_FILES['file_acta']['tmp_name'], $path);
               }
               $model->save();

            }
        }*/

          
        return $this->redirect(['view', 
            'id' => $id
        ]);

    }

    public function actionAgregarcronograma($id){
        $model=new CronogramaProyecto;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->setAttribute('id_proyecto', $id);
            $model->setAttribute('color', $_POST['color_evento']);
            $model->setAttribute('usuario', Yii::$app->session['usuario-exito']);
            $model->save();
            return $this->redirect(['view','id'=>$id]);
        }
    }

    public function actionEditarcronograma($id,$id_proyecto){
        $model=CronogramaProyecto::findOne($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //$model->setAttribute('id_proyecto', $id);
            $model->save();
            return $this->redirect(['view','id'=>$id_proyecto]);
        }else{
            $usuarios=Usuario::find()->where('estado="A"')->all();
            $list_usuarios=ArrayHelper::map($usuarios, 'usuario', 'usuario');
            return $this->render('_form_cronograma',['model'=>$model,'id'=>$id_proyecto,'list_usuarios'=>$list_usuarios]);
        }
    }

    public function actionDeletecronograma($id,$id_proyecto){
        $model=CronogramaProyecto::findOne($id);

        if($model->delete())
            return $this->redirect(['view','id'=>$id_proyecto]);
    }

    public function actionInfoCronograma(){
        $id=$_POST['id'];
        $model=CronogramaProyecto::findOne($id);
        $res= $this->renderPartial('_info_crono', array('model'=>$model), true);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'respuesta' => $res
        ];
    }

    public function actionBloquearCronograma($id,$accion){
        $model=$this->findModel($id);
              
        $model->setAttribute('estado_cronograma', $accion);
        if($model->save()){
            return $this->redirect(['view','id'=>$id]);
        }else{

            print_r($model->getErrors());
        }
        
    }

    public function actionAsignarSistematodos(){
        $count=0;
        if ($_POST['tipo']=='normal') {
            if(trim($_POST['productos_id'])!=''){
                $array = explode(",",$_POST['productos_id']);
                if(count($array)>0){
                    foreach ($array as $valor) {
                        $model = ProyectoPedidos::findOne($valor);
                        $model->setAttribute('sistema', $_POST['sistema']);
                        if($model->save()){
                            $count++;
                        }
                    }
                }
            }
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'respuesta' => $count,
            ];
        }else if ($_POST['tipo']=='especial') {
            if(trim($_POST['productos_id'])!=''){
               $array = explode(",",$_POST['productos_id']);
                if(count($array)>0){
                    foreach ($array as $valor) {
                        $model = ProyectoPedidoEspecial::findOne($valor);
                        $model->setAttribute('sistema', $_POST['sistema']);
                        if($model->save()){
                            $count++;
                        }
                    }
                } 
            }
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'respuesta' => $count,
            ];
        }
    }

    public function actionAsignarSistemaPedido(){

        if ($_POST['tipo']=='normal') {
            $model = ProyectoPedidos::findOne($_POST['producto']);
            $model->setAttribute('sistema', $_POST['sistema']);
            if($model->save()){
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'respuesta' => true,
                ];
            }else{
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'respuesta' => print_r($model->getErrors()),
                ];
            }
        }else if ($_POST['tipo']=='especial') {
            $model = ProyectoPedidoEspecial::findOne($_POST['producto']);
            $model->setAttribute('sistema', $_POST['sistema']);
            if($model->save()){
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'respuesta' => true,
                ];
            }else{
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'respuesta' => print_r($model->getErrors()),
                ];
            }
        }

    }
}
