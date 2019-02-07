<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use app\models\DispositivoAdmin;

/* @var $this yii\web\View */
/* @var $model app\models\DispositivoAdmin */
/* @var $form yii\widgets\ActiveForm */
$data_zona=[];

foreach ($zonasUsuario as $row) {
    
    $data_zona[$row->zona_id]=$row->zona->nombre;
}




?>

<div class="dispositivo-admin-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
    	<div class="col-md-6">
    		<?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>		
    	</div>

    	<div class="col-md-6">
    		<?php 
				echo $form->field($model, 'nit_empresa')->widget(Select2::classname(), [
				    'data' =>$list_empresas,
				    'options' => ['placeholder' => 'Selecciona una empresa']
				]);

			?>
    	</div>
    </div>
    <br>

    <div class="row">
    	<div class="col-md-6">
            <?php 
				echo $form->field($model, 'id_regional')->widget(Select2::classname(), [
				    'data' =>$data_zona,
				    'options' => ['placeholder' => 'Selecciona una regional','id'=>'zona','disabled'=>isset($actualizar)?true:false]
				]);

			?>
        </div>

    	<div class="col-md-6">
    		<label>Dependencias</label>
            <?php 

            	if ($actualizar==1) {
            		$value_dep=DispositivoAdmin::DependenciasDisp($model->id);

            		echo Select2::widget([
	                    'name' => 'dependencias[]',
	                    'data' => $data_dependencias,
	                    //'size' => Select2::SMALL,
	                    'value'=>$value_dep,
	                    'options' => ['placeholder' => 'Selecciona dependencias ...', 'multiple' => true,'id'=>'deps'],
	                    'pluginOptions' => [
	                        'allowClear' => true
	                    ],
                	]);
            	}else{
            		echo Select2::widget([
	                    'name' => 'dependencias[]',
	                    'data' => $data_dependencias,
	                    //'size' => Select2::SMALL,
	                    'options' => ['placeholder' => 'Selecciona dependencias ...', 'multiple' => true,'id'=>'deps'],
	                    'pluginOptions' => [
	                        'allowClear' => true
	                    ],
                	]);
            	}
               
            ?>
    	</div>
    </div>
    
    <br>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
	$('#zona').change(function(event) {
        $.ajax({
            url:"<?php echo Yii::$app->request->baseUrl . '/adminsupervision/dependenciaszona'; ?>",
            type:'POST',
            dataType:"json",
            cache:false,
            async:false,
            data: {
                zona: $(this).val(),
                empresa:$('#empresa option:selected').val()
            },
            beforeSend:  function() {
                //$('#body_ayuda').html('Cambiando... <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>');
            },
            success: function(data){
               $('#deps').html(data.resp);
            }
        });
        
   });
</script>
