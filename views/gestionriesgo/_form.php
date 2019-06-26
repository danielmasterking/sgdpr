<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use marqu3s\summernote\Summernote;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\models\GestionRiesgo */
/* @var $form yii\widgets\ActiveForm */

date_default_timezone_set ( 'America/Bogota');
$fecha = date('Y-m-d');
$orden = 1;
$ciudades_zonas = array();

$dependencias_distritos = array();




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


?>
<!-- <div class="alert alert-info" role="alert">
	<i class="glyphicon glyphicon-warning-sign"></i> Este modulo se encuentra en mantenimiento por lo tanto estara suspendido temporalmente gracias por su comprension...
</div> -->
<div class="gestion-riesgo-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
    	<div class="col-md-4">
    		<?= $form->field($model, 'fecha')->textInput([
    			'value'=>$fecha,'readonly'=>true,
			

    		]) ?>
    	</div>

    	<div class="col-md-4">
		    <?=



		       $form->field($model, 'id_centro_costo')->widget(Select2::classname(), [
		       
			   'data' => $data_dependencias,
				'options' => [
				'id' => 'dependencia',
				'placeholder' => 'Dependencia',
											
			    ],
		    
		      ])


		     ?>
		</div>

		<div class="col-md-4">
			 <?= $form->field($model, 'fecha_visita')->widget(DateControl::classname(), [
				  'autoWidget'=>true,
				 'displayFormat' => 'php:Y-m-d',
				 'saveFormat' => 'php:Y-m-d',
				  'type'=>DateControl::FORMAT_DATE,
     
           ]);?>
			

		</div>
    </div>

    
    <?php  

    	foreach($consultas as $row_con):
    ?>

	<div class="panel panel-primary">
  		<div class="panel-body">
		    <div class="row">
				<div class="col-md-12">
					<p>&nbsp;</p>
						 

					<input type="hidden" name="preguntas[]" value="<?= $row_con->id ?>">

					<p>

					<strong style="font-size: 15px;"><?= $orden?> </strong>

					<b style="font-size: 15px;"><?= '. '.$row_con->descripcion?></b> 

					<a onclick="ayuda(<?= $row_con->id?>,'<?= $row_con->descripcion?>');" data-toggle="modal" data-target="#myModal" title="Click para observar texto de ayuda"><i class="far fa-question-circle fa-2x"></i></a>

					

					</p>
						  
				</div>

				
			</div>


			<div class="row">
				<div class="col-md-6">
				   	<select class="form-control" name="repuesta[]"  onchange="plan_accion(<?= $orden?>);" id='resp<?= $orden ?>'  required>
				   		<option value="">Selecciona una respuesta</option>
				   		<?php
				   		foreach ($respuestas as $row_resp) {
							$list_respuestas[$row_resp->id]=$row_resp->descripcion;

							echo "<option value='".$row_resp->id."'>".$row_resp->descripcion."</option>";
						}
				   		?>
				   	</select>
				    
				</div>

				<div class="col-xs-1">
					<a class='text-danger' onclick="ayuda_resp(<?= $row_con->id?>,'<?= $row_con->descripcion?>');" data-toggle="modal" data-target="#myModal1" title="Click para observar texto de ayuda de respuestas"><i class="far fa-question-circle fa-2x"></i></a>
				</div>
				
			</div>

			<br>

			<div class="row">
				<div class="col-md-12">
					<label style="font-size: 15px;">Observación</label>
					<textarea name="observacion[]" rows="4" class="form-control" id="obs<?= $orden?>" ></textarea>


				</div>

				
			</div>
			<br>
			<div class="row" style="display:none;" id="plan_accion<?= $orden?>">
				<div class="col-md-12" >
					<label style="font-size: 15px;">Planes de acción</label>
					<textarea name="planes[]" rows="4" class="form-control" id="planes<?= $orden?>"></textarea>
				</div>
			</div>
		</div>
	</div>

    <?php   
     $orden++;
     endforeach; 
    ?>


    <div class="row">
    	<div class="col-md-12">
    		<?= $form->field($model, 'observacion')->widget(Summernote::className(), [
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



</div>


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="ayuda_header" >Modal title</h4>
      </div>
      <div class="modal-body" id="body_ayuda">
        ...
      </div>
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> -->
    </div>
  </div>
</div>



<!-- Modal Respuestas-->
<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="ayuda_header_resp" >Modal title</h4>
      </div>
      <div class="modal-body" id="body_ayuda_resp">
        ...
      </div>
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> -->
    </div>
  </div>
</div>




<script type="text/javascript">
	

	function plan_accion(orden){

		var desc=$('#resp'+orden+' option:selected').text();

		if (desc=='No cumple ' || desc=='En proceso') {
			$('#plan_accion'+orden).show('slow/400/fast', function() {
				
			});

			$('#obs'+orden).attr('required','required');
			

			$('#planes'+orden).attr('required','required');

			

		}else{
			$('#plan_accion'+orden).hide('slow/400/fast', function() {
				
			});

			 $('#obs'+orden).removeAttr('required');

			

			 $('#planes'+orden).removeAttr('required');

			
		}

	} 


	function ayuda(id,titulo){

		$("#ayuda_header").html(titulo);
		$.ajax({
            url:"<?php echo Yii::$app->request->baseUrl . '/gestionriesgo/ayuda'; ?>",
            type:'POST',
            dataType:"json",
            cache:false,
            data: {
                id: id,
                
            },
            beforeSend:  function() {
                $('#body_ayuda').html('Cambiando... <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>');
            },
            success: function(data){
                //alert(data.respuesta);
                if (data.respuesta==null) {
                	$("#body_ayuda").html("<h3>No tiene ayuda asignada</h3>");	
                }else{
                	$("#body_ayuda").html(data.respuesta);
                }
            }
        });

	}

	function ayuda_resp(id,titulo){

		$("#ayuda_header_resp").html(titulo);
		$.ajax({
            url:"<?php echo Yii::$app->request->baseUrl . '/gestionriesgo/ayuda_resp'; ?>",
            type:'POST',
            dataType:"json",
            cache:false,
            data: {
                id: id,
                
            },
            beforeSend:  function() {
                $('#body_ayuda_resp').html('Cambiando... <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>');
            },
            success: function(data){
                	//console.log(data);
                	var resp1=data.respuesta1==null?'Sin texto asignado':data.respuesta1;
                	var resp2=data.respuesta2==null?'Sin texto asignado':data.respuesta2;
                	var resp3=data.respuesta3==null?'Sin texto asignado':data.respuesta3;

                	$("#body_ayuda_resp").html(
                		//'<div class="col-md-12">'+
                		'<table class="table table-striped table-bordered">'+
                		'<thead>'+
                		'<tr>'+
                		'<th>Cumple</th>'+
                		'<th>No Cumple</th>'+
                		'<th>En Proceso</th>'+
                		'</tr>'+
                		'</thead>'+
                		'<tbody>'+
                		'<tr>'+
                		'<td>'+resp1+'</td>'+
                		'<td>'+resp2+'</td>'+
                		'<td>'+resp3+'</td>'+
                		'</tr>'+
                		'</tbody>'+
                		'</table>'
                		//+'</div>'
                		

                	);
                
            }
        });

	}

</script>