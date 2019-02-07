<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PreciosMonitoreo */
/* @var $form yii\widgets\ActiveForm */
if( isset(Yii::$app->session['permisos-exito']) ){

  $permisos = Yii::$app->session['permisos-exito'];

}


?>

<div class="precios-monitoreo-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_empresa')->dropDownList($empresas) ?>

    <?= $form->field($model, 'id_sistema_monitoreo')->dropDownList($sistema_monitoreado) ?>

    <?= $form->field($model, 'valor_unitario')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ano')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php if( !isset($actualizar) ):?>
    	<table class="table table-striped my-data">
    		<thead>
    			<tr>
    				<th></th>
    				<th>Empresa</th>
    				<th>Sistema Monitoreado</th>
    				<th>Valor unitario</th>
    				<th>Año</th>
    			</tr>
    		</thead>
    		<tbody>
    			<?php foreach($consulta as $row): ?>
    			<tr>
    				<td>
					
					<?php
					
					if($permisos != null){
										
						if(in_array("administrador", $permisos) ){
						   
						  echo Html::a('<i class="fa fa-edit fa-fw"></i>',Yii::$app->request->baseUrl.'/preciosmonitoreo/update?id='.$row->id,['class'=>'btn btn-primary btn-xs']);
						  echo Html::a('<i class="fa fa-trash"></i>',Yii::$app->request->baseUrl.'/preciosmonitoreo/delete?id='.$row->id,['data-method'=>'post','data-confirm' => 'Está seguro de eliminar elemento','class'=>'btn btn-danger btn-xs']);
		  
						 }
						 
					}
					?>
					</td>
					<td><?= $row->empresa->nombre?></td>
					<td><?= $row->sistemamonitoreo->nombre?></td>
					<td><?= '$ '.number_format((double)$row->valor_unitario, 0, '.', '.').' COP'?></td>
					<td><?= $row->ano?></td>

    			</tr>
    			<?php endforeach;?>
    		</tbody>

    	</table>


   	<?php endif;?>

</div>
