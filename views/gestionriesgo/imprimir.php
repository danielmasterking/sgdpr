
<?php 
	
	$styletd='style="padding: 5px;text-align: center;font-size: 9px;"';
	$styletd2='style="padding: 5px;font-size: 10px;"';
	$styleth='style="padding: 5px;text-align: center;font-size: 9px;';
	$styleimg='style=" width: 400px; height: 150px;"';

	$this->title = 'Detalle Gestion de riesgo ';

?>
<h1 style="text-align: center;"><?php echo $this->title ?></h1> 

<table style="width: 100%;border-collapse: collapse;" border="1">
	<tr>
		<td <?=$styleth.'"'?>>Dependencia:</td>
		<td <?=$styletd?>><?= $model->dependencia->nombre ?></td>
		<td rowspan="2" style="text-align: center;">
			<img <?= $styleimg?> alt="imagen" class="img-responsive img-thumbnail" src="<?=Yii::$app->request->baseUrl.$model->dependencia->foto?>" />
		</td>
	</tr>
	<tr>
		<td <?=$styleth.'"'?>>Fecha Visita:</td>
		<td <?=$styletd?>><?= $model->fecha_visita ?></td>
	</tr>

	<tr>
		<th  <?=$styleth.'"'?>>Novedad:</th>
		<td colspan="2" <?=$styletd?>><?= $model->observacion ?></td>
	</tr>
</table>

<br>

<table style="width: 100%;border-collapse: collapse;" border="1">
		<thead>
			<tr>
				<th <?=$styleth.'"'?>></th>
				<th <?=$styleth.'"'?>>Tema</th>
				<th <?=$styleth.'"'?>>Respuesta</th>
				<th <?=$styleth.'"'?>>Observaciones</th>
				<th <?=$styleth.'"'?>>Plan de accion</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$orden=1;
			foreach($consulta as $row){
			?>

			<tr>
				<td <?=$styletd?>><b><?= $orden?>.</b></td>
				<td <?=$styletd?>><?= $row->consulta->descripcion?></td>
				<td <?=$styletd?>><?= $row->respuesta->descripcion?></td>
				<td <?=$styletd?>><?= $row->observaciones?></td>
				<td <?=$styletd?>><?= $row->planes_de_accion?></td>


			</tr>
			<?php 
				$orden++;
				}
			?>
		</tbody>
	</table>