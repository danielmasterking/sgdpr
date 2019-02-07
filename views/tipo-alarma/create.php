<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Distrito */


$this->title = 'Crear Tipo Alarma';	

?>
      <?= $this->render('_tabs',['alarma' => $alarma]) ?>

   
   <?php if(isset($done) && $done === '200'):?>
   
         <p style="text-align: center;" class="alert alert-success">Alarma Creada.</p>
   
   <?php endif;?>
   

   
   
   
   <h1 style="text-align: center;"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
	
        'model' => $model,
		'alarmas' => $alarmas,
	
    ]) ?>