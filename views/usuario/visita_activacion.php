<?php 
$this->title = 'Visitas por activacion '.$usuario;
 ?>
<?= $this->render('_tabs',['visitas_activacion' => 'active','usuario' => $usuario]) ?>
<h1 class="text-center"><?= $this->title ?></h1>
<div class="row">
	<div class="col-md-12">
		<table class="table table-striped visitas_table" data-page-length="30">
			<thead>
				<tr>
					<th>Dependencia</th>
					<th>Cantidad de apoyo</th>
					<th>Otros</th>
					<th>Cantidad de apoyo otros</th>
					<th>Fecha</th>
					<th>Novedad</th>
					<th>Descripcion</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($visitas as $vs){ ?>
					<tr>
						<td><?= $vs->dependencia->nombre ?></td>
						<td><?= $vs->cantidad_apoyo ?></td>
						<td><?= $vs->otros ?></td>
						<td><?= $vs->cantidad_apoyo_otros ?></td>
						<td><?= $vs->fecha ?></td>
						<td><?= $vs->novedad->nombre ?></td>
						<td><?= $vs->descripcion ?></td>
					</tr>
				<?php } ?>

			</tbody>
		</table>
	</div>
</div>


<script type="text/javascript">
	$(function()
	{

		var visitas_table = $('.visitas_table').DataTable({
        "columnDefs": [{
            "className": "dt-center",
            "targets": "_all"
        }],
        dom: 'Bfrtip',
        buttons: ['excel', 'pdf'],
        "order": [[4,"desc"]],
        language: {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        }
    	});
   		visitas_table.buttons().container().appendTo($('.col-sm-6:eq(0)', visitas_table.table().container()));
	})
</script>