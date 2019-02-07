<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use app\models\Usuario;

$indicador = isset($indicador) ? $indicador : '';
$capacitacion = isset($capacitacion) ? $capacitacion : '';
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
	 
   
     <li role="presentation" class="<?= $indicador ?>"><?php echo Html::a('Indicador',Yii::$app->request->baseUrl.'/usuario/indicador-capacitaciones?id='.$usuario); ?></li>

	   <li role="presentation" class="<?= $capacitacion ?>"><?php echo Html::a('Capacitacion',Yii::$app->request->baseUrl.'/usuario/capacitacion?id='.$usuario); ?></li>

     
   </ul>
     
   </div>