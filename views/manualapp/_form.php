<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\ManualApp */
/* @var $form yii\widgets\ActiveForm */
if( isset(Yii::$app->session['permisos-exito']) ){

  $permisos = Yii::$app->session['permisos-exito'];

}

?>

<div class="manual-app-form">

    <?php $form = ActiveForm::begin([

        'options'=>['enctype'=>'multipart/form-data'] // important


    ]); ?>

    <?= $form->field($model, 'modulo')->textInput(['maxlength' => true]) ?>


    <?php
			 // Usage with ActiveForm and model
			 echo $form->field($model, 'pdf')->widget(FileInput::classname(), [
			//'options'=>['multiple'=>false],
			'pluginOptions'=>['allowedFileExtensions'=>[/*'jpg', 'gif', 'png','jpeg','docx','xlsx',*/'pdf'],
							   'maxFileSize' => 5120,
			  ]
			 ]);

			 ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php if( !isset($actualizar) ):?>
    <table class="table table-striped">
    	<thead>
    		<tr>
    			<th></th>
    			<th>Modulo</th>
    		</tr>
    	</thead>
    	<tbody>
    		<?php 

    		foreach($query as $row){
    		 ?>
    		<tr>
    			<td>
    				<?php
					
					if($permisos != null){
										
						if(in_array("administrador", $permisos) ){
						   
						  echo Html::a('<i class="fas fa-edit"></i>',Yii::$app->request->baseUrl.'/manualapp/update?id='.$row->id,['class'=>'btn btn-primary btn-xs']);
						  echo Html::a('<i class="fa fa-trash"></i>',Yii::$app->request->baseUrl.'/manualapp/delete?id='.$row->id,['data-method'=>'post','data-confirm' => 'Está seguro de eliminar elemento','class'=>'btn btn-danger btn-xs']);
		  
						 }
						 
					}
					?>

    			</td>
    			<td><?= $row->modulo ?></td>

    		</tr>
    		<?php

    		}
    		?>
    	</tbody>

    </table>
    <?php endif;?>
</div>
