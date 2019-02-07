<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\HelpRespuestas */
/* @var $form yii\widgets\ActiveForm */

if( isset(Yii::$app->session['permisos-exito']) ){

  $permisos = Yii::$app->session['permisos-exito'];

}

$disabled=isset($actualizar)? true:false;

?>

<?php if( !isset($actualizar) ):?>
<a class="btn btn-primary" role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
  <i class="fa fa-plus"></i>
</a>

<br><br>
<?php endif;?>

<div class="help-respuestas-form collapse <?= isset($actualizar)?'in':'' ?>"  id="collapseExample">

    <?php $form = ActiveForm::begin(); ?>

    
    <?= $form->field($model, 'id_consulta')->widget(Select2::classname(), [
       
       'data' => $preguntas,
       'options' => ['placeholder' => 'Selecciona un Tema','disabled'=>$disabled ],
    
      ]); ?>


    <?= $form->field($model, 'cumple')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'no_cumple')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'en_proceso')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<?php if( !isset($actualizar) ):?>
	<div class="col-md-12">
		<table class="table table-striped">
			<thead>
				<tr>
					<th></th>
					<th>Tema</th>
					<th>Cumple</th>
					<th>No Cumple</th>
					<th>En Proceso</th>
				</tr>
			</thead>
			<tbody>
				<?php 

				foreach($registros as $row):
				?>
				<tr>
				<td>	
					<?php
					
					if($permisos != null){
										
						if(in_array("administrador", $permisos) ){
						   
						  echo Html::a('<i class="fas fa-edit"></i>',Yii::$app->request->baseUrl.'/helprespuestas/update?id='.$row->id,['class'=>'btn btn-primary btn-xs']);
						  echo Html::a('<i class="fa fa-trash"></i>',Yii::$app->request->baseUrl.'/helprespuestas/delete?id='.$row->id,['data-method'=>'post','data-confirm' => 'Está seguro de eliminar elemento','class'=>'btn btn-danger btn-xs']);
		  
						 }
						 
					}
					?>
					

				</td>
				<td><?= $row->tema->descripcion ?></td>
				<td><?= $row->cumple ?></td>
				<td><?= $row->no_cumple ?></td>
				<td><?= $row->en_proceso ?></td>
				</tr>
				<?php

				endforeach;
				?>
			</tbody>
		</table>

	</div>

<?php endif;?>