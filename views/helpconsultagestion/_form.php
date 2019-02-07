<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\HelpConsultaGestion */
/* @var $form yii\widgets\ActiveForm */

if( isset(Yii::$app->session['permisos-exito']) ){

  $permisos = Yii::$app->session['permisos-exito'];

}


?>

<div class="help-consulta-gestion-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'descripcion')->textarea(['rows' => 6]) ?>


    <?= $form->field($model, 'id_consulta_gestion')->widget(Select2::classname(), [
       
       'data' => $preguntas,
       'options' => ['placeholder' => 'Selecciona un Tema' ],
    
      ]); ?>

  

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

<?php if( !isset($actualizar) ):?>
    <div class="col-md-12">
    	<table class="table table-striped">
    		<thead>
    			<tr>
    				<th></th>
    				<th>Tema </th>
    				<th>Ayuda</th>
    			</tr>
    		</thead>
    		<tbody>
    			<?php foreach($registros as $row ): ?>
    			<tr>
    				<td>
					
					<?php
					
					if($permisos != null){
										
						if(in_array("administrador", $permisos) ){
						   
						  echo Html::a('<i class="fas fa-edit"></i>',Yii::$app->request->baseUrl.'/helpconsultagestion/update?id='.$row->id,['class'=>'btn btn-primary btn-xs']);
						  echo Html::a('<i class="fa fa-trash"></i>',Yii::$app->request->baseUrl.'/helpconsultagestion/delete?id='.$row->id,['data-method'=>'post','data-confirm' => 'Está seguro de eliminar elemento','class'=>'btn btn-danger btn-xs']);
		  
						 }
						 
					}
					?>
					</td>
					<td>
						<?php echo $row->consulta->descripcion ?>
					</td>

					<td>
						<?php echo $row->descripcion ?>
					</td>

    			</tr>
    			<?php endforeach;?>
    		</tbody>

    	</table>
    </div>
<?php endif;?>

</div>
