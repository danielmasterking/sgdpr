<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use kartik\popover\PopoverX;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Agregar Orden de Compra Solicitud Especial';

$permisos = array();
if( isset(Yii::$app->session['permisos-exito']) ){
	$permisos = Yii::$app->session['permisos-exito'];
}


?>
    <div class="page-header">
	  <h1><small><i class="fa fa-file fa-fw"></i></small> <?= Html::encode($this->title) ?></h1>
	</div>

    <?php 

	    $flashMessages = Yii::$app->session->getAllFlashes();
	    if ($flashMessages) {
	        foreach($flashMessages as $key => $message) {
	            echo "<div class='alert alert-" . $key . " alert-dismissible' role='alert'>
	                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
	                    $message
	                </div>";   
	        }
	    }
	?>

<?= Html::a('Normales',Yii::$app->request->baseUrl.'/pedido/orden-compra',['class'=>'btn btn-primary']) ?>	

	<button type="button" class="btn btn-primary" onclick="confirmar_pedido();">
        <i class="fa fa-check" aria-hidden="true"></i>
        Confirmar pedidos
    </button>		


    <?php 

    if(in_array("administrador", $permisos)){
    ?>
    <button type="button" class="btn btn-primary" onclick="devolver();">
        <i class="fa fa-reply" aria-hidden="true"></i>
        Devolver a consolidado
    </button>

    <?php

	}
    ?>

	<div class="form-group">
		
	</div>	
  <?php $form2 = ActiveForm::begin(); ?>	
  	<div class="table-responsive">      
	 <table  class="display my-data" data-page-length='20' cellspacing="0" width="100%">
	 
       <thead>

       <tr>
           
           <th><input type="checkBox" id="checkTodos"></th>
       	   <th>Repetido?</th>
		   <th>Id_pedido</th>
		   
		   <th>Dependencia CeBe</th>
		   <th>Dependencia</th>
		   
           <th>Texto Breve</th>
		   <th>Cantidad</th>
		   
		   <th>OC/No.Solicitud</th>
           <th>Cotización</th>		   
		   <th>Fecha de Creación</th>
		   <th>
		   	<?= '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-orden-todos">
                     <i class="fas fa-edit"></i> Varios Pedidos
                      </button>';
		   		Modal::begin([
                          'header' => '<h4>1 Orden a Varios Pedidos</h4>',
                          'id' => 'modal-orden-todos',
                          'size' => 'modal-lg',
                          ]);
						 echo '<textarea name="mensaje-orden-todos" id="mensaje-orden-todos" class="form-control" rows="4"></textarea>';
                         echo '<p>&nbsp;</p>';
                         echo Html::a('Guardar', ['pedido/orden-compra-especial-todos'], ['data-method'=>'post','class' => 'btn btn-primary']);
                         Modal::end();
		   	?>
		   </th>
		   <th></th>
		   
           
       </tr>
           

       </thead>	 
	   
	   <tbody>
	   
             <?php foreach($pendientes as $pendiente):?>	  
			   
              <tr>
              	<td>
            		<?= Html::checkBox('pedidos[]',false, ['value' => $pendiente->id, 'id'=>'materiales','class'=>'micheckbox'])?>
            	</td>
                <td>
                	<?php
					  //validar repetidos;
					  if($pendiente->repetido=='SI'){
						  echo '<label style="color: red;">R</label>';
					  }
					?>
                </td>
				<td><?= $pendiente->id_pedido?></td>
				
				<td><?= $pendiente->pedido->dependencia->cebe?></td>
				<td><?= $pendiente->pedido->dependencia->nombre?></td>
				<td><?= $pendiente->maestra->texto_breve?></td>
     			<td><?= $pendiente->cantidad?></td>
                
				<td><?= $pendiente->orden_compra?></td>
				<td>
					<?php if($pendiente->archivo!=''){ ?>
						<!-- <a href="http://cvsc.com.co/sgs/web<?php //echo$pendiente->archivo?>" download>
						 <i class="fa fa-download" aria-hidden="true"></i>
						</a> -->
						<a href="<?= Yii::$app->request->baseUrl.$pendiente->archivo ?>" download>
						 <i class="fa fa-download" aria-hidden="true"></i>
						</a>
					<?php }else{ 
							echo '-';
						  }
					?>
				</td>
	
				<td><?= $pendiente->pedido->fecha?></td>
				
				
				<td>
				
				 <?php
				 
				 
                        echo ' 
                             <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-'.$pendiente->id.'">
                             <i class="fas fa-edit"></i>
                             </button>';

                         // echo '<img alt="Evidencia" class="img-responsive img-thumbnail" src="'.Yii::$app->request->baseUrl.$value->archivo.'"/>';
                         Modal::begin([

                          'header' => '<h4>Orden de Compra</h4>',
                          'id' => 'modal-'.$pendiente->id,
                          'size' => 'modal-lg',

                          ]);

                         echo '<input name="item-'.$pendiente->id.'" id="item-'.$pendiente->id.'" class="form-control" value="'.$pendiente->id.'"  type="hidden"/>';
						 echo '<textarea name="orden-'.$pendiente->id.'" id="orden-'.$pendiente->id.'" class="form-control" rows="4"></textarea>';
                         echo '<p>&nbsp;</p>';
						 echo '<input type="submit" name="guardar" value="Guardar" class="btn btn-primary btn-lg"/>';
                         Modal::end();
	 
				 ?>						
	
				</td>
				<td>
				<!-- Llamado ajax para aprobar-->
				<?php// Pjax::begin(); ?>
				  <?php //echo Html::a('<i class="fa fa-check" aria-hidden="true"></i>', ['pedido/orden-asignada-especial?id_detalle_producto='.$pendiente->id], ['class' => 'btn btn-primary']);?>
				<?php// Pjax::end(); ?>
				
				</td>				
				
              </tr>
        	 <?php endforeach; ?>			 
	   
	   </tbody>
	 
	 </table>
	 </div>
     <?php ActiveForm::end(); ?>

     <script type="text/javascript">

     	$(function(){


       		$("#checkTodos").change(function () {
			      $("input:checkbox").prop('checked', $(this).prop("checked"));
			  });


       });
     	
     	function confirmar_pedido(){
     		var check=0;

     		$('.micheckbox:checked').each(
			    function() {
					check++;			        
			    }
			);

     		if (check<1) {
     			alert('Selecciona por lo menos un pedido');
     		}else{

     			var confirmar=confirm('Seguro desea realizar esta accion?');
     			if (confirmar) {

     				$("#w0").submit();
     			}
     		}	
     	}
     	
     	function devolver(){
     		var confirmar=confirm(' Seguro desea devolver todos estos registros a consolidado?');
     		if (confirmar) {

     			location.href='<?= Yii::$app->request->baseUrl.'/pedido/devolver_consolidado_especial' ?>';
     		}else{

     			return false;
     		}
     	}
     </script>