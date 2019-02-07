<script src="https://code.highcharts.com/highcharts.src.js"></script>
<?php 

use yii\helpers\Html;
use yii\helpers\Url;
//use miloschuman\highcharts\Highcharts;
$this->title = 'Pre-facturas';
$permisos = array();
if( isset(Yii::$app->session['permisos-exito']) ){
    $permisos = Yii::$app->session['permisos-exito'];
}

?>

 <h1 style="text-align: center;"><?= Html::encode($this->title) ?></h1>

<a href="<?php echo Url::toRoute('prefactura-fija/index')?>" class="btn btn-primary btn-lg">
<i class="fa fa-user-secret"></i>
	Prefactura Humana
</a>


<a href="<?php echo Url::toRoute('prefacturaelectronica/index')?>" class="btn btn-primary btn-lg">
<i class="fas fa-video"></i>
	Prefactura Electronica
</a>

<?php if(in_array("prefactura-administracion-supervision", $permisos)){     ?>

<a href="<?php echo Url::toRoute('adminsupervision/index')?>" class="btn btn-primary btn-lg">
<i class="fa fa-users"></i>
   Administración y supervisión
</a>

<?php }?>

<br><br>


<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Dependencias Prefacturadas</a></li>
    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Gastos Por Regional</a></li>
    
</ul>


<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="home">
    	<div id="container">

		</div>
    </div>
    <div role="tabpanel" class="tab-pane" id="profile">...</div>
    
</div>

<script type="text/javascript">
	///GRAFICO DE DEPENDENCIAS PREFACTURADAS
	Highcharts.chart('container', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Prefacturas por regional'
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        type: 'category',
        labels: {
            rotation: -45,
            style: {
                fontSize: '13px',
                fontFamily: 'Verdana, sans-serif'
            }
        }
    },
    yAxis: {
        min: 0,
        title: {
            text: ''
        }
    },
    legend: {
        enabled: false
    },
    tooltip: {
        pointFormat: '<b>{point.y} </b>'
    },
    series: [{
        name: 'Population',
        data: <?= $json?>,
        dataLabels: {
            enabled: true,
            rotation: -90,
            color: '#FFFFFF',
            align: 'right',
            format: '{point.y}', // one decimal
            y: 10, // 10 pixels down from the top
            style: {
                fontSize: '13px',
                fontFamily: 'Verdana, sans-serif'
            }
        }
    }]
});
/////////////////////////////////////////////////////////////////////////////

</script>