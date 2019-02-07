<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use marqu3s\summernote\Summernote;
use kartik\widgets\FileInput;
use kartik\datecontrol\DateControl;
$this->title = 'Reporte de Gestión / Novedades';



?>

<?= Html::a('<i class="fa fa-arrow-left"></i> ',Yii::$app->request->baseUrl.'/visita-mensual/view?id='.$id.'&dependencia='.$dependencia, ['class'=>'btn btn-primary']) ?>

<h1 class="text-center"><?= Html::encode($this->title)?></h1>


<div class="row">
	<div class="col-md-12">
		<label>Tipo</label>
		<select class="form-control" id="tipo">
			<option value="Inspeccion-Semestral">Visita-Semestral</option>
			<option value="Pedido">Pedido</option>
			<option value="Capacitacion">Capacitacion</option>
		</select>
	</div>
</div>
<br>

<div id="visita">
<?php $form = ActiveForm::begin([

     'options'=>['enctype'=>'multipart/form-data','id'=>'form-visita'] // important
]); ?>



<div class="row">
	<div class="col-md-6">
		<?php echo $form->field($model, 'categoria_id')->dropDownList(
                $list_categorias, 
                ['prompt'=>'Select...','onchange'=>'NovedadCategoria(this);']);
		?>
	</div>

	<div class="col-md-6">
		<?php echo $form->field($model, 'novedad_id')->dropDownList(
                [], 
                ['prompt'=>'Select...','id'=>'novedad']);
		?>
	</div>
</div>

<div class="row">
	

	<div class="col-md-12">
		
		<?= $form->field($model, 'fecha_novedad')->widget(DateControl::classname(), [
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
		
		<?= $form->field($model, 'descripcion')->widget(Summernote::className(), [
		
			'clientOptions' => [
				
	               'height' => 120,

				]
		  
		]) ?> 
	</div>
</div>

<!-- VALIDACION -->
<label>Requiere plan de accion</label>
<label class="radio-inline">
  <input type="radio" name="aplica_plan" id="check_si" value="S" onclick="plan_de_accion('S');">Si
</label>
<label class="radio-inline">
  <input type="radio" name="aplica_plan" id="check_no" value="N" onclick="plan_de_accion('N');" checked=""> No
</label>

<!-- ********** -->

<div class="row" id="plan_accion" style="display: none;">
    <div class="col-md-12">
		
		<?= $form->field($model, 'plan_de_accion')->widget(Summernote::className(), [
		
			'clientOptions' => [
				
	               'height' => 120,

				]
		  
		]) ?>   
		
    </div>
</div>	

<div class="row">
	<div class="col-md-12">
		<label>Archivos</label>
		<button class="btn btn-primary btn-xs" onclick="agregar_file();" type="button">
			<i class="fa fa-plus"></i>
		</button>
		<?//= $form->field($model, 'file[]')->fileInput(['multiple' => false, 'id'=>'file0'])->label(false) ?>
		<div id="file0">
			<div class="input-group">
		        <label  id="browsebutton" class="btn btn-default input-group-addon" for="my-file-selector0" style="background-color:white">

		             <input onchange="text_file(this,0);" id="my-file-selector0" type="file" name="VisitaMensualDetalle[file][]" style="display:none;" >
		             <?//= $form->field($model, 'image[]')->fileInput(['multiple' => false, 'id'=>'my-file-selector0','class'=>'form-control','onchange'=>'text_file(this,0);','style'=>'display:none;'])->label(false) ?>
		           
		            <i class="fa  fa-camera"></i> Adjuntar Archivo...
		        </label>
		        <input id="label-0" type="text" class="form-control" readonly="">
		    </div>
		  
		  <!-- 	<span class="help-block">
				<small id="fileHelp" class="form-text text-muted">Only CSV with size less than 2MB is allowed.</small>
			</span> -->
		</div>
		<?php
			 // Usage with ActiveForm and model
			/* echo $form->field($model, 'file[]')->widget(FileInput::classname(), [
			'options'=>['multiple'=>true],
			'pluginOptions'=>['allowedFileExtensions'=>['jpg', 'gif', 'png','jpeg','doc','docx','xls','xlsx','ppt','pptx','pdf'],
							   'maxFileSize' => 5120,
			  ]
			 ]);*/

	    ?>
	    <br>
	    <div id="files">
	    	
	    </div>
	</div>
</div>

<button class="btn btn-primary">Crear</button>
<?php ActiveForm::end(); ?>
</div>


 <?= $this->render('_create_novedad_capacitacion', [
                'model2' => $model2,
				'list_tema' => $list_tema,
				
    ]) ?>

<?= $this->render('_create_novedad_pedido', [
                'model3' => $model3,
				
				
    ]) ?>

<script type="text/javascript">
	

		$('#tipo').change(function(event) {
			
			var tipo=$(this).val();
			
			switch(tipo) {
			    case 'Inspeccion-Semestral':
			        
			        $('#visita').show('slow/400/fast', function() {
			        	
			        });

			        $('#capacitacion').hide('slow/400/fast', function() {
			        	
			        });

			        $('#pedido').hide('slow/400/fast', function() {
			        	
			        });

			    break;
			    case 'Capacitacion':

			        $('#capacitacion').show('slow/400/fast', function() {
			        	
			        });

			        $('#visita').hide('slow/400/fast', function() {
			        	
			        });

			        $('#pedido').hide('slow/400/fast', function() {
			        	
			        });

			    break;

			    case 'Pedido':

			    	$('#pedido').show('slow/400/fast', function() {
			        	
			        });


			        $('#capacitacion').hide('slow/400/fast', function() {
			        	
			        });

			        $('#visita').hide('slow/400/fast', function() {
			        	
			        });

			    break;

			    default: 
		            console.log('default');
		        break;
			    
			}
			
		});
	

	function NovedadCategoria(objeto){

		$.ajax({
            url:"<?php echo Yii::$app->request->baseUrl . '/visita-mensual/novedad-categoria'; ?>",
            type:'POST',
            dataType:"json",
            cache:false,
            async:false,
            data: {
                categoria: $(objeto).val(),
               
            },
            beforeSend:  function() {
            	$('#btn_calcular').hide();
                $('#body_ayuda').html('Actualizando... <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>');
            },
            success: function(data){
              
              $('#novedad').html(data.resp);
            }
        });

	}

	function plan_de_accion(opcion){
		switch(opcion) {

			case 'S':
			        
			    $('#plan_accion').show('slow/400/fast', function() {
			    	
			    });
			break;

			case 'N':
			        
			    $('#plan_accion').hide('slow/400/fast', function() {
			    	
			    });
			break;
		}

		
	}


	$('#form-visita').submit(function(event) {
			

		if($("#check_si").is(':checked')){
			var plan_de_accion=$('#visitamensualdetalle-plan_de_accion').val();
			if ($.trim(plan_de_accion)=='') {
				alert('El plan de accion no puede estar vacio');

				return false;
			}
		}


	});

	var files_cont=1;
	function agregar_file(){
	var html='<div id="file'+files_cont+'">';
		html+='<div class="input-group">';
		html+='<div class="input-group-btn">';
		html+=' <label  id="browsebutton" class="btn btn-default " for="my-file-selector'+files_cont+'" style="background-color:white">';
		html+='<input onchange="text_file(this,'+files_cont+');" id="my-file-selector'+files_cont+'" type="file" name="VisitaMensualDetalle[file][]" style="display:none;" >';
		html+='<i class="fa  fa-camera"></i> Adjuntar Archivo...</label>';
		html+="<button class='btn btn-danger ' type='button' onclick='quitar_file("+files_cont+",this)'><i class='fa fa-trash'></i></button>";
		html+='</div>';
		html+='<input id="label-'+files_cont+'" type="text" class="form-control" readonly=""></div>';
		
		html+='<br></div>';

		$('#files').append(html);
		files_cont++;

	}

	function quitar_file(cont,objeto){
		var confirmar=confirm('Desea eliminar este elemento?');
		if(confirmar){
			$('#file'+cont).remove();
			$(objeto).remove();
		}	
	}

	function text_file(objeto,num){
    	var valor=objeto.files[0].name;
    	//alert(valor);
    	$('#label-'+num).val(valor);
    	
    }
</script>