<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\DepDrop ;
use kartik\widgets\DatePicker;
use yii\helpers\Url;

$this->title = 'Formulario de Visita Semestral';

date_default_timezone_set ( 'America/Bogota');
$fecha = date('Y-m-d');
$orden = 0;
$data_dependencias = array();
$ciudades_zonas = array();

foreach($zonasUsuario as $zona){
	
     $ciudades_zonas [] = $zona->zona->ciudades;	
	
}

$ciudades_permitidas = array();

foreach($ciudades_zonas as $ciudades){
	
	foreach($ciudades as $ciudad){
		
		$ciudades_permitidas [] = $ciudad->ciudad->codigo_dane;
		
	}
	
}

$marcas_permitidas = array();

foreach($marcasUsuario as $marca){
	
		
		$marcas_permitidas [] = $marca->marca_id;

}

$dependencias_distritos = array();

foreach($distritosUsuario as $distrito){
	
     $dependencias_distritos [] = $distrito->distrito->dependencias;	
	
}

$dependencias_permitidas = array();

foreach($dependencias_distritos as $dependencias0){
	
	foreach($dependencias0 as $dependencia0){
		
		$dependencias_permitidas [] = $dependencia0->dependencia->codigo;
		
	}
	
}

$tamano_dependencias_permitidas = count($dependencias_permitidas);

$data_dependencias = array();

foreach($dependencias as $value){
	
	if(in_array($value->ciudad_codigo_dane,$ciudades_permitidas)){
		
		if(in_array($value->marca_id,$marcas_permitidas)){
			
		   if($tamano_dependencias_permitidas > 0){
			   
			   if(in_array($value->codigo,$dependencias_permitidas)){
				   
				 $data_dependencias[$value->codigo] =  $value->nombre;
				   
			   }else{
				   //temporal mientras se asocian distritos
				   $data_dependencias[$value->codigo] =  $value->nombre;
			   }
			   
			   
		   }else{
			   
			   $data_dependencias[$value->codigo] =  $value->nombre;
		   }	
       
		}

	}
}

?>
<?= Html::a('<i class="fa fa-arrow-left"></i> ',Yii::$app->request->baseUrl.'/visita-mensual/index', ['class'=>'btn btn-primary']) ?>

<h1 class="text-center"><?= Html::encode($this->title)?></h1>

<?php 

    $flashMessages = Yii::$app->session->getAllFlashes();
    if ($flashMessages) {
        foreach($flashMessages as $key => $message) {
            echo "<div class='alert alert-" . $key . " alert-dismissible' role='alert'>
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                    $message
                </div>";   
        }
    }
?>

<?php $form = ActiveForm::begin([

     'options'=>['enctype'=>'multipart/form-data'] // important
]); ?>

<div class="row">

	<div class="col-md-6">
	  	<?= $form->field($model, 'centro_costo_codigo')->widget(Select2::classname(), [
		   	'data' => $data_dependencias,
			'options' => [
			'id' => 'dependencia',
			'placeholder' => 'Dependencia',
										
		    ],
	    
	      	])
		?>
	   
	   </div>
	

	<div class="col-md-6">
		<?= $form->field($model, 'fecha_visita')->widget(DatePicker::classname(), [
		    'options' => ['value' => $fecha],
		    'pluginOptions' => [
		        'autoclose'=>true,
		        'format' => 'yyyy-mm-dd',
		    ]
		]); ?>
	</div>
</div>



<div class="row">
	<div class="col-md-6">
		<?php

	        // Child # 1
	        echo $form->field($model, 'atendio')->widget(DepDrop::classname(), [
	            'type'=>DepDrop::TYPE_SELECT2,
	            'data' => [1 => ''],   
	            'pluginOptions'=>[
	              
	                'depends'=>['dependencia'],
	                'placeholder' => 'Select...',
	                'url'=>Url::to(['responsable/dependencia']),
	                //'params'=>['input-type-1', 'input-type-2']
	              ]
	          ]);


	        ?>
	 </div>

	<div class="col-md-6">
		<?= $form->field($model, 'otro')->textInput(['maxlength' => true,'readonly' => 'readonly']) ?>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'semestre')->radioList(array('1'=>'Primer Semestre','2'=>'Segundo Semestre')); ?>
	</div>
</div>

<button class="btn btn-primary">Crear</button>


<?php ActiveForm::end(); ?>