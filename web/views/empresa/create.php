<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Distrito */


$this->title = 'Crear Empresa';	

?>
      <?= $this->render('_tabs',['empresa' => $empresa]) ?>

   
   <?php if(isset($done) && $done === '200'):?>
   
         <p style="text-align: center;" class="alert alert-success">Empresa Creada.</p>
   
   <?php endif;?>
   
   	<div class="form-group">

	<?= Html::a('Configuración Electrónica',Yii::$app->request->baseUrl.'/tipo-alarma/create',['class'=>'btn btn-primary']) ?>
		
	</div>	 

   
   
   
   <h1 style="text-align: center;"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
	
        'model' => $model,
		'empresas' => $empresas,
	
    ]) ?>