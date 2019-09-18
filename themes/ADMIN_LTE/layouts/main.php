<?php
$url_actual_sistema=Yii::$app->request->url;
/*echo $url_actual_sistema;
$url_actual=str_replace("/sgdpr/web/","",$url_actual_sistema);
echo "<br>".$url_actual;*/
if (Yii::$app->session->isActive){
    //$this->redirect(['site/index','flash' => 'La sessión actual ha terminado por favor ingrese nuevamente.']);
 /* $this->registerJs("
     
        var autoLockTimer;
        window.onload = resetTimer;
        window.onmousemove = resetTimer;
        window.onmousedown = resetTimer; // catches touchscreen presses
        window.onclick = resetTimer;     // catches touchpad clicks
        window.onscroll = resetTimer;    // catches scrolling with arrow keys
        window.onkeypress = resetTimer;
 
        function lockScreen() {
            window.location.href = '".\yii\helpers\Url::to(['site/lock-screen','url'=>$url_actual_sistema])."';
        }
 
        function resetTimer() {
            clearTimeout(autoLockTimer);
            autoLockTimer = setTimeout(lockScreen, 300000);  // time is in milliseconds
        }
    ");*/
}

/* @var $this \yii\web\View */
/* @var $content string */
//Establecer zona horaria en colombia
date_default_timezone_set ( 'America/Bogota');

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\models\Notificacion;
use app\models\Usuario;
use app\models\ManualApp;
use yii\helpers\Url;

/////////////////////////////////////////////////////
if(isset(Yii::$app->session['usuario-exito'])):
  $date=date('Y-m-d');
  /*$usuario = Usuario::findOne(Yii::$app->session['usuario-exito']);
  $zonasUsuario = $usuario->zonas;

  $in_zona=" IN(";

  $contador=0;
  foreach ($zonasUsuario as $value) {
    $in_zona.=" '".$value->zona_id."',";  
    $contador++;
  }

  if($contador!=0){
      $in_final = substr($in_zona, 0, -1).")";
  }else{
      $in_final = " IN('')";
  }*/



  $manuales=ManualApp::find()->count();
endif;
/////////////////////////////////////////////////////
$this->theme->baseUrl="https://cvsc.co/sgs/themes/ADMIN_LTE";
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <?= Html::csrfMetaTags() ?>
  <title><?= Html::encode($this->title) ?></title>
   <?php $this->head() ?>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo $this->theme->baseUrl; ?>/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo $this->theme->baseUrl; ?>/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo $this->theme->baseUrl; ?>/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo $this->theme->baseUrl; ?>/dist/css/skins/_all-skins.css">

  <!--mis propios estilos personalizados-->
  <link rel="stylesheet" href="<?php echo $this->theme->baseUrl; ?>/dist/css/estilos.css"> 

  <link rel="icon" type="image/png" href="<?php echo $this->theme->baseUrl; ?>/dist/img/431px-Grupo_Exito_logo.png" />

  <!--vue js-->
  <script src="https://cdn.jsdelivr.net/npm/vue"></script> 
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <style type="text/css">
    .loader {
      position: fixed;
      left: 0px;
      top: 0px;
      width: 100%;
      height: 100%;
      z-index: 9999;
      background: url('<?php echo $this->theme->baseUrl; ?>/dist/img/loader.gif') 50% 50% no-repeat rgb(249,249,249);
      opacity: .8;
    }
  </style>
</head>
<body class="hold-transition skin-blue fixed sidebar-collapse sidebar-mini">
<?php $this->beginBody() ?>
<div class="loader"></div>
<!-- Site wrapper -->
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="#" class="logo" style="color: black;">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>D</b>PR</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>SG</b>DPR</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span> 
        <span class="fas fa-bars "></span>
        <!-- <span class="icon-bar"></span>
        <span class="icon-bar"></span> -->

      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu">
            <a href="<?= Yii::$app->request->baseUrl.'/manualapp/manual'?>"  style="color: black;" title="Manuales">
              <i class="fa fa-book fa-fw"></i>
              <span class="label label-success"><?= $manuales?></span> 
            </a>
           
          </li> 
          <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu">
            <a href="#" c data-target="#myModal-notificacion" data-toggle="modal" style="color: black;" title="" id="badge-total">
              <i class="fas fa-bell"></i>
              <span class="label label-warning info-notificacion" id="total_not"></span>
            </a>
            <!-- <ul class="dropdown-menu">
              <li class="header">Tienes <?= $count_total?> notificaciones</li>
              <li>
                
                 <ul class="menu">
                  <?php //foreach($notificacion2 as $noti): ?>
                  <li>
                    <a href="#">
                      <i class="fas fa-info-circle" style="color: #Ffe701 "></i> <?= $noti->titulo?>
                    </a>
                  </li>
                <?php //endforeach;?>

                 <?php //foreach($notificacion1 as $noti): ?>
                  <li>
                    <a href="#">
                      <i class="fas fa-info-circle" style="color: #Ffe701 "></i> <span class="label label-info"><i class="fas fa-envelope "></i> Mensaje</span> <?= $noti->titulo?>
                    </a>
                  </li>
                <?php //endforeach;?>
                </ul> 
              </li>
              <li class="footer" data-target="#myModal-notificacion" data-toggle="modal" ><a href="#">Ver Todas</a></li>
            </ul>  -->
          </li>
          <!-- Tasks: style can be found in dropdown.less -->
          <!-- <li class="dropdown tasks-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-flag-o"></i>
              <span class="label label-danger">9</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 9 tasks</li>
              <li>
               
                <ul class="menu">
                  <li>
                    <a href="#">
                      <h3>
                        Design some buttons
                        <small class="pull-right">20%</small>
                      </h3>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar"
                             aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">20% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>
                  
                </ul>
              </li>
              <li class="footer">
                <a href="#">View all tasks</a>
              </li>
            </ul>
          </li> -->
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php echo $this->theme->baseUrl; ?>/dist/img/431px-Grupo_Exito_logo.png" class="user-image" alt="User Image">
              <span class="hidden-xs" style="color: black;"><?= Yii::$app->session['usuario-exito']?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header" style="background-color: #Ffe701 ;">
                <img src="<?php echo $this->theme->baseUrl; ?>/dist/img/MmTwXdnv_400x400.png" class="img-circle" alt="User Image">

                <p style="color: black;">
                  <?= Yii::$app->session['nombre-apellido']?>
                  <small><?= date('Y/m/d')?></small>

                </p>
              </li>
             
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="<?= Yii::$app->request->baseUrl.'/site/cambio'?>" class="btn btn-default btn-flat">
                    <i class="fas fa-key"></i> Cambiar Clave
                  </a>
                </div>
                <div class="pull-right">
                  <a href="<?= Yii::$app->request->baseUrl.'/site/logout'?>" class="btn btn-default btn-flat">
                    <i class="fas fa-door-closed"></i> Salir
                  </a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
         
        </ul>
      </div>
    </nav>
  </header>

  <!-- =============================================== -->
   <?= $this->render('_menu') ?>
   
  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
   <!--  <section class="content-header">
      <h1>
        Blank page
        <small>it all starts here</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Examples</a></li>
        <li class="active">Blank page</li>
      </ol>
    </section> -->

    <!-- Main content -->
    <section class="content " >

      <!-- Default box -->
      <div class="box">
        
        <div class="box-body" >
        
          <?= $content ?>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 6.6.8
    </div>
    <strong>© Grupo Exito <?= date('Y')?> Todos los derechos reservados. Cualquier inquietud, duda o necesidad particular ponerse en contacto con <a href="mailto:soporte@sistemagestiondpr.com.co" >soporte@sistemagestiondpr.com.co</a>.
    </strong> 
  </footer>
  <?php 
    if(isset(Yii::$app->session['usuario-exito']))
      echo  $this->render('_modal',[
        'count'=>$count_total,
        'count1'=>$count1,
        'count2'=>$count2,
        'notificacion'=>$notificacion,
        'notificacion1'=>$notificacion1,
        'notificacion2'=>$notificacion2,
      ]);
  ?>
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<!-- <script src="<?php //echo $this->theme->baseUrl; ?>/bower_components/jquery/dist/jquery.min.js"></script> -->
<!-- Bootstrap 3.3.7 -->
<!-- <script src="<?php //echo $this->theme->baseUrl; ?>/bower_components/bootstrap/dist/js/bootstrap.min.js"></script> -->
<!-- SlimScroll -->
<script src="<?php echo $this->theme->baseUrl; ?>/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo $this->theme->baseUrl; ?>/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo $this->theme->baseUrl; ?>/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo $this->theme->baseUrl; ?>/dist/js/demo.js"></script>
<script type="text/javascript">
/*$(window).load(function() {
   
});*/

  $(document).ready(function () {
    $('.sidebar-menu').tree();
    $(".loader").fadeOut("slow");
    $('.info-notificacion').html('<i class="fas fa-sync fa-spin"></i>');
   
    <?php if(Yii::$app->session['notificacion']==1 ): ?>
  
      $('#myModal-notificacion').modal('show');
 
    <?php Yii::$app->session['notificacion']=0; endif; ?>
    /*setInterval(function(){
      notificacion();
    },20000);*/
    notificacion();
  })


    
    var cantidad_notificaciones=0;
    var ingreso=0;
    function notificacion(){
      $.ajax({
            url:"<?php echo Url::toRoute('notificacion/notificacion')?>",
            type:'POST',
            dataType:"json",
            //cache:false,
            //Async:false;
            data: {
               cantidad_notificaciones:cantidad_notificaciones
            },
            async:false,
            beforeSend:  function() {
                //$('.info-notificacion').html('<i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>');
            },
            success: function(data){
              if(cantidad_notificaciones>0 && cantidad_notificaciones<data.total_not_general || ingreso==0){
                $("#total_not").html(data.total_not_general);
                $("#total_notificaciones_modal").html(data.total_not_general);
                $("#badge-total").attr({
                  title: 'Tienes '+data.total_not_general+' notificaciones',
                });
                $("#total_not_mensajes").html(data.total_not_mensaje);
                $("#total_not_pedido").html(data.total_not_pedido);
                $("#body_not_mensaje").html(data.res_not);
                $("#body_not_pedido").html(data.res_pedido);
              }

                if(ingreso==0)
                  cantidad_notificaciones=data.total_not_general;

                if(data.total_not_general>cantidad_notificaciones){
                  var notificaciones_nuevas=(data.total_not_general-cantidad_notificaciones);
                  cantidad_notificaciones=notificaciones_nuevas;
                  //Push.create('tienes '+notificaciones_nuevas+' notificaciones nuevas');
                  Push.create("SGDPR", {
                      body: 'Tienes '+cantidad_notificaciones+' Notificaciones nuevas',
                      icon: '<?php echo $this->theme->baseUrl; ?>/dist/img/431px-Grupo_Exito_logo.png',
                      timeout: 7000,
                      onClick: function () {
                          //window.focus();
                           $('#myModal-notificacion').modal('show');
                          this.close();
                      }
                  });


                  cantidad_notificaciones=data.total_not_general;
                }

                ingreso++;
                setTimeout(function(){notificacion();},15000);
            }
        });
    }

    function leido(notificacion_id,usuario){
      
      ingreso=0;
      $.ajax({
            url:"<?php echo Url::toRoute('notificacion/leido')?>",
            type:'POST',
            dataType:"json",
            //cache:false,
            //Async:false;
            data: {
               id_not:notificacion_id,
               usuario:usuario
            },
            async:false,
            beforeSend:  function() {
                $('.info-notificacion').html('<i class="fas fa-sync fa-spin"></i>');
            },
            success: function(data){
              
                notificacion();
            }
        });

    }

    <?php if(Yii::$app->session['update-contrasena']==false): ?>
     
     location.href="<?= Yii::$app->request->baseUrl.'/site/cambio'?>";
     <?php Yii::$app->session['update-contrasena']=true; endif; ?>
</script>
<!-- Smartsupp Live Chat script -->
<script type="text/javascript">
var _smartsupp = _smartsupp || {};
_smartsupp.key = '53f8d6e7e81b45ad6bc911e68182a236fd4ea00f';
window.smartsupp||(function(d) {
  var s,c,o=smartsupp=function(){ o._.push(arguments)};o._=[];
  s=d.getElementsByTagName('script')[0];c=d.createElement('script');
  c.type='text/javascript';c.charset='utf-8';c.async=true;
  c.src='https://www.smartsuppchat.com/loader.js?';s.parentNode.insertBefore(c,s);
})(document);
</script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage(); ?>
