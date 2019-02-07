 <?php
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Visitas períodicas';
if( isset(Yii::$app->session['permisos-exito']) ){
  $permisos = Yii::$app->session['permisos-exito'];
}
$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"); 
?>
<div class="row">
	<div class="col-md-12">
	<?= $this->render('_tabsDependencia',['codigo_dependencia' => $codigo_dependencia,'modelo_prefactura' => $modelo_prefactura]) ?>
	</div>
</div>
<br>
<div class="row">
	<div class="col-md-4">
	<?= Html::a('<i class="fa fa-arrow-left"></i> Volver a Pre-facturas',Yii::$app->request->baseUrl.'/centro-costo/prefacturas?id='.$codigo_dependencia,['class'=>'btn btn-primary']) ?>
	</div>
</div>
<br>
<h3 style="text-align: center;">Prefacturas de la Dependencia <?=$model->nombre?></h3>

<h3>Prefactura - fija</h3>
<div class="row">
	<div class="col-md-12">
		<table  class="table" style="font-size: 18px;">
		   	<thead>
			   	<tr>
				   	<th></th>
				   	<th>Año</th>
		           	<th>Mes</th>
		           	<th>Total del Servicio</th>
			   	</tr>
		   	</thead>
		   	<tbody>
	          	<?php foreach($modelo as $prefactura):?>
              	<tr>			   
			   		<td>
				   		<?php

				   		    if($prefactura->estado!='abierto'){	               
						   		if(in_array("administrador", $permisos) ){
			                  		echo Html::a('<i class="far fa-file-pdf"></i>',Yii::$app->request->baseUrl.'/prefactura-fija/imprimir?id='.$prefactura->id,['target'=>'_blank','class'=>'btn btn-danger btn-sm']);
						   		} 
						   	}
				   		?>
					</td>
                	<td><?= $prefactura->ano?></td>
     				<td>
     					<?=strtoupper ($meses[$prefactura->mes-1]);?>
     				</td>
     				<?php 
			    	//AQUI SE CALCULA EL VALOR TOTAL DE FTES Y EL VALOR TOTAL DEL SERVICIO
			    	$dispositivos = $model_dispositivo->find()->where('id_prefactura_fija='.$prefactura->id)->all();
			    	$total_servicio_fijo = 0;
			    	$total_servicio_variable = 0;
			    	foreach ($dispositivos as $value) {
			    		if($value->tipo=='fijo'){
			    			$total_servicio_fijo = $total_servicio_fijo + $value->valor_mes;
			    		}elseif($value->tipo=='variable'){
			    			if($value->tipo_servicio !='No Prestado'){
                               $total_servicio_variable = $total_servicio_variable + $value->valor_mes;
                            }else{
                               $total_servicio_variable = $total_servicio_variable - $value->valor_mes;
                            }
			    		}

			    	}

			    ?>
			    <td><?='$ '.number_format(($total_servicio_fijo+$total_servicio_variable), 0, '.', '.').' COP'?></td>
              	</tr>
        	 <?php endforeach; ?>	
		   	</tbody>
		</table>
	</div>
</div>


<h3>Prefactura Electronica</h3>
<div class="row">
	<div class="col-md-12">
		<table class="table table-striped" style="font-size: 18px;">
			<thead>
				<tr>
					<th></th>
				   	<th>Año</th>
		           	<th>Mes</th>
		           	<th>Total del Servicio</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($modelo_electronica as $pref_electronica): ?>
				<tr>
					<td>
						<?php

							$total_fijo_elect=0;
							$total_variable_elect=0;
							$total_monitoreo=0;

							$disp_fijo=$modelo_fijo_elect->find()->where('id_prefactura_electronica='.$pref_electronica->id)->all();
							$disp_variable=$modelo_variable_elect->find()->where('id_prefactura_electronica='.$pref_electronica->id)->all();
							$disp_monitoreo=$monitoreo->find()->where('id_prefactura_electronica='.$pref_electronica->id)->all();

							foreach ($disp_fijo as $row_fijo) {
								$total_fijo_elect=$total_fijo_elect+$pref_electronica->number_unformat($row_fijo->valor_arrendamiento_mensual);
							}

							foreach($disp_variable as $row_variable){
								$total_variable_elect=$total_variable_elect+$pref_electronica->number_unformat($row_variable->valor_novedad);
							}


							foreach ($disp_monitoreo as $row_monitoreo) {
								$total_monitoreo=$total_monitoreo+$row_monitoreo->valor_total;
							}

				   		    if($pref_electronica->estado!='abierto'){	               
						   		if(in_array("administrador", $permisos) ){
			                  		echo Html::a('<i class="far fa-file-pdf"></i>',Yii::$app->request->baseUrl.'/prefacturaelectronica/imprimir?id='.$pref_electronica->id,['target'=>'_blank','class'=>'btn btn-danger btn-sm']);
						   		} 
						   	}
				   		?>
					</td>
					<td><?= $pref_electronica->ano?></td>
					<td><?=strtoupper ($meses[$pref_electronica->mes-1]);?></td>
					<td><?='$ '.number_format(($total_fijo_elect+$total_variable_elect+$total_monitoreo), 0, '.', '.').' COP'?></td>
				</tr>
			<?php endforeach;?>
			</tbody>
		</table>
	</div>
	
</div>


<h3>Administracion y supervision</h3>

<div class="row">
	<div class="col-md-12">
		<table class="table" style="font-size: 18px;">
			<thead>
				<tr>
					<th></th>
					<th>Año</th>
					<th>Mes</th>
					<th>Total Facturado</th>
				</tr>
			</thead>
			<tbody>
				<?php 

				foreach($rows as $admin){
				?>
				<tr>
					<td>
				   		<?php

				   		    if($admin['estado']!='abierto'){	               
						   		if(in_array("administrador", $permisos) ){
			                  		echo Html::a('<i class="far fa-file-pdf"></i>',Yii::$app->request->baseUrl.'/adminsupervision/imprimir?id='.$admin['id_admin'],['target'=>'_blank','class'=>'btn btn-danger btn-sm']);
						   		} 
						   	}
				   		?>
					</td>
					<td><?= $admin['ano']?></td>
					<td><?=strtoupper ($meses[$admin['mes']-1]);?></td>
					<td><?='$ '.number_format($admin['TOTAL'], 0, '.', '.').' COP'?></td>
				</tr>
				<?php }?>

			</tbody>

		</table>
	</div>

</div>