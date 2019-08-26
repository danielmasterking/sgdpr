<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\CentroCosto;
use app\models\Usuario;
use app\models\RolUsuario;
use app\models\PermisoRol;
use app\models\LogUsuarios;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout','contact','index','cambio','inicio','about','login'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['logout','contact','index','cambio','inicio','about'],
                        'roles' => ['@'],//para usuarios logueados
                    ],
                    [
                        'allow' => true,
                        'actions' => ['login'],
                        'roles' => ['?'],//para usuarios sin loguear
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get'],
                ],
            ],
        ];
    }
    public function actions()
    {
        return [
            /*'error' => [
                'class' => 'yii\web\ErrorAction',
            ],*/
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
       
   	    if (!\Yii::$app->user->isGuest) {
            //return $this->goHome();
             $this->redirect(['centro-costo/index']);
        }else{
            return $this->render('about');
        }
    }

    public function actionMapa(){
      return $this->render('mapa');
    }

    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;
        if ($exception instanceof \yii\web\NotFoundHttpException) {
            // all non existing controllers+actions will end up here
            return $this->render('pnf'); // page not found
        } else {
          return $this->render('error', ['exception' => $exception]);
        }
    }
	
	public function actionCambio(){


         if(isset( Yii::$app->session['usuario-exito'] )){


            $array_post = Yii::$app->request->post();


            $nueva_clave = isset($array_post['clave']) ? $array_post['clave'] : 'X';
            $hash = Yii::$app->getSecurity()->generatePasswordHash($nueva_clave);
            //$confirmacion_clave = isset($array_post['confirmacion_clave']) ? $array_post['confirmacion_clave'] : 'Y';

            $model = null;

            //if($confirmacion_clave == $nueva_clave){
            if(isset($array_post['clave'])){

                $usuario = Usuario::find()->where(['usuario' => Yii::$app->session['usuario-exito'] ])->one();

                $model = $usuario;

                $model->SetAttribute('password',$hash);
                $model->SetAttribute('update_contrasena','S');

                $model->save();

                Yii::$app->session['update-contrasena']=true;
            }


            return $this->render('cambio',[

                        'model' => $model,
                        'post' =>  $array_post,

                ]);


         }
    }

  public function actionReestablecerClave(){

    $array_post = Yii::$app->request->post();


    $nueva_clave = isset($array_post['clave']) ? $array_post['clave'] : 'X';
    $hash = Yii::$app->getSecurity()->generatePasswordHash($nueva_clave);
    //$confirmacion_clave = isset($array_post['confirmacion_clave']) ? $array_post['confirmacion_clave'] : 'Y';

    $model = null;

    //if($confirmacion_clave == $nueva_clave){
    if(isset($array_post['clave'])){

        $usuario = Usuario::find()->where(['usuario' => $array_post['usuario'] ])->one();

        $model = $usuario;

        $model->SetAttribute('password',$hash);
        $model->SetAttribute('update_contrasena','S');

        if($model->save()){
          Yii::$app->session->setFlash('success','Tu clave fue reestablecida correctamente');
          $this->redirect(['login']);

        }

      
    }
  }
	
	public function actionInicio(){
	    $usuario = Usuario::findOne(Yii::$app->session['usuario-exito']);
		$zonasUsuario = null;
		$marcasUsuario = null;
		$distritosUsuario = null;
		
		if($usuario != null){
		  
          $zonasUsuario = $usuario->zonas;		
          $marcasUsuario = $usuario->marcas;
          $distritosUsuario = $usuario->distritos;		  
			
		}
		
		//Establecer Layout
		
		$puestos = CentroCosto::find()->where(['estado' => 'A'])->all();
		return $this->render('inicio',
		
		 ['puestos' => $puestos,
          'zonasUsuario' => $zonasUsuario,
		  'marcasUsuario' => $marcasUsuario,
		  'distritosUsuario' => $distritosUsuario,]);
		
	}
	
    public function actionLogin()
    {		
        $this->layout='login';
		$model = new LoginForm();
        
		if ($model->load(\Yii::$app->request->post()) && $model->login()) {
			$session = \Yii::$app->session;
            // open a session
            $session->open();
			 $usuario = \Yii::$app->user->identity->usuario;
			 
			 $modelUsuario = Usuario::find()->where(['usuario' => $usuario])->one();
			 
			 $roles = $modelUsuario->roles;
             $rolesArray = array();
             $area=$modelUsuario->area;
             $ambas_areas=$modelUsuario->ambas_areas;	         
			 
			 foreach($roles as $rol){
				 
				 $rolesArray [] = strtolower($rol->rol->nombre);
   				 
			 }
			 \Yii::$app->session->setTimeout($model->rememberMe);//5400
			 \Yii::$app->session['usuario-exito'] = $usuario;
             \Yii::$app->session['nombre-apellido'] = $modelUsuario->nombres." ".$modelUsuario->apellidos;
			 \Yii::$app->session['rol-exito'] = $rolesArray;		
             \Yii::$app->session['permisos-exito'] = $this->allowed($usuario);
              \Yii::$app->session['area-usuario'] = $area;
             \Yii::$app->session['ambas-areas-usuario'] = $ambas_areas;	
             \Yii::$app->session['notificacion'] =1; 
             \Yii::$app->session['update-contrasena'] =$modelUsuario->update_contrasena=='S'?true:false; 
              //INSERTAMOS EN LA TABLA LOG
             date_default_timezone_set ( 'America/Bogota');

             $model_log=new LogUsuarios();

             $model_log->setAttribute('usuario', $usuario);
             $model_log->setAttribute('fecha',date('Y-m-d'));
             $model_log->setAttribute('hora_inicio',date('H:i:s'));
             $model_log->setAttribute('dispositivo',$this->dispositivo());            
             $model_log->save();

             \Yii::$app->session['id-log'] = $model_log->id;
			 
			 //Validar rol para determinar donde redirigir TODO
			 //$this->redirect('inicio');
             $permisos = array();
            if( isset(Yii::$app->session['permisos-exito']) ){
                $permisos = Yii::$app->session['permisos-exito'];
            }
            if(in_array("dependencia-ver", $permisos)){
              if(isset($_POST['url_actual'])){
                $url_actual=str_replace("/sgdpr/web/","",$_POST['url_actual']);
              }else{
                $url_actual='centro-costo/index';
              }
               $this->redirect([$url_actual]);
              
              //echo $model->rememberMe;
            }else{
                $this->redirect(['site/about']);
            }
		}else{
            $errors = $model->errors;

            //print_r($errors);
            $usuario=isset($_POST['LoginForm']['username'])?$_POST['LoginForm']['username']:'';
            return $this->render('login', [
                'model' => $model,
                'errors'=>$errors,
                'usuario'=>$usuario
            ]);	
		}
    }


    public function actionVerificarUsuario(){

      $usuario=$_POST['user'];

     $modelUsuario = Usuario::find()->where('usuario="'.$usuario.'" OR email="'.$usuario.'" ')->count();
      //echo "el usuario:".$usuario;
      //echo $modelUsuario;
      if($modelUsuario==1){

        $arreglo_resp=['respuesta'=>1];
        //echo "entro en el if";
      }else{
        $arreglo_resp=['respuesta'=>0];
        //echo "entro en el else";
      }


      return json_encode($arreglo_resp);
    }

    public function actionLogout()
    {

        //Actualizamos el log
        date_default_timezone_set ( 'America/Bogota');
        $model = LogUsuarios::findOne(Yii::$app->session['id-log']);
        $model->setAttribute('hora_fin', date('H:i:s'));
        $model->save();


        Yii::$app->session->remove('usuario-exito');
		
		Yii::$app->session->remove('rol-exito');

        // close a session
        Yii::$app->session->close();

        Yii::$app->session->destroy();

        Yii::$app->user->logout();

        return $this->redirect('index');
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
	
    public function allowed($usuario){
        
		Yii::$app->session->setTimeout(5400);	
        $usuario_model = RolUsuario::find()->where(['usuario' => $usuario])->all();
        $permisos = array();

        foreach ($usuario_model as $key) {

            /*Obtener Permisos Rol*/
            $permiso_rol = PermisoRol::find()->where(['rol_id' => $key->rol_id])->all();

            foreach ($permiso_rol as $key2) {

                $permisos [] = strtolower( rtrim($key2->permiso->nombre) );

            }
        }


        return $permisos;


    }	


     public function    dispositivo(){

        //Obtenemos el UserAgent
$useragent=$_SERVER['HTTP_USER_AGENT'];
//Creamos una variable para detectar los móviles
$ismobile=preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|zh-cn|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4));

//Los buscamos
//if($ismobile) { echo "Dispositivo Móvil Detectado"; }

//También podemos detectar si es móvil u ordenador usando else
    if($ismobile) { 
        //echo "¡Estás usando un dispositivo móvil!"; 
      // return '<i class="fa fa-mobile" aria-hidden="true"></i>';
         return 'mobile';
    }else { 
        //echo "No estás usando un dispositivo móvil.";
         //return '<i class="fa fa-desktop" aria-hidden="true"></i>';
         return 'desktop';
    }
    

    }


  public function actionLockScreen($url)
  {
      // save current username  
      $this->layout="_lockscreen";  
      $username = Yii::$app->session['usuario-exito'];
       
      // force logout     
      Yii::$app->user->logout();
       
      // render form lockscreen
      $model = new LoginForm(); 
      $model->username = $username;    //set default value 
      return $this->render('lockScreen', [
          'model' => $model,
          'url'=>$url
      ]);     
  }
}
