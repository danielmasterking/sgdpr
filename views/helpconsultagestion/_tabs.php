<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
$respuestas = isset($respuestas) ? $respuestas : '';
$preguntas = isset($preguntas) ? $preguntas : '';
$help_res=isset($help_res) ? $help_res : '';



//$this->title = 'Exito';
?>
   <div class="bottom">

     <ul class="nav nav-tabs nav-justified">
	 
 	 
     <li role="presentation" class="<?= $preguntas ?>"><?php echo Html::a('Temas',Yii::$app->request->baseUrl.'/consultasgestion/create'); ?></li>

     <li role="presentation" class="<?= $respuestas ?>"><?php echo Html::a('Respuestas',Yii::$app->request->baseUrl.'/respuestasgestion/create'); ?></li>

     <li role="presentation" class="<?= $help ?>"><?php echo Html::a('Ayuda para Temas',Yii::$app->request->baseUrl.'/helpconsultagestion/create'); ?></li>

      <li role="presentation" class="<?= $help_res ?>"><?php echo Html::a('Ayuda para Respuestas',Yii::$app->request->baseUrl.'/helprespuestas/create'); ?></li>

     
      
	 </ul>
     
   </div>