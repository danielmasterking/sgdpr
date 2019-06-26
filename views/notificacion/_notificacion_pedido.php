
<?php 
use yii\helpers\Url;
?>

<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="panel panel-primary notificacion-head">
                
            <div class="panel-collapse " id="collapseOne">
                <div class="notificacion-panel panel-body">
                    <ul class="chat">
                       <?php $cantidad=0; foreach($notificacion2 as $not): ?>
                       <?php if($not->tipo=='C'): ?>
                       <a href="<?php echo Url::to(['notificacion/view','id'=> $not->id])?>" title="Ver notificacion" style="text-decoration: none;">
                        <li class="left clearfix"><span class="chat-img pull-left">
                            <img src="<?php echo $this->theme->baseUrl; ?>/dist/img/Grupo exito 50x 50.jpg" alt="User Avatar" class="img-circle" />
                        </span>
                            <div class="chat-body clearfix">
                                <div class="header">
                                    <strong class="primary-font"><?= $not->titulo ?></strong> <small class="pull-right text-muted">
                                        <span class="glyphicon glyphicon-time"></span><?= $not->fecha_inicio ?></small>

                                        <input type="checkbox" title="Marcar como leido" onclick="leido(<?= $not->id?>,'<?=Yii::$app->session['usuario-exito']?>');" class="checkbox">
                                </div>
                                <p>
                                    <i class="fas fa-info-circle text-red"></i> Notificacion de aprobacion del modulo de revision de pedidos diaria.
                                </p>
                            </div>
                        </li>
                        </a>
                       <?php 
                          $cantidad++; 
                            endif;
                          endforeach;
                        ?>
                        <?php 
                        if ($cantidad==0) {
                          echo "<li class='left clearfix'><h4 class='text-center'><i class='fas fa-bell-slash'></i> No hay notificaciones</h4></li>";
                        }

                        ?>
                    </ul>
                </div>
                <div class="panel-footer">
                    <!-- <div class="input-group">
                        <input id="btn-input" type="text" class="form-control input-sm" placeholder="Type your message here..." />
                        <span class="input-group-btn">
                            <button class="btn btn-warning btn-sm" id="btn-chat">
                                Buscar</button>
                        </span>
                    </div> -->
                    <a href="<?php echo Url::toRoute('notificacion/listado-notificaciones')?>" class="text-center">
                      <h4><i class="far fa-arrow-alt-circle-right"></i> Ver todas las notificaciones....</h4>
                    </a>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>

 


       
         