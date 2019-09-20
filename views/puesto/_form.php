<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\money\MaskMoney;

/* @var $this yii\web\View */
/* @var $model app\models\DetalleServicio */
/* @var $form yii\widgets\ActiveForm */
$data_servicios = array();
foreach ($codigos as $value) {
    
    $data_servicios[$value->id] = $value->nombre;
}

if( isset(Yii::$app->session['permisos-exito']) ){

  $permisos = Yii::$app->session['permisos-exito'];

}

?>

<div class="puesto-form">

    <?php $form = ActiveForm::begin(); ?>
	
	<div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => 'btn btn-primary']) ?>
    </div>
	

	
	<?=

       $form->field($model, 'servicio_id')->widget(Select2::classname(), [
       
	   'data' => $data_servicios,
    
      ])


     ?>		

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?php ActiveForm::end(); ?>
	
		<?php if( !isset($actualizar) ):?>	
	
	<div class="col-md-12">
	 
	 <table class="table table-responsive my-data" data-page-length="30">
	   
		   <thead>
		   
		      <tr>
			     <th></th>
			     <th>Nombre</th>
				 <th>Servicio</th>


			  
			  </tr>
		   
		   
		   </thead>
		   
		   <tbody>
		       
			   <?php foreach($puestos as $key):?>
			   
		           <tr>
				   
				    <td>
					
					<?php
					  
                      if($permisos != null){
						  
						 if(in_array("administrador", $permisos) ){
						   
						  echo Html::a('<i class="fas fa-edit"></i>',Yii::$app->request->baseUrl.'/puesto/update?id='.$key->id,['class'=>'btn btn-primary btn-xs']);
						  /*echo Html::a('<i class="fa fa-trash"></i>',Yii::$app->request->baseUrl.'/puesto/delete?id='.$key->id,['data-method'=>'post','data-confirm' => 'Está seguro de eliminar elemento','class'=>'btn btn-danger btn-xs']);*/
						  if($key->estado=="A"){
						  	echo Html::a('<i class="fas fa-thumbs-down"></i>',Yii::$app->request->baseUrl.'/puesto/desactivar?id='.$key->id,['data-method'=>'post','data-confirm' => 'Está seguro de desactivar elemento','class'=>'btn btn-danger btn-xs','title'=>"Desactivar"]);
						  }else{
						  	echo Html::a('<i class="fas fa-thumbs-up"></i>',Yii::$app->request->baseUrl.'/puesto/activar?id='.$key->id,['data-method'=>'post','data-confirm' => 'Está seguro de Activar elemento','class'=>'btn btn-success btn-xs','title'=>"Activar"]);
						  }
		  
						 }
						  
						  
					  }					  

					?>
					</td>
					<td><?=$key->nombre?></td>
					<td><?=$key->servicio->nombre?></td>

                   </tr>					


               <?php endforeach;?>			   
	   
	 
	 </table>
	
	</div>
	
	<?php endif;?>	

</div>
