<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use app\models\Usuario;

$capacitaciones = isset($capacitaciones) ? $capacitaciones : '';
$comites = isset($comites) ? $comites : '';
$visitas = isset($visitas) ? $visitas : '';
$investigaciones = isset($investigaciones) ? $investigaciones : '';
$siniestros = isset($siniestros) ? $siniestros : '';
$gestiones=isset($gestiones)?$gestiones:'';
$semestral=isset($semestral)?$semestral:'';
$visitas_activacion=isset($visitas_activacion)?$visitas_activacion:'';
$data_user=Usuario::findOne($usuario);


$area=$data_user->area;
//$this->title = 'Exito';

$permisos = array();
if( isset(Yii::$app->session['permisos-exito']) ){
  $permisos = Yii::$app->session['permisos-exito'];
}

?>
   <div class="bottom">

     <ul class="nav nav-tabs nav-justified">
	 
    <?php

    //if($area=='Seguridad' or $data_user->ambas_areas=='S'){
    ?>
     <li role="presentation" class="<?= $capacitaciones ?>"><?php echo Html::a('Capacitaciones',Yii::$app->request->baseUrl.'/usuario/capacitacion?id='.$usuario); ?></li>

	   <li role="presentation" class="<?= $comites ?>"><?php echo Html::a('Comités',Yii::$app->request->baseUrl.'/usuario/comite?id='.$usuario); ?></li>

     <?php //}?>


     <?php

    //if($area=='Riesgos' or $data_user->ambas_areas=='S'){
     if(in_array("gestion-riesgo", $permisos)){
    ?>
    <li role="presentation" class="<?= $gestiones ?>"><?php echo Html::a('Gestion Riesgo',Yii::$app->request->baseUrl.'/usuario/gestiones?id='.$usuario); ?></li>

    <?php }?>


     <!--<li role="presentation" class="<?= $siniestros ?>"><?php //echo Html::a('Siniestros',Yii::$app->request->baseUrl.'/usuario/siniestro?id='.$usuario); ?></li>-->

     <?php

    //if($area=='Seguridad' or $data_user->ambas_areas=='S'){
    ?>
	 <li role="presentation" class="<?= $visitas ?>"><?php echo Html::a('Visitas',Yii::$app->request->baseUrl.'/usuario/visita?id='.$usuario); ?></li>

    <li role="presentation" class="<?= $visitas_activacion ?>"><?php echo Html::a('Visitas Por activacion',Yii::$app->request->baseUrl.'/usuario/visita_activacion?id='.$usuario); ?></li>

   <li role="presentation" class="<?= $semestral ?>"><?php echo Html::a('Insp Semestral ',Yii::$app->request->baseUrl.'/usuario/insp-semestral?id='.$usuario); ?></li>
   
	 <li role="presentation" class="<?= $investigaciones ?>"><?php echo Html::a('Investigaciones',Yii::$app->request->baseUrl.'/usuario/incidente?id='.$usuario); ?></li>
   <?php //}?>
     
   </ul>
     
   </div>