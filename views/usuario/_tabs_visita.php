<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use app\models\Usuario;

$visita = isset($visita) ? $visita : '';
$indicador = isset($indicador) ? $indicador : '';






//$this->title = 'Exito';

$permisos = array();
if( isset(Yii::$app->session['permisos-exito']) ){
  $permisos = Yii::$app->session['permisos-exito'];
}

?>
   <div class="bottom">

    <ul class="nav nav-tabs nav-justified">
	 
    
     
    <li role="presentation" class="<?= $indicador ?>"><?php echo Html::a('Indicador',Yii::$app->request->baseUrl.'/usuario/visita?id='.$usuario); ?></li>

	<li role="presentation" class="<?= $visita ?>"><?php echo Html::a('Visitas',Yii::$app->request->baseUrl.'/usuario/visita_user?id='.$usuario); ?></li>
    
     
   </ul>
     
   </div>