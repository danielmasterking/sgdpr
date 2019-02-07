<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Distrito */


$this->title = 'Actualizar Prefactura';	
//var_dump($filas);
?>

<?= Html::a('<i class="fa fa-arrow-left"></i> Volver',Yii::$app->request->baseUrl.'/prefactura-fija/view?id='.$model->id, ['class'=>'btn btn-primary']) ?>

   <h1 style="text-align: center;"><?= Html::encode($this->title) ?></h1>

   <?php $form = ActiveForm::begin(); ?>

   	<div class="row">
		<div class="col-md-6">
			<?= $form->field($model, 'mes')->dropDownList([
				'01' => 'ENERO', 
				'02' => 'FEBRERO',
				'03' => 'MARZO',
				'04' => 'ABRIL',
				'05' => 'MAYO',
				'06' => 'JUNIO',
				'07' => 'JULIO',
				'08' => 'AGOSTO',
				'09' => 'SEPTIEMBRE',
				'10' => 'OCTUBRE',
				'11' => 'NOVIEMBRE',
				'12' => 'DICIEMBRE',
			]) ?>
		</div>
		<div class="col-md-6">
			<?//= $form->field($model, 'ano')->textInput(['value' => $year,'maxlength' => true/*,'readonly'  => 'readonly'*/]) ?>
	        <?= $form->field($model, 'ano')->dropDownList([
	            '2018' => '2018', 
	            '2019' => '2019' 
	           
	        ]) ?>
		</div>
	</div>

	 <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>

   <?php ActiveForm::end(); ?>

    <?/*= $this->render('_form', [
	
        'model' => $model,
		'dependencias' => $dependencias,
		'empresas' => $empresas,
		'zonasUsuario' => $zonasUsuario,
		'marcasUsuario' => $marcasUsuario,
		'distritosUsuario' => $distritosUsuario,
		'empresasUsuario' => $empresasUsuario,
		 'servicios' => $servicios,
		 'dias' => $dias,
		 'jornadas' => $jornadas,
         'actualizar' => 's',
         'filas' => $filas,		 
		
	
    ])*/ ?>