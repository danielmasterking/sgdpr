<?php 

use kartik\widgets\Select2;
?>
<!-- Modal -->
<div class="modal fade" id="Modal-Puesto"  role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Puesto</h4>
      </div>
      <div class="modal-body">
      	 <form action='' method="POST" id='form_puesto'>
       <?php 
       		echo Select2::widget([
			    'name' => 'puestos',
			    'data' => $puestos,
			    'options' => [
			        'placeholder' => 'Seleciona un Puesto ...',
			        'required'=>true
			        //'multiple' => true
			    ],
			]);


       ?>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
      </div>
    </div>
  </div>
</div>