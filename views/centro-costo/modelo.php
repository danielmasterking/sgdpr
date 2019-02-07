<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;

$this->title = 'Configuracion de Dispositivo Fijo';
?>
<div class="row">
	<div class="col-md-12">
		<?= $this->render('_tabsDependencia',['codigo_dependencia' => $codigo_dependencia,'modelo_prefactura' => $modelo_prefactura]) ?>
	</div>
</div>
<br>
<!-- <div class="row"> -->
	<!-- <div class="col-md-2"> -->
<?= Html::a('<i class="fa fa-arrow-left"></i> Volver a Pre-facturas',Yii::$app->request->baseUrl.'/centro-costo/prefacturas?id='.$codigo_dependencia,['class'=>'btn btn-primary']) ?>
	<!-- </div> -->

	<!-- <div class="col-md-2"> -->
		<button class="btn btn-primary" data-toggle="modal" data-target="#myModal">
			<i class="fa fa-plus"></i> Crear Grupo
		</button>
	<!-- </div> -->

	<!-- <div class="col-md-2" id="ver_grupo"> -->
		<button class="btn btn-primary" onclick="ver_grupos();" id="ver_grupos">
			<i class="fa fa-archive"></i> Ver Grupos
		</button>
	<!-- </div >-->

	<!-- <div class="col-md-2" style="display: none;" id="ver_dispositivos" onclick="ver_dispositivos();"> -->
		<button class="btn btn-primary"  onclick="ver_dispositivos();" style="display: none;" id="ver_dispositivos" >
			<i class="fa fa-user-secret"></i> Ver Dispositivos
		</button>
	<!-- </div> -->

<!-- </div> -->
<br>
<h1 style="text-align: center;"><?= Html::encode($this->title) ?></h1>

<?php 

    $flashMessages = Yii::$app->session->getAllFlashes();

    if ($flashMessages) {
    	echo "<br><br><div class='row'>";
        foreach($flashMessages as $key => $message) {
            echo "<div class='alert alert-" . $key . " alert-dismissible' role='alert'>
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                    $message
                </div>";   
        }

        echo "</div>";
    }
?>
<?= $this->render('_tabsgrupo',['grups' => $list_grupos,'model'=>$model,'codigo_dependencia'=>$codigo_dependencia]) ?>

<div id='all_disp'>
<div class="row">
	<div class="col-md-12">
<?= Html::a('<i class="glyphicon glyphicon-plus"></i> Nuevo Dispositivo Fijo',Yii::$app->request->baseUrl.'/centro-costo/create-modelo?id='.$codigo_dependencia,['class'=>'btn btn-primary']) ?>
	</div>
</div>

<table class = "table table-hover">
  	<thead>
		<tr>
			<th style="width: 5%;"></th>
	   		<th style="width: 12%;">$/Mes</th>	   
	   		<th style="width: 15%;">Servicio</th>
	   		<th style="width: 7%;">Puesto</th>
	   		<th style="width: 3%;">Cant</th>
	   		<th style="width: 5%;">Jornada (Horas)</th>
	   		<th style="width: 5%;">Desde</th>
	   		<th style="width: 5%;">Hasta</th>
	   		<th style="width: 1%;">L</th>
	   		<th style="width: 1%;">M</th>
	   		<th style="width: 1%;">M</th>
	   		<th style="width: 1%;">J</th>
	   		<th style="width: 1%;">V</th>
	   		<th style="width: 1%;">S</th>
	   		<th style="width: 1%;">D</th>
	   		<th style="width: 1%;">F</th>	   
	   		<th style="width: 3%;">% de Cobro</th>
	   		<th style="width: 3%;">FTES DIURNO</th>
	   		<th style="width: 3%;">FTES NOCTURNO</th>
	   		<th style="width: 3%;">FTES</th>
	   		<th style="width: 3%;">Total Días</th>
	   		<th style="width: 3%;">Grupo</th>
	   		<th style="width: 3%;"></th>
		</tr>
  	</thead>
  	<tbody id = "lastRow">
     	<?php  
     	$total_ftes = 0;
     	$total_ftes_diurno = 0;
     	$total_ftes_nocturno = 0;
		$total_servicio = 0;
     	foreach($filas_modelo as $value):?>
		    <tr>
		    	<td></td>
			  	<td>
			  		<?='$ '.number_format($value->valor_mes, 0, '.', '.').' COP'?>
			  	</td>
			  	<td><?=$value->servicio->servicio->nombre.'-'.$value->servicio->descripcion?></td>
			  	<td><?=$value->puesto->nombre?></td>
			  	<td><?=$value->cantidad_servicios?></td>
			  	<td>
			  	<?php
				  	$date = new DateTime($value->horas);
					echo $date->format('H:i');
				?>
				</td>
			  	<td>
			  	<?php
				  	$date = new DateTime($value->hora_inicio);
					echo $date->format('H:i');
				?>
				</td>
			  	<td>
			  	<?php
				  	$date = new DateTime($value->hora_fin);
					echo $date->format('H:i');
				?>
			  	</td>
			  	<td><?=$value->lunes?></td>
			  	<td><?=$value->martes?></td>	
			  	<td><?=$value->miercoles?></td>
			  	<td><?=$value->jueves?></td>
			  	<td><?=$value->viernes?></td>
			  	<td><?=$value->sabado?></td>
			  	<td><?=$value->domingo?></td>
			  	<td><?=$value->festivo?></td>
			  	<td><?=$value->porcentaje?></td>
			  	<td><?=$value->ftes_diurno?></td>
			  	<td><?=$value->ftes_nocturno?></td>
			  	<td><?=$value->ftes?></td>			  
			  	<td><?=$value->total_dias?></td>
			  	<td>
			  		<?php
			  			if($value->grupo->nombre==''){

			  				echo "No Asignado";
			  			}else{

							echo $value->grupo->nombre;			  				
			  			}
			  		?>
			  			
			  			
			  	</td>
			  	<td>
			  	<?php
			    	$total_ftes = $total_ftes + $value->ftes;
			    	$total_ftes_diurno = $total_ftes_diurno + $value->ftes_diurno;
			    	$total_ftes_nocturno = $total_ftes_nocturno + $value->ftes_nocturno;
					$total_servicio = $total_servicio + $value->valor_mes;
					echo Html::a('<i class="fa fa-trash"></i>',Yii::$app->request->baseUrl.'/centro-costo/delete-renglon?id='.$value->id.'&dependencia='.$codigo_dependencia,['data-method'=>'post','class'=>'btn btn-danger btn-sm']);
			  	?>
			  	<a title="Asignar Grupo" onclick="asignar(<?= $value->id ?>,'<?= $codigo_dependencia?>');" class="btn btn-primary btn-sm">
			  		
			  		<i class="fa fa-archive" aria-hidden="true"></i>
			  	</a>
			  	</td>
			</tr>
	 	<?php endforeach;?>
	    <tr>
		  	<td><strong>TOTAL: </strong></td>		  
		  	<td>
		  		<?='$ '.number_format($total_servicio, 0, '.', '.').' COP'?>
		  	</td>				  
		  	<td></td>
		  	<td></td>
		  	<td></td>
		  	<td></td>
		  	<td></td>
		  	<td></td>
		  	<td></td>
		  	<td></td>	
		  	<td></td>
		  	<td></td>
		  	<td></td>
		  	<td></td>
		  	<td></td>
		  	<td></td>
		  	<td></td>
		  	<td></td>
		  	<td></td>
		  	<td><strong><?=$total_ftes?></strong></td>
		  	<td></td>
		  	<td></td>
		</tr>
		<tr>
		  	<td><strong>Ftes diurnos: </strong></td>		  
		  	<td>
		  		<?=$total_ftes_diurno?>
		  	</td>				  
		  	<td></td>
		  	<td><strong>Ftes nocturnos: </strong></td>
		  	<td>
		  		<?=$total_ftes_nocturno?>
		  	</td>
		  	<td></td>
		  	<td></td>
		  	<td></td>
		  	<td></td>
		  	<td></td>	
		  	<td></td>
		  	<td></td>
		  	<td></td>
		  	<td></td>
		  	<td></td>
		  	<td></td>
		  	<td></td>
		  	<td></td>
		  	<td></td>
		  	<td></td>
		</tr>
  	</tbody>
</table>
</div>


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Nuevo Grupo</h4>
      </div>
      <div class="modal-body">
        <?php $form = ActiveForm::begin(['id'=>'form_create','action'=>Yii::$app->request->baseUrl.'/centro-costo/guardar_grupo?id='.$codigo_dependencia]); ?>

        <?= $form->field($grupos, 'nombre')->textInput() ?>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
        <?php ActiveForm::end(); ?>
      </div>
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Asignar Grupo</h4>
      </div>
      <div class="modal-body">

      <form action='' method="POST" id='form_asignar'>
       <label>Grupos</label>
       <?php 
       		echo Select2::widget([
			    'name' => 'grupo',
			    'data' => $list_grupos,
			    'options' => [
			        'placeholder' => 'Seleciona un grupo ...',
			        'required'=>true
			        //'multiple' => true
			    ],
			]);


       ?>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
	
	function asignar(id,codigo){
		
		$('#myModal1').modal("show");

		var action="<?php echo Yii::$app->request->baseUrl . '/centro-costo/asignar_grupo'; ?>?disp="+id+"&codigo="+codigo;

		$('#form_asignar').attr({
			action: action,
			
		});

	}


	function ver_grupos(){

		$('#tab_grupo').show('slow/400/fast', function() {
			
		});

		$('#ver_grupos').hide('slow/400/fast', function() {
			
		});

		$('#ver_dispositivos').show('slow/400/fast', function() {
			
		});

		$('#all_disp').hide('slow/400/fast', function() {
			
		});
	}

	function ver_dispositivos(){
		$('#all_disp').show('slow/400/fast', function() {
			
		});


		$('#tab_grupo').hide('slow/400/fast', function() {
			
		});

		$('#ver_dispositivos').hide('slow/400/fast', function() {
			
		});

		$('#ver_grupos').show('slow/400/fast', function() {
			
		});
	}
</script>