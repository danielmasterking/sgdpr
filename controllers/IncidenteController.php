<?php

namespace app\controllers;

use Yii;
use app\models\Incidente;
use app\models\Novedad;
use app\models\AreaDependencia;
use app\models\ZonaDependencia;
use app\models\FotoIncidente;
use app\models\CentroCosto;
use app\models\Usuario;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\VarDumper;
use kartik\mpdf\Pdf;
use yii\filters\AccessControl;
use app\models\NovedadIncidente;
use app\models\TipoNovedadIncidente;
use app\models\FotoNovedadIncidente;
use app\models\UsuarioIncidente;
use yii\helpers\ArrayHelper;
use app\models\TipoInfractor;
use app\models\InvestigacionInfractor;


/**
 * IncidenteController implements the CRUD actions for Incidente model.
 */
class IncidenteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['index', 'view', 'ViewFromCordinador', 'Pdf', 'create', 'Update', 'delete'],
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['index', 'view', 'ViewFromCordinador', 'Pdf', 'create', 'Update', 'delete'],
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
     * Lists all Incidente models.
     * @return mixed
     */
    public function actionIndex()
    {
       Yii::$app->session->setTimeout(5400);

       $permisos = array();

        if( isset(Yii::$app->session['permisos-exito']) ){

            $permisos = Yii::$app->session['permisos-exito'];

        }

        $usuario = Usuario::findOne(Yii::$app->session['usuario-exito']);

        $usuario_incidente=$usuario->incidentes;

        

        

        if(in_array("eliminar-investigacion", $permisos) or in_array("administrador", $permisos)){
            //////////DEPENDENCIAS DEL USUARIO
            $dependencias_user=$this->dependencias_usuario(Yii::$app->session['usuario-exito']);
            $in=" IN(";

            foreach ($dependencias_user as $value) {
            
                $in.=" '".$value."',";    
            }

            $in_final = substr($in, 0, -1).")";
            $investigaciones=Incidente::find()->where('centro_costo_codigo '.$in_final.' and fecha_creado<>"" ')->orderby('id DESC')->all();
            
        }else{
            $in=" (id IN(";

            $contador=0;
            foreach ($usuario_incidente as $ui) {
                $in.=" ".$ui->id_incidente.",";  
                $contador++;
            }

            if($contador >0){
              $in_final = substr($in, 0, -1).")) and ( fecha_creado<>'' )";
            }else{
              $in_final = '(usuario="'.Yii::$app->session['usuario-exito'].'")';
            }
            //echo $in_final;
            $investigaciones=Incidente::find()->where(' '.$in_final.' /*(usuario="'.Yii::$app->session['usuario-exito'].'") and( fecha_creado<>"" )*/')->orderby('id DESC')->all();
        }


        return $this->render('index', [
            'investigaciones'=>$investigaciones

        ]);
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
     * Displays a single Incidente model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        //$this->layout = 'main_sin_menu';
        $novedades=NovedadIncidente::find()->where('id_incidente='.$id)->orderby(' id ASC')->all();
        $model = $this->findModel($id);

        return $this->render('view', [
           
           'id'=>$id,
           'novedades'=>$novedades,
           'model'=>$model
            
        ]);
    }

    public function actionImprimir($id){
        $novedades=NovedadIncidente::find()->where('id_incidente='.$id)->orderby(' id ASC')->all();
        $model = $this->findModel($id);

        $content = $this->renderPartial('_imprimir', array(
             'id'=>$id,
           'novedades'=>$novedades,
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
            //'cssInline' => 'table, td, th {border: 1px solid black;} .kv-heading-1{font-size:18px}', 
             // set mPDF properties on the fly
            'options' => ['title' => 'Investigacion'],
             // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>['Investigacion-'.$model->dependencia->nombre.'-'.date('Y-m-d')], 
                'SetFooter'=>['{PAGENO}'],
            ]
        ]);*/
        $pdf = Yii::$app->pdf;
        $pdf->filename ='Investigacion-'.$model->dependencia->nombre.'-'.date('Y-m-d').'.pdf';
        $pdf->content =$content;
        $pdf->destination = Pdf::DEST_DOWNLOAD;
        // return the pdf output as per the destination setting
        return $pdf->render();

    }



	 public function actionViewFromCordinador($id)
    {
        return $this->render('viewcoordinador', [
            'model' => $this->findModel($id),
			
        ]);
    }

	public function actionPdf($id){
		
		$model = $this->findModel($id);
		
		$pdf = Yii::$app->pdf;
		
		$pdf->filename = 'Incidente_'.$model->fecha.'_'.$model->novedad->nombre.'.pdf';
		
		$pdf->content = $this->renderPartial('_pdf',['model' => $model],true);
        
		
		$pdf->destination = Pdf::DEST_DOWNLOAD ;
		
		return $pdf->render();
		
		//return $this->redirect('view', ['id' => $id,]);
		
	}		

    /**
     * Creates a new Incidente model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Incidente();

        $usuarios=Usuario::find()->all();
        $list_usuarios=ArrayHelper::map($usuarios,'usuario','usuario');
        $tipo_infractor=TipoInfractor::find()->all();
        $list_tipo_infractor=ArrayHelper::map($tipo_infractor,'id','nombre');

        Yii::$app->session->setTimeout(5400);
	    $novedades = Novedad::find()->where(['tipo' => 'I'])->orderBy(['nombre' => SORT_ASC])->all();
		$dependencias = CentroCosto::find()->where(['not in', 'estado', ['C']])->orderBy(['nombre' => SORT_ASC])->all();
		//$zonas = ZonaDependencia::find()->orderBy(['nombre' => SORT_ASC])->all();
		$usuario = Usuario::findOne(Yii::$app->session['usuario-exito']);
		$zonasUsuario = array();
		$marcasUsuario = array();
		$distritosUsuario = array();
		
		if($usuario != null){
		  
          $zonasUsuario = $usuario->zonas;		
          $marcasUsuario = $usuario->marcas;
          $distritosUsuario = $usuario->distritos;		  
			
		}
		
		Yii::$app->params['uploadPath'] = Yii::$app->basePath . '/web/uploads/';
        $shortPath = '/uploads/';		

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			
			
			  //$fecha_real_visita = strtotime('+1 day' , strtotime ( $model->fecha ));
			  //$fecha_real_visita = date('Y-m-d',$fecha_real_visita);
			  $model->setAttribute('fecha',$model->fecha );
              $model->setAttribute('fecha_creado',date('Y-m-d H:i:s'));
			  $model->save(); 
			
			
			/*********************** Validar Imagen ****************/
			$images = UploadedFile::getInstances($model, 'image');
			$image_incidente = UploadedFile::getInstance($model, 'image2');
			//VarDumper::dump($images);
			
			foreach($images as $image){

				  if($image !== null)
				  {
					  $foto_incidente = new FotoIncidente();	
					  $ext = end((explode(".", $image->name)));
				      $name = date('Ymd').rand(1, 10000).''.$image->name;
				      $path = Yii::$app->params['uploadPath'] . $name;
				      $foto_incidente->setAttribute('imagen',$shortPath. $name);
				      $foto_incidente->setAttribute('incidente_id',$model->id);
					  $image->saveAs($path);
					  $foto_incidente->save();

				  }

		
			}
			
			if($image_incidente != null){
				
				$ext = end((explode(".", $image_incidente->name)));
				$name = date('Ymd').rand(1, 10000).''.$image_incidente->name;
				$path = Yii::$app->params['uploadPath'] . $name;
				$model->setAttribute('imagen', $shortPath. $name);
				$image_incidente->saveAs($path);
			    $model->save();
				
			}
			


          if(isset($_POST['usuarios'])){
            $users=$_POST['usuarios'];
            array_unshift($users,Yii::$app->session['usuario-exito']);
            //print_r($users);
          }else{
            $users=array(Yii::$app->session['usuario-exito']);
          }
            

            if(isset($users)){
                foreach ($users as $rowuser) {
                   $usuario_incidente=new UsuarioIncidente();
                    $usuario_incidente->setAttribute('usuario', $rowuser);
                    $usuario_incidente->setAttribute('id_incidente',$model->id);
                    $usuario_incidente->save();
                    
                }
            }

            $infractores=$_POST['infractores'];
          if (isset($infractores)) {
              
            foreach ($infractores as $infractor) {
                $investigacion_infractor=new InvestigacionInfractor;
                $investigacion_infractor->setAttribute('infractor_id', $infractor);
                $investigacion_infractor->setAttribute('incidente_id',$model->id);
                $investigacion_infractor->save();
            }
          }


            return $this->redirect(['view', 'id' => $model->id]);
   //          $model = new Incidente();
			// return $this->render('create', [
   //              'model' => $model,
			// 	'novedades' => $novedades,
			// 	'dependencias' => $dependencias,
			// 	'marcasUsuario' => $marcasUsuario,
			// 	'distritosUsuario' => $distritosUsuario,
			// 	'zonasUsuario' => $zonasUsuario,
			// 	'incidente' => 'active',
			// 	'done' => '200',
   //          ]);
			
			
			
        } else {
            return $this->render('create', [
              'model' => $model,
				      'novedades' => $novedades,
				      'dependencias' => $dependencias,
				      'marcasUsuario' => $marcasUsuario,
				      'distritosUsuario' => $distritosUsuario,
				      'incidente' => 'active',
				      'zonasUsuario' => $zonasUsuario,
              'usuarios'=>$list_usuarios,
              'list_tipo_infractor'=>$list_tipo_infractor

            ]);
        }
    }

    /**
     * Updates an existing Incidente model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $usuarios=Usuario::find()->all();
        $list_usuarios=ArrayHelper::map($usuarios,'usuario','usuario');

        $tipo_infractor=TipoInfractor::find()->all();
        $list_tipo_infractor=ArrayHelper::map($tipo_infractor,'id','nombre');

        Yii::$app->session->setTimeout(5400);
        $novedades = Novedad::find()->where(['tipo' => 'I'])->orderBy(['nombre' => SORT_ASC])->all();
        $dependencias = CentroCosto::find()->where(['not in', 'estado', ['C']])->orderBy(['nombre' => SORT_ASC])->all();
        //$zonas = ZonaDependencia::find()->orderBy(['nombre' => SORT_ASC])->all();
        $usuario = Usuario::findOne(Yii::$app->session['usuario-exito']);
        $zonasUsuario = array();
        $marcasUsuario = array();
        $distritosUsuario = array();
        
        if($usuario != null){
          
          $zonasUsuario = $usuario->zonas;      
          $marcasUsuario = $usuario->marcas;
          $distritosUsuario = $usuario->distritos;        
            
        }


        $user_incidente=UsuarioIncidente::find()->where('id_incidente='.$id)->all();

        $usuarios_incidente=[];
        foreach ($user_incidente as $ui) {
            $usuarios_incidente[]=$ui->usuario;
        }

        $investigacion_infractor=InvestigacionInfractor::find()->where('incidente_id='.$id)->all();

        $infractores_inv=[];

        foreach ($investigacion_infractor as $infr) {
          $infractores_inv[]=$infr->infractor_id;         
        }


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //$fecha_real_visita = strtotime('+1 day' , strtotime ( $model->fecha ));
            //$fecha_real_visita = date('Y-m-d',$fecha_real_visita);
            $model->setAttribute('fecha',$model->fecha);
            $model->setAttribute('fecha_creado',date('Y-m-d H:i:s'));
            $model->save(); 
            UsuarioIncidente::deleteAll(['id_incidente' => $id]);
            InvestigacionInfractor::deleteAll(['incidente_id' => $id]);
            $users=$_POST['usuarios'];

            //print_r($users);
            

            if(isset($users)){
                foreach ($users as $rowuser) {
                   $usuario_incidente=new UsuarioIncidente();
                    $usuario_incidente->setAttribute('usuario', $rowuser);
                    $usuario_incidente->setAttribute('id_incidente',$model->id);
                    $usuario_incidente->save();
                    
                }
            }

            $infractores=$_POST['infractores'];
          if (isset($infractores)) {
              
            foreach ($infractores as $infractor) {
                $investigacion_infractor=new InvestigacionInfractor;
                $investigacion_infractor->setAttribute('infractor_id', $infractor);
                $investigacion_infractor->setAttribute('incidente_id',$model->id);
                $investigacion_infractor->save();
            }
          }

            return $this->redirect(['view', 'id' => $model->id]);

        } else {
            return $this->render('update', [
                'model' => $model,
                'novedades' => $novedades,
                'dependencias' => $dependencias,
                'marcasUsuario' => $marcasUsuario,
                'distritosUsuario' => $distritosUsuario,
                'incidente' => 'active',
                'zonasUsuario' => $zonasUsuario,
                'usuarios'=>$list_usuarios,
                'usuarios_incidente'=>$usuarios_incidente,
                'infractores_inv'=>$infractores_inv,
                'list_tipo_infractor'=>$list_tipo_infractor
            ]);
        }
    }

    /**
     * Deletes an existing Incidente model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        

        $novedades=NovedadIncidente::find()->where('id_incidente='.$id)->all();

        foreach ($novedades as $novedad) {
            
            $fotos=FotoNovedadIncidente::find()->where('id_novedad='.$novedad->id)->all();

            foreach ($fotos as $foto) {
                
                unlink(Yii::$app->basePath .'/web/'.$foto->foto);
            }

            FotoNovedadIncidente::deleteAll('id_novedad = :id ', [':id' => $novedad->id]);

        }

        NovedadIncidente::deleteAll('id_incidente = :id ', [':id' => $id]);

        return $this->redirect(['index']);
    }

    /**
     * Finds the Incidente model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Incidente the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Incidente::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionNovedad_investigacion($id){

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
            $model->setAttribute('usuario',Yii::$app->session['usuario-exito']);
            $model->setAttribute('fecha_creado',date('Y-m-d H:i:s'));
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

            return $this->redirect(['view', 'id' => $id]);

        }

        return $this->render('novedad_investigacion', [
           'list_tipo_novedad'=>$list_tipo_novedad,
           'model'=>$model,
           'list_usuarios'=>$list_usuarios,
           'id'=>$id
            
        ]);

    }

    public function actionCerrarcaso($id,$area,$detalle){
        $model = $this->findModel($id);
        $model->setAttribute('estado','cerrado');
        $model->setAttribute('area_encargada',$area);
        $model->setAttribute('detalle_cierre',$detalle);
        $model->save();

        if($area=='J'){
          $num=UsuarioIncidente::find()->where(' id_incidente='.$model->id.' AND usuario="sara pavon" ')->count();
          if($num==0){
            $usuario_incidente=new UsuarioIncidente();
            $usuario_incidente->setAttribute('usuario','sara pavon');
            $usuario_incidente->setAttribute('id_incidente',$model->id);
            $usuario_incidente->save();
          }
        }

        return $this->redirect(['view', 'id' => $id]);

    }

    public function actionAbrircaso($id){
    	$model = $this->findModel($id);
        $model->setAttribute('estado','abierto');
        $model->save();
        return $this->redirect(['view', 'id' => $id]);
    }
}
