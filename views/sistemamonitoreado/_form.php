<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SistemaMonitoreado */
/* @var $form yii\widgets\ActiveForm */
if( isset(Yii::$app->session['permisos-exito']) ){

  $permisos = Yii::$app->session['permisos-exito'];

}


?>

<div class="sistema-monitoreado-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php if( !isset($actualizar) ):?>
    <table class="table table-striped">
    	<thead>
    		<tr>
    			<th></th>
    			<th>Nombre</th>

    		</tr>

    	</thead>
    	<tbody>
    		<?php 

    			foreach($consulta as $row){
    		?>
    		<tr>
    			<td>
    				<?php
					
					if($permisos != null){
										
						if(in_array("administrador", $permisos) ){
						   
						  echo Html::a('<i class="fa fa-pencil fa-fw"></i>',Yii::$app->request->baseUrl.'/sistemamonitoreado/update?id='.$row->id);
						  echo Html::a('<i class="fa fa-remove"></i>',Yii::$app->request->baseUrl.'/sistemamonitoreado/delete?id='.$row->id,['data-method'=>'post','data-confirm' => 'Está seguro de eliminar elemento']);
		  
						 }
						 
					}
					?>
    			</td>

    			<td><?= $row->nombre ?></td>


    		</tr>

    		<?php
    		}
    		?>
    	</tbody>
    </table>
    <?php endif;?>
</div>
