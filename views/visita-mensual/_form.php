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
use app\models\ValorNovedad;

date_default_timezone_set ( 'America/Bogota');
$fecha = date('Y-m-d');
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
?>

 <?php $form = ActiveForm::begin([

        'options'=>['enctype'=>'multipart/form-data'] // important


    ]); ?>

<div class="row">
	<div class="col-md-4">
        <?php


            echo DateControl::widget([
            'name'=>'fecha_inicial', 
            'type'=>DateControl::FORMAT_DATE,
            'autoWidget' => true,
            'value'=>$fecha_inicio,
            'displayFormat' => 'php:Y-m-d',
            'saveFormat' => 'php:Y-m-d',


             ]);
        ?>
        </div>

        <div class="col-md-4">
            <?php


                echo DateControl::widget([
                'name'=>'fecha_final', 
                'type'=>DateControl::FORMAT_DATE,
                'autoWidget' => true,
                'value'=>$fecha_final,
                'displayFormat' => 'php:Y-m-d',
                'saveFormat' => 'php:Y-m-d'

                 ]);
            ?>
        </div>

	<div class="col-md-4">
		<?=

	       $form->field($model, 'centro_costo_codigo')->widget(Select2::classname(), [
	       
		   'data' => $data_dependencias,
			'options' => [
			'id' => 'dependencia',
			'placeholder' => 'Dependencia',
										
		    ],
	    
	      ])->label(false);

		?>
	</div>
</div>

<button class="btn btn-primary"><i class="fa fa-search"></i> Consultar</button>


<br><br>
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingOne">
      <h4 class="panel-title">
        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          Visitas Quincenales
        </a>
      </h4>
    </div>
    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
      <div class="panel-body">
      	<?php if(isset($codigo_dependencia)) :?>
        	<!-- ////////////////////////////////// -->
        	<div class="row">
				<div class="col-md-6">
					<div id="container_bueno" style="height: 350px;"></div>		
				</div>

				<div class="col-md-6">
					<div id="container_negativo" style="height: 350px;"></div>		
				</div>
			</div>
			 
			 <div id="clon"></div>
			<!-- ******************************************************************* -->
			<div class="row">

				<div class="panel-group" id="accordionvisita" role="tablist" aria-multiselectable="true">
				    <?php 

				        $calif_ano=0;
				        foreach ($arr_meses as $key_mes => $value_mes) {
				        

				            $num_visita= $model_visita->Num_visitas($key_mes,$codigo_dependencia,$fecha_inicio,$fecha_final);

				            if ($num_visita==0) {

				                $calif=0;

				            }elseif($num_visita>=2){

				               $calif=100;

				            }elseif($num_visita<2){
				                
				                $calif=50;
				            }


				            $calif_mes=round(($calif*8.33)/100, 2, PHP_ROUND_HALF_DOWN);

				            $calif_ano+=$calif_mes;

				    ?>  

				<div class="col-md-6">
				  <div class="panel panel-default">
				    <div class="panel-heading" role="tab" id="headingOne">
				      <h4 class="panel-title">
				        <a role="button" data-toggle="collapse" data-parent="#accordionvisita" href="#collapse<?= $key_mes ?>" aria-expanded="true" aria-controls="collapseOne">
				       
				          <i class="fa fa-calendar"></i> <?= $value_mes." <span class='text-danger'> ".$calif."% </span>"?> 
				        </a>
				      </h4>
				    </div>
				    <div id="collapse<?= $key_mes ?>" class="panel-collapse collapse " role="tabpanel" aria-labelledby="headingOne">
				      <div class="panel-body">
				        <table class="table table-striped">
				        <thead>
				            <tr>
				                <th>Fecha</th>
				                <th>Calif %</th>
				            </tr>
				        </thead>
				        
				        <tbody>
				        <?php 
				            $visitas_ano=$model_visita->Visitas($key_mes,$codigo_dependencia,$fecha_inicio,$fecha_final);

				            $cont_visita=0;

				            foreach ($visitas_ano as $key_visita => $value_visita) {
				                               
				        ?>
				        <tr>
				            <td><a href="<?= Yii::$app->request->baseUrl.'/visita-dia/view?id='.$value_visita->id.'&dependencia='.$codigo_dependencia ?>"><?= $value_visita->fecha ?></a></td>
				            <td>
				                <?php 

				                    $det_visita=$model_visita->Detalle_visitas($value_visita->id);

				                    $porcentaje=0;
				                    foreach ($det_visita as $value_detalle) {
				                        $valor_calif=ValorNovedad::porcentaje($value_detalle->novedad->id,$value_detalle->resultado->id);
				                        $porcentaje+=$valor_calif;
				                    }

				                    echo $porcentaje."%";
				                ?>
				            </td>
				        </tr>

				        <?php
				        $cont_visita++;
				        }
				        ?>
				        </tbody>

				        </table>

				        <?php if($cont_visita>=2): ?>

				            <div class="alert alert-success" role="alert"><i class="fa fa-thumbs-o-up"></i> Cumple</div>

				        <?php else: ?>
				            <div class="alert alert-danger" role="alert"><i class="fa  fa-thumbs-o-down"></i> No Cumple</div>
				        <?php endif; ?>

				      </div>
				    </div>
				  </div>
				</div>
				  <?php
				    }
				   ?>  

				</div>
				</div>

			<h3>Calif Anual : <span class="text-danger"><?= $calif_ano."%"?></span></h3>
			<!-- ******************************************************************* -->
		<?php else: ?>
		<div class="alert alert-info" role="alert">Debe realizar el filtro para ver informacion</div>
        	<!-- ////////////////////////////////// -->
        <?php endif;?>
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingTwo">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          Capacitaciones
        </a>
      </h4>
    </div>
    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
      <div class="panel-body">
        <!-- *********************************** -->
        <?php if(isset($codigo_dependencia)) :?>
        <div class="row">
	    	<div class="col-md-12">
	    		<div id="container"></div>
	    	</div>
    	</div>

    	<!-- *************************************** -->
    	 <div class="row">
	        <div class="col-md-12">
	            <table class="table table-striped ">
	                <thead>
	                    <tr>
	                        <th class="text-center">Novedad</th>
	                        <th class="text-center">Personas capacitadas</th>
	                        <th class="text-center">Capacitaciones Realizadas</th>
	                    </tr>
	                </thead>
	                <tbody class="text-center">

	                    <?php 
	                        $i=0; 
	                        $totalPersonas=0;
	                        $totalCapacitaciones=0;
	                        foreach($capacitaciones_tema as $cpt): 
	                    ?>
	                    <tr>
	                        <td><?= $cpt['name'].":" ?></td>
	                        <td><?= $cpt['y'] ?></td>
	                        <td><?= $cpt['capacitaciones'] ?></td>
	                    </tr>
	                    <?php 
	                        $i++; 
	                        $totalPersonas+=$cpt['y'];
	                        $totalCapacitaciones+=$cpt['capacitaciones'];
	                        endforeach;
	                    ?>
	                    <tr>
	                        <th class="text-center">Total:</th>
	                        <td><?= $totalPersonas ?></td>
	                        <td><?= $totalCapacitaciones ?></td>
	                    </tr>
	                </tbody>
	            </table>
	        </div>
	    </div>
	    

	    <div class="row">
	        <div class="col-md-6">
	            <table class="table table-striped ">
	                <thead >
	                    <tr>
	                        <th colspan="3" class="text-center" >Primer Semestre</th>
	                    </tr>
	                    <tr>
	                        <th class="text-center">Novedad</th>
	                        <th class="text-center">Total Capacitaciones</th>
	                        <th class="text-center">Calif%</th>
	                    </tr>
	                </thead>
	                <tbody class="text-center">
	                    <?php 
	                        $capSemprimero=0;
	                        $retail_calif=0;
	                        $vigias_calif=0;
	                        foreach($array_semestre as $as): 
	                    ?>
	                        <tr>
	                            <td><?php echo $as['novedad']?></td>
	                            <td><?php echo $as['cantidad']?></td>
	                            <td><?php echo $as['calif']."%"?></td>
	                        </tr>
	                    <?php 
	                        if ($as['novedad']=='Seguridad-en-Retail') {
	                            $retail_calif+=$as['calif'];

	                        }elseif($as['novedad']=='Vigías-Protección-de-Recursos'){
	                            $vigias_calif+=$as['calif'];
	                        }

	                        $capSemprimero+=$as['calif'];
	                        endforeach; 

	                    ?>
	                </tbody>
	            </table>
	        </div>

	        <div class="col-md-6">
	            <table class="table table-striped ">
	                <thead >
	                    <tr>
	                        <th colspan="3" class="text-center">Segundo Semestre</th>
	                    </tr>
	                    <tr >
	                        <th class="text-center">Novedad</th>
	                        <th class="text-center">Total Capacitaciones</th>
	                        <th class="text-center">Calif%</th>
	                    </tr>
	                </thead>
	                <tbody class="text-center">
	                    <?php 
	                        $capSegundo=0;
	                        $retail_calif2=0;
	                        $vigias_calif2=0;
	                        foreach($array_semestre2 as $as2): 
	                    ?>
	                        <tr>
	                            <td><?php echo $as2['novedad']?></td>
	                            <td><?php echo $as2['cantidad']?></td>
	                            <td><?php echo $as2['calif']."%"?></td>
	                        </tr>
	                    <?php 
	                        if ($as2['novedad']=='Seguridad en Retail') {
	                            $retail_calif2+=$as2['calif'];

	                        }elseif($as2['novedad']=='Vigías Protección de Recursos'){
	                            $vigias_calif2+=$as2['calif'];
	                        }
	                        $capSegundo+=$as2['calif'];
	                        endforeach; 
	                    ?>
	                </tbody>
	            </table>
	        </div>
	    </div>

	    <?php 
	        $promedio_calif=($capSemprimero+$capSegundo)/4;
	        $promedio_retail=($retail_calif+$retail_calif2)/2;
	        $promedio_vigias=($vigias_calif+$vigias_calif2)/2;
	    ?>

	    
	    <h3>Seguridad en Retail: <span class="text-danger"><?= round($promedio_retail,2,PHP_ROUND_HALF_DOWN)."%" ?></span></h3>
	    <h3>Vigías Protección de Recursos: <span class="text-danger"><?= round($promedio_vigias,2,PHP_ROUND_HALF_DOWN)."%" ?></span></h3>
	    <h3> Porcentaje Consolidado de Capacitación: <span class="text-danger"><?= round($promedio_calif,2,PHP_ROUND_HALF_DOWN)."%" ?></span></h3>
        <!-- *********************************** -->
    <?php else: ?>
		<div class="alert alert-info" role="alert">Debe realizar el filtro para ver informacion</div>
    <?php endif;?>
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingThree">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          Pedidos
        </a>
      </h4>
    </div>
    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
      <div class="panel-body">
        <?php if(isset($codigo_dependencia)) :?>
        	<!-- ******************************* -->
        	<table  class="display my-data" data-page-length='20' cellspacing="0" width="100%">
	 
		       <thead>

		       <tr>
		           
		           <th>Texto Breve</th>
				   <th>Cantidad</th>
				   <th>OC/No.Solicitud</th>		   
				   <th>Fecha de Creación</th>
				   
		       </tr>
		           

		       </thead>	 
			   
			   <tbody>
			   
		             <?php foreach($pedidos as $pendiente):?>	  
					   
		              <tr>			   
		            	
						
						<td><?= $pendiente->producto->texto_breve?></td>
		     			<td><?= $pendiente->cantidad?></td>
						<td><?= $pendiente->orden_compra?></td>
						<td><?= $pendiente->pedido->fecha?></td>
						
						
						
		              </tr>
		        	 <?php endforeach; ?>			 
			   
			   </tbody>
			 
			 </table>
        	<!-- ******************************* -->
        <?php else: ?>
			<div class="alert alert-info" role="alert">Debe realizar el filtro para ver informacion</div>
    	<?php endif;?>
      </div>
    </div>
  </div>
</div>

<?php ActiveForm::end(); ?>
