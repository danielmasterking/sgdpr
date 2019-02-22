<?php
use yii\bootstrap\Modal;
?>
	<div class="table-responsive">
	 <table  class="table table-hover" width="100%">
	 
       <thead>

       <tr>
           
           <th>Fecha creacion pedido</th>
		   <th>Fecha Creación</th>
		   <th>Repetido?</th>
		   <th>Dependencia</th>
		   <th>CeBe</th>
           <th>Producto</th>
           <th>Observacion</th>
		   <th>Cantidad</th>
		   <th>Proveedor</th>
		   <th>Orden Compra</th>
		   <th>Código Activo</th>
		   <th>Precio Neto(UN)</th>
		   <th>Precio Total</th>
		   <th>Revision Financiera</th>
		   <th>Fecha Rev. Finan</th>
		   <th>Obs. Finan</th>
		   <th>Motivo Rechazo</th>
		   <th>Imputacion</th>
		   
           
       </tr>
           

       </thead>	 
	   
	   <tbody>
	   
             <?php foreach($pendientes as $pendiente):?>	  
			   
              <tr>			  
              	<td><?= $pendiente['Fecha_pedido']?></td> 
                <td><?= $pendiente['fecha']?></td>
                <td>
				<?php
				  if($pendiente['repetido']=='SI'){
					  echo '<label style="color: red;">R</label>';
				  }
				?>
				</td>
				<td><?= $pendiente['dependencia']?></td>
					
                <td><?= $pendiente['cebe']?></td>
				<td><?= $pendiente['producto']?></td>
				<td><?= $pendiente['observaciones']?></td>
                <td><?= $pendiente['cantidad']?></td>				
				<td><?= $pendiente['proveedor']?></td>

     			<td><?= $pendiente['orden']?></td>
     			<td><?= $pendiente['codigo_activo']?></td>
				<td><?= '$'.number_format($pendiente['precio_neto'], 0, '.', '.')?></td>
                <td><?= '$'.number_format($pendiente['precio_total'], 0, '.', '.')?></td>
                <td>
				    <?php
					    if($pendiente['estado'] == 'Z' || $pendiente['estado'] == 'C' || $pendiente['estado'] == 'O' || $pendiente['estado'] == 'I'){
					    	echo '<i class="fa fa-check" aria-hidden="true"></i>';
						} else if($pendiente['estado'] == 'V'){
                          	echo 'Rechazado';
						} else {
                          	echo '<i class="fa fa-trash" aria-hidden="true"></i>';
						}
					?>
				</td>
				<td><?= $pendiente['ffinanciera']?></td>
				<td><?= $pendiente['ofinanciera']?></td>
				<td><?= $pendiente['mrechazo']?></td>
				<td><?= $pendiente['Imputacion']?></td>

              </tr>
        	 <?php endforeach; ?>
	   </tbody>
	 
	 </table>
	</div>