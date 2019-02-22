<?php
use yii\widgets\LinkPager;
use yii\helpers\Url;
use kartik\widgets\Select2;


$this->title = 'Prefactura Pedido';
?>
<h1><i class="glyphicon glyphicon-list-alt"></i> <?= $this->title ?></h1>

<div class="col-md-12">
  <div class="box box-primary collapsed-box box-solid">
    <div class="box-header with-border">
      <h3 class="box-title"><i class="fa fa-search fa-fw"></i> Filtro Avanzado</h3>

      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
        </button>
      </div>
      <!-- /.box-tools -->
    </div>
    <!-- /.box-header -->
    <div class="box-body">
       <form id="form_excel" method="get" action="<?php echo Url::toRoute('prefactura-index')?>">
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
    <!-- /.box-body -->
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
  <!-- /.box -->
</div>

<?php
    echo "Mostrando Pagina <b>".$pagina."</b>  de un total de <b>".$count."</b> Registros <br>";
    echo LinkPager::widget([
        'pagination' => $pagination
    ]);
?>
<div class="col-md-12">
  <div class="table-responsive">
      <table class="table table-striped">
        <thead>
            <tr>
               <th>Acciones</th>
               <th>Dependencia</th>
               <th>Ceco</th>
               <th>Cebe</th>
               <th>Marca</th>
               <th>Solicitante</th>
               <th>Material</th>
               <th>Fecha Pedido</th>
               <th>Valor</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($rows as $rw):?>
            <tr>
                <td>
                    <a class="btn btn-success btn-xs">
                        <i class="fas fa-clipboard-check"></i> Aceptar
                    </a>
                    <a class="btn btn-danger btn-xs">
                        <i class="fas fa-ban"></i> Rechazar
                    </a>
                </td>
                <td><?= $rw['dependencia']?></td>
                <td><?= $rw['ceco']?></td>
                <td><?= $rw['cebe']?></td>
                <td><?= $rw['marca']?></td>
                <td><?= $rw['solicitante']?></td>
                <td><?= $rw['material']?></td>
                <td><?= $rw['fecha_pedido']?></td>
                <td><?= '$ '.number_format(($rw['valor']), 0, '.', '.').' COP'?></td>
            </tr>
            
        <?php endforeach;?>
        </tbody>
      </table>
  </div>
</div> 