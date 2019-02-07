<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ConsultasGestion */
/* @var $form yii\widgets\ActiveForm */


if( isset(Yii::$app->session['permisos-exito']) ){

  $permisos = Yii::$app->session['permisos-exito'];

}


?>

<div class="consultas-gestion-form">

    <?php $form = ActiveForm::begin(); ?>



    <?= $form->field($model, 'descripcion')->textarea(['rows' => 6]) ?>

   <?= $form->field($model, 'orden')->textInput() ?>

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
                    <th>Orden</th>
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
						   
						  echo Html::a('<i class="fas fa-edit"></i>',Yii::$app->request->baseUrl.'/consultasgestion/update?id='.$row->id,['class'=>'btn btn-primary btn-xs']);
						  echo Html::a('<i class="fa fa-trash"></i>',Yii::$app->request->baseUrl.'/consultasgestion/delete?id='.$row->id,['data-method'=>'post','data-confirm' => 'Está seguro de eliminar elemento','class'=>'btn btn-danger btn-xs']);

                          if($row->estado=="A"){
                            echo Html::a('<i class="far fa-hand-point-down"></i> Desactivar',Yii::$app->request->baseUrl.'/consultasgestion/cambiar-estado?id='.$row->id.'&estado=I',['data-method'=>'post','data-confirm' => 'Está seguro de desactivar este tema','class'=>'btn btn-warning btn-xs']);
                          }else{
                            echo Html::a('<i class="fas fa-hand-point-up"></i> Activar',Yii::$app->request->baseUrl.'/consultasgestion/cambiar-estado?id='.$row->id.'&estado=A',['data-method'=>'post','data-confirm' => 'Está seguro de activar este tema','class'=>'btn btn-success btn-xs']);
                          }
		  
						}
						 
					}
					?>
					</td>

    				<!-- <td><?php //echo $row->id ?></td> -->
                    <td><?= $row->orden ?></td>
    				<td><?= $row->descripcion ?></td>
    				
    			</tr>
    		<?php endforeach;?>
    		</tbody>
    	</table>
    </div>
    <?php endif;?>

</div>
