<?php
use yii\widgets\LinkPager;
use yii\helpers\Url;
use kartik\widgets\Select2;
use yii\helpers\Html;


$this->title = 'Consolidado prefacturas';
?>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<?= $this->render('_tabsConsolidado',['prefactura' => 'active']) ?>
<h1><i class="glyphicon glyphicon-list-alt"></i> <?= $this->title ?></h1>

<?php //echo Html::a('<i class="fas fa-ban"></i> Rechazados',Yii::$app->request->baseUrl.'/pedido/prefactura-rechazados',['class'=>'btn btn-primary']) ?>	
<br>

<!-- <div class="col-md-12">
  <div class="box box-primary collapsed-box box-solid">
    <div class="box-header with-border">
      <h3 class="box-title"><i class="fa fa-search fa-fw"></i> Filtro Avanzado</h3>

      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
        </button>
      </div>
      
    </div>
    
    <div class="box-body">
       <form id="form_excel" method="get" action="<?php echo Url::toRoute('prefactura-aprobados')?>">
            <div class="row">
                
                <div class="col-md-3">
                    <input type="text" id="buscar" name="buscar" class="form-control" placeholder="Buscar Coincidencias" value="<?=isset($_GET['buscar']) && $_GET['buscar']!=''?$_GET['buscar']:''?>">
                </div>
                <div class="col-md-3">
                    <?php 
                        echo Select2::widget([
                            'id' => 'dependencias',
                            'name' => 'dependencias',
                            'value' => isset($_GET['dependencias']) && $_GET['dependencias']!=''?$_GET['dependencias']:'',
                            'data' => $dependencias,
                            'options' => ['multiple' => false, 'placeholder' => 'POR DEPENDENCIA...'],
                            'pluginOptions' => [
                            'allowClear' => true
                            ]
                        ]);
                    ?>
                </div>

                <div class="col-md-3">
                        <select id="ordenado" name="ordenado" class="form-control">
                            <option value="" <?= isset($_GET['ordenado']) && $_GET['ordenado']==''?'selected':''?>>[ORDENAR POR...]</option>
                            <option value="marca" <?= isset($_GET['ordenado']) && $_GET['ordenado']=='marca'?'selected':''?>>Marca</option>
                            <option value="dependencia" <?= isset($_GET['ordenado']) && $_GET['ordenado']=='dependencia'?'selected':''?>>Dependencia</option>
                            <option value="solicitante" <?= isset($_GET['ordenado']) && $_GET['ordenado']=='solicitante'?'selected':''?>>Solicitante</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select id="forma" name="forma" class="form-control">
                            <option value="" <?= isset($_GET['forma']) && $_GET['forma']==''?'selected':''?>>[FORMA...]</option>
                            <option value="SORT_ASC" <?= isset($_GET['forma']) && $_GET['forma']=='SORT_ASC'?'selected':''?>>Ascendente</option>
                            <option value="SORT_DESC" <?= isset($_GET['forma']) && $_GET['forma']=='SORT_DESC'?'selected':''?>>Descendente</option>
                        </select>
                    </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-3">
                    <?php 
                        echo Select2::widget([
                            'id' => 'marcas',
                            'name' => 'marca',
                            'value' => isset($_GET['marca']) && $_GET['marca']!=''?$_GET['marca']:'',
                            'data' => $marcas,
                            'options' => ['multiple' => false, 'placeholder' => 'POR MARCA...'],
                            'pluginOptions' => [
                            'allowClear' => true
                            ]
                        ]);
                    ?>
                </div>

            </div>
    </div>
    
    <div class="box-footer">
        <button type="button" class="btn btn-primary" onclick="excel()">
            <i class="fas fa-file-excel"></i> Descargar Busqueda en Excel
        </button>
        <button type="submit" class="btn btn-primary"  name="enviar">
            <i class="fa fa-search fa-fw"></i> Buscar
        </button>
        </form>
    </div>
  </div>
  
</div> -->

<button class="btn btn-primary"><i class="fas fa-balance-scale"></i> Realizar equivalencia</button>
<button class="btn btn-primary"><i class="fas fa-user-circle"></i> Cabecera</button>
<button class="btn btn-primary" id="finalizar_pref" onclick="finalizar();"><i class="fas fa-clipboard-list"></i> Finalizar</button>

<br><br>
<?php
    /*echo "Mostrando Pagina <b>".$pagina."</b>  de un total de <b>".$count."</b> Registros <br>";
    echo LinkPager::widget([
        'pagination' => $pagination
    ]);*/
?>
<div class="col-md-12">
  <div class="table-responsive">
      <table class="table table-striped my-data-consolidado" data-page-length='30'>
        <thead>
            <tr>
              <th>ID_PEDIDO</th>
              <th>Posición</th>
              <th>Material</th>
              <th>Texto Breve</th>
              <th>Cantidad </th>
              <th>unidad</th>
              <th>ultima entrada</th>
              <th>importe para bapis</th>
              <th>Centro</th>
              <th>Grupo</th>
              <th>Almacen</th>
              <th>Imputación</th>
              <th>Solicitante</th>
              <th>Indicador Iva</th>
              <th>Fecha Entrega</th>
              <th>Clase de condicion</th>
              <th>Impte. Condición </th>
              <th>Clave de moneda</th>
              <th>Tipo de modificacion</th>
              <th>Numero de cuenta mayor</th>
              <th>Centro de Coste </th>
              <th>Número de Orden</th>
              <th>Contrato Asociado</th>
              <th>Posición Contrato </th>
              <th>Codigo activo Fijo</th>
              <th>División</th>
              <th>Cebe</th>
              <th>Sublinea </th>
              <th>Descripción por posición</th>
            </tr>
        </thead>
        <tbody>
        <?php 
          foreach($rows as $rw):
            $total_mes=$rw['total_mes'];
            $valor1=(($rw['total_mes']*90)/100);
            $valor2=(($rw['total_mes']*10)/100);
        ?>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td>1</td>
              <td>UN</td>
              <td></td>
              <td><?= '$ '.number_format(($valor1), 0, '.', '.').' COP'?></td>
              <td><?= $rw['cebe']?></td>
              <td></td>
              <td></td>
              <td>K</td>
              <td><?= $rw['usuario']?></td>
              <td>93</td>
              <td>
                <?php 
                  $fecha=$rw['fecha_aprobacion'];
                  echo date('Y-m-d', strtotime($fecha. ' + 1 days'));
                ?>  
              </td>
              <td>MWVS</td>
              <td>19</td>
              <td>COP</td>
              <td></td>
              <td>
                <?php
                  $ceco=(string) $rw['ceco'];
                  $resultado = substr($ceco, 0,1);

                  switch ($resultado) {
                    case '3':

                      echo 533505001 ;

                      break;
                    
                    default:
                      echo 523505001;
                      break;
                  }
                ?>
              </td>
              <td><?= $rw['ceco']?></td>
              <td>102926</td>
              <td>
                <?php 
                  $empresa=$rw['empresa'];

                  switch ($empresa) {
                    case 'NASER LTDA':
                      echo "5500009152";   
                    break;

                    case 'PEGASO LTDA':
                      echo "5500009151";   
                    break;

                    case 'MIRO SEGURIDAD':
                      echo "5500009156";   
                    break;

                    case 'ANDINA SEGURIDAD DEL VALLE':
                      echo "5500009157";   
                    break;

                    case 'SECANCOL LTDA':
                      echo "5500009158";   
                    break;

                    case 'COLVISEG DEL CARIBE':
                      echo "5500009159";   
                    break;

                    case 'SECURITAS':
                      echo "5500009161";   
                    break;
                    
                    default:
                      echo "N/A";
                    break;
                  }

                ?>
              </td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>

              <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td>1</td>
              <td>UN</td>
              <td></td>
              <td><?= '$ '.number_format(($valor2), 0, '.', '.').' COP'?></td>
              <td><?= $rw['cebe']?></td>
              <td></td>
              <td></td>
              <td>K</td>
              <td><?= $rw['usuario']?></td>
              <td>SO</td>
              <td>
                <?php 
                  $fecha=$rw['fecha_aprobacion'];
                  echo date('Y-m-d', strtotime($fecha. ' + 1 days'));
                ?>  
              </td>
              <td>MWVS</td>
              <td>19</td>
              <td>COP</td>
              <td></td>
              <td>
                <?php
                  $ceco=(string) $rw['ceco'];
                  $resultado = substr($ceco, 0,1);

                  switch ($resultado) {
                    case '3':

                      echo 533505001 ;

                      break;
                    
                    default:
                      echo 523505001;
                      break;
                  }
                ?>
              </td>
              <td><?= $rw['ceco']?></td>
              <td>102926</td>
              <td>
                <?php 
                  $empresa=$rw['empresa'];

                  switch ($empresa) {
                    case 'NASER LTDA':
                      echo "5500009152";   
                    break;

                    case 'PEGASO LTDA':
                      echo "5500009151";   
                    break;

                    case 'MIRO SEGURIDAD':
                      echo "5500009156";   
                    break;

                    case 'ANDINA SEGURIDAD DEL VALLE':
                      echo "5500009157";   
                    break;

                    case 'SECANCOL LTDA':
                      echo "5500009158";   
                    break;

                    case 'COLVISEG DEL CARIBE':
                      echo "5500009159";   
                    break;

                    case 'SECURITAS':
                      echo "5500009161";   
                    break;
                    
                    default:
                      echo "N/A";
                    break;
                  }

                ?>
              </td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>

            <tr class="info">
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td><b><?= '$ '.number_format(($rw['total_mes']), 0, '.', '.').' COP'?></b></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
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

    table_consolidado.buttons().container().appendTo($('.col-sm-6:eq(0)', table.table().container()));

  });

  function finalizar(){
    swal({
          title: "Seguro desea finalizar estas prefacturas?",
          text: "",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((confirm) => {
          if (confirm) {
            location.href="<?php echo Url::toRoute('finalizar-prefacturas')?>";
          } else {
            return false;
          }
        });
  }
</script>