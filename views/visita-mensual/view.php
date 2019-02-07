<script src="https://code.highcharts.com/highcharts.src.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<?php 
use yii\helpers\Html;
use kartik\datecontrol\DateControl;
use yii\widgets\ActiveForm;
use app\models\ValorNovedad;
use app\models\AdjuntoVisitaDetalle;
use app\models\AdjuntoNovedadCapacitacion;
use app\models\AdjuntoNovedadPedido;

$this->title = 'Visita Semestral-'.$model->dependencia->nombre;
$permisos = array();
if( isset(Yii::$app->session['permisos-exito']) ){
$permisos = Yii::$app->session['permisos-exito'];
}

?>
<?= Html::a('<i class="fa fa-arrow-left"></i> ',Yii::$app->request->baseUrl.'/visita-mensual/index', ['class'=>'btn btn-primary']) ?>
<!-- <a href="javascript:window.history.go(-1);" class="btn btn-primary"><i class="fa fa-arrow-left"></i></a> -->

<?php if(in_array("administrador", $permisos) || $model->usuario==Yii::$app->session['usuario-exito']):?>
<?php if($model->estado=='abierta'): ?>
<?= Html::a('<i class="fa fa-plus"></i> gestión novedad',Yii::$app->request->baseUrl.'/visita-mensual/create-novedad?id='.$model->id.'&dependencia='.$model->centro_costo_codigo,['class'=>'btn btn-primary']) ?>


<?//= Html::a('<i class="fa  fa-expeditedssl"></i> Cerrar Visita',Yii::$app->request->baseUrl.'/visita-mensual/cerrar-visita?id='.$model->id.'&dependencia='.$model->centro_costo_codigo,['class'=>'btn btn-danger','data-confirm'=>'Seguro desea cerrar esta visita']) ?>

<button onclick="cerrar_visita();" class="btn btn-danger">
	<i class="fab fa-expeditedssl"></i> Cerrar Visita
</button>
<?php endif; ?>
<?php endif; ?>

<?php if($model->estado=='cerrado'): ?>
<button class="btn btn-danger" onclick="pdf();">
	<i class="far fa-file-pdf"></i> PDF
</button>

<?= Html::a('<i class="fa  fa-folder-open"></i> Abrir Visita',Yii::$app->request->baseUrl.'/visita-mensual/abrir-visita?id='.$model->id.'&dependencia='.$model->centro_costo_codigo,['class'=>'btn btn-success','data-confirm'=>'Seguro desea abrir esta visita']) ?>


<?php endif; ?>
<h1 class="text-center"><?= Html::encode($this->title)?></h1>

<?php 

    $flashMessages = Yii::$app->session->getAllFlashes();
    if ($flashMessages) {
        foreach($flashMessages as $key => $message) {
            echo "<div class='alert alert-" . $key . " alert-dismissible' role='alert'>
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                    $message
                </div>";   
        }
    }
?>

<div class="col-md-12">
<table class="table table-striped">
	
	<tr>
		
		<th>Fecha Creado:</th>
		<td><?= $model->fecha?></td>
		<td rowspan="6" class="text-center">
			<?php 
				$ruta = $model->dependencia->foto == null ? ' ' : $model->dependencia->foto;
		        $ruta = Yii::$app->request->baseUrl.$ruta; 


		    ?> 
			<img src="<?= $ruta ?>" class="img-responsive img-thumbnail" style='height:200px;width: 400px'>
		</td>
	</tr>
	<tr>
		<th>Fecha inicio Visita:</th>
		<td><?= $model->fecha_visita?></td>
	</tr>
	<tr>
		<th>Usuario:</th>
		<td><?= $model->usuario?></td>
	</tr>
	<tr>
		<th>Atendio:</th>
		<td><?= $model->atendio?></td>
	</tr>
	<tr>
		<th>Otro:</th>
		<td><?= $model->otro?></td>
	</tr>

	<tr>
		<th>Semestre:</th>
		<td><?= $model->semestre?></td>
	</tr>
</table>
</div>

<h1 class="text-center">Estadisticas</h1>

<?php $form = ActiveForm::begin([

        'options'=>['enctype'=>'multipart/form-data'] // important


    ]); ?>

<div class="row ">
	<div class="col-md-4 col-md-offset-2">
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

        <div class="col-md-4 ">
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

        <button class="btn btn-primary"> <i class="fa fa-search"></i> Consultar</button>
</div>

<?php ActiveForm::end(); ?>
<br><br>
<!-- /////////////////////////////////// -->
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingOne">
      <h4 class="panel-title">
        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          <i class="fas fa-chart-pie"></i> Visitas Quincenales
        </a>
      </h4>
    </div>
    <div id="collapseOne" class="panel-collapse collapse " role="tabpanel" aria-labelledby="headingOne">
      <div class="panel-body">
      	<?php if(isset($codigo_dependencia)) :?>
        	<!-- ////////////////////////////////// -->
        	<div class="row">
				<div class="col-md-6">
					<div id="container_bueno" style="height: 350px;" class="graficos"></div>		
				</div>

				<div class="col-md-6">
					<div id="container_negativo" style="height: 350px;" ></div>		
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
          <i class="fas fa-chart-pie"></i> Capacitaciones
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
	        	<div class="table-responsive">
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
	                        if ($as2['novedad']=='Seguridad-en-Retail') {
	                            $retail_calif2+=$as2['calif'];

	                        }elseif($as2['novedad']=='Vigías-Protección-de-Recursos'){
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
          <i class="fas fa-bars"></i> Pedidos
        </a>
      </h4>
    </div>
    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
      <div class="panel-body">
        <?php if(isset($codigo_dependencia)) :?>
        	<!-- ******************************* -->
        	<div class="table-responsive">
        	<table  class="display my-data" data-page-length='20' cellspacing="0" width="100%">
	 
		       <thead>

		       <tr>
		           
		           <th>Texto Breve</th>
				   <th>Cantidad</th>
				   <th>OC/No.Solicitud</th>		   
				   <th>Fecha de Creación</th>
				   <th>Tipo</th>
				   
		       </tr>
		           

		       </thead>	 
			   
			   <tbody>
			   
		             <?php foreach($pedidos as $pendiente):?>	  
					   
		              <tr>			   
		            	
						
						<td><?= $pendiente->producto->texto_breve?></td>
		     			<td><?= $pendiente->cantidad?></td>
						<td><?= $pendiente->orden_compra?></td>
						<td><?= $pendiente->pedido->fecha?></td>
						<td>Normales</td>
							
						
		              </tr>
		        	 <?php endforeach; ?>	

		        	<?php foreach($pedidos_especial as $pendiente_es):?>
		        	<td><?= $pendiente_es->maestra->texto_breve?></td>
		        	<td><?= $pendiente_es->cantidad?></td>
		        	<td><?= $pendiente_es->orden_compra?></td>
		        	<td><?= $pendiente_es->pedido->fecha?></td>
		        	<td>Especiales</td>
		        	 <?php endforeach; ?>
			   
			   </tbody>
			 
			 </table>
			 </div>
        	<!-- ******************************* -->
        <?php else: ?>
			<div class="alert alert-info" role="alert">Debe realizar el filtro para ver informacion</div>
    	<?php endif;?>
      </div>
    </div>
  </div>
</div>


<?php //if($model->semestre==2): ?>
	<h1 class="text-center">Planes de accion</h1>

	<div class="col-md-12">
		<div class="table-responsive">
		<table class="table table-striped " id="tbl_planes">
			<thead>
				<tr>
					<th style="text-align: center;">Tipo</th>
					<th style="text-align: center;">Fecha</th>
					<th style="text-align: center;">Plan de accion</th>
					<th style="text-align: center;">Cumplio</th>
					<th style="width: 500px;text-align: center;" >Observaciones</th>
					<th>Guardar</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($planes_de_accion as $pl): ?>
					<form method="POST">
					<tr>
						<td style="text-align: center;">
							<?= $pl->tipo ?>
								
					    </td>
						<td style="text-align: center;">
							<input type="hidden" name="id_novedad" value="<?= $pl->id ?>">
							<!-- <input type="hidden" name="tipo_novedad" value="visita"> -->
							<?= $pl->fecha?>
								
					    </td>
						<td style="text-align: center;"><?= $pl->plan_de_accion ?></td>
						<td style="text-align: center;" class="cumplimiento">
							<label class="radio-inline">
							  <input type="radio" name="cumplimiento" id="inlineRadio1" value="S" <?= $pl->cumplimiento=='S'?'checked':'' ?> > Si
							</label><br>
							<label class="radio-inline">
							  <input type="radio" name="cumplimiento" id="inlineRadio2" value="N" <?= $pl->cumplimiento=='N'?'checked':'' ?>>No
							</label>
						</td>
						<td style="width: 500px;text-align: center;">
							<textarea class="form-control" name="observacion" rows="5"  cols="5"><?= $pl->observacion?></textarea>
						</td>
						<td style="text-align: center;">
							<?php if($model->estado=='abierta'): ?>
							<button class="btn btn-primary "><i class="fa fa-save"></i></button>
							<?php endif; ?>
						</td>
					</tr>
					</form>
				<?php  endforeach;?>

				
			</tbody>
		</table>
		</div>
	</div>
<?php // endif;?>

<h1 class="text-center">Novedades</h1>


<!-- ********************************************* -->
<div class="col-md-12">
	<div class="table-responsive">
	<table class="table table-striped my-data">
		<thead>
			<tr>
				<th>
					
				</th>
				<th>Tipo</th>
				<th>Categoria</th>
				<th>Novedad</th>
				<th>Usuario</th>
				<th>Fecha Novedad</th>
				<th>Descripcion</th>
				<th>Cumplimiento</th>
				<th>Plan de accion</th>
				<th>Observacion</th>
				<th>Adjuntos</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($NovedadesMensual as $nv): ?>
				<tr>
					<td>

						<!-- <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#myModal" onclick="InfoNovedad(<?= $nv->id ?>);" >
						  <i class="fa fa-eye"></i>
						</button> -->
						<?php if($model->estado=='abierta'): ?>
						<a href="<?= Yii::$app->request->baseUrl.'/visita-mensual/delete-novedad?id='.$nv->id.'&visita='.$id_visita.'&dependencia='.$model->centro_costo_codigo?>" class="btn btn-danger btn-xs" data-confirm='Desea eliminar esta novedad?'><i class="fa fa-trash"></i></a>
						

						<?php if( $nv->cumplimiento!='S' && $nv->cumplimiento!='N' && $nv->aplica_plan=='S'): ?>
						<button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#myModal1" onclick="cumplimiento(<?=$nv->id ?>,'visita')" title="Confirmar Cumplimiento">
					  		<i class="fa fa-check"></i>
						</button>
						<?php endif;?>

						<?php endif;?>
					</td>
					<td>Visita</td>
					<td><?= $nv->categoria->nombre?></td>
					<td><?= $nv->novedad->nombre?></td>
					<td><?= $nv->usuario?></td>
					<td><?= $nv->fecha_novedad?></td>
					<td><?= $nv->descripcion?></td>
					<td>
					<?php
						switch ($nv->cumplimiento) {
						 	case 'S':
						 		echo "Si";
						 	break;
						 	
						 	case 'N':
						 		echo "No";
						 	break;

						 	default:
						 		
						 	break;
						 } 
						
					?>
						
					</td>
					<td><?= $nv->plan_de_accion?></td>
					<td><?= $nv->observacion?></td>
					<td >
						<?php 
						$adjuntos=AdjuntoVisitaDetalle::Documentos($nv->id);
						foreach($adjuntos as $adj):
							$nombre_archivo=str_replace('/uploads/VisitaMensual/','',$adj->archivo);

            				$extension=explode('.',$nombre_archivo);


            				if($extension[1]=='jpg' or $extension[1]=='JPG' or $extension[1]=='png' or $extension[1]=='PNG' or $extension[1]=='gif' or $extension[1]=='GIF'  ):
						?>	
						<a title="Click para ver imagen completa" data-toggle="modal" data-target="#myModal" onclick="cargar_imagen('<?= Yii::$app->request->baseUrl.$adj->archivo ?>');"><img src="<?= Yii::$app->request->baseUrl.$adj->archivo ?>" class="img-responsive img-thumbnail"  style='height:50px;width: 70px'></a><br>

						<?php else: ?>
						<a title="<?= $nombre_archivo?>"  href="<?= Yii::$app->request->baseUrl.$adj->archivo ?>" download=""><i class="fa fa-file-archive-o fa-2x"></i> </a><br>
						<?php endif; ?>

						<?php endforeach;?>
					</td>
				</tr>
			<?php endforeach;?>
			<?php foreach($NovedadesCapacitacion as $nvc): ?>
				<tr>
					<td>

						<!-- <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#myModal" onclick="InfoNovedadCapacitacion(<?= $nvc->id ?>);">
						  <i class="fa fa-eye"></i>
						</button> -->
						<?php if($model->estado=='abierta'): ?>
						<a href="<?= Yii::$app->request->baseUrl.'/visita-mensual/delete-novedad-capacitacion?id='.$nvc->id.'&visita='.$id_visita.'&dependencia='.$model->centro_costo_codigo?>" class="btn btn-danger btn-xs" data-confirm='Desea eliminar esta novedad?'><i class="fa fa-trash"></i></a>
						

						<?php if( $nvc->cumplimiento!='S' && $nvc->cumplimiento!='N' && $nvc->aplica_plan=='S'): ?>
						<button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#myModal1" onclick="cumplimiento(<?=$nvc->id ?>,'capacitacion')" title="Confirmar Cumplimiento">
					  		<i class="fa fa-check"></i>
						</button>
						<?php endif;?>
					    <?php endif;?>

					</td>
					<td>Capacitacion</td>
					<td><?= $nvc->novedad->nombre?></td>
					<td></td>
					<td><?= $nvc->usuario?></td>
					<td><?= $nvc->fecha_novedad?></td>
					<td><?= $nvc->descripcion?></td>
					<td>
					<?php
						switch ($nvc->cumplimiento) {
						 	case 'S':
						 		echo "Si";
						 	break;
						 	
						 	case 'N':
						 		echo "No";
						 	break;

						 	default:
						 		
						 	break;
						 } 
						
					?>
						
					</td>
					<td><?= $nvc->plan_de_accion?></td>
					<td><?= $nvc->observacion?></td>
					<td>
						<?php 
						$adjuntos=AdjuntoNovedadCapacitacion::Documentos($nvc->id);
						foreach($adjuntos as $adj):
							$nombre_archivo=str_replace('/uploads/novedad_capacitacion/','',$adj->archivo);

            				$extension=explode('.',$nombre_archivo);


            				if($extension[1]=='jpg' or $extension[1]=='JPG' or $extension[1]=='png' or $extension[1]=='PNG' or $extension[1]=='gif' or $extension[1]=='GIF'  ):
						?>	
						<a title="Click para ver imagen completa" data-toggle="modal" data-target="#myModal" onclick="cargar_imagen('<?= Yii::$app->request->baseUrl.$adj->archivo ?>');"><img src="<?= Yii::$app->request->baseUrl.$adj->archivo ?>" class="img-responsive img-thumbnail"  style='height:50px;width: 70px'></a><br>

						<?php else: ?>
						<a title="<?= $nombre_archivo?>"  href="<?= Yii::$app->request->baseUrl.$adj->archivo ?>" download=""><i class="fa fa-file-archive-o fa-2x"></i> </a><br>
						<?php endif; ?>

						<?php endforeach;?>

					</td>
				</tr>
			<?php endforeach;?>
			<?php foreach($NovedadPedido as $nvp): ?>
			<tr>
				<td>

					<!-- <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#myModal" onclick="InfoNovedadPedido(<?= $nvp->id ?>);">
					  <i class="fa fa-eye"></i>
					</button> -->
					<?php if($model->estado=='abierta'): ?>
					<a href="<?= Yii::$app->request->baseUrl.'/visita-mensual/delete-novedad-pedido?id='.$nvp->id.'&visita='.$id_visita.'&dependencia='.$model->centro_costo_codigo?>" class="btn btn-danger btn-xs" data-confirm='Desea eliminar esta novedad?'><i class="fa fa-trash"></i></a>
					

					<?php if( $nvp->cumplimiento!='S' && $nvp->cumplimiento!='N' && $nvp->aplica_plan=='S'): ?>
					<button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#myModal1" onclick="cumplimiento(<?=$nvp->id ?>,'pedido')" title="Confirmar Cumplimiento">
					  	<i class="fa fa-check"></i>
					</button>
					<?php endif;?>
				    <?php endif;?>
				</td>
				<td>Pedido</td>
				<td>-</td>
				<td>-</td>
				<td><?= $nvp->usuario?></td>
				<td><?= $nvp->fecha_novedad?></td>
				<td><?= $nvp->descripcion?></td>
				<td>
					<?php
						switch ($nvp->cumplimiento) {
						 	case 'S':
						 		echo "Si";
						 	break;
						 	
						 	case 'N':
						 		echo "No";
						 	break;

						 	default:
						 		
						 	break;
						 } 
						
					?>
						
					</td>
				<td><?= $nvp->plan_de_accion?></td>
				<td><?= $nvp->observacion?></td>
				<td>
					<?php 
						$adjuntos=AdjuntoNovedadPedido::Documentos($nvp->id);
						foreach($adjuntos as $adj):
							$nombre_archivo=str_replace('/uploads/novedad_pedido/','',$adj->archivo);

            				$extension=explode('.',$nombre_archivo);


            				if($extension[1]=='jpg' or $extension[1]=='JPG' or $extension[1]=='png' or $extension[1]=='PNG' or $extension[1]=='gif' or $extension[1]=='GIF'  ):
						?>	
						<a title="Click para ver imagen completa" data-toggle="modal" data-target="#myModal" onclick="cargar_imagen('<?= Yii::$app->request->baseUrl.$adj->archivo ?>');"><img src="<?= Yii::$app->request->baseUrl.$adj->archivo ?>" class="img-responsive img-thumbnail"  style='height:50px;width: 70px'></a><br>

						<?php else: ?>
						<a title="<?= $nombre_archivo?>"  href="<?= Yii::$app->request->baseUrl.$adj->archivo ?>" download=""><i class="fa fa-file-archive-o fa-2x"></i> </a><br>
						<?php endif; ?>

						<?php endforeach;?>
				</td>
			</tr>
		<?php endforeach;?>

		</tbody>
	</table>
	</div>
</div>
<!-- ********************************************* -->
	
<?= $this->render('_modal_cumplimiento', ['view'=>$model->id,'dependencia'=>$model->centro_costo_codigo]) ?>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        
      </div> 
      <div class="modal-body">
       <!-- *************** -->
	      <center><img  class="img-responsive img-thumbnail" style='height:500px;width: 700px' id="imagen_adjunto"></center>
       <!-- ***************** -->
      </div>
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> -->
    </div>
  </div>
</div>

<!-- ///////////////////////////////////// -->


<script type="text/javascript">
	$(function () {
  		$('[data-toggle="tooltip"]').tooltip();
  		/**************************************/
  		Highcharts.chart('container_bueno', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Estadistica general'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                }
            }
        }
    },
    series: [{
        name: 'Porcentaje',
        colorByPoint: true,
        data: <?= $json_bueno?>
    }]
});

/////////////////////////////////////////////////////////////////////////
Highcharts.theme = {
    colors: ['#c0392b','#f4d03f','#dc7633',' #52be80 '],
    chart: {
        backgroundColor: null,
        style: {
            fontFamily: 'Dosis, sans-serif'
        }
    },
    title: {
        style: {
            fontSize: '16px',
            fontWeight: 'bold',
            textTransform: 'uppercase'
        }
    },
    tooltip: {
        borderWidth: 0,
        backgroundColor: 'rgba(219,219,216,0.8)',
        shadow: false
    },
    legend: {
        itemStyle: {
            fontWeight: 'bold',
            fontSize: '13px'
        }
    },
    xAxis: {
        gridLineWidth: 1,
        labels: {
            style: {
                fontSize: '12px'
            }
        }
    },
    yAxis: {
        minorTickInterval: 'auto',
        title: {
            style: {
                textTransform: 'uppercase'
            }
        },
        labels: {
            style: {
                fontSize: '12px'
            }
        }
    },
    plotOptions: {
        candlestick: {
            lineColor: '#404048'
        }
    },


    // General
    background2: '#F0F0EA'

};

// Apply the theme
Highcharts.setOptions(Highcharts.theme);

Highcharts.chart('container_negativo', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Mayores Novedades generadas'
    },
    tooltip: {
        pointFormat: '{series.name}<br>: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                }
            }
        }
    },
    series: [{
        name: 'Porcentaje',
        colorByPoint: true,
        data:  <?= $json_negativo?>
    }]
});

Highcharts.chart('container', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: '%capacitaciones por tema'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {

        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                }
            }
        }
    },
    series: [{
        name: 'Brands',
        colorByPoint: true,
        data: <?= $torta ?>
    }]
});




  		/****************************************/

	});

	function InfoNovedad(id){


		$.ajax({
            url:"<?php echo Yii::$app->request->baseUrl . '/visita-mensual/info-novedad'; ?>",
            type:'POST',
            dataType:"json",
            cache:false,
            async:false,
            data: {
                id: id,
               
            },
            beforeSend:  function() {
            	
                $('#home').html('<i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>...');
            },
            success: function(data){
              
              	$('#home').html(data.adjuntos);
              	//$('#profile').html(data.plan);
            }
        });
	}

	function pdf(){
		var chart1 = $('#container_bueno').highcharts();
	    svg_visita1 = chart1.getSVG();   
	   // var base_image = new Image();
	    //svg_visita1 = "data:image/svg+xml,"+svg_visita1;
	    //base_image.src = svg;


	    var chart2 = $('#container_negativo').highcharts();
	    svg_visita2 = chart2.getSVG();   
	    //var base_image = new Image();
	    //svg_visita2 = "data:image/svg+xml,"+svg_visita2;
	    //base_image.src = svg;
	   // $('#mock').attr('src', svg);

	   var chart3 = $('#container').highcharts();
	    svg_capacitacion = chart3.getSVG();   
	    //var base_image = new Image();
	    //svg_capacitacion = "data:image/svg+xml,"+svg_capacitacion;
	    //base_image.src = svg;
	   // $('#mock').attr('src', svg);

	   $.ajax({
            url:"<?php echo Yii::$app->request->baseUrl . '/visita-mensual/guardar_grafico'; ?>",
            type:'POST',
            dataType:"json",
            cache:false,
            async:false,
            data: {
                id_visita:"<?= $model->id?>",
                imagen_visita1:svg_visita1,
                imagen_visita2:svg_visita2,
                imagen_capacitacion:svg_capacitacion
               
            },
            success: function(data){
              
              	
            }
        });
	   //location.href="<?php //echo Yii::$app->request->baseUrl . '/visita-mensual/pdf?id='.$model->id; ?>";
	   var ventimp=window.open("<?php echo Yii::$app->request->baseUrl . '/visita-mensual/pdf?id='.$model->id; ?>");
		//alert(ficha);
		//var ventimp=window.open("<?php //echo Yii::$app->request->baseUrl . '/visita-mensual/pdf?id='.$model->id.'&graf='; ?>"+svg);
		

		//document.write(ficha);

	}

	function InfoNovedadCapacitacion(id){
		$.ajax({
            url:"<?php echo Yii::$app->request->baseUrl . '/visita-mensual/info-novedad-capacitacion'; ?>",
            type:'POST',
            dataType:"json",
            cache:false,
            async:false,
            data: {
                id: id,
               
            },
            beforeSend:  function() {
            	
                $('#home').html('<i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>...');
            },
            success: function(data){
              
              	$('#home').html(data.adjuntos);
              	//$('#profile').html(data.plan);
            }
        });
	}


	function InfoNovedadPedido(id){
		$.ajax({
            url:"<?php echo Yii::$app->request->baseUrl . '/visita-mensual/info-novedad-pedido'; ?>",
            type:'POST',
            dataType:"json",
            cache:false,
            async:false,
            data: {
                id: id,
               
            },
            beforeSend:  function() {
            	
                $('#home').html('<i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>...');
            },
            success: function(data){
              
              	$('#home').html(data.adjuntos);
              	//$('#profile').html(data.plan);
            }
        });
	}

	function cerrar_visita(){
		/*var no_seleccionados=0;
		$("#tbl_planes tbody tr").each(function() {
          
           var seleccionados=$(this).find('td').eq(3).find('input[name=cumplimiento]:radio:checked').length;

           if (seleccionados==0) {
           	no_seleccionados++;
           }
           
        });

		if (no_seleccionados>0) {
			alert('Todos los planes de accion deben ser gestionados');
		}else{*/

			var confirmar=confirm('Seguro desea cerrar esta visita');

			if (confirmar) {
				location.href='<?= Yii::$app->request->baseUrl.'/visita-mensual/cerrar-visita?id='.$model->id.'&dependencia='.$model->centro_costo_codigo?>';
			}
		//}

	}

	function cargar_imagen(src){
		$('#imagen_adjunto').attr({
			src: src
			
		});
	}
</script>