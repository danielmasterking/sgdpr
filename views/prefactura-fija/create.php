<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\widgets\Select2;
$this->title = 'Crear Pre-factura';
date_default_timezone_set ('America/Bogota');
//$year = date('Y');
$year = '2018';
$regionales = array();
$ciudades_zonas = array();//almacena las regionales permitidas al usuario

foreach($zonasUsuario as $zona){
	$ciudades_zonas [] = $zona->zona->ciudades;
}
$ciudades_permitidas = array();
$ciudades_zonas_permitidas = array();//guarda solo la regional y la ciudad para filtrar por javascript
foreach($ciudades_zonas as $ciudades){
	foreach($ciudades as $ciudad){
		foreach($zonas as $z){
			if($z->id==$ciudad->zona_id){
				$regionales[$z->id] = $z->nombre;break;
			}
		}
		$ciudades_permitidas [] = $ciudad->ciudad->codigo_dane;
		$ciudades_zonas_permitidas [] = array('zona' => $ciudad->zona_id, 'nombre' => $ciudad->ciudad->nombre, 'codigo' => $ciudad->ciudad->codigo_dane);
	}
}
$marcas_permitidas = array();
$marcas = array();
foreach($marcasUsuario as $marca){
	$marcas_permitidas [] = $marca->marca_id;
	$marcas[$marca->marca->nombre] = $marca->marca->nombre;
}
$empresas_permitidas = array();
foreach($empresasUsuario as $empresa){
	$empresas_permitidas [] = $empresa->nit;
}

$data_dependencias = array();
foreach($dependencias as $dependencia){
	if(in_array($dependencia->ciudad_codigo_dane,$ciudades_permitidas) ){
		if(in_array($dependencia->marca_id,$marcas_permitidas) ){
			if(in_array($dependencia->empresa,$empresas_permitidas) ){
				$data_dependencias[] = array('codigo' => $dependencia->codigo, 'nombre' => $dependencia->nombre, 'codigo_ciudad' => $dependencia->ciudad_codigo_dane, 'codigo_marca' => $dependencia->marca_id);
			}
		}
	}
}
//var_dump($data_dependencias);
?>
<h1 style="text-align: center;"><?= Html::encode($this->title) ?></h1>
<?= Html::a('<i class="fa fa-arrow-left"></i> Volver a Pre-facturas',Yii::$app->request->baseUrl.'/prefactura-fija/index', ['class'=>'btn btn-primary']) ?>
<?php if(isset($mensaje)){?>
	<div class="alert alert-warning alert-dismissable">
	  	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	  	<strong>Advertencia!</strong> <?=$mensaje?>
	</div>
<?php } ?>
<?php $form = ActiveForm::begin(); ?>
<br>
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
<div class="row">
	<div class="col-md-6">
		<?= $form->field($model, 'regional')->widget(Select2::classname(), [
			'data' => $regionales,
			'options' => ['placeholder' => 'Selecccione Regional'],
		])?>
	</div>
	<div class="col-md-6">
		<?= $form->field($model, 'centro_costo_codigo')->widget(Select2::classname(), [
			'data' => $data_dependencias,
			'options' => ['placeholder' => 'Selecccione Dependencia'],
		])?>
	</div>

	<input type="hidden" name="nombre_factura" id="nombre_factura" value="No Aplica">
</div>
<?php //echo Html::submitButton('Generar', ['class' => 'btn btn-primary']) ?>

<button type="button" class="btn btn-primary" onclick="/*grupos();*/existe_factura();"> Generar</button>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Selecciona grupos a prefacturar</h4>
      </div>
      <div class="modal-body" id="body_ayuda">
         
      		<ul class="list-group">
			  <li class="list-group-item"><input type="checkbox" name=""> Cras justo odio</li>
			  <li class="list-group-item"><input type="checkbox" name=""> Dapibus ac facilisis in</li>
			  <li class="list-group-item"><input type="checkbox" name=""> Morbi leo risus</li>
			  <li class="list-group-item"><input type="checkbox" name=""> Porta ac consectetur ac</li>
			  <li class="list-group-item"><input type="checkbox" name=""> Vestibulum at eros</li>
			</ul>




      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" onclick="enviar();">Crear</button>
      </div>
    </div>
  </div>
</div>

<?php ActiveForm::end(); ?>
<script>
	var ciudad_zona = JSON.parse('<?php echo json_encode($ciudades_zonas_permitidas);?>');
	var dependencias = JSON.parse('<?php echo json_encode($data_dependencias);?>');
	$('#prefacturafija-centro_costo_codigo').empty();
	$('#prefacturafija-regional').val();
	$("#prefacturafija-regional").on('change',function(){
		var region= $(this).val();
		var depencias=[];
		//primero buscar en servicios el id del seleccionado para saber el servicio_id
        for ( var index=0; index < ciudad_zona.length; index++ ) {
            if(ciudad_zona[index]['zona']==region){//elige la ciudad
            	for ( var i=0; i < dependencias.length; i++ ) {
		            if(dependencias[i]['codigo_ciudad']==ciudad_zona[index]['codigo']){//comprara ciduad con ciudad dependencia
		            	depencias.push( dependencias[i] );
		            }
            	}
        	}
	    }
	    $('#prefacturafija-centro_costo_codigo').empty();
        for ( var i=0; i < depencias.length; i++ ) {
            $('#prefacturafija-centro_costo_codigo').append($('<option>', {
			    value: depencias[i]['codigo'],
			    text: depencias[i]['nombre']
			}));
        }
   	});


   	// $('#w0').submit(function(event) {
   		
   	// 	var pasa=true;

   	// 	$.ajax({
    //         url:"<?php //echo Yii::$app->request->baseUrl . '/prefactura-fija/existe_factura'; ?>",
    //         type:'POST',
    //         dataType:"json",
    //         cache:false,
    //         async: false,
    //         data: {
    //             mes: $('#prefacturafija-mes option:selected').val(),
    //             ano: $('#prefacturafija-ano').val(),
    //             dependencia: $('#prefacturafija-centro_costo_codigo option:selected').val()
                
    //         },
    //         beforeSend:  function() {
    //             $('#body_ayuda').html('Cambiando... <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>');
    //         },
    //         success: function(data){
    //             //alert(data.respuesta);
    //             if (data.respuesta==1) {
    //             	var confirmar=confirm('Ya existe una prefactura creada para este tiempo desea crear otra prefactura?');

    //             	if (confirmar) {
    //     				var nombre = prompt("Nombre de la prefactura", "");  

    //     				if (nombre && nombre!='') {

    //     					$('#nombre_factura').val(nombre);
        				    
    //     				}else{

    //     					alert('El nombre de la prefactura es obligatorio');
    //     					pasa=false;
        					
    //     				}

        				      		


    //             	}else{

    //             		pasa= false;
    //             	}		
    //             }
    //         }
    //     });

    //     return pasa;
   		
   	// });

    function grupos(){

    	var dep= $('#prefacturafija-centro_costo_codigo option:selected').val();

    	if(dep!=undefined){
		    	var confirmar=confirm('Desea seleccionar un grupo? (En caso de no seleccionar un grupo o seleccionar cancelar se crearan todos los dispositivos en una sola prefactura!)');


		    	if (confirmar) {

		    		$('#myModal').modal('show');
		    		$.ajax({
		            url:"<?php echo Yii::$app->request->baseUrl . '/prefactura-fija/grupos_prefactura'; ?>",
		            type:'POST',
		            dataType:"json",
		            cache:false,
		            async: false,
		            data: {
		                
		                dependencia:dep
		                
		            },
		            beforeSend:  function() {
		                $('#body_ayuda').html('Cambiando... <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>');
		            },
		            success: function(data){

		               $('#body_ayuda').html(data.respuesta);
		               
		            }
		        });




		    		//$('#myModal').modal('show');
		    	}else{

		    		$('#w0').submit();
		    	}
		    	

		    
		}else{

			alert('Selecciona una dependencia');
		}
	}


	function enviar(){

		var checkbox=0;
		var dep= $('#prefacturafija-centro_costo_codigo option:selected').val();

		$('input[type=checkbox]:checked').each(function() {
            checkbox=checkbox+1;
        });

        if (checkbox==0) {

        	alert('Selecciona un grupo');

        }else if(dep==undefined){

        	alert('Selecciona una dependencia');

        }else{

        	$('#w0').submit();
        }
	}


	function existe_factura(){

		//var pasa=true;

   		$.ajax({
            url:"<?php echo Yii::$app->request->baseUrl . '/prefactura-fija/existe_factura'; ?>",
            type:'POST',
            dataType:"json",
            cache:false,
            async: false,
            data: {
                mes: $('#prefacturafija-mes option:selected').val(),
                ano: $('#prefacturafija-ano').val(),
                dependencia: $('#prefacturafija-centro_costo_codigo option:selected').val()
                
            },
            beforeSend:  function() {
                $('#body_ayuda').html('Cambiando... <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>');
            },
            success: function(data){
                //alert(data.respuesta);
                if (data.respuesta==1) {
                	var confirmar=confirm('Ya existe una prefactura creada para este tiempo desea crear otra prefactura?');

                	if (confirmar) {
        				var nombre = prompt("Nombre de la prefactura", "");  

        				if (nombre && nombre!='') {

        					$('#nombre_factura').val(nombre);

        					grupos();
        				    
        				}else{

        					
        					if(nombre==''){
        						alert('El nombre de la prefactura es obligatorio');	
        					}else{

        						alert('Cancelado');	
        					}
        					
        					//pasa=false;
        					
        				}

        				      		


                	}else{

                		
                		//pasa= false;
                	}		
                }else{
                	grupos();
                	
                }
            }
        });

        //return pasa;
   		


	}

</script>