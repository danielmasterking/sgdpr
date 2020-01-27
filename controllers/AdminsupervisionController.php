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
use yii\data\Pagination;
use app\models\EmpresaDependencia;


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
                '((SELECT SUM(ftes_dependencia) FROM admin_dispositivo WHERE id_admin=admin_supervision.id) * (SELECT COUNT(id) FROM admin_dependencia WHERE id_admin=admin_supervision.id limit 1))ftes_totales',
                '(SELECT SUM(precio_total) FROM admin_dispositivo WHERE id_admin=admin_supervision.id) total_factura'])
            ->from('admin_supervision')
            ->innerJoin('empresa', 'admin_supervision.empresa=empresa.nit')
            ->where('1=1 ');
        }else{

            $rows = (new \yii\db\Query())
            ->select(['id','mes','ano','usuario','created','estado','numero_factura','fecha_factura','empresa.nombre as empresa',
                '((SELECT SUM(ftes_dependencia) FROM admin_dispositivo WHERE id_admin=admin_supervision.id) * (SELECT COUNT(id) FROM admin_dependencia WHERE id_admin=admin_supervision.id limit 1))ftes_totales',
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

        if (trim($_POST['empresas'])!='') {
            $rows->andWhere("admin_supervision.empresa like '%".$_POST['empresas']."%'");
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


        if ($model->load(Yii::$app->request->post()) /*&& $model->save()*/) {

            $array_post = Yii::$app->request->post();
            //$model->setAttribute('created', $date_time);
                      
            $model->setAttribute('empresa',$_POST['AdminSupervision']['empresa']);
            
            
            $model->save();

            
            $dependencias_seleccionadas=$_POST['dependencias'];
            $cantidad=count($dependencias_seleccionadas);
            AdminDependencia::deleteAll(['id_admin' => $model->id]);

            if($cantidad>0){
                foreach ($dependencias_seleccionadas as $value) {
                    $model_dep=new AdminDependencia();
                    $model_dep->setAttribute('centro_costos_codigo', $value);
                   
                    $model_dep->setAttribute('id_admin', $model->id);
                    
                    $model_dep->save();


                }

                $cantidad=AdminDependencia::find()->where('id_admin='.$model->id)->count();
            }

            //echo $cantidad;
            $dispositivos=AdminDispositivo::find()->where('id_admin='.$id)->all();
            
            foreach ($dispositivos as $row) {
                
                $precio_dep=($row->precio_total/$cantidad);
                $precio_dep_final=round($precio_dep, 2, PHP_ROUND_HALF_DOWN);

                //$model_disp=AdminDispositivo::findOne($row->id);
                //$model_disp->setAttribute('precio_dependencia',$precio_dep_final);
                $array_update=['precio_dependencia'=>$precio_dep_final];
                if ($row->ftes!=0) {
                        
                    $ftes_diurno_dep=bcdiv((($row->ftes_diurno/$cantidad)*$row->cantidad), '1', 3);
                    
                    $ftes_nocturno_dep=bcdiv((($row->ftes_nocturno/$cantidad)*$row->cantidad), '1', 3);


                    //$ftes_dep=($row->ftes/$cantidad);
                    //$ftes_dep_final=round($ftes_dep,3, PHP_ROUND_HALF_DOWN);
                    $ftes_dep=($ftes_diurno_dep+$ftes_nocturno_dep);
                    $ftes_dep_final=bcdiv($ftes_dep, '1', 3);
                    //$model_disp->setAttribute('ftes_diurno_dep',$ftes_diurno_dep);
                    //$model_disp->setAttribute('ftes_nocturno_dep',$ftes_nocturno_dep);
                    //$model_disp->setAttribute('ftes_dependencia',$ftes_dep_final);

                    $array_update['ftes_diurno_dep']=$ftes_diurno_dep;
                    $array_update['ftes_nocturno_dep']=$ftes_nocturno_dep;
                    $array_update['ftes_dependencia']=$ftes_dep_final;

                }

                //$model_disp->save();

                AdminDispositivo::updateAll($array_update, ['=', 'id', $row->id ]);
            }
            // AdminDependencia::updateAll(['precio' => $_POST['precio_dep'],'horas'=>$horas_dep,'ftes'=>$_POST['ftes_dep']], 'id_admin ='.$model->id);

            


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
            //if($value->marca->nombre!='VIVA' && $value->marca->nombre!='INDUSTRIA'){
            $modelo_emp_dep=new EmpresaDependencia();
            $emp_dep=$modelo_emp_dep->get_empresa_deps($value->codigo);
            $existe=false;
            if($emp_dep!=null){
                foreach ($emp_dep as $emp) {
                    if(in_array($_POST['empresa'],$emp_dep) ){
                        $existe=true;
                        break;
                    }
                }
            }

            if($existe){
                $option.="<option value='".$value->codigo."'>".$value->nombre."</option>";
            }
            //}
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


    public function actionAprobacion(){

        $empresas = Empresa::find()->orderBy(['nombre' => SORT_ASC])->all();
        $list_empresas=ArrayHelper::map($empresas,'nombre','nombre');

       $query = (new \yii\db\Query())
        ->select(['id','mes','ano','usuario','created','estado','numero_factura','fecha_factura','empresa.nombre as empresa',
            '(SELECT SUM((ftes*cantidad)) FROM admin_dispositivo WHERE id_admin=admin_supervision.id) ftes_totales',
            '(SELECT SUM(precio_total) FROM admin_dispositivo WHERE id_admin=admin_supervision.id) total_factura'])
        ->from('admin_supervision')
        ->innerJoin('empresa', 'admin_supervision.empresa=empresa.nit')
        ->where('estado="cerrado" AND estado_pedido="S"'); 

        //FILTROS
        if(isset($_GET['enviar'])){
            if(isset($_GET['mes']) && $_GET['mes']!=''){
                $query->andWhere('admin_supervision.mes="'.$_GET['mes'].'" ');
            }

            if(isset($_GET['ano']) && $_GET['ano']!=''){
                $query->andWhere('admin_supervision.ano="'.$_GET['ano'].'" ');
            }

            if(isset($_GET['empresas']) && $_GET['empresas']!=''){
                $query->andWhere('empresa.nombre="'.$_GET['empresas'].'" ');
            }

            if(isset($_GET['buscar']) && $_GET['buscar']!=''){
                $query->andWhere(" 
               
                empresa.nombre like '%".$_GET['buscar']."%'
                OR usuario like '%".$_GET['buscar']."%' 
                OR mes like '%".$_GET['buscar']."%' 
                OR ano like '%".$_GET['buscar']."%' 
                OR numero_factura like '%".$_GET['buscar']."%'
                OR usuario like '%".$_GET['buscar']."%' 
                ");
            }
        }

        $ordenado=isset($_GET['ordenado']) && $_GET['ordenado']!=''?$_GET['ordenado']:"id";
        $forma=isset($_GET['forma']) && $_GET['forma']!=''?$_GET['forma']:"SORT_ASC";

        $query->orderBy([
            $ordenado => $forma
        ]);

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count]);
        $limit=30;
        $command = $query->offset($pagination->offset)->limit($limit)->createCommand();

        // Ejecutar el comando:
        $rows = $command->queryAll();

        $pagina=isset($_GET['page'])?$_GET['page']:1;

        return $this->render('aprobacion', [
              'rows'=>$rows,
              'list_empresas'=>$list_empresas,
              'pagina'=>$pagina,
              'pagination'=>$pagination,
              'count'=>$count,
              
        ]);
    }


    public function actionAprobarRechazar(){
        $count=count($_POST['seleccion']);
        $checks=$_POST['seleccion'];

        if(isset($_POST['aprobar'])){
            foreach ($checks as $value) {
                $model=AdminSupervision::find()->where('id='.$value)->one();
                $model->setAttribute('estado_pedido', 'A');
                $model->setAttribute('usuario_aprueba', Yii::$app->session['usuario-exito']);
                $model->setAttribute('fecha_aprobacion',date('Y-m-d'));
                $model->save();
            }
        }else if(isset($_POST['rechazar'])){
            foreach ($checks as $value) {
                $model=AdminSupervision::find()->where('id='.$value)->one();
                $model->setAttribute('estado_pedido', 'R');
                $model->setAttribute('motivo_rechazo_prefactura',$_POST['observacion']);
                $model->setAttribute('usuario_rechaza', Yii::$app->session['usuario-exito']);
                $model->setAttribute('fecha_rechazo',date('Y-m-d'));
                $model->save();
            }
        }

        return $this->redirect(['aprobacion']);

    }


    public function actionAprobarPrefactura($id){//A= Aprobar
        $model=AdminSupervision::find()->where('id='.$id)->one();
        $model->setAttribute('estado_pedido', 'A');
        $model->setAttribute('usuario_aprueba', Yii::$app->session['usuario-exito']);
        $model->setAttribute('fecha_aprobacion',date('Y-m-d'));
        if($model->save()){
            return $this->redirect(['aprobacion']);
        }else{
            print_r($model->getErrors());
        }

    }


    public function actionRechazarPrefactura($id){//R= Rechazar
        $model=AdminSupervision::find()->where('id='.$id)->one();
        $model->setAttribute('estado_pedido', 'R');
        $model->setAttribute('motivo_rechazo_prefactura',$_POST['observacion']);
        $model->setAttribute('usuario_rechaza', Yii::$app->session['usuario-exito']);
        $model->setAttribute('fecha_rechazo',date('Y-m-d'));
        if($model->save()){
            return $this->redirect(['aprobacion']);
        }else{
            print_r($model->getErrors());
        }

    }

    public function actionConsolidado(){

        $query=(new \yii\db\Query())
        ->select(['as.mes','as.ano','cc.nombre dependencia','as.empresa nit','em.nombre empresa_seg','as.estado','(select SUM(precio_dependencia) FROM admin_dispositivo where id_admin=as.id) as total_factura','as.numero_factura','as.fecha_factura',
            '(
                CASE
                WHEN SUBSTRING(cc.ceco,1,1) =3 THEN 533505001 
                

                ELSE  523505001
                END

             )cuenta_contable','cc.ceco','c.nombre as ciudad','m.nombre as marca',
             '(
            select zona.nombre  from centro_costo cc
            inner join ciudad_zona cz on cc.ciudad_codigo_dane=cz.ciudad_codigo_dane
            inner join zona on  cz.zona_id=zona.id
            WHERE cc.codigo=ad.centro_costos_codigo limit 1
            ) regional','ad.id id_admin_dep','cc.cebe','ad.id_pedido','as.fecha_aprobacion'])
            ->from('admin_supervision  as')
            //->innerJoin('admin_supervision  as', 'ad.id_admin=as.id')
            ->innerJoin('admin_dependencia ad', 'as.id=ad.id_admin')
            //->innerJoin('admin_dispositivo da', 'as.id=da.id_admin')
            ->leftJoin('centro_costo  cc', 'ad.centro_costos_codigo=cc.codigo')
            ->leftJoin('marca  m', 'cc.marca_id=m.id')
            ->leftJoin('ciudad  c', 'cc.ciudad_codigo_dane=c.codigo_dane')
            ->leftJoin('empresa  em', 'as.empresa=em.nit')
            ->where('as.estado="cerrado" AND as.estado_pedido="A"')
            ->orderby('as.numero_factura ASC');

        $command = $query->createCommand();

        // Ejecutar el comando:
        $rows = $command->queryAll();
        return $this->render('consolidado', [
            'rows'=>$rows
        ]);
    }

    public function actionEquivalenciaPrefactura(){
        $id_pedido=1;
        $posicion=1;
        $numero_factura_anterior=null;
       // $empresa_anterior=null;
        //$ciudad_anterior=null;
        //$posicion_anterior=0;

        //$query=PrefacturaConsolidadoPedido::find()->where('estado_pedido="A"')->orderby('ciudad,empresa')->all();

        $query=(new \yii\db\Query())
        ->select(['cc.nombre dependencia','asu.empresa nit','em.nombre empresa_seg','asu.estado',
            'cc.ceco','c.nombre as ciudad','m.nombre as marca','ad.id as id_disp','ad.id id_admin_dep','cc.cebe','ad.id_pedido','asu.numero_factura'])
            ->from('admin_supervision  asu'/*'admin_dependencia ad','admin_dispositivo da'*/)
            //->innerJoin('admin_supervision  as', 'ad.id_admin=as.id')
            ->innerJoin('admin_dependencia ad', 'asu.id=ad.id_admin')
            //->innerJoin('admin_dispositivo da', 'asu.id=da.id_admin')
            ->leftJoin('centro_costo  cc', 'ad.centro_costos_codigo=cc.codigo')
            ->leftJoin('marca  m', 'cc.marca_id=m.id')
            ->leftJoin('ciudad  c', 'cc.ciudad_codigo_dane=c.codigo_dane')
            ->leftJoin('empresa  em', 'asu.empresa=em.nit')
            ->where('asu.estado="cerrado" AND asu.estado_pedido="A"')
            ->orderby('asu.numero_factura');

        $command = $query->createCommand();

        // Ejecutar el comando:
        $rows = $command->queryAll();


        foreach ($rows as $ped) {
            if($numero_factura_anterior==null ){
                AdminDependencia::updateAll(['id_pedido' => $id_pedido,'posicion'=>$posicion], ['=', 'id', $ped['id_disp']]);

                $numero_factura_anterior=$ped['numero_factura'];
                
                //$posicion_anterior=$posicion;
            }elseif($numero_factura_anterior==$ped['numero_factura']){
                $posicion++;
                AdminDependencia::updateAll(['id_pedido' => $id_pedido,'posicion'=>$posicion], ['=', 'id', $ped['id_disp']]);
                $numero_factura_anterior=$ped['numero_factura'];
                
                //$posicion_anterior=$posicion_anterior+1;
            }else{
                $id_pedido++;
                $posicion=1;
                AdminDependencia::updateAll(['id_pedido' => $id_pedido,'posicion'=>$posicion], ['=', 'id', $ped['id_disp']]);
                $numero_factura_anterior=$ped['numero_factura'];
                
            }
            //echo $ped['id_disp']."-".$ped['ciudad']."-".$ped['empresa_seg']."<br>";
        }

        return $this->redirect(['consolidado']);
    }


    public function actionCabeceraPrefactura(){

        $query=(new \yii\db\Query())
        ->select(["CAST(null AS CHAR) as nombre_factura",'as.mes','as.ano','cc.nombre dependencia','as.empresa nit','em.nombre empresa_seg','as.estado',"/*ROUND((da.ftes_dependencia* da.cantidad),3)*/ da.ftes_dependencia as ftes/*da.ftes*/",'da.precio_dependencia as total_factura','as.numero_factura','as.fecha_factura',
            '(
                CASE
                WHEN SUBSTRING(cc.ceco,1,1) =3 THEN 533505001 
                

                ELSE  523505001
                END

             )cuenta_contable','cc.ceco','c.nombre as ciudad','m.nombre as marca',"da.descripcion as puesto","da.cantidad as cantidad_servicios","da.horas","da.lunes","da.martes","da.miercoles","da.jueves","da.viernes","da.sabado","da.domingo","da.festivo","CAST(null AS CHAR) as tipo_servicio","da.hora_inicio","da.hora_fin","da.dias as total_dias",
             /*"(SELECT COUNT(id) FROM admin_dependencia WHERE id_admin=as.id  ) as  num_dep",*/
             "da.ftes_diurno_dep /*ROUND(((da.ftes_diurno/(SELECT COUNT(id) FROM admin_dependencia WHERE id_admin=as.id  ))*da.cantidad),3)*/ as ftes_diurno",
             "da.ftes_nocturno_dep as ftes_nocturno",
             "CAST(null AS CHAR) as explicacion",
             "/*CAST(null AS CHAR)*/((da.ftes_diurno_dep*da.precio_dependencia)/da.ftes_dependencia) as valor_serv_diurno","/*CAST(null AS CHAR)*/((da.ftes_nocturno_dep*da.precio_dependencia)/da.ftes_dependencia) as valor_serv_nocturno",'CAST("Administracion y supervision" AS CHAR) as tipo','(
            select zona.nombre  from centro_costo cc
            inner join ciudad_zona cz on cc.ciudad_codigo_dane=cz.ciudad_codigo_dane
            inner join zona on  cz.zona_id=zona.id
            WHERE cc.codigo=ad.centro_costos_codigo limit 1
            ) regional','da.id as id_disp','CAST(null AS CHAR) as servicio_disp','ad.id id_admin_dep','cc.cebe','ad.id_pedido','as.usuario'])
            ->from('admin_supervision  as'/*'admin_dependencia ad','admin_dispositivo da'*/)
            //->innerJoin('admin_supervision  as', 'ad.id_admin=as.id')
            ->innerJoin('admin_dependencia ad', 'as.id=ad.id_admin')
            ->innerJoin('admin_dispositivo da', 'as.id=da.id_admin')
            ->leftJoin('centro_costo  cc', 'ad.centro_costos_codigo=cc.codigo')
            ->leftJoin('marca  m', 'cc.marca_id=m.id')
            ->leftJoin('ciudad  c', 'cc.ciudad_codigo_dane=c.codigo_dane')
            ->leftJoin('empresa  em', 'as.empresa=em.nit')
            ->where('as.estado="cerrado" AND as.estado_pedido="A"  AND ad.id_pedido <> "" ')
            ->orderby('c.nombre,em.nombre');

        $command = $query->createCommand();

        // Ejecutar el comando:
        $rows = $command->queryAll();
         return $this->render('cabecera_prefactura', [
         'result'=>$rows
        ]);
    }


    public function actionFinalizarPrefacturas(){
        AdminSupervision::updateAll(['estado_pedido' => "F"], ['=', 'estado_pedido', 'A']);
        return $this->redirect(['consolidado']);
    }

    public function actionDevolverAprobacion(){
        AdminSupervision::updateAll(['estado_pedido' => 'S'], ['=', 'estado_pedido', 'A']);
        return $this->redirect(['consolidado']);
    }

    public function actionOrdenCompraPrefactura(){

        $prefacturas=$_POST['seleccion'];

        if(count($prefacturas)>0){
            foreach ($prefacturas as $pref) {
                $query=AdminDependencia::find()->where('id='.$pref)->one();
                if($query->orden_compra!=null && $query->orden_compra!=''){
                    $query->setAttribute('estado', 'H');
                    $query->save();
                }
            }
            return $this->redirect(['orden-compra-prefactura']);

        }

        $query=(new \yii\db\Query())
        ->select(["CAST(null AS CHAR) as nombre_factura",'as.mes','as.ano','cc.nombre dependencia','as.empresa nit','em.nombre empresa_seg','as.estado',"/*ROUND((da.ftes_dependencia* da.cantidad),3)*/ da.ftes_dependencia as ftes/*da.ftes*/",'da.precio_dependencia as total_factura','as.numero_factura','as.fecha_factura',
            '(
                CASE
                WHEN SUBSTRING(cc.ceco,1,1) =3 THEN 533505001 
                

                ELSE  523505001
                END

             )cuenta_contable','cc.ceco','c.nombre as ciudad','m.nombre as marca',"da.descripcion as puesto","da.cantidad as cantidad_servicios","da.horas","da.lunes","da.martes","da.miercoles","da.jueves","da.viernes","da.sabado","da.domingo","da.festivo","CAST(null AS CHAR) as tipo_servicio","da.hora_inicio","da.hora_fin","da.dias as total_dias",
             /*"(SELECT COUNT(id) FROM admin_dependencia WHERE id_admin=as.id  ) as  num_dep",*/
             "da.ftes_diurno_dep /*ROUND(((da.ftes_diurno/(SELECT COUNT(id) FROM admin_dependencia WHERE id_admin=as.id  ))*da.cantidad),3)*/ as ftes_diurno",
             "da.ftes_nocturno_dep as ftes_nocturno",
             "CAST(null AS CHAR) as explicacion",
             "/*CAST(null AS CHAR)*/((da.ftes_diurno_dep*da.precio_dependencia)/da.ftes_dependencia) as valor_serv_diurno","/*CAST(null AS CHAR)*/((da.ftes_nocturno_dep*da.precio_dependencia)/da.ftes_dependencia) as valor_serv_nocturno",'CAST("Administracion y supervision" AS CHAR) as tipo','(
            select zona.nombre  from centro_costo cc
            inner join ciudad_zona cz on cc.ciudad_codigo_dane=cz.ciudad_codigo_dane
            inner join zona on  cz.zona_id=zona.id
            WHERE cc.codigo=ad.centro_costos_codigo limit 1
            ) regional','ad.id as id_disp','CAST(null AS CHAR) as servicio_disp','ad.id id_admin_dep','cc.cebe','ad.id_pedido','as.usuario','ad.posicion','ad.orden_compra'])
            ->from('admin_supervision  as'/*'admin_dependencia ad'/*,'admin_dispositivo da'*/)
            //->innerJoin('admin_supervision  as', 'ad.id_admin=as.id')
             ->innerJoin('admin_dependencia ad', 'as.id=ad.id_admin')
            ->innerJoin('admin_dispositivo da', 'as.id=da.id_admin')
            ->leftJoin('centro_costo  cc', 'ad.centro_costos_codigo=cc.codigo')
            ->leftJoin('marca  m', 'cc.marca_id=m.id')
            ->leftJoin('ciudad  c', 'cc.ciudad_codigo_dane=c.codigo_dane')
            ->leftJoin('empresa  em', 'as.empresa=em.nit')
            ->where('as.estado_pedido="F"  AND ad.estado="S" ');
            //->orderby('c.nombre,em.nombre');

        $command = $query->createCommand();

        // Ejecutar el comando:
        $rows = $command->queryAll();


        return $this->render('orden_prefactura', [
         'result'=>$rows
        ]);
    }

    public function actionPrefacturaAgregarOrden(){

        AdminDependencia::updateAll(['orden_compra' => $_POST['orden']], ['=', 'id', $_POST['id_pref']]);
        return $this->redirect(['orden-compra-prefactura']);
    }


    public function actionPrefacturaAgregarOrdenTodos(){

        $prefacturas=$_POST['seleccion'];
        $orden=$_POST['orden'];
        foreach ($prefacturas as $key => $value) {
            AdminDependencia::updateAll(['orden_compra' => $orden], ['=', 'id', $value]);
        }
       
        return $this->redirect(['orden-compra-prefactura']);
    }

    public function actionDevolverConsolidado(){
        AdminSupervision::updateAll(['estado_pedido' => 'A'], ['=', 'estado_pedido', 'F']);
        return $this->redirect(['orden-compra-prefactura']);
    }

    public function actionHistoricoPrefactura(){
          $query=(new \yii\db\Query())
        ->select(["CAST(null AS CHAR) as nombre_factura",'as.mes','as.ano','cc.nombre dependencia','as.empresa nit','em.nombre empresa_seg','as.estado',"/*ROUND((da.ftes_dependencia* da.cantidad),3)*/ da.ftes_dependencia as ftes/*da.ftes*/",'da.precio_dependencia as total_factura','as.numero_factura','as.fecha_factura',
            '(
                CASE
                WHEN SUBSTRING(cc.ceco,1,1) =3 THEN 533505001 
                

                ELSE  523505001
                END

             )cuenta_contable','cc.ceco','c.nombre as ciudad','m.nombre as marca',"da.descripcion as puesto","da.cantidad as cantidad_servicios","da.horas","da.lunes","da.martes","da.miercoles","da.jueves","da.viernes","da.sabado","da.domingo","da.festivo","CAST(null AS CHAR) as tipo_servicio","da.hora_inicio","da.hora_fin","da.dias as total_dias",
             /*"(SELECT COUNT(id) FROM admin_dependencia WHERE id_admin=as.id  ) as  num_dep",*/
             "da.ftes_diurno_dep /*ROUND(((da.ftes_diurno/(SELECT COUNT(id) FROM admin_dependencia WHERE id_admin=as.id  ))*da.cantidad),3)*/ as ftes_diurno",
             "da.ftes_nocturno_dep as ftes_nocturno",
             "CAST(null AS CHAR) as explicacion",
             "/*CAST(null AS CHAR)*/((da.ftes_diurno_dep*da.precio_dependencia)/da.ftes_dependencia) as valor_serv_diurno","/*CAST(null AS CHAR)*/((da.ftes_nocturno_dep*da.precio_dependencia)/da.ftes_dependencia) as valor_serv_nocturno",'CAST("Administracion y supervision" AS CHAR) as tipo','(
            select zona.nombre  from centro_costo cc
            inner join ciudad_zona cz on cc.ciudad_codigo_dane=cz.ciudad_codigo_dane
            inner join zona on  cz.zona_id=zona.id
            WHERE cc.codigo=ad.centro_costos_codigo limit 1
            ) regional','da.id as id_disp','CAST(null AS CHAR) as servicio_disp','ad.id id_admin_dep', 'cc.cebe','ad.id_pedido','as.usuario','as.usuario_aprueba','as.fecha_aprobacion','as.usuario_rechaza','as.fecha_rechazo','as.motivo_rechazo_prefactura','ad.estado'])
            ->from('admin_supervision  as'/*'admin_dependencia ad','admin_dispositivo da'*/)
            //->innerJoin('admin_supervision  as', 'ad.id_admin=as.id')
            ->innerJoin('admin_dependencia ad', 'as.id=ad.id_admin')
            ->innerJoin('admin_dispositivo da', 'as.id=da.id_admin')
            ->leftJoin('centro_costo  cc', 'ad.centro_costos_codigo=cc.codigo')
            ->leftJoin('marca  m', 'cc.marca_id=m.id')
            ->leftJoin('ciudad  c', 'cc.ciudad_codigo_dane=c.codigo_dane')
            ->leftJoin('empresa  em', 'as.empresa=em.nit')
            ->where('as.estado_pedido IN("H","R") OR ad.estado="H"')
            ->orderby('c.nombre,em.nombre');

        $command = $query->createCommand();

        // Ejecutar el comando:
        $rows = $command->queryAll();
        return $this->render('historico', [
            'rows'=>$rows
        ]);

    }

    public function actionEliminarViva($eliminar=0,$contador=0){
        $query=(new \yii\db\Query())
        ->select(["dp.id id_admin_dep","cc.nombre dependencia","m.nombre marca"])
        ->from('admin_dependencia dp')
        ->innerJoin('admin_supervision asu', 'dp.id_admin=asu.id')
        ->innerJoin('centro_costo cc', 'cc.codigo=dp.centro_costos_codigo')
        ->innerJoin('marca m', 'm.id=cc.marca_id')
        ->where("m.nombre IN('VIVA','INDUSTRIA') AND asu.mes='06' AND asu.empresa<>'800185549'");
        $command = $query->createCommand();
        // Ejecutar el comando:
        $rows = $command->queryAll();

        if($eliminar==1){
            $contador=0;
            foreach ($rows as $value) {
                $model=AdminDependencia::find()->where('id='.$value['id_admin_dep'])->one();
                $model->delete();
                $contador++;
            }

             return $this->redirect(['eliminar-viva','eliminar'=>0,'contador'=>$contador]);
        }


        return $this->render('eliminar_viva', [
            'rows'=>$rows,
            'contador'=>$contador
        ]);
    }
}
