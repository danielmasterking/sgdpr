<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RespuestasGestion */
/* @var $form yii\widgets\ActiveForm */
if( isset(Yii::$app->session['permisos-exito']) ){

  $permisos = Yii::$app->session['permisos-exito'];

}

?>

<div class="respuestas-gestion-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>

  

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php if( !isset($actualizar) ):?>
    <div class="col-md-12">
    	<table class="table table striped">
    		<thead>
    			<tr>
    				<th></th>
    				<th>Descripcion</th>
    			</tr>
    		</thead>
    		<tbody>
    			<?php 

    			foreach($consulta as $row):
    			?>
    			<tr>	
    				<td>
					
					<?php
					
					if($permisos != null){
										
						if(in_array("administrador", $permisos) ){
						   
						  echo Html::a('<i class="fas fa-edit"></i>',Yii::$app->request->baseUrl.'/respuestasgestion/update?id='.$row->id,['class'=>'btn btn-primary btn-xs']);
						  echo Html::a('<i class="fa fa-trash"></i>',Yii::$app->request->baseUrl.'/respuestasgestion/delete?id='.$row->id,['data-method'=>'post','data-confirm' => 'Está seguro de eliminar elemento','class'=>'btn btn-danger btn-xs']);
		  
						 }
						 
					}
					?>
					</td>

    				<!-- <td><?php //echo $row->id ?></td> -->
    				<td><?= $row->descripcion ?></td>
    				
    			</tr>
    		<?php endforeach;?>
    		</tbody>
    	</table>
    </div>
    <?php endif;?>

</div>
