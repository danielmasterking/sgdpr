<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AdminSupervisionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Notificaciones';

?>

<h1><i class="fas fa-bell"></i> <?php echo $this->title?></h1>
<div class="col-md-12">
	<table class="table table-striped my-data" >
		<thead>
			<tr>
				<th></th>
				<th style="text-align: center;">Titulo</th>
				<th style="text-align: center;">Fecha</th>

			</tr>
		</thead>
		<tbody>
		<?php foreach($notificacion as $nt): ?>
			<tr>
				<td style="text-align: center;">
					<a href="<?php echo Url::to(['notificacion/view','id'=> $nt->id])?>" class="btn btn-info btn-xs" title="Ver notificacion">
	  					<i class="fa fa-eye"></i>
	  				</a>
				</td>
				<td style="text-align: center;">
					<a href="<?php echo Url::to(['notificacion/view','id'=> $nt->id])?>">
						<i class="fas fa-info-circle" style="color: #Ffe701 "></i> <?= $nt->titulo ?>
					</a>
				</td>

				<td style="text-align: center;">
					<?= $nt->fecha_inicio ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>