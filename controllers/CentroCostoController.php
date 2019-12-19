<?php

namespace app\controllers;

use app\models\CapacitacionDependencia;
use app\models\CentroCosto;
use app\models\CentroDistrito;
use app\models\Ciudad;
use app\models\ComiteDependencia;
use app\models\DetalleServicio;
use app\models\Dia;
use app\models\Distrito;
use app\models\Empresa;
use app\models\Evento;
use app\models\Jornada;
use app\models\Marca;
use app\models\ModeloPrefactura;
use app\models\PrefacturaFija;
use app\models\Presupuesto;
use app\models\Puesto;
use app\models\Responsable;
use app\models\Siniestro;
use app\models\Usuario;
use app\models\VisitaDia;
use app\models\VisitaMensual;
use app\models\ModeloPrefacturaElectronica;
use app\models\TipoAlarma;
use app\models\DescAlarma;
use app\models\MarcaAlarma;
use app\models\AreaDependencia;
use app\models\SistemaMonitoreado;
use app\models\ModeloMonitoreo;
use app\models\PreciosMonitoreo;
use app\models\PrefacturaElectronica;
use app\models\PrefacturaDispositivoFijoElectronico;
use app\models\PrefacturaDispositivoVariableElectronico;
use app\models\Incidente;
use app\models\FotoIncidente;
use app\models\GestionRiesgo;
use app\models\DetalleGestionRiesgo;
use app\models\PrefacturaMonitoreo;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use app\models\PrefacturaDispositivo;
use app\models\GruposPrefactura;
use app\models\CategoriaVisita;
use app\models\Novedad;
use app\models\NovedadDependencia;
use app\models\Capacitacion;
use app\models\VisitaMensualDetalle;
use app\models\NovedadCapacitacion;
use app\models\NovedadPedido;
use app\models\GerentesDependencia;
use app\models\LideresDependencia;
use app\models\CoordinadoresDependencia;

class CentroCostoController extends Controller
{
    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['index', 'capacitacion', 'comite', 'prefacturas', 'deleteRenglon', 'modelo',
                		    'puestos', 'siniestro', 'visita', 'mensual', 'evento', 'informacion',
                		    'imagen', 'view', 'update', 'create', 'delete', 'deleteImagen','informe_empresas','asignar_empresa','informacion','inspSemestral','gestiones','detalle_gestiones'],
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['index', 'capacitacion', 'comite', 'prefacturas', 'deleteRenglon', 'modelo',
                            		  'puestos', 'siniestro', 'visita', 'mensual', 'evento', 'informacion',
                            		  'imagen', 'view', 'update', 'create', 'delete', 'deleteImagen','informe_empresas','asignar_empresa','informacion','inspSemestral','gestiones','detalle_gestiones'],
                        'roles'   => ['@'], //para usuarios logueados
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex(){
        Yii::$app->session->setTimeout(5400);
        //$roles = Yii::$app->session['rol-exito'];

        /* Yii::$app->mailer->compose()
        ->setFrom('sgsexito@cvsc.com.co')
        ->setTo('olsalas@uninorte.edu.co')
        ->setSubject('Prueba')
        ->setTextBody('Plain text content')
        ->setHtmlBody('<h1>Es una Prueba</h1>')
        ->send();*/

        $usuario          = Usuario::findOne(Yii::$app->session['usuario-exito']);
        $zonasUsuario     = array();
        $marcasUsuario    = array();
        $distritosUsuario = array();

        if ($usuario != null) {

            $zonasUsuario     = $usuario->zonas;
            $marcasUsuario    = $usuario->marcas;
            $distritosUsuario = $usuario->distritos;

        }

        $roles = Yii::$app->session['rol-exito'];

        if ($roles != null) {

            if (in_array("administrador", $roles)) {

                $dependencias = CentroCosto::find()->orderBy(['nombre' => SORT_ASC])->all();

            } else {

                $dependencias = CentroCosto::find()->where(['not in', 'estado', ['C']])->orderBy(['nombre' => SORT_ASC])->all();

            }

        }

        return $this->render('index', [
            'dependencias'     => $dependencias,
            'zonasUsuario'     => $zonasUsuario,
            'marcasUsuario'    => $marcasUsuario,
            'distritosUsuario' => $distritosUsuario,

        ]);
    }

    /*public function actionCapacitacion($id)
    {
        Yii::$app->session->setTimeout(5400);
        $roles = Yii::$app->session['rol-exito'];

        $capacitaciones = CapacitacionDependencia::find()->where(['centro_costo_codigo' => $id])
            ->orderBy(['capacitacion_id' => SORT_DESC])->all();

        return $this->render('capacitacion', [
            'capacitaciones'     => $capacitaciones,
            'codigo_dependencia' => $id,
            'capacitacion'       => 'active',

        ]);
    }*/

  public function actionCapacitacion($id)
    {

        
        Yii::$app->session->setTimeout(5400);
        $roles = Yii::$app->session['rol-exito'];

        $capacitaciones = CapacitacionDependencia::find()->where(['centro_costo_codigo' => $id])
            ->orderBy(['capacitacion_id' => SORT_DESC])->all();

        $novedades=Novedad::find()->where('tipo="C" AND estado="A"')->all();
        $connection = \Yii::$app->db;
        $torta=array();
        $capacitaciones_tema=array();
        $ano=date('Y');

        foreach ($novedades as  $value) {
            $sql='SELECT SUM(cd.cantidad) as cantidad,COUNT(capacitacion.id) as capacitaciones FROM capacitacion  
            inner join capacitacion_dependencia as cd on cd.capacitacion_id=capacitacion.id
            where cd.centro_costo_codigo=:dependencia AND capacitacion.novedad_id=:novedad  AND (YEAR(fecha_capacitacion)=:ano)
            ';
            $capDep= $connection->createCommand($sql, [
                ':dependencia' => $id,
                ':novedad'=>$value->id,
                ':ano'=>$ano
            ])->queryOne();
            $torta[]=array('name'=>$value->nombre,'y'=>(int)$capDep['cantidad']);
            $capacitaciones_tema[]=array('name'=>$value->nombre,'y'=>(int)$capDep['cantidad'],'capacitaciones'=>$capDep['capacitaciones']);
        }
    
        $torta=json_encode($torta);

        $novedadDependencia=new NovedadDependencia;

        //$novedades_seleccionadas=$novedadDependencia->find()->where('centro_costo_codigo="'.$id.'" ')->all();

        $novedades_seleccionadas=["Seguridad-en-Retail"=>20,"Vigías-Protección-de-Recursos"=>21];
        $array_semestre=[];
        $array_semestre2=[];
        foreach ($novedades_seleccionadas as $key=>$nov) {
            
            $sqlSem='SELECT COUNT(capacitacion.id) as cantidad FROM capacitacion  
            inner join capacitacion_dependencia as cd on cd.capacitacion_id=capacitacion.id
            where cd.centro_costo_codigo=:dependencia AND capacitacion.novedad_id=:novedad ';

            $consultaSem=$sqlSem.'AND (fecha_capacitacion BETWEEN "'.$ano.'-01-01" AND
            "'.$ano.'-06-31")';
            
            $capSem= $connection->createCommand($consultaSem, [
                ':dependencia' => $id,
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



        // echo "<pre>";
        // print_r($array_semestre);
        // echo "</pre>";

        // echo "<pre>";
        // print_r($array_semestre2);
        // echo "</pre>";

        return $this->render('capacitacion', [
            'capacitaciones'     => $capacitaciones,
            'codigo_dependencia' => $id,
            'capacitacion'       => 'active',
            'torta'              =>$torta,
            'capacitaciones_tema'=>$capacitaciones_tema,
            'array_semestre'     =>$array_semestre,
            'array_semestre2'    =>$array_semestre2

        ]);
    }


    public function actionConf_capacitacion($dependencia){
        $model=new NovedadDependencia;
        $novedades=Novedad::find()->where('tipo="C"')->all();

        $list_novedades=ArrayHelper::map($novedades,'id','nombre');
        $novedad_dep=$model->find()->where('centro_costo_codigo="'.$dependencia.'"')->all();
        $centro_costo=CentroCosto::find()->where('codigo="'.$dependencia.'"')->one();

        if ($model->load(Yii::$app->request->post()) ) {

            $model->setAttribute('centro_costo_codigo', $dependencia);
            $model->save();

            return $this->redirect(['conf_capacitacion', 'dependencia' => $dependencia ]);

        }else{

            return $this->render('conf_capacitacion', [
                'list_novedades'=>$list_novedades,
                'dependencia'=>$dependencia,
                'model'=>$model,
                'novedad_dep'=>$novedad_dep,
                'centro_costo'=>$centro_costo
            ]);
        }
    }   

     public function actionEdit_conf_capacitacion($id,$dependencia){
        $model=NovedadDependencia::findOne($id);

        $novedades=Novedad::find()->where('tipo="C"')->all();

        $list_novedades=ArrayHelper::map($novedades,'id','nombre');

        if ($model->load(Yii::$app->request->post()) ) {

            
            $model->save();

            return $this->redirect(['conf_capacitacion', 'dependencia' => $dependencia ]);

        }else{

            return $this->render('edit_conf_capacitacion', [
                'list_novedades'=>$list_novedades,
                'dependencia'=>$dependencia,
                'model'=>$model,
                
            ]);
        }
    }

    public function actionDelete_conf_capacitacion($id,$dependencia){
        $model=NovedadDependencia::findOne($id);
        $model->delete();
        return $this->redirect(['conf_capacitacion', 'dependencia' => $dependencia ]);
    }


    public function actionComite($id)
    {
        Yii::$app->session->setTimeout(5400);
        $roles = Yii::$app->session['rol-exito'];

        $comites = ComiteDependencia::find()->where(['centro_costo_codigo' => $id])
            ->orderBy(['comite_id' => SORT_DESC])->all();

        return $this->render('comite', [
            'comites'            => $comites,
            'codigo_dependencia' => $id,
            'comite'             => 'active',

        ]);
    }


    public  function actionInvestigaciones($id){
        Yii::$app->session->setTimeout(5400);

        $investigaciones=Incidente::find()->where(['centro_costo_codigo' => $id])->all();

        return $this->render('investigaciones', [
            'comites'            => $comites,
            'codigo_dependencia' => $id,
            'investigacion'             => 'active',
            'investigaciones'=>$investigaciones

        ]);
    } 

    public function actionPrefacturas($id)
    {
        Yii::$app->session->setTimeout(5400);
        $roles = Yii::$app->session['rol-exito'];

        $modelo = ModeloPrefactura::find()->where(['centro_costo_codigo' => $id])->all();
        $model_electronica=new ModeloPrefacturaElectronica();
        $modelo_electronica = $model_electronica->find()->where(['centro_costos_codigo' => $id])->all();

        $monitoreos=ModeloMonitoreo::find()->where(['centro_costo_codigo' => $id])->orderBy(['id' => SORT_ASC])->all();


        $query = (new \yii\db\Query())
        ->select(['(SELECT SUM(precio_dependencia) FROM admin_dispositivo WHERE id_admin=asu.id     ) AS TOTAL'])
        ->from('admin_supervision AS asu')
        ->innerJoin('admin_dependencia AS ad', 'asu.id=ad.id_admin')
        ->where('ad.centro_costos_codigo="'.$id.'"')
        ->createCommand();


        $row=$query->queryAll();
        $total=0;
        foreach ($row as $val) {
            $total=$total+$val['TOTAL'];
        }

        //echo $total;
        return $this->render('prefactura', [

            'modelo'        => $modelo,
            'modelo_electronica'=>$modelo_electronica,
            'model_elect'=>$model_electronica,
            'codigo_dependencia' => $id,
            'modelo_prefactura'  => 'active',
            'monitoreo'=>$monitoreos,
            'row'=>$row,
            'total'=>$total

        ]);
    }

    public function actionPrefacturaselectronica($id){
        Yii::$app->session->setTimeout(5400);
        $roles = Yii::$app->session['rol-exito'];
        $model=new ModeloPrefacturaElectronica();

        $modelo = $model->find()->where(['centro_costos_codigo' => $id])->all();

        return $this->render('prefactura_electronica', [
            'modelo'        => $modelo,
            'codigo_dependencia' => $id,
            'modelo_prefactura'  => 'active',
            'model'=>$model

        ]);
    }

    public function actionListadoPrefacturas($id)
    {
        Yii::$app->session->setTimeout(5400);
        $roles = Yii::$app->session['rol-exito'];

        $modelo = PrefacturaFija::find()->where(['centro_costo_codigo' => $id])->all();
        $model_dispositivo=new PrefacturaDispositivo();

        $pref_electronica=new PrefacturaElectronica(); 
        $modelo_electronica=$pref_electronica->find()->where(['centro_costo_codigo' => $id])->all();
        $modelo_fijo_elect=new PrefacturaDispositivoFijoElectronico();
        $modelo_variable_elect=new PrefacturaDispositivoVariableElectronico();
        $modelo_monitoreo=new PrefacturaMonitoreo();


        $model = $this->findModel($id);


        $query = (new \yii\db\Query())
        ->select(['(SELECT SUM(precio_dependencia) FROM admin_dispositivo WHERE id_admin=asu.id ) AS TOTAL','asu.mes','asu.ano','asu.id as id_admin','asu.estado'])
        ->from('admin_supervision AS asu')
        ->innerJoin('admin_dependencia AS ad', 'asu.id=ad.id_admin')
        ->where('ad.centro_costos_codigo="'.$id.'"')
        ->orderBy(['asu.mes' => SORT_ASC])
        ->createCommand();

        $rows=$query->queryAll();




        return $this->render('prefactura_listado', [
            'model' => $model,
            'modelo' => $modelo,
            'codigo_dependencia' => $id,
            'modelo_prefactura' => 'active',
            'model_dispositivo'=>$model_dispositivo,
            'modelo_electronica'=>$modelo_electronica,
            'modelo_fijo_elect'=>$modelo_fijo_elect,
            'modelo_variable_elect'=>$modelo_variable_elect,
            'pref_electronica'=>$pref_electronica,
            'monitoreo'=>$modelo_monitoreo,
            'rows'=>$rows
        ]);
    }

    public function actionDeleteRenglon($id, $dependencia)
    {
        $model = ModeloPrefactura::findOne($id);

        if ($model != null) {

            $model->delete();
        }

        return $this->redirect(['modelo', 'id' => $dependencia]);
    }


    public function actionDeletedispositvoelct($id, $dependencia){

        $model = ModeloPrefacturaElectronica::findOne($id);

        if ($model != null) {

            $model->delete();
        }
        Yii::$app->session->setFlash('success','Dispositivo eliminado correctamente');
        return $this->redirect(['modeloelectronico', 'id' => $dependencia]);

    }


    public function actionModelo($id){
        //$this->layout = 'main_sin_menu';
        Yii::$app->session->setTimeout(5400);
        $grupos=new GruposPrefactura;
        //obtener filas del modelo de prefactura

        $modelo_pref=new ModeloPrefactura;
        $filas_modelo = $modelo_pref->find()->where(['centro_costo_codigo' => $id])->orderBy(['detalle_servicio_id' => SORT_ASC, 'puesto_id' => SORT_ASC])->all();

        $grupos_all=$grupos->find()->where('codigo_dependencia="'.$id.'" ')->all();
        $lista_grupos=ArrayHelper::map($grupos_all,'id','nombre');

        $puestos=CentroCosto::Puestos();


        return $this->render('modelo', [
            'codigo_dependencia' => $id,
            'modelo_prefactura'  => 'active',
            'filas_modelo'       => $filas_modelo,
            'grupos'=>$grupos,
            'list_grupos'=>$lista_grupos,
            'model'=>$modelo_pref,
            'puestos'=>$puestos

        ]);
    }

    public function actionGuardar_grupo($id){
        $model=new GruposPrefactura;
        $array_post = Yii::$app->request->post();
        $contar=$model->find()->where('codigo_dependencia="'.$id.'" ')->count();

        if($contar<5){
            if ($model->load($array_post)) {
                $model->setAttribute('codigo_dependencia', $id);
                if($model->save()){

                  Yii::$app->session->setFlash('success','Grupo creado correctamente');
                  return $this->redirect(['modelo', 'id' => $id]);
                }
            }
        }else{

            Yii::$app->session->setFlash('danger','El limite de grupos es de 5');
            return $this->redirect(['modelo', 'id' => $id]);

        }
        
    }


    public function actionAsignar_grupo($disp,$codigo){
        ModeloPrefactura::updateAll(['id_grupo' => $_POST['grupo']], ['=', 'id', $disp]);

        Yii::$app->session->setFlash('success','Asignado correctamente');

        return $this->redirect(['modelo', 'id' => $codigo]);


    }

    public function actionAsignar_puesto($disp,$codigo){
        ModeloPrefactura::updateAll(['puesto_id' => $_POST['puestos']], ['=', 'id', $disp]);

        Yii::$app->session->setFlash('success','Asignado correctamente');

        return $this->redirect(['modelo', 'id' => $codigo]);


    }


    public function actionModeloelectronico($id){
        //$this->layout = 'main_sin_menu';
        Yii::$app->session->setTimeout(5400);


        $filas_modelo = ModeloPrefacturaElectronica::find()->where(['centro_costos_codigo' => $id])->orderBy(['id' => SORT_ASC])->all();

        $monitoreos=ModeloMonitoreo::find()->where(['centro_costo_codigo' => $id])->orderBy(['id' => SORT_ASC])->all();

        return $this->render('modelo_electronico', [
            'codigo_dependencia' => $id,
            'modelo_prefactura'  => 'active',
            'filas_modelo'       => $filas_modelo,
            'monitoreos'=>$monitoreos

        ]);
    }


    public function actionCreatemonitoreo($id){
        //$this->layout = 'main_sin_menu';
        Yii::$app->session->setTimeout(5400);
        $model=new ModeloMonitoreo();
        $array_post = Yii::$app->request->post();
        $sistema_mon=SistemaMonitoreado::find()->all();
        $list_sistema_mon=ArrayHelper::map($sistema_mon,'id','nombre');
        $empresas=Empresa::find()->where(['seguridad_electronica'=>'S'])->all();
        $list_empresas=ArrayHelper::map($empresas,'nit','nombre');
        if ($model->load($array_post)) {
            $model->setAttribute('centro_costo_codigo', $id);
            $model->setAttribute('fecha_inicio', $array_post['fecha_inicio']);
            $model->setAttribute('fecha_fin', $array_post['fecha_fin']);
            $model->save();
            Yii::$app->session->setFlash('success','Monitoreo creado correctamente');
            return $this->redirect(['modeloelectronico', 'id' => $id]);

        }else{

            return $this->render('create_monitoreo', [
                'codigo_dependencia' => $id,
                'modelo_prefactura'  => 'active',
                'sistema_monitoreado' => $list_sistema_mon,
                'model'=>$model,
                'empresas'=>$list_empresas

            ]);
        }

    }

    public function actionUpdatemonitoreo($id,$id_monitoreo){

        //$this->layout = 'main_sin_menu';
        Yii::$app->session->setTimeout(5400);

        $model=ModeloMonitoreo::findOne($id_monitoreo);
        $array_post = Yii::$app->request->post();
        $sistema_mon=SistemaMonitoreado::find()->all();
        $list_sistema_mon=ArrayHelper::map($sistema_mon,'id','nombre');

        $empresas=Empresa::find()->where(['seguridad_electronica'=>'S'])->all();
        $list_empresas=ArrayHelper::map($empresas,'nit','nombre');

        if ($model->load($array_post)) {
            $model->setAttribute('centro_costo_codigo', $id);
            $model->setAttribute('fecha_inicio', $array_post['fecha_inicio']);
            $model->setAttribute('fecha_fin', $array_post['fecha_fin']);
            $model->save();
            Yii::$app->session->setFlash('success','Monitoreo actualizado correctamente');
            return $this->redirect(['modeloelectronico', 'id' => $id]);
        }else{
            return $this->render('create_monitoreo', [
                    'codigo_dependencia' => $id,
                    'modelo_prefactura'  => 'active',
                    'sistema_monitoreado' => $list_sistema_mon,
                    'model'=>$model,
                    'actualizar'=>'S',
                    'empresas'=>$list_empresas

                ]);
        }
    }

    public function actionDeletemonitoreo($id,$dependencia){

        $model=ModeloMonitoreo::findOne($id);

        $model->delete();

        Yii::$app->session->setFlash('success','Monitoreo eliminado correctamente');
        return $this->redirect(['modeloelectronico', 'id' => $dependencia]);

    }

    public function actionPreciomonitoreo(){

        $centro_costo=CentroCosto::find()->where(['codigo' => $_POST['centro_costo']])->one();

        $empresa=$centro_costo->empresa_electronica;
        //$empresa=$_POST['empresa'];
        $sistema=$_POST['sistema'];
        //$year=date('Y');
        $year='2019';

         $precio_monitoreo=PreciosMonitoreo::find()->where([
             'id_empresa' =>$empresa,
             'id_sistema_monitoreo'=>$sistema,
             'ano'=>$year
             ])->one();

        echo Json::encode(['precio' => $precio_monitoreo->valor_unitario]);
        
    }



    public function actionPuestos()
    {

        $out      = [];
        $servicio = '3';
        if (isset($_POST['depdrop_parents'])) {

            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {

                $servicio = $parents[0];

                $puestos = Puesto::find()->where(['servicio_id' => $servicio])
                    ->all();

                $data         = array();
                $defaultValue = '';

                foreach ($puestos as $key) {

                    $data[]       = array('id' => $key->id, 'name' => $key->nombre);
                    $defaultValue = $key->id;
                }

                $value = (count($data) === 0) ? ['' => ''] : $data;

                $out = $value;

                echo Json::encode(['output' => $out]);
                return;
            }

        }

        echo Json::encode(['output' => '', 'selected' => '']);

    }

    public function actionSiniestro($id)
    {
        Yii::$app->session->setTimeout(5400);
        $roles = Yii::$app->session['rol-exito'];

        $siniestros = Siniestro::find()->where(['centro_costo_codigo' => $id])
            ->orderBy(['id' => SORT_DESC])->all();

        return $this->render('siniestro', [
            'siniestros'         => $siniestros,
            'codigo_dependencia' => $id,
            'siniestro'          => 'active',

        ]);
    }



    public function actionVisita($id)
    {
        Yii::$app->session->setTimeout(5400);
        $roles = Yii::$app->session['rol-exito'];
        $arr_meses=array('01'=>'Enero','02'=>'Febrero','03'=>'Marzo','04'=>'Abril','05'=>'Mayo','06'=>'Junio','07'=>'Julio','08'=>'Agosto',
            '09'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre'
        );

        $model_visita=new VisitaDia;
        $ano=date('Y');

        $fecha_inicio=isset($_POST['fecha_inicial'])?$_POST['fecha_inicial']:'';
        $fecha_final=isset($_POST['fecha_final'])?$_POST['fecha_final']:'';

        if ($fecha_inicio!='' AND $fecha_final!='' ) {

            $filtro_fecha=" AND DATE(fecha) between '".$_POST['fecha_inicial']."' AND '".$_POST['fecha_final']."' ";
        }else{

            $filtro_fecha=" AND (YEAR(fecha)='".$ano."')";
        }

        $visitas = $model_visita->find()->where('centro_costo_codigo ="'.$id.'" '.$filtro_fecha.' ')
            ->orderBy(['id' => SORT_DESC])->all();



        $categorias=CategoriaVisita::find()->all();

        
        $rows_bueno = (new \yii\db\Query())
        ->select(['COUNT(dvd.id)AS total','resultado.nombre'])
        ->from('detalle_visita_dia AS dvd')
        ->leftJoin(['visita_dia AS vd'], 'dvd.visita_dia_id=vd.id')
        ->leftJoin(['resultado'], 'dvd.resultado_id=resultado.id')
        ->where("(resultado.nombre='Bueno' OR resultado.nombre='Malo'  OR resultado.nombre='Regular')  AND (vd.centro_costo_codigo='".$id."') ");
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

        //echo"<pre>";
        //print_r($json_bueno);
        //echo"</pre>";

         $rows_negativo = (new \yii\db\Query())
        ->select(['COUNT(dvd.id)AS total','ct.nombre'])
        ->from('detalle_visita_dia AS dvd')
        ->leftJoin(['visita_dia AS vd'], 'dvd.visita_dia_id=vd.id')
        ->leftJoin(['resultado'], 'dvd.resultado_id=resultado.id')
        ->leftJoin(['novedad_categoria_visita AS nc'], 'dvd.novedad_categoria_visita_id=nc.id')
        ->leftJoin(['categoria_visita AS ct'], 'nc.categoria_visita_id=ct.id')
        ->where("( resultado.nombre='Malo' OR resultado.nombre='Regular') AND (vd.centro_costo_codigo='".$id."') ");

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

        return $this->render('visita', [
            'visitas'            => $visitas,
            'codigo_dependencia' => $id,
            'visita'             => 'active',
            'categorias'         =>$categorias,
            'json_bueno'         =>$json_bueno,
            'json_negativo'      =>$json_negativo,
            'arr_meses'          =>$arr_meses,
            'model_visita'       =>$model_visita,
            'fecha_inicio'       =>$fecha_inicio,
            'fecha_final'        =>$fecha_final

        ]);
    }

    public function actionMensual($id)
    {
        Yii::$app->session->setTimeout(5400);
        $roles   = Yii::$app->session['rol-exito'];
        $visitas = VisitaMensual::find()->where(['centro_costo_codigo' => $id])
            ->orderBy(['id' => SORT_DESC])->all();
        return $this->render('mensual', [
            'visitas'            => $visitas,
            'codigo_dependencia' => $id,
            'visita'             => 'active',
        ]);
    }
    public function actionEvento($id)
    {
        Yii::$app->session->setTimeout(5400);
        $roles = Yii::$app->session['rol-exito'];

        $visitas = Evento::find()->where(['centro_costo_codigo' => $id])
            ->orderBy(['id' => SORT_DESC])->all();

        return $this->render('evento', [
            'visitas'            => $visitas,
            'codigo_dependencia' => $id,
            'visita'             => 'active',

        ]);
    }

    public function actionInformacion($id)
    {
        Yii::$app->session->setTimeout(5400);
        $roles = Yii::$app->session['rol-exito'];

        $responsables = Responsable::find()->where(['centro_costo_codigo' => $id])
            ->orderBy(['nombre' => SORT_DESC])->all();

        return $this->render('informacion', [
            'responsables'       => $responsables,
            'codigo_dependencia' => $id,
            'informacion'        => 'active',
            'model'              => $this->findModel($id),

        ]);
    }

    public function actionImagen($id)
    {
        Yii::$app->session->setTimeout(5400);
        $roles                          = Yii::$app->session['rol-exito'];
        $array_post                     = Yii::$app->request->post();
        Yii::$app->params['uploadPath'] = Yii::$app->basePath . '/web/uploads/';
        $shortPath                      = '/uploads/';
        $model                          = $this->findModel($id);

        if (isset($array_post['cambiar'])) {

            $image = UploadedFile::getInstance($model, 'image');
            if ($image !== null) {
                date_default_timezone_set('America/Bogota');
                $fecha_registro = date('Ymd', time());

                $model->foto = $fecha_registro . '_' . utf8_encode($image->name);
                $ext         = end((explode(".", $image->name)));
                $path        = Yii::$app->params['uploadPath'] . $model->foto;
                $model->foto = $shortPath . $model->foto;
                $model->save();
                $image->saveAs($path);

                return $this->redirect(['informacion', 'id' => $id]);

            }

        }

        return $this->render('imagen', [
            'model'              => $model,
            'codigo_dependencia' => $id,
            //'visita' => 'active',

        ]);
    }

    /**
     * Displays a single CentroCosto model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new CentroCosto model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        Yii::$app->session->setTimeout(5400);
        $roles = Yii::$app->session['rol-exito'];

        $marcas     = Marca::find()->orderBy(['nombre' => SORT_ASC])->all();
        $ciudades   = Ciudad::find()->orderBy(['nombre' => SORT_ASC])->all();
        $distritos  = Distrito::find()->orderBy(['nombre' => SORT_ASC])->all();
        $empresas   = Empresa::find()/*->where(['seguridad_electronica'=>'N'])*/->orderBy(['nombre' => SORT_ASC])->all();

        $empresas_electronica=Empresa::find()->where(['seguridad_electronica'=>'S'])->all();
        $list_empresas=ArrayHelper::map($empresas_electronica,'nit','nombre');


        $array_post = Yii::$app->request->post();
        $model      = new CentroCosto();
        $model->fecha_apertura=date('Y-m-d');

        $usuario          = Usuario::findOne(Yii::$app->session['usuario-exito']);
        $zonasUsuario     = array();
        $marcasUsuario    = array();
        $distritosUsuario = array();

        if ($usuario != null) {

            $zonasUsuario     = $usuario->zonas;
            $marcasUsuario    = $usuario->marcas;
            $distritosUsuario = $usuario->distritos;

        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $distrito = array_key_exists('distrito', $array_post) ? $array_post['distrito'] : '';
            $model->setAttribute('fecha_apertura', $array_post['fecha_apertura-centrocosto-fecha_apertura-disp']);
            $model->save();

            if ($distrito != '') {

                $model_r = new CentroDistrito();
                $model_r->setAttribute('distrito_id', $distrito);
                $model_r->setAttribute('centro_costo_codigo', $model->codigo);
                $model_r->save();

            }
            return $this->redirect('index');
        } else {
            return $this->render('create', [
                'model'            => $model,
                'marcas'           => $marcas,
                'ciudades'         => $ciudades,
                'distritos'        => $distritos,
                'zonasUsuario'     => $zonasUsuario,
                'marcasUsuario'    => $marcasUsuario,
                'distritosUsuario' => $distritosUsuario,
                'empresas'         => $empresas,
                'empresas_electronica'=>$list_empresas
            ]);
        }
    }

    /**
     * Updates an existing CentroCosto model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        Yii::$app->session->setTimeout(5400);
        $roles    = Yii::$app->session['rol-exito'];
        $empresas = Empresa::find()->orderBy(['nombre' => SORT_ASC])->all();

        $empresas_electronica=Empresa::find()->where(['seguridad_electronica'=>'S'])->all();
        $list_empresas=ArrayHelper::map($empresas_electronica,'nit','nombre');

        $model            = $this->findModel($id);

        if($model->fecha_apertura=='0000-00-00'){
            $model->fecha_apertura=date('Y-m-d');
        }

        $array_post       = Yii::$app->request->post();
        $marcas           = Marca::find()->orderBy(['nombre' => SORT_ASC])->all();
        $ciudades         = Ciudad::find()->orderBy(['nombre' => SORT_ASC])->all();
        $distritos        = Distrito::find()->orderBy(['nombre' => SORT_ASC])->all();
        $usuario          = Usuario::findOne(Yii::$app->session['usuario-exito']);
        $zonasUsuario     = array();
        $marcasUsuario    = array();
        $distritosUsuario = array();

        if ($usuario != null) {

            $zonasUsuario     = $usuario->zonas;
            $marcasUsuario    = $usuario->marcas;
            $distritosUsuario = $usuario->distritos;

        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $distrito = array_key_exists('distrito', $array_post) ? $array_post['distrito'] : '';
            $model->setAttribute('fecha_apertura', $array_post['fecha_apertura-centrocosto-fecha_apertura-disp']);
            $model->save();

            $presupuesto = Presupuesto::find()->where(['centro_costo_codigo' => $model->codigo])->one();

            if ($presupuesto != null) {

                $presupuesto->setAttribute('estado_dependencia', $model->estado);
                $presupuesto->save();

            }

            if ($distrito != '') {

                $primaryConnection = Yii::$app->db;
                $primaryCommand    = $primaryConnection->createCommand("DELETE
                    FROM centro_distrito
                    WHERE centro_costo_codigo = :centro
                    ");
                $primaryCommand->bindValue(':centro', $model->codigo)->execute();

                $model_r = new CentroDistrito();
                $model_r->setAttribute('distrito_id', $distrito);
                $model_r->setAttribute('centro_costo_codigo', $model->codigo);
                $model_r->save();

            }

            return $this->redirect('index');
        } else {
            return $this->render('update', [
                'model'            => $model,
                'marcas'           => $marcas,
                'ciudades'         => $ciudades,
                'distritos'        => $distritos,
                'zonasUsuario'     => $zonasUsuario,
                'marcasUsuario'    => $marcasUsuario,
                'distritosUsuario' => $distritosUsuario,
                'empresas'         => $empresas,
                'empresas_electronica'=>$list_empresas
            ]);
        }
    }

    /**
     * Deletes an existing CentroCosto model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionDeleteImagen($id)
    {

        /*Server bluehost*/
        //$prefijo = '/home/cvsccomc/public_html/sgs/web';

        /*Servidor Local*/
        $prefijo = Yii::getAlias('@web');

        $model = $this->findModel($id);

        $name = ($model->foto == null) ? '' : $model->foto;

        $filename = $prefijo . $name;

        if (file_exists($filename)) {

            unlink($filename);
        }

        $model->setAttribute('foto', null);

        $model->save();

        return $this->redirect(['informacion', 'id' => $id]);
    }

    public function actionCalcular_total(){

        $model      = new ModeloPrefactura();
        $query=$model->find()->all();
        $jornada  = Jornada::find()->all();
        //$year = date('Y',time());
        $year = '2019';
        $servicios  = DetalleServicio::find()->where("ano='".$year."'")->orderBy(['codigo' => SORT_ASC])->all();
        //print_r($servicios);
        return $this->render('calcular_total', [
                'model'=> $model,
                'query'=>$query,
                'jornada'=>$jornada,
                'servicios'=>$servicios
                
            ]);
    }


    public function actionActualizar_precio(){
        ModeloPrefactura::updateAll(['valor_mes' => $_POST['total'],'ftes'=>$_POST['total_ftes'],'ftes_diurno'=>$_POST['ftes_diurno'],'ftes_nocturno'=>$_POST['ftes_nocturno'] ], ['=', 'id', $_POST['id'] ]);
    }


    public function actionCalcular_monitoreo(){

        $model=ModeloMonitoreo::find()->all();
        return $this->render('calcular_monitoreo', [
                'query'=>$model
                
            ]);
    }

    public function actionActualizar_precio_monitoreo(){

        ModeloMonitoreo::updateAll(['valor_total' => $_POST['calculo'],'valor_unitario'=>$_POST['valor_unitario'] ], ['=', 'id', $_POST['id'] ]);   
    }



    public function actionVentana_calcular(){


        return $this->render('ventana_calcular', []);
    }

    public function actionCreateModelo($id,$grupo=0)
    {
        
        //$this->layout = 'main_sin_menu';
        Yii::$app->session->setTimeout(5400);
        $array_post = Yii::$app->request->post();
        $roles      = Yii::$app->session['rol-exito'];
        $model      = new ModeloPrefactura();
        date_default_timezone_set ( 'America/Bogota');
        //$year = date('Y',time());
        $year ='2019';
        $servicios  = DetalleServicio::find()->where("ano='".$year."'")->orderBy(['codigo' => SORT_ASC])->all();
        $puesto  = Puesto::find()->where('estado="A"')->all();
        $jornada  = Jornada::find()->all();

        if (isset($array_post) && array_key_exists('hora_fin2', $array_post)) {
            $cantidad = $array_post['ModeloPrefactura']['cantidad_servicios'];
            $detalle_servicio  = $array_post['ModeloPrefactura']['detalle_servicio_id'];
            //echo $array_post['ModeloPrefactura']['cantidad_servicios'];exit();
            //print_r($array_post);
            //echo "aqui".$cantidad;exit();
            if ($detalle_servicio != 0) {
                //guardar filas
                $modelo_prefactura = new ModeloPrefactura();
                $puesto = $array_post['ModeloPrefactura']['puesto_id'];
                $can_servicio = $array_post['ModeloPrefactura']['cantidad_servicios'];
                $jornada = $array_post['ModeloPrefactura']['horas'];
                $desde = $array_post['ModeloPrefactura']['hora_inicio'];
                $hasta = $array_post['hora_fin2'];
                $porcentaje = $array_post['ModeloPrefactura']['porcentaje'];
                $ftes = $array_post['ftes2'];
                $ftes_diurno = $array_post['ftes_diurno'];
                $ftes_nocturno = $array_post['ftes_nocturno'];
                $dias_totales = $array_post['dias_prestados2'];
                $precio = $array_post['valor_servicio2'];

                $lunes = $array_post['ModeloPrefactura']['lunes'];
                $martes = $array_post['ModeloPrefactura']['martes'];
                $miercoles = $array_post['ModeloPrefactura']['miercoles'];
                $jueves = $array_post['ModeloPrefactura']['jueves'];
                $viernes = $array_post['ModeloPrefactura']['viernes'];
                $sabado = $array_post['ModeloPrefactura']['sabado'];
                $domingo = $array_post['ModeloPrefactura']['domingo'];
                $festivo = $array_post['ModeloPrefactura']['festivo'];
                

                if ($lunes == '1') {
                    $modelo_prefactura->setAttribute('lunes', 'X');
                }
                if ($martes == '1') {
                    $modelo_prefactura->setAttribute('martes', 'X');
                }
                if ($miercoles == '1') {
                    $modelo_prefactura->setAttribute('miercoles', 'X');
                }
                if ($jueves == '1') {
                    $modelo_prefactura->setAttribute('jueves', 'X');
                }
                if ($viernes == '1') {
                    $modelo_prefactura->setAttribute('viernes', 'X');
                }
                if ($sabado == '1') {
                    $modelo_prefactura->setAttribute('sabado', 'X');
                }
                if ($domingo == '1') {
                    $modelo_prefactura->setAttribute('domingo', 'X');
                }
                if ($festivo == '1') {
                    $modelo_prefactura->setAttribute('festivo', 'X');
                }
                $modelo_prefactura->setAttribute('detalle_servicio_id', $detalle_servicio);
                $modelo_prefactura->setAttribute('puesto_id', $puesto);
                $modelo_prefactura->setAttribute('cantidad_servicios', $can_servicio);
                $modelo_prefactura->setAttribute('horas', $jornada);
                $modelo_prefactura->setAttribute('hora_inicio', $desde);
                $modelo_prefactura->setAttribute('hora_fin', $hasta);
                $modelo_prefactura->setAttribute('porcentaje', $porcentaje);
                $modelo_prefactura->setAttribute('ftes', $ftes);
                $modelo_prefactura->setAttribute('ftes_diurno', $ftes_diurno);
                $modelo_prefactura->setAttribute('ftes_nocturno', $ftes_nocturno);
                $modelo_prefactura->setAttribute('total_dias', $dias_totales);
                $modelo_prefactura->setAttribute('valor_mes', $precio);
                $modelo_prefactura->setAttribute('centro_costo_codigo', $id);
                if ($grupo!=0) {
                    $modelo_prefactura->setAttribute('id_grupo', $grupo);
                }
                

                if($modelo_prefactura->save()){
                    return $this->redirect(['modelo', 'id' => $id]);
                }else{
                    print_r($modelo_prefactura->getErrors());
                }
            }
        }else{
            return $this->render('modelo_create', [
                'codigo_dependencia' => $id,
                'servicios'          => $servicios,
                'puesto'          => $puesto,
                'modelo_prefactura'  => 'active',
                'model'              => $model,
                'jornada'              => $jornada,
                
            ]);
        }
    }

    public function actionCreatemodeloelectronica($id){
        //$this->layout = 'main_sin_menu';
        Yii::$app->session->setTimeout(5400);
        $array_post = Yii::$app->request->post();
        $roles      = Yii::$app->session['rol-exito'];
        $model      = new ModeloPrefacturaElectronica();

        $meses=array();
        

        for ($i=1; $i <= 60 ; $i++) { 
            $clave = (string) $i;
            $meses[$clave]=$i;
            
        }



        $tipos_alarma=TipoAlarma::find()->orderBy(['nombre' => SORT_ASC])->all();
        $list_alarmas=ArrayHelper::map($tipos_alarma,'id','nombre');


        $marcas_alarma=MarcaAlarma::find()->orderBy(['nombre' => SORT_ASC])->all();
        $list_marcas_alarmas=ArrayHelper::map($marcas_alarma,'id','nombre');        

        $areas=AreaDependencia::find()->all();
        $list_areas=ArrayHelper::map($areas,'id','nombre');      

        //$empresas=Empresa::find()->where(['seguridad_electronica'=>'S'])->all();  
        //$list_empresas=ArrayHelper::map($empresas,'nit','nombre');        

        if ($model->load($array_post)) {
            

            $model->setAttribute('fecha_inicio', $array_post['fecha_inicio']);
            $model->setAttribute('fecha_ultima_reposicion', $array_post['fecha_ultima_reposicion']);
            $model->setAttribute('valor_arrendamiento_mensual', $array_post['modeloprefacturaelectronica-valor_arrendamiento_mensual-disp']);
            $model->setAttribute('centro_costos_codigo', $array_post['centro_costo']);

            $model->save();
            Yii::$app->session->setFlash('success','Dispositivo creado correctamente');
            return $this->redirect(['modeloelectronico', 'id' => $id]);
        }else{  

            return $this->render('modelo_create_electronico', [
                'codigo_dependencia' => $id,
                'model'              => $model,
                'modelo_prefactura'  => 'active',
                'alarmas'=>$list_alarmas,
                'marcas_alarma'=>$list_marcas_alarmas,
                'meses'=>$meses,
                'areas'=>$list_areas,
                //'empresas'=>$list_empresas
                ]);
        }

    }


    function actionUpdatemodeloelectronica($id,$id_prefactura){
        //$this->layout = 'main_sin_menu';
        Yii::$app->session->setTimeout(5400);
        $array_post = Yii::$app->request->post();
        $roles      = Yii::$app->session['rol-exito'];
        $model=ModeloPrefacturaElectronica::findOne($id_prefactura);

         $meses=array();
        

        for ($i=1; $i <= 60 ; $i++) { 
            $clave = (string) $i;
            $meses[$clave]=$i;
            
        }



        $tipos_alarma=TipoAlarma::find()->orderBy(['nombre' => SORT_ASC])->all();
        $list_alarmas=ArrayHelper::map($tipos_alarma,'id','nombre');


        $marcas_alarma=MarcaAlarma::find()->orderBy(['nombre' => SORT_ASC])->all();
        $list_marcas_alarmas=ArrayHelper::map($marcas_alarma,'id','nombre');        

        $areas=AreaDependencia::find()->all();
        $list_areas=ArrayHelper::map($areas,'id','nombre'); 

        $descripcion=DescAlarma::find()->where("id_tipo_alarma=".$model->id_tipo_alarma)->all();
        $list_descripcion=ArrayHelper::map($descripcion,'id','descripcion');      

        $empresas=Empresa::find()->where(['seguridad_electronica'=>'S'])->all();  
        $list_empresas=ArrayHelper::map($empresas,'nit','nombre');      

        if ($model->load($array_post)) {
            

            $model->setAttribute('fecha_inicio', $array_post['fecha_inicio']);
            $model->setAttribute('fecha_ultima_reposicion', $array_post['fecha_ultima_reposicion']);
            $model->setAttribute('valor_arrendamiento_mensual', $array_post['modeloprefacturaelectronica-valor_arrendamiento_mensual-disp']);
            $model->setAttribute('centro_costos_codigo', $array_post['centro_costo']);

            $model->save();
            Yii::$app->session->setFlash('success','Dispositivo actualizado correctamente');
            return $this->redirect(['modeloelectronico', 'id' => $id]);
        }else{ 
            return $this->render('modelo_create_electronico', [
                    'codigo_dependencia' => $id,
                    'model'              => $model,
                    'modelo_prefactura'  => 'active',
                    'alarmas'=>$list_alarmas,
                    'marcas_alarma'=>$list_marcas_alarmas,
                    'meses'=>$meses,
                    'areas'=>$list_areas,
                    'list_descripcion'=>$list_descripcion,
                    'actualizar'=>'s',
                    'empresas'=>$list_empresas
                    ]);
        }

    }


    public function actionDescripcion_alarma(){
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);
            $desc_alarma=DescAlarma::find()->where("id_tipo_alarma=".$id)->all();
            $selected  = null;
            if ($id != null && count($desc_alarma) > 0) {
                $selected = '';
                foreach ($desc_alarma as $i => $row) {
                    $out[] = ['id' => $row['id'], 'name' => $row['descripcion']];
                    if ($i == 0) {
                        $selected = $row['id'];
                    }
                }
                // Shows how you can preselect a value
                echo Json::encode(['output' => $out, 'selected'=>$selected]);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected'=>'']);
    }


    public function actionInforme_empresas(){
        $model=new CentroCosto();
        $permisos = array();
        if( isset(Yii::$app->session['permisos-exito']) ){
            $permisos = Yii::$app->session['permisos-exito'];
        }

        if(!in_array("administrador", $permisos)){
            $rows = (new \yii\db\Query())
            ->select(['cc.empresa','cc.codigo','cc.cebe', 'cc.ceco','cc.nombre','marca.nombre AS marca','ciudad.nombre AS ciudad'])
            ->from('usuario_zona')
            ->leftJoin('ciudad_zona', 'usuario_zona.zona_id=ciudad_zona.zona_id')
            ->leftJoin('centro_costo AS cc', 'ciudad_zona.ciudad_codigo_dane=cc.ciudad_codigo_dane')
            ->leftJoin('marca', 'cc.marca_id=marca.id')
            ->leftJoin('ciudad', 'cc.ciudad_codigo_dane=ciudad.codigo_dane')
            ->where(['cc.empresa' => ''])
            ->andWhere("cc.estado NOT IN ('C')")
            ->andWhere("usuario_zona.usuario='".Yii::$app->session['usuario-exito']."'")
            ->all()
            ;
       
            //echo "no es admin";
        }else{
            $rows = (new \yii\db\Query())
            ->select(['cc.empresa','cc.codigo','cc.cebe', 'cc.ceco','cc.nombre','marca.nombre AS marca','ciudad.nombre AS ciudad'])
            ->from('usuario_zona')
            ->leftJoin('ciudad_zona', 'usuario_zona.zona_id=ciudad_zona.zona_id')
            ->leftJoin('centro_costo AS cc', 'ciudad_zona.ciudad_codigo_dane=cc.ciudad_codigo_dane')
            ->leftJoin('marca', 'cc.marca_id=marca.id')
            ->leftJoin('ciudad', 'cc.ciudad_codigo_dane=ciudad.codigo_dane')
            ->where(['cc.empresa' => ''])
            ->andWhere("cc.estado NOT IN ('C')")
            ->all()
            ;

        }
        
       $empresas=Empresa::find()->all();
       $list_empresas=ArrayHelper::map($empresas,'nit','nombre');
       return $this->render('informe_empresa', [
            'rows'=>$rows,
            'empresas'=>$list_empresas,
            'model'=>$model
        ]);
    }


    public function actionAsignar_empresa(){

        if ($_POST['empresa']=='') {
          Yii::$app->session->setFlash('danger','Selecciona una empresa de seguridad');
        }else{
            Yii::$app->db->createCommand()->update('centro_costo', ['empresa' =>$_POST['empresa']], 'codigo ="'.$_POST['id_dependencia'].'"')->execute();

            Yii::$app->session->setFlash('success','Empresa asignada correctamente');
        }
        
       return $this->redirect(['informe_empresas']);          
    }


    protected function findModel($id)
    {
        if (($model = CentroCosto::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionArchivos_investigacion(){
        $array_post = Yii::$app->request->post();

        $foto_incidente= FotoIncidente::find()->where('incidente_id='.$array_post['id'])->all();
        $files='';
        $contador=0;
        foreach ($foto_incidente as $row) {

            $nombre_archivo=str_replace("/uploads/", '', $row->imagen);

            $files.='<a href="'.$row->imagen.'" download title="Descargar archivo">
                        <i class="fa fa-download" aria-hidden="true"></i>
                    </a>'.$nombre_archivo."<br>";
            $contador++;
        }

        if ($contador==0) {
            $files='
                <div class="alert alert-danger" role="alert">
                    <h3><i class="fa fa-file-o" aria-hidden="true"></i> No existen archivos adjuntos</h3>
                </div>
            ';
        }
       \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'respuesta' => $files,
                ];
    }   


    function actionGestiones($id){
        $consulta=GestionRiesgo::find()->where('id_centro_costo="'.$id.'" ')->all();


        return $this->render('gestiones', [
            'consulta'=>$consulta,
            'codigo_dependencia'=>$id
            
        ]);

    }

    public function actionDelete_gestiones($id,$dependencia){

        $model = GestionRiesgo::findOne($id);

        $model_detalle=DetalleGestionRiesgo::deleteAll('id_gestion ='.$id.' ');


        if ($model != null) {

            $model->delete();

        }

        Yii::$app->session->setFlash('success',' Eliminado correctamente');

        return $this->redirect(['gestiones', 'id' => $dependencia]);
    }

    function actionDetalle_gestiones($id,$dependencia,$modulo,$usuario=0,$nombre_dependencia=''){
       // $this->layout = 'main_sin_menu';

        

        $consulta=DetalleGestionRiesgo::find()->where('id_gestion ="'.$id.'" ')->all();
        $model = GestionRiesgo::findOne($id);

         return $this->render('detalle_gestiones', [
            'consulta'=>$consulta,
            'codigo_dependencia'=>$dependencia,
            'modulo'=>$modulo,
            'usuario'=>$usuario,
            'nombre_dependencia'=>$nombre_dependencia,
            'id'=>$id,
            'model'=>$model
            
        ]);
    }

    function actionNovedad_investigacion($cc,$id,$detalle){

        $model=new NovedadIncidente();
        $tipo_novedad=TipoNovedadIncidente::find()->all();
        $list_tipo_novedad=ArrayHelper::map($tipo_novedad,'id','nombre');

        $usuarios=Usuario::find()->all();
        $list_usuarios=ArrayHelper::map($usuarios,'usuario','usuario');

        Yii::$app->params['uploadPath'] = Yii::$app->basePath . '/web/uploads/novedad_incidente/';
        $shortPath = '/uploads/novedad_incidente/';

        if ($model->load(Yii::$app->request->post()) /*&& $model->save()*/) {

            $model->setAttribute('id_incidente',$id);
            $model->setAttribute('fecha',$_POST['fecha']);
            $model->save();

            $images = UploadedFile::getInstances($model, 'image');

            //print_r($images);

            foreach($images as $image){

                  //echo "entra";
                  if($image !== null)
                  {
                      $foto = new FotoNovedadIncidente();    
                      $ext = end((explode(".", $image->name)));
                      $name = date('Ymd').rand(1, 10000).''.$image->name;
                      $path = Yii::$app->params['uploadPath'] . $name;
                      $foto->setAttribute('foto',$shortPath. $name);
                      $foto->setAttribute('id_novedad',$model->id);
                      //echo $path;
                      $image->saveAs($path);
                      $foto->save();

                  }

        
            }

            return $this->redirect(['investigaciones', 'id' => $cc]);

        }

        return $this->render('novedad_investigacion', [
           'cc'=>$cc,
           'detalle'=>$detalle,
           'list_tipo_novedad'=>$list_tipo_novedad,
           'model'=>$model,
           'list_usuarios'=>$list_usuarios
            
        ]);

    }


    function actionNovedades_investigacion($cc,$id){

        $novedades=NovedadIncidente::find()->where('id_incidente='.$id)->orderby(' id DESC')->all();

        return $this->render('detalle_novedades', [
           'cc'=>$cc,
           'id'=>$id,
           'novedades'=>$novedades
            
        ]);
    }

    function actionFoto_novedad_incidente($cc,$id,$incidente){
        $this->layout = 'main_sin_menu';

        $fotos=FotoNovedadIncidente::find()->where('id_novedad='.$id)->all();

        return $this->render('foto_novedad_incidente', [
           'cc'=>$cc,
           'id'=>$id,
           'incidente'=>$incidente,
           'fotos'=>$fotos
            
        ]);
    }

    public function actionInspSemestral($id){
        //$rows_negativo->andWhere("DATE(vd.fecha) between '".$_POST['fecha_inicial']."' AND '".$_POST['fecha_final']."'");
        $ano=date('Y');
        $NovedadVisitas=VisitaMensualDetalle::find()
        ->leftJoin('visita_mensual', 'visita_mensual_id= visita_mensual.id')
        ->where('visita_mensual.centro_costo_codigo="'.$id.'" AND  (YEAR(fecha_novedad)="'.$ano.'")  ');
        $cantidad_visitas=$NovedadVisitas->count();

        $NovedadCapacitacion=NovedadCapacitacion::find()
        ->leftJoin('visita_mensual', 'visita_mensual_id= visita_mensual.id')
        ->where('visita_mensual.centro_costo_codigo="'.$id.'" AND  (YEAR(fecha_novedad)="'.$ano.'")  ');

        $cantidad_capacitacion=$NovedadCapacitacion->count();

        $Novedadpedido=NovedadPedido::find()
        ->leftJoin('visita_mensual', 'visita_mensual_id= visita_mensual.id')
        ->where('visita_mensual.centro_costo_codigo="'.$id.'" AND  (YEAR(fecha_novedad)="'.$ano.'")  ');
        $cantidad_pedido=$Novedadpedido->count();

        $total_novedades=($cantidad_visitas+$cantidad_capacitacion+$cantidad_pedido);

        $mayoresNovedades=[array('name'=>'Visitas','y'=>(int)$cantidad_visitas ),array('name'=>'Capacitacion','y'=>(int)$cantidad_capacitacion),array('name'=>'Pedidos','y'=>(int)$cantidad_pedido )];

        $mayoresNovedades=json_encode($mayoresNovedades);
        
        $visitaMensual=VisitaMensual::find();
        
        $primerSemestre=$visitaMensual->where('centro_costo_codigo="'.$id.'" AND  fecha_visita BETWEEN "'.$ano.'-01-01" AND
            "'.$ano.'-06-30"')->count();

        $segundoSemestre=$visitaMensual->where('centro_costo_codigo="'.$id.'" AND fecha_visita BETWEEN "'.$ano.'-07-01" AND
            "'.$ano.'-12-31"')->count();
        
        $califPrimerSemestre=0;

        if($primerSemestre>=1){
            $califPrimerSemestre=100;

        }/*else if($primerSemestre>=2){
            $califPrimerSemestre=100;            
        }*/

        $califSegundoSemestre=0;

        if($segundoSemestre>=1){
            $califSegundoSemestre=100;

        }/*else if($segundoSemestre>=2){
            $califSegundoSemestre=100;            
        }*/

        $promedio_anual=($califPrimerSemestre+$califSegundoSemestre)/2;

        $queryVisitas=$visitaMensual->where('centro_costo_codigo="'.$id.'" ')->orderby(' fecha_visita DESC')->all();

        $categorias=CategoriaVisita::find()->all();
        $novedades = Novedad::find()->where('tipo="C" AND estado="A" ')->orderBy(['nombre' => SORT_ASC])->all();

        $array_novedades=[];
        foreach ($categorias as $key => $value) {

           $cantidad_categoria=VisitaMensualDetalle::find()
            ->leftJoin('visita_mensual', 'visita_mensual_id= visita_mensual.id')
            ->where('visita_mensual.centro_costo_codigo="'.$id.'" AND  (YEAR(fecha_novedad)="'.$ano.'")  ')
            ->andWhere(' visita_mensual_detalle.categoria_id='.$value->id.' ')->count();
           
           $array_novedades[]=array('name'=>$value->nombre,'y'=>(int)$cantidad_categoria);
        }

        foreach ($novedades as $key1 => $value1) {
           $cantidad_temas=NovedadCapacitacion::find()
            ->leftJoin('visita_mensual', 'visita_mensual_id= visita_mensual.id')
            ->where('visita_mensual.centro_costo_codigo="'.$id.'" AND  (YEAR(fecha_novedad)="'.$ano.'")  ')
            ->andWhere(' novedad_capacitacion.tema_cap_id='.$value1->id.' ')
            ->count();
           
           $array_novedades[]=array('name'=>$value1->nombre,'y'=>(int)$cantidad_temas);
        }

      
        $array_novedades=json_encode($array_novedades);
        // echo "<pre>";
        // print_r($array_novedades);
        // echo "</pre>";

        return $this->render('insp_semestral', [
         'mayoresNovedades'=>$mayoresNovedades,
         'codigo_dependencia'=>$id,
         'primerSemestre'=>$primerSemestre,
         'segundoSemestre'=>$segundoSemestre,
         'califPrimerSemestre'=>$califPrimerSemestre,
         'califSegundoSemestre'=>$califSegundoSemestre,
         'promedio_anual'=>$promedio_anual,
         'cantidad_visitas'=>$cantidad_visitas,
         'cantidad_capacitacion'=>$cantidad_capacitacion,
         'cantidad_pedido'=>$cantidad_pedido,
         'total_novedades'=>$total_novedades,
         'queryVisitas'=>$queryVisitas,
         'array_novedades'=>$array_novedades
        ]);
        
    }

    public function actionGerentes($codigo){
        $gerentesModel=new GerentesDependencia;
        if ($gerentesModel->load(Yii::$app->request->post())) {
            $post=Yii::$app->request->post();
            $usuarios=$post['GerentesDependencia']['usuario'];
            GerentesDependencia::deleteAll(['codigo_dependencia' => $codigo]);
            foreach ($usuarios as $key => $value) {
               $model=new GerentesDependencia;
               $model->setAttribute('usuario', $value);
               $model->setAttribute('codigo_dependencia', $codigo);

               $model->save();
            }
            return $this->redirect(['gerentes','codigo'=>$codigo]);
        }else{

            $gerentesModel->usuario=$gerentesModel->GetGerentes($codigo);
            return $this->render('gerentes', [
             'gerentesModel'=>$gerentesModel,
             'codigo'=>$codigo
            ]);
        }
    }

    public function actionLideres_seguridad($codigo){
        $gerentesModel=new LideresDependencia;
        if ($gerentesModel->load(Yii::$app->request->post())) {
            $post=Yii::$app->request->post();
            $usuarios=$post['LideresDependencia']['usuario'];
            LideresDependencia::deleteAll(['codigo_dependencia' => $codigo]);
            foreach ($usuarios as $key => $value) {
               $model=new LideresDependencia;
               $model->setAttribute('usuario', $value);
               $model->setAttribute('codigo_dependencia', $codigo);

               $model->save();
            }
            return $this->redirect(['lideres_seguridad','codigo'=>$codigo]);
        }else{

            $gerentesModel->usuario=$gerentesModel->GetGerentes($codigo);
            return $this->render('lideres_seguridad', [
             'gerentesModel'=>$gerentesModel,
             'codigo'=>$codigo
            ]);
        }
    }

    public function actionCoordinadores_seguridad($codigo){
        $gerentesModel=new CoordinadoresDependencia;
        if ($gerentesModel->load(Yii::$app->request->post())) {
            $post=Yii::$app->request->post();
            $usuarios=$post['CoordinadoresDependencia']['usuario'];
            CoordinadoresDependencia::deleteAll(['codigo_dependencia' => $codigo]);
            foreach ($usuarios as $key => $value) {
               $model=new CoordinadoresDependencia;
               $model->setAttribute('usuario', $value);
               $model->setAttribute('codigo_dependencia', $codigo);

               $model->save();
            }
            return $this->redirect(['coordinadores_seguridad','codigo'=>$codigo]);
        }else{

            $gerentesModel->usuario=$gerentesModel->GetGerentes($codigo);
            return $this->render('coordinadores_seguridad', [
             'gerentesModel'=>$gerentesModel,
             'codigo'=>$codigo
            ]);
        }
    }
}
