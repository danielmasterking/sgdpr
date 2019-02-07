<?php 
use yii\helpers\Html;
$this->title ="Delete Cerradas";
?>
<h1>Depurar Dependencias Cerradas</h1>
<?php 
	if($cont>0){

        echo "Cantidad eliminada ".$cont."<br>";
    }

?>
<?= Html::a('Comenzar Depuracion',Yii::$app->request->baseUrl.'/adminsupervision/delete-cerradas?accion=1', ['class'=>'btn btn-primary','data-confirm'=>'Seguro desea eliminar estas dependencias de la prefactura?']) ?>
<table class="table table-bordered my-data">
	<thead>
		<tr>
			<th>Id</th>
			<th>Prefactura</th>
			<th>Numero</th>
			<th>Mes</th>
			<th>Año</th>
			<th>Codigo</th>
			<th>Dependencia</th>
			<th>Estado</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($rows as $row): ?>
		<tr>
			<td><?= $row['id_admin_dep']?></td>
			<td><?= $row['id_pref']?></td>
			<td><?= $row['numero_factura']?></td>
			<td><?= $row['mes']?></td>
			<td><?= $row['ano']?></td>
			<td><?= $row['cd']?></td>
			<td><?= $row['dep']?></Std>
			<td><?= $row['estado']?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>