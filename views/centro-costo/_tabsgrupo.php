<?php 
use yii\helpers\Html;
?>

<div style="display: none;" id="tab_grupo">

  <!-- Nav tabs -->

  <ul class="nav nav-tabs" role="tablist">
    <?php 
    $i=0;
    foreach ($grups as $key => $value) {
      
    ?>
    <li role="presentation"  class='<?php  echo $i==0?'active':''  ?>' ><a href="#<?php echo $key ?>" aria-controls="<?php echo $key ?>" role="tab" data-toggle="tab"><?php echo $value ?></a></li>
    <?php 
    $i++;
    }
    ?>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">


    <?php 
    $j=0;

    foreach ($grups as $key1 => $value1) {
     
    ?>
    <div role="tabpanel"  id="<?php echo $key1 ?>" class='tab-pane <?php  echo $j==0?' active':''  ?>'>
      
    <?php  
    $consulta=$model->find()->where('id_grupo='.$key1)->all();

    ?>

    <br>
    <?= Html::a('<i class="glyphicon glyphicon-plus"></i> Nuevo Dispositivo Fijo',Yii::$app->request->baseUrl.'/centro-costo/create-modelo?id='.$codigo_dependencia."&grupo=".$key1,['class'=>'btn btn-primary']) ?>

    <table class = "table table-hover">
    <thead>
    <tr>
      <th style="width: 5%;"></th>
        <th style="width: 12%;">$/Mes</th>     
        <th style="width: 15%;">Servicio</th>
        <th style="width: 7%;">Puesto</th>
        <th style="width: 3%;">Cant</th>
        <th style="width: 5%;">Jornada (Horas)</th>
        <th style="width: 5%;">Desde</th>
        <th style="width: 5%;">Hasta</th>
        <th style="width: 1%;">L</th>
        <th style="width: 1%;">M</th>
        <th style="width: 1%;">M</th>
        <th style="width: 1%;">J</th>
        <th style="width: 1%;">V</th>
        <th style="width: 1%;">S</th>
        <th style="width: 1%;">D</th>
        <th style="width: 1%;">F</th>    
        <th style="width: 3%;">% de Cobro</th>
        <th style="width: 3%;">FTES</th>
        <th style="width: 3%;">Total Días</th>
        <th style="width: 3%;"></th>
    </tr>
    </thead>
    <tbody id = "lastRow">
      <?php  
      $total_ftes = 0;
      $total_ftes_diurno = 0;
      $total_ftes_nocturno = 0;
    $total_servicio = 0;
      foreach($consulta as $value):?>
        <tr>
          <td></td>
          <td>
            <?='$ '.number_format($value->valor_mes, 0, '.', '.').' COP'?>
          </td>
          <td><?=$value->servicio->servicio->nombre.'-'.$value->servicio->descripcion?></td>
          <td><?=$value->puesto->nombre?></td>
          <td><?=$value->cantidad_servicios?></td>
          <td>
          <?php
            $date = new DateTime($value->horas);
          echo $date->format('H:i');
        ?>
        </td>
          <td>
          <?php
            $date = new DateTime($value->hora_inicio);
          echo $date->format('H:i');
        ?>
        </td>
          <td>
          <?php
            $date = new DateTime($value->hora_fin);
          echo $date->format('H:i');
        ?>
          </td>
          <td><?=$value->lunes?></td>
          <td><?=$value->martes?></td>  
          <td><?=$value->miercoles?></td>
          <td><?=$value->jueves?></td>
          <td><?=$value->viernes?></td>
          <td><?=$value->sabado?></td>
          <td><?=$value->domingo?></td>
          <td><?=$value->festivo?></td>
          <td><?=$value->porcentaje?></td>
          <td><?=$value->ftes?></td>        
          <td><?=$value->total_dias?></td>
          <td>
          <?php
            $total_ftes = $total_ftes + $value->ftes;
            $total_ftes_diurno = $total_ftes_diurno + $value->ftes_diurno;
            $total_ftes_nocturno = $total_ftes_nocturno + $value->ftes_nocturno;
          $total_servicio = $total_servicio + $value->valor_mes;
          echo Html::a('<i class="fa fa-remove"></i>',Yii::$app->request->baseUrl.'/centro-costo/delete-renglon?id='.$value->id.'&dependencia='.$codigo_dependencia,['data-method'=>'post']);
          ?>
          

          </td>
      </tr>
    <?php endforeach;?>
      <tr>
        <td><strong>TOTAL: </strong></td>     
        <td>
          <?='$ '.number_format($total_servicio, 0, '.', '.').' COP'?>
        </td>         
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
        <td><strong><?=$total_ftes?></strong></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td><strong>Ftes diurnos: </strong></td>      
        <td>
          <?=$total_ftes_diurno?>
        </td>         
        <td></td>
        <td><strong>Ftes nocturnos: </strong></td>
        <td>
          <?=$total_ftes_nocturno?>
        </td>
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
    </tbody>

    </table>

    </div>

    <?php 
    $j++;
    }
    ?>
  </div>

</div>










