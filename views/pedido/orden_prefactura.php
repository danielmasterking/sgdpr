<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
$this->title = 'Agregar Orden de Compra Prefactura';
?>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<?= $this->render('_tabs_oc',['ocprefactura' => 'active']) ?>
<div class="page-header">
	<h1><small><i class="fa fa-file fa-fw"></i></small> <?= Html::encode($this->title) ?></h1>
</div>

<form id="form-orden-all" method="post" action="<?php echo Url::toRoute('prefactura-agregar-orden-todos')?>">
<div class="col-md-12">
	<div class="table-responsive">
		<table class="table table-striped my-data-consolidado" data-page-length='30'>
			<thead>
				<tr>
					<th><input type="checkbox" id="todos"></th>
					<th>Id_pedido</th>
					<th>Posicion</th>
					<th>Cebe</th>
					<th>Dependencia</th>
					<th>Texto breve</th>
					<th>Cantidad</th>
					<th>OC/No.Solicitud</th>
					<th>Fecha de Creacion</th>
					<th>
						<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#myModal-todos">
							<i class="fas fa-layer-group"></i> Multiple
						</button>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($result as $row): ?>
				  <tr>
				  	<td><input type="checkbox" name="seleccion[]" class="check" value="<?= $row['id']?>"></td>
				  	<td></td>
				  	<td></td>
				  	<td><?= $row['cebe'] ?></td>
				  	<td><?= $row['dependencia']?></td>
				  	<td></td>
				  	<td>1</td>
				  	<td><?= $row['orden_compra']?></td>
				  	<td><?= $row['Fecha_creado']?></td>
				  	<td>
				  		<button class="btn btn-primary" title="Agregar orden de compra" data-toggle="modal" data-target="#myModal" onclick="agregar_id(<?=$row['id']?>);" type="button">
				  			<i class="fab fa-angellist"></i>
				  		</button>
				  	</td>
				  </tr>
				<?php endforeach;?>
			</tbody>
		</table>
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="myModal-todos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">#Orden de compra</h4>
      </div>
      <div class="modal-body">
        
        	<label>#Orden</label>
        	<input type="text" name="orden" class="form-control" id="orden-all" required="">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary"  onclick="agregarordenTodos();">Guardar</button>
       
      </div>
    </div>
  </div>
</div>
</form>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">#Orden de compra</h4>
      </div>
      <div class="modal-body">
        <form id="form-orden-one" method="post" action="<?php echo Url::toRoute('prefactura-agregar-orden')?>">
        	<input type="hidden" name="id_pref" id="pref_one">
        	<label>#Orden</label>
        	<input type="text" name="orden" class="form-control" id="orden-one" required="">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="agregar_orden_one" onclick="agregarorden();">Guardar</button>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
	$(function(){

	    $("#todos").change(function () {
          $("input:checkbox").prop('checked', $(this).prop("checked"));
        });

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
        // "order": [[0,"desc"]],
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

function agregar_id(id){
	$('#pref_one').val(id);
}

function agregarorden(){
	swal({
	  title: "Seguro desea agregar esta orden de compra?",
      text: "",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((confirm) => {
      if (confirm) {
      	if($('#orden-one').val()==''){
      		swal("Error", "El numero de la orden es obligatoria", "error");
      	}else{
        	$('#form-orden-one').submit();
        }
      } else {
        return false;
      }
    });
}

function agregarordenTodos(){

	swal({
	  title: "Seguro desea agregar esta orden de compra?",
      text: "",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((confirm) => {
        var contador=0;
	    // Recorremos todos los checkbox para contar los que estan seleccionados
	    $(".check").each(function(){

	        if($(this).is(":checked"))

	            contador++;

	    });


      if (confirm) {
      	if($('#orden-all').val()==''){
      		swal("Error", "El numero de la orden es obligatoria", "error");
      	}else if(contador==0){
      		swal("Error", "Selecciona una prefactura", "error");
      	}else{
        	$('#form-orden-all').submit();
        }
      }else {
        return false;
      }
    });
}
</script>