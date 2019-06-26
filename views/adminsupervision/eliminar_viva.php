<?php 


use yii\helpers\Html;
use yii\helpers\Url;

?>

<a href="<?php echo Url::to(['adminsupervision/eliminar-viva', 'eliminar' => 1])?>" class="btn btn-primary" data-confirm="seguro deseas eliminar">
    Eliminar
</a>

<?php 
	if($contador>0){

		echo "Eliminadas un total de ".$contador." dependencias";
	}

?>

<table class="table table-striped">
	<thead>
		<tr>
			<th>Id</th>
			<th>Dependencia</th>
			<th>Marca</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach($rows as $rw): ?>
		<tr>
			<td><?= $rw[id_admin_dep]?></td>
			<td><?= $rw[dependencia]?></td>
			<td><?= $rw[marca]?></td>
		</tr>
    <?php endforeach; ?>
	</tbody>
</table>