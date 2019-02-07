<div class="col-md-12">
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Id</th>
				<th>Fecha</th>
				<th>Avance</th>
				<th>Accion</th>
				<th>Usuario</th>
				<th>Sistema</th>
				<th>Tipo Reporte</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($historial as $hi): ?>
		    <tr>
		    	<td><?= $hi->id?></td>
		    	<td><?= $hi->fecha?></td>
		    	<td><?= $hi->porcentaje."%"?></td>
		    	<td><?= $hi->accion=='I'?'Insercion':'Actualizacion'?></td>
		    	<td><?= $hi->usuario?></td>
		    	<td><?= $hi->sistema->nombre?></td>
		    	<td><?= $hi->reportes->nombre?></td> 	
		    </tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
