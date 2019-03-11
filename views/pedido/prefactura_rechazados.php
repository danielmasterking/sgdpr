<?php
use yii\widgets\LinkPager;
use yii\helpers\Url;
use kartik\widgets\Select2;
use yii\helpers\Html;

$this->title = 'Historico Rechazados';
?>
<?= $this->render('_tabsHistorico',['historico_prefactura' => 'active']) ?>
<h1><i class="glyphicon glyphicon-list-alt"></i> <?= $this->title ?></h1>

<br>



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
               <th>Regional</th>
               <th>Proveedor</th>
               <th>Mes</th>
               <th>Ano</th>
               <th>Total Fijo</th>
               <th>Total Variable</th>
               <th>Total Servicio</th>
               <th>Solicitante</th>
               <th>Usuario Rechazo</th>
               <th>Fecha Rechazo</th>
               <th>Motivo Rechazo</th>
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
                <td><?= $rw['empresa']?></td>
                <td><?= $rw['mes']?></td>
                <td><?= $rw['ano']?></td>
                <td><?= '$ '.number_format(($rw['total_fijo']), 0, '.', '.').' COP'?></td>
                <td><?= '$ '.number_format(($rw['total_varible']), 0, '.', '.').' COP'?></td>
                <td><?= '$ '.number_format(($rw['total_mes']), 0, '.', '.').' COP'?></td>
                <td><?= $rw['usuario']?></td>
                <td><?= $rw['usuario_rechaza']?></td>
                <td><?= $rw['fecha_rechazo']?></td>
                <td><?= $rw['motivo_rechazo_prefactura']?></td>
            </tr>
            
        <?php endforeach;?>
        </tbody>
      </table>
  </div>
</div> 