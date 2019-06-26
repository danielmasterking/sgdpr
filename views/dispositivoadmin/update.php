<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DispositivoAdmin */

$this->title = 'Actualizar: ' . $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Dispositivo Admins', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';

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

foreach($empresasUsuario as $empresa){
	$empresas_permitidas [] = $empresa->nit;

	
}


//print_r($list_empresas);


$tamano_dependencias_permitidas = count($dependencias_permitidas);

$data_dependencias = array();

$array_dep=array();

foreach($dependencias as $value){
	
	if(in_array($value->ciudad_codigo_dane,$ciudades_permitidas)){
		
		if(in_array($value->marca_id,$marcas_permitidas)){
			if(in_array($value->empresa,$empresas_permitidas) ){
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
			}
       
		}

	}
}

?>

<div class="dispositivo-admin-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'list_empresas'=>$list_empresas,
        'data_dependencias'=>$array_dep,
        'zonasUsuario' => $zonasUsuario,
        'actualizar'=>1
    ]) ?>

</div>
