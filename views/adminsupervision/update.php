<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\AdminSupervision */

$this->title = 'Actualizar';
$this->params['breadcrumbs'][] = ['label' => 'Admin Supervisions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
use app\models\EmpresaDependencia;
$list_dep=ArrayHelper::map($dependencias,'codigo','nombre');

// echo "<pre>";
// print_r($list_dep);
// echo "</pre>";

$ciudades_zonas = array();//almacena las regionales permitidas al usuario

foreach($zonasUsuario as $zona){
	$ciudades_zonas [] = $zona->zona->ciudades;
}
$ciudades_permitidas = array();
$ciudades_zonas_permitidas = array();//guarda solo la regional y la ciudad para filtrar por javascript
foreach($ciudades_zonas as $ciudades){
	foreach($ciudades as $ciudad){
		foreach($zonas as $z){
			if($z->id==$ciudad->zona_id){
				$regionales[$z->id] = $z->nombre;break;
			}
		}
		$ciudades_permitidas [] = $ciudad->ciudad->codigo_dane;
		$ciudades_zonas_permitidas [] = array('zona' => $ciudad->zona_id, 'nombre' => $ciudad->ciudad->nombre, 'codigo' => $ciudad->ciudad->codigo_dane);
	}
}
$marcas_permitidas = array();
$marcas = array();
foreach($marcasUsuario as $marca){
	$marcas_permitidas [] = $marca->marca_id;
	$marcas[$marca->marca->nombre] = $marca->marca->nombre;
}
$empresas_permitidas = array();
foreach($empresasUsuario as $empresa){
	$empresas_permitidas [] = $empresa->nit;
	$list_empresas[$empresa->nit]=$empresa->empresa->nombre;
}

$data_dependencias = array();
foreach($dependencias as $dependencia){
	if(in_array($dependencia->ciudad_codigo_dane,$ciudades_permitidas) ){
		if(in_array($dependencia->marca_id,$marcas_permitidas) ){
            $modelo_emp_dep=new EmpresaDependencia();
            $emp_dep=$modelo_emp_dep->get_empresa_deps($dependencia->codigo);
            $existe=false;
            if($emp_dep!=null){
                foreach ($emp_dep as $emp) {
                    if(in_array($emp,$empresas_permitidas) ){
                        $existe=true;
                        break;
                    }
                }
            }
			if($existe){
				//$data_dependencias[] = array('codigo' => $dependencia->codigo, 'nombre' => $dependencia->nombre);
				$array_dep[$dependencia->codigo] =  $dependencia->nombre;	
			}
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
