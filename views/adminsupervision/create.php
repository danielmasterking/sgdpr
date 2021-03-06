<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\AdminSupervision */

$this->title = 'Crear Nuevo';
$this->params['breadcrumbs'][] = ['label' => 'Admin Supervisions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


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
<div class="admin-supervision-create">

<?= Html::a('<i class="fa fa-arrow-left"></i> Volver ',Yii::$app->request->baseUrl.'/adminsupervision/index', ['class'=>'btn btn-primary']) ?>


    <h1><?= Html::encode($this->title) ?></h1>

    <?php 

    $flashMessages = Yii::$app->session->getAllFlashes();
    if ($flashMessages) {
        echo "<br>";
        foreach($flashMessages as $key => $message) {
            echo "
            	<div class='row'>
            		<div class='col-md-6'>
		                <div class='alert alert-" . $key . " alert-dismissible text-center' role='alert'>
		                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
		                    $message
		                </div>
		            </div>
	            </div>
                ";   
        }
    }
?>


    <?= $this->render('_form', [
        'model' => $model,
        'dependencias' => $dependencias,
		'marcasUsuario' => $marcasUsuario,
		'distritosUsuario' => $distritosUsuario,
		'zonasUsuario' => $zonasUsuario,
		'data_dependencias'=>$array_dep,
		'list_empresas'=>$list_empresas,
		'empresasUsuario'=>$empresasUsuario,
		'list_disp'=>$list_disp
    ]) ?>

</div>
