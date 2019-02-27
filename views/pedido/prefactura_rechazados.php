<?php
use yii\widgets\LinkPager;
use yii\helpers\Url;
use kartik\widgets\Select2;
use yii\helpers\Html;

$this->title = 'Prefactura Rechazados';
?>
<?= $this->render('_tabsConsolidado',['prefactura' => 'active']) ?>
<h1><i class="glyphicon glyphicon-list-alt"></i> <?= $this->title ?></h1>
<?= Html::a('<i class="fas fa-clipboard-check"></i> Aprobados',Yii::$app->request->baseUrl.'/pedido/prefactura-aprobados',['class'=>'btn btn-primary']) ?>	
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
               <th>Solicitante</th>
               <th>Material</th>
               <th>Fecha Pedido</th>
               <th>Valor</th>
               <th>Motivo</th>
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
                <td><?= $rw['motivo']?></td>
            </tr>
            
        <?php endforeach;?>
        </tbody>
      </table>
  </div>
</div> 