<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Gestiones de riesgo';
$permisos = array();

if( isset(Yii::$app->session['permisos-exito']) ){

  $permisos = Yii::$app->session['permisos-exito'];

}
$gestiones='active';

?>
    <?= $this->render('_tabsDependencia',['codigo_dependencia' => $codigo_dependencia,'gestiones' => $gestiones]) ?>

    <h1 style="text-align: center;"><?php echo $this->title ?></h1>

     <?php 

	    $flashMessages = Yii::$app->session->getAllFlashes();

	    if ($flashMessages) {
	        echo "<br><br><div class='row'>";
	        foreach($flashMessages as $key => $message) {
	            echo "<div class='alert alert-" . $key . " alert-dismissible' role='alert'>
	                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
	                    $message
	                </div>";   
	        }

	        echo "</div>";
	    }
	?>

	 
	 
	<table  class="table table-striped display my-data">
		<thead>
			<tr>
				<th></th>
				<th>Usuario</th>
				<!-- <th>Observacion</th> -->
				<th>Fecha Visita</th>
				<th>Fecha creado</th>
			</tr>
		</thead>
		<tbody>
			<?php 

			foreach($consulta as $row){
			?>	
			<tr>
				<td>
					<?php 
						echo Html::a('<i class="fa fa-eye"></i>',Yii::$app->request->baseUrl.'/centro-costo/detalle_gestiones?id='.$row->id.'&dependencia='.$codigo_dependencia.'&modulo=dependencia',['title'=>'ver','class'=>'btn btn-info btn-sm']);

						 if(in_array("administrador", $permisos)){
                            echo Html::a('<i class="fa fa-trash"></i>',Yii::$app->request->baseUrl.'/centro-costo/delete_gestiones?id='.$row->id.'&dependencia='.$codigo_dependencia,['data-method'=>'post','data-confirm' => 'Está seguro de eliminar elemento','title'=>'Eliminar','class'=>'btn btn-danger btn-sm']);
                        }


					?>

				</td>
				<td><?php echo  $row->usuario ?></td>
				<!-- <td><?php echo  $row->observacion ?></td> -->
				<td><?php echo  $row->fecha_visita ?></td>
				<td><?php echo  $row->fecha ?></td>
			</tr>
			<?php }?>
		</tbody>

	</table>