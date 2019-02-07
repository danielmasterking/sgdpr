<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use marqu3s\summernote\Summernote;
use kartik\widgets\TimePicker;
use yii\web\JsExpression;
use kartik\widgets\FileInput;
use kartik\widgets\DepDrop ;
use kartik\datecontrol\Module;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
date_default_timezone_set ( 'America/Bogota');
$fecha = date('Y-m-d',time());
$data_dependencias = array();
$ciudades_zonas = array();

$data_novedades = array();
foreach ($novedades as $value) {
    
    $data_novedades[$value->id] = $value->nombre;
}


foreach($zonasUsuario as $zona){
	
     $ciudades_zonas [] = $zona->zona->ciudades;	
	
}

$ciudades_permitidas = array();

foreach($ciudades_zonas as $ciudades){
	
	foreach($ciudades as $ciudad){
		
		$ciudades_permitidas [] = $ciudad->ciudad->codigo_dane;
		
	}
	
}

$marcas_permitidas = array();
$data_marcas = array();

foreach($marcasUsuario as $marca){
	
		
		$marcas_permitidas [] = $marca->marca_id;
		$data_marcas[$marca->marca_id] = $marca->marca->nombre; 

}

$dependencias_distritos = array();
$data_distritos = array();
foreach($distritosUsuario as $distrito){
	
     $dependencias_distritos [] = $distrito->distrito->dependencias;	
	 $data_distritos [$distrito->distrito_id] = $distrito->distrito->nombre; 
	
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
				   
				 $data_dependencias[$value->codigo] =  $value->nombre;
				   
			   }else{
				   //temporal mientras se asocian distritos
				   $data_dependencias[$value->codigo] =  $value->nombre;
			   }
			   
			   
		   }else{
			   
			   $data_dependencias[$value->codigo] =  $value->nombre;
		   }	
       
		}

	}
}




?>

<div class="visita-dia-form">

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
    
      ])

     ?>
	  
	  
	  <div class="col-md-6">
	  
	  	  	<?=

			   $form->field($model, 'centro_costo_codigo')->widget(Select2::classname(), [
			   
			   'data' => $data_dependencias,
				'options' => [
				'id' => 'dependencia',
				'placeholder' => 'Dependencia',
											
				],
			
			  ])


			 ?>
	  
	  </div>
	  
	  <div class="col-md-3">
	     
	    
		  <?= $form->field($model, 'cantidad_apoyo')->textInput() ?>
	  
	  </div>
	  
	  	  <div class="col-md-3">
	     
	    
		  
	  
	      </div>
	  

	   
	   </div>
	</div>

    <div class="col-md-12">

	   <div class="col-md-12">
	   
	   	  <div class="col-md-6">
	       
		   <label><input type="checkbox" id="otros-chk" /> Otros</label>
		   <?= $form->field($model, 'otros')->textInput(['readonly' => 'readonly']) ?>
		  
		  </div>
		  
		  <div class="col-md-3">
		      <label>Cantidad de apoyo</label>
			 <?= $form->field($model, 'cantidad_apoyo_otros')->textInput(['readonly' => 'readonly']) ?>
		  
		  </div>
		  
		  	  	  <div class="col-md-3">
	     
	    
		  
	  
	      </div>
	  
	   
	   </div>
	   
	   

	</div>
	
	<!--<div class="col-md-12">
	   
	     <div class="col-md-12">
		   
		   <div class="form-group"> 
		   
		   <label><strong>Detalle del evento:</strong></label>
		   
		   </div>
		  
		   
		   <div class="col-md-6">
		      
			  <label><input type="checkbox" id="marca-chk" checked="checked"/> Marca</label>
			  
			  <div id="div-marca" class="show">
			  
			  			 
				 <?php

				   /*Select2::widget([
				   'name' => 'marca_select',
				   'data' => $data_marcas,
					'options' => [
					'id' => 'marca_select',
					'placeholder' => 'Marca',
					
												
					],
				
				  ])*/


				 ?>
			  
			  </div>

			 
		   
		   </div>
		   <div class="col-md-6">
		       <label><input type="checkbox" id="distrito-chk" checked="checked"/> Distrito</label>
			  
			  <div id="div-distrito" class="show">
			  <?php
			  /*
			   Select2::widget([
			   'name' => 'distrito_select',
			   'data' => $data_distritos,
				'options' => [
				'id' => 'distrito_select',
				'placeholder' => 'Distrito',
				'class' => 'hidden',
											
				],
			
			  ])*/


			 ?>
			 
			 </div>
		   </div>
		   
	     </div>   
		
	</div>-->
	
	<p>&nbsp;</p>
	
	 <div class="col-md-12">
		  <div class="col-md-12">
		
		<?= $form->field($model, 'descripcion')->widget(Summernote::className(), [
		
			'clientOptions' => [
				
	               'height' => 120,

				]
		  
		]) ?>   
		
		 </div>
	   </div>	
	
		   <div class="col-md-12">
		  <div class="col-md-12">
           <label>Fotografías (3 imágenes máximo)</label>
			<button class="btn btn-primary btn-xs" onclick="agregar_file();" type="button">
				<i class="fa fa-plus"></i>
			</button>
           	<div id="file0">
			<div class="input-group">
		        <label  id="browsebutton" class="btn btn-default input-group-addon" for="my-file-selector0" style="background-color:white">

		             <input onchange="text_file(this,0);" id="my-file-selector0" type="file" name="Evento[image][]" style="display:none;" >
		             <?//= $form->field($model, 'image[]')->fileInput(['multiple' => false, 'id'=>'my-file-selector0','class'=>'form-control','onchange'=>'text_file(this,0);','style'=>'display:none;'])->label(false) ?>
		           
		            <i class="fa  fa-camera"></i> Adjuntar una imagen...
		        </label>
		        <input id="label-0" type="text" class="form-control" readonly="">
		    </div>
		  
		  <!-- 	<span class="help-block">
				<small id="fileHelp" class="form-text text-muted">Only CSV with size less than 2MB is allowed.</small>
			</span> -->
		</div>

		<br>
		<div id="files">
	    	
	    </div>
		  <?php
			 // Usage with ActiveForm and model
			 /*echo $form->field($model, 'image[]')->widget(FileInput::classname(), [
			'options'=>['accept'=>'image/*', 'multiple'=>true],
			'pluginOptions'=>['allowedFileExtensions'=>['jpg', 'gif', 'png','jpeg'],
							   'maxFileSize' => 5120,
			  ]
			 ]);*/

			 ?>
			
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
		html+='<input onchange="text_file(this,'+files_cont+');" id="my-file-selector'+files_cont+'" type="file" name="Evento[image][]" style="display:none;" >';
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
