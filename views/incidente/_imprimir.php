
<?php 

use yii\helpers\Url;
use yii\helpers\Html;
use app\models\FotoNovedadIncidente;

$this->title = 'Investigacion- '.$model->dependencia->nombre;

$styletd='style="padding: 5px;text-align: center;font-size: 9px;"';
$styletd2='style="padding: 5px;font-size: 10px;"';
$styleth='style="padding: 5px;text-align: center;font-size: 9px;';
$styleimg='style=" width: 250px; height: 150px;"';
$styleimg2='style=" width: 200px; height: 100px;"';


?>


<h3 class="text-center"><i class="fa fa-folder-open"></i> <?= $this->title ?></h3> 

<div class="col-md-12">
	
	<table class="table table-striped table-bordered">

		<tr>
			<th <?=$styletd2?>>Nombre Investigacion :</th>
			<td <?=$styletd2?>><?= $model->titulo ?></td>

			<td rowspan="7" align="center">
				<img <?= $styleimg?> alt="imagen" class="img-responsive img-thumbnail" src="<?=Yii::$app->request->baseUrl.$model->dependencia->foto?>" />
			</td>

		</tr>

		<tr>
			<th <?=$styletd2?>>Dependencia :</th>
			<td <?=$styletd2?>><?= $model->dependencia->nombre ?></td>
			
		</tr>

		<tr>
			<th <?=$styletd2?>>Regional :</th>
			<td <?=$styletd2?>><?= $model->dependencia->ciudad->zona->zona->nombre ?></td>
			
		</tr>

		<tr>
			<th <?=$styletd2?>>Ceco :</th>
			<td <?=$styletd2?>><?= $model->dependencia->ceco ?></td>
		</tr>
		<tr>
			<th <?=$styletd2?>>Fecha evento :</th>
			<td <?=$styletd2?>><?= $model->fecha ?></td>
		</tr>
		<tr>
			<th <?=$styletd2?>>Fecha inicio :</th>
			<td <?=$styletd2?>><?= $model->fecha_inicio ?></td>
		</tr>
		<tr>
			<th <?=$styletd2?>>Tipo incidente :</th>
			<td <?=$styletd2?>><?= $model->novedad->nombre ?></td>
		</tr>

		<tr>
			<th <?=$styletd2?>>Detalle :</th>
			<td colspan="2" <?=$styletd2?>><?= $model->detalle ?></td>
		</tr>
		<?php

			if($model->estado=='cerrado'){
		?>
		<tr>
			<th <?=$styletd2?> >Detalle Cierre :</th>
			<td colspan="2" <?=$styletd2?>><?= $model->detalle_cierre ?></td>
		</tr>
		<?php
		}
		?>

		


	</table>


</div>


<h3 class="text-center"><i class="fa fa-search"></i> Novedades</h3>

<?php 
	
	foreach($novedades as $row): 

	$fotos=FotoNovedadIncidente::find()->where('id_novedad='.$row->id)->all();
?>

<table class="table table-bordered">
	<tr>
		<th  <?=$styleth.'"'?>>Tipo Novedad:</th>           
		<td <?=$styletd?>><?= $row->tipo_novedades->nombre ?></td>      
		<td rowspan="4">
			<?php
                                    		 
                foreach($fotos as $foto): 

                $nombre_archivo=str_replace('/uploads/novedad_incidente/','',$foto->foto);

            	$extension=explode('.',$nombre_archivo);


            	if($extension[1]=='jpg' or $extension[1]=='JPG' or $extension[1]=='png' or $extension[1]=='PNG' or $extension[1]=='gif' or $extension[1]=='GIF'  ){


            ?>

              
			    <!-- <a href="#" class="thumbnail"> -->
			      <img class="thumbnail" <?= $styleimg2?> src="<?= Yii::$app->request->baseUrl.$foto->foto ?>" alt="...">
			    <!-- </a> -->
			  

			<?php 
				}else{
			 ?>
        	<a style="font-size: 9px;" href="<?= Yii::$app->request->baseUrl.$foto->foto ?>" download=""><i class="fa fa-paperclip"></i> <?= $nombre_archivo ?></a><br>
        	<?php 
                }
                endforeach;
            ?>
		</td>    
	</tr>
	<tr>
		<th <?=$styleth.'"'?>>Fecha Evento:</th>
		<td <?=$styletd?>><?= $row->fecha ?></td>
	</tr>
	<tr>
		<th <?=$styleth.'"'?>>Usuario:</th>
		<td <?=$styletd?>><?= $row->usuario ?></td>
	</tr>

	<tr>
		<th <?=$styleth.'"'?>>Detalle:</th>
		<td <?=$styletd?>><?= $row->desc_novedad?></td>
	</tr>

</table>


<?php  
	
	echo "<hr>";
	endforeach;
?>







