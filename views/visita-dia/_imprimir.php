
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use marqu3s\summernote\Summernote;
use kartik\widgets\TimePicker;
use kartik\widgets\FileInput;
use kartik\widgets\DepDrop ;
use kartik\datecontrol\Module;
use kartik\datecontrol\DateControl;
use app\models\DetalleVisitaSeccion;
use app\models\CategoriaVisita;
use app\models\DetalleVisitaDia;
use app\models\ValorNovedad;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Visita Quincenal ';
//$detalle_visita = $model->detalle; //array con detalle de la visita
//$seguridad_electronica = false;

$styletd='style="padding: 5px;text-align: center;font-size: 9px;"';
$styleth='style="padding: 5px;text-align: center;font-size: 9px;';

?>

<!-- <div class="form-group"> -->

	   
     
	 <h3 style="text-align: center;"><?= Html::encode($this->title) ?></h3>

	 <table class="table table-striped table-bordered">
	 	<tr>
	 		<th <?=$styleth.'"'?>>Fecha de creación:</th>
	 		<td <?=$styletd?> ><?= $model->fecha?></td>
	 		
	 	</tr>
	 	<tr>
	 		<th <?=$styleth.'"'?>>Creada por:</th>
	 		<td <?=$styletd?> ><?= $model->usuario?></td>
	 	</tr>
	 	<tr>
	 		<th <?=$styleth.'"'?>>Dependencia:</th>
	 		<td <?=$styletd?> ><?= $model->dependencia->nombre?></td>
	 	</tr>

	 	<tr>
	 		<th <?=$styleth.'"'?>>Atendió Visita:</th>
	 		<td <?=$styletd?> ><?= $model->responsable?></td>
	 	</tr>

	 	<tr>
	 		<th <?=$styleth.'"'?>>Otro:</th>
	 		<td <?=$styletd?> ><?= $model->otro?></td>
	 	</tr>

	 	<tr>
		 	<th <?=$styleth.'"'?>>Observaciones:</th>
		 	<td  <?=$styletd?>><?= $model->observaciones?></td>
		</tr>
	 </table>

 <div class="row" id="estadistica" >
	
	<div class="col-md-8 table-responsive " style="padding-left: 0px;">	
		<table class="table table-striped">
			<?php 
			$orden=1;
			$calif=0;
			$array_calif=array();
			foreach($categorias as $cat1):

				$detalle_visita=DetalleVisitaDia::Detalle_visitas($cat1->id,$id_visita);

			?>
				<tr>
					<th  colspan="5" class="danger" style="background-color: #f2dede;text-align: center;font-size: 9px;"><?= $cat1->nombre ?></th>
				</tr>

				<tr >	
					<th <?=$styleth.'"'?>></th>
					<th <?=$styleth.'"'?>>Novedad</th>
					<th <?=$styleth.'"'?>>Resultado</th>
					<th <?=$styleth.'"'?>>Mensaje</th>
					<th <?=$styleth.'"'?>>Comentario</th>
				</tr>
			<?php  

				$calif_secc=0;
				foreach($detalle_visita as $detalle):

				$valor_calif=ValorNovedad::porcentaje($detalle->novedad->id,$detalle->resultado->id);

				$calif+=$valor_calif;

				$calif_secc+=$valor_calif;
			?>
			
				<tr>
					<td <?=$styletd?> ><b><?= $orden."." ?></b></td>
					<td <?=$styletd?> ><?= $detalle->novedad->nombre?></td>
					<td <?=$styletd?> ><?= $detalle->resultado->nombre?></td>
					<td <?=$styletd?> ><?= $detalle->mensajeNovedad->mensaje?></td>
					<td <?=$styletd?> ><?= $detalle->observacion?></td>
				</tr>

				<?php 
					$detalle_seccion=DetalleVisitaSeccion::find()->where('detalle_visita_dia_id='.$detalle->id)->all();

					if($detalle_seccion!=null):
				?>
				<tr>
					<th colspan="5" style="background-color:#d9edf7;text-align: center;font-size: 9px;" class="info">Secciones- <?= $cat1->nombre ?></th>
				</tr>

				<tr >
					<th <?=$styleth.'"'?>></th>
					<th <?=$styleth.'"'?>>Seccion</th>
					<th <?=$styleth.'"'?> colspan="3">Resultado</th>
					<!-- <th style="text-align: center;">Mensaje</th>
					<th style="text-align: center;"> Comentario</th> -->
				</tr>

				<?php 
					$arr_secc=['a','b','c','d','e','f','g','h','i','j','k','l','m','n','ñ','o','p','q','r','s','t','u','x','y','z'];
					$i=0;
					foreach($detalle_seccion as $secc): 
				?>
				<tr>
					<td <?=$styletd?>><b><?= $arr_secc[$i] ?>.</b></td>
					<td <?=$styletd?>><?= $secc->seccion->nombre?></td>
					<td <?=$styletd?> colspan="3"><?= $secc->resultado_secc->nombre?></td>
					<!-- <td style="text-align: center;"><?//= $secc->mensaje_secc->mensaje?></td>
					<td style="text-align: center;"><?//= $secc->observacion?></td> -->

				</tr>
				<?php 
					$i++;
					endforeach;
				?>
				<tr>
					<td colspan="5" class="info" style="text-align: center;background-color:#d9edf7;" ></td>
				</tr>
				<?php
				endif;
				?>

				

			

		<?php 
			$orden++;
			endforeach;


			$array_calif[$cat1->nombre]=$calif_secc;

			endforeach;		 
		?>
		</table>
	</div>
		
	</div>

	<table>
		<tr>
			<td>
				<!-- ************************ -->
				<table class="table table-striped" style="display:inline;width: 200px;">
					<?php 

					foreach($array_calif as $key=> $value ):
					?>
					<tr>
						<th <?=$styleth.'"'?>><?= $key ?></th>
						<td <?=$styletd?>><?= $value."%" ?></td>
					</tr>
					<?php
					endforeach;
					?>
					
				</table>
				<!-- ************************ -->
			</td>

			<td>
				<!-- ******************************* -->
				<table class="table" style="display:inline-block;width: 200px;">
					<tr>
						<th style="text-align: center;background-color:#d9edf7;"> Total% </th>
					</tr>
					<tr>
						<td style="text-align: center;"><h1 class="text-center text-danger"><?= $calif."%"?></h1></td>
					</tr>
				</table>
				<!-- ******************************** -->
			</td>
		</tr>
	</table>	
	
		

		
	
		


	
	
