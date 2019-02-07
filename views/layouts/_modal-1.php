<?php 
use app\models\Notificacion;

$date=date('Y-m-d');
$notificacion=Notificacion::find()->where(' "'.$date.'" BETWEEN fecha_inicio AND fecha_final ')->orderBy('id DESC')->all();
$count=Notificacion::find()->where(' "'.$date.'" BETWEEN fecha_inicio AND fecha_final ')->count();
?>
<!-- Modal -->

<div class="modal fade" id="myModal-notificacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-bell"></i> <span class="badge badge-info" style="background-color:blue;"><?= $count?></span> Notificaciones</h4>
      </div>
      <div class="modal-body">
        <?php $cantidad=0; foreach($notificacion as $not): ?>
        
          <div class="panel panel-primary">
            <div class="panel-heading"><i class="fa fa-commenting"></i> <?= $not->titulo ?></div>
            <div class="panel-body">
              <?= $not->descripcion  ?>
            </div>
          </div>
        
        <?php $cantidad++; endforeach;?>

        <?php 
        if ($cantidad==0) {
          echo "<h3 class='text-center'><i class='fa fa-bell-slash-o'></i> No hay notificaciones</h3>";
        }

        ?>
      </div>
      
    </div>
  </div>
</div>
