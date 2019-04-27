<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
$historico = isset($historico) ? $historico : '';
$historico_prefactura = isset($historico_prefactura) ? $historico_prefactura : '';

$permisos = array();
  if( isset(Yii::$app->session['permisos-exito']) ){
    $permisos = Yii::$app->session['permisos-exito'];
  }

//$this->title = 'Exito';
?>
   <div class="bottom">

     <ul class="nav nav-tabs nav-justified">
	 
 	
     <li role="presentation" class="<?= $historico ?>"><?php echo Html::a('Historico',Yii::$app->request->baseUrl.'/pedido/historico'); ?></li>

     <?php if(in_array("administrador", $permisos) || in_array("ver-historico-prefactura-fija", $permisos)){ ?>
	 <li role="presentation" class="<?= $historico_prefactura ?>"><?php echo Html::a('Historico Prefactura',Yii::$app->request->baseUrl.'/pedido/prefactura-rechazados'); ?></li>
	<?php }?>
      
   </ul>
     
   </div>