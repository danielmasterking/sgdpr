<?php 
use app\models\Notificacion;
use app\models\Usuario;

$date=date('Y-m-d');
$usuario = Usuario::findOne(Yii::$app->session['usuario-exito']);
$zonasUsuario = $usuario->zonas;

$in_zona=" IN(";
$contador=0;
foreach ($zonasUsuario as $value) {
  $in_zona.=" '".$value->zona_id."',";
  $contador++;  
}

if($contador!=0){
  $in_final = substr($in_zona, 0, -1).")";
}else{
  $in_final = " IN('')";
}   


$notificacion=Notificacion::find()
->leftJoin('notificacion_zona', ' notificacion_zona.id_notificacion= notificacion.id')
->leftJoin('notificacion_usuario', ' notificacion_usuario.id_notificacion= notificacion.id')
->where(' ( notificacion.fecha_final >= "'.$date.'" ) and (notificacion_zona.id_zona '.$in_final.' or notificacion_usuario.usuario IN("'.Yii::$app->session['usuario-exito'].'") )')
->orderBy('id DESC')
->all();

$count=Notificacion::find()
->leftJoin('notificacion_zona', ' notificacion_zona.id_notificacion= notificacion.id')
->leftJoin('notificacion_usuario', ' notificacion_usuario.id_notificacion= notificacion.id')
->where(' ( notificacion.fecha_final >= "'.$date.'" ) and (notificacion_zona.id_zona '.$in_final.' or notificacion_usuario.usuario IN("'.Yii::$app->session['usuario-exito'].'") )')
->groupBy('notificacion.id')
->count();
?>
<!-- Modal -->
<div class="modal fade" id="myModal-notificacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-bell"></i> <span class="badge badge-info" style="background-color:blue;"><?= $count?></span> Notificaciones - <?= $date?></h4>
      </div>
      <div class="modal-body">
          

        <?php $cantidad=0; foreach($notificacion as $not): ?>

          <div class="panel panel-primary">
            <div class="panel-heading"><i class="fa fa-commenting"></i> <?= $not->titulo ?></div>
            <div class="panel-body">

              <p><?php echo $not->descripcion  ?></p>
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