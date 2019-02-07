<?php 
	
	use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
	use kartik\widgets\Select2;
	use kartik\date\DatePicker;
	use marqu3s\summernote\Summernote;
	use kartik\widgets\FileInput;

    $this->title = 'Novedad Investigacion';

?>

<?= Html::a('<i class="fa fa-arrow-left"></i>',Yii::$app->request->baseUrl.'/incidente/view?id='.$id,['class'=>'btn btn-primary']) ?>

<h1 class="text-center"><?php echo $this->title ?></h1>
<br>

<?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data'] ]); ?>

<div class="row">
	<div class="col-md-6">
		<?php 
			echo $form->field($model, 'tipo_novedad')->widget(Select2::classname(), [
			    'data' =>$list_tipo_novedad,
			    'options' => ['placeholder' => 'Selecciona una opcion','id'=>'tipo_novedad']
			]);

		?>
	</div>
	<div class="col-md-6">
		<label>Fecha evento</label>
		<?= 
            DatePicker::widget([
                'id' => 'fecha',
                'name' => 'fecha',
                'value' => date('Y-m-d'),
                'options' => ['placeholder' => 'Fecha Inicio'],
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true
                ]
            ]);
         ?>
	</div>
</div>

<!-- <div class="row">
	<div class="col-md-6">
		<?php 
			echo $form->field($model, 'usuario')->widget(Select2::classname(), [
				'value' => (string)Yii::$app->session['usuario-exito'],
			    'data' =>$list_usuarios,
			    //'value' => [Yii::$app->session['usuario-exito']],
			    'options' => ['placeholder' => 'Selecciona una opcion','id'=>'usuario']
			]);

		?>
	</div>

	<div class="col-md-6">
		<?= $form->field($model, 'cargo')->textInput() ?>
	</div>

</div> -->

<div class="row">
	<div class="col-md-12">
		<?php
			 // Usage with ActiveForm and model
			echo $form->field($model, 'image[]')->widget(FileInput::classname(), [
				'options'=>['multiple'=>true],
				'pluginOptions'=>['allowedFileExtensions'=>['jpg', 'gif', 'png','jpeg','docx','xlsx','pdf'],
					'maxFileSize' => 5120,
			  	]
			]);
		?>
	</div>
</div>


<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'desc_novedad')->widget(Summernote::className(), [
		    'clientOptions' => [
		       
		    ]
		]); ?>
	</div>

</div>

<br>
<div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
</div>



<?php ActiveForm::end(); ?>