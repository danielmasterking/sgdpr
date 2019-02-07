<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use marqu3s\summernote\Summernote;
use kartik\widgets\TimePicker;
use kartik\widgets\FileInput;
use kartik\widgets\DepDrop ;
use kartik\datecontrol\Module;
use kartik\datecontrol\DateControl;
date_default_timezone_set ( 'America/Bogota');
$fecha = date('Y-m-d',time());
/* @var $this yii\web\View */
/* @var $model app\models\Capacitacion */
/* @var $form yii\widgets\ActiveForm */
$data_novedades = array();
foreach ($novedades as $value) {
    
	if($value->id != 23){
          
      $data_novedades[$value->id] = $value->nombre;		  
	  
	}else{
		
		//Pendiente validar rol de usuario actual para asignar novedad de 
		//estrellas del servicio.
		if(isset(Yii::$app->session['usuario-exito']) ){
			
			$data_novedades[$value->id] = $value->nombre;		  
			
		}
		
	}
    
}

$ciudades_zonas = array();

foreach($zonasUsuario as $zonaO){
	
     $ciudades_zonas [] = $zonaO->zona->ciudades;	
	
}

$ciudades_permitidas = array();

foreach($ciudades_zonas as $ciudades){
	
	foreach($ciudades as $ciudad){
		
		$ciudades_permitidas [] = $ciudad->ciudad->codigo_dane;
		
	}
	
}

$marcas_permitidas = array();

foreach($marcasUsuario as $marca){
	
		
		$marcas_permitidas [] = $marca->marca_id;

}

$dependencias_distritos = array();

foreach($distritosUsuario as $distrito){
	
     $dependencias_distritos [] = $distrito->distrito->dependencias;	
	
}

$dependencias_permitidas = array();

foreach($dependencias_distritos as $dependencias0){
	
	foreach($dependencias0 as $dependencia0){
		
		$dependencias_permitidas [] = $dependencia0->dependencia->codigo;
		
	}
	
}

$tamano_dependencias_permitidas = count($dependencias_permitidas);

$data_dependencias = array();

foreach($dependencias as $value){
	
	if(in_array($value->ciudad_codigo_dane,$ciudades_permitidas)){
		
		if(in_array($value->marca_id,$marcas_permitidas)){
			
		   if($tamano_dependencias_permitidas > 0){
			   
			   if(in_array($value->codigo,$dependencias_permitidas)){
				   
				 $data_dependencias [] = array('codigo' => $value->codigo, 'nombre' => $value->nombre);	  
				   
			   }else{
				   
				   	//temporal mientras se asocian distritos
				   $data_dependencias [] = array('codigo' => $value->codigo, 'nombre' => $value->nombre);	  
				   
			   }
			   
			   
		   }else{
			   
			   $data_dependencias [] = array('codigo' => $value->codigo, 'nombre' => $value->nombre);			
		   }	
       
		}

	}
}

$data_cordinadores = array();

foreach($cordinadores as $value){
	
	if($value->usuario !== 'admin'){
		
	  $roles = $value->roles;	
	  
	   foreach($roles as $rol){
		   
		   if($rol->rol_id === 2){
			   
			   $data_cordinadores[$value->usuario] = $value->nombres.' '.$value->apellidos;
			   
		   }
		   
	   }
		
	  
	}
	
}

?>

<script>

    var dependencias = <?php echo json_encode($data_dependencias);?>;
	var len = dependencias.length;
	var index = 1;

</script>

<div class="capacitacion-form">

    <?php $form = ActiveForm::begin([

        'options'=>['enctype'=>'multipart/form-data'] // important


    ]); ?>
	
	
	<div class="col-md-12">
	  <div class="col-md-12">
	   <?= $form->field($model, 'fecha')->textInput(['value' => $fecha,'readonly' => 'readonly']) ?>
	   </div>
	</div>
	
    <div class="col-md-12">
	  <div class="col-md-12">
	<?=

       $form->field($model, 'novedad_id')->widget(Select2::classname(), [
       
	   'data' => $data_novedades,
	   'options' => ['placeholder' => 'Tema', ],
    
      ])


     ?>
	 </div>   
	 </div>   

	  <div class="col-md-12">
	  
	    
	    <div class="col-md-12">
		
           <?= $form->field($model, 'fecha_capacitacion')->widget(DateControl::classname(), [
				  'autoWidget'=>true,
				 'displayFormat' => 'php:Y-m-d',
				 'saveFormat' => 'php:Y-m-d',
				  'type'=>DateControl::FORMAT_DATE,
     
           ]);?>
		
		</div>
	 </div>

   	   <div class="col-md-12">
		  <div class="col-md-12">
		

		
		    <div id="add-permiso" class="form-group">
			
			  <div class="row">
			  
			    <div class="col-md-10">
				
				<div class="row" id="ocultar">
				   
				  <div class="col-md-2">
				     
					 <input type="checkbox" name="todas" id="todas" /> Todas
					 
				  </div>
				  
				  <div class="col-md-4">
				  
				        <?php
							
							   echo Select2::widget([
								'name' => 'instructor',
								'data' => $data_cordinadores,
								'options' => [
									'id' => 'instructor',
									'placeholder' => 'Cordinador',
																
								 ],


							   ]);
							
						?>	
				  
				  </div>
				  
				   <div class="col-md-6">
				     
					 <div class="col-md-5">
					   <label >Asistentes</label> 
					 </div>
					 
					 <div class="col-md-7">
					   <input type="text"  class="form-control" name="asistentes" id="asistentes" placeholder="# de asistentes."/>
					 </div>
				     
					 
					 
				  </div>
				
				
				</div>
				  
				  

				</div>

			    <div class="col-md-2">

				<p>&nbsp;</p>
				  <button type="button" id="btn-add" class="btn btn-default btn-primary pull-right" aria-label="Left Align">
					<span class="glyphicon glyphicon-plus" aria-hidden="true"> Dependencia</span>
				  </button>


				</div>				
			  
			  </div>

               

           </div>
		
		   <div id="dependencias" class="form-group">
		   
		   </div>
		   
		   <div class="form-group">
		      <p>&nbsp;</p>
		   </div>
		   
		   	<?= $form->field($model, 'observaciones')->widget(Summernote::className(), [
		  
		    ]) ?>   
		
		<label>Registro Fotografico</label>
		<button class="btn btn-primary btn-xs" onclick="agregar_file();" type="button">
			<i class="fa fa-plus"></i>
		</button>
		<?//= $form->field($model, 'image[]')->fileInput(['multiple' => false, 'id'=>'file0'])->label(false) ?>
		<div id="file0">
			<div class="input-group">
		        <label  id="browsebutton" class="btn btn-default input-group-addon" for="my-file-selector0" style="background-color:white">

		             <input onchange="text_file(this,0);" id="my-file-selector0" type="file" name="Capacitacion[image][]" style="display:none;" >
		            
		            <i class="fa  fa-camera"></i> Adjuntar una imagen...
		        </label>
		        <input id="label-0" type="text" class="form-control" readonly="">
		    </div>
		
		</div>
		<?php
			 // Usage with ActiveForm and model
			 /*echo $form->field($model, 'image')->widget(FileInput::classname(), [
			//'options' => ['accept' => 'image/*'],
			'pluginOptions'=>['allowedFileExtensions'=>['jpg', 'gif', 'png','jpeg'],
							   'maxFileSize' => 5120,
			  ]
			 ]);*/

		?>
		<br>
		<div id="files">
	    	
	    </div>
		
		<?php
			 // Usage with ActiveForm and model
			 echo $form->field($model, 'file')->widget(FileInput::classname(), [
			//'options' => ['accept' => 'image/*'],
			'pluginOptions'=>['allowedFileExtensions'=>['xls', 'xlsx', 'pdf','jpg','png','gif','jpeg'],
							   'maxFileSize' => 5120,
			  ]
			 ]);

		 ?>
	    
		
		<input type="hidden" name="cantidad" id="cantidad" value="0"/>		
		<?= Html::activeHiddenInput($model, 'usuario',['value' => Yii::$app->session['usuario-exito'] ])?>						
            <div class="form-group">
              <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' =>  'btn btn-primary']) ?>
            </div>
		
		 </div>
	   </div>	



    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
	var files_cont=1;
	function agregar_file(){
			var file=$('#my-file-selector0').clone().attr({
			id: 'my-file-selector'+files_cont
		});
		var html='<div id="file'+files_cont+'">';
		html+='<div class="input-group">';
		html+='<div class="input-group-btn">';
		html+=' <label  id="browsebutton" class="btn btn-default " for="my-file-selector'+files_cont+'" style="background-color:white">';
		html+='<input onchange="text_file(this,'+files_cont+');" id="my-file-selector'+files_cont+'" type="file" name="Capacitacion[image][]" style="display:none;" >';
		html+='<i class="fa  fa-camera"></i> Adjuntar una imagen...</label>';
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