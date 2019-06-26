<?php
use yii\widgets\LinkPager;
use yii\helpers\Url;
use kartik\widgets\Select2;
use yii\helpers\Html;

$this->title = 'Historico Prefactura';
?>
<?= $this->render('/pedido/_tabsHistorico',['historico_prefactura' => 'active']) ?>
<h1><i class="glyphicon glyphicon-list-alt"></i> <?= $this->title ?></h1>


<a href="<?php echo Url::toRoute('pedido/prefactura-rechazados')?>" class="btn btn-primary">Prefactura-fija</a>
<br><br>



<?php
    /*echo "Mostrando Pagina <b>".$pagina."</b>  de un total de <b>".$count."</b> Registros <br>";
    echo LinkPager::widget([
        'pagination' => $pagination
    ]);*/
?>
<div class="col-md-12">
  <div class="table-responsive">
      <table class="table table-striped my-data-consolidado">
        <thead>
            <tr>
               <th>Dependencia</th>
               <th>Ceco</th>
               <th>Cebe</th>
               <th>Marca</th>
               <th>Regional</th>
               <th>Proveedor</th>
               <th>Mes</th>
               <th>Ano</th>
               <th>Total Servicio</th>
               <th>Solicitante</th>
               <th>Usuario aprobo</th>
               <th>Fecha Aprobacion</th>
               <th>Usuario Rechazo</th>
               <th>Fecha Rechazo</th>
               <th>Motivo Rechazo</th>
               <th>Estado</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($rows as $rw):?>
            <tr>
                <td><?= $rw['dependencia']?></td>
                <td><?= $rw['ceco']?></td>
                <td><?= $rw['cebe']?></td>
                <td><?= $rw['marca']?></td>
                <td><?= $rw['regional']?></td>
                <td><?= $rw['empresa_seg']?></td>
                <td><?= $rw['mes']?></td>
                <td><?= $rw['ano']?></td>
                <td><?= '$ '.number_format(($rw['total_factura']), 0, '.', '.').' COP'?></td>
                <td><?= $rw['usuario']?></td>
                <td><?= $rw['usuario_aprueba']?></td>
                <td><?= $rw['fecha_aprobacion']?></td>
                <td><?= $rw['usuario_rechaza']?></td>
                <td><?= $rw['fecha_rechazo']?></td>
                <td><?= $rw['motivo_rechazo_prefactura']?></td>
                <td>
                    <?php 
                        $estado=$rw['estado_pedido']=='H'?'<span class="label label-success">Aprobado</span>':'<span class="label label-danger">Rechazado</span>';

                        $estado=$rw['estado']=='H'?'<span class="label label-success">Aprobado</span>':'<span class="label label-danger">Rechazado</span>';

                        echo $estado;
                    ?>
                    
                </td>
            </tr>
            
        <?php endforeach;?>
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

    table_consolidado.buttons().container().appendTo($('.col-sm-6:eq(0)', table_consolidado.table().container()));

  });
</script>