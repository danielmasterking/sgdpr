<?php
use yii\helpers\Url;

$roles_usuario = array();
$roles_id = array();
if($usuario != null){
	$roles_usuario = $usuario->roles;
    foreach($roles_usuario as $key){
		$roles_id [] = $key->rol_id;
	}
}

$permisos = array();
if( isset(Yii::$app->session['permisos-exito']) ){
	$permisos = Yii::$app->session['permisos-exito'];
}
?>
<div class="col-md-12">
	<div class="table-responsive">
 	<table  class="table table-hover" width="100%">
       	<thead>
       		<tr>
       			<th style="text-align: center;">
       				<?php if (in_array("administrador", $permisos) or in_array("habilitar-prefactura", $permisos)): ?>
       				<input type="checkbox" id="todos">
       				<?php endif ?>
       			</th>
       			<th style="text-align: center;">Ver</th>
       			<th style="text-align: center;">Id</th>
       			<th style="text-align: center;">Numero factura</th>
       			<th style="text-align: center;">Fecha factura</th>
       			<th style="text-align: center;">Nombre factura</th>
	           	<th style="text-align: center;">Fecha creación</th>
	           	<th style="text-align: center;">Mes</th>
			   	<th style="text-align: center;">Año</th>
			   	<th style="text-align: center;">Usuario</th>
			   	<th style="text-align: center;">Regional</th>
			   	<th style="text-align: center;">Marca</th>
			   	<th style="text-align: center;">Ciudad</th>
			   	<th style="text-align: center;">Ceco</th>
			   	<th style="text-align: center;">Dependencia</th>           
			   	<th style="text-align: center;">Nit</th>
			   	<th style="text-align: center;">Empresa</th>
			   	<th style="text-align: center;">FTES Fijos</th>
			   	<th style="text-align: center;">FTES Variables</th>
			   	<th style="text-align: center;">Total de FTES</th>
			   	<th style="text-align: center;">Total Fijo</th>
			   	<th style="text-align: center;">Total Variable</th>
			   	<th style="text-align: center;">Total del Servicio</th>
			   	<th></th>
       		</tr>
       	</thead>
	   	<tbody>
		   	<?php  
		   		$contador=0;
		   		foreach($prefacturas as $pref):
		   	?>
		   	<tr style="text-align: center;">
		   		<td>
		   			<?php if($pref['estado']=='cerrado'){?>
		   				<?php if (in_array("administrador", $permisos) or in_array("habilitar-prefactura", $permisos)): ?>
		   				<input type="checkbox" name="seleccion[]" class="check" value="<?= $pref['id']?>">
		   				<?php endif ?>
		   			<?php }?>
		   		</td>
		   		<td>
		   			<?php if($pref['estado']=='abierto'){?>
			   		<a href="<?php echo Url::toRoute('prefactura-fija/view?id='.$pref['id'])?>" class="btn btn-primary btn-xs">
				        <i class="fas fa-folder-open"></i> Abrir
				    </a>

				    <a href="<?php echo Url::toRoute('prefactura-fija/imprimir?id='.$pref['id'])?>" class="btn btn-danger btn-xs" >
					    <i class="far fa-file-pdf"></i> PDF
					</a>
				    <?php }?>
				    <?php if($pref['estado']=='cerrado'){?>
					    <a href="<?php echo Url::toRoute('prefactura-fija/imprimir?id='.$pref['id'])?>" class="btn btn-danger btn-xs" >
					        <i class="far fa-file-pdf"></i> PDF
					    </a>

					    <?php if (in_array("administrador", $permisos) or in_array("habilitar-prefactura", $permisos)): ?>
					    	<a data-confirm='Seguro desea habilitar esta prefactura' href="<?php echo Url::toRoute('prefactura-fija/abrir_pref?id='.$pref['id'])?>" class="btn btn-success btn-xs" target="_blank">
					        	<i class="fas fa-thumbs-up"></i> Habilitar
					    	</a>
					    <?php endif ?>
				    <?php }?>
				    <?php /*if($pref['estado_pedido']!='G' && $pref['estado_pedido']!='L' && $pref['estado_pedido']!='S' && $pref['estado_pedido']!='Z' && $pref['estado_pedido']!='R'){?>
				    	<a data-confirm='Seguro desea enviar a aprobacion' href="<?php echo Url::toRoute('prefactura-fija/enviar_aprobacion_gerente?id='.$pref['id'])?>" class="btn btn-info btn-xs" target="_blank">
				        	<i class="fas fa-check"></i> Aprobacion
				    	</a>
				    <?php } */?>
				    <?php /*if($pref['estado_pedido']=='G' || $pref['estado_pedido']=='L'  || $pref['estado_pedido']=='Z' ){?>
				    	<label class="label label-warning">...En proceso</label>
					<?php } */?>
				    <?php /*if($pref['estado_pedido']=='S'){?>
				    	<label class="label label-success">Aprobado <?= $pref['fecha_aprobacion']?></label>
				    <?php } */?>
				    <?php /*if($pref['estado_pedido']=='R'){?>
				    	<label class="label label-danger">Rechazada <?= $pref['fecha_rechazo']?> 
				    	 <a href="javascript:void(0)" title="Motivo rechazo" style="color:white;font-size: 13px;" data-toggle="modal" data-target="#myModal" onclick="motivo_rechazo('<?= $pref['motivo_rechazo_prefactura'] ?>');"><i class="fa fa-eye"></i></a>
				        </label>
				    <?php } */?>

			    </td>
			    <?php 
			    	//AQUI SE CALCULA EL VALOR TOTAL DE FTES Y EL VALOR TOTAL DEL SERVICIO
			    	$dispositivos = $model_dispositivo->find()->where('id_prefactura_fija='.$pref['id'])->all();
			    	$total_ftes_fijos = 0;
			    	$total_ftes_variable = 0;
			    	$total_servicio_fijo = 0;
			    	$total_servicio_variable = 0;
			    	foreach ($dispositivos as $value) {
			    		if($value->tipo=='fijo'){
			    			$total_ftes_fijos = $total_ftes_fijos + $value->ftes;
			    			$total_servicio_fijo = $total_servicio_fijo + $value->valor_mes;
			    		}elseif($value->tipo=='variable'){
			    			if($value->tipo_servicio !='No Prestado'){

                               $total_ftes_variable = $total_ftes_variable + $value->ftes;
                               $total_servicio_variable = $total_servicio_variable + $value->valor_mes;

                            }else{

                               $total_ftes_variable = $total_ftes_variable - $value->ftes;
                               $total_servicio_variable = $total_servicio_variable - $value->valor_mes;
                               
                            }
			    		}

			    	}

			    ?>
			    <td><?=$pref['id']?></td>
			    <td><?=$pref['numero_factura']?></td>
			    <td><?=$pref['fecha_factura']?></td>
			    <td>
			    	<?php

			    		echo $pref['nombre_factura']==''?'No aplica':$pref['nombre_factura'];
			    	?>
			    	
			    </td>
         		<td><?=$pref['fecha']?></td>
		 		<td><?=$pref['mes']?></td>
		 		<td><?=$pref['ano']?></td>
		 		<td><?=$pref['usuario']?></td>
		 		<td><?=$pref['regional']?></td>
		 		<td><?=$pref['marca']?></td>
		 		<td><?=$pref['ciudad']?></td>
		 		<td><?=$pref['ceco']?></td>
		 		<td><?=$pref['dependencia']?></td>
		 		<td><?=$pref['nit']?></td>
		 		<td><?=$pref['empresa']?></td>
		 		<td><?=$pref['ftes_fijos']?></td>
		 		<td><?=$pref['ftes_variables']?></td>
		 		<td><?=($total_ftes_fijos+$total_ftes_variable)?></td>
		 		<td><?='$ '.number_format($total_servicio_fijo, 0, '.', '.').' COP'?></td>
		 		<td><?='$ '.number_format($total_servicio_variable, 0, '.', '.').' COP'?></td>
		 		<td><?='$ '.number_format(($total_servicio_fijo+$total_servicio_variable), 0, '.', '.').' COP'?></td>
			 	<td>

			 		<?php 
			 		
				 	//if(in_array("administrador", $permisos)){
						// if($pref['estado']=='abierto' and $pref['usuario']==Yii::$app->session['usuario-exito']){
					 // 		echo '<button type="button" class="btn btn-primary btn-xs" onclick="eliminar('.$pref['id'].')">
			   //                   <i class="fa fa-remove" aria-hidden="true"></i>
			   //                   </button>';
			   //          }else{	
						
			            	if(in_array("administrador", $permisos) or in_array("eliminar_prefactura", $permisos)){
			            		echo '<button type="button" class="btn btn-primary btn-xs" onclick="eliminar('.$pref['id'].')">
			                     <i class="fa fa-trash" aria-hidden="true"></i>
			                     </button>';
			            	}elseif($pref['estado']=='abierto' and $pref['usuario']==Yii::$app->session['usuario-exito'] and !in_array("prefactura-regional", $permisos)){
			            		echo '<button type="button" class="btn btn-primary btn-xs" onclick="eliminar('.$pref['id'].')">
			                     <i class="fa fa-trash" aria-hidden="true"></i>
			                     </button>';
			            	}

			            //}
	                 //}
			 		?>
			 	</td>
           	</tr>
           <?php 
           	$contador++;
           	endforeach;
           ?>

           <?php if($contador==0): ?>
           	<tr>
           		<td colspan="20">
           			<i class="fa fa-frown-o" aria-hidden="true"></i> No se encontro ningun resultado
           		</td>
           	</tr>
           <?php endif;?>
	   	</tbody>
 	</table>
 </div>
</div>