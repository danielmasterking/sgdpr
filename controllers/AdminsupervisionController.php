<?php

namespace app\controllers;

use Yii;
use app\models\AdminSupervision;
use app\models\AdminSupervisionSearch;
use app\models\CentroCosto;
use app\models\Usuario;
use app\models\AdminDispositivo;
use app\models\AdminDependencia;
use app\models\Jornada;
use app\models\DispositivoAdmin;
use app\models\DetalleDispAdmin;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use kartik\mpdf\Pdf;
use yii\filters\AccessControl;
use app\models\Empresa;

/**
 * AdminsupervisionController implements the CRUD actions for AdminSupervision model.
 */
class AdminsupervisionController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {

        return [

            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['index', 'view','create','createdispositivo'],
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['index', 'view','create','createdispositivo'],
                        'roles'   => ['@'], //para usuarios logueados
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
     * Lists all AdminSupervision models.
     * @return mixed
     */
    public function actionIndex()
    {
        $empresas=Empresa::find()->all();
        $list_empresas=ArrayHelper::map($empresas,'nit','nombre');
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
        }else{
            $per_page = $rowsPerPage; // Per page records
            $start = $page * $per_page;
            $cur_page = 1;
            $previous_btn = true;
            $next_btn = true;
            $first_btn = true;
            $last_btn = true;
        }

        $permisos = array();
        if( isset(Yii::$app->session['permisos-exito']) ){
            $permisos = Yii::$app->session['permisos-exito'];
        }

        if(in_array("administrador", $permisos) or in_array("prefactura-analista", $permisos)){
           $rows = (new \yii\db\Query())
            ->select(['id','mes','ano','usuario','created','estado','numero_factura','fecha_factura','empresa.nombre as empresa',
                '(SELECT SUM((ftes*cantidad)) FROM admin_dispositivo WHERE id_admin=admin_supervision.id) ftes_totales',
                '(SELECT SUM(precio_total) FROM admin_dispositivo WHERE id_admin=admin_supervision.id) total_factura'])
            ->from('admin_supervision')
            ->innerJoin('empresa', 'admin_supervision.empresa=empresa.nit')
            ->where('1=1 ');
        }else{

            $rows = (new \yii\db\Query())
            ->select(['id','mes','ano','usuario','created','estado','numero_factura','fecha_factura','empresa.nombre as empresa',
                '(SELECT SUM((ftes*cantidad)) FROM admin_dispositivo WHERE id_admin=admin_supervision.id) ftes_totales',
                '(SELECT SUM(precio_total) FROM admin_dispositivo WHERE id_admin=admin_supervision.id) total_factura'])
            ->from('admin_supervision')
            ->innerJoin('empresa', 'admin_supervision.empresa=empresa.nit')
            ->where('admin_supervision.usuario="'.Yii::$app->session['usuario-exito'].'" ');
        }

        if(isset($_POST['desde'])){
            if($_POST['desde']!="" && $_POST['hasta']!=""){
                $rows->andWhere("DATE(created) between '".$_POST['desde']."' AND '".$_POST['hasta']."'");
            }
        }

        if(isset($_POST['buscar'])){
            if (trim($_POST['buscar'])!='') {
                $buscar=trim($_POST['buscar']);
                
                $rows->andWhere("mes like '%". $buscar."%' OR ano like '%".$buscar."%' OR  usuario like '%".$buscar."%' OR numero_factura='".$buscar."'");
                
            }
        }



        if (trim($_POST['mes'])!='') {
            $rows->andWhere("mes like '%".$_POST['mes']."%'");
        }

        if (trim($_POST['empresa'])!='') {
            $rows->andWhere("admin_supervision.empresa like '%".$_POST['empresa']."%'");
        }

        $rowsCount= clone $rows;

        $ordenado='id';
        if(isset($_POST['ordenado'])){
            switch ($_POST['ordenado']) {
                case "mes":
                    $ordenado='mes';
                    break;
                case "ano":
                    $ordenado='ano';
                    break;
                
                case "fecha":
                    $ordenado='created';
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
        $query = $command->queryAll();
        if(isset($_POST['excel'])){
            \moonland\phpexcel\Excel::widget([
                'models' => $query,
                'mode' => 'export',
                'fileName' => 'Administracion y supervision', 
                'columns' => ['mes','ano','usuario','created','numero_factura','fecha_factura','ftes_totales','total_factura','empresa'],
                'headers' => [
                    'mes' => 'MES',
                    'ano' => 'AÑO',
                    'usuario'=>'Usuario',
                    'created'=>'Fecha creado',
                    'ftes_totales'=>'Ftes',
                    'total_factura'=>'Total',
                    'numero_factura'=>'Numero Factura',
                    'fecha_factura'=>'Fecha Factura',
                    'empresa'=>'Empresa'
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
                'modelcount' => $modelcount,
                
            ), true);
        }


       $res.= $this->renderPartial('_partial', array(
            'query' => $query,
            'modelcount' => $modelcount,
            
                ), true);
        if(isset($_POST['page'])){
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'respuesta' => $res,
                'query' => $command->sql,
            ];
        }else{
            return $this->render('index',
                [
                    'partial' => $res, 
                    'list_empresas'=>$list_empresas
                ]);
        }
    }

    /**
     * Displays a single AdminSupervision model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        //$this->layout = 'main_sin_menu';

        $model_dep=new AdminDependencia();

        $count_dep=$model_dep->find()->where('id_admin='.$id)->count();
        $admin_dep=$model_dep->find()->where('id_admin='.$id)->all();

        $admin_disp=AdminDispositivo::find()->where('id_admin='.$id)->orderby('id ASC')->all();

        if (isset($_POST['fecha_factura'])) {
            $model =AdminSupervision::find()->where('id='.$id)->one();
            $model->setAttribute('numero_factura', $_POST['num_factura']);
            $model->setAttribute('fecha_factura', $_POST['fecha_factura']);

            $model->save();

            return $this->redirect(['view', 'id' => $id ]);
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
            'admin_dep'=>$admin_dep,
            'modeldep'=>$model_dep,
            'admin_disp'=>$admin_disp,
            'count_dep'=>$count_dep,
            'id'=>$id
        ]);
    }

    /**
     * Creates a new AdminSupervision model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        //$this->layout = 'main_sin_menu';
        date_default_timezone_set ( 'America/Bogota');
        $model = new AdminSupervision();
        $dependencias = CentroCosto::find()->where(['not in', 'estado', ['C']])->orderBy(['nombre' => SORT_ASC])->all();
        $usuario = Usuario::findOne(Yii::$app->session['usuario-exito']);
        $zonasUsuario = array();
        $marcasUsuario = array();
        $distritosUsuario = array();
        $empresasUsuario = array();

        if($usuario != null){
          
          $zonasUsuario = $usuario->zonas;
          $marcasUsuario = $usuario->marcas;
          $distritosUsuario = $usuario->distritos;            
          $empresasUsuario = $usuario->empresas;
        }

        $in=" IN(";

        foreach ($empresasUsuario as $key => $value) {
             $in.=" '".$value->empresa->nit."',";    
        }
        $in_final = substr($in, 0, -1).")";

        $dispositivos_admin=DispositivoAdmin::find()->where(' nit_empresa '.$in_final.' ')->all();

        $list_disp=ArrayHelper::map($dispositivos_admin,'id','nombre');

        if ($model->load(Yii::$app->request->post())) {

            $date_time=date('Y-m-d H:i:s');
            $array_post = Yii::$app->request->post();
            
            if ($_POST['dispositivo']!='') {
               
               $disp=DispositivoAdmin::find()->where('id='.$_POST['dispositivo'])->one();
            }
            $model->setAttribute('created', $date_time);
            $model->setAttribute('usuario', Yii::$app->session['usuario-exito']);
            //$model->setAttribute('precio',$_POST['precio_total']);
            //$model->setAttribute('precio_unitario',$_POST['precio_uni']);
            if ($_POST['dispositivo']!='') {
                $model->setAttribute('empresa',$disp->nit_empresa);
            }else{
                $model->setAttribute('empresa',$_POST['AdminSupervision']['empresa']);
            }
           //$model->setAttribute('descripcion',$_POST['AdminSupervision']['descripcion']);
            $model->setAttribute('fecha_desde',$_POST['AdminSupervision']['fecha_desde']);
            $model->setAttribute('fecha_hasta',$_POST['AdminSupervision']['fecha_hasta']);
            $model->setAttribute('dias',$_POST['AdminSupervision']['dias']);
            //$model->setAttribute('cantidad',$_POST['AdminSupervision']['cantidad']);
            //$model->setAttribute('horas',$_POST['AdminSupervision']['horas']);
           // $model->setAttribute('ftes',$_POST['AdminSupervision']['ftes']);
            
            
            if ($_POST['dispositivo']!='') {
                $model->save();
                $detalle_disp=DetalleDispAdmin::find()->where('id_disp_admin='.$_POST['dispositivo'])->all();
                foreach ($detalle_disp as $key => $value) {
                    if($value->dependencia->estado!='C'){
                        $model_dep=new AdminDependencia();
                        $model_dep->setAttribute('centro_costos_codigo', $value->cod_dependencia);
                        //$model_dep->setAttribute('precio', $_POST['precio_dep']);
                        $model_dep->setAttribute('id_admin', $model->id);
                        //$model_dep->setAttribute('horas',$horas_dep);
                        //$model_dep->setAttribute('ftes', $_POST['ftes_dep']);
                        $model_dep->save();
                    }
                }

            }else{


                $dependencias=$_POST['dependencias'];
                if(count($dependencias)>0){

                    $model->save();
                    // $cantidad=count($dependencias);

                    // $horas_dep=($_POST['AdminSupervision']['horas']/$cantidad);

                    // $horas_dep=round($horas_dep,1);
                    foreach ($dependencias as $value) {
                        $model_dep=new AdminDependencia();
                        $model_dep->setAttribute('centro_costos_codigo', $value);
                        //$model_dep->setAttribute('precio', $_POST['precio_dep']);
                        $model_dep->setAttribute('id_admin', $model->id);
                        //$model_dep->setAttribute('horas',$horas_dep);
                        //$model_dep->setAttribute('ftes', $_POST['ftes_dep']);
                        $model_dep->save();


                    }


                }else{
                     Yii::$app->session->setFlash('danger','Error debe escoger una dependencia');
                    return $this->redirect(['create']);
                }
            }


            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'dependencias' => $dependencias,
                'zonasUsuario' => $zonasUsuario,
                'marcasUsuario' => $marcasUsuario,
                'distritosUsuario' => $distritosUsuario,
                'empresasUsuario'=>$empresasUsuario,
                'list_disp'=>$list_disp
                
            ]);
        }
    }

    public function actionCreatedispositivo($id){
        //$this->layout = 'main_sin_menu';
        
        $model=new AdminDispositivo();

        $num_dep=AdminDependencia::find()->where('id_admin='.$id)->count();

        $jornada  = Jornada::find()->all();

        $array_post = Yii::$app->request->post();

        if ($model->load($array_post)) {

            $lunes = $array_post['AdminDispositivo']['lunes'];
            $martes = $array_post['AdminDispositivo']['martes'];
            $miercoles = $array_post['AdminDispositivo']['miercoles'];
            $jueves = $array_post['AdminDispositivo']['jueves'];
            $viernes = $array_post['AdminDispositivo']['viernes'];
            $sabado = $array_post['AdminDispositivo']['sabado'];
            $domingo = $array_post['AdminDispositivo']['domingo'];
            $festivo = $array_post['AdminDispositivo']['festivo'];

            if ($lunes == '1') {
                $model->setAttribute('lunes', 'X');
            }else{

                $model->setAttribute('lunes', 'O');
            }

            if ($martes == '1') {

                $model->setAttribute('martes', 'X');

            }else{

                $model->setAttribute('martes', 'O');
            }

            if ($miercoles == '1') {
                $model->setAttribute('miercoles', 'X');
            }else{

                $model->setAttribute('miercoles', 'O');
            }

            if ($jueves == '1') {
                $model->setAttribute('jueves', 'X');
            }else{
                $model->setAttribute('jueves', 'O');
            }


            if ($viernes == '1') {
                $model->setAttribute('viernes', 'X');
            }else{

                $model->setAttribute('viernes', 'O');
            }

            if ($sabado == '1') {
                $model->setAttribute('sabado', 'X');
            }else{

                $model->setAttribute('sabado', 'O');
            }

            if ($domingo == '1') {
                $model->setAttribute('domingo', 'X');
            }else{

                $model->setAttribute('domingo', 'O');
            }

            if ($festivo == '1') {
                $model->setAttribute('festivo', 'X');
            }else{
                $model->setAttribute('festivo', 'O');
            }
 
            $model->setAttribute('precio_unitario', $array_post['precio_uni']);
            $model->setAttribute('precio_total', $array_post['precio_total']);
            $model->setAttribute('precio_dependencia', $array_post['precio_dep']);
            $model->setAttribute('id_admin', $id);
            //$model->setAttribute('hora_fin',$array_post['hora_fin2']);
            $model->setAttribute('dias',$array_post['dias_prestados2']);
            //$model->setAttribute('ftes_diurno',$array_post['ftes_diurno']);
            //$model->setAttribute('ftes_nocturno',$array_post['ftes_nocturno']);


            if($array_post['optradio']=='N'){

                $model->setAttribute('horas','00:00');
                $model->setAttribute('hora_inicio','00:00');
                $model->setAttribute('hora_fin','00:00');
                $model->setAttribute('ftes','0');    
                $model->setAttribute('ftes_dependencia','0');
                $model->setAttribute('ftes_diurno','0');
                $model->setAttribute('ftes_nocturno','0');
                $model->setAttribute('ftes_diurno_dep','0');
                $model->setAttribute('ftes_nocturno_dep','0');
                
            }else{
                $model->setAttribute('hora_fin',$array_post['hora_fin2']);
                $model->setAttribute('ftes_diurno',$array_post['ftes_diurno']);
                $model->setAttribute('ftes_nocturno',$array_post['ftes_nocturno']);
                $model->setAttribute('ftes_diurno_dep',$array_post['AdminDispositivo']['ftes_diurno_dep']);
                $model->setAttribute('ftes_nocturno_dep',$array_post['AdminDispositivo']['ftes_nocturno_dep']);
            }

            if($model->save()){

                return $this->redirect(['view', 'id' => $id]);

            }
        }


        return $this->render('create_dispositivo', [
            'model' => $model,
            'num_dep'=>$num_dep,
            'id'=>$id,
            'jornada'=>$jornada
        ]);

    }


    public function actionUpdateServicio($id,$view){
        //$this->layout = 'main_sin_menu';
         $model=AdminDispositivo::findOne($id);

        $num_dep=AdminDependencia::find()->where('id_admin='.$view)->count();

        $jornada  = Jornada::find()->all();

        $array_post = Yii::$app->request->post();

        if ($model->load($array_post)) {

            $lunes = $array_post['lunes'];
            $martes = $array_post['martes'];
            $miercoles = $array_post['miercoles'];
            $jueves = $array_post['jueves'];
            $viernes = $array_post['viernes'];
            $sabado = $array_post['sabado'];
            $domingo = $array_post['domingo'];
            $festivo = $array_post['festivo'];

            if ($lunes == '1') {
                $model->setAttribute('lunes', 'X');
            }else{

                $model->setAttribute('lunes', 'O');
            }

            if ($martes == '1') {

                $model->setAttribute('martes', 'X');

            }else{

                $model->setAttribute('martes', 'O');
            }

            if ($miercoles == '1') {
                $model->setAttribute('miercoles', 'X');
            }else{

                $model->setAttribute('miercoles', 'O');
            }

            if ($jueves == '1') {
                $model->setAttribute('jueves', 'X');
            }else{
                $model->setAttribute('jueves', 'O');
            }


            if ($viernes == '1') {
                $model->setAttribute('viernes', 'X');
            }else{

                $model->setAttribute('viernes', 'O');
            }

            if ($sabado == '1') {
                $model->setAttribute('sabado', 'X');
            }else{

                $model->setAttribute('sabado', 'O');
            }

            if ($domingo == '1') {
                $model->setAttribute('domingo', 'X');
            }else{

                $model->setAttribute('domingo', 'O');
            }

            if ($festivo == '1') {
                $model->setAttribute('festivo', 'X');
            }else{
                $model->setAttribute('festivo', 'O');
            }
 
            $model->setAttribute('precio_unitario', $array_post['precio_uni']);
            $model->setAttribute('precio_total', $array_post['precio_total']);
            $model->setAttribute('precio_dependencia', $array_post['precio_dep']);
            //$model->setAttribute('id_admin', $id);
            //$model->setAttribute('hora_fin',$array_post['hora_fin2']);
            $model->setAttribute('dias',$array_post['dias_prestados2']);
            //$model->setAttribute('ftes_diurno',$array_post['ftes_diurno']);
            //$model->setAttribute('ftes_nocturno',$array_post['ftes_nocturno']);
            
            if($array_post['optradio']=='N'){

                $model->setAttribute('horas','00:00');
                $model->setAttribute('hora_inicio','00:00');
                $model->setAttribute('hora_fin','00:00');
                $model->setAttribute('ftes','0');
                $model->setAttribute('ftes_dependencia','0');
                 $model->setAttribute('ftes_diurno','0');
                $model->setAttribute('ftes_nocturno','0');
                 $model->setAttribute('ftes_diurno_dep','0');
                $model->setAttribute('ftes_nocturno_dep','0');

            }else{
                $model->setAttribute('hora_fin',$array_post['hora_fin2']);
                $model->setAttribute('ftes_diurno',$array_post['ftes_diurno']);
                $model->setAttribute('ftes_nocturno',$array_post['ftes_nocturno']);
                $model->setAttribute('ftes_diurno_dep',$array_post['AdminDispositivo']['ftes_diurno_dep']);
                $model->setAttribute('ftes_nocturno_dep',$array_post['AdminDispositivo']['ftes_nocturno_dep']);
            }

            if($model->save()){

                return $this->redirect(['view', 'id' => $view]);

            }
        }


        return $this->render('update_servicio', [
            'model' => $model,
            'num_dep'=>$num_dep,
            'id'=>$id,
            'view'=>$view,
            'jornada'=>$jornada
        ]);

    }

    /**
     * Updates an existing AdminSupervision model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
   public function actionUpdate($id)
    {
       // $this->layout = 'main_sin_menu';
        date_default_timezone_set ( 'America/Bogota');
        $model = $this->findModel($id);
        $model_dep= AdminDependencia::find()->where('id_admin='.$id.' limit 1')->one();
        $dependencias = CentroCosto::find()->/*where(['not in', 'estado', ['C']])->*/orderBy(['nombre' => SORT_ASC])->all();
        $usuario = Usuario::findOne(Yii::$app->session['usuario-exito']);
        $zonasUsuario = array();
        $marcasUsuario = array();
        $distritosUsuario = array();
        $empresasUsuario = array();

        if($usuario != null){
          
          $zonasUsuario = $usuario->zonas;
          $marcasUsuario = $usuario->marcas;
          $distritosUsuario = $usuario->distritos;            
          $empresasUsuario = $usuario->empresas;
        }


        if ($model->load(Yii::$app->request->post()) /*&& $model->save()*/) {

            $array_post = Yii::$app->request->post();
            //$model->setAttribute('created', $date_time);
                      
            $model->setAttribute('empresa',$_POST['AdminSupervision']['empresa']);
            
            
            $model->save();

            
            $dependencias=$_POST['dependencias'];
            $cantidad=count($dependencias);


            $dispositivos=AdminDispositivo::find()->where('id_admin='.$id)->all();
            
            foreach ($dispositivos as $row) {
                
                $precio_dep=($row->precio_total/$cantidad);
                $precio_dep_final=round($precio_dep, 2, PHP_ROUND_HALF_DOWN);

                $model_disp=AdminDispositivo::findOne($row->id);
                $model_disp->setAttribute('precio_dependencia',$precio_dep_final);

                if ($row->ftes!=0) {
                    
                    $ftes_dep=($row->ftes/$cantidad);
                    $ftes_dep_final=round($ftes_dep,3, PHP_ROUND_HALF_DOWN);
                    $model_disp->setAttribute('ftes_dependencia',$ftes_dep_final);
                }

                $model_disp->save();
            }
            // AdminDependencia::updateAll(['precio' => $_POST['precio_dep'],'horas'=>$horas_dep,'ftes'=>$_POST['ftes_dep']], 'id_admin ='.$model->id);

            AdminDependencia::deleteAll(['id_admin' => $model->id]);

            if($cantidad>0){
                foreach ($dependencias as $value) {
                    $model_dep=new AdminDependencia();
                    $model_dep->setAttribute('centro_costos_codigo', $value);
                   
                    $model_dep->setAttribute('id_admin', $model->id);
                    
                    $model_dep->save();


                }
            }


            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'dependencias' => $dependencias,
                'zonasUsuario' => $zonasUsuario,
                'marcasUsuario' => $marcasUsuario,
                'distritosUsuario' => $distritosUsuario,
                'empresasUsuario'=>$empresasUsuario,
                'model_dep'=>$model_dep
            ]);
        }
    }

    /**
     * Deletes an existing AdminSupervision model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        AdminDependencia::deleteAll('id_admin = :id ', [':id' => $id]);

        AdminDispositivo::deleteAll('id_admin = :id ', [':id' => $id]);        

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    public function actionDelete_dependencia($id,$admin){


        AdminDependencia::findOne($id)->delete();

        $model=$this->findModel($admin);

        $dependencias=AdminDependencia::find()->where('id_admin='.$admin)->count();

        $precio_dep=($model->precio/$dependencias);

        AdminDependencia::updateAll(['precio' => $precio_dep ], 'id_admin ='.$model->id);


        //$contar=$dependencias->count();

        

        return $this->redirect(['view', 'id' => $admin]);
    }



    public function actionImprimir($id){

        date_default_timezone_set ( 'America/Bogota');
        $model=new AdminSupervision();
        $query=$model->findOne($id);;

        $modelDep=new AdminDependencia();
        $dependencias=$modelDep->find()->where('id_admin='.$id)->all();
        $admin_disp=AdminDispositivo::find()->where('id_admin='.$id)->all();
        $count_dep=$modelDep->find()->where('id_admin='.$id)->count();

        $content = $this->renderPartial('_imprimir', array(
            'model' => $prefactura,
            'query'=>$query,
            'dependencias'=>$dependencias,
            'modelDep'=>$modelDep,
            'admin_disp'=>$admin_disp,
            'count_dep'=>$count_dep
        ), true);


        /*$pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE, 
            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT, 
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER, 
            // your html content input
            'content' => $content,  
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => 'table, td, th {border: 1px solid black;} .kv-heading-1{font-size:18px}', 
             // set mPDF properties on the fly
            'options' => ['title' => 'Administracion y Supervision'],
             // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>['Administracion y Supervision -'.date('Y-m-d')], 
                'SetFooter'=>['{PAGENO}'],
            ]
        ]);
        
        // return the pdf output as per the destination setting
        return $pdf->render();*/
        $pdf = Yii::$app->pdf; // or new Pdf();
        $mpdf = $pdf->api; // fetches mpdf api
        //$mpdf->cssInline('table, td, th {border: 1px solid black;} .kv-heading-1{font-size:18px}');
        $mpdf->SetHeader('Prefactura'.date('Y-m-d')); // call methods or set any properties
        $mpdf->SetFooter('{PAGENO}');
        $mpdf->WriteHtml($content); // call mpdf write html
        echo $mpdf->Output('Prefactura'.date('Y-m-d').'.pdf', 'D'); // call the mpdf api output as needed
    }


    public function actionFinalizar(){
        $model = $this->findModel($_POST['id']);
        $model->setAttribute('estado','cerrado');
        $res='';
        if($model->save()){
            $res='true';
        }else{
            $res=print_r($model->getErrors());
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'respuesta' => $res,
        ];


    }
    /**
     * Finds the AdminSupervision model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdminSupervision the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdminSupervision::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionDeleteservicio($id,$view){

        AdminDispositivo::findOne($id)->delete();
        return $this->redirect(['view','id'=>$view]);
    } 

    public function actionDependenciaszona(){

        $query=CentroCosto::find()
        ->leftJoin('ciudad', ' ciudad.codigo_dane= centro_costo.ciudad_codigo_dane')
        ->leftJoin('ciudad_zona', ' ciudad_zona.ciudad_codigo_dane= ciudad.codigo_dane')
        ->where('ciudad_zona.zona_id='.$_POST['zona'].' AND centro_costo.estado NOT IN("C")  ')
        ->all();

        $option="";
        foreach ($query as $value) {
            $option.="<option value='".$value->codigo."'>".$value->nombre."</option>";
        }

        echo json_encode(array('resp'=>$option));
    }  

    public function  actionAbrir_pref($id){
        $model = $this->findModel($id);
        $model->setAttribute('estado','abierto');
        $model->save();
        return $this->redirect(['view','id'=>$id]);
    }

    public function actionDeleteCerradas($accion=0){
        $query = (new \yii\db\Query())
        ->select(['ad.id id_admin_dep', 'ad.centro_costos_codigo cd','cc.nombre as dep','cc.estado','asup.id id_pref','asup.numero_factura',
            'asup.mes','asup.ano'])
        ->from('admin_dependencia ad')
        ->innerJoin('admin_supervision asup', 'ad.id_admin = asup.id')
        ->innerJoin('centro_costo cc', 'ad.centro_costos_codigo = cc.codigo')
        ->where("/*asup.mes='01' AND asup.ano='2018' AND*/ cc.estado='C'  /*AND asup.id=793*/")
        ->orderby('asup.mes');
        $command = $query->createCommand();
        
        // Ejecutar el comando:
        $rows = $command->queryAll();
        $cont=0;
        if($accion==1){
          foreach($rows as $row_dep):
            if(AdminDependencia::findOne($row_dep['id_admin_dep'])->delete()){
                $cont++;
            }
          endforeach;
        }
        return $this->render('delete_cerrados', [
              'rows'=>$rows,
              'accion'=>$accion,
              'cont'=>$cont
        ]);

    }
}
