<?php

if (Yii::$app->session->isActive){
    //$this->redirect(['site/index','flash' => 'La sessión actual ha terminado por favor ingrese nuevamente.']);
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

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody() ?>
            <header >
               <img class="img-responsive" style="width: 100%;height: 50%;" src="<?php echo Yii::$app->request->baseUrl.'/img/EXITOPORTADA.png'?>">
            </header>
            <?php if(isset(Yii::$app->session['usuario-exito'])){ ?>
            <nav class="navbar navbar-toggleable-md navbar-inverse fixed-top bg-inverse">
                <div class="container-fluid">
                    <div class="navbar-header nav-negro">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="nav-brand-negro" href="#">SGS Grupo Exito</a>
                    </div>
                    <div id="navbar" class="navbar-collapse collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <?php if(isset(Yii::$app->session['usuario-exito'])):
                            $date=date('Y-m-d');
                            $usuario = Usuario::findOne(Yii::$app->session['usuario-exito']);
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
                            }

                            $count=Notificacion::find()
                            ->leftJoin('notificacion_zona', ' notificacion_zona.id_notificacion= notificacion.id')
                            ->leftJoin('notificacion_usuario', ' notificacion_usuario.id_notificacion= notificacion.id')
                            ->where(' (notificacion.fecha_final > "'.$date.'" OR notificacion.fecha_final = "'.$date.'" ) AND (notificacion_zona.id_zona '.$in_final.' OR notificacion_usuario.usuario IN("'.Yii::$app->session['usuario-exito'].'") )')
                            ->groupBy('notificacion.id')
                            ->count();
                            ?>
                                <li>
                                     <?php
                                        echo Html::a('<i class="fa fa-sign-out fa-fw"></i> Salir',Yii::$app->request->baseUrl.'/site/logout');
                                     ?>
                                </li>

                                <li>
                                     <?php
                                        echo Html::a('<i class="fa fa-book fa-fw"></i> Manuales',Yii::$app->request->baseUrl.'/manualapp/manual');
                                     ?>
                                </li>

                                <li class="dropdown">
                                  <a data-target="#myModal-notificacion" href="#" class="dropdown-toggle" data-toggle="modal" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-bell"></i> <span class="badge badge-info" style="background-color:blue;"><?= $count?></span><!-- <span class="caret"></span> --></a>
                                  <!-- <ul class="dropdown-menu">
                                    <li><a href="#">Actualizacion</a></li>
                                    <li><a href="#">Another action</a></li>
                                    <li><a href="#">Something else here</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="#">Separated link</a></li> -->
                                    <!-- <li role="separator" class="divider"></li> -->
                                    <!-- <li><a href="#">One more separated link</a></li>
                                  </ul> --> 
                                </li>

                                <li>
                                     <?php
                                        echo Html::a('<i class="fa fa-lock"></i> Cambiar Clave',Yii::$app->request->baseUrl.'/site/cambio');
                                     ?>
                                </li>
                                <li><a href="#"> Bienvenido, <strong><?= Yii::$app->session['usuario-exito']?></strong></a></li>
                            <?php endif;?>
                        </ul>
                    </div>
                </div>
            </nav>
            <?php //echo $this->render('_modal') ?>
            <div class="container-fluid"> 
                <div class="row">
                    <div class="col-md-3">
                        <?php echo $this->render('_modal') ?>
                        <div style="padding-left: 5px;">
                        <?= $this->render('_menu') ?>
                        </div>
                    </div>
                    
                    <div class="col-md-9">
                        <?= $content ?>
                    </div>
                    

                </div>
            </div>

            <script type="text/javascript">
                <?php if(Yii::$app->session['notificacion']==1 and $count>0 ): ?>
                $(function(){
                    $('#myModal-notificacion').modal('show');
                });
                <?php Yii::$app->session['notificacion']=0; endif; ?>
                
            </script>
            <?php }else{ ?>
                <div class="row">
                    <div class="col-sm-12">
                        <?= $content ?>
                    </div>
                </div>
            <?php } ?>
        <footer class="footer">
            <div class="container">
                <p class="pull-left">&copy; Grupo Exito <?= date('Y') ?> Todos los derechos reservados. Cualquier inquietud, duda o necesidad particular ponerse en contacto con DannyMiguel.GuevaraBuiles@grupo-exito.com</p>
                <p class="pull-right">Version 5.6.8<?php //echo Yii::powered() ?></p>
            </div>
        </footer>

    <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
