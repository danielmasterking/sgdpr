<script src="https://code.highcharts.com/highcharts.src.js"></script>
<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Gestiones '.$usuario;
if( isset(Yii::$app->session['permisos-exito']) ){

  $permisos = Yii::$app->session['permisos-exito'];

}


// echo "<pre>";
// print_r($json);
// echo "</pre>";
?>
<?= $this->render('_tabs',['gestiones' => $gestiones,'usuario' => $usuario]) ?>

    <h1 style="text-align: center;"><?= Html::encode($this->title) ?></h1>

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



    <div id="container">

	</div>

	<br>

    <div class="table-responsive">
	<table  class="table table-striped  my-data">
		<thead>
			<tr>
				<th></th>
				<th>Usuario</th>
				<th>Dependencia</th>
				<th>Observacion</th>
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
						echo Html::a('<i class="fa fa-eye"></i>',Yii::$app->request->baseUrl.'/centro-costo/detalle_gestiones?id='.$row->id.'&dependencia='.$row->id_centro_costo.'&modulo=coordinador&usuario='.$usuario,['title'=>'ver','class'=>'btn btn-info btn-sm']);

                        if(in_array("administrador", $permisos)){
                            echo Html::a('<i class="fa fa-trash"></i>',Yii::$app->request->baseUrl.'/usuario/delete_gestiones?id='.$row->id.'&usuario='.$usuario,['data-method'=>'post','data-confirm' => 'Está seguro de eliminar elemento','title'=>'Eliminar','class'=>'btn btn-danger btn-sm']);
                        }


					?>

				</td>
				<td><?php echo  $row->usuario ?></td>
				<td><?php echo  $row->dependencia->nombre ?></td>
				<td><?php echo  $row->observacion ?></td>
				<td><?php echo $row->fecha_visita ?></td>
				<td><?php echo  $row->fecha ?></td>
			</tr>
			<?php }?>
		</tbody>

	</table>
</div>
<script type="text/javascript">
	
	Highcharts.chart('container', {
    chart: {
        type: 'column'
    },
    title: {
        text: ''
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        categories: <?= $categorias?>,
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Cantidad'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y}</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: <?= $json?>
});


</script>

   
	