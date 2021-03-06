<?php
use yii\helpers\Url;
$permisos = array();
if( isset(Yii::$app->session['permisos-exito']) ){
	$permisos = Yii::$app->session['permisos-exito'];
}
?>
<?php 
	if(in_array("revision-financiera", $permisos) || in_array("administrador", $permisos)){?>
<div class="btn-group dropup">
  	<button type="button" class="btn btn-primary btn-xs lock">Marcar Como...</button>
  	<button type="button" class="btn btn-primary btn-xs dropdown-toggle lock" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    	<span class="caret"></span>
    	<span class="sr-only">Toggle Dropdown</span>
  	</button>
  	<ul class="dropdown-menu">
    	<li><a href="#" onclick="cambiarGastoActivoCheckBox('gasto','normal');return false;"><i class="fas fa-dollar-sign"></i> -Gasto</a></li>
    	<li><a href="#" onclick="cambiarGastoActivoCheckBox('activo','normal');return false;"><i class="fas fa-chart-line"></i> +Activo</a></li>
  	</ul>
</div>

<div class="btn-group dropup">
  	<button type="button" class="btn btn-primary btn-xs lock">Marcar sistema...</button>
  	<button type="button" class="btn btn-primary btn-xs dropdown-toggle lock" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    	<span class="caret"></span>
    	<span class="sr-only">Toggle Dropdown</span>
  	</button>
  	<ul class="dropdown-menu">
  		<?php foreach($sistemas as $st): ?>
    	<li><a href="#" onclick="AsignarSistemaTodos('normal','<?= $st->sistema->nombre?>');"><i class="fas fa-video"></i> <?= $st->sistema->nombre?></a></li>
        <?php endforeach; ?>
    	
  	</ul>
</div>

<button class="btn btn-success btn-xs" onclick="MarcarEnviadoTodos('N');"><i class="fa fa-thumbs-up"></i> GE-OC-Varios</button>
<?php } ?>
<div class="table-responsive">
	<table  class="table table-hover" width="100%">
		<thead>
			<tr>
				<th style="width: 5%;">
					<input type="checkbox" class="checkBoxAll"/>
				</th>
				<th style="width: 5%;">Area</th>
				<th style="width: 15%;">Material</th>
				<th>Sistema</th>
				<th style="width: 10%;">Prov</th>
				<th style="width: 5%;">Cant</th>
				<?php 
					if(in_array("revision-financiera", $permisos) || in_array("administrador", $permisos)){?>
						<th style="width: 20%;">Precio Neto</th>
				<?php } ?>
				<th style="width: 10%;">GE-OC</th>
				<th style="width: 10%;">Solicita</th>
				<th style="width: 10%;">Fecha</th>
				<?php 
					if(in_array("revision-financiera", $permisos) || in_array("administrador", $permisos) || in_array("coordinador", $permisos)){?>
				<th style="width: 35%;">Acciones</th>
				<?php 	}?>
			</tr>
		</thead>
		<tbody>
		<?php foreach($model as $key):?>
			<tr>
				<td>
					<input type="checkbox" class="checkBoxid" id="<?=$key['id']?>"/>
				</td>
				<td><?= ucwords($key['tipo_presupuesto'].' - '.$key['gasto_activo'])?></td>
				<td>
				<?php
					if($key['repetido']=='SI'){
						echo '<label style="color: red;">R</label>';
					}
				?>
				<?= $key['codigo'].'-'.$key['material']?></td>
				<td><?= $key['sistema']?></td>
				<td><?= $key['proveedor']?></td>
				<td><?= $key['cantidad']?></td>
				<?php 
					if(in_array("revision-financiera", $permisos) || in_array("administrador", $permisos)){?>
						<td><?='$ '.number_format($key['precio_neto'], 0, '.', '.').' COP'?></td>
				<?php } ?>
				<td>
					<?php 
						if($key['ge-oc']==true){
							echo"<label class='label label-success'>Enviado</label>";
						}else{
							echo"<label class='label label-danger'>Pendiente</label>";
						}
					?>
				</td>
				<td><?= $key['solicitante']?></td>
				<td><?= $key['fecha']?></td>
				
				<td>
				<?php if(in_array("coordinador", $permisos) || in_array("administrador", $permisos)){?>
					<?php 
						    if($estado==1){?>
								<button class="btn btn-primary lock btn-xs" data-toggle="modal" data-target="#modal-no-aprobado" onclick="setDataNa(2,<?=$key['id']?>,'normal','<?=$key['cantidad']?>')"><i class="fa fa-thumbs-down"></i> No Aprobar</button>
					<?php 	}else if($estado==2){?>
								<button class="btn btn-primary lock btn-xs" onclick="cambiarEstado(1,<?=$key['id']?>,'normal','<?=$key['cantidad']?>')"><i class="fa fa-thumbs-up"></i> Aprobar</button>
					<?php 	}?>

					<?php if($key['ge-oc']==false){ ?>
					<button class="btn btn-success btn-xs" onclick="MarcarEnviado(<?=$key['id']?>,'N');"><i class="fa fa-thumbs-up"></i> GE-OC</button>
					<?php } ?>
				<?php 	}?>
					<?php if(in_array("revision-financiera", $permisos) || in_array("administrador", $permisos)){?>
						<div class="btn-group dropup">
						  	<button type="button" class="btn btn-primary btn-xs lock">Marcar Como...</button>
						  	<button type="button" class="btn btn-primary btn-xs dropdown-toggle lock" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    	<span class="caret"></span>
						    	<span class="sr-only">Toggle Dropdown</span>
						  	</button>
						  	<ul class="dropdown-menu">
						    	<li><a href="#" onclick="cambiarGastoActivo('gasto',<?=$key['id']?>,'normal');return false;"><i class="fas fa-dollar-sign"></i> -Gasto</a></li>
						    	<li><a href="#" onclick="cambiarGastoActivo('activo',<?=$key['id']?>,'normal');return false;"><i class="fas fa-chart-line"></i> +Activo</a></li>
						  	</ul>
						</div>

						<div class="btn-group dropup">
						  	<button type="button" class="btn btn-primary btn-xs lock">Marcar sistema...</button>
						  	<button type="button" class="btn btn-primary btn-xs dropdown-toggle lock" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    	<span class="caret"></span>
						    	<span class="sr-only">Toggle Dropdown</span>
						  	</button>
						  	<ul class="dropdown-menu">
						  		<?php foreach($sistemas as $st1): ?>
						    	<li><a href="#" onclick="AsignarSistema('<?= $st1->sistema->nombre?>',<?=$key['id']?>,'normal');"><i class="fas fa-video"></i> <?= $st1->sistema->nombre?></a></li>
						        <?php endforeach; ?>
						    	
						  	</ul>
						</div>
					<?php } ?>
				</td>
				
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>