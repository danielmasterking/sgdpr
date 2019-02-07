<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\VisitaMensual;
use kartik\datecontrol\DateControl;
/* @var $this yii\web\View */
/* @var $searchModel app\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Inspeccion Semestral-'.$usuario;
if( isset(Yii::$app->session['permisos-exito']) ){

  $permisos = Yii::$app->session['permisos-exito'];

}


?>
<?= $this->render('_tabs',['semestral' =>'active','usuario' => $usuario]) ?>

<h1 class="text-center"><?= $this->title ?></h1>

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

<h3 id="promedioUsuario" class="text-center"></h3>

<div class="row">
	<div class="col-md-12">
		<table class="table table-striped my-data" data-page-length="50" id="tbl_dep">
			<thead>
				<tr>
					<th></th>
					<th>Dependencia</th>
					<th>Primer Semestre</th>
					<th>Segundo Semestre</th>
					<th>Promedio Anual</th>
				</tr>

			</thead>
			<tbody>
				<?php 
				foreach($dependencias as $row):
					
				?>
				<tr>
					<td>
						<?php echo Html::a('<i class="fa fa-eye"></i>',Yii::$app->request->baseUrl.'/centro-costo/insp-semestral?id='.$row['codigo'],['target'=>'_blank']); ?>
					</td>
					<td><?= $row->nombre?></td>
					<td>
						<?php 

							if($row->indicador_semestre=='S'){
								$primerSemestre=VisitaMensual::CalifSemestre($row['codigo'],1);
								echo $primerSemestre."%";
							}elseif($row->indicador_semestre=='N'){
								echo "<i class='fa fa-times-circle text-info' ></i> ";
							}
						?>
					</td>
					<td>
						<?php 
							if($row->indicador_semestre=='S'){
								$segundoSemestre=VisitaMensual::CalifSemestre($row['codigo'],2);
								echo $segundoSemestre."%";
							}elseif($row->indicador_semestre=='N'){
								echo "<i class='fa fa-times-circle text-info' ></i> ";
							}
						?>
					</td>
					<td>
						<?php  
							if($row->indicador_semestre=='S'){
								$totalAno=($primerSemestre+$segundoSemestre)/2;
								echo $totalAno."%";
							}elseif($row->indicador_semestre=='N'){
								echo "<i class='fa fa-times-circle text-info' ></i> ";
							}
						?>
					</td>
				</tr>
				<?php endforeach;?>
			</tbody>

		</table>
	</div>
</div>


<script type="text/javascript">

	$(function(){
		var totalAnual=0;
		var count=0;
		$('#promedioUsuario').html('Cargando porcentaje ... <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>');

		$('#tbl_dep tbody tr').each(function(index, el) {
			var anual=$(this).find('td').eq(4).html().replace("%", "");
			if(isNaN(anual)){
				anual=0;
			}

			totalAnual=totalAnual+parseInt(anual);
			count=count+1;
		});
		//alert(count);

		var calif=totalAnual/count;
		
		$('#promedioUsuario').html('Consolidado Dependencias: <span class="text-danger">'+calif.toFixed(2)+'%</span>');

	});

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
