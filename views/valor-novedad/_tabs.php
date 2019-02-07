<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
$novedad_categoria = isset($novedad_categoria) ? $novedad_categoria : '';
$resultados = isset($resultados) ? $resultados : '';
$categoria = isset($categoria) ? $categoria : '';
$seccion = isset($seccion) ? $seccion : '';


?>
   <div class="bottom">

     <ul class="nav nav-tabs nav-justified">
	 
 	 
     <li role="presentation" class="<?= $categoria ?>"><?php echo Html::a('Categorias',Yii::$app->request->baseUrl.'/categoria-visita/index'); ?></li>
     <!-- <li role="presentation" class="<?= $empresa ?>"><?php //echo Html::a('Precios',Yii::$app->request->baseUrl.'/empresa-precio/create'); ?></li> -->

     <li role="presentation" class="<?= $novedad_categoria ?>"><?php echo Html::a('Novedad categoria',Yii::$app->request->baseUrl.'/novedad-categoria-visita/index'); ?></li>

     <li role="presentation" class="<?= $resultados ?>"><?php echo Html::a('Resultados',Yii::$app->request->baseUrl.'/resultado/index'); ?></li>

      <li role="presentation" class="<?= $resultados_novedad ?>"><?php echo Html::a('Resultados novedad',Yii::$app->request->baseUrl.'/valor-novedad/index'); ?></li>

     <li role="presentation" class="<?= $mensaje ?>"><?php echo Html::a('Mensaje novedades',Yii::$app->request->baseUrl.'/mensaje-novedad/index'); ?></li>

     <li role="presentation" class="<?= $seccion ?>"><?php echo Html::a('Secciones',Yii::$app->request->baseUrl.'/seccion/index'); ?></li>
    
     
	 </ul>
     
   </div>