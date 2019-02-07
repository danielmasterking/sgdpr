<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use marqu3s\summernote\Summernote;
use kartik\widgets\FileInput;
use kartik\datecontrol\DateControl;


?>

<div id="capacitacion" style="display: none;">
<?php $form = ActiveForm::begin([

     'options'=>['enctype'=>'multipart/form-data','id'=>'form-capacitacion'] // important
]); ?>



<div class="row">
	<div class="col-md-12">
		<?php echo $form->field($model2, 'tema_cap_id')->dropDownList(
                $list_tema, 
                ['prompt'=>'Select...']);
		?>
	</div>

	
</div>

<div class="row">
	

	<div class="col-md-12">
		
		<?= $form->field($model2, 'fecha_novedad')->widget(DateControl::classname(), [
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
		
		<?= $form->field($model2, 'descripcion')->widget(Summernote::className(), [
		
			'clientOptions' => [
				
	               'height' => 120,

				]
		  
		]) ?> 
	</div>
</div>

<!-- VALIDACION -->
<label>Requiere plan de accion</label>
<label class="radio-inline">
  <input type="radio" name="aplica_plan" id="check_si_capacitacion" value="S" onclick="plan_de_accion_capacitacion('S');">Si
</label>
<label class="radio-inline">
  <input type="radio" name="aplica_plan" id="check_no_capacitacion" value="N" onclick="plan_de_accion_capacitacion('N');" checked=""> No
</label>

<!-- ********** -->

<div class="row" id="plan_accion_capacitacion" style="display: none;">
    <div class="col-md-12">
		
		<?= $form->field($model2, 'plan_de_accion')->widget(Summernote::className(), [
		
			'clientOptions' => [
				
	               'height' => 120,

				]
		  
		]) ?>   
		
    </div>
</div>	

<div class="row">
	<div class="col-md-12">
		<label>Archivos</label>
		<button class="btn btn-primary btn-xs" onclick="agregar_file_cap();" type="button">
			<i class="fa fa-plus"></i>
		</button>

		<div id="file_cap0">
			<div class="input-group">
		        <label  id="browsebutton" class="btn btn-default input-group-addon" for="my-file-selector_cap0" style="background-color:white">

		             <input onchange="text_file_Cap(this,0);" id="my-file-selector_cap0" type="file" name="NovedadCapacitacion[file][]" style="display:none;" >
		             <?//= $form->field($model, 'image[]')->fileInput(['multiple' => false, 'id'=>'my-file-selector0','class'=>'form-control','onchange'=>'text_file(this,0);','style'=>'display:none;'])->label(false) ?>
		           
		            <i class="fa  fa-camera"></i> Adjuntar una Archivo...
		        </label>
		        <input id="label_cap-0" type="text" class="form-control" readonly="">
		    </div>
		  
		  <!-- 	<span class="help-block">
				<small id="fileHelp" class="form-text text-muted">Only CSV with size less than 2MB is allowed.</small>
			</span> -->
		</div>
		<?php
			 // Usage with ActiveForm and model
			 /*echo $form->field($model2, 'file[]')->widget(FileInput::classname(), [
			'options'=>['multiple'=>true],
			'pluginOptions'=>['allowedFileExtensions'=>['jpg', 'gif', 'png','jpeg','doc','docx','xls','xlsx','ppt','pptx','pdf'],
							   'maxFileSize' => 5120,
			  ]
			 ]);*/

	    ?>
	    <br>
	    <div id="files_cap">
	    	
	    </div>
	</div>
</div>
<br>
<button class="btn btn-primary">Crear</button>
<?php ActiveForm::end(); ?>
</div>
<script type="text/javascript">
	function plan_de_accion_capacitacion(opcion){
		switch(opcion) {

			case 'S':
			        
			    $('#plan_accion_capacitacion').show('slow/400/fast', function() {
			    	
			    });
			break;

			case 'N':
			        
			    $('#plan_accion_capacitacion').hide('slow/400/fast', function() {
			    	
			    });
			break;
		}

		
	}

	$('#form-capacitacion').submit(function(event) {
			

		if($("#check_si_capacitacion").is(':checked')){
			var plan_de_accion=$('#novedadcapacitacion-plan_de_accion').val();
			if ($.trim(plan_de_accion)=='') {
				alert('El plan de accion no puede estar vacio');

				return false;
			}
		}


	});

	var files_cont_cap=1;
	function agregar_file_cap(){
	var html='<div id="file_cap'+files_cont_cap+'">';
		html+='<div class="input-group">';
		html+='<div class="input-group-btn">';
		html+=' <label  id="browsebutton" class="btn btn-default " for="my-file-selector_cap'+files_cont_cap+'" style="background-color:white">';
		html+='<input onchange="text_file_Cap(this,'+files_cont_cap+');" id="my-file-selector_cap'+files_cont_cap+'" type="file" name="NovedadCapacitacion[file][]" style="display:none;" >';
		html+='<i class="fa  fa-camera"></i> Adjuntar una imagen...</label>';
		html+="<button class='btn btn-danger ' type='button' onclick='quitar_file_cap("+files_cont_cap+",this)'><i class='fa fa-trash'></i></button>";
		html+='</div>';
		html+='<input id="label_cap-'+files_cont_cap+'" type="text" class="form-control" readonly=""></div>';
		
		html+='<br></div>';

		$('#files_cap').append(html);
		files_cont_cap++;

	}

	function quitar_file_cap(cont,objeto){
		var confirmar=confirm('Desea eliminar este elemento?');
		if(confirmar){
			$('#file_cap'+cont).remove();
			$(objeto).remove();
		}	
	}

	function text_file_Cap(objeto,num){
    	var valor=objeto.files[0].name;
    	//alert(valor);
    	$('#label_cap-'+num).val(valor);
    	
    }
</script>