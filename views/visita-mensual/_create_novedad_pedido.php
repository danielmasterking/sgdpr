<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use marqu3s\summernote\Summernote;
use kartik\widgets\FileInput;
use kartik\datecontrol\DateControl;


?>

<div id="pedido" style="display: none;">
<?php $form = ActiveForm::begin([

     'options'=>['enctype'=>'multipart/form-data','id'=>'form_pedido'] // important
]); ?>



<div class="row">
	

	<div class="col-md-12">
		
		<?= $form->field($model3, 'fecha_novedad')->widget(DateControl::classname(), [
				'value'=>date('Y-m-d'),
				'autoWidget'=>true,
				'displayFormat' => 'php:Y-m-d',
				'saveFormat' => 'php:Y-m-d',
				'type'=>DateControl::FORMAT_DATE,
     
           ]);?>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		
		<?= $form->field($model3, 'descripcion')->widget(Summernote::className(), [
		
			'clientOptions' => [
				
	               'height' => 120,

				]
		  
		]) ?> 
	</div>
</div>

<!-- VALIDACION -->
<label>Requiere plan de accion</label>
<label class="radio-inline">
  <input type="radio" name="aplica_plan" id="check_si_pedido" value="S" onclick="plan_de_accion_pedido('S');">Si
</label>
<label class="radio-inline">
  <input type="radio" name="aplica_plan" id="check_no_pedido" value="N" onclick="plan_de_accion_pedido('N');" checked=""> No
</label>

<!-- ********** -->

<div class="row" id="plan_accion_pedido" style="display: none;">
    <div class="col-md-12">
		
		<?= $form->field($model3, 'plan_de_accion')->widget(Summernote::className(), [
		
			'clientOptions' => [
				
	               'height' => 120,

				]
		  
		]) ?>   
		
    </div>
</div>	

<div class="row">
	<div class="col-md-12">
		<?php
			 // Usage with ActiveForm and model
			 echo $form->field($model3, 'file[]')->widget(FileInput::classname(), [
			'options'=>['multiple'=>true],
			'pluginOptions'=>['allowedFileExtensions'=>['jpg', 'gif', 'png','jpeg','doc','docx','xls','xlsx','ppt','pptx','pdf'],
							   'maxFileSize' => 5120,
			  ]
			 ]);

	    ?>
	</div>
</div>

<button class="btn btn-primary">Crear</button>
<?php ActiveForm::end(); ?>
</div>
<script type="text/javascript">
	function plan_de_accion_pedido(opcion){
		switch(opcion) {

			case 'S':
			        
			    $('#plan_accion_pedido').show('slow/400/fast', function() {
			    	
			    });
			break;

			case 'N':
			        
			    $('#plan_accion_pedido').hide('slow/400/fast', function() {
			    	
			    });
			break;
		}

		
	}

	$('#form_pedido').submit(function(event) {
			

		if($("#check_si_pedido").is(':checked')){
			var plan_de_accion=$('#novedadpedido-plan_de_accion').val();
			if ($.trim(plan_de_accion)=='') {
				alert('El plan de accion no puede estar vacio');

				return false;
			}
		}


	});
</script>