<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\models\CentroCosto */
/* @var $form yii\widgets\ActiveForm */
$data_ciudades = array();

$permisos = array();
if( isset(Yii::$app->session['permisos-exito']) ){

  $permisos = Yii::$app->session['permisos-exito'];

}

foreach ($ciudades as $key) {
  
  $data_ciudades[$key->codigo_dane] = $key->nombre; 
}

$data_empresas = array();

foreach ($empresas as $key) {
  
  $data_empresas[$key->nit] = $key->nombre; 
}

$data_marcas = array();

foreach ($marcas as $key) {
  
  $data_marcas[$key->id] = $key->nombre; 
}

$distritos_permitidos = array();
foreach($distritosUsuario as $distrito){
	
     $distritos_permitidos [] = $distrito->distrito->id;	
	
}


$data_distritos = array();
foreach ($distritos as $value) {
    
	if(in_array($value->id,$distritos_permitidos)){
	  
      $data_distritos[$value->id] = $value->nombre;	  
	}
    
}



?>

<div class="centro-costo-form">

    <?php $form = ActiveForm::begin(); ?>
   
    <?php
	
	   if(in_array('administrador',$permisos) ){
		   
		   ?>
		   
            	<?= $form->field($model, 'codigo')->textInput(['maxlength' => true,'disabled'=>$model->isNewRecord ? false : true]) ?>
				<?= $form->field($model, 'cebe')->textInput(['maxlength' => true]) ?>
				<?= $form->field($model, 'ceco')->textInput(['maxlength' => true]) ?>
				<?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>
		   <?php
		   
		   
	   }
	
	?>
   

	<?php if(!isset($actualizar)):?>
    
     <?php //echo $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>   
   <?php endif;?>
   

    <?= $form->field($model, 'direccion')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'telefono')->textInput(['maxlength' => true]) ?>


	<?php 


	if(in_array('administrador',$permisos) ){
	?>
	
	<?= $form->field($model, 'marca_id')->widget(Select2::classname(), [
       
	   'data' => $data_marcas,
    
      ])?>
	  
	<?= $form->field($model, 'ciudad_codigo_dane')->widget(Select2::classname(), [
       
	   'data' => $data_ciudades,
    
      ])?>


    <?php 

	}
    ?>

    <label>Empresas</label>
    <?php 
    	if ($model->isNewRecord) {
    		echo Select2::widget([
                'name' => 'empresas[]',
                'data' => $data_empresas,
                //'size' => Select2::SMALL,
                'options' => ['placeholder' => 'Selecciona empresas ...', 'multiple' => true,'id'=>'empresas'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
        	]);
    	}else{
    		echo Select2::widget([
                'name' => 'empresas[]',
                'data' => $data_empresas,
                //'size' => Select2::SMALL,
                'value'=>$empresas_dep,
                'options' => ['placeholder' => 'Selecciona empresas ...', 'multiple' => true,'id'=>'empresas'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
        	]);
    	}


    ?>
	


      <?= $form->field($model, 'empresa_electronica')->widget(Select2::classname(), [
       
	   'data' => $empresas_electronica,
	   'options' => ['placeholder' => 'Selecccione empresa'],
    
      ])?>	  

	  
	  		<?php
		
		   echo Select2::widget([
			'name' => 'distrito',
			'data' => $data_distritos,
			'options' => [
				'id' => 'distrito',
				'placeholder' => 'Distrito',
											
			 ],


		   ]);
		
	?>	
	<p>&nbsp;</p>

	
	<?= $form->field($model, 'indicador_visita')->radioList(array('S'=>'Si','N'=>'No')); ?>
	<?= $form->field($model, 'indicador_capacitacion')->radioList(array('S'=>'Si','N'=>'No')); ?>

	<?= $form->field($model, 'indicador_semestre')->radioList(array('S'=>'Si','N'=>'No')); ?>
	<?= $form->field($model, 'indicador_gestion')->radioList(array('S'=>'Si','N'=>'No')); ?>
	<?php
       
          if(in_array('administrador',$permisos) ){
    ?>
       <?= $form->field($model, 'fecha_apertura')->widget(DateControl::classname(), [
				  'autoWidget'=>true,
				 'displayFormat' => 'php:Y-m-d',
				 'saveFormat' => 'php:Y-m-d',
				  'type'=>DateControl::FORMAT_DATE,
     
           ]);?>
	
	  <?= $form->field($model, 'estado')->dropDownList(['A' => 'Abierta', 
	                                                    'C' => 'Cerrada',
														'D' => 'En Desarrollo',
														'O' => 'Otro']) ?>	  


    <?php

		  }		  
	
	?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => 'btn btn-primary btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>
	

</div>
