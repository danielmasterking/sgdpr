<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
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

$permisos = array();
if( isset(Yii::$app->session['permisos-exito']) ){
	$permisos = Yii::$app->session['permisos-exito'];
}

$buttons=" {view} {delete}";

/*if(in_array("administrador", $permisos) )
	$buttons.="{delete}";*/

$this->title = 'Inspeccion Semestral';



?>
    <div class="page-header">
      <h1><small><i class="fa fa-suitcase fa-fw"></i></small> <?= Html::encode($this->title) ?></h1>
    </div>

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
	
	<div class="form-group">

	<?= Html::a('<i class="fa fa-plus"></i> Crear Visita',Yii::$app->request->baseUrl.'/visita-mensual/create-visita',['class'=>'btn btn-primary']) ?>
		
	</div>	
    
<?php Pjax::begin(); ?>

<form class="form-inline" method="post" action="<?= Yii::$app->request->baseUrl.'/visita-mensual/index'?>" data-pjax='' id='form'>
	<div class="form-group col-md-4 pull-right">
	<?php 
		/*echo Select2::widget([
			'name' => 'dependencia',
			'value' =>$searchDep,
		    'data' => $data_dependencias,
		    'options' => ['placeholder' => 'Dependencia ...'],
		    'pluginOptions' => [
		        'allowClear' => true
		    ],
		]);*/
	?>
		<div class="input-group">
	  		<span class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
			<input placeholder="Buscar...." type="text" name="buscar" class="form-control" onkeyup="$('#form').submit();" value="<?= $searchDep ?>">
		</div>
	</div>
	<!-- <button type="submit" class="btn btn-primary">
		<i class="fa fa-search"></i>	Buscar
	</button> -->
</form>

<br>
	<div class="col-md-12">
	<div class="table-responsive">
	<?= GridView::widget([
	    'dataProvider' => $visitas,
	    //'filterModel' => $searchModel,
	    'class'=>'table table-striped',
	    'columns' => [
	       
	        // 'isactive',
	        [
	        	'class' => 'yii\grid\ActionColumn',
	        	'template' =>$buttons,
	            'buttons' => [
	                'view' => function ($url,$model) {
	                    return Html::a(
	                        '<i class="fa fa-eye"></i>', 
	                        Yii::$app->request->baseUrl.'/visita-mensual/view?id='.$model->id.'&dependencia='.$model->centro_costo_codigo,['class'=>'btn btn-info btn-xs']
	                    );
	                },
	                'delete' => function ($url,$model,$key) {
	                    return Html::a(
	                        '<i class="fa fa-trash"></i>', 
	                        Yii::$app->request->baseUrl.'/visita-mensual/delete?id='.$model->id,['data-confirm'=>'Seguro desea eliminar esta visita(Todos los documentos adjuntos seran eliminados)?','class'=>'btn btn-danger btn-xs']
	                    );
	                },
		        ],
	        ],
	        //['class' => 'yii\grid\SerialColumn'],
	        'dependencia.nombre',
	        'usuario',
	        'atendio',
	        'fecha_visita',
	        'semestre',
	        'estado',
	    ],
	]);
	?>
	</div>
	</div>
<script type="text/javascript">
	
	$(function(){
		$('table').removeClass('table-bordered');
	});

</script>

<?php Pjax::end(); ?>



