<?php
use yii\bootstrap\Modal;

$roles_usuario = array();
$roles_id = array();
if($usuario != null){
	$roles_usuario = $usuario->roles;
    foreach($roles_usuario as $key){
		$roles_id [] = $key->rol_id;
	}
}
?>
	<div class="table-responsive">
	 <div class="col-md-12">
	 <table  class="table table-hover" width="100%">
	 
       <thead>

       <tr>
           <th></th>
           <th>Fecha creación</th>
           <th>Repetido?</th>
		   <th>Dependencia</th>
		   <th>Producto</th>
		   <th>Producto Sugerido</th>
		   <th>Cantidad</th>           
		   <th>Proveedor</th>
		   <th>Nro Orden de compra</th>
		   <th>Solicitante</th>
		   <th>Cotizacion</th>
		   <th>Obs</th>
		   <th></th>
		   <th>Fecha Revisión Coordinador</th>
		   
		   <th></th>
		   <th>Fecha Revisión Técnica</th>
		   <th></th>
		   <th>Fecha Revisión Financiera</th>
		   <th>Obs Coord</th>
		   <th>Obs Técnica</th>
		   <th>Obs Financiera</th>
		   <th>Mot Rechazo</th>
		   <th>Aprobado Coordinador</th>
		   <th>Aprobado Tecnologia</th>
		   <th>Aprobado Financiera</th>
		   <th></th>   
		   
		             
       </tr>
           

       </thead>	 
	   
	   <tbody>
           
		   <?php  foreach($pendientes as $pen):?>
		   
		   <tr>
		   
				<td>
				
				 <?php
				     
					 if( in_array(1,$roles_id) || in_array(11,$roles_id) ){
						 
                        echo ' 
                             <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-'.$pen['id'].'">
                             <i class="fas fa-pencil-alt"></i>
                             </button>';

                         // echo '<img alt="Evidencia" class="img-responsive img-thumbnail" src="'.Yii::$app->request->baseUrl.$value->archivo.'"/>';
                         Modal::begin([

                          'header' => '<h4>Orden de Compra</h4>',
                          'id' => 'modal-'.$pen['id'],
                          'size' => 'modal-lg',

                          ]);

                         echo '<input name="item-'.$pen['id'].'" id="item-'.$pen['id'].'" class="form-control" value="'.$pen['id'].'"  type="hidden"/>';
						 echo '<textarea name="orden-'.$pen['id'].'" id="orden-'.$pen['id'].'" class="form-control" rows="4"></textarea>';
                         echo '<p>&nbsp;</p>';
						 echo '<input type="submit" name="guardar" value="Guardar" class="btn btn-primary btn-lg"/>';
                         Modal::end();	
					 }
				 ?>
			</td>	   
             <td><?=$pen['fecha']?></td>
             <td><?php
					  //validar repetidos;
					  if($pen['repetido']=='SI'){
						  echo '<label style="color: red;">R</label>';
					  }
					?>
			</td>
			 <td>
			 <?=$pen['dependencia']?></td>
			 <td><?=$pen['producto']?></td>
			 <td><?=$pen['psugerido']?></td>
			 <td><?=$pen['cantidad']?></td>
			 <td><?=$pen['proveedor']?></td>
			 <td><?=$pen['orden']?></td>
			 <td><?=strtoupper($pen['solicitante'])?></td>
			 <td>
			 	<?php if($pen['cotizacion']!=''){ ?>
					<!-- <a href="http://cvsc.com.co/sgs/web<?php //echo $pen['cotizacion']?>" download>
					 <i class="fa fa-download" aria-hidden="true"></i>
					</a> -->

					<a href="<?= Yii::$app->request->baseUrl.$pen['cotizacion'] ?>" download>
						<i class="fa fa-download" aria-hidden="true"></i>
					</a>
				<?php }else{ 
						echo '-';
					  }
				?>
			 </td>
			 <td><?=$pen['pobservaciones']?></td>
			 <td>
			     <?php
					if($pen['estado'] != 'P' && $pen['estado'] != 'E'){
					    if($pen['estado'] == 'R'){
							echo '<i class="fa fa-trash" aria-hidden="true"></i>';
						}else{
                          echo '<i class="fa fa-check" aria-hidden="true"></i>';
						}
					}
				 ?>
			 </td>
			 <td><?=$pen['fcoordinador']?></td>
			 <td>
			     <?php
					if($pen['estado'] != 'R' && $pen['estado'] != 'P' && $pen['estado'] != 'E' && $pen['estado'] != 'T' && $pen['estado'] != 'W'){
					    if($pen['estado'] == 'Y'){
							echo '<i class="fa fa-trash" aria-hidden="true"></i>';
						}else{
                          echo '<i class="fa fa-check" aria-hidden="true"></i>';
						}
					}
				 ?>
			 </td>
			 <td><?=$pen['ftecnica']?></td>
			 <td>
			     <?php
					if($pen['estado'] != 'P' && $pen['estado'] != 'E' && $pen['estado'] != 'T' && $pen['estado'] != 'W'
					   && $pen['estado'] != 'Y' && $pen['estado'] != 'R' && $pen['estado'] != 'F' && $pen['estado'] != 'Z' && $pen['estado'] != 'B'
					){
						if($pen['estado'] == 'V'){
							echo '<i class="fa fa-trash" aria-hidden="true"></i>';
						}else{
                          echo '<i class="fa fa-check" aria-hidden="true"></i>';
						}
					}
				 ?>
			 </td>
			 <td><?=$pen['ffinanciera']?></td>
			 <td><?=$pen['ocoordinador']?></td>
			 <td><?=$pen['otecnica']?></td>
			 <td><?=$pen['ofinanciera']?></td>
			 <td><?=$pen['mrechazo']?></td>
			  <td><?= $pen['usuario_aprobador_revision'] ?></td>
			 <td><?= $pen['usuario_aprobador_tecnica'] ?></td>
			 <td><?= $pen['usuario_aprobador_financiera'] ?></td>
			 <td>
			 	<?php 
			 	$permisos = array();
				if( isset(Yii::$app->session['permisos-exito']) ){
					$permisos = Yii::$app->session['permisos-exito'];
				}
			 	if(in_array("administrador", $permisos)){
				 	echo '<button type="button" class="btn btn-primary" onclick="eliminarPedido('.$pen['id'].')">
	                     <i class="fa fa-trash" aria-hidden="true"></i>
	                     </button>';
                }
			 	?>
			 </td>
           </tr>
           <?php endforeach;?>	
	   </tbody>
	 </table>
	</div>
</div>