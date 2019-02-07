<?php
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Prefacturas';
if( isset(Yii::$app->session['permisos-exito']) ){
  $permisos = Yii::$app->session['permisos-exito'];
}
$suma=0;$ftes=0;
foreach ($modelo as $value) {
	$suma=$suma+$value->valor_mes;
	$ftes=$ftes+$value->ftes;
}


$suma_electronica=0;
foreach ($modelo_electronica as $value) {
	$valor_mes=$model_elect->number_unformat($value->valor_arrendamiento_mensual);

	$suma_electronica=$suma_electronica+$valor_mes;
	
}


$suma_monitoreos=0;

foreach ($monitoreo as $row_mon) {
	$suma_monitoreos=$suma_monitoreos+$row_mon->valor_total;
}


//$fija='active';
?>
<div class="row">
	<div class="col-md-12">
	<?= $this->render('_tabsDependencia',['codigo_dependencia' => $codigo_dependencia,'modelo_prefactura' => $modelo_prefactura]) ?>
	</div>
</div>
<br>

<!-- <div class="row">
	<div class="col-md-12">
	<?php //echo $this->render('_tabsprefactura',['codigo_dependencia' => $codigo_dependencia,'fija' => $fija]) ?>
	</div>
</div>
<br> -->



<div class="row">
	<div class="col-md-4">
	<?= Html::a('<i class="fa fa-cogs"></i> Configuracion de Dispositivo Fijo',Yii::$app->request->baseUrl.'/centro-costo/modelo?id='.$codigo_dependencia,['class'=>'btn btn-primary']) ?>
	</div>

	<div class="col-md-5">
		<?= Html::a('<i class="fa fa-cogs"></i> Configuracion de Monitoreo',Yii::$app->request->baseUrl.'/centro-costo/modeloelectronico?id='.$codigo_dependencia,['class'=>'btn btn-primary']) ?>
	</div>

	<div class="col-md-3">
	<?= Html::a('<i class="fa fa-list-ol"></i> Listado de Prefacturas',Yii::$app->request->baseUrl.'/centro-costo/listado-prefacturas?id='.$codigo_dependencia,['class'=>'btn btn-primary']) ?>
	</div>
</div>
<br>
<h2 style="text-align: center;">Resumen</h2>
<div class="row">
	<div class="col-md-12">
		<table  class="table" style="font-size: 18px;">
		   	<thead>
			   	<tr>
				   	<th>Dispositivo</th>
			       	<th>Costo</th>
			       	<th>Ftes Totales</th>
			   	</tr>
		   	</thead>
		   	<tbody>
	          	<tr>
		            <td>Fijo</td>
		 			<td><?='$ '.number_format($suma, 0, '.', '.').' COP'?></td>
		 			<td><?=$ftes?></td>
	          	</tr>

	          	<tr>
		            <td>Seguridad-Electronica</td>
		 			<td>
		 				<?php 
		 					$total_electronica=$suma_electronica+$suma_monitoreos;
		 					echo '$ '.number_format($total_electronica, 0, '.', '.').' COP';
		 				?>
		 				
		 			</td>
		 			<td></td>
	          	</tr>

	          	<!-- <tr>
		            <td>Monitoreo-Electronica</td>
		 			<td><?php //echo '$ '.number_format($suma_monitoreos, 0, '.', '.').' COP'?></td>
		 			<td></td>
	          	</tr> -->
	          	<tr>
	          			
	          	<td>TOTAL</td>
		 			<td><?php echo '$ '.number_format($suma+$total_electronica, 0, '.', '.').' COP'?></td>
		 			<td></td>

	          	</tr>

		   	</tbody>
		</table>
	</div>
</div>


<h2 style="text-align: center;">Resumen Administracion y Supervision</h2>

<div class="row">
	
	<table class="table" style="font-size: 18px;">
		<thead>
			<tr>
				<th style="text-align: center;">Total Facturado</th>
			</tr>
		</thead>
	
		<tbody>
			<tr >
				<td style="text-align: center;"><?php echo '$ '.number_format($total, 0, '.', '.').' COP'?></td>
			</tr>

		</tbody>
	</table>
</div>