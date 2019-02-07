<script src="https://code.highcharts.com/highcharts.src.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use kartik\date\DatePicker;
use kartik\widgets\Select2;
use yii\widgets\Pjax;
use app\models\DetalleGestionRiesgo;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $searchModel app\models\GestionRiesgoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Desempeño SG-SST';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1 class="text-center"><?= Html::encode($this->title) ?></h1>
<?php //Pjax::begin(); ?>
<form id="form_excel" method="post" action="<?php echo Url::toRoute('gestionriesgo/informe-novedades')?>" data-pjax=''>
        <div class="row">
            <!--<div class="navbar-form navbar-right" role="search">-->
                <div class="col-md-3">
                    <input type="text" id="buscar" name="buscar" class="form-control" placeholder="Buscar Coincidencias" value="<?= $_POST['buscar']!=''?$_POST['buscar']:'' ?>">
                </div>
                <div class="col-md-3">
                    <?php 
                        echo Select2::widget([
                            'id' => 'dependencias2',
                            'name' => 'dependencias2',
                            'value' => $_POST['dependencias2']!=''?$_POST['dependencias2']:'',
                            'data' => $data_dependencias,
                            'options' => ['multiple' => false, 'placeholder' => 'POR DEPENDENCIA...'],
                             'pluginOptions' => [
                                'allowClear' => true
                            ]
                        ]);
                    ?>
                </div>
                <div class="col-md-3">
                    <select id="ordenado" name="ordenado" class="form-control">
                        <option value="" >[ORDENAR POR...]</option>
                        <option value="fecha" <?= $_POST['ordenado']=='fecha'?'selected':'' ?>>Fecha</option>
                        <option value="dependencia" <?= $_POST['ordenado']=='dependencia'?'selected':'' ?>>Dependencia</option>
                        <option value="marca" <?= $_POST['ordenado']=='marca'?'selected':'' ?>>Marca</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="forma" name="forma" class="form-control">
                        <option value="">[FORMA...]</option>
                        <option value="SORT_ASC" <?= $_POST['forma']=='SORT_ASC'?'selected':'' ?>>Ascendente</option>
                        <option value="SORT_DESC" <?= $_POST['forma']=='SORT_DESC'?'selected':'' ?>>Descendente</option>
                    </select>
                </div>
            <!--</div>-->
        </div>
        <br>
        <div class="row">
            <!--<div class="navbar-form navbar-right" role="search">-->

                <div class="col-md-3">
                    <?php 
                        echo Select2::widget([
                            'id' => 'marcas2',
                            'name' => 'marcas2',
                            'value' => $_POST['marcas2']!=''?$_POST['marcas2']:'',
                            'data' => $data_marcas,
                            'options' => ['multiple' => false, 'placeholder' => 'POR MARCA...'],
                             'pluginOptions' => [
                                'allowClear' => true
                            ]
                        ]);
                    ?>
                </div>



                <div class="col-md-3">
                    <?= 
                        DatePicker::widget([
                            'id' => 'desde',
                            'name' => 'desde',
                            'value'=>$_POST['desde']!=''?$_POST['desde']:'',
                            'options' => ['placeholder' => 'Fecha Desde'],
                            'pluginOptions' => [
                                'format' => 'yyyy-mm-dd',
                                'todayHighlight' => true
                            ]
                        ]);
                    ?>
                </div>
                <div class="col-md-3">
                    <?= 
                        DatePicker::widget([
                            'id' => 'hasta',
                            'name' => 'hasta',
                            'value'=>$_POST['hasta']!=''?$_POST['hasta']:'',
                            'options' => ['placeholder' => 'Fecha Hasta'],
                            'pluginOptions' => [
                                'format' => 'yyyy-mm-dd',
                                'todayHighlight' => true
                            ]
                        ]);
                    ?>
                </div>

                <div class="col-md-3">
                    <?php 
                        echo Select2::widget([
                            'id' => 'regional',
                            'name' => 'regional',
                            'value' => $_POST['regional']!=''?$_POST['regional']:'',
                            'data' => $regionales,
                            'options' => ['multiple' => false, 'placeholder' => 'POR REGIONAL...'],
                             'pluginOptions' => [
                                'allowClear' => true
                            ]
                        ]);
                    ?>
                </div>

            <!--</div>-->
        </div>

        <div class="row">
            <div class="navbar-form navbar-right" role="search">
                <button type="button" class="btn btn-primary" onclick="excel()">
                    <i class="fas fa-file-excel"></i> Descargar Busqueda en Excel
                </button> 
                <button type="submit" class="btn btn-primary" id="btn_buscar">
                    <i class="fa fa-search fa-fw"></i> Buscar
                </button>
            </div>
        </div>
        
        
    </form>

<div id="info"></div>

<div class="row">
    <div class="col-md-6">
        <div id="container2"></div>
    </div>
    <div class="col-md-6">
        <div id="container"></div>
    </div>
</div>


<?php 
    echo LinkPager::widget([
    'pagination' => $pagination,
]);
?>

<span >Pagina <b><?=$page?></b> de <b><?=$count?></b> Registros </span>
<div class="table-responsive">
<table class="table table-striped " data-page-length='30'>
    <thead>
        <tr>
            <th>Id</th>
            <th>Fecha</th>
            <th>Dependencia</th>
            <th>Fecha Visita</th>
            <th>Marca</th>
            <th>Regional</th>
            <th>Usuario</th>
            <th>Observacion</th>
            <th></th>
            <?php/* foreach($temas as $tm):?>
            <th style="color: red;"><?= $tm->descripcion?></th>
            <th>Observacion</th>
            <th>Plan de accion</th>
            <?php endforeach;*/?>
        </tr>
    </thead>
    <tbody>
        <?php foreach($gestiones as $gs): ?>
        <tr>
            <td><?= $gs['id']?></td>
            <td><?= $gs['fecha']?></td>
            <td><?= $gs['Dependencia']?></td>
            <td><?= $gs['fecha_visita']?></td>
            <td><?= $gs['Marca']?></td>
            <td><?= $gs['regional']?></td>
            <td><?= $gs['usuario']?></td>
            <td><?= $gs['observacion']?></td>
            <td>
                <?php 
                    echo Html::a('<i class="fa fa-eye" aria-hidden="true"></i>',Yii::$app->request->baseUrl.'/gestionriesgo/view?id='.$gs['id'],['title'=>'ver','class'=>'btn btn-info btn-xs']);

                    echo Html::a('<i class="fa fa-trash"></i>',Yii::$app->request->baseUrl.'/centro-costo/delete?id='.$gs['id'],['data-method'=>'post','data-confirm'=>'Seguro desea eliminar?','class'=>'btn btn-danger btn-xs']);  

                ?>
            </td>
            <?php /*foreach($temas as $tm):
                $detalle=DetalleGestionRiesgo::detalle_gestion($gs->id,$tm->id);
            ?>
            <td><?= $detalle->respuesta->descripcion?></td>
            <td><?= $detalle->observaciones?></td>
            <td><?= $detalle->planes_de_accion?></td>
            <?php endforeach;*/?>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>
</div>


<script type="text/javascript">
    
    $(function(){
        $('table').removeClass('table-bordered');
        $('#btn_buscar').click(function(event) {
            $('#form_excel').attr({
            action: '<?php echo Url::toRoute('gestionriesgo/informe-novedades')?>',
            });
            $('#info').html('<i class="fa fa-gear fa-spin"></i>.....');

        });


        Highcharts.chart('container', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Porcentaje de gestiones'
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
        data: <?= $array_gestiones?>
    }]
});
        
    });

///////////////////////////////////////

Highcharts.chart('container2', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Porcentaje de cumplimiento'
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
        data:<?= $arreglo_temas?>
    }]
});

    function excel(){
        $('#form_excel').attr({
            action: '<?php echo Url::toRoute('gestionriesgo/informe-excel')?>',
        });

        $('#form_excel').submit();
    }

</script>
<?php //Pjax::end(); ?>