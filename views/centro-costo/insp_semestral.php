<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\ValorNovedad;
use kartik\datecontrol\DateControl;
/* @var $this yii\web\View */
/* @var $searchModel app\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Inspeccion Semestral';
if( isset(Yii::$app->session['permisos-exito']) ){

  $permisos = Yii::$app->session['permisos-exito'];

}


?>

<?= $this->render('_tabsDependencia',['codigo_dependencia' => $codigo_dependencia,'semestral' =>'active']) ?>

<h1 style="text-align: center;"><?= Html::encode($this->title) ?></h1>

<div id="container" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
<div id="novedades_grafico" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>

<br>

<div class="row">
  <div class="col-md-12">
  	<table class="table table-striped">
  		<thead>
  			<tr>
  				<th style="text-align: center;" colspan="3">Total Novedades</th>
  			</tr>
  			<tr>
  				<th style="text-align: center;">Visitas</th>
  				<th style="text-align: center;">Capacitaciones</th>
  				<th style="text-align: center;">Pedidos</th>
  			</tr>
  		</thead>
  		<tbody>
  			<tr>
  				<td style="text-align: center;"><?=$cantidad_visitas ?></td>
  				<td style="text-align: center;"><?= $cantidad_capacitacion?></td>
  				<td style="text-align: center;"><?= $cantidad_pedido ?></td>
  			</tr>
  		</tbody>
  	</table>
  </div>	
</div>

<h3 class="text-center" >Total : <span class="text-danger"> <?= $total_novedades ?> </span></h3>

<br>

<div class="row">
	<div class="col-md-6">
		<table class="table table-striped">
			<thead>
				<tr>
					<th colspan="2" style="text-align: center;">Primer Semestre</th>
				</tr>
				<tr>
					<th style="text-align: center;">Total Visitas</th>
					<th style="text-align: center;">Calif%</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td style="text-align: center;"><?= $primerSemestre?></td>
					<td style="text-align: center;"><?= $califPrimerSemestre.'%' ?></td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="col-md-6">
		<table class="table table-striped">
			<thead>
				<tr>
					<th colspan="2" style="text-align: center;">Segundo Semestre</th>
				</tr>
				<tr>
					<th style="text-align: center;">Total Visitas</th>
					<th style="text-align: center;">Calif%</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td style="text-align: center;"><?= $segundoSemestre?></td>
					<td style="text-align: center;"><?= $califSegundoSemestre.'%' ?></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

<h3 class="text-center">Promedio Anual: <span class="text-danger"><?= $promedio_anual."%" ?></span></h3>
<br>

<h3 class="text-center">Visitas</h3>

<div class="row">
	<div class="col-md-12">
		<table class="table table-striped display my-data"  data-page-length='20'>
			<thead>
				<tr>
					<th></th>
					<th>Dependencia</th>
					<th>Usuario</th>
					<th>Atendio</th>
					<th>Fecha Visita</th>
					<th>Semestre</th>
					<th>estado</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($queryVisitas as $vs):?>
					<tr>
						<td>
							<?php 
								echo  Html::a('<i class="fa fa-eye"></i>', Yii::$app->request->baseUrl.'/visita-mensual/view?id='.$vs->id.'&dependencia='.$vs->centro_costo_codigo,['class'=>'btn btn-info btn-sm']);

								echo Html::a('<i class="fa fa-trash"></i>', Yii::$app->request->baseUrl.'/visita-mensual/delete?id='.$vs->id,['data-confirm'=>'Seguro desea eliminar esta visita(Todos los documentos adjuntos seran eliminados)?','class'=>'btn btn-danger btn-sm']
	                    );
							?>
						</td>
						<td><?= $vs->dependencia->nombre ?></td>
						<td><?= $vs->usuario ?></td>
						<td><?= $vs->atendio ?></td>
						<td><?= $vs->fecha_visita ?></td>
						<td><?= $vs->semestre ?></td>
						<td><?= $vs->estado ?></td>
					</tr>
				<?php endforeach;?>
			</tbody>
		</table>	
	</div>
</div>

<script type="text/javascript">
	Highcharts.chart('container', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Mayores novedades generadas'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                }
            }
        }
    },
    series: [{
        name: 'Total',
        colorByPoint: true,
        data:<?= $mayoresNovedades ?>
    }]
});

/************************************/
Highcharts.chart('novedades_grafico', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Novedades Por Tema'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                }
            }
        }
    },
    series: [{
        name: 'Total',
        colorByPoint: true,
        data:<?= $array_novedades ?>
    }]
});
</script>