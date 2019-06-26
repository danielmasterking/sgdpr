<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\AdminSupervision */

$this->title = 'Actualizar';
$this->params['breadcrumbs'][] = ['label' => 'Admin Supervisions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$list_dep=ArrayHelper::map($dependencias,'codigo','nombre');

// echo "<pre>";
// print_r($list_dep);
// echo "</pre>";

$ciudades_zonas = array();

foreach($zonasUsuario as $zonaO){
	
     $ciudades_zonas [] = $zonaO->zona->ciudades;	
	
}

$ciudades_permitidas = array();

foreach($ciudades_zonas as $ciudades){
	
	foreach($ciudades as $ciudad){
		
		$ciudades_permitidas [] = $ciudad->ciudad->codigo_dane;
		
	}
	
}

$marcas_permitidas = array();

foreach($marcasUsuario as $marca){
	
	if($marca->marca->nombre!='VIVA' && $marca->marca->nombre!='INDUSTRIA'){
		$marcas_permitidas [] = $marca->marca_id;
	}

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

$empresas_permitidas = array();
$list_empresas=array();
foreach($empresasUsuario as $empresa){
	$empresas_permitidas [] = $empresa->nit;

	$list_empresas[$empresa->nit]=$empresa->empresa->nombre;
}


//print_r($list_empresas);


$tamano_dependencias_permitidas = count($dependencias_permitidas);

$data_dependencias = array();

$array_dep=array();

foreach($dependencias as $value){
	
	if(in_array($value->ciudad_codigo_dane,$ciudades_permitidas)){
		
		if(in_array($value->marca_id,$marcas_permitidas)){
			//if(in_array($value->empresa,$empresas_permitidas) ){
			   if($tamano_dependencias_permitidas > 0){
				   
				   if(in_array($value->codigo,$dependencias_permitidas)){
					   
					 $data_dependencias [] = array('codigo' => $value->codigo, 'nombre' => $value->nombre);	

					 $array_dep[$value->codigo] =  $value->nombre;
					   
				   }else{
					   
					   	//temporal mientras se asocian distritos
					   $data_dependencias [] = array('codigo' => $value->codigo, 'nombre' => $value->nombre);

					   $array_dep[$value->codigo] =  $value->nombre;	  
					   
				   }
				   
				   
			   }else{
				   
				   $data_dependencias [] = array('codigo' => $value->codigo, 'nombre' => $value->nombre);
				   $array_dep[$value->codigo] =  $value->nombre;			
			   }	
			//}
       
		}

	}
}


 // echo "<pre>";
 // print_r($data_dependencias);
 // echo "</pre>";

?>
<script>

    var dependencias = <?php echo json_encode($data_dependencias);?>;
	var len = dependencias.length;
	var index = 1;

</script>
<div class="admin-supervision-update">
	<?= Html::a('<i class="fa fa-arrow-left"></i> Volver ',Yii::$app->request->baseUrl.'/adminsupervision/view?id='.$model->id, ['class'=>'btn btn-primary']) ?>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formupdate', [
        'model' => $model,
        'dependencias' => $dependencias,
		'marcasUsuario' => $marcasUsuario,
		'distritosUsuario' => $distritosUsuario,
		'zonasUsuario' => $zonasUsuario,
		'data_dependencias'=>$array_dep,
		'list_empresas'=>$list_empresas,
		'list_dep'=>$list_dep,
		'model_dep'=>$model_dep
    ]) ?>

</div>
