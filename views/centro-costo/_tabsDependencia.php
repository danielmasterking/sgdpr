<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$permisos = null;

if( isset(Yii::$app->session['permisos-exito']) ){

  $permisos = Yii::$app->session['permisos-exito'];

}


use yii\helpers\Html;
$capacitacion = isset($capacitacion) ? $capacitacion : '';
$siniestro = isset($siniestro) ? $siniestro : '';
$visita = isset($visita) ? $visita : '';
$comite = isset($comite) ? $comite : '';
$informacion = isset($informacion) ? $informacion : '';
$modelo_prefactura = isset($modelo_prefactura) ? $modelo_prefactura : '';
$investigaciones = isset($investigacion) ? $investigacion : '';
$gestiones = isset($gestiones) ? $gestiones : '';
$semestral=isset($semestral) ? $semestral : '';

//$this->title = 'Exito';
?>
   <div class="bottom">

     <ul class="nav nav-tabs nav-justified">
	 <li role="presentation" class="<?= $informacion ?>"><?php echo Html::a('Información',Yii::$app->request->baseUrl.'/centro-costo/informacion?id='.$codigo_dependencia); ?></li>
     <li role="presentation" class="<?= $capacitacion ?>"><?php echo Html::a('Capacitaciones',Yii::$app->request->baseUrl.'/centro-costo/capacitacion?id='.$codigo_dependencia); ?></li>
     <!--<li role="presentation" class="<?= $siniestro ?>"><?php //echo Html::a('Siniestros',Yii::$app->request->baseUrl.'/centro-costo/siniestro?id='.$codigo_dependencia); ?></li>-->
     <li role="presentation" class="<?= $visita ?>"><?php echo Html::a('Visitas',Yii::$app->request->baseUrl.'/centro-costo/visita?id='.$codigo_dependencia); ?></li>

     <li role="presentation" class="<?= $semestral ?>"><?php echo Html::a('Insp Semestral',Yii::$app->request->baseUrl.'/centro-costo/insp-semestral?id='.$codigo_dependencia); ?></li>
     
     <li role="presentation" class="<?= $gestiones ?>"><?php echo Html::a('Gestiones',Yii::$app->request->baseUrl.'/centro-costo/gestiones?id='.$codigo_dependencia); ?></li>


	 <li role="presentation" class="<?= $comite ?>"><?php echo Html::a('Comités',Yii::$app->request->baseUrl.'/centro-costo/comite?id='.$codigo_dependencia); ?></li>


	 <li role="presentation" class="<?= $investigaciones ?>"><?php echo Html::a('Investigaciones',Yii::$app->request->baseUrl.'/centro-costo/investigaciones?id='.$codigo_dependencia); ?></li>
	 
	 <?php if($permisos != null ):?>
	 
	   <?php if(in_array("asociar-prefactura", $permisos) ):?>
	   
	     <li role="presentation" class="<?= $modelo_prefactura ?>"><?php echo Html::a('Pre-Facturas',Yii::$app->request->baseUrl.'/centro-costo/prefacturas?id='.$codigo_dependencia); ?></li>  
	   
	   <?php endif;?>
	   
	 <?php endif;?>
	 
	 
     
   </ul>
     
   </div>