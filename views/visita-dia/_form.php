<style type="text/css">
	/* Switch button */
.btn-default.btn-on.active{background-color: #5BB75B;color: white;}
.btn-default.btn-off.active{background-color: #DA4F49;color: white;}

.btn-default.btn-on-1.active{background-color: #006FFC;color: white;}
.btn-default.btn-off-1.active{background-color: #DA4F49;color: white;}

.btn-default.btn-on-2.active{background-color: #00D590;color: white;}
.btn-default.btn-off-2.active{background-color: #A7A7A7;color: white;}

.btn-default.btn-on-3.active{color: #5BB75B;font-weight:bolder;}
.btn-default.btn-off-3.active{color: #DA4F49;font-weight:bolder;}

.btn-default.btn-on-4.active{background-color: #006FFC;color: #5BB75B;}
.btn-default.btn-off-4.active{background-color: #DA4F49;color: #DA4F49;}
</style>
<?php
use lo\widgets\Toggle;
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
use kartik\widgets\DatePicker;
use app\models\NovedadCategoriaVisita;
use app\models\ValorNovedad;

date_default_timezone_set ( 'America/Bogota');
$fecha = date('Y-m-d');
$orden = 0;
$data_dependencias = array();
$ciudades_zonas = array();

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


$data_secciones = array();
foreach($secciones as $seccion){
	
	$data_secciones[$seccion->id] = $seccion->nombre;
	
}

$arr_secc=['a','b','c','d','e','f','g','h','i','j','k','l','m','n','ñ','o','p','q','r','s','t','u','x','y','z'];
?>

<div class="visita-dia-form">

       <?php $form = ActiveForm::begin([

        'options'=>['enctype'=>'multipart/form-data'] // important


    ]); ?>
	
	<div class="col-md-12">
	  <div class="col-md-6">
	   <?//= $form->field($model, 'fecha')->textInput(['value' => $fecha,'readonly' => 'readonly']) ?>

	   <?= $form->field($model, 'fecha')->widget(DatePicker::classname(), [
		    'options' => ['value' => $fecha],
		    'pluginOptions' => [
		        'autoclose'=>true,
		        'format' => 'yyyy-mm-dd',
		    ]
		]); ?>
	   </div>

	    <div class="col-md-6">
	  	<?= $form->field($model, 'centro_costo_codigo')->widget(Select2::classname(), [
		   	'data' => $data_dependencias,
			'options' => [
			'id' => 'dependencia',
			'placeholder' => 'Dependencia',
										
		    ],
	    
	      	])
		?>
	   
	   </div>
	</div>
	
	
	
	<div class="col-md-12">
	  <div class="col-md-6">
	  
	     <?php

                // Child # 1
          echo $form->field($model, 'responsable')->widget(DepDrop::classname(), [
              'type'=>DepDrop::TYPE_SELECT2,
               'data' => [1 => ''],   
              'pluginOptions'=>[
              
                  'depends'=>['dependencia'],
                  'placeholder' => 'Select...',
                  'url'=>Url::to(['responsable/dependencia']),
                  //'params'=>['input-type-1', 'input-type-2']
              ]
          ]);


        ?>

        
	   
	   </div>
	   <div class="col-md-6">
     <?= $form->field($model, 'otro')->textInput(['maxlength' => true,'readonly' => 'readonly']) ?>
	   
	   </div>
	</div>
	
    <div class="col-md-12">
	  <div class="col-md-12">
	    

		<?php 
			foreach($categorias as $categoria):

				$clase=str_replace(' ','-',$categoria->nombre);
				$clase=str_replace('.','',$clase);
			//echo $clase;
			
		?>
		<div class="panel panel-primary">
 		  <div class="panel-body">
			<div class="row">
			   
				  <div class="col-md-12">
				  <p>&nbsp;</p>
				   <p><strong><h3> <?= $categoria->nombre?></h3></strong></p>
				  	
				  	<div class="btn-group" id="status" data-toggle="buttons">
		              <label class="btn btn-default btn-on btn-xs active" onclick="activar('<?= $clase ?>');" >
		              <input onclick="activar('<?= $clase?>');" type="radio" value="1" name="multifeatured_module[module_id][status]" checked="checked">Aplica</label>
		              <label class="btn btn-default btn-off btn-xs " onclick="desactivar('<?= $clase?>');">
		              <input onclick="desactivar('<?= $clase ?>');" type="radio" value="0" name="multifeatured_module[module_id][status]">No aplica</label>
		            </div>
				  	
				  </div>
				  
				 			   
			 </div>
			 	<?php 

			 		$categorias_visita=str_replace(' ','-', $categoria->nombre);
			 		$categorias_visita=str_replace('.','', $categorias_visita);
			 	?>
				<input type="hidden" name="categoria[]" value="<?= $categorias_visita ?>">
		   <?php 
		   	 
		      //obtener Novedades categoría
			  //$novedades = $categoria->novedades;
			  $novedades=NovedadCategoriaVisita::find()->where('estado="A" AND categoria_visita_id='.$categoria->id.' ')->all();
			  
			  foreach($novedades as $novedad):
			   if($novedad->estado!='I'):
			    //$valores = $novedad->valorNovedades;
			   	$valores =ValorNovedad::find()->where('novedad_categoria_visita_id='.$novedad->id)->all();
				$orden++;
				$data_valor =  array();
				$novedad_visita=str_replace(' ','-', $novedad->nombre);

                foreach($valores as $valor){
					if($valor->resultado->estado!='I'){
						$data_valor[$valor->resultado->id] = $valor->resultado->nombre;
					}
					
				}	

				// echo "<pre>";
			 //  print_r($data_valor);
			 //  echo "</pre>";			
			  
				 
			  ?>
			 
 	
			   <div class="row" >
			   
				  <div class="col-md-4">
				   <p>&nbsp;</p>
				  
				   <p><strong><?= $orden?></strong><?= '. '.$novedad->nombre?></p>
				  	<input type="hidden" name="<?= $categorias_visita ?>[pregunta<?= $orden ?>][pregunta]" value="<?= $novedad->id ?>">
				  </div>
				  
				  <div class="col-md-3">
				   <p>&nbsp;</p>
				    <?php
					
					   echo Select2::widget([
						'name' => $categorias_visita.'[pregunta'.$orden.'][respuesta]',
						'data' => $data_valor,
						'options' => [
							'id' => 'valor-novedad-'.$novedad->id,
							'class'=>$clase
							//'placeholder' => 'Estado',
														
						 ],


					   ]);
					
					?>
				  </div>
				  
				  <div class="col-md-3">
				   	<p>&nbsp;</p>
					<?php
					
					   //Obtener Mensajes Novedades
					  				   
					   $mensajes = $valores[0]->mensajeNovedades;				   
					   $id_data = ($mensajes != null ) ? $mensajes[0]->id : 1;
					   $value_data = ($mensajes != null ) ? $mensajes[0]->mensaje : '';
					   
					   
					
					   echo DepDrop::widget([
					    'name' => $categorias_visita.'[pregunta'.$orden.'][mensaje]',
						'options' => ['id' => 'mensaje-novedad-'.$novedad->id,'class'=>$clase." mensaje-secc"],
						'type'=>DepDrop::TYPE_SELECT2,
						 'data' => [$id_data => $value_data], 
						 //'select2Options'=>['pluginOptions'=>['allowClear'=>true]],  
						'pluginOptions'=>[
						
							'depends'=>['valor-novedad-'.$novedad->id],
							//'placeholder' => 'Select...',
							'url'=>Url::to(['mensaje-novedad/mensaje?novedad='.$novedad->id]),
							'initDepends' => ['valor-novedad-'.$novedad->id],
							//'initialize' => true
							//'params'=>['input-type-1', 'input-type-2']
						]
						
					   ]);
					
					?>
				  </div>
				  
				  <div class="col-md-2">
				  <p>&nbsp;</p>
				      <input type="text" name="<?= $categorias_visita ?>[pregunta<?=$orden?>][comentario]" class="form-control <?= $clase?>" placeholder="Comentario"/> 
				  </div>
			   
			   
			   </div>

			   <?php

			    if($novedad->seccion=='S'){
			   ?>
			    <button class="btn btn-success <?= $clase?>" onclick="agregar_secion(<?= $orden ?>,'<?= $categorias_visita ?>');" type="button"><i class="fa fa-plus"></i></button> 

			    <button class="btn btn-danger <?= $clase?>" onclick="quitar_secion(<?= $orden ?>);" type="button"><i class="fa fa-minus"></i></button> 

			 <div class="row" id="seccion<?= $orden?>">
			   
				  <div class="col-md-4">
				   <p>&nbsp;</p>
				  		
				  

				   <div class="row" >
				   
				       <div class="col-md-4">
					   
					   <p><b>-</b> Sección</p>
					   
					   </div>
					   
				   
				       <div class="col-md-8">
					   
							<?php
							
						        $mensajes = $valores[0]->mensajeNovedades;				   
					            $id_data = ($mensajes != null ) ? $mensajes[0]->id : 1;
					            $value_data = ($mensajes != null ) ? $mensajes[0]->mensaje : '';
							
							   /*echo Select2::widget([
								'name' =>$categorias_visita.'[pregunta'.$orden.'][secciones][secc-'.$i.'][seccion]',
								'data' => $data_secciones,
								'options' => [
									'id' => 'seccion-'.$novedad_visita,
									//'placeholder' => 'Sección',
									'class'=>$clase
																
								 ],


							   ]);*/



							   echo Html::dropDownList($categorias_visita.'[pregunta'.$orden.'][secciones][secc-0][seccion]',$selection = null, $data_secciones, [
								   
								    'class'=>'form-control '.$clase.' secc-'.$orden.' '
								]);

							
							?>						   
					   
					   </div>					   
				   
				   </div>				   
				  
				  </div>
				  
				  <div class="col-md-3">
				   <p>&nbsp;</p>
				    <?php
					
					  /* echo Select2::widget([
						'name' => $categorias_visita.'[pregunta'.$orden.'][secciones][secc-][respuesta-seccion]',
						'data' => $data_valor,
						'options' => [
							'id' => 'valor-seccion-'.$novedad_visita,
							'class'=>$clase
							//'placeholder' => 'Estado',
														
						 ],


					   ]);*/


					    echo Html::dropDownList($categorias_visita.'[pregunta'.$orden.'][secciones][secc-0][respuesta-seccion]',$selection = null, $data_valor, [
						   
						    'class'=>'form-control  '.$clase.'  '
						]);
					
					?>
				  </div>
				  
				  <!-- <div class="col-md-3">
				   	<p>&nbsp;</p>
					<?php
					
					  /* echo DepDrop::widget([
					    'name' => $categorias_visita.'[pregunta'.$orden.'][secciones][secc-][mensaje-seccion]',
						'options' => ['id' => 'mensaje-seccion-'.$arr_secc[$i],'class'=>$clase],
						'type'=>DepDrop::TYPE_SELECT2,
						 'data' => [$id_data => $value_data],   
						'pluginOptions'=>[
						
							'depends'=>['valor-seccion-'.$novedad_visita],
							//'placeholder' => 'Select...',
							'url'=>Url::to(['mensaje-novedad/mensaje']),

							//'params'=>['input-type-1', 'input-type-2']
						]
						
					   ]);*/
					
					?>
				  </div> -->
				  
				<!--   <div class="col-md-2">
				  <p>&nbsp;</p>
				      <input type="text" class="form-control <?= $clase?>" name="<?= $categorias_visita ?>[pregunta<?=$orden?>][secciones][secc-][comentario-seccion]" placeholder="Comentario"/> 
				  </div> -->
			   
			   
			   </div>	

			   <div  id="secciones-new<?= $orden?>">
			   	
			   </div>

			   <?php
				}

				
			   ?>

			  <?php 
			  	endif;
			  	endforeach;
			  	
			  ?>
			 </div>
			</div>
		
		<?php endforeach;?>
		
		    <p style="clear: both;">&nbsp;</p>
		
			<?= $form->field($model, 'observaciones')->widget(Summernote::className(), []) ?>  
			
			   <!-- Fotografía -->
	   	<label>Registro Fotografico</label>
		<button class="btn btn-primary btn-xs" onclick="agregar_file();" type="button">
			<i class="fa fa-plus"></i>
		</button>
		<div id="file0">
			<div class="input-group">
		        <label  id="browsebutton" class="btn btn-default input-group-addon" for="my-file-selector0" style="background-color:white">

		             <input onchange="text_file(this,0);" id="my-file-selector0" type="file" name="VisitaDia[image][]" style="display:none;" >
		             <?//= $form->field($model, 'image[]')->fileInput(['multiple' => false, 'id'=>'my-file-selector0','class'=>'form-control','onchange'=>'text_file(this,0);','style'=>'display:none;'])->label(false) ?>
		           
		            <i class="fa  fa-camera"></i> Adjuntar una imagen...
		        </label>
		        <input id="label-0" type="text" class="form-control" readonly="">
		    </div>
		  
		  <!-- 	<span class="help-block">
				<small id="fileHelp" class="form-text text-muted">Only CSV with size less than 2MB is allowed.</small>
			</span> -->
		</div>
	   	<?php
			 // Usage with ActiveForm and model
			/* echo $form->field($model, 'image')->widget(FileInput::classname(), [
			//'options' => ['accept' => 'image/*'],
			'pluginOptions'=>['allowedFileExtensions'=>['jpg', 'gif', 'png','jpeg'],
							   'maxFileSize' => 5120,
			  ]
			 ]);*/

		?>	
		<br>
		<div id="files">
	    	
	    </div>
		<br>
		
		
		<div class="form-group">
           <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' =>  'btn btn-primary']) ?>
        </div>
	   
	   </div>
	   

	   
	   
	</div>

    <input type="hidden" name="cantidad" id="cantidad" value="<?=$orden?>" />
    <?= Html::activeHiddenInput($model, 'usuario',['value' => Yii::$app->session['usuario-exito'] ])?>						

 




    <?php ActiveForm::end(); ?>

</div>

<script type="text/javascript">
	$(function(){

		$('#w0').submit(function(event) {
			var count=0;
			$('.mensaje-secc').each(function(index, el) {
				
				if($(this).val()==''){
					count+=1;
				}

			});

			if (count>0) {

				alert('Todos los detalles de las respuestas deben ser seleccionados');

				return false;
			}
		});


	});
	
	function desactivar(clase){

		
		$('.'+clase).attr({
			disabled:true
			
		});
	}

	function activar(clase){

		
		$('.'+clase).removeAttr('disabled');
	}

	function agregar_secion(orden,categoria){

		var cantidad=0;
		$('#secciones-new'+orden).find('.secc-'+orden+' ').each(function(index, el) {
			cantidad++;
		});

		cantidad=cantidad+1;

		var cont=1;
		$('#seccion'+orden).clone().appendTo('#secciones-new'+orden).find('select').each(function(index, el) {
			if(cont==1){
				 $(this).attr({
			     	name: categoria+'[pregunta'+orden+'][secciones][secc-'+cantidad+'][seccion]'
				
			    });

				

			}else if(cont==2){

				$(this).attr({
			     	name: categoria+'[pregunta'+orden+'][secciones][secc-'+cantidad+'][respuesta-seccion]'
				
			    });

			    
			}
			
			cont++;
			
		});
	}

	function quitar_secion(orden){


		$('#secciones-new'+orden).find('#seccion'+orden+':last').remove();
	}

	var files_cont=1;
	function agregar_file(){
			var file=$('#my-file-selector0').clone().attr({
			id: 'my-file-selector'+files_cont
		});
		var html='<div id="file'+files_cont+'">';
		html+='<div class="input-group">';
		html+='<div class="input-group-btn">';
		html+=' <label  id="browsebutton" class="btn btn-default " for="my-file-selector'+files_cont+'" style="background-color:white">';
		html+='<input onchange="text_file(this,'+files_cont+');" id="my-file-selector'+files_cont+'" type="file" name="VisitaDia[image][]" style="display:none;" >';
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
