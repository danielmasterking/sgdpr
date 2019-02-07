<div class="col-md-12">
<div class="table-responsive">
<table class="table table-hover" width="100%">
	<thead>
		<tr>
			<th style="text-align: center;">Usuario</th>
			<th style="text-align: center;">Fecha</th>
			<th style="text-align: center;">Hora inicio conexion</th>
			<th style="text-align: center;">Hora fin conexion</th>
			<th style="text-align: center;">Dispositivo</th>

		</tr>

	</thead>
	<tbody>
		
	<?php foreach($usuarios as $row): ?>
	<tr style="text-align: center;">
		<td><?= $row['usuario']?></td>
		<td><?= $row['fecha']?></td>
		<td><?= $row['hora_inicio']?></td>
		<td><?= $row['hora_fin']?></td>
		<td>
			<?php 

				if ($row['dispositivo']=='desktop') {
					echo '<i class="fa fa-desktop fa-2x" aria-hidden="true" title="PC"></i>';

				}elseif($row['dispositivo']=='mobile'){
					echo '<i class="fa fa-mobile fa-2x" aria-hidden="true" title="MOBILE"></i>';

				}else{

					echo '<i class="fa fa-ban fa-2x" aria-hidden="true" title="NO IDENTIFICADO"></i>';					
				}




			?>

		</td>

	</tr>
	<?php endforeach; ?>


	</tbody>
	


</table>

</div>
</div>