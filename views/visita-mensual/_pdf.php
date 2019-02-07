
<?php 
use yii\helpers\Html;
use app\models\ValorNovedad;
use app\models\AdjuntoVisitaDetalle;
use app\models\AdjuntoNovedadCapacitacion;
use app\models\AdjuntoNovedadPedido;

$this->title = 'Visita Semestral-'.$model->dependencia->nombre;

$styletd='style="padding: 5px;text-align: center;font-size: 9px;border: 1px solid black;"';
$styletd2='style="padding: 5px;font-size: 10px;"';

?>


<h4 class="text-center"><?= Html::encode($this->title)?></h4>


<table class="table table-striped" >
	
	<tr>
		
		<th <?=$styletd2?>>Fecha Creado:</th>
		<td <?=$styletd2?>><?= $model->fecha?></td>
		<td rowspan="6" class="text-center" <?=$styletd2?>>
			<?php 
				$ruta = $model->dependencia->foto == null ? ' ' : $model->dependencia->foto;
		        $ruta = Yii::$app->request->baseUrl.$ruta; 


		    ?> 
			<img src="<?= $ruta ?>" class="img-responsive img-thumbnail" style='height:200px;width: 400px'>
		</td>
	</tr>
	<tr>
		<th <?=$styletd2?>>Fecha inicio Visita:</th>
		<td <?=$styletd2?>><?= $model->fecha_visita?></td>
	</tr>
	<tr>
		<th <?=$styletd2?>>Usuario:</th>
		<td <?=$styletd2?>><?= $model->usuario?></td>
	</tr>
	<tr>
		<th <?=$styletd2?>>Atendio:</th>
		<td <?=$styletd2?>><?= $model->atendio?></td>
	</tr>
	<tr>
		<th <?=$styletd2?>>Otro:</th>
		<td <?=$styletd2?>><?= $model->otro?></td>
	</tr>

	<tr>
		<th <?=$styletd2?>>Semestre:</th>
		<td <?=$styletd2?>><?= $model->semestre?></td>
	</tr>
</table>

<hr>
<h4 class="text-center">Visitas Quincenales</h4>

<table>
	<tr>
	<?php  
	foreach($grafico as $graf):
		
		if ($graf->tipo=='Visita') {
			echo "<td>". $graf->data."</td>";
		}
	endforeach;
	?>
	</tr>
</table>



<table class="" style="width: 100%;">
<thead>
	<tr style="height: 3px !important;">
		<th style="font-size: 10px;text-align: center;height: 3px !important;">Mes</th>
		<th style="font-size: 10px;text-align: center;height: 3px !important;">Calificacion</th>
		<th style="font-size: 10px;text-align: center;height: 3px !important;">Promedio</th>
		<th style="font-size: 10px;text-align: center;height: 3px !important;">Cantidad visitas</th>
	</tr>
</thead>
<tbody>
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


	<tr style="height: 3px !important;">
		<td style="font-size: 10px;text-align: center;height: 3px !important;"><?= $value_mes ?></td>
		<td style="font-size: 10px;text-align: center;height: 3px !important;"><?= " <span class='text-danger'> ".$calif."% </span>"?></td>
		<td style="font-size: 10px;text-align: center;height: 3px !important;">
			<?php 
				$visitas_ano=$model_visita->Visitas($key_mes,$codigo_dependencia,$fecha_inicio,$fecha_final);

				$cont_visita=0;
				$acum_porcentajes=0;
				foreach ($visitas_ano as $key_visita => $value_visita) {

					$det_visita=$model_visita->Detalle_visitas($value_visita->id);

                    $porcentaje=0;
                    foreach ($det_visita as $value_detalle) {
                        $valor_calif=ValorNovedad::porcentaje($value_detalle->novedad->id,$value_detalle->resultado->id);
                        $porcentaje+=$valor_calif;
                    }

                    $acum_porcentajes+= $porcentaje;
                    $cont_visita++;
				}
				

				$promedio_final=$cont_visita==0?0:round(($acum_porcentajes/$cont_visita), 2, PHP_ROUND_HALF_DOWN);

				echo $promedio_final."%";
		    ?>
		</td>
		<td style="font-size: 10px;text-align: center;height: 3px !important;">
			<?= $cont_visita?>
		</td>
	</tr>
<?php
    }
?> 
</tbody>

<tfoot>
	<tr>
		<th style="font-size: 10px;text-align: center;">Calif Anual : </th>
		<td style="font-size: 10px;text-align: center;" ><span class="text-danger"><?= $calif_ano."%"?></span></td>
		<td></td>
		<td></td>
	</tr>
</tfoot>

</table>


<hr>

<h4 class="text-center">Capacitaciones</h4>

	
	<?php  
	foreach($grafico as $graf):
		
		if ($graf->tipo=='Capacitacion') {
			echo  "<div style='height: 200px;'>".$graf->data."</div>";
		}
	endforeach;
	?>
	
        <table class="" style="font-size: 10px;">
            <thead>
                <tr>
                    <th class="text-center" <?=$styletd2?>>Novedad</th>
                    <th class="text-center" <?=$styletd2?>>Personas capacitadas</th>
                    <th class="text-center" <?=$styletd2?>>Capacitaciones Realizadas</th>
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
                    <td class="text-center" <?=$styletd2?>><?= $cpt['name'].":" ?></td>
                    <td class="text-center" <?=$styletd2?>><?= $cpt['y'] ?></td>
                    <td class="text-center" <?=$styletd2?>><?= $cpt['capacitaciones'] ?></td>
                </tr>
                <?php 
                    $i++; 
                    $totalPersonas+=$cpt['y'];
                    $totalCapacitaciones+=$cpt['capacitaciones'];
                    endforeach;
                ?>
                <tr>
                    <th class="text-center">Total:</th>
                    <td class="text-center"><?= $totalPersonas ?></td>
                    <td class="text-center"><?= $totalCapacitaciones ?></td>
                </tr>
            </tbody>
        </table>
	       
	    

	    <table>
	    	<tr>
	    		<td>
	    			<!-- ****************************************** -->
	    			<table class="table table-striped "  style="font-size: 10px;">
	                <thead >
	                    <tr>
	                        <th colspan="3" class="text-center" >Primer Semestre</th>
	                    </tr>
	                    <tr>
	                        <th class="text-center" <?=$styletd2?>>Novedad</th>
	                        <th class="text-center" <?=$styletd2?>>Total Capacitaciones</th>
	                        <th class="text-center" <?=$styletd2?>>Calif%</th>
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
	                            <td class="text-center" <?=$styletd2?>><?php echo $as['novedad']?></td>
	                            <td class="text-center" <?=$styletd2?>><?php echo $as['cantidad']?></td>
	                            <td class="text-center" <?=$styletd2?>><?php echo $as['calif']."%"?></td>
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
	    			<!-- ****************************************** -->
	    		</td>
	    		<td>
	    			<!-- ******************************************* -->
	    			 <table class="table table-striped " style="font-size: 10px;">
	                <thead >
	                    <tr>
	                        <th colspan="3" class="text-center">Segundo Semestre</th>
	                    </tr>
	                    <tr >
	                        <th class="text-center" <?=$styletd2?>>Novedad</th>
	                        <th class="text-center" <?=$styletd2?>>Total Capacitaciones</th>
	                        <th class="text-center" <?=$styletd2?>>Calif%</th>
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
	                            <td class="text-center" <?=$styletd2?>><?php echo $as2['novedad']?></td>
	                            <td class="text-center" <?=$styletd2?>><?php echo $as2['cantidad']?></td>
	                            <td class="text-center" <?=$styletd2?>><?php echo $as2['calif']."%"?></td>
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
	    			<!-- ******************************************* -->
	    		</td>
	    	</tr>
	    </table>

	    

	    <?php 
	        $promedio_calif=($capSemprimero+$capSegundo)/4;
	        $promedio_retail=($retail_calif+$retail_calif2)/2;
	        $promedio_vigias=($vigias_calif+$vigias_calif2)/2;
	    ?>

	    <table>
	    	<tr>
	    		<td style="text-align: center;">Seguridad en Retail: <br><span class="text-danger"><?= round($promedio_retail,2,PHP_ROUND_HALF_DOWN)."%" ?></span></td>
	    		<td style="padding-left: 40px;text-align: center;">Vigías Protección de Recursos:<br> <span class="text-danger"><?= round($promedio_vigias,2,PHP_ROUND_HALF_DOWN)."%" ?></span></td>
	    		<td style="padding-left: 40px;text-align: center;">Porcentaje Consolidado de Capacitación:<br> <span class="text-danger"><?= round($promedio_calif,2,PHP_ROUND_HALF_DOWN)."%" ?></td>
	    	</tr>
	    </table>
	    
	    
<!-- ******************************************* -->
<hr>
<h4 class="text-center">Pedidos</h4>

<table  class="display my-data" data-page-length='20' cellspacing="0" width="100%">
	 
		       <thead>

		       <tr>
		           
		           <th <?=$styletd2?>>Texto Breve</th>
				   <th <?=$styletd2?>>Cantidad</th>
				   <th <?=$styletd2?>>OC/No.Solicitud</th>		   
				   <th <?=$styletd2?>>Fecha de Creación</th>
				   
		       </tr>
		           

		       </thead>	 
			   
			   <tbody>
			   
		             <?php foreach($pedidos as $pendiente):?>	  
					   
		              <tr>			   
		            	
						
						<td <?=$styletd2?>><?= $pendiente->producto->texto_breve?></td>
		     			<td <?=$styletd2?>><?= $pendiente->cantidad?></td>
						<td <?=$styletd2?>><?= $pendiente->orden_compra?></td>
						<td <?=$styletd2?>><?= $pendiente->pedido->fecha?></td>
						
						
						
		              </tr>
		        	 <?php endforeach; ?>			 
			   
			   </tbody>
			 
			 </table>
<!-- ****************************************************************************************** -->

<hr>
<h4 class="text-center">Planes de accion</h4>

<table class="table ">
<thead>
	<tr>
		<th style="text-align: center;font-size: 10px;">Tipo</th>
		<th style="text-align: center;font-size: 10px;">Fecha</th>
		<th style="text-align: center;font-size: 10px;">Plan de accion</th>
		<th style="text-align: center;font-size: 10px;">Cumplio</th>
		<th style="width: 500px;text-align: center;font-size: 10px;" >Observaciones</th>
		
	</tr>
</thead>
<tbody>
	<?php foreach($planes_de_accion as $pl): ?>
		
		<tr>
			<td style="text-align: center;font-size: 10px;"><?= $pl->tipo ?></td>
			<td style="text-align: center;font-size: 10px;">
				<?= $pl->fecha?>
					
		    </td>
			<td style="text-align: center;font-size: 10px;"><?= strip_tags($pl->plan_de_accion) ?></td>
			<td style="text-align: center;font-size: 10px;">
				<?php 
					$cumplimiento=$pl->cumplimiento=='S'?'SI':'';
					$cumplimiento= $pl->cumplimiento=='N'?'NO':$cumplimiento;
					echo $cumplimiento;
				?> 
				
			</td>
			<td style="width: 500px;text-align: center;font-size: 10px;">
				<?= strip_tags($pl->observacion)?>
			</td>
			
		</tr>
	<?php endforeach;?>
	</tbody>
</table>

<h4 class="text-center">Novedades</h4>

<table class="table table-striped">
		<thead>
			<tr>
				
				<th style="text-align: center;font-size: 10px;">Tipo</th>
				<th style="text-align: center;font-size: 10px;">Categoria</th>
				<th style="text-align: center;font-size: 10px;">Novedad</th>
				<th style="text-align: center;font-size: 10px;">Usuario</th>
				<th style="text-align: center;font-size: 10px;">Fecha Novedad</th>
				<th style="text-align: center;font-size: 10px;">Descripcion</th>
				<th style="text-align: center;font-size: 10px;">Plan de accion</th>

			</tr>
		</thead>
		<tbody>
			<?php foreach($NovedadesMensual as $nv): ?>
				<tr>
					
					<td >Visita</td>
					<td style="text-align: center;font-size: 10px;"><?= $nv->categoria->nombre?></td>
					<td style="text-align: center;font-size: 10px;"><?= $nv->novedad->nombre?></td>
					<td style="text-align: center;font-size: 10px;"><?= $nv->usuario?></td>
					<td style="text-align: center;font-size: 10px;"><?= $nv->fecha_novedad?></td>
					<td style="text-align: center;font-size: 10px;"><?= strip_tags($nv->descripcion)?></td>
					<td style="text-align: center;font-size: 10px;"><?= strip_tags($nv->plan_de_accion)?></td>
				</tr>
				<tr>
					<td colspan="7">
						<?php  AdjuntoVisitaDetalle::adjuntos($nv->id); ?>
					</td>
				</tr>
			<?php endforeach;?>
			<?php foreach($NovedadesCapacitacion as $nvc): ?>
				<tr>
					
					<td style="text-align: center;font-size: 10px;">Capacitacion</td>
					<td style="text-align: center;font-size: 10px;"><?= $nvc->novedad->nombre?></td>
					<td style="text-align: center;font-size: 10px;">-</td>
					<td style="text-align: center;font-size: 10px;"><?= $nvc->usuario?></td>
					<td style="text-align: center;font-size: 10px;"><?= $nvc->fecha_novedad?></td>
					<td style="text-align: center;font-size: 10px;"><?= strip_tags($nvc->descripcion)?></td>
					<td style="text-align: center;font-size: 10px;"><?= strip_tags($nvc->plan_de_accion) ?></td>
				</tr>
				<tr>
					<td colspan="7">
						<?php  AdjuntoNovedadCapacitacion::adjuntos($nvc->id); ?>
					</td>
				</tr>
			<?php endforeach;?>
			<?php foreach($NovedadPedido as $nvp): ?>
			<tr>
				
				<td style="text-align: center;font-size: 10px;">Pedido</td>
				<td style="text-align: center;font-size: 10px;">-</td>
				<td style="text-align: center;font-size: 10px;">-</td>
				<td style="text-align: center;font-size: 10px;"><?= $nvp->usuario?></td>
				<td style="text-align: center;font-size: 10px;"><?= $nvp->fecha_novedad?></td>
				<td style="text-align: center;font-size: 10px;"><?= strip_tags($nvp->descripcion)?></td>
				<td style="text-align: center;font-size: 10px;"><?= strip_tags($nvp->plan_de_accion)?></td>
			</tr>
			<tr>
				<td colspan="7">
					<?php  AdjuntoNovedadPedido::adjuntos($nvp->id); ?>
				</td>
			</tr>
		<?php endforeach;?>

		</tbody>
	</table>


