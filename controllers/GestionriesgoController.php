<?php

namespace app\controllers;

use Yii;
use app\models\GestionRiesgo;
use app\models\GestionRiesgoSearch;
use app\models\Usuario;
use app\models\CentroCosto;
use app\models\ConsultasGestion;
use app\models\RespuestasGestion;
use app\models\HelpConsultaGestion;
use app\models\DetalleGestionRiesgo;
use app\models\HelpRespuestas;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use kartik\mpdf\Pdf;
use yii\data\Pagination;
use app\models\Zona;

/**
 * GestionriesgoController implements the CRUD actions for GestionRiesgo model.
 */
class GestionriesgoController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [

            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['create','informeNovedades','view'],
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['create','informeNovedades','view'],
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
     * Lists all GestionRiesgo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GestionRiesgoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single GestionRiesgo model.
     * @param integer $id
     * @return mixed
     */
   public function actionView($id)
    {
        //$this->layout = 'main_sin_menu';
        $consulta=DetalleGestionRiesgo::find()->where('id_gestion='.$id)->all();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'consulta'=>$consulta
        ]);
    }

    /**
     * Creates a new GestionRiesgo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GestionRiesgo();
        $usuario          = Usuario::findOne(Yii::$app->session['usuario-exito']);
        $distritosUsuario = array();
        $zonasUsuario     = array();
        $marcasUsuario    = array();

        if ($usuario != null) {

            $distritosUsuario = $usuario->distritos;
            $zonasUsuario     = $usuario->zonas;
            $marcasUsuario    = $usuario->marcas;
        }

        $dependencias     = CentroCosto::find()->where(['not in', 'estado', ['C']])->orderBy(['nombre' => SORT_ASC])->all();
        $consultas=ConsultasGestion::find()->where('estado="A"')->orderBy(['orden' => SORT_ASC])->all();
        $respuestas=RespuestasGestion::find()->all();
        $list_respuestas=ArrayHelper::map($respuestas,'id','descripcion');

        if ($model->load(Yii::$app->request->post()) ) {

            $model->setAttribute('usuario',Yii::$app->session['usuario-exito']);

            $model->save();
            
            // echo "<pre>";
            // print_r($_POST);
            // echo "</pre>";

            foreach ($_POST['preguntas'] as $key => $value) {
                $modelo_detalle=new DetalleGestionRiesgo();

                $modelo_detalle->setAttribute('id_consulta',$value);
                $modelo_detalle->setAttribute('id_respuesta',$_POST['repuesta'][$key]);
                $modelo_detalle->setAttribute('observaciones',$_POST['observacion'][$key]);
                $modelo_detalle->setAttribute('planes_de_accion',$_POST['planes'][$key]);
                $modelo_detalle->setAttribute('id_gestion',$model->id);
                $modelo_detalle->save();
            }

            Yii::$app->session->setFlash('success','Gestion creada correctamente');
            return $this->redirect(['create']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'dependencias'     => $dependencias,
                'distritosUsuario' => $distritosUsuario,
                'marcasUsuario'    => $marcasUsuario,
                'zonasUsuario'     => $zonasUsuario,
                'consultas'        =>$consultas,
                //'list_respuestas'=>$list_respuestas
                'respuestas'=>$respuestas
            ]);
        }
    }


    public function actionAyuda(){
        $id=$_POST['id'];

        $consulta=HelpConsultaGestion::find()->where(['id_consulta_gestion'=>$id])->one();

        $arreglo=array('respuesta'=>$consulta->descripcion);

        return json_encode($arreglo);
    }


    public function actionAyuda_resp(){
        $id=$_POST['id'];

        $consulta=HelpRespuestas::find()->where(['id_consulta'=>$id])->one();

        $arreglo=array(
            'respuesta1'=>$consulta->cumple,
            'respuesta2'=>$consulta->no_cumple,
            'respuesta3'=>$consulta->en_proceso

        );

        return json_encode($arreglo);
    }

    /**
     * Updates an existing GestionRiesgo model.
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
     * Deletes an existing GestionRiesgo model.
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
     * Finds the GestionRiesgo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GestionRiesgo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionImprimir($id){

        $consulta=DetalleGestionRiesgo::find()->where('id_gestion ="'.$id.'" ')->all();
        $model = $this->findModel($id);
        $content = $this->renderPartial('imprimir', array(
            'consulta' => $consulta,
            'model'=>$model
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
            'options' => ['title' => 'Gestion Riesgo'],
             // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>['Gestion-'.$model->dependencia->nombre." - ".date('Y-m-d')], 
                'SetFooter'=>['{PAGENO}'],
            ]
        ]);
        
        // return the pdf output as per the destination setting
        return $pdf->render();*/
        $pdf = Yii::$app->pdf; // or new Pdf();
        $mpdf = $pdf->api; // fetches mpdf api
        //$mpdf->cssInline('table, td, th {border: 1px solid black;} .kv-heading-1{font-size:18px}');
        $mpdf->SetHeader('Gestion-'.$model->dependencia->nombre." - ".date('Y-m-d')); // call methods or set any properties
        $mpdf->SetFooter('{PAGENO}');
        $mpdf->WriteHtml($content); // call mpdf write html
        echo $mpdf->Output('Gestion'.date('Y-m-d').'.pdf', 'D');
    }

    public function actionInformeNovedades(){
         /*DEPENDENCIAS*/
        $ano=date('Y');
        $usuario = Usuario::findOne(Yii::$app->session['usuario-exito']);
        $dependencias = CentroCosto::find()->where(['not in', 'estado', ['C']])->orderBy(['nombre' => SORT_ASC])->all();
        $gestionadas=0;
        $no_gestionadas=0;

        foreach ($dependencias as $key => $value) {
            $conteo=GestionRiesgo::find()
            ->innerjoin('centro_costo cc','gestion_riesgo.id_centro_costo=cc.codigo')
            ->innerjoin('marca m','cc.marca_id=m.id')
            ->where('id_centro_costo="'.$value->codigo.'" AND  cc.indicador_gestion="S" ');
            if ($_POST['desde']!='' && $_POST['hasta']!='') {
                $conteo=$conteo->andWhere(' fecha_visita BETWEEN "'.$_POST['desde'].'" AND "'.$_POST['hasta'].'" ');
            }

            if(isset($_POST['buscar'])){
                if($_POST['buscar']!='')
                    $conteo=$conteo->andWhere('gestion_riesgo.id like "%'.$_POST['buscar'].'%" OR  YEAR(gestion_riesgo.fecha_visita)="'.$_POST['buscar'].'"
                        OR  gestion_riesgo.usuario="'.$_POST['buscar'].'"');


            }

            if (isset($_POST['marcas2'])) {

                if($_POST['marcas2']!='')
                    $conteo=$conteo->andWhere('m.nombre="'.$_POST['marcas2'].'"');
            }

            if (isset($_POST['regional'])) {

                if($_POST['regional']!='')
                    $conteo=$conteo->andWhere(' (
                    select zona.nombre  from centro_costo cco
                    inner join ciudad_zona cz on cco.ciudad_codigo_dane=cz.ciudad_codigo_dane
                    inner join zona on  cz.zona_id=zona.id
                    WHERE cco.codigo=gestion_riesgo.id_centro_costo limit 1
                    )="'.$_POST['regional'].'" ');
            }

            $conteo=$conteo->count();
            if ($conteo>0) {
                $gestionadas++; 
            }else{
                $no_gestionadas++;
            }
        }

        $array_gestiones=array(
            array('name'=>'Gestionadas','y'=>(int)$gestionadas,'sliced'=>true,'selected'=>true),
            array('name'=>'No Gestionadas','y'=>(int)$no_gestionadas)
        );

        $array_gestiones=json_encode($array_gestiones);

        $ConsultaRespuesta=RespuestasGestion::find()->all();

        $arreglo_Resp=[];
        foreach ($ConsultaRespuesta as $res) {
            $DetalleNum=DetalleGestionRiesgo::find()
            ->innerjoin('gestion_riesgo gs','detalle_gestion_riesgo.id_gestion=gs.id')
            ->innerjoin('centro_costo cc','gs.id_centro_costo=cc.codigo')
            ->innerjoin('marca m','cc.marca_id=m.id')
            ->where(' id_respuesta='.$res->id.' AND  cc.indicador_gestion="S" ');

            if(isset($_POST['buscar'])){
                if($_POST['buscar']!='')
                    $DetalleNum=$DetalleNum->andWhere('(gs.id like "%'.$_POST['buscar'].'%") OR  (YEAR(gs.fecha_visita)="'.$_POST['buscar'].'") OR  (gs.usuario="'.$_POST['buscar'].'")');


            }


            if ($_POST['desde']!='' && $_POST['hasta']!='') {
                $DetalleNum=$DetalleNum->andWhere(' gs.fecha_visita BETWEEN "'.$_POST['desde'].'" AND "'.$_POST['hasta'].'" ');
            }

            if (isset($_POST['dependencias2'])) {

                if($_POST['dependencias2']!='')
                    $DetalleNum=$DetalleNum->andWhere('cc.nombre="'.$_POST['dependencias2'].'"');
            }

            if (isset($_POST['marcas2'])) {

                if($_POST['marcas2']!='')
                    $DetalleNum=$DetalleNum->andWhere('m.nombre="'.$_POST['marcas2'].'"');
            }

            if (isset($_POST['regional'])) {

                if($_POST['regional']!='')
                    $DetalleNum=$DetalleNum->andWhere(' (
                    select zona.nombre  from centro_costo cco
                    inner join ciudad_zona cz on cco.ciudad_codigo_dane=cz.ciudad_codigo_dane
                    inner join zona on  cz.zona_id=zona.id
                    WHERE cco.codigo=gs.id_centro_costo limit 1
                    )="'.$_POST['regional'].'" ');
            }

            $DetalleNum=$DetalleNum->count();


            $arreglo_temas[]=['name'=>$res->descripcion,'y'=>(int)$DetalleNum];
        }

        // echo "<pre>";
        // print_r($arreglo_temas);
        // echo "</pre>";

        $arreglo_temas=json_encode($arreglo_temas);

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
        $ciudades_zonas = array();//almacena las regionales permitidas al usuario

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
        $data_marcas=array();
        foreach($marcasUsuario as $marca){
            $marcas_permitidas [] = $marca->marca_id;
            $data_marcas [$marca->marca->nombre] = $marca->marca->nombre;
        }


        $empresas_permitidas = array();
        foreach($empresasUsuario as $empresa){
            $empresas_permitidas [] = $empresa->nit;
        }
        $data_dependencias = array();
        foreach($dependencias as $dependencia){
            if(in_array($dependencia->ciudad_codigo_dane,$ciudades_permitidas) ){
                if(in_array($dependencia->marca_id,$marcas_permitidas) ){
                    //if(in_array($dependencia->empresa,$empresas_permitidas) ){
                        $data_dependencias[$dependencia->nombre] =  $dependencia->nombre;
                    //}
                }
            }
        }
        ////////////////////////////////////////////
        $ano=date('Y');
        $query=(new \yii\db\Query())
        ->select(['gs.id','gs.fecha','gs.fecha_visita','gs.observacion','gs.usuario','cc.nombre Dependencia','m.nombre Marca','(
            select zona.nombre  from centro_costo cco
            inner join ciudad_zona cz on cco.ciudad_codigo_dane=cz.ciudad_codigo_dane
            inner join zona on  cz.zona_id=zona.id
            WHERE cco.codigo=cc.codigo limit 1
            ) regional'])
            ->from('gestion_riesgo gs')
            ->innerJoin('centro_costo cc', 'gs.id_centro_costo=cc.codigo')
            ->innerjoin('marca m','cc.marca_id=m.id');
        //->where('1=1');

        if(isset($_POST['buscar'])){
            if($_POST['buscar']!='')
            $query->andWhere('gs.id like "%'.$_POST['buscar'].'%" OR cc.nombre like "%'.$_POST['buscar'].'%" OR m.nombre like "%'.$_POST['buscar'].'%" OR YEAR(gs.fecha_visita)="'.$_POST['buscar'].'"');


        }


        if (isset($_POST['dependencias2'])) {

            if($_POST['dependencias2']!='')
                $query->andWhere('cc.nombre="'.$_POST['dependencias2'].'"');
        }

        if ($_POST['desde']!='' && $_POST['hasta']!='' ) {

            $query->andWhere('gs.fecha_visita BETWEEN "'.$_POST['desde'].'" AND "'.$_POST['hasta'].'" ');
        }else{
            $query->andWhere('YEAR(gs.fecha_visita)="'.$ano.'"');
        }

        if (isset($_POST['marcas2'])) {

            if($_POST['marcas2']!='')
                $query->andWhere('m.nombre="'.$_POST['marcas2'].'"');
        }

        if (isset($_POST['regional'])) {

            if($_POST['regional']!='')
                $query->andWhere('(
                    select zona.nombre  from centro_costo cco
                    inner join ciudad_zona cz on cco.ciudad_codigo_dane=cz.ciudad_codigo_dane
                    inner join zona on  cz.zona_id=zona.id
                    WHERE cco.codigo=cc.codigo limit 1
                    )="'.$_POST['regional'].'"');
        }

        $ordenado='gs.fecha_visita';
        
        if(isset($_POST['ordenado'])){
            switch ($_POST['ordenado']) {
                
                case "dependencia":
                    $ordenado='cc.nombre';
                    
                    break;
                case "marca":
                    $ordenado='m.nombre';
                    
                    break;
                case "fecha":
                    $ordenado='gs.fecha_visita';
                    
                    break;
            }
        }
        if(isset($_POST['forma'])){
            if($_POST['forma']=='SORT_ASC'){
                $query->orderBy([$ordenado => SORT_ASC]);
                
            }else{
                $query->orderBy([$ordenado => SORT_DESC]);
                

            }
        }else{
            $query->orderBy([$ordenado => SORT_DESC]);
            
        }

        $count = $query->count();
        
        $pagination = new Pagination(['totalCount' => $count]);
        $gestiones=$query->offset($pagination->offset)
                    ->limit($pagination->limit)
                    ->all();

        $page=isset($_GET['page'])?$_GET['page']:1;
        $regionales=Zona::find()->all();
        $list_regionales=ArrayHelper::map($regionales,'nombre','nombre');

        return $this->render('informe_novedades', [
            'gestiones' => $gestiones,
            'data_dependencias'=> $data_dependencias,
            'data_marcas'=>$data_marcas,
            'POST'=>$_POST,
            'temas'=>ConsultasGestion::find()->all(),
            'pagination'=>$pagination,
            'page'=>$page,
            'count'=>$count,
            'regionales'=>$list_regionales,
            'array_gestiones'=>$array_gestiones,
            'arreglo_temas'=>$arreglo_temas
           
        ]);
    }

    public function actionInformeExcel(){
        $ano=date('Y');
        $query=(new \yii\db\Query())
        ->select(['gs.id','gs.fecha','gs.fecha_visita','gs.observacion','gs.usuario','cc.nombre Dependencia','m.nombre Marca','(
            select zona.nombre  from centro_costo cco
            inner join ciudad_zona cz on cco.ciudad_codigo_dane=cz.ciudad_codigo_dane
            inner join zona on  cz.zona_id=zona.id
            WHERE cco.codigo=cc.codigo limit 1
            ) regional'])
            ->from('gestion_riesgo gs')
            ->innerJoin('centro_costo cc', 'gs.id_centro_costo=cc.codigo')
            ->innerjoin('marca m','cc.marca_id=m.id');
           

         if(isset($_POST['buscar'])){
            if($_POST['buscar']!='')
            $query->andWhere('gs.id like "%'.$_POST['buscar'].'%" OR cc.nombre like "%'.$_POST['buscar'].'%" OR m.nombre like "%'.$_POST['buscar'].'%" OR YEAR(gs.fecha_visita)="'.$_POST['buscar'].'"');


        }


        if (isset($_POST['dependencias2'])) {

            if($_POST['dependencias2']!='')
                $query->andWhere('cc.nombre="'.$_POST['dependencias2'].'"');
        }

        if ($_POST['desde']!='' && $_POST['hasta']!='' ) {

            $query->andWhere('gs.fecha_visita BETWEEN "'.$_POST['desde'].'" AND "'.$_POST['hasta'].'" ');

        }else{
            $query->andWhere('YEAR(gs.fecha_visita)="'.$ano.'"');
        }

        if (isset($_POST['marcas2'])) {

            if($_POST['marcas2']!='')
                $query->andWhere('m.nombre="'.$_POST['marcas2'].'"');
        }

        if (isset($_POST['regional'])) {

            if($_POST['regional']!='')
                $query->andWhere('(
                    select zona.nombre  from centro_costo cco
                    inner join ciudad_zona cz on cco.ciudad_codigo_dane=cz.ciudad_codigo_dane
                    inner join zona on  cz.zona_id=zona.id
                    WHERE cco.codigo=cc.codigo limit 1
                    )="'.$_POST['regional'].'"');
        }

        $ordenado='gs.fecha_visita';
        
        if(isset($_POST['ordenado'])){
            switch ($_POST['ordenado']) {
                
                case "dependencia":
                    $ordenado='cc.nombre';
                    
                    break;
                case "marca":
                    $ordenado='m.nombre';
                    
                    break;
                case "fecha":
                    $ordenado='gs.fecha_visita';
                    
                    break;
            }
        }
        if(isset($_POST['forma'])){
            if($_POST['forma']=='SORT_ASC'){
                $query->orderBy([$ordenado => SORT_ASC]);
                
            }else{
                $query->orderBy([$ordenado => SORT_DESC]);
                

            }
        }else{
            $query->orderBy([$ordenado => SORT_DESC]);
            
        }

        //$result=$query->all();
        $result=$query->createCommand()->queryAll();

        \moonland\phpexcel\Excel::widget([
                
                'models' => $result,
                'mode' => 'export',
                'fileName' => 'Desempeño SG-SST', 
                'columns' => [
                    'id',
                    'fecha',
                    'Dependencia',
                    'Marca',
                    'regional',
                    'fecha_visita',
                    [
                            'attribute' => 'content',
                            'header' => 'Actualización de la política',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],14);
                                return $detalle->respuesta->descripcion;
                            },
                    ],
                    [
                            'attribute' => 'content',
                            'header' => 'Observacion',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],14);
                                return $detalle->observaciones;
                            },
                    ],
                    [
                            'attribute' => 'content',
                            'header' => 'Plan de accion',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],14);
                                return $detalle->planes_de_accion;
                            },
                    ],
                    [
                            'attribute' => 'content',
                            'header' => 'Divulgación de la política',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],15);
                                return $detalle->respuesta->descripcion;
                            },
                    ],
                    [
                            'attribute' => 'content',
                            'header' => 'Observacion',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],15);
                                return $detalle->observaciones;
                            },
                    ],
                    [
                            'attribute' => 'content',
                            'header' => 'Plan de accion',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],15);
                                return $detalle->planes_de_accion;
                            },
                    ]
                    ,
                    [
                            'attribute' => 'content',
                            'header' => 'Plan de trabajo anual en SST',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],16);
                                return $detalle->respuesta->descripcion;
                            },
                    ],
                    [
                            'attribute' => 'content',
                            'header' => 'Observacion',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],16);
                                return $detalle->observaciones;
                            },
                    ],
                    [
                            'attribute' => 'content',
                            'header' => 'Plan de accion',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],16);
                                return $detalle->planes_de_accion;
                            },
                    ],
                    [
                            'attribute' => 'content',
                            'header' => 'Identificación y valoración de riesgos',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],17);
                                return $detalle->respuesta->descripcion;
                            },
                    ],
                    [
                            'attribute' => 'content',
                            'header' => 'Observacion',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],17);
                                return $detalle->observaciones;
                            },
                    ],
                    [
                            'attribute' => 'content',
                            'header' => 'Plan de accion',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],17);
                                return $detalle->planes_de_accion;
                            },
                    ]

                    ,
                    [
                            'attribute' => 'content',
                            'header' => 'Implementación de programa EPP',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],18);
                                return $detalle->respuesta->descripcion;
                            },
                    ],
                    [
                            'attribute' => 'content',
                            'header' => 'Observacion',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],18);
                                return $detalle->observaciones;
                            },
                    ],
                    [
                            'attribute' => 'content',
                            'header' => 'Plan de accion',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],18);
                                return $detalle->planes_de_accion;
                            },
                    ]

                    ,
                    [
                            'attribute' => 'content',
                            'header' => 'TAR (Trabajos de alturas, espacios confinados, trabajos en caliente, energías peligrosas)',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],19);
                                return $detalle->respuesta->descripcion;
                            },
                    ],
                    [
                            'attribute' => 'content',
                            'header' => 'Observacion',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],19);
                                return $detalle->observaciones;
                            },
                    ],
                    [
                            'attribute' => 'content',
                            'header' => 'Plan de accion',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],19);
                                return $detalle->planes_de_accion;
                            },
                    ]
                    ,
                    [
                            'attribute' => 'content',
                            'header' => 'Planes de acción de investigaciones de accidentes de trabajo',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],22);
                                return $detalle->respuesta->descripcion;
                            },
                    ],
                    [
                            'attribute' => 'content',
                            'header' => 'Observacion',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],22);
                                return $detalle->observaciones;
                            },
                    ],
                    [
                            'attribute' => 'content',
                            'header' => 'Plan de accion',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],22);
                                return $detalle->planes_de_accion;
                            },
                    ]
                    ,
                    [
                            'attribute' => 'content',
                            'header' => 'Reporte e investigación de AT',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],21);
                                return $detalle->respuesta->descripcion;
                            },
                    ],
                    [
                            'attribute' => 'content',
                            'header' => 'Observacion',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],21);
                                return $detalle->observaciones;
                            },
                    ],
                    [
                            'attribute' => 'content',
                            'header' => 'Plan de accion',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],21);
                                return $detalle->planes_de_accion;
                            },
                    ]
                    ,
                    [
                            'attribute' => 'content',
                            'header' => 'Estándares de Seguridad',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],23);
                                return $detalle->respuesta->descripcion;
                            },
                    ],
                    [
                            'attribute' => 'content',
                            'header' => 'Observacion',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],23);
                                return $detalle->observaciones;
                            },
                    ],
                    [
                            'attribute' => 'content',
                            'header' => 'Plan de accion',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],23);
                                return $detalle->planes_de_accion;
                            },
                    ]
                    ,
                    [
                            'attribute' => 'content',
                            'header' => 'Inspecciones de seguridad',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],24);
                                return $detalle->respuesta->descripcion;
                            },
                    ],
                    [
                            'attribute' => 'content',
                            'header' => 'Observacion',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],24);
                                return $detalle->observaciones;
                            },
                    ],
                    [
                            'attribute' => 'content',
                            'header' => 'Plan de accion',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],24);
                                return $detalle->planes_de_accion;
                            },
                    ]
                    ,
                    [
                            'attribute' => 'content',
                            'header' => 'Orden y aseo',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],25);
                                return $detalle->respuesta->descripcion;
                            },
                    ],
                    [
                            'attribute' => 'content',
                            'header' => 'Observacion',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],25);
                                return $detalle->observaciones;
                            },
                    ],
                    [
                            'attribute' => 'content',
                            'header' => 'Plan de accion',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],25);
                                return $detalle->planes_de_accion;
                            },
                    ]
                    ,
                    [
                            'attribute' => 'content',
                            'header' => 'Inducción y reinducción en seguridad y salud en el trabajo',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],26);
                                return $detalle->respuesta->descripcion;
                            },
                    ],
                    [
                            'attribute' => 'content',
                            'header' => 'Observacion',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],26);
                                return $detalle->observaciones;
                            },
                    ],
                    [
                            'attribute' => 'content',
                            'header' => 'Plan de accion',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],26);
                                return $detalle->planes_de_accion;
                            },
                    ]
                    ,
                    [
                            'attribute' => 'content',
                            'header' => 'Identificación de casos de médicos',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],27);
                                return $detalle->respuesta->descripcion;
                            },
                    ],
                    [
                            'attribute' => 'content',
                            'header' => 'Observacion',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],27);
                                return $detalle->observaciones;
                            },
                    ],
                    [
                            'attribute' => 'content',
                            'header' => 'Plan de accion',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],27);
                                return $detalle->planes_de_accion;
                            },
                    ]
                    ,
                    [
                            'attribute' => 'content',
                            'header' => 'Seguimiento Casos médicos',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],28);
                                return $detalle->respuesta->descripcion;
                            },
                    ],
                    [
                            'attribute' => 'content',
                            'header' => 'Observacion',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],28);
                                return $detalle->observaciones;
                            },
                    ],
                    [
                            'attribute' => 'content',
                            'header' => 'Plan de accion',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],28);
                                return $detalle->planes_de_accion;
                            },
                    ]
                    ,
                    [
                            'attribute' => 'content',
                            'header' => 'Reporte de EL al Ministerio o Entidad Territorial',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],29);
                                return $detalle->respuesta->descripcion;
                            },
                    ],
                    [
                            'attribute' => 'content',
                            'header' => 'Observacion',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],29);
                                return $detalle->observaciones;
                            },
                    ],
                    [
                            'attribute' => 'content',
                            'header' => 'Plan de accion',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],29);
                                return $detalle->planes_de_accion;
                            },
                    ]
                    ,
                    [
                            'attribute' => 'content',
                            'header' => 'Investigación de EL',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],30);
                                return $detalle->respuesta->descripcion;
                            },
                    ],
                    [
                            'attribute' => 'content',
                            'header' => 'Observacion',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],30);
                                return $detalle->observaciones;
                            },
                    ],
                    [
                            'attribute' => 'content',
                            'header' => 'Plan de accion',
                            'format' => 'text',
                            'value' => function($model) {
                                $detalle=DetalleGestionRiesgo::detalle_gestion($model['id'],30);
                                return $detalle->planes_de_accion;
                            },
                    ]
                    ,
                    [
                            'attribute' => 'content',
                            'header' => 'Novedad',
                            'format' => 'text',
                            'value' => function($model) {
                               
                                return strip_tags($model['observacion']);
                            },
                    ]
                    
                ],
                'headers' => [
                    'id'=>'Id',
                    'fecha'=>'Fecha',
                    'Dependencia'=>'Dependencia',
                    'Marca'=>'Marca',
                    'regional'=>'Regional',
                    'fecha_visita'=>'Fecha Visita'
                    //'observacion'=>'Observacion'
                ], 
            ]);
    }

    
    protected function findModel($id)
    {
        if (($model = GestionRiesgo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
