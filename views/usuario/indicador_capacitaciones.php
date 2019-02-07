<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<?php 
use yii\helpers\Html;
use app\models\NovedadDependencia;
use kartik\datecontrol\DateControl;

$this->title = 'Capacitaciones '.$usuario;
?>

<?= $this->render('_tabs',['capacitaciones' =>'active','usuario' => $usuario]) ?>
<br>
<?= $this->render('_tabsCapacitaciones',['indicador' => 'active','usuario' => $usuario]) ?>

<h1 class="text-center"><?= $this->title ?></h1>

<form action="?id=<?= $usuario?>" method="post">

	<div class="row">
		<div class="col-md-4">
			<?php 
				echo DateControl::widget([
				    'name'=>'inicio', 
				    'value'=>$inicio,
				    'type'=>DateControl::FORMAT_DATE,
				    'autoWidget'=>true,
				    'displayFormat' => 'php:Y-m-d',
				    'saveFormat' => 'php:Y-m-d'
				]);
			?>
			
		</div>
		<div class="col-md-4">
			<?php 
				echo DateControl::widget([
				    'name'=>'final', 
				    'value'=>$final,
				    'type'=>DateControl::FORMAT_DATE,
				    'autoWidget'=>true,
				    'displayFormat' => 'php:Y-m-d',
				    'saveFormat' => 'php:Y-m-d'
				]);
			?>
		</div>
		
		<button class="btn btn-primary" >
			<i class="fa fa-search"></i> Buscar
		</button>
	</div>
	
</form>
<div class="row">
	<div class="col-md-12">
		<div id="container"></div>
	</div>
</div>

<div class="row">
    <div class="col-md-12 ">
    	<div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="text-center">Novedad</th>
                    <th class="text-center">Personas capacitadas</th>
                    <th class="text-center">Capacitaciones Realizadas</th>
                </tr>
            </thead>
            <tbody class="text-center">

                <?php 
                	$i=0; 
                	$totalPersonas=0;
                    $totalCapacitaciones=0;
                	foreach($capacitaciones_tema as $cpt): 
                ?>
                <tr>
                    <td><?= $cpt['name'].":" ?></td>
                    <td><?= $cpt['y'] ?></td>
                    <td><?= $cpt['capacitaciones'] ?></td>
                </tr>
                <?php 
                	$i++; 
                	$totalPersonas+=$cpt['y'];
                    $totalCapacitaciones+=$cpt['capacitaciones'];
                	endforeach;
               	?>
               	<tr>
                    <th class="text-center">Total:</th>
                    <td><?= $totalPersonas ?></td>
                    <td><?= $totalCapacitaciones ?></td>
                </tr>
            </tbody>
        </table>
        </div>
    </div>
</div>


<h3 id="promedioUsuario" class="text-center"></h3>

<div class="row">
	<div class="col-md-12">
		<div class="table-responsive">
		<table class="table table-striped my-data" data-page-length="50" id="tbl_dep">
			<thead>
				<tr>
					<th></th>
					<th>Dependencia</th>
					<th>Cantidad capacitaciones</th>
					<th>Cantidad personas capacitadas</th>
					<th>Seguridad en Retail</th>
					<th>Vigías Protección de Recursos</th>
					<th>Anual</th>
					<th style="display: none;"></th>
				</tr>

			</thead>
			<tbody>
				<?php 
				$deps_num=0;
				foreach($dependencias as $row):
					$califSegretail=NovedadDependencia::CalificacionTema(20,$row['codigo'],$inicio,$final);
					$califVigia=NovedadDependencia::CalificacionTema(21,$row['codigo'],$inicio,$final);
					$cantidad=NovedadDependencia::ContarCapacitaciones($row['codigo'],$inicio);

					if($row->indicador_capacitacion=='S'){
						$deps_num=$deps_num+1;
					}

				?>
				<tr>
					<td>
						<?php echo Html::a('<i class="fa fa-eye"></i>',Yii::$app->request->baseUrl.'/centro-costo/capacitacion?id='.$row['codigo'],['target'=>'_blank','class'=>'btn btn-info btn-sm']); ?>
					</td>
					<td><?= $row->nombre?></td>
					<td><?= $cantidad['capacitaciones']?></td>
					<td>
						<?php 
							if($cantidad['cantidad']==''){

								echo 0;
							}else{

								echo $cantidad['cantidad'];
							}

						?>
						
					</td>
					<td>
						<?php 
							if($row->indicador_capacitacion=='S'){
								echo round($califSegretail,2,PHP_ROUND_HALF_DOWN)."%" ;
							}else{

								echo "<i class='fa fa-times-circle text-info' ></i> ";
							}

						?>
								
					</td>
					<td>
						<?php
							if($row->indicador_capacitacion=='S'){ 
								echo round($califVigia,2,PHP_ROUND_HALF_DOWN)."%" ;
							}else{

								echo "<i class='fa fa-times-circle text-info' ></i> ";
							}
						?>	
					</td>
					<td>
						<?php 
							if($row->indicador_capacitacion=='S'){ 
								$califAnual=($califSegretail+$califVigia)/2;
								echo round($califAnual,2,PHP_ROUND_HALF_DOWN)."%";
							}else{

								echo "<i class='fa fa-times-circle text-info' ></i> ";
							}
						?>
					</td>
					<td style="display: none;">
						<?php echo $row->indicador_capacitacion ?>
					</td>
				</tr>
				<?php endforeach;?>
			</tbody>

		</table>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function(){
		var totalAnual=0;
		var count=0;
		$('#promedioUsuario').html('Cargando porcentaje ... <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>');

		$('#tbl_dep tbody tr').each(function(index, el) {
			var anual=$(this).find('td').eq(6).html().replace("%", "");
			var aplica=$(this).find('td').eq(7).html().toString();
			if(isNaN(anual)){
				anual=0;
			}
			
			totalAnual=totalAnual+parseInt(anual);
			//if(aplica=="S"){
				count=count+1;
			//}
		});

		var calif=totalAnual/<?=$deps_num?>;
		
		$('#promedioUsuario').html('Porcentaje Consolidado de Capacitación: <span class="text-danger">'+calif.toFixed(2)+'%</span>');

	});

	Highcharts.chart('container', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: '%capacitaciones por tema'
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
        name: 'Brands',
        colorByPoint: true,
        data: <?= $torta ?>
    }]
});
</script>