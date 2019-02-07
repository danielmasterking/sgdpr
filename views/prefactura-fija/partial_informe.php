<div class="table-responsive">
<table class="table table-striped ">
	<thead>
		<tr>
			<th>Id</th>
			<th>Tipo</th>
			<th>Mes</th>
			<th>Año</th>
			<th>Dependencia</th>
			<th>Regional</th> 
			<th>Nit</th>
			<th>Empresa</th>
			<th>Estado</th>
			<th>Ftes_diurno</th>
			<th>Ftes_nocturno</th>
			<th>Total ftes</th>
			<th>Total_Servicio</th>
			<th>Valor Servicio diurno</th>
			<th>Valor Servicio nocturno</th>
			<th>Numero Factura</th>
			<th>Fecha Factura</th>
			<th>Cuenta contable</th>
			<th>ceco</th>
			<th>Ciudad</th>
			<th>Marca</th>
			<th>Servicio</th>
			<th>Puesto</th>
			<th>Cantidad servicios</th>
			<th>Horas</th>
			<th>Hora Inicio</th>
			<th>Hora Fin</th>
			<th>Lunes</th>
			<th>Martes</th>
			<th>Miercoles</th>
			<th>Jueves</th>
			<th>viernes</th>
			<th>Sabado</th>
			<th>Domingo</th>
			<th>Festivo</th>
			<th>Tipo servicio(variable)</th>
			<th>Explicacion Variable</th>
			<th>Nombre Factura</th>
			
			

		</tr>

	</thead>
	<tbody>
		<?php 


		foreach($dispositivos as $row){
		?>	
		<tr>
			<td><?= $row['id_disp'] ?></td>
			<td><?= $row['tipo'] ?></td>
			<td><?= $row['mes'] ?></td>
			<td><?= $row['ano'] ?></td>
			<td><?= $row['dependencia'] ?></td>
			<td><?php echo $row['regional'] ?></td> 
			<td><?= $row['nit'] ?></td>
			<td><?= $row['empresa_seg'] ?></td>
			<td><?= $row['estado'] ?></td>
			<td><?= $row['ftes_diurno'] ?></td>
			<td><?= $row['ftes_nocturno'] ?></td>
			<td>
			<?php 
			
				// if($row['valor_mes']<0){

				// 	echo "-".$row['ftes'] ;	
				// }else{

				// 	echo $row['ftes'] ;
				// }		

				echo $row['ftes'] ;
			?>
				
			</td>
			<td>
				<?php
					if($row['valor_total_mes']==0 || $row['valor_total_mes']==''){

						echo "$ 0 COP";
					}else{
						echo '$ '.number_format(($row['valor_total_mes']), 0, '.', '.').' COP';
					}
				?>			
			</td>

			<td>
				
				<?='$ '.number_format(($row['valor_serv_diurno']), 0, '.', '.').' COP'?>
			</td>
			<td>
				
				<?='$ '.number_format(($row['valor_serv_nocturno']), 0, '.', '.').' COP'?>
			</td>
			<td><?= $row['numero_factura'] ?></td>
			<td><?= $row['fecha_factura'] ?></td>
			<td>
				<?php
					$ceco=(string) $row['ceco'];
					$resultado = substr($ceco, 0,1);

					switch ($resultado) {
						case '3':

							echo 533505001 ;

							break;
						
						default:
							echo 523505001;
							break;
					}
				?>
				
			</td>
			<td><?= $row['ceco'] ?></td>
			<td><?= $row['ciudad'] ?></td>
			<td><?= $row['marca'] ?></td>
			<td><?= $row['servicio_disp'] ?></td>
			<td><?= $row['puesto'] ?></td>
			<td><?= $row['cantidad_servicios'] ?></td>
			<td><?= $row['horas'] ?></td>
			<td><?= $row['hora_inicio'] ?></td>
			<td><?= $row['hora_fin'] ?></td>
			<td><?= $row['lunes'] ?></td>
			<td><?= $row['martes'] ?></td>
			<td><?= $row['miercoles'] ?></td>
			<td><?= $row['jueves'] ?></td>
			<td><?= $row['viernes'] ?></td>
			<td><?= $row['sabado'] ?></td>
			<td><?= $row['domingo'] ?></td>
			<td><?= $row['festivo'] ?></td>
			<td><?= $row['tipo_servicio'] ?></td>
			<td><?= $row['explicacion'] ?></td>
			<td><?= $row['nombre_factura'] ?></td>
			


		</tr>
		<?php
			}
		?>
	</tbody>


</table>
</div>