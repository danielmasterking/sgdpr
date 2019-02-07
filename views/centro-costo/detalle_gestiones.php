<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Detalle Gestion de riesgo';
$permisos = array();

if( isset(Yii::$app->session['permisos-exito']) ){

  $permisos = Yii::$app->session['permisos-exito'];

}


?>

<?php


if($modulo=='dependencia'){
?>

<?= Html::a('<i class="fa fa-arrow-left"></i> Volver a gestiones',Yii::$app->request->baseUrl.'/centro-costo/gestiones?id='.$codigo_dependencia,['class'=>'btn btn-primary']) ?>

<?php

}
?>


<?php

if($modulo=='coordinador'){
?>

<?= Html::a('<i class="fa fa-arrow-left"></i> Volver a gestiones',Yii::$app->request->baseUrl.'/usuario/gestiones?id='.$usuario,['class'=>'btn btn-primary']) ?>


<?php

}
?>

<a href="<?php echo Url::toRoute('gestionriesgo/imprimir?id='.$id)?>" class="btn btn-danger " >
	<i class="far fa-file-pdf"></i> PDF
</a>

    <h1 style="text-align: center;"><?php echo $this->title ?></h1>
	 
	 <div class="col-md-12">
	 	<table class="table table-bordered">
			<tr>
				<th >Dependencia:</th>
				<td ><?= $model->dependencia->nombre ?></td>
				<td rowspan="2" style="text-align: center;">
					<img <?= $styleimg?> alt="imagen" class="img-responsive img-thumbnail" src="<?=Yii::$app->request->baseUrl.$model->dependencia->foto?>" style=" width: 400px; height: 200px;" />
				</td>
			</tr>
			<tr>
				<th >Fecha Visita:</th>
				<td ><?= $model->fecha_visita ?></td>
			</tr>
			<tr>
				<th >Novedad:</th>
				<td colspan="2"><?= $model->observacion ?></td>
			</tr>
		</table>
	</div>

	<br>
	 
	<table class="table table-striped ">
		<thead>
			<tr>
				<th></th>
				<th>Tema</th>
				<th>Respuesta</th>
				<th>Observaciones</th>
				<th>Plan de accion</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$orden=1;
			foreach($consulta as $row){
			?>

			<tr>
				<td><b><?= $orden?>.</b></td>
				<td><?= $row->consulta->descripcion?></td>
				<td><?= $row->respuesta->descripcion?></td>
				<td><?= $row->observaciones?></td>
				<td><?= $row->planes_de_accion?></td>


			</tr>
			<?php 
				$orden++;
				}
			?>
		</tbody>
	</table>