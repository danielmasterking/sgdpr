<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
$this->title = 'Cabecera Administracion y supervision';
?>
<div class="page-header">
	<h1><small><i class="fa fa-file fa-fw"></i></small> <?= Html::encode($this->title) ?></h1>
</div>

<a class="btn btn-primary" href="<?php echo Url::toRoute('consolidado')?>"><i class="fas fa-receipt"></i> Items</a>
<br><br>

<div class="col-md-12">
	<div class="table-responsive">
		<table class="table table-striped my-data-consolidado" data-page-length='30'>
			<thead>
				<tr>
					<th>ID_PEDIDO </th>
					<th>Clase de Documento de compra</th>
					<th>Fecha de creación</th>
					<th>Número de cuenta proveedor</th>
					<th>Organización Compras</th>
					<th>Grupo de compras</th>
					<th>Clave de moneda</th>
					<th>Compañia</th>
					<th>Observaciones</th>
					<th>Descripción General</th>
					<th>Responsable</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($result as $rw): ?>
					<tr>
						<td><?= $rw['id_pedido']?></td>
						<td>ZNB</td>
						<td>
							<?php 
			                  $fecha=$rw['fecha_aprobacion'];
			                  echo date('dmY', strtotime($fecha. ' + 1 days'));
			                ?>  
						</td>
						<td>
							<?php 
				                  $empresa=trim((string)$rw['empresa_seg']);
				                  //echo $empresa;
				                  switch ($empresa) {
				                    case 'NASER LTDA':
				                      echo "243802";   
				                    break;

				                    case 'PEGASO LTDA':
				                      echo "288545";   
				                    break;

				                    case 'MIRO SEGURIDAD':
				                      echo "236925";   
				                    break;

				                    case 'ANDINA SEGURIDAD DEL VALLE':
				                      echo "181316";   
				                    break;

				                    case 'SECANCOL LTDA':
				                      echo "244111";   
				                    break;

				                    case 'COLVISEG DEL CARIBE':
				                      echo "184161";   
				                    break;

				                    case 'SECURITAS':
				                      echo "406814";   
				                    break;
				                    
				                    default:
				                      echo "N/A";
				                    break;
				                  }

				                ?>
						</td>
						<td>1006</td>
						<td>606</td>
						<td>COP</td>
						<td></td>
						<td></td>
						<td></td>
						<td><?= $rw['usuario']?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
<script type="text/javascript">
	  $(function(){
      var table_consolidado = $('.my-data-consolidado').DataTable({
        "ordering": false,
        "columnDefs": [{
            "className": "dt-center",
            "targets": "_all"
        }],
        dom: 'Bfrtip',
        buttons: [
          {
            extend:    'excelHtml5',
            text:      '<i class="fas fa-file-excel"></i> Excel',
            titleAttr: 'Excel'
          }

        ],
        //"order": [[0,"asc"]],
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

    table_consolidado.buttons().container().appendTo($('.col-sm-6:eq(0)', table.table().container()));

  });
</script>