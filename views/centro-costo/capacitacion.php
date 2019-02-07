<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Capacitaciones';
$permisos = array();

if( isset(Yii::$app->session['permisos-exito']) ){

  $permisos = Yii::$app->session['permisos-exito'];

}



?>
    <?= $this->render('_tabsDependencia',['codigo_dependencia' => $codigo_dependencia,'capacitacion' => $capacitacion]) ?>

    <?php 
        echo Html::a('<i class="fas fa-cogs"></i> Configurar indicador',Yii::$app->request->baseUrl.'/centro-costo/conf_capacitacion?&dependencia='.$codigo_dependencia,['class'=>'btn btn-primary']); 
    ?>
    
    <br><br>

    <div class="row">
        <div class="col-md-12">
            <div id="container"></div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="table-responsive">
            <table class="table table-striped ">
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
    

    <div class="row">
        <div class="col-md-6">
            <table class="table table-striped ">
                <thead >
                    <tr>
                        <th colspan="3" class="text-center" >Primer Semestre</th>
                    </tr>
                    <tr>
                        <th class="text-center">Novedad</th>
                        <th class="text-center">Total Capacitaciones</th>
                        <th class="text-center">Calif%</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php 
                        $capSemprimero=0;
                        $retail_calif=0;
                        $vigias_calif=0;
                        foreach($array_semestre as $as): 
                    ?>
                        <tr>
                            <td><?php echo $as['novedad']?></td>
                            <td><?php echo $as['cantidad']?></td>
                            <td><?php echo $as['calif']."%"?></td>
                        </tr>
                    <?php 
                        if ($as['novedad']=='Seguridad-en-Retail') {
                            $retail_calif+=$as['calif'];

                        }elseif($as['novedad']=='Vigías-Protección-de-Recursos'){
                            $vigias_calif+=$as['calif'];
                        }

                        $capSemprimero+=$as['calif'];
                        endforeach; 

                    ?>
                </tbody>
            </table>
        </div>

        <div class="col-md-6">
            <table class="table table-striped ">
                <thead >
                    <tr>
                        <th colspan="3" class="text-center">Segundo Semestre</th>
                    </tr>
                    <tr >
                        <th class="text-center">Novedad</th>
                        <th class="text-center">Total Capacitaciones</th>
                        <th class="text-center">Calif%</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php 
                        $capSegundo=0;
                        $retail_calif2=0;
                        $vigias_calif2=0;
                        foreach($array_semestre2 as $as2): 
                    ?>
                        <tr>
                            <td><?php echo $as2['novedad']?></td>
                            <td><?php echo $as2['cantidad']?></td>
                            <td><?php echo $as2['calif']."%"?></td>
                        </tr>
                    <?php 
                        if ($as2['novedad']=='Seguridad-en-Retail') {
                            $retail_calif2+=$as2['calif'];

                        }elseif($as2['novedad']=='Vigías-Protección-de-Recursos'){
                            $vigias_calif2+=$as2['calif'];
                        }
                        $capSegundo+=$as2['calif'];
                        endforeach; 
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php 
        $promedio_calif=($capSemprimero+$capSegundo)/4;
        $promedio_retail=($retail_calif+$retail_calif2)/2;
        $promedio_vigias=($vigias_calif+$vigias_calif2)/2;
    ?>

    
    <h3>Seguridad en Retail: <span class="text-danger"><?= round($promedio_retail,2,PHP_ROUND_HALF_DOWN)."%" ?></span></h3>
    <h3>Vigías Protección de Recursos: <span class="text-danger"><?= round($promedio_vigias,2,PHP_ROUND_HALF_DOWN)."%" ?></span></h3>
    <h3> Porcentaje Consolidado de Capacitación: <span class="text-danger"><?= round($promedio_calif,2,PHP_ROUND_HALF_DOWN)."%" ?></span></h3>

    <div class="table-responsive">
    <table  class="display my-data" data-page-length='20' cellspacing="0" width="100%">
     
       <thead>

       <tr>
           
           <th></th>
           <th>Codigo</th>
           <th>Fecha capacitación</th>
           <th>Tema</th>
           <!-- <th>Observaciones</th> -->
          
       </tr>
           

       </thead>  
       
       <tbody>
       
             <?php foreach($capacitaciones as $capacitacion):?>   
               
              <tr>             
               <td><?php
               echo Html::a('<i class="fa fa-eye"></i>',Yii::$app->request->baseUrl.'/capacitacion/view?id='.$capacitacion->capacitacion_id.'&dependencia='.$codigo_dependencia,['title'=>'ver','class'=>'btn btn-info btn-sm']);
               if(in_array("administrador", $permisos) ){
                   
                 // echo Html::a('<i class="fa fa-pencil fa-fw"></i>',Yii::$app->request->baseUrl.'/capacitacion/update?id='.$capacitacion->capacitacion_id);
                  echo Html::a('<i class="fa fa-trash"></i>',Yii::$app->request->baseUrl.'/capacitacion/delete?id='.$capacitacion->capacitacion_id.'&dependencia='.$codigo_dependencia,['data-method'=>'post', 'data-confirm' => 'Está seguro de eliminar este elemento','class'=>'btn btn-danger btn-sm']);
  
               }
              
                    ?>
                </td>
                <td><?= $capacitacion->capacitacion_id?></td>
                <td><?= $capacitacion->capacitacion->fecha_capacitacion?></td>
                <td><?= $capacitacion->capacitacion->novedad->nombre?></td>
                <!-- <td><?php// $capacitacion->capacitacion->observaciones?></td> -->
                
              </tr>
             <?php endforeach; ?>            
       
       </tbody>
     
     </table>
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