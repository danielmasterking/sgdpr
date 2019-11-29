<?php

namespace app\controllers;

use Yii;
use app\models\PrefacturaElectronica;
use app\models\PrefacturaFija;
use app\models\ModeloPrefactura;
use app\models\PrefacturaDispositivo;
use app\models\Dia;
use app\models\Zona;
use app\models\Jornada;
use app\models\DetalleServicio;
use app\models\Empresa;
use app\models\CentroCosto;
use app\models\Usuario;
use app\models\Puesto;
use app\models\UsuarioEmpresa;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use kartik\mpdf\Pdf;
use app\models\GruposPrefactura;
use yii\helpers\ArrayHelper;
use app\models\AdminDispositivo;
use app\models\AdminDependencia;
use app\models\Pedido;
use app\models\Ciudad;
use yii\data\Pagination;
class PrefacturaFijaController extends Controller
{
    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['index', 'view', 'cargar', 'create', 'update', 'delete','ventana_inicio','informedispositivos'],
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['index', 'view', 'cargar', 'create', 'update', 'delete','ventana_inicio','informedispositivos'],
                        'roles'   => ['@'], //para usuarios logueados
                    ],
                ],  
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                   // 'delete' => ['GET'],
                    'update' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex(){
        
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





        // if(in_array("prefactura-regional", $permisos) ){
        if(in_array("administrador", $permisos) ||  in_array("prefactura-analista", $permisos) ){
             $rows = (new \yii\db\Query())
            ->select(['dp.nombre_factura','dp.id id', 'DATE(dp.created) fecha','dp.mes mes','dp.ano ano','dp.usuario usuario','cc.nombre dependencia','em.nombre empresa','dp.estado estado','(
    (SELECT SUM(ftes) 
    FROM  prefactura_dispositivo 
     WHERE    id_prefactura_fija=dp.id and tipo="fijo"
    )

    +
    (
    (SELECT COALESCE(SUM(ftes) ,0)
    FROM  prefactura_dispositivo 
     WHERE   tipo_servicio <>"No Prestado"   and id_prefactura_fija=dp.id and tipo="variable"
    ) -(SELECT COALESCE(SUM(ftes) ,0)
    FROM  prefactura_dispositivo 
    WHERE   tipo_servicio="No Prestado" and id_prefactura_fija=dp.id and tipo="variable"
    ) ) )total_ftes','

    (
    (SELECT SUM(valor_mes) 
    FROM  prefactura_dispositivo 
     WHERE    id_prefactura_fija=dp.id and tipo="fijo"
    )

    +
    (
    (SELECT COALESCE(SUM(valor_mes) ,0)
    FROM  prefactura_dispositivo 
     WHERE   tipo_servicio <>"No Prestado"   and id_prefactura_fija=dp.id and tipo="variable"
    ) -(SELECT COALESCE(SUM(valor_mes) ,0)
    FROM  prefactura_dispositivo 
    WHERE   tipo_servicio="No Prestado" and id_prefactura_fija=dp.id and tipo="variable"
    ) ) )total_mes

    ','em.nit','dp.numero_factura','dp.created','cc.ceco',
    '(SELECT COUNT(id) FROM prefactura_dispositivo WHERE id_prefactura_fija=dp.id)cantidad_servicios',
    '(SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(horas))) AS hours FROM prefactura_dispositivo WHERE id_prefactura_fija=dp.id) horas',
    
    '
    (


        (SELECT SUM(ftes_diurno) 
            FROM  prefactura_dispositivo 
            WHERE    id_prefactura_fija=dp.id and tipo="fijo"
        )

        +

        (
            (SELECT COALESCE(SUM(ftes_diurno) ,0)
            FROM  prefactura_dispositivo 
             WHERE   tipo_servicio <>"No Prestado"   and id_prefactura_fija=dp.id and tipo="variable"
            ) -(SELECT COALESCE(SUM(ftes_diurno) ,0)
            FROM  prefactura_dispositivo 
            WHERE   tipo_servicio="No Prestado" and id_prefactura_fija=dp.id and tipo="variable"
            ) 
        ) 
    ) ftes_diurnos

    ',
    '

    (


        (SELECT SUM(ftes_nocturno) 
            FROM  prefactura_dispositivo 
            WHERE    id_prefactura_fija=dp.id and tipo="fijo"
        )

        +

        (
            (SELECT COALESCE(SUM(ftes_nocturno) ,0)
            FROM  prefactura_dispositivo 
             WHERE   tipo_servicio <>"No Prestado"   and id_prefactura_fija=dp.id and tipo="variable"
            ) -(SELECT COALESCE(SUM(ftes_nocturno) ,0)
            FROM  prefactura_dispositivo 
            WHERE   tipo_servicio="No Prestado" and id_prefactura_fija=dp.id and tipo="variable"
            ) 
        ) 
    ) ftes_nocturnos



    ','dp.ciudad','dp.marca','(SELECT SUM(ftes) FROM prefactura_dispositivo WHERE  id_prefactura_fija=dp.id and tipo="fijo") ftes_fijos'
    ,'(
        (SELECT COALESCE(SUM(ftes) ,0) FROM prefactura_dispositivo WHERE tipo_servicio <>"No Prestado" and id_prefactura_fija=dp.id and tipo="variable") 
        -
        (SELECT COALESCE(SUM(ftes) ,0) FROM prefactura_dispositivo WHERE tipo_servicio ="No Prestado" and id_prefactura_fija=dp.id and tipo="variable") 
       )ftes_variables','(SELECT SUM(valor_mes) 
    FROM  prefactura_dispositivo 
     WHERE    id_prefactura_fija=dp.id and tipo="fijo"
    ) total_fijo','(
    (SELECT COALESCE(SUM(valor_mes) ,0)
    FROM  prefactura_dispositivo 
     WHERE   tipo_servicio <>"No Prestado"   and id_prefactura_fija=dp.id and tipo="variable"
    ) -(SELECT COALESCE(SUM(valor_mes) ,0)
    FROM  prefactura_dispositivo 
    WHERE   tipo_servicio="No Prestado" and id_prefactura_fija=dp.id and tipo="variable"
    ) ) total_variable','dp.numero_factura','dp.fecha_factura','dp.regional','dp.ciudad','cc.ceco','em.nit'])
            ->from('prefactura_fija dp, centro_costo cc, empresa em')
            ->where('dp.centro_costo_codigo=cc.codigo AND dp.empresa=em.nit');
    //}elseif(in_array("administrador", $permisos) ||  in_array("prefactura-analista", $permisos) ){
    }elseif(in_array("prefactura-regional", $permisos)){
            $rows = (new \yii\db\Query())
            ->select(['dp.nombre_factura','dp.id id', 'DATE(dp.created) fecha','dp.mes mes','dp.ano ano','dp.usuario usuario','cc.nombre dependencia','em.nombre empresa','dp.estado estado','(
    (SELECT SUM(ftes) 
    FROM  prefactura_dispositivo 
     WHERE    id_prefactura_fija=dp.id and tipo="fijo"
    )

    +
    (
    (SELECT COALESCE(SUM(ftes) ,0)
    FROM  prefactura_dispositivo 
     WHERE   tipo_servicio <>"No Prestado"   and id_prefactura_fija=dp.id and tipo="variable"
    ) -(SELECT COALESCE(SUM(ftes) ,0)
    FROM  prefactura_dispositivo 
    WHERE   tipo_servicio="No Prestado" and id_prefactura_fija=dp.id and tipo="variable"
    ) ) )total_ftes','

    (
    (SELECT SUM(valor_mes) 
    FROM  prefactura_dispositivo 
     WHERE    id_prefactura_fija=dp.id and tipo="fijo"
    )

    +
    (
    (SELECT COALESCE(SUM(valor_mes) ,0)
    FROM  prefactura_dispositivo 
     WHERE   tipo_servicio <>"No Prestado"   and id_prefactura_fija=dp.id and tipo="variable"
    ) -(SELECT COALESCE(SUM(valor_mes) ,0)
    FROM  prefactura_dispositivo 
    WHERE   tipo_servicio="No Prestado" and id_prefactura_fija=dp.id and tipo="variable"
    ) ) )total_mes

    ','em.nit','dp.numero_factura','dp.created','cc.ceco',
    '(SELECT COUNT(id) FROM prefactura_dispositivo WHERE id_prefactura_fija=dp.id)cantidad_servicios',
    '(SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(horas))) AS hours FROM prefactura_dispositivo WHERE id_prefactura_fija=dp.id) horas',
    
    '
    (


        (SELECT SUM(ftes_diurno) 
            FROM  prefactura_dispositivo 
            WHERE    id_prefactura_fija=dp.id and tipo="fijo"
        )

        +

        (
            (SELECT COALESCE(SUM(ftes_diurno) ,0)
            FROM  prefactura_dispositivo 
             WHERE   tipo_servicio <>"No Prestado"   and id_prefactura_fija=dp.id and tipo="variable"
            ) -(SELECT COALESCE(SUM(ftes_diurno) ,0)
            FROM  prefactura_dispositivo 
            WHERE   tipo_servicio="No Prestado" and id_prefactura_fija=dp.id and tipo="variable"
            ) 
        ) 
    ) ftes_diurnos

    ',
    '

    (


        (SELECT SUM(ftes_nocturno) 
            FROM  prefactura_dispositivo 
            WHERE    id_prefactura_fija=dp.id and tipo="fijo"
        )

        +

        (
            (SELECT COALESCE(SUM(ftes_nocturno) ,0)
            FROM  prefactura_dispositivo 
             WHERE   tipo_servicio <>"No Prestado"   and id_prefactura_fija=dp.id and tipo="variable"
            ) -(SELECT COALESCE(SUM(ftes_nocturno) ,0)
            FROM  prefactura_dispositivo 
            WHERE   tipo_servicio="No Prestado" and id_prefactura_fija=dp.id and tipo="variable"
            ) 
        ) 
    ) ftes_nocturnos



    ','dp.ciudad','dp.marca','(SELECT SUM(ftes) FROM prefactura_dispositivo WHERE  id_prefactura_fija=dp.id and tipo="fijo") ftes_fijos','(
        (SELECT COALESCE(SUM(ftes) ,0) FROM prefactura_dispositivo WHERE tipo_servicio <>"No Prestado" and id_prefactura_fija=dp.id and tipo="variable") 
        -
        (SELECT COALESCE(SUM(ftes) ,0) FROM prefactura_dispositivo WHERE tipo_servicio ="No Prestado" and id_prefactura_fija=dp.id and tipo="variable") 
       )ftes_variables','(SELECT SUM(valor_mes) 
    FROM  prefactura_dispositivo 
     WHERE    id_prefactura_fija=dp.id and tipo="fijo"
    ) total_fijo','(
    (SELECT COALESCE(SUM(valor_mes) ,0)
    FROM  prefactura_dispositivo 
     WHERE   tipo_servicio <>"No Prestado"   and id_prefactura_fija=dp.id and tipo="variable"
    ) -(SELECT COALESCE(SUM(valor_mes) ,0)
    FROM  prefactura_dispositivo 
    WHERE   tipo_servicio="No Prestado" and id_prefactura_fija=dp.id and tipo="variable"
    ) ) total_variable','dp.numero_factura','dp.fecha_factura','dp.regional','dp.ciudad','cc.ceco','em.nit'])
            ->from('prefactura_fija dp, centro_costo cc, empresa em')
            ->where('dp.centro_costo_codigo=cc.codigo AND dp.empresa=em.nit AND ( dp.centro_costo_codigo '.$in_final.' ) ');

            
        }else{

            /////////////////////////////////////////////////////////
              $rows = (new \yii\db\Query())
            ->select(['dp.nombre_factura','dp.id id', 'DATE(dp.created) fecha','dp.mes mes','dp.ano ano','dp.usuario usuario','cc.nombre dependencia','em.nombre empresa','dp.estado estado','(
    (SELECT SUM(ftes) 
    FROM  prefactura_dispositivo 
     WHERE    id_prefactura_fija=dp.id and tipo="fijo"
    )

    +
    (
    (SELECT COALESCE(SUM(ftes) ,0)
    FROM  prefactura_dispositivo 
     WHERE   tipo_servicio <>"No Prestado"   and id_prefactura_fija=dp.id and tipo="variable"
    ) -(SELECT COALESCE(SUM(ftes) ,0)
    FROM  prefactura_dispositivo 
    WHERE   tipo_servicio="No Prestado" and id_prefactura_fija=dp.id and tipo="variable"
    ) ) )total_ftes','

    (
    (SELECT SUM(valor_mes) 
    FROM  prefactura_dispositivo 
     WHERE    id_prefactura_fija=dp.id and tipo="fijo"
    )

    +
    (
    (SELECT COALESCE(SUM(valor_mes) ,0)
    FROM  prefactura_dispositivo 
     WHERE   tipo_servicio <>"No Prestado"   and id_prefactura_fija=dp.id and tipo="variable"
    ) -(SELECT COALESCE(SUM(valor_mes) ,0)
    FROM  prefactura_dispositivo 
    WHERE   tipo_servicio="No Prestado" and id_prefactura_fija=dp.id and tipo="variable"
    ) ) )total_mes

    ','em.nit','dp.numero_factura','dp.created','cc.ceco',
    '(SELECT COUNT(id) FROM prefactura_dispositivo WHERE id_prefactura_fija=dp.id)cantidad_servicios',
    '(SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(horas))) AS hours FROM prefactura_dispositivo WHERE id_prefactura_fija=dp.id) horas',
    
    '
    (


        (SELECT SUM(ftes_diurno) 
            FROM  prefactura_dispositivo 
            WHERE    id_prefactura_fija=dp.id and tipo="fijo"
        )

        +

        (
            (SELECT COALESCE(SUM(ftes_diurno) ,0)
            FROM  prefactura_dispositivo 
             WHERE   tipo_servicio <>"No Prestado"   and id_prefactura_fija=dp.id and tipo="variable"
            ) -(SELECT COALESCE(SUM(ftes_diurno) ,0)
            FROM  prefactura_dispositivo 
            WHERE   tipo_servicio="No Prestado" and id_prefactura_fija=dp.id and tipo="variable"
            ) 
        ) 
    ) ftes_diurnos

    ',
    '

    (


        (SELECT SUM(ftes_nocturno) 
            FROM  prefactura_dispositivo 
            WHERE    id_prefactura_fija=dp.id and tipo="fijo"
        )

        +

        (
            (SELECT COALESCE(SUM(ftes_nocturno) ,0)
            FROM  prefactura_dispositivo 
             WHERE   tipo_servicio <>"No Prestado"   and id_prefactura_fija=dp.id and tipo="variable"
            ) -(SELECT COALESCE(SUM(ftes_nocturno) ,0)
            FROM  prefactura_dispositivo 
            WHERE   tipo_servicio="No Prestado" and id_prefactura_fija=dp.id and tipo="variable"
            ) 
        ) 
    ) ftes_nocturnos



    ','dp.ciudad','dp.marca','(SELECT SUM(ftes) FROM prefactura_dispositivo WHERE  id_prefactura_fija=dp.id and tipo="fijo") ftes_fijos','(
        (SELECT COALESCE(SUM(ftes) ,0) FROM prefactura_dispositivo WHERE tipo_servicio <>"No Prestado" and id_prefactura_fija=dp.id and tipo="variable") 
        -
        (SELECT COALESCE(SUM(ftes) ,0) FROM prefactura_dispositivo WHERE tipo_servicio ="No Prestado" and id_prefactura_fija=dp.id and tipo="variable") 
       )ftes_variables','(SELECT SUM(valor_mes) 
    FROM  prefactura_dispositivo 
     WHERE    id_prefactura_fija=dp.id and tipo="fijo"
    ) total_fijo','(
    (SELECT COALESCE(SUM(valor_mes) ,0)
    FROM  prefactura_dispositivo 
     WHERE   tipo_servicio <>"No Prestado"   and id_prefactura_fija=dp.id and tipo="variable"
    ) -(SELECT COALESCE(SUM(valor_mes) ,0)
    FROM  prefactura_dispositivo 
    WHERE   tipo_servicio="No Prestado" and id_prefactura_fija=dp.id and tipo="variable"
    ) ) total_variable','dp.numero_factura','dp.fecha_factura','dp.regional','dp.ciudad','cc.ceco','em.nit'])
            ->from('prefactura_fija dp, centro_costo cc, empresa em')
            ->where('dp.centro_costo_codigo=cc.codigo AND dp.empresa=em.nit AND ( dp.usuario="'.Yii::$app->session['usuario-exito'].'" ) ');
            /////////////////////////////////////////////////////////

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
                $rows->andWhere("dp.mes like '%". $buscar."%' OR dp.ano like '%".$buscar."%' OR em.nombre like '%".$buscar."%' OR dp.usuario like '%".$buscar."%' OR dp.nombre_factura like '%".$buscar."%'  OR dp.numero_factura like '%".$buscar."%' OR cc.ceco like '%".$buscar."%'");
                if($dependencia!=''){
                    $rows->andWhere("cc.nombre like '%".$dependencia."%'");
                }
            }else if(trim($_POST['dependencias2'])!='' && trim($_POST['dependencias2'])!='0'){
                $rows->andWhere("cc.nombre like '%".$_POST['dependencias2']."%'");
            }
        }

        //BUSQUEDA POR MARCA

        

        if (isset($_POST['marca'])) {
            $rows->andWhere("dp.marca like '%".$_POST['marca']."%'");
        }

        if (trim($_POST['empresa'])!='') {
            $rows->andWhere("dp.empresa ='".$_POST['empresa']."'");
        }

        if (trim($_POST['mes'])!='') {
            $rows->andWhere("dp.mes like '%".$_POST['mes']."%'");
        }

        if (trim($_POST['ano'])!='') {
            $rows->andWhere("dp.ano ='".$_POST['ano']."'");
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
                'columns' => [
                   'numero_factura',
                   'fecha_factura',
                   'nombre_factura',
                   'fecha',
                   'mes',
                   'ano',
                   'usuario',
                   'regional',
                   'ciudad',
                   'ceco',
                   'dependencia',
                   'nit',
                   'empresa',
                   'ftes_fijos',
                   'ftes_variables',
                   'total_ftes',
                   'total_fijo',
                   'total_variable',
                   'total_mes'
                    
                    
                ],
                'headers' => [
                    'numero_factura'=>'Numero Factura',
                    'fecha_factura'=>'Fecha Factura',
                    'nombre_factura'=>'Nombre Factura',
                    'fecha'=>'Fecha Creacion',
                    'mes'=>'Mes',
                    'ano'=>'Año',
                    'usuario'=>'Usuario',
                    'regional'=>'Regional',
                    'ciudad'=>'Ciudad',
                    'ceco'=>'Ceco',
                    'dependencia'=>'dependencia',
                    'nit'=>'Nit',
                    'empresa'=>'empresa',
                    'ftes_fijos'=>'Ftes Fijos',
                    'ftes_variables'=>'Ftes Variables',
                    'total_ftes'=>'Total Ftes',
                    'total_fijo'=>'Total Fijo',
                    'total_variable'=>'Total Variable',
                    'total_mes'=>'Total Servicio'
                    

                ], 
            ]);
        }
        $modelcount = $rowsCount->count();
        $no_of_paginations = ceil($modelcount / $per_page);
        $res='';
        $model_dispositivo=new PrefacturaDispositivo();
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

    public function actionVentana_inicio(){

        $zonas=Zona::find()->all();
        $regionales=array();
        foreach ($zonas  as $row_zona) {
            $prefactura_fija=PrefacturaFija::find()->where('regional="'.$row_zona->nombre.'"  ')->count();
            $prefactura_elct=PrefacturaElectronica::find()->where('regional="'.$row_zona->nombre.'"  ')->count();
            $suma=$prefactura_fija+$prefactura_elct;
            $arreglo=array($row_zona->nombre,$suma);
            array_push($regionales, $arreglo);
        }
        $json=json_encode($regionales);
        // echo "<pre>";
        // print_r($json);
        // echo "</pre>";



        return $this->render('ventana_inicio', [
            'json'=>$json,
            
        ]);
    }

    public function actionInformedispositivos(){

        $permisos = array();
        if( isset(Yii::$app->session['permisos-exito']) ){
            $permisos = Yii::$app->session['permisos-exito'];
        }
        $usuario = Usuario::findOne(Yii::$app->session['usuario-exito']);
        $zonas = Zona::find()->all();
        $dependencias = CentroCosto::find()->where(['not in', 'estado', ['C']])->orderBy(['nombre' => SORT_ASC])->all();
        $empresas = Empresa::find()->orderBy(['nombre' => SORT_ASC])->all();
        $list_empresas=ArrayHelper::map($empresas,'nit','nombre');
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

        $list_zonas=ArrayHelper::map($zonasUsuario,'zona.nombre','zona.nombre');


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



        $empresas_usuario=$this->usuario_empresas(Yii::$app->session['usuario-exito']);
        if (count($empresas_usuario)>0) {
             
            $in_emp="IN(";
            foreach ($empresas_usuario as $emp) {
                $in_emp.=" '".$emp."',";       
            }

            $in_empresa = substr($in_emp, 0, -1).")";
            
        }else{

            $in_empresa='';
        }
       



        //////////////////////////////////

        $rows = (new \yii\db\Query())
        ->select(['dp.nombre_factura','dp.mes', 'dp.ano','cc.nombre dependencia','dp.empresa nit','em.nombre empresa_seg','dp.estado',
            '

                (
                    CASE 

                        WHEN  pd.tipo_servicio="No Prestado" THEN

                            CONCAT("-", "", pd.ftes)
                        ELSE  pd.ftes
                        

                    END
                        
                )ftes

            ',
            '
                (
                    CASE 

                        WHEN  pd.tipo_servicio="No Prestado" THEN

                            CONCAT("-", "", pd.valor_mes)
                        ELSE  pd.valor_mes
                        

                    END
                        
                )valor_total_mes

            ','dp.numero_factura','dp.fecha_factura',
            '(
                CASE
                WHEN SUBSTRING(cc.ceco,1,1) =3 THEN 533505001 
                

                ELSE  523505001
                END

             )cuenta_contable'
            ,'cc.ceco','dp.ciudad','m.nombre marca','pu.nombre puesto',
            'pd.cantidad_servicios','pd.horas','pd.lunes','pd.martes','pd.miercoles','pd.jueves','pd.viernes','pd.sabado','pd.domingo','pd.festivo','pd.tipo_servicio','pd.hora_inicio','pd.hora_fin','pd.total_dias',
                '
                (
                    CASE 

                        WHEN  pd.tipo_servicio="No Prestado" THEN

                            CONCAT("-", "", pd.ftes_diurno)
                        ELSE  pd.ftes_diurno
                        

                    END
                        
                )ftes_diurno
                
                ',
                '
                (
                    CASE 

                        WHEN  pd.tipo_servicio="No Prestado" THEN

                            CONCAT("-", "", pd.ftes_nocturno)
                        ELSE  pd.ftes_nocturno
                        

                    END
                        
                )ftes_nocturno
                
                ',
                'pd.explicacion','

                    (
                        CASE 

                        WHEN  pd.tipo_servicio="No Prestado" THEN
                            
                            CONCAT("-", "", ((pd.ftes_diurno*pd.valor_mes)/pd.ftes) )

                        ELSE  ((pd.ftes_diurno*pd.valor_mes)/pd.ftes)
                        END


                    )valor_serv_diurno


                ','
                    (
                        CASE 

                        WHEN  pd.tipo_servicio="No Prestado" THEN
                            
                            CONCAT("-", "", ((pd.ftes_nocturno*pd.valor_mes)/pd.ftes) )

                        ELSE  ((pd.ftes_nocturno*pd.valor_mes)/pd.ftes)
                        END


                    )valor_serv_nocturno

                    ','pd.tipo',
            'dp.regional'/*,'pd.tipo_servicio'*/,'pd.id as id_disp','CONCAT(serv.nombre,"-", ds.descripcion) as servicio_disp','CAST(null AS CHAR) as id_admin_dep'/*,"CAST(null AS CHAR) as num_dep"*/
        ])
        ->from('prefactura_dispositivo pd')
        ->innerJoin('prefactura_fija  dp', 'pd.id_prefactura_fija=dp.id')
        ->innerJoin('centro_costo  cc', 'dp.centro_costo_codigo=cc.codigo')
        ->innerJoin('marca  m', 'cc.marca_id=m.id')
        ->innerJoin('empresa  em', 'dp.empresa=em.nit')
        ->innerJoin('puesto  pu', 'pd.puesto_id=pu.id')
        ->leftJoin('detalle_servicio  ds', 'pd.detalle_servicio_id=ds.id')
        ->leftJoin('servicio  serv', 'ds.servicio_id=serv.id')
        ->where('(dp.estado="cerrado") AND ( dp.centro_costo_codigo '.$in_final.' ) AND (dp.empresa '.$in_empresa.') ');
        

        $rows1=(new \yii\db\Query())
        ->select(["CAST(null AS CHAR) as nombre_factura",'as.mes','as.ano','cc.nombre dependencia','as.empresa nit','em.nombre empresa_seg','as.estado',"/*ROUND((da.ftes_dependencia* da.cantidad),3)*/ da.ftes_dependencia as ftes/*da.ftes*/",'da.precio_dependencia as valor_mes','as.numero_factura','as.fecha_factura',
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
            ) regional','da.id as id_disp','CAST(null AS CHAR) as servicio_disp','ad.id id_admin_dep'])
            ->from('admin_supervision  as'/*'admin_dependencia ad','admin_dispositivo da'*/)
            //->innerJoin('admin_supervision  as', 'ad.id_admin=as.id')
            ->innerJoin('admin_dependencia ad', 'as.id=ad.id_admin')
            ->innerJoin('admin_dispositivo da', 'as.id=da.id_admin')
            ->leftJoin('centro_costo  cc', 'ad.centro_costos_codigo=cc.codigo')
            ->leftJoin('marca  m', 'cc.marca_id=m.id')
            ->leftJoin('ciudad  c', 'cc.ciudad_codigo_dane=c.codigo_dane')
            ->leftJoin('empresa  em', 'as.empresa=em.nit')
            ->where('(as.estado="cerrado") AND ( ad.centro_costos_codigo '.$in_final.' ) AND (as.empresa '.$in_empresa.')');
        
        
        if(isset($_POST['desde'])){
            if($_POST['desde']!="" && $_POST['hasta']!=""){
                $rows->andWhere("DATE(dp.created) between '".$_POST['desde']."' AND '".$_POST['hasta']."'");

                $rows1->andWhere("DATE(as.created) between '".$_POST['desde']."' AND '".$_POST['hasta']."'");
            }
        }
        if(isset($_POST['buscar'])){
            if (trim($_POST['buscar'])!='') {
                $buscar=trim($_POST['buscar']);
                //echo $buscar;
                /*$dependencia='';
                if(trim($_POST['dependencias2'])!='' && trim($_POST['dependencias2'])!='0'){
                    $dependencia=trim($_POST['dependencias2']);
                }*/

                $rows->andWhere("dp.mes like '%".$buscar."%' OR dp.ano like '%".$_POST['buscar']."%' OR em.nombre like '%".$buscar."%' OR dp.usuario like '%".$buscar."%' OR dp.numero_factura = '".$buscar."' OR dp.fecha_factura = '".$buscar."' OR pd.tipo like '%".$buscar."%'  ");


                $rows1->andWhere("as.mes like '%".$buscar."%' OR as.ano like '%".$buscar."%' OR em.nombre like '%".$buscar."%' OR as.usuario like '%".$buscar."%' OR as.numero_factura = '".$buscar."' OR fecha_factura = '%".$buscar."%' OR 
                    (SELECT CAST('Administracion y supervision' AS CHAR) as tipo)like '%".$buscar."%'

                    ");



                 /*if($dependencia!=''){
                     $rows->andWhere("cc.nombre like '%".$dependencia."%'");
                     $rows1->andWhere("cc.nombre like '%".$dependencia."%'");
                 }*/
            }
            // }else if(trim($_POST['dependencias2'])!='' && trim($_POST['dependencias2'])!='0'){
            //     $rows->andWhere("cc.codigo like '%".$_POST['dependencias2']."%'");
            //     $rows1->andWhere("cc.codigo like '%".$_POST['dependencias2']."%'");

            // }
        }

        if(trim($_POST['dependencias2'])!='' && trim($_POST['dependencias2'])!='0' && isset($_POST['dependencias2'])){
            $rows->andWhere("cc.nombre like '%".$_POST['dependencias2']."%'");
            $rows1->andWhere("cc.nombre like '%".$_POST['dependencias2']."%'"); 
        }


        //BUSQUEDA POR MARCA
        if (trim($_POST['marca'])!='' && isset($_POST['marca'])) {
            $rows->andWhere("dp.marca like '%".$_POST['marca']."%'");
            $rows1->andWhere("m.nombre like '%".$_POST['marca']."%'");
        }


        if (trim($_POST['mes'])!='' && isset($_POST['mes']) ) {
            $rows->andWhere("dp.mes like '%".$_POST['mes']."%'");
            $rows1->andWhere("as.mes like '%".$_POST['mes']."%'");
        }

        if (trim($_POST['empresas'])!='' && isset($_POST['empresas'])) {

            $rows->andWhere("(dp.empresa ='".$_POST['empresas']."')");
            $rows1->andWhere("(as.empresa ='".$_POST['empresas']."')");
            //$rows1->andWhere("m.nombre like '%".$_POST['marca']."%'");
        }

        if (trim($_POST['regional'])!='' && isset($_POST['regional'])) {
            //echo $_POST['regional'];

            $rows->andWhere("dp.regional = '".$_POST['regional']."' ");
            $rows1->andWhere("(
            select zona.nombre  from centro_costo cco
            inner join ciudad_zona cz on cco.ciudad_codigo_dane=cz.ciudad_codigo_dane
            inner join zona on  cz.zona_id=zona.id
            WHERE cco.codigo=cc.codigo limit 1
            ) ='".$_POST['regional']."' ");
            
        }

        if (trim($_POST['tipo_fijo'])!='' || trim($_POST['tipo_variable'])!='' || trim($_POST['tipo_admin'])!='' ) {
            $fijo=isset($_POST['tipo_fijo'])?$_POST['tipo_fijo']:'0';

            $variable=isset($_POST['tipo_variable'])?$_POST['tipo_variable']:'';
            $admin=isset($_POST['tipo_admin'])?$_POST['tipo_admin']:'';

            
            $rows->andWhere("( pd.tipo IN ('".$fijo."','".$variable."','".$admin."') )");
            $rows1->andWhere("( (SELECT CAST('admin' AS CHAR) as tipo) IN ('".$fijo."','".$variable."','".$admin."') )");
            //$rows1->andWhere("m.nombre like '%".$_POST['marca']."%'");
        }

        if (trim($_POST['ano'])!='' && isset($_POST['ano']) ) {
            $rows->andWhere("dp.ano like '%".trim($_POST['ano'])."%'");
            $rows1->andWhere("as.ano like '%".trim($_POST['ano'])."%'");
        }

        $ordenado='dp.numero_factura';
        $ordenado1='as.id';
        if(isset($_POST['ordenado'])){
            switch ($_POST['ordenado']) {
                case "mes":
                    $ordenado='dp.mes';
                    $ordenado1='as.mes';
                    break;
                case "ano":
                    $ordenado='dp.ano';
                    $ordenado1='as.ano';
                    break;
                case "dependencia":
                    $ordenado='cc.nombre';
                    $ordenado1='cc.nombre';
                    break;
                case "empresa":
                    $ordenado='em.nombre';
                    $ordenado1='em.nombre';
                    break;
                case "fecha":
                    $ordenado='dp.created';
                    $ordenado1='as.created';
                    break;
            }
        }
        if(isset($_POST['forma'])){
            if($_POST['forma']=='SORT_ASC'){
                $rows->orderBy([$ordenado => SORT_ASC]);
                $rows1->orderBy([$ordenado1 => SORT_ASC]);
            }else{
                $rows->orderBy([$ordenado => SORT_DESC]);
                $rows1->orderBy([$ordenado1 => SORT_DESC]);
            }
        }else{
            $rows->orderBy([$ordenado => SORT_DESC]);
            $rows1->orderBy([$ordenado1 => SORT_DESC]);
        }
        


        $rowsCount= clone $rows;
        $rowsCount1= clone $rows1;
        $modelcount = $rowsCount->count()+$rowsCount1->count();
        //$modelcount = $rowsCount->count();

        if(!isset($_POST['excel'])){
            $rows->limit($rowsPerPage)->offset($start);

            $rows1->limit($rowsPerPage)->offset($start);
        }

        //$command = $rows->createCommand();
        $command=$rows->union($rows1)->createCommand();
        //echo $command->sql;exit();
        $dispositivos = $command->queryAll();

        ///DESCARAGA DE EXCEL
        if(isset($_POST['excel'])){
            // set_time_limit(5000);
            //ini_set('memory_limit', '2024M');
            \moonland\phpexcel\Excel::widget([
                'models' => $dispositivos,
                'mode' => 'export',
                'fileName' => 'Informe dispositivos', 
                'columns' => [
                    'id_disp',
                    'tipo',
                    'mes',
                    'ano',
                    'dependencia',
                    'regional',
                    'nit',
                    'empresa_seg',
                    'estado',
                    'ftes_diurno',
                    'ftes_nocturno',
                    'ftes',
                    'valor_total_mes',
                    'valor_serv_diurno',
                    'valor_serv_nocturno',
                    'numero_factura',
                    'fecha_factura',
                    'cuenta_contable',
                    'ceco',
                    'ciudad',
                    'marca',
                    'servicio_disp',
                    'puesto',
                    'cantidad_servicios',
                    'horas',
                    'hora_inicio',
                    'hora_fin',
                    'lunes',
                    'martes',
                    'miercoles',
                    'jueves',
                    'viernes',
                    'sabado',
                    'domingo',
                    'festivo',
                    'tipo_servicio',
                    'explicacion',
                    'nombre_factura'
                    
                ],
                'headers' => [
                    'id_disp'=>'id',
                    'tipo'=>'Tipo',
                    'mes' => 'Mes',
                    'ano' => 'Año',
                    'dependencia' => 'Dependencia',
                    'regional' => 'Regional',
                    'nit'=>'Nit',
                    'empresa_seg'=>'Empresa',
                    'estado'=>'Estado',
                    'ftes_diurno'=>'Ftes_diurno',
                    'ftes_nocturno'=>'Ftes_nocturno',
                    'ftes'=>'Total ftes',
                    'valor_total_mes'=>'Total_Servicio',
                    'valor_serv_diurno'=>'Valor servicio diurno',
                    'valor_serv_nocturno'=>'Valor servicio nocturno',
                    'numero_factura'=>'Numero Factura',
                    'fecha_factura'=>'Fecha Factura',
                    'cuenta_contable'=>'Cuenta contable',
                    'ceco'=>'ceco',
                    'ciudad'=>'Ciudad',
                    'marca'=>'Marca',
                    'servicio_disp'=>'Servicio',
                    'puesto'=>'Puesto',
                    'cantidad_servicios'=>'Cantidad servicios',
                    'horas'=>'Horas',
                    'hora_inicio'=>'Hora Inicio',
                    'hora_fin'=>'Hora Fin',
                    'lunes'=>'Lunes',
                    'martes'=>'Martes',
                    'miercoles'=>'Miercoles',
                    'jueves'=>'Jueves',
                    'viernes'=>'Viernes',
                    'sabado'=>'Sabado',
                    'domingo'=>'Domingo',
                    'festivo'=>'Festivo',
                    'tipo_servicio'=>'Tipo servicio',
                    'explicacion'=>'Explicacion',
                    'nombre_factura'=>'Nombre Factura'

                ], 
            ]);
        }



        //////////////////////

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


         $res.= $this->renderPartial('partial_informe', array(
            'dispositivos' => $dispositivos,
            'modelcount' => $modelcount,
            
                ), true);

        if(isset($_POST['page'])){
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'respuesta' => $res,
                'query' => $command->sql,
            ];
        }else{
            return $this->render('informe_dispositivos', [
                'res'=>$res,
                'zonas' => $zonas,
                'dependencias' => $dependencias,
                'empresas' => $empresas,
                'zonasUsuario' => $zonasUsuario,
                'marcasUsuario' => $marcasUsuario,
                'distritosUsuario' => $distritosUsuario,                
                'empresasUsuario' => $empresasUsuario,
                'list_empresas'=>$list_empresas,
                'list_zonas'=>$list_zonas
                
            ]);
        }


    }



    public function usuario_empresas($id){

        $empresas=UsuarioEmpresa::find()->Where(['usuario'=>$id])->all();

        $array=array();

        foreach ($empresas as $value) {
            $array[]=$value->nit;    
        } 

        return $array;

    }

     public function dependencias_usuario($id){

        $usuario= Usuario::findOne($id);
        $zonasUsuario     = array();
        $marcasUsuario    = array();
        $distritosUsuario = array();
        $dependencias     = CentroCosto::find()->/*where(['not in', 'estado', ['A']])->*/orderBy(['nombre' => SORT_ASC])->all();

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


    public function actionView($id){
        $dispositivos = PrefacturaDispositivo::find()->where('id_prefactura_fija='.$id)->all();

        if (isset($_POST['fecha_factura'])) {
            $model =PrefacturaFija::find()->where('id='.$id)->one();
            $model->setAttribute('numero_factura', $_POST['num_factura']);
            $model->setAttribute('fecha_factura', $_POST['fecha_factura']);

            $model->save();

            return $this->redirect(['view', 'id' => $id ]);
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
            'dispositivos' => $dispositivos,
        ]);
    }
	
    public function actionCreate(){
        $model = new PrefacturaFija();
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
        if ($model->load(Yii::$app->request->post())) {
            $model->setAttribute('usuario', Yii::$app->session['usuario-exito']);
            $model->setAttribute('created', $fecha_actual);
            $model->setAttribute('updated', $fecha_actual);
            $model->setAttribute('estado', 'abierto');
            //buscar la dependencia
            $dependencia = CentroCosto::findOne($model->centro_costo_codigo);
            $zona = Zona::findOne($model->regional);
            $model->setAttribute('ciudad', $dependencia->ciudad->nombre);
            $model->setAttribute('marca', $dependencia->marca->nombre);
            $model->setAttribute('empresa', $dependencia->empresa);
            $model->setAttribute('regional', $zona->nombre);
            // $model->setAttribute('nombre_factura', $_POST['nombre_factura']);

            $prefactura = PrefacturaFija::find()->where(['ano' => $model->ano,'mes' => $model->mes,'centro_costo_codigo' => $model->centro_costo_codigo])->one();
            $mensaje='';
            //if($prefactura == null){
                //cargar configuracion dispositivo fijo
                $modelo_prefactura = ModeloPrefactura::find()->where("centro_costo_codigo='".$model->centro_costo_codigo."'")->all();
                if(count($modelo_prefactura)>0){

                    // echo "<pre>";
                    // print_r($_POST['grupo']);
                    // echo "</pre>";
                    $cantidad=isset($_POST['grupo'])?count($_POST['grupo']):1;
                    //echo $cantidad."<br>";

                    for ($i=0; $i < $cantidad; $i++) { 
                        $name_group="";
                        if (isset($_POST['grupo'])) {
                            //echo $i;
                            $modelo_prefactura = ModeloPrefactura::find()->where("centro_costo_codigo='".$model->centro_costo_codigo."' and id_grupo=".$_POST['grupo'][$i])->all();
                            // echo "<pre>";
                            // print_r($modelo_prefactura);
                            // echo "</pre>";

                            $grup_name=GruposPrefactura::find()->where('id='.$_POST['grupo'][$i])->one();

                            $name_group=$grup_name->nombre;
                        }


                        
                        if(count($modelo_prefactura)>0){
                            
                            if($i>0)
                                $model->setAttribute('id',$model->id+1);


                            



                            $model->setAttribute('nombre_factura', $_POST['nombre_factura']."-".$name_group);
                            $model->save();

                            foreach ($modelo_prefactura as $mp) {
                                $pd = new PrefacturaDispositivo();
                                $pd->setAttribute('puesto_id', $mp->puesto_id);
                                $pd->setAttribute('detalle_servicio_id', $mp->detalle_servicio_id);
                                $pd->setAttribute('cantidad_servicios', $mp->cantidad_servicios);
                                $pd->setAttribute('horas', $mp->horas);
                                $pd->setAttribute('lunes', $mp->lunes);
                                $pd->setAttribute('martes', $mp->martes);
                                $pd->setAttribute('miercoles', $mp->miercoles);
                                $pd->setAttribute('jueves', $mp->jueves);
                                $pd->setAttribute('viernes', $mp->viernes);
                                $pd->setAttribute('sabado', $mp->sabado);
                                $pd->setAttribute('domingo', $mp->domingo);
                                $pd->setAttribute('festivo', $mp->festivo);
                                $pd->setAttribute('hora_inicio', $mp->hora_inicio);
                                $pd->setAttribute('hora_fin', $mp->hora_fin);
                                $pd->setAttribute('porcentaje', $mp->porcentaje);
                                $pd->setAttribute('ftes', $mp->ftes);
                                $pd->setAttribute('ftes_diurno', $mp->ftes_diurno);
                                $pd->setAttribute('ftes_nocturno', $mp->ftes_nocturno);
                                $pd->setAttribute('total_dias', $mp->total_dias);
                                $pd->setAttribute('valor_mes', $mp->valor_mes);
                                $pd->setAttribute('centro_costo_codigo', $mp->centro_costo_codigo);
                                $pd->setAttribute('id_prefactura_fija', $model->id);
                                $pd->setAttribute('tipo', 'fijo');
                                if(!$pd->save()){
                                    print_r($pd->getErrors());exit();
                                }

                                $model->isNewRecord=true;
                            }

                            //echo "id insertado=".$model->id."<br>";
                        }
                    }
                    // return $this->redirect(['view', 'id' => $model->id ]);
                    return $this->redirect(['index']);
                }else{
                    $mensaje='No se encontraron "DISPOSITIVOS FIJOS", Por favor, configure en la Dependencia los dispositivos fijos.';
                }
             // }else{
    //              $mensaje='Ya se encuentra creada una Pre-factura en esta dependencia para este tiempo';
    //          }
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


    public function actionGrupos_prefactura(){
        $grupos=GruposPrefactura::find()->where('codigo_dependencia="'.$_POST['dependencia'].'" ')->all();

        $res= $this->renderPartial('_grupos', array(
            'grupos' => $grupos
            
                ), true);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'respuesta' => $res
            
        ];

    }




    public function actionExiste_factura(){
        $prefactura = PrefacturaFija::find()->where(['ano' => $_POST['ano'],'mes' => $_POST['mes'],'centro_costo_codigo' => $_POST['dependencia']])->one();

        $existe=0;

        if (!$prefactura == null) {
            $existe=1;
        }


        $arreglo=array('respuesta'=>$existe);

        return json_encode($arreglo);

    }



    public function actionUpdate(){
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

    public function actionUpdatePrefactura($id){
        $model = $this->findModel($id);
        $array_post = Yii::$app->request->post();
        if ($model->load($array_post)) {
            $model->save();
            return $this->redirect(['view', 'id' => $id]);
        }else{
            return $this->render('update', [
                'model' => $model
                
            ]);
        }
    }

    public function actionDelete($id/*, $prefactura*/){

        //$prefactura_dispositivo = PrefacturaDispositivo::findOne($id);
        $prefactura_dispositivo = PrefacturaFija::findOne($id);

        if($prefactura_dispositivo != null){

            $prefactura_dispositivo->delete();

        }
       // return $this->redirect(['view', 'id' => $prefactura ]);
         return $this->redirect('index');
    }

    public function actionDeletePrefacturaDispositivo($id, $prefactura){

        $prefactura_dispositivo = PrefacturaDispositivo::findOne($id);
        //$prefactura_dispositivo = PrefacturaFija::findOne($id);

        if($prefactura_dispositivo != null){

            $prefactura_dispositivo->delete();

        }
        return $this->redirect(['view', 'id' => $prefactura ]);
       //  return $this->redirect('index');
    }    

    protected function findModel($id){
        if (($model = PrefacturaFija::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionCreateDispositivo($id)//variable
    {
        //$this->layout = 'main_sin_menu';
        Yii::$app->session->setTimeout(5400);
        $array_post = Yii::$app->request->post();
        $model      = new PrefacturaDispositivo();
        date_default_timezone_set ( 'America/Bogota');
        //$year = date('Y',time());
        $pf = PrefacturaFija::findOne($id);
        if($pf->mes=='01' || $pf->ano=='2018'){
            $year ='2018';
        }else{

            $year = date('Y',time());
        }
        $servicios  = DetalleServicio::find()->where("ano='".$year."'")->orderBy(['codigo' => SORT_ASC])->all();
        $puesto  = Puesto::find()->where('estado="A"')->all();
        $jornada  = Jornada::find()->all();

        if (isset($array_post) && array_key_exists('hora_fin2', $array_post)) {
            $pf = PrefacturaFija::findOne($id);
            $cantidad = $array_post['PrefacturaDispositivo']['cantidad_servicios'];
            $detalle_servicio  = $array_post['PrefacturaDispositivo']['detalle_servicio_id'];
            //echo $array_post['PrefacturaDispositivo']['cantidad_servicios'];exit();
            //print_r($array_post);
            //echo "aqui".$cantidad;exit();
            if ($detalle_servicio != 0) {
                //guardar filas
                $prefactura_dispositivo = new PrefacturaDispositivo();
                $puesto = $array_post['PrefacturaDispositivo']['puesto_id'];
                $can_servicio = $array_post['PrefacturaDispositivo']['cantidad_servicios'];
                $jornada = $array_post['PrefacturaDispositivo']['horas'];
                $desde = $array_post['PrefacturaDispositivo']['hora_inicio'];
                $hasta = $array_post['hora_fin2'];
                $porcentaje = $array_post['PrefacturaDispositivo']['porcentaje'];
                $ftes = $array_post['ftes2'];
                $ftes_diurno = $array_post['ftes_diurno'];
                $ftes_nocturno = $array_post['ftes_nocturno'];
                $dias_totales = $array_post['PrefacturaDispositivo']['total_dias'];
                $precio = $array_post['valor_servicio2'];
                $tipo_servicio = $array_post['PrefacturaDispositivo']['tipo_servicio'];
                $explicacion = $array_post['PrefacturaDispositivo']['explicacion'];
                
                $prefactura_dispositivo->setAttribute('detalle_servicio_id', $detalle_servicio);
                $prefactura_dispositivo->setAttribute('puesto_id', $puesto);
                $prefactura_dispositivo->setAttribute('cantidad_servicios', $can_servicio);
                $prefactura_dispositivo->setAttribute('horas', $jornada);
                $prefactura_dispositivo->setAttribute('hora_inicio', $desde);
                $prefactura_dispositivo->setAttribute('hora_fin', $hasta);
                $prefactura_dispositivo->setAttribute('porcentaje', $porcentaje);
                $prefactura_dispositivo->setAttribute('ftes', $ftes);
                $prefactura_dispositivo->setAttribute('ftes_diurno', $ftes_diurno);
                $prefactura_dispositivo->setAttribute('ftes_nocturno', $ftes_nocturno);
                $prefactura_dispositivo->setAttribute('total_dias', $dias_totales);
                $prefactura_dispositivo->setAttribute('valor_mes', $precio);
                $prefactura_dispositivo->setAttribute('centro_costo_codigo', $pf->centro_costo_codigo);
                $prefactura_dispositivo->setAttribute('id_prefactura_fija', $id);
                $prefactura_dispositivo->setAttribute('tipo', 'variable');
                $prefactura_dispositivo->setAttribute('tipo_servicio', $tipo_servicio);
                $prefactura_dispositivo->setAttribute('explicacion', $explicacion);
                
                if($prefactura_dispositivo->save()){
                    return $this->redirect(['view', 'id' => $id]);
                }else{
                    print_r($prefactura_dispositivo->getErrors());
                }
            }
        }else{
            return $this->render('create_dispositivo', [
                'codigo_dependencia' => $id,
                'servicios'          => $servicios,
                'puesto'          => $puesto,
                'modelo_prefactura'  => 'active',
                'model'              => $model,
                'jornada'              => $jornada,
            ]);
        }
    }
    public function actionImprimir($id){
        $prefactura  = PrefacturaFija::findOne($id);
        date_default_timezone_set ( 'America/Bogota');
        $dispositivos = PrefacturaDispositivo::find()->where('id_prefactura_fija='.$id)->all();
        $content = $this->renderPartial('_imprimir', array(
            'model' => $prefactura,
            'dispositivos' => $dispositivos
        ), true);

       /* $pdf = new Pdf([
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
            'options' => ['title' => 'Prefactura'],
             // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>['Prefactura-'.date('Y-m-d')], 
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

    public function  actionAbrir_pref($id){
        $model = $this->findModel($id);
        $model->setAttribute('estado','abierto');
        $model->save();
        return $this->redirect(['index']);
    }

    public function actionAbrir_pref_todas(){
        $seleccionadas=$_POST['seleccion'];

        foreach ($seleccionadas as $key => $value) {
            $model = $this->findModel($value);
            $model->setAttribute('estado','abierto');
            $model->save();
        }

        return $this->redirect(['index']);
    }

    public function actionEditarFts(){
        $dispositivos=AdminDispositivo::find()
        ->innerJoin('admin_supervision', 'admin_supervision.id = admin_dispositivo.id_admin')
        ->where('admin_supervision.ano="2019" AND admin_supervision.mes ="01"')
        ->all();
        foreach ($dispositivos as $row) {
            $num_dep=AdminDependencia::find()->where('id_admin='.$row->id_admin)->count();   

            if($row->ftes!=0 && $row->ftes!="" /*&& $row->ftes_dependencia!=0 && $row->ftes_dependencia!=""*/ && $row->cantidad!=0 && $row->cantidad!="" && $num_dep!=0):

            //$ftes_diurno_dep= round((($row->ftes_diurno/$num_dep)*$row->cantidad),3, PHP_ROUND_HALF_DOWN);
            //$ftes_nocturno_dep= round((($row->ftes_nocturno/$num_dep)*$row->cantidad),3, PHP_ROUND_HALF_DOWN);
            //$ftes_dep=round((($row->ftes/$num_dep)*$row->cantidad),3, PHP_ROUND_HALF_DOWN);
            //comentar cuando se actualiza el precio
            $ftes_diurno_dep=bcdiv((($row->ftes_diurno/$num_dep)*$row->cantidad), '1', 3);
            $ftes_nocturno_dep=bcdiv((($row->ftes_nocturno/$num_dep)*$row->cantidad), '1', 3);
            $ftes_dep=($ftes_diurno_dep+$ftes_nocturno_dep);

            $ftes=($row->ftes_diurno+$row->ftes_nocturno);
            //comentar cuando actualize ftes
            //$precio_dep=($row->precio_total/$num_dep);
            //$precio_dep_final=round($precio_dep, 2, PHP_ROUND_HALF_DOWN);
            
            AdminDispositivo::updateAll(['ftes_diurno_dep' => $ftes_diurno_dep,'ftes_nocturno_dep'=>$ftes_nocturno_dep,'ftes_dependencia'=>$ftes_dep,'ftes'=>$ftes], ['=', 'id', $row->id]);

            //AdminDispositivo::updateAll(['precio_dependencia'=>$precio_dep_final], ['=', 'id', $row->id]);

            endif;

            echo "cantidad:".$row->cantidad."-numdep:".$num_dep."-ftes_diurno_dep:".$ftes_diurno_dep."-ftes_nocturno_dep:".$ftes_nocturno_dep."-ftes_dependencia:".$ftes_dep."-ftesdiurno:".$row->ftes_diurno."-ftesnocturno:".$row->ftes_nocturno."-ftes:".$ftes." <hr><br>";
            
        }



        echo"Terminado....";

    }

    function Truncar($numero, $digitos)
    {
        $truncar = 10**$digitos;
        echo  intval($numero * $truncar) / $truncar;
    }

    public function actionCalcular_total(){

        $model      = new PrefacturaDispositivo();
        $query=$model
        ->find()
        ->innerJoin('prefactura_fija', 'prefactura_fija.id = prefactura_dispositivo.id_prefactura_fija')
        ->where('prefactura_fija.mes="01" AND prefactura_fija.ano="2019" /*AND prefactura_dispositivo.id NOT IN (43447,48677)*/')
        ->orderby('id ASC')
        ->all();
        $jornada  = Jornada::find()->all();
        //$year = date('Y',time());
        $year = '2019';
        $servicios  = DetalleServicio::find()->where("ano='".$year."'")->orderBy(['codigo' => SORT_ASC])->all();
        return $this->render('calcular_total', [
                'model'=> $model,
                'query'=>$query,
                'jornada'=>$jornada,
                'servicios'=>$servicios
                
            ]);
    }

    public function actionActualizar_ftes(){
        PrefacturaDispositivo::updateAll(['ftes'=>$_POST['total_ftes'],'ftes_diurno'=>$_POST['ftes_diurno'],'ftes_nocturno'=>$_POST['ftes_nocturno'] ], ['=', 'id', $_POST['id'] ]);
    }


    function actionAprobacion_gerente(){
        $dependencias=Pedido::DependenciasUsuario(Yii::$app->session['usuario-exito'],'Name');
        $usuario = Usuario::findOne(Yii::$app->session['usuario-exito']);
        $zonasUsuario = array();
        $marcasUsuario = array();
        
        if($usuario != null){
          $zonasUsuario = $usuario->zonas;      
          $marcasUsuario = $usuario->marcas;
        }
        $data_marcas=array();
        foreach($marcasUsuario as $marca){
            
            $data_marcas [$marca->marca->nombre] = $marca->marca->nombre;
        }

        $data_regional=[];
        foreach ($zonasUsuario as $reg){
            $data_regional[$reg->zona->nombre]=$reg->zona->nombre;
        }

        $empresas = Empresa::find()->orderBy(['nombre' => SORT_ASC])->all();
        $list_empresas=ArrayHelper::map($empresas,'nombre','nombre');

        $ciudad=Ciudad::find()->orderBy(['nombre' => SORT_ASC])->all();
        $list_ciudad=ArrayHelper::map($ciudad,'nombre','nombre');        

        $query = (new \yii\db\Query())
        ->select('id,dependencia,ceco,cebe,marca,regional,empresa,mes,ano,total_fijo,total_variable,total_mes,ciudad')
        ->from('prefactura_consolidado_pedido')
        ->where('estado_pedido="S" AND estado="cerrado" AND /*DATE(created) >= "2019-03-01"*/ mes > 3 AND ano="2019"');
        //FILTROS
        if(isset($_GET['enviar'])){
           
            if(isset($_GET['dependencias']) && $_GET['dependencias']!=''){
                $query->andWhere('dependencia="'.$_GET['dependencias'].'" ');
            }
            if(isset($_GET['marca']) && $_GET['marca']!=''){
                $query->andWhere('marca="'.$_GET['marca'].'" ');
            }

            if(isset($_GET['regional']) && $_GET['regional']!=''){
                $query->andWhere('regional="'.$_GET['regional'].'" ');
            }

            if(isset($_GET['mes']) && $_GET['mes']!=''){
                $query->andWhere('mes="'.$_GET['mes'].'" ');
            }
            if(isset($_GET['empresas']) && $_GET['empresas']!=''){
                $query->andWhere('empresa="'.$_GET['empresas'].'" ');
            }

            if(isset($_GET['ciudad']) && $_GET['ciudad']!=''){
                $query->andWhere('ciudad="'.$_GET['ciudad'].'" ');
            }

            if(isset($_GET['buscar']) && $_GET['buscar']!=''){
                $query->andWhere(" 
                dependencia like '%".$_GET['buscar']."%' OR 
                marca like '%".$_GET['buscar']."%' OR 
                ceco like '%".$_GET['buscar']."%' 
                OR usuario like '%".$_GET['buscar']."%' 
                OR cebe like '%".$_GET['buscar']."%' 
                OR regional like '%".$_GET['buscar']."%' 
                OR ano like '%".$_GET['buscar']."%'
                OR ciudad like '%".$_GET['buscar']."%' 
                ");
            }
        }

        $ordenado=isset($_GET['ordenado']) && $_GET['ordenado']!=''?$_GET['ordenado']:"id";
        $forma=isset($_GET['forma']) && $_GET['forma']!=''?$_GET['forma']:"SORT_ASC";

        $query->orderBy([
            $ordenado => $forma
        ]);

        $count = $query->count();
        // crea un objeto paginación con dicho total
        $pagination = new Pagination(['totalCount' => $count]);
        $limit=30;
        $command = $query->offset($pagination->offset)->limit($limit)->createCommand();

        // Ejecutar el comando:
        $rows = $command->queryAll();

        $pagina=isset($_GET['page'])?$_GET['page']:1;
        
        return $this->render('aprobacion_gerente', [
            'rows'=>$rows,
            'pagination'=>$pagination,
            'count'=>$count,
            'dependencias'=>$dependencias,
            'marcas'=>$data_marcas,
            'pagina'=>$pagina,
            'data_regional'=>$data_regional,
            'list_empresas'=>$list_empresas,
            'list_ciudad'=>$list_ciudad
        ]);
    }

    public function actionAprobarRechazar(){
        $count=count($_POST['seleccion']);
        $checks=$_POST['seleccion'];

        if(isset($_POST['aprobar'])){
            foreach ($checks as $value) {
                $model=PrefacturaFija::find()->where('id='.$value)->one();
                $model->setAttribute('estado_pedido', 'L');
                $model->setAttribute('usuario_aprueba', Yii::$app->session['usuario-exito']);
                $model->setAttribute('fecha_aprobacion',date('Y-m-d'));
                $model->save();
            }
        }else if(isset($_POST['rechazar'])){
            foreach ($checks as $value) {
                $model=PrefacturaFija::find()->where('id='.$value)->one();
                $model->setAttribute('estado_pedido', 'R');
                $model->setAttribute('motivo_rechazo_prefactura',$_POST['observacion']);
                $model->setAttribute('usuario_rechaza', Yii::$app->session['usuario-exito']);
                $model->setAttribute('fecha_rechazo',date('Y-m-d'));
                $model->save();
            }
        }

        return $this->redirect(['aprobacion_gerente']);

    }

    public function actionAprobarPrefactura($id){//A= Aprobar
        $model=PrefacturaFija::find()->where('id='.$id)->one();
        $model->setAttribute('estado_pedido', 'L');
        $model->setAttribute('usuario_aprueba', Yii::$app->session['usuario-exito']);
        $model->setAttribute('fecha_aprobacion',date('Y-m-d'));
        if($model->save()){
            return $this->redirect(['aprobacion_gerente']);
        }else{
            print_r($model->getErrors());
        }

    }
}