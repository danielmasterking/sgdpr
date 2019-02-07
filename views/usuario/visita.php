<script src="https://code.highcharts.com/highcharts.src.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<?php 

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\datecontrol\DateControl;
$this->title = 'Visitas Quincenal '.$usuario;


?>

<?= $this->render('_tabs',['visitas' => $visitas,'usuario' => $usuario]) ?>
<br>
<?= $this->render('_tabs_visita',['indicador' => 'active','usuario' => $usuario]) ?>

   <h1 style="text-align: center;"><?= Html::encode($this->title) ?></h1>
   <!-- FILTRO DE FECHAS -->
   <form method="post" class="form-inline"> 
        <div class="form-group">
        <?php


            echo DateControl::widget([
            'name'=>'fecha_inicial', 
            'type'=>DateControl::FORMAT_DATE,
            'autoWidget' => true,
            'value'=>$fecha_inicio,
            'displayFormat' => 'php:Y-m-d',
            'saveFormat' => 'php:Y-m-d'

             ]);
        ?>
        </div>

        <div class="form-group">
            <?php


                echo DateControl::widget([
                'name'=>'fecha_final', 
                'type'=>DateControl::FORMAT_DATE,
                'autoWidget' => true,
                'value'=>$fecha_final,
                'displayFormat' => 'php:Y-m-d',
                'saveFormat' => 'php:Y-m-d'

                 ]);
            ?>
        </div>

        <input type="submit" name="consultar" class="btn btn-primary" value="Consultar"/>
    </form>
        
   <!--  -->


    <div class="row">
		<div class="col-md-12">
			<div id="container_bueno" style="height: 350px;"></div>		
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div id="container_negativo" style="height: 350px;"></div>		
		</div>
	</div>

<div class="row">
	<div class="col-md-8 col-md-offset-3">
		<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
		<?php foreach($zonasUsuario as $zn): ?>



		  <div class="panel panel-default">
		    <div class="panel-heading" role="tab" id="headingOne">
		      <h4 class="panel-title">
		        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $zn->zona_id?>" aria-expanded="true" aria-controls="collapseOne">
		          <?= $zn->zona->nombre?>
		        </a>
		      </h4>
		    </div>
		    <div id="collapse<?= $zn->zona_id?>" class="panel-collapse collapse " role="tabpanel" aria-labelledby="headingOne">
		      <div class="panel-body">
		       <!--  -->
		       <div class="col-md-12">
			       <table class="table table-striped">
			       	<?php 
			       		$calif_anual=0;
                        $dependencias_reg=$model_visita->dependencias_regional($zn->zona->id,$usuario);
                        $num_dep=count($dependencias_reg);
			       		foreach($arr_meses as $num_mes=>$nom_mes): 
			       	?>
			       	<tr>
			       		<th><?= $nom_mes ?></th>
			       		<td>
			       			<?php 

			       				
			       				$calif_mes_total=0;
                                $num_visita=0;
                                $calif_mes=0;
			       				foreach ($dependencias_reg as $dep) {

			       					$num_visita= $model_visita->Num_visitas($num_mes,$dep,$fecha_inicio,$fecha_final);

			       					
					                if ($num_visita==0) {

					                    $califc=0;

					                }elseif($num_visita>=2){

					                   $califc=100;

					                }elseif($num_visita<2){
					                    
					                    $califc=50;
					                }
			       					//echo $dep['nombre']."---".$num_visita."<br>";

					                //$calif_mes=round(($califc*8.33)/100, 2, PHP_ROUND_HALF_DOWN);
                                    $calif_mes+=$califc;
					                //$calif_mes_total+=round($califc/$num_dep, PHP_ROUND_HALF_DOWN);


			       				}

                                if($calif_mes==0 or $num_dep==0){
                                    $calif_mes_total=0;    
                                }else{
                                    $calif_mes_total=round($calif_mes/$num_dep, 2,PHP_ROUND_HALF_DOWN);    
                                }
                                
			       				$calif_anual+=$calif_mes_total;
			       				echo "<span class='text-danger'>".$calif_mes_total." %</span>";
			       			?>
			       		</td>
			       	</tr>
			       <?php endforeach;?>
			       <tfoot>
			       	<tr>
			       		<th>Total: </th>
			       		<td><?= round($calif_anual/12,2,PHP_ROUND_HALF_DOWN)." %" ?></td>
			       	</tr>
			       </tfoot>
			       </table>
			    </div>

		       <!--  -->
		      </div>
		    </div>
		  </div>

		  

		<?php endforeach;?>
		</div>

	</div>

</div>


<!-- <form class="form-inline">
	<div class="form-group">
    
    <input type="text" class="form-control" id="buscar" placeholder="...Buscar">
  </div>
  <button type="button" class="btn btn-primary" onclick="consultar(0)"><i class="fa fa-search"></i> Buscar</button>

  
</form> -->


<div class="row">
	<div class="col-md-12">
	
		<div id="info"></div>
		<div id="partial"><?=$partial?></div>
	</div>
</div>


<script type="text/javascript">
$(document).on( "click", "#partial .pagination li", function() {
        var page = $(this).attr('p');
        consultar(page);
    });

function consultar(page){
      
        var buscar=$("#buscar").val();
        
        $.ajax({
            url:"<?php echo Url::toRoute('usuario/visita?id='.$usuario)?>",
            type:'POST',
            dataType:"json",
            cache:false,
            data: {
               
                buscar: buscar, 
                page: page
                
            },
            beforeSend:  function() {
                $('#info').html('Cargando... <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>');
            },
            success: function(data){
                $("#partial").html(data.respuesta);
                $("#info").html('');
            }
        });
    }


	Highcharts.chart('container_bueno', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Estadistica general'
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
        name: 'Porcentaje',
        colorByPoint: true,
        data: <?= $json_bueno?>
    }]
});

/////////////////////////////////////////////////////////////////////////
Highcharts.theme = {
    colors: ['#c0392b','#f4d03f','#dc7633',' #52be80 '],
    chart: {
        backgroundColor: null,
        style: {
            fontFamily: 'Dosis, sans-serif'
        }
    },
    title: {
        style: {
            fontSize: '16px',
            fontWeight: 'bold',
            textTransform: 'uppercase'
        }
    },
    tooltip: {
        borderWidth: 0,
        backgroundColor: 'rgba(219,219,216,0.8)',
        shadow: false
    },
    legend: {
        itemStyle: {
            fontWeight: 'bold',
            fontSize: '13px'
        }
    },
    xAxis: {
        gridLineWidth: 1,
        labels: {
            style: {
                fontSize: '12px'
            }
        }
    },
    yAxis: {
        minorTickInterval: 'auto',
        title: {
            style: {
                textTransform: 'uppercase'
            }
        },
        labels: {
            style: {
                fontSize: '12px'
            }
        }
    },
    plotOptions: {
        candlestick: {
            lineColor: '#404048'
        }
    },


    // General
    background2: '#F0F0EA'

};

// Apply the theme
Highcharts.setOptions(Highcharts.theme);

Highcharts.chart('container_negativo', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Mayores Novedades generadas'
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
        name: 'Porcentaje',
        colorByPoint: true,
        data:  <?= $json_negativo?>
    }]
});

</script>	