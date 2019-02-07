<?php
use app\models\CapacitacionFoto; 
$capacitacion_instructores = $model->capacitacionInstructor;

$instructores = array();


foreach($capacitacion_instructores as $key){
	
	$instructores [] = $key->instructor;
	
}

$capacitacionDependencias = $model->capacitacionDependencias;
$dependencias = array();

if($capacitacionDependencias != null){
	
	foreach($capacitacionDependencias as $key){
		
		$dependencias [] = array('nombre' => $key->dependencia->nombre, 'cantidad' => $key->cantidad);
		
	}
	
}

?>
<h3 style="text-align: center;">Capacitacion-<?= $model->novedad->nombre?></h3>

<table class="table table-bordered">
	<tr>
		<th>Fecha de creación:</th>
		<td><?php echo $model->fecha;?></td>
		<th>Fecha de capacitación:</th>
		<td><?php echo $model->fecha_capacitacion;?></td>
	</tr>
	<tr>
		<th>Creada por:</th>
		<td><?php echo $model->usuario;?></td>
		<th>Instructor:</th>
		<td><?php echo $instructores[0];?></td>
	</tr>
	<!-- <tr>
		<th>Tema:</th>
		<td colspan="3"><?php //echo $model->novedad->nombre;?></td>
	</tr> -->

	<tr>
		<th>Observaciones:</th>
		<td colspan="3"><?php echo $model->observaciones;?></td>
	</tr>
	
</table>

<h3 style="text-align: center;">Dependencias</h3>

<table class="table table-bordered">
	         
 <thead>
    
	<tr>
	  
	  <td>Nombre</td>
	  
	  <td>Cantidad</td>
	
	</tr>
 
 </thead>
			 
 <tbody>
 
  <?php if($dependencias != null):?>
	<?php foreach($dependencias as $key):?>
	
	   <tr>
	   
	     <td><?=$key['nombre']?></td>
				 
		 <td><?=$key['cantidad']?></td>
	   
	   </tr>
	    
	<?php endforeach;?>
  <?php endif;?>
    

 </tbody>
			 
</table>

       <div class="row">
	   <?php 
	  		$fotos=CapacitacionFoto::fotos($model->id);

	  		if($fotos!=null):

	  		foreach($fotos as $ft):
	  	?>
	  	    <div class="col-lg-3 col-md-4 col-xs-6 thumb">
                <a class="thumbnail" href="#" data-image-id="" data-toggle="modal" data-title=""
                   data-image="<?php echo Yii::$app->request->baseUrl.$ft->archivo?>"
                   data-target="#image-gallery">
                    <img class="img-thumbnail"
                         src="<?php echo Yii::$app->request->baseUrl.$ft->archivo?>"
                         alt="Foto">
                </a>
            </div>
	  	
	  	<?php 

	  		endforeach;
	  		endif;
	  	?>
	  	</div>
<?php
		 
 /**********************Rendering Image *******************************/
  if($model->foto != null && $model->foto != ''){
	  
	  ?>
	  <h4 style="text-align: center;"><strong>Registro Fotografico</strong></h4>
	  <img src="<?php echo Yii::$app->request->baseUrl.$model->foto; ?>">  
	  
	  <?php
	  
  }
  
  ?>

  <?php
		 
		 /**********************Rendering Image *******************************/
		  if($model->lista != null && $model->lista != ''){
			  
			  /**Validar imagen o pdf o xls*/
			 if((strpos($model->lista, 'pdf') === false && strpos($model->lista, 'xls') === false && strpos($model->lista, 'xlsx') === false ) ){
				  
			  
			  ?>
			  <h4 style="text-align: center;"><strong>Acta de asistencia</strong></h4>
			  <p>&nbsp;</p>
			   <img src="<?php echo Yii::$app->request->baseUrl.$model->lista; ?>">  
			  
			  <?php
			  }			  
			  ?>
			  
			  
			  
			  <?php
			  
		  }
			  
			  
		 ?>