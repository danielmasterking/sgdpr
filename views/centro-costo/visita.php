<script src="https://code.highcharts.com/highcharts.src.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/highcharts-3d.js"></script>

<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\ValorNovedad;
use kartik\datecontrol\DateControl;
/* @var $this yii\web\View */
/* @var $searchModel app\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Visitas Quincenales';
if( isset(Yii::$app->session['permisos-exito']) ){

  $permisos = Yii::$app->session['permisos-exito'];

}


?>
    <?= $this->render('_tabsDependencia',['codigo_dependencia' => $codigo_dependencia,'visita' => $visita]) ?>

   
   
	
	<div class="form-group">

	<?= Html::a('Solicitud o Activación',Yii::$app->request->baseUrl.'/centro-costo/evento?id='.$codigo_dependencia,['class'=>'btn btn-primary']) ?>
	<?= Html::a('Semestral',Yii::$app->request->baseUrl.'/centro-costo/mensual?id='.$codigo_dependencia,['class'=>'btn btn-primary']) ?>


	</div>	
	<h1 style="text-align: center;"><?= Html::encode($this->title) ?></h1>


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
        
	<!---->
    
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
  	 

    <!---->
<div class="row">

    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <?php 

            $calif_ano=0;
            foreach ($arr_meses as $key_mes => $value_mes) {
            

                $num_visita= $model_visita->Num_visitas($key_mes,$codigo_dependencia,$fecha_inicio,$fecha_final);

                if ($num_visita==0) {

                    $calif=0;

                }elseif($num_visita>=2){

                   $calif=100;

                }elseif($num_visita<2){
                    
                    $calif=50;
                }


                $calif_mes=round(($calif*8.33)/100, 2, PHP_ROUND_HALF_DOWN);

                $calif_ano+=$calif_mes;

        ?>  

    <div class="col-md-6">
      <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="headingOne">
          <h4 class="panel-title">
            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $key_mes ?>" aria-expanded="true" aria-controls="collapseOne">
           
              <i class="fa fa-calendar"></i> <?= $value_mes." <span class='text-danger'> ".$calif."% </span>"?> 
            </a>
          </h4>
        </div>
        <div id="collapse<?= $key_mes ?>" class="panel-collapse collapse " role="tabpanel" aria-labelledby="headingOne">
          <div class="panel-body">
            <table class="table table-striped">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Calif %</th>
                </tr>
            </thead>
            
            <tbody>
            <?php 
                $visitas_ano=$model_visita->Visitas($key_mes,$codigo_dependencia,$fecha_inicio,$fecha_final);

                $cont_visita=0;

                foreach ($visitas_ano as $key_visita => $value_visita) {
                                   
            ?>
            <tr>
                <td><a href="<?= Yii::$app->request->baseUrl.'/visita-dia/view?id='.$value_visita->id.'&dependencia='.$codigo_dependencia ?>"><?= $value_visita->fecha ?></a></td>
                <td>
                    <?php 

                        $det_visita=$model_visita->Detalle_visitas($value_visita->id);

                        $porcentaje=0;
                        foreach ($det_visita as $value_detalle) {
                            $valor_calif=ValorNovedad::porcentaje($value_detalle->novedad->id,$value_detalle->resultado->id);
                            //$valor_calif=0;
                            $porcentaje+=$valor_calif;
                        }

                        echo $porcentaje."%";
                    ?>
                </td>
            </tr>

            <?php
            $cont_visita++;
            }
            ?>
            </tbody>

            </table>

            <?php if($cont_visita>=2): ?>

                <div class="alert alert-success" role="alert"><i class="fa fa-thumbs-o-up"></i> Cumple</div>

            <?php else: ?>
                <div class="alert alert-danger" role="alert"><i class="fa  fa-thumbs-o-down"></i> No Cumple</div>
            <?php endif; ?>

          </div>
        </div>
      </div>
    </div>
      <?php
        }
       ?>  
  
    </div>
</div>

<h3>Calif Anual : <span class="text-danger"><?= $calif_ano."%"?></span></h3>

<br>

<div class="row">
    <div class="col-md-12 table-responsive">
    	<table  class="display my-data" data-page-length='20' cellspacing="0" width="100%">
    	 
           <thead>

           <tr>
               
               <th></th>
    		   <th>Codigo</th>
               <th>Fecha</th>
    		   <th>Creada</th>
    		   <th>Observaciones</th>
              
           </tr>
               

           </thead>	 
    	   
    	   <tbody>
    	   
                 <?php foreach($visitas as $visita):?>	  
    			   
                  <tr>			   
    			   <td><?php
    			   echo Html::a('<i class="fa fa-eye"></i>',Yii::$app->request->baseUrl.'/visita-dia/view?id='.$visita->id.'&dependencia='.$codigo_dependencia,['title'=>'ver','class'=>'btn btn-info btn-sm']);
                   
    			   if(in_array("administrador", $permisos) ){
    				   
    				 // echo Html::a('<i class="fa fa-pencil fa-fw"></i>',Yii::$app->request->baseUrl.'/capacitacion/update?id='.$capacitacion->capacitacion_id);
                      echo Html::a('<i class="fa fa-trash"></i>',Yii::$app->request->baseUrl.'/visita-dia/delete?id='.$visita->id.'&dependencia='.$codigo_dependencia,['data-method'=>'post', 'data-confirm' => 'Está seguro de eliminar este elemento','class'=>'btn btn-danger btn-sm']);
      
    			   }
    			   
    			   //echo Html::a('<i class="fa fa-pencil fa-fw"></i>',Yii::$app->request->baseUrl.'/siniestro/update?id='.$siniestro->id);
                   //echo Html::a('<i class="fa fa-remove"></i>',Yii::$app->request->baseUrl.'/siniestro/delete?id='.$siniestro->id,['data-method'=>'post']);

                        ?>
    				</td>
                    <td><?= $visita->id?></td>
         			<td><?= $visita->fecha?></td>
    				<td><?= $visita->usuario?></td>
    				<td><?= $visita->observaciones?></td>
    				
                  </tr>
            	 <?php endforeach; ?>			 
    	   
    	   </tbody>
    	 
    	 </table>
     </div>
</div>
<script type="text/javascript">
	 	

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