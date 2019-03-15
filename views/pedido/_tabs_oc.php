<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
$ocpedido = isset($ocpedido) ? $ocpedido : '';
$ocprefactura = isset($ocprefactura) ? $ocprefactura : '';



//$this->title = 'Exito';
?>
   <div class="bottom">

     <ul class="nav nav-tabs nav-justified">
	 
 	
     <li role="presentation" class="<?= $ocpedido ?>"><?php echo Html::a('OC Pedido',Yii::$app->request->baseUrl.'/pedido/orden-compra'); ?></li>
	 <li role="presentation" class="<?= $ocprefactura ?>"><?php echo Html::a('OC Prefactura',Yii::$app->request->baseUrl.'/pedido/orden-compra-prefactura'); ?></li>
	 
      
   </ul>
     
   </div>