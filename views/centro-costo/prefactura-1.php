<?php
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Visitas períodicas';
if( isset(Yii::$app->session['permisos-exito']) ){
  $permisos = Yii::$app->session['permisos-exito'];
}
$suma=0;$ftes=0;
foreach ($modelo as $value) {
	$suma=$suma+$value->valor_mes;
	$ftes=$ftes+$value->ftes;
}
?>
<div class="row">
	<div class="col-md-12">
	<?= $this->render('_tabsDependencia',['codigo_dependencia' => $codigo_dependencia,'modelo_prefactura' => $modelo_prefactura]) ?>
	</div>
</div>
<br>
<div class="row">
	<div class="col-md-4">
	<?= Html::a('<i class="fa fa-cogs"></i> Configuracion de Dispositivo Fijo',Yii::$app->request->baseUrl.'/centro-costo/modelo?id='.$codigo_dependencia,['class'=>'btn btn-primary']) ?>
	</div>
	<div class="col-md-5"></div>
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
		   	</tbody>
		</table>
	</div>
</div>