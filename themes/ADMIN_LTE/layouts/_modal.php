<?php 
use app\models\Notificacion;
use app\models\Usuario;

$date=date('Y-m-d');

?>
<!-- Modal -->
<div class="modal fade" id="myModal-notificacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-bell"></i> <span class="badge badge-info info-notificacion" style="background-color:blue;" id="total_notificaciones_modal"><?= $count?></span> Notificaciones - <?= $date?></h4>
      </div>
      <div class="modal-body" id="body-notificacion">
          
        
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active">
            <a href="#body_not_mensaje" aria-controls="body_not_mensaje" role="tab" data-toggle="tab">
               <i class="fas fa-envelope text-aqua"></i> <span class="badge badge-info info-notificacion" style="background-color:blue;" id="total_not_mensajes"><?= $count1?></span> Mensajes 
            </a>
          </li>
          <li role="presentation">
            <a href="#body_not_pedido" aria-controls="body_not_pedido" role="tab" data-toggle="tab">
              <i class="fas fa-shopping-cart text-warning"></i> <span class="badge badge-info info-notificacion" style="background-color:blue;" id="total_not_pedido"><?= $count2?></span> Pedidos 
            </a>
          </li>
        </ul>

        <br>
        <div class="tab-content">
          <div role="tabpanel" class="tab-pane active info-notificacion" id="body_not_mensaje">
            
          </div>
          <div role="tabpanel" class="tab-pane info-notificacion" id="body_not_pedido">
            
          </div>
          </div>
         
        </div>
        
      </div>
      
    </div>
  </div>
</div>