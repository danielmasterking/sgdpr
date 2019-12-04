<?php
use yii\widgets\LinkPager;
use yii\helpers\Url;
use kartik\widgets\Select2;


$this->title = 'Aprobación Prefactura';

?>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<h1><i class="glyphicon glyphicon-list-alt"></i> <?= $this->title ?></h1>

<div class="col-md-12">
  <div class="box box-primary collapsed-box box-solid">
    <div class="box-header with-border">
      <h3 class="box-title"><i class="fa fa-search fa-fw"></i> Filtro Avanzado</h3>

      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
        </button>
      </div>
     
    </div>
    
    <div class="box-body">
       <form id="form_excel" method="get" action="<?php echo Url::toRoute('aprobacion_gerente')?>">
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
                            <option value="usuario" <?= isset($_GET['ordenado']) && $_GET['ordenado']=='usuario'?'selected':''?>>Solicitante</option>

                            <option value="mes" <?= isset($_GET['ordenado']) && $_GET['ordenado']=='mes'?'selected':''?>>Mes</option>

                            <option value="ano" <?= isset($_GET['ordenado']) && $_GET['ordenado']=='ano'?'selected':''?>>Año</option>
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

                 <div class="col-md-3">
                    <?php 
                        echo Select2::widget([
                            'id' => 'regional',
                            'name' => 'regional',
                            'value' => isset($_GET['regional']) && $_GET['regional']!=''?$_GET['regional']:'',
                            'data' => $data_regional,
                            'options' => ['multiple' => false, 'placeholder' => 'POR REGIONAL...'],
                            'pluginOptions' => [
                            'allowClear' => true
                            ]
                        ]);
                    ?>
                </div>

                <div class="col-md-3" >
                  <select class="form-control"  name="mes">
                      <option value="" <?= isset($_GET['mes']) && $_GET['mes']==''?'selected':''?>>Por Mes</option>
                      <option value="01" <?= isset($_GET['mes']) && $_GET['mes']=='01'?'selected':''?>>Enero</option>
                      <option value="02" <?= isset($_GET['mes']) && $_GET['mes']=='02'?'selected':''?>>Febrero</option>
                      <option value="03" <?= isset($_GET['mes']) && $_GET['mes']=='03'?'selected':''?>>Marzo</option>
                      <option value="04" <?= isset($_GET['mes']) && $_GET['mes']=='04'?'selected':''?>>Abril</option>
                      <option value="05" <?= isset($_GET['mes']) && $_GET['mes']=='05'?'selected':''?>>Mayo</option>
                      <option value="06" <?= isset($_GET['mes']) && $_GET['mes']=='06'?'selected':''?>>Junio</option>
                      <option value="07" <?= isset($_GET['mes']) && $_GET['mes']=='07'?'selected':''?>>Julio</option>
                      <option value="08" <?= isset($_GET['mes']) && $_GET['mes']=='08'?'selected':''?>>Agosto</option>
                      <option value="09" <?= isset($_GET['mes']) && $_GET['mes']=='09'?'selected':''?>>Septiembre</option>
                      <option value="10" <?= isset($_GET['mes']) && $_GET['mes']=='10'?'selected':''?>>Octubre</option>
                      <option value="11" <?= isset($_GET['mes']) && $_GET['mes']=='11'?'selected':''?>>Noviembre</option>
                      <option value="12" <?= isset($_GET['mes']) && $_GET['mes']=='12'?'selected':''?>>Diciembre</option>

                  </select>
                </div>


              <div class="col-md-3">
                    <?php 
                      echo Select2::widget([
                          'name' => 'empresas',
                          'value' => isset($_GET['empresas']) && $_GET['empresas']!=''?$_GET['empresas']:'',
                          'data' => $list_empresas,
                          //'size' => Select2::SMALL,
                          'options' => ['placeholder' => 'Por Empresa ...', 'id'=>'empresas'],
                          'pluginOptions' => [
                              'allowClear' => true
                          ],
                      ]);
                    ?>
              </div>

            </div>
            <br>
          <div class="row"> 
             <div class="col-md-3">
                    <?php 
                      echo Select2::widget([
                          'name' => 'ciudad',
                          'value' => isset($_GET['ciudad']) && $_GET['ciudad']!=''?$_GET['ciudad']:'',
                          'data' => $list_ciudad,
                          //'size' => Select2::SMALL,
                          'options' => ['placeholder' => 'Por Ciudad ...', 'id'=>'ciudad'],
                          'pluginOptions' => [
                              'allowClear' => true
                          ],
                      ]);
                    ?>
              </div>
          </div>
    </div>
    
    <div class="box-footer">
        <!-- <button type="button" class="btn btn-primary" onclick="excel()">
            <i class="fas fa-file-excel"></i> Descargar Busqueda en Excel
        </button> -->
        <button type="submit" class="btn btn-primary"  name="enviar">
            <i class="fa fa-search fa-fw"></i> Buscar
        </button>
        </form>
    </div>
  </div>
  
</div> 

<?php
    echo "Mostrando Pagina <b>".$pagina."</b>  de un total de <b>".$count."</b> Registros <br>";
    echo LinkPager::widget([
        'pagination' => $pagination
    ]);
?> 
<form action="<?php echo Url::toRoute('aprobar-rechazar')?>?status=<?= $estado_siguiente ?>" method="post" id="form-aprobar-rechazar">

<button class="btn btn-primary" type="submit" name="aprobar" title="Aprobar Todos">
    <i class="fas fa-clipboard-check"></i> Aprobar
</button>
<button class="btn btn-danger" type="button"   title="Rechazar Todos" data-toggle="modal" data-target="#myModal1">
    <i class="fas fa-ban"></i> Rechazar
</button>

<div class="col-md-12">
  <div class="table-responsive">
   
      <table class="table table-striped">
        <thead>
            <tr>
               <th><input type="checkbox" id="todos"></th>
               <th>Acciones</th>
               <th></th>
               <th>Dependencia</th>
               <th>Ceco</th>
               <th>Cebe</th>
               <th>Marca</th>
               <th>Ciudad</th>
               <th>Regional</th>
               <th>Empresa</th>
               <th>Mes</th>
               <th>Ano</th>
               <th>Total Fijo</th>
               <th>Total Variable</th>
               <th>Total Servicio</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($rows as $rw):?>
            <tr>

                <td>
                    <input type="checkbox" name="seleccion[]" class="check" value="<?= $rw['id']?>">
                </td>
                <td>
                    <button class="btn btn-success btn-xs"  title="Aprobar" onclick="Aprobar(<?= $rw['id']?>)" type="button">
                        <i class="fas fa-clipboard-check"></i>
                    </button>
                    <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#myModal" onclick="Rechazo(<?= $rw['id']?>);" title="Rechazar" type="button">
                        <i class="fas fa-ban"></i>
                    </button>
                </td>
                <td><?= $rw['id']?></td>
                <td><?= $rw['dependencia']?></td>
                <td><?= $rw['ceco']?></td>
                <td><?= $rw['cebe']?></td>
                <td><?= $rw['marca']?></td>
                <td><?= $rw['ciudad']?></td>
                <td><?= $rw['regional']?></td>
                <td><?= $rw['empresa']?></td>
                <td><?= $rw['mes']?></td>
                <td><?= $rw['ano']?></td>
                <td><?= '$ '.number_format(($rw['total_fijo']), 0, '.', '.').' COP'?></td>
                <td><?= '$ '.number_format(($rw['total_variable']), 0, '.', '.').' COP'?></td>
                <td><?= '$ '.number_format(($rw['total_mes']), 0, '.', '.').' COP'?></td>
            </tr>
            
        <?php endforeach;?>
        </tbody>
      </table>
    
  </div>
</div>




<!-- Modal Rechazar todos-->
<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Motivo Rechazo</h4>
      </div>
      <div class="modal-body">
        

         <label>Observacion:</label>
         <textarea class="form-control" name="observacion" id="observacion-todos" rows="5"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" name="rechazar" >Confirmar</button>
        
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
        <h4 class="modal-title" id="myModalLabel">Motivo Rechazo</h4>
      </div>
      <div class="modal-body">
        <form method="post" acion="" id="form-rechazo">

         <label>Observacion:</label>
         <textarea class="form-control" name="observacion" id="observacion" rows="5"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" >Confirmar</button>
        </form>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
    $(function(){
        $('#form-rechazo').submit(function(event) {
            /* Act on the event */
            var confirmar=confirm('Seguro desea rechazar este producto?');

            if(confirmar){
                if($('#observacion').val()==''){
                    alert('La observacion es obligatoria')
                    return false;
                }
            }else{

                return false;
            }
        });


        $("#todos").change(function () {
          $("input:checkbox").prop('checked', $(this).prop("checked"));
        });


        $('#form-aprobar-rechazar').submit(function(event) {

            /* Act on the event */
            var contador=0;
            // Recorremos todos los checkbox para contar los que estan seleccionados
            $(".check").each(function(){

                if($(this).is(":checked"))

                    contador++;

            });

            if(contador>0){
              
                var confirmar=confirm('Seguro desea realizar esta accion?');

                if(!confirmar){
                    return false;
                }
            }else{

                alert('Selecciona un pedido');
                return false;
            }
        });

    });
    function Rechazo(id){
        $('#form-rechazo').attr('action', '<?php echo Url::toRoute('rechazar-prefactura')?>'+'?id='+id);
    }

    function Aprobar(id){
      swal({
          title: "Seguro desea aprobar esta prefactura?",
          text: "",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((confirm) => {
          if (confirm) {
            location.href="<?php echo Url::toRoute('aprobar-prefactura')?>?id="+id+"&status=<?= $estado_siguiente?>";
          } else {
            return false;
          }
        });
    }


</script>