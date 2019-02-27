<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
$consolidado = isset($consolidado) ? $consolidado : '';
$prefactura = isset($prefactura) ? $prefactura : '';



//$this->title = 'Exito';
?>
   <div class="bottom">

     <ul class="nav nav-tabs nav-justified">
	 
 	
     <li role="presentation" class="<?= $consolidado ?>"><?php echo Html::a('Consolidado',Yii::$app->request->baseUrl.'/pedido/consolidar'); ?></li>
	 <li role="presentation" class="<?= $prefactura ?>"><?php echo Html::a('Prefactura',Yii::$app->request->baseUrl.'/pedido/prefactura-aprobados'); ?></li>
	 
      
   </ul>
     
   </div>