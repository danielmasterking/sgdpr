<br>
<?php
	switch ($tipo) {
		case 'visita':
?>


<?php foreach ($adjuntosVisita as $key => $value): ?>
	<a  target="_blank" href="<?= Yii::$app->request->baseUrl.$value->archivo ?>">
		<i class="fa  fa-cloud-download"></i>	<?= str_replace('/uploads/VisitaMensual/','', $value->archivo) ?>
			
	</a><br>
<?php endforeach ?>

<?php 
	break;

	case 'capacitacion':
?>

<?php foreach ($adjuntosVisita as $key => $value): ?>
	<a  target="_blank" href="<?= Yii::$app->request->baseUrl.$value->archivo ?>">
		<i class="fa  fa-cloud-download"></i>	<?= str_replace('/uploads/novedad_capacitacion/','', $value->archivo) ?>
			
	</a><br>
<?php endforeach ?>
<?php 
	break;

	case 'pedido':

	
	//case 'capacitacion':
?>

<?php foreach ($adjuntosVisita as $key => $value): ?>
	<a  target="_blank" href="<?= Yii::$app->request->baseUrl.$value->archivo ?>">
		<i class="fa  fa-cloud-download"></i>	<?= str_replace('/uploads/novedad_pedido/','', $value->archivo) ?>
			
	</a><br>
<?php endforeach ?>

<?php
	break;


}

?>