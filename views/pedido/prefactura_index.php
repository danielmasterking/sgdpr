<?php
use yii\widgets\LinkPager;
use yii\helpers\Url;
use kartik\widgets\Select2;


$this->title = 'Prefactura Pedido';
?>
<h1><i class="glyphicon glyphicon-list-alt"></i> <?= $this->title ?></h1>
<div class="panel panel-primary">
  <div class="panel-heading"><i class="fa fa-search"></i> Filtro avanzado</div>
  <div class="panel-body">
    <!-- ******************************************************************************************* -->
        <form id="form_excel" method="post" action="<?php echo Url::toRoute('prefactura-index')?>">
            <div class="row">
                
                <div class="col-md-3">
                    <input type="text" id="buscar" name="buscar" class="form-control" placeholder="Buscar Coincidencias" value="<?=isset($_POST['buscar']) && $_POST['buscar']!=''?$_POST['buscar']:''?>">
                </div>
                <div class="col-md-3">
                    <?php 
                        echo Select2::widget([
                            'id' => 'dependencias',
                            'name' => 'dependencias',
                            'value' => isset($_POST['dependencias']) && $_POST['dependencias']!=''?$_POST['dependencias']:'',
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
                            <option value="" <?= isset($_POST['ordenado']) && $_POST['ordenado']==''?'selected':''?>>[ORDENAR POR...]</option>
                            <option value="marca" <?= isset($_POST['ordenado']) && $_POST['ordenado']=='marca'?'selected':''?>>Marca</option>
                            <option value="dependencia" <?= isset($_POST['ordenado']) && $_POST['ordenado']=='dependencia'?'selected':''?>>Dependencia</option>
                            <option value="solicitante" <?= isset($_POST['ordenado']) && $_POST['ordenado']=='solicitante'?'selected':''?>>Solicitante</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select id="forma" name="forma" class="form-control">
                            <option value="" <?= isset($_POST['forma']) && $_POST['forma']==''?'selected':''?>>[FORMA...]</option>
                            <option value="SORT_ASC" <?= isset($_POST['forma']) && $_POST['forma']=='SORT_ASC'?'selected':''?>>Ascendente</option>
                            <option value="SORT_DESC" <?= isset($_POST['forma']) && $_POST['forma']=='SORT_DESC'?'selected':''?>>Descendente</option>
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
                            'value' => isset($_POST['marca']) && $_POST['marca']!=''?$_POST['marca']:'',
                            'data' => $marcas,
                            'options' => ['multiple' => false, 'placeholder' => 'POR MARCA...'],
                            'pluginOptions' => [
                            'allowClear' => true
                            ]
                        ]);
                    ?>
                </div>

            </div>

        
    <!-- ******************************************************************************************* -->
  </div>
  <div class="panel-footer">
        <button type="button" class="btn btn-primary" onclick="excel()">
            <i class="fas fa-file-excel"></i> Descargar Busqueda en Excel
        </button>
        <button type="submit" class="btn btn-primary"  name="enviar">
            <i class="fa fa-search fa-fw"></i> Buscar
        </button>
        </form>
  </div>
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
               <th>Dependencia</th>
               <th>Ceco</th>
               <th>Cebe</th>
               <th>Marca</th>
               <th>Solicitante</th>
               <th>Material</th>
               <th>Fecha Pedido</th>
               <th>Valor</th>
               <th>Tipo</th>   
            </tr>
        </thead>
        <tbody>
        <?php foreach($rows as $rw):?>
            <tr>
                <td><?= $rw['dependencia']?></td>
                <td><?= $rw['ceco']?></td>
                <td><?= $rw['cebe']?></td>
                <td><?= $rw['marca']?></td>
                <td><?= $rw['solicitante']?></td>
                <td><?= $rw['material']?></td>
                <td><?= $rw['fecha_pedido']?></td>
                <td><?= '$ '.number_format(($rw['valor']), 0, '.', '.').' COP'?></td>
                <td>Fijo</td>
            </tr>
            <tr>
                <td><?= $rw['dependencia']?></td>
                <td><?= $rw['ceco']?></td>
                <td><?= $rw['cebe']?></td>
                <td><?= $rw['marca']?></td>
                <td><?= $rw['solicitante']?></td>
                <td><?= $rw['material']?></td>
                <td><?= $rw['fecha_pedido']?></td>
                <td><?= '$ '.number_format(($rw['valor']), 0, '.', '.').' COP'?></td>
                <td>Variable</td>
            </tr>
            <tr class="danger">
                <td colspan="6"></td>
                <td><b>Total:</b> </td>
                <td><?= '$ '.number_format(($rw['valor']+$rw['valor']), 0, '.', '.').' COP'?></td>
                <td></td>
            </tr>
        <?php endforeach;?>
        </tbody>
      </table>
  </div>
</div> 