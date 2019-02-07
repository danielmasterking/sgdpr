<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ManualApp */

$this->title = 'Manuales Aplicativo';

?>
<div class="manual-app-update">

    <h1><?= Html::encode($this->title) ?></h1>

   <table class="table table-striped">
   		<thead>
   			<tr>
   				<th></th>
   				<th>Modulo</th>
   				<th>Documentacion</th>
   			</tr>
   		</thead>
   		<tbody>
   			<?php 
   			$cont=1;
   			foreach($query as $row){
   			?>
   			<tr>
   				<td><b><?= $cont ?>.</b></td>
   				<td><?= $row->modulo ?></td>
   				<td>
   					<a href="<?= Yii::$app->request->baseUrl.$row->archivo ?>" target='_blank'>
   						<i class="fas fa-file"></i>

   					</a>

   				</td>

   			</tr>
   			<?php
   			 $cont++;
   			}
   			?>

   		</tbody>
   </table>

</div>
