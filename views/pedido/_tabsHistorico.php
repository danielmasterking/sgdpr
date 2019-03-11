<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
$historico = isset($historico) ? $historico : '';
$historico_prefactura = isset($historico_prefactura) ? $historico_prefactura : '';



//$this->title = 'Exito';
?>
   <div class="bottom">

     <ul class="nav nav-tabs nav-justified">
	 
 	
     <li role="presentation" class="<?= $historico ?>"><?php echo Html::a('Historico',Yii::$app->request->baseUrl.'/pedido/historico'); ?></li>
	 <li role="presentation" class="<?= $historico_prefactura ?>"><?php echo Html::a('Historico Prefactura',Yii::$app->request->baseUrl.'/pedido/prefactura-rechazados'); ?></li>
	 
      
   </ul>
     
   </div>