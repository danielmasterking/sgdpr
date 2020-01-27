<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use kartik\date\DatePicker;
use kartik\widgets\Select2;
/* @var $this yii\web\View */
/* @var $searchModel app\models\AdminSupervisionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Administración y supervisión';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-supervision-index">

    <div class="page-header">
        <h1><small><i class="fa fa-users"></i></small> <?= Html::encode($this->title) ?></h1>
    </div>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

     <a href="<?php echo Url::toRoute('prefactura-fija/ventana_inicio')?>" class="btn btn-primary">
        <i class="fa fa-reply"></i> Volver a Prefactura
    </a>

    
    <?= Html::a('<i class="fa fa-file"></i> Crear', ['create'], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('<i class="fa  fa-cubes"></i> Dispositivos', ['dispositivoadmin/index'], ['class' => 'btn btn-primary']) ?>
    <br>
    <br>
    
<?php// Pjax::begin(); ?>    

 <form id="form_excel" method="post" action="<?php echo Url::toRoute('adminsupervision/index')?>">
        <div class="row">
            <!--<div class="navbar-form navbar-right" role="search">-->
                <div class="col-md-4">
                    <input type="text" id="buscar" name="buscar" class="form-control" placeholder="Buscar Coincidencias">
                </div>
                
                <div class="col-md-4">
                    <select id="ordenado" name="ordenado" class="form-control">
                        <option value="">[ORDENAR POR...]</option>
                        <option value="fecha">Fecha</option>
                        <option value="mes">Mes</option>
                        <option value="ano">Año</option>
                        
                    </select>
                </div>
                <div class="col-md-4">
                    <select id="forma" name="forma" class="form-control">
                        <option value="">[FORMA...]</option>
                        <option value="SORT_ASC">Ascendente</option>
                        <option value="SORT_DESC">Descendente</option>
                    </select>
                </div>
            <!--</div>-->
        </div>
        <br>
        <div class="row">
            <!--<div class="navbar-form 6-right" role="search">-->

                <div class="col-md-4">
                    <?= 
                        DatePicker::widget([
                            'id' => 'desde',
                            'name' => 'desde',
                            'options' => ['placeholder' => 'Fecha Desde'],
                            'pluginOptions' => [
                                'format' => 'yyyy-mm-dd',
                                'todayHighlight' => true
                            ]
                        ]);
                    ?>
                </div>
                <div class="col-md-4">
                    <?= 
                        DatePicker::widget([
                            'id' => 'hasta',
                            'name' => 'hasta',
                            'options' => ['placeholder' => 'Fecha Hasta'],
                            'pluginOptions' => [
                                'format' => 'yyyy-mm-dd',
                                'todayHighlight' => true
                            ]
                        ]);
                    ?>
                </div>

                <div class="col-md-4">
                    <?php 
                    echo Select2::widget([
                        'name' => 'empresas',
                        'data' => $list_empresas,
                        //'size' => Select2::SMALL,
                        'options' => ['placeholder' => 'Por Empresa ...', 'id'=>'empresas'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
            ?>
                </div>
            <!--</div>-->
        </div>

         <br>
        <div class="row">
            <div class="col-md-4" >
                
                <select class="form-control" id="mes" name="mes">
                    <option value="">Por Mes</option>
                    <option value="01">Enero</option>
                    <option value="02">Febrero</option>
                    <option value="03">Marzo</option>
                    <option value="04">Abril</option>
                    <option value="05">Mayo</option>
                    <option value="06">Junio</option>
                    <option value="07">Julio</option>
                    <option value="08">Agosto</option>
                    <option value="09">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>

                </select>
            </div>
            <div class="col-md-4" >
                
                <select class="form-control" id="ano" name="ano">
                    <option value="">Por año</option>
                    <option value="2017">2017</option>
                    <option value="2018">2018</option>
                    <option value="2019">2019</option>
                    <option value="2020">2020</option>
                </select>
            </div>
        </div>
    </form>


    <div class="row">
        <div class="navbar-form navbar-right" role="search">
            <button type="submit" class="btn btn-primary" onclick="excel()">
                <i class="fas fa-file-excel"></i> Descargar Busqueda en Excel
            </button>
            <button type="submit" class="btn btn-primary" onclick="consultar(0)">
                <i class="fa fa-search fa-fw"></i> Buscar
            </button>
        </div>
    </div>






<div class="row">
    <hr>
    <div id="info"></div>
    <div id="partial"><?=$partial?></div>
</div>

<?php //Pjax::end(); ?></div>
<script type="text/javascript">
     $(document).on( "click", "#partial .pagination li", function() {
        var page = $(this).attr('p');
        consultar(page);
    });
    function consultar(page){
        var form=document.getElementById("form_excel");
        var input=document.getElementById("excel");
        if(input!=null){
            form.removeChild(input);
        }
        var desde=$('#desde').val();
        var hasta=$('#hasta').val();
        var buscar=$("#buscar").val();
        var ordenado=$("#ordenado").val();
        var forma=$("#forma").val();
        var dependecia=$("#dependencias2").val();
        var marca=$("#marcas2").val();
        var mes=$("#mes").val();
        var empresa=$("#empresas option:selected").val();
        var ano=$("#ano option:selected").val();
        $.ajax({
            url:"<?php echo Url::toRoute('adminsupervision/index')?>",
            type:'POST',
            dataType:"json",
            cache:false,
            data: {
                desde: desde,
                hasta: hasta,
                buscar: buscar,
                ordenado: ordenado,
                forma: forma,
                dependencias2: dependecia,
                page: page,
                marca:marca,
                mes:mes,
                empresas:empresa,
                ano:ano
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


    function eliminar(id){
        var url="<?php echo Url::toRoute('adminsupervision/delete')?>";
        var r = confirm('¿Desea eliminar la Pre-factura?');
        if (r == true) {
            location.href=url+"?id="+id;
        }
    }

    function excel(){
        var form=document.getElementById("form_excel");
        var input = document.createElement('input');
        input.type = 'hidden';
        input.id = 'excel';
        input.name = 'excel';
        input.value = '';
        form.appendChild(input);
        form.submit();
    }
</script>