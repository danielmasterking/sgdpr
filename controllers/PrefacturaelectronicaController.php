<?php

namespace app\controllers;

use Yii;
use app\models\PrefacturaElectronica;
use app\models\PrefacturaElectronicaSearch;
use app\models\CentroCosto;
use app\models\Empresa;
use app\models\Zona;
use app\models\Usuario;
use app\models\ModeloPrefacturaElectronica;
use app\models\PrefacturaDispositivoFijoElectronico;
use app\models\PrefacturaDispositivoVariableElectronico;
use app\models\TipoAlarma;
use app\models\DescAlarma;
use app\models\MarcaAlarma;
use app\models\AreaDependencia;
use app\models\TipoServicioElectronica;
use app\models\PrefacturaMonitoreo;
use app\models\ModeloMonitoreo;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use kartik\mpdf\Pdf;
use yii\filters\AccessControl;
use yii\data\Pagination;
/**
 * PrefacturaelectronicaController implements the CRUD actions for PrefacturaElectronica model.
 */
class PrefacturaelectronicaController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['view','index','create','update'],
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['view','index','create','update'],
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
     * Lists all PrefacturaElectronica models.
     * @return mixed
     */
    public function actionIndex()
    {
        $usuario = Usuario::findOne(Yii::$app->session['usuario-exito']);
        //permiso administrador
        $permisos = array();
        if( isset(Yii::$app->session['permisos-exito']) ){
            $permisos = Yii::$app->session['permisos-exito'];
        }

        //print_r($permisos);
        ///////////////////////
        $zonas = Zona::find()->all();
        $dependencias = CentroCosto::find()->/*where(['not in', 'estado', ['C']])->*/orderBy(['nombre' => SORT_ASC])->all();
        $empresas = Empresa::find()->orderBy(['nombre' => SORT_ASC])->all();
        $list_empresas=ArrayHelper::map($empresas,'nit','nombre');
        $zonasUsuario = array();
        $marcasUsuario = array();
        $distritosUsuario = array();
        $empresasUsuario = array();
        
        $filas = array();
        
        if($usuario != null){
          $zonasUsuario = $usuario->zonas;      
          $marcasUsuario = $usuario->marcas;
          $distritosUsuario = $usuario->distritos;        
          $empresasUsuario = $usuario->empresas;
        }

        $llaves = array();
        $id_pendiente = '';
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


        //////////DEPENDENCIAS DEL USUARIO
        $dependencias_user=$this->dependencias_usuario(Yii::$app->session['usuario-exito']);

        $in=" IN(";

        foreach ($dependencias_user as $value) {
            
            $in.=" '".$value."',";    
        }

        $in_final = substr($in, 0, -1).")";

        if(in_array("administrador", $permisos) ||  in_array("prefactura-analista", $permisos)){
            $rows = (new \yii\db\Query())
            ->select(['dp.id id', 'DATE(dp.created) fecha','dp.mes mes','dp.ano ano','dp.usuario usuario','cc.nombre dependencia','cc.ceco','em.nombre empresa','dp.estado estado','(

                    
                    (   SELECT SUM(REPLACE(valor_arrendamiento_mensual, ".", "")) 
                        FROM prefactura_dispositivo_fijo_electronico 
                        WHERE id_prefactura_electronica=dp.id  
                    )
                    +

                    (
                        (   SELECT COALESCE(SUM(REPLACE(valor_novedad, ".", "")) ,0)
                            FROM prefactura_dispositivo_variable_electronico 
                            WHERE (id_prefactura_electronica=dp.id)  and   (id_tipo_servicio=2)
                        )
                        -

                        (   SELECT COALESCE(SUM(REPLACE(valor_novedad, ".", "")),0)
                            FROM prefactura_dispositivo_variable_electronico 
                            WHERE (id_prefactura_electronica=dp.id)  and   (id_tipo_servicio=1  or  id_tipo_servicio=4)
                        )
                    )

                )Total','
                (

                    
                    (   SELECT SUM(REPLACE(valor_arrendamiento_mensual, ".", "")) 
                        FROM prefactura_dispositivo_fijo_electronico 
                        WHERE id_prefactura_electronica=dp.id  
                    )
                    

                    

                )fijos
                ',
                '
                    (
                        (   SELECT COALESCE(SUM(REPLACE(valor_novedad, ".", "")) ,0)
                            FROM prefactura_dispositivo_variable_electronico 
                            WHERE (id_prefactura_electronica=dp.id)  and   (id_tipo_servicio=2)
                        )
                        -

                        (   SELECT COALESCE(SUM(REPLACE(valor_novedad, ".", "")),0)
                            FROM prefactura_dispositivo_variable_electronico 
                            WHERE (id_prefactura_electronica=dp.id)  and   (id_tipo_servicio=1  or  id_tipo_servicio=4)
                        )
                    )variables

                '

                ,'
                    (   SELECT SUM(valor_total) 
                        FROM prefactura_monitoreo 
                        WHERE id_prefactura_electronica=dp.id  
                    ) Monitoreo

                ','(
            select zona.nombre  from centro_costo cc
            inner join ciudad_zona cz on cc.ciudad_codigo_dane=cz.ciudad_codigo_dane
            inner join zona on  cz.zona_id=zona.id
            WHERE cc.codigo=dp.centro_costo_codigo limit 1
            ) regional','dp.numero_factura','dp.fecha_factura','dp.nombre_factura'])
            ->from('prefactura_electronica dp, centro_costo cc, empresa em')
            ->where('dp.centro_costo_codigo=cc.codigo AND dp.empresa=em.nit');
            
        }elseif(in_array("prefactura-regional", $permisos) ){
            


            $rows = (new \yii\db\Query())
            ->select(['dp.id id', 'DATE(dp.created) fecha','dp.mes mes','dp.ano ano','dp.usuario usuario','cc.nombre dependencia','cc.ceco','em.nombre empresa','dp.estado estado','(

                    
                    (   SELECT SUM(REPLACE(valor_arrendamiento_mensual, ".", "")) 
                        FROM prefactura_dispositivo_fijo_electronico 
                        WHERE id_prefactura_electronica=dp.id  
                    )
                    +

                    (
                        (   SELECT COALESCE(SUM(REPLACE(valor_novedad, ".", "")) ,0)
                            FROM prefactura_dispositivo_variable_electronico 
                            WHERE (id_prefactura_electronica=dp.id)  and   (id_tipo_servicio=2)
                        )
                        -

                        (   SELECT COALESCE(SUM(REPLACE(valor_novedad, ".", "")),0)
                            FROM prefactura_dispositivo_variable_electronico 
                            WHERE (id_prefactura_electronica=dp.id)  and   (id_tipo_servicio=1  or  id_tipo_servicio=4)
                        )
                    )

                )Total','
                (

                    
                    (   SELECT SUM(REPLACE(valor_arrendamiento_mensual, ".", "")) 
                        FROM prefactura_dispositivo_fijo_electronico 
                        WHERE id_prefactura_electronica=dp.id  
                    )
                    

                    

                )fijos
                ',
                '
                    (
                        (   SELECT COALESCE(SUM(REPLACE(valor_novedad, ".", "")) ,0)
                            FROM prefactura_dispositivo_variable_electronico 
                            WHERE (id_prefactura_electronica=dp.id)  and   (id_tipo_servicio=2)
                        )
                        -

                        (   SELECT COALESCE(SUM(REPLACE(valor_novedad, ".", "")),0)
                            FROM prefactura_dispositivo_variable_electronico 
                            WHERE (id_prefactura_electronica=dp.id)  and   (id_tipo_servicio=1  or  id_tipo_servicio=4)
                        )
                    )variables

                '

                ,'
                    (   SELECT SUM(valor_total) 
                        FROM prefactura_monitoreo 
                        WHERE id_prefactura_electronica=dp.id  
                    ) Monitoreo

                ','(
            select zona.nombre  from centro_costo cc
            inner join ciudad_zona cz on cc.ciudad_codigo_dane=cz.ciudad_codigo_dane
            inner join zona on  cz.zona_id=zona.id
            WHERE cc.codigo=dp.centro_costo_codigo limit 1
            ) regional','dp.numero_factura','dp.fecha_factura'])
            ->from('prefactura_electronica dp, centro_costo cc, empresa em','dp.nombre_factura')
            ->where('dp.centro_costo_codigo=cc.codigo AND dp.empresa=em.nit AND ( dp.centro_costo_codigo '.$in_final.' )');
            
        }else{

            ///////////////////////////////////////////////////////////////////////////////
             $rows = (new \yii\db\Query())
            ->select(['dp.id id', 'DATE(dp.created) fecha','dp.mes mes','dp.ano ano','dp.usuario usuario','cc.nombre dependencia','cc.ceco','em.nombre empresa','dp.estado estado','(

                    
                    (   SELECT SUM(REPLACE(valor_arrendamiento_mensual, ".", "")) 
                        FROM prefactura_dispositivo_fijo_electronico 
                        WHERE id_prefactura_electronica=dp.id  
                    )
                    +

                    (
                        (   SELECT COALESCE(SUM(REPLACE(valor_novedad, ".", "")) ,0)
                            FROM prefactura_dispositivo_variable_electronico 
                            WHERE (id_prefactura_electronica=dp.id)  and   (id_tipo_servicio=2)
                        )
                        -

                        (   SELECT COALESCE(SUM(REPLACE(valor_novedad, ".", "")),0)
                            FROM prefactura_dispositivo_variable_electronico 
                            WHERE (id_prefactura_electronica=dp.id)  and   (id_tipo_servicio=1  or  id_tipo_servicio=4)
                        )
                    )

                )Total','
                (

                    
                    (   SELECT SUM(REPLACE(valor_arrendamiento_mensual, ".", "")) 
                        FROM prefactura_dispositivo_fijo_electronico 
                        WHERE id_prefactura_electronica=dp.id  
                    )
                    

                    

                )fijos
                ',
                '
                    (
                        (   SELECT COALESCE(SUM(REPLACE(valor_novedad, ".", "")) ,0)
                            FROM prefactura_dispositivo_variable_electronico 
                            WHERE (id_prefactura_electronica=dp.id)  and   (id_tipo_servicio=2)
                        )
                        -

                        (   SELECT COALESCE(SUM(REPLACE(valor_novedad, ".", "")),0)
                            FROM prefactura_dispositivo_variable_electronico 
                            WHERE (id_prefactura_electronica=dp.id)  and   (id_tipo_servicio=1  or  id_tipo_servicio=4)
                        )
                    )variables

                '

                ,'
                    (   SELECT SUM(valor_total) 
                        FROM prefactura_monitoreo 
                        WHERE id_prefactura_electronica=dp.id  
                    ) Monitoreo

                ','(
            select zona.nombre  from centro_costo cc
            inner join ciudad_zona cz on cc.ciudad_codigo_dane=cz.ciudad_codigo_dane
            inner join zona on  cz.zona_id=zona.id
            WHERE cc.codigo=dp.centro_costo_codigo limit 1
            ) regional','dp.numero_factura','dp.fecha_factura','dp.nombre_factura'])
            ->from('prefactura_electronica dp, centro_costo cc, empresa em')
            ->where('dp.centro_costo_codigo=cc.codigo AND dp.empresa=em.nit AND dp.usuario="'.Yii::$app->session['usuario-exito'].'"');
            ///////////////////////////////////////////////////////////////////////////////
        }

        //SI ES ADMIN MUESTRA TODAS DE LO CONTRARIO MUESTRA SOLO MIS FACTURAS
        /*if(!in_array("administrador", $permisos)){
            $rows->andWhere("dp.usuario='".Yii::$app->session['usuario-exito']."' ");
        }*/

        //////////////////////////////////////////////////////////////////////

        if(isset($_POST['desde'])){
            if($_POST['desde']!="" && $_POST['hasta']!=""){
                $rows->andWhere("DATE(dp.created) between '".$_POST['desde']."' AND '".$_POST['hasta']."'");
            }
        }
        if(isset($_POST['buscar'])){
            if (trim($_POST['buscar'])!='') {
                $buscar=trim($_POST['buscar']);
                $dependencia='';
                if(trim($_POST['dependencias2'])!='' && trim($_POST['dependencias2'])!='0'){
                    $dependencia=trim($_POST['dependencias2']);
                }
                $rows->andWhere("dp.mes like '%". $buscar."%' OR dp.ano like '%".$buscar."%' OR em.nombre like '%".$buscar."%' OR dp.usuario like '%".$buscar."%' OR dp.numero_factura like '%".$buscar."%' ");
                if($dependencia!=''){
                    $rows->andWhere("cc.nombre like '%".$dependencia."%'");
                }
            }else if(trim($_POST['dependencias2'])!='' && trim($_POST['dependencias2'])!='0'){
                $rows->andWhere("cc.nombre like '%".$_POST['dependencias2']."%'");
            }
        }

        //BUSQUEDA POR MARCA
        if (trim($_POST['marca'])) {
            $rows->andWhere("dp.marca like '%".$_POST['marca']."%'");
        }

        if (trim($_POST['mes'])!='') {
            $rows->andWhere("dp.mes like '%".$_POST['mes']."%'");
        }

        if (trim($_POST['empresa'])!='') {
            $rows->andWhere("dp.empresa ='".$_POST['empresa']."'");
        }
        ////////////////////

        $rowsCount= clone $rows;
        $ordenado='dp.id';
        if(isset($_POST['ordenado'])){
            switch ($_POST['ordenado']) {
                case "mes":
                    $ordenado='dp.mes';
                    break;
                case "ano":
                    $ordenado='dp.ano';
                    break;
                case "dependencia":
                    $ordenado='cc.nombre';
                    break;
                case "empresa":
                    $ordenado='em.nombre';
                    break;
                case "fecha":
                    $ordenado='dp.created';
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
        $prefacturas = $command->queryAll();
        if(isset($_POST['excel'])){
            \moonland\phpexcel\Excel::widget([
                'models' => $prefacturas,
                'mode' => 'export',
                'fileName' => 'listado de prefacturas', 
                'columns' => ['numero_factura','fecha_factura','mes','ano','usuario','regional','ceco','dependencia','empresa','fijos','variables','Monitoreo'],
                'headers' => [
                    'numero_factura'=>'Numero Factura',
                    'fecha_factura'=>'Fecha Factura',
                    'mes' => 'MES',
                    'ano' => 'AÑO',
                    'usuario'=>'Usuario',
                    'regional'=>'Regional',
                    'ceco'=>'Ceco',
                    'dependencia' => 'DEPENDENCIA',
                    'empresa'=>'EMPRESA',
                    'fijos'=>'Dispositivos fijos',
                    'variables'=>'Dispositivos variables',
                    'Monitoreo'=>'Monitoreo'
                ], 
            ]);
        }
        $modelcount = $rowsCount->count();
        $no_of_paginations = ceil($modelcount / $per_page);
        $res='';
        $model_dispositivo=new PrefacturaDispositivoFijoElectronico();
        if($modelcount > $rowsPerPage){
           
            $res.=$this->renderPartial('_paginacion_partial', array(
                'cur_page' => $cur_page,
                'no_of_paginations' => $no_of_paginations,
                'first_btn' => $first_btn,
                'previous_btn' => $previous_btn,
                'next_btn' => $next_btn,
                'last_btn' => $last_btn,
                'modelcount' => $modelcount,
                'model_dispositivo'=>$model_dispositivo
            ), true);
        }
        $res.= $this->renderPartial('_partial', array(
            'prefacturas' => $prefacturas,
            'historico' => 'active', 'usuario' => $usuario,
            'modelcount' => $modelcount,
            'model_dispositivo'=>$model_dispositivo
                ), true);
        if(isset($_POST['page'])){
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'respuesta' => $res,
                'query' => $command->sql,
            ];
        }else{
            return $this->render('index',
                ['partial' => $res, 
                'historico' => 'active',
                'zonas' => $zonas,
                'dependencias' => $dependencias,
                'empresas' => $empresas,
                'zonasUsuario' => $zonasUsuario,
                'marcasUsuario' => $marcasUsuario,
                'distritosUsuario' => $distritosUsuario,                
                'empresasUsuario' => $empresasUsuario,
                'list_empresas'=>$list_empresas
                ]);
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
    /**
     * Displays a single PrefacturaElectronica model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
       $dispositivos = PrefacturaDispositivoFijoElectronico::find()->where('id_prefactura_electronica='.$id)->all();
       //$count_fijo = $dispositivos->count();
       // $pag_fijos = new Pagination([
       //  'defaultPageSize'=>10,
       //  'totalCount' => $dispositivos->count()
       //  ]);
       // $disp_fijos = $dispositivos
       //  ->offset($pag_fijos->offset)
       //  ->limit($pag_fijos->limit)
       //  ->all();

       $modelo=new  PrefacturaDispositivoFijoElectronico();
       $variables=PrefacturaDispositivoVariableElectronico::find()->where('id_prefactura_electronica='.$id)->all();
       $monitoreos=PrefacturaMonitoreo::find()->where('id_prefactura_electronica='.$id)->all();


        if (isset($_POST['fecha_factura'])) {
            $model =PrefacturaElectronica::find()->where('id='.$id)->one();
            $model->setAttribute('numero_factura', $_POST['num_factura']);
            $model->setAttribute('fecha_factura', $_POST['fecha_factura']);

            $model->save();

            return $this->redirect(['view', 'id' => $id ]);
        }



        return $this->render('view', [
            'model' => $this->findModel($id),
            'dispositivos' => $dispositivos/*$disp_fijos*/,
            'pag_fijos'=>$pag_fijos,
            'modelo'=>$modelo,
            'variables'=>$variables,
            'monitoreos'=>$monitoreos
        ]);
    }


    public function actionImprimir($id){
       date_default_timezone_set ( 'America/Bogota');
       $dispositivos = PrefacturaDispositivoFijoElectronico::find()->where('id_prefactura_electronica='.$id)->all();
       $modelo=new  PrefacturaElectronica();
       $prefactura=$modelo->findOne($id);
       $variables=PrefacturaDispositivoVariableElectronico::find()->where('id_prefactura_electronica='.$id)->all();
       $monitoreos=PrefacturaMonitoreo::find()->where('id_prefactura_electronica='.$id)->all();

       $content = $this->renderPartial('_imprimir', array(
            'model' => $prefactura,
            'modelo'=>$modelo,
            'dispositivos' => $dispositivos,
            'variables'=>$variables,
            'monitoreos'=>$monitoreos
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
            'options' => ['title' => 'Prefactura Electronica'],
             // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>['Prefactura Electronica-'.date('Y-m-d')], 
                'SetFooter'=>['{PAGENO}'],
            ]
        ]);
        
        // return the pdf output as per the destination setting
        return $pdf->render();*/
        $pdf = Yii::$app->pdf; // or new Pdf();
        $mpdf = $pdf->api; // fetches mpdf api
        //$mpdf->cssInline('table, td, th {border: 1px solid black;} .kv-heading-1{font-size:18px}');
        $mpdf->SetHeader('Prefactura-Electronica'.date('Y-m-d')); // call methods or set any properties
        $mpdf->SetFooter('{PAGENO}');
        $mpdf->WriteHtml($content); // call mpdf write html
        echo $mpdf->Output('Prefactura'.date('Y-m-d').'.pdf', 'D'); // call the mpdf api output as needed


    }

    /**
     * Creates a new PrefacturaElectronica model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PrefacturaElectronica();
        date_default_timezone_set ( 'America/Bogota');
        $fecha_actual = date('Y-m-d H:i:s');
        $dependencias = CentroCosto::find()->where(['not in', 'estado', ['C']])->orderBy(['nombre' => SORT_ASC])->all();    
        $empresas = Empresa::find()->orderBy(['nombre' => SORT_ASC])->all();
        $zonas = Zona::find()->all();
        Yii::$app->session->setTimeout(5400);               
        $usuario = Usuario::findOne(Yii::$app->session['usuario-exito']);
        $zonasUsuario = array();
        $marcasUsuario = array();
        $distritosUsuario = array();
        $empresasUsuario = array();
    
        $filas = array();
        
        if($usuario != null){
          $zonasUsuario = $usuario->zonas;      
          $marcasUsuario = $usuario->marcas;
          $distritosUsuario = $usuario->distritos;        
          $empresasUsuario = $usuario->empresas;

        }

        if ($model->load(Yii::$app->request->post()) ) {

            $model->setAttribute('usuario', Yii::$app->session['usuario-exito']);
            $model->setAttribute('created', $fecha_actual);
            $model->setAttribute('updated', $fecha_actual);
            $model->setAttribute('estado', 'abierto');
            $model->setAttribute('nombre_factura', $_POST['nombre_factura']);
            //buscar la dependencia
            $dependencia = CentroCosto::findOne($model->centro_costo_codigo);
            $zona = Zona::findOne($model->regional);
            $model->setAttribute('ciudad', $dependencia->ciudad->nombre);
            $model->setAttribute('marca', $dependencia->marca->nombre);
            $model->setAttribute('empresa', $dependencia->empresa_electronica);
            $model->setAttribute('regional', $zona->nombre);

            $prefactura = PrefacturaElectronica::find()->where(['ano' => $model->ano,'mes' => $model->mes,'centro_costo_codigo' => $model->centro_costo_codigo])->one();

            //if($prefactura == null){

                $modelo_prefactura = ModeloPrefacturaElectronica::find()->where("centro_costos_codigo='".$model->centro_costo_codigo."'")->all();
                $modelo_monitoreo=ModeloMonitoreo::find()->where("centro_costo_codigo='".$model->centro_costo_codigo."'")->all();

                if(count($modelo_prefactura)>0){
                    $model->save();
                    foreach ($modelo_prefactura as $mp) {

                        $pd = new PrefacturaDispositivoFijoElectronico();

                        $pd->setAttribute('estado', $mp->estado);
                        $pd->setAttribute('sistema', $mp->sistema);
                        $pd->setAttribute('id_tipo_alarma', $mp->id_tipo_alarma);
                        $pd->setAttribute('id_marca', $mp->id_marca);
                        $pd->setAttribute('referencia', $mp->referencia);
                        $pd->setAttribute('ubicacion', $mp->ubicacion);
                        $pd->setAttribute('zona_panel', $mp->zona_panel);
                        $pd->setAttribute('meses_pactados', $mp->meses_pactados);
                        $pd->setAttribute('fecha_inicio', $mp->fecha_inicio);
                        $pd->setAttribute('fecha_ultima_reposicion', $mp->fecha_ultima_reposicion);
                        $pd->setAttribute('valor_arrendamiento_mensual', $mp->valor_arrendamiento_mensual);
                        $pd->setAttribute('centro_costos_codigo', $mp->centro_costos_codigo);
                        $pd->setAttribute('id_desc', $mp->id_desc);
                        $pd->setAttribute('id_prefactura_electronica', $model->id);
                        $pd->setAttribute('detalle_ubicacion', $mp->detalle_ubicacion);
                       // $pd->setAttribute('id_empresa', $mp->empresa);

                        if(!$pd->save()){
                            print_r($pd->getErrors());exit();
                        }

                    }

                    foreach($modelo_monitoreo as $mm){
                        $pm=new PrefacturaMonitoreo();

                        $pm->setAttribute('monitoreo', $mm->monitoreo);
                        $pm->setAttribute('id_sistema_monitoreo', $mm->id_sistema_monitoreo);
                        $pm->setAttribute('cantidad_servicios', $mm->cantidad_servicios);
                        $pm->setAttribute('valor_unitario', $mm->valor_unitario);
                        $pm->setAttribute('fecha_inicio', $mm->fecha_inicio);
                        $pm->setAttribute('fecha_fin', $mm->fecha_fin);
                        $pm->setAttribute('valor_total', $mm->valor_total);
                        $pm->setAttribute('centro_costo_codigo', $mm->centro_costo_codigo);
                        //$pm->setAttribute('id_empresa', $mm->id_empresa);
                        $pm->setAttribute('id_prefactura_electronica', $model->id);

                        if(!$pm->save()){
                            print_r($pd->getErrors());exit();
                        }
                    }
                    return $this->redirect(['view', 'id' => $model->id ]);

                }else{

                    $mensaje='No se encontraron "DISPOSITIVOS FIJOS", Por favor, configure en la Dependencia los dispositivos fijos.';

                }

            // }else{

            //     $mensaje='Ya se encuentra creada una Pre-factura en esta dependencia para este tiempo';

            // }


            return $this->render('create', [
                'model' => $model,
                'zonas' => $zonas,
                'dependencias' => $dependencias,
                'empresas' => $empresas,
                'zonasUsuario' => $zonasUsuario,
                'marcasUsuario' => $marcasUsuario,
                'distritosUsuario' => $distritosUsuario,                
                'empresasUsuario' => $empresasUsuario,
                'mensaje' => $mensaje,
            ]);



           
        } else {
            return $this->render('create', [
                'model' => $model,
                'zonas' => $zonas,
                'dependencias' => $dependencias,
                'empresas' => $empresas,
                'zonasUsuario' => $zonasUsuario,
                'marcasUsuario' => $marcasUsuario,
                'distritosUsuario' => $distritosUsuario,                
                'empresasUsuario' => $empresasUsuario,
            ]);
        }
    }

    public function actionExiste_factura(){
        $prefactura = PrefacturaElectronica::find()->where(['ano' => $_POST['ano'],'mes' => $_POST['mes'],'centro_costo_codigo' => $_POST['dependencia']])->one();

        $existe=0;

        if (!$prefactura == null) {
            $existe=1;
        }


        $arreglo=array('respuesta'=>$existe);

        return json_encode($arreglo);

    }






    function actionCreatevariable($id){

        //$this->layout = 'main_sin_menu';
        Yii::$app->session->setTimeout(5400);
        $array_post = Yii::$app->request->post();
        $roles      = Yii::$app->session['rol-exito'];
        $model      = new PrefacturaDispositivoVariableElectronico();

        $tipos_alarma=TipoAlarma::find()->orderBy(['nombre' => SORT_ASC])->all();
        $list_alarmas=ArrayHelper::map($tipos_alarma,'id','nombre');


        $marcas_alarma=MarcaAlarma::find()->orderBy(['nombre' => SORT_ASC])->all();
        $list_marcas_alarmas=ArrayHelper::map($marcas_alarma,'id','nombre');        

        $areas=AreaDependencia::find()->all();
        $list_areas=ArrayHelper::map($areas,'id','nombre');


        $servicios=TipoServicioElectronica::find()->all();
        $list_servicios=ArrayHelper::map($servicios,'id','nombre'); 


        // $empresas=Empresa::find()->where(['seguridad_electronica'=>'S'])->all();
        // $list_empresas=ArrayHelper::map($empresas,'nit','nombre');       


        if ($model->load($array_post)) {
            $pf = PrefacturaElectronica::findOne($id);

            
            $model->setAttribute('fecha_inicio', $array_post['fecha_inicio']);
            $model->setAttribute('fecha_fin', $array_post['fecha_fin']);
            $model->setAttribute('valor_novedad', $array_post['prefacturadispositivovariableelectronico-valor_novedad-disp']);
            $model->setAttribute('centro_costos_codigo', $pf->centro_costo_codigo);
            $model->setAttribute('id_prefactura_electronica', $id);

            $model->save();
            Yii::$app->session->setFlash('success','Dispositivo creado correctamente');
            return $this->redirect(['view', 'id' => $id]);

        }else{

            return $this->render('crear_variable', [
                    'codigo_dependencia' => $id,
                    'model'              => $model,
                    'alarmas'=>$list_alarmas,
                    'marcas_alarma'=>$list_marcas_alarmas,
                    'areas'=>$list_areas,
                    'servicios'=>$list_servicios,
                   // 'empresas'=>$list_empresas
                    ]);
        }


    }




    /**
     * Updates an existing PrefacturaElectronica model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate()
    {
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
     * Deletes an existing PrefacturaElectronica model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success','Prefactura eliminada correctamente');
        return $this->redirect(['index']);
    }


    public function actionDeletedispositivo($id_disp,$id){
        $model= PrefacturaDispositivoVariableElectronico::findOne($id_disp);
        $model->delete();
        Yii::$app->session->setFlash('success','Dispositivo eliminado correctamente');
        return $this->redirect(['view', 'id' => $id]);
    } 

    /**
     * Finds the PrefacturaElectronica model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PrefacturaElectronica the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function  actionAbrir_pref($id){
        $model = $this->findModel($id);
        $model->setAttribute('estado','abierto');
        $model->save();
        return $this->redirect(['view','id'=>$id]);
    }

    protected function findModel($id)
    {
        if (($model = PrefacturaElectronica::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
