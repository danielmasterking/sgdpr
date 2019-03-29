<style>
.modal-process {
    display:    none;
    position:   fixed;
    z-index:    1000;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .8 ) 
                url('http://i.stack.imgur.com/FhHRx.gif') 
                50% 50% 
                no-repeat;
}

/* When the body has the loading class, we turn
   the scrollbar off with overflow:hidden */
body.loading {
    overflow: hidden;   
}

/* Anytime the body has the loading class, our
   modal element will be visible */
body.loading .modal-process {
    display: block;
}
</style> 
<?php 
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use kartik\date\DatePicker;
use kartik\widgets\Select2;
/* @var $this yii\web\View */
/* @var $searchModel app\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$permisos = array();
if( isset(Yii::$app->session['permisos-exito']) ){
	$permisos = Yii::$app->session['permisos-exito'];
}
$this->title = 'Informe Dispositivos';

$ciudades_zonas = array();//almacena las regionales permitidas al usuario

foreach($zonasUsuario as $zona){
    $ciudades_zonas [] = $zona->zona->ciudades;
}
$ciudades_permitidas = array();
foreach($ciudades_zonas as $ciudades){
    foreach($ciudades as $ciudad){
        $ciudades_permitidas [] = $ciudad->ciudad->codigo_dane;
    }
}

$marcas_permitidas = array();
$data_marcas=array();
foreach($marcasUsuario as $marca){
    $marcas_permitidas [] = $marca->marca_id;
    $data_marcas [$marca->marca->nombre] = $marca->marca->nombre;
}


$empresas_permitidas = array();
foreach($empresasUsuario as $empresa){
    $empresas_permitidas [] = $empresa->nit;
}
$data_dependencias = array();
foreach($dependencias as $dependencia){
    if(in_array($dependencia->ciudad_codigo_dane,$ciudades_permitidas) ){
        if(in_array($dependencia->marca_id,$marcas_permitidas) ){
            if(in_array($dependencia->empresa,$empresas_permitidas) ){
                $data_dependencias[$dependencia->nombre] =  $dependencia->nombre;
            }
        }
    }
}

$arr_tipos=['fijo'=>'Fijo','variable'=>'Variable','admin'=>'Admin y sup'];
?>


<div class="page-header">
    <h1><small><i class="fa fa-briefcase fa-fw"></i></small> <?= Html::encode($this->title) ?></h1>
</div>
<div class="modal-process"></div>

 <form id="form_excel" method="post" action="<?php echo Url::toRoute('prefactura-fija/informedispositivos')?>">
        <div class="row">
            <!--<div class="navbar-form navbar-right" role="search">-->
                <div class="col-md-3">
                    <input type="text" id="buscar" name="buscar" class="form-control" placeholder="Buscar Coincidencias">
                </div>
                <div class="col-md-3">
                    <?php 
                        echo Select2::widget([
                            'id' => 'dependencias2',
                            'name' => 'dependencias2',
                            'value' => '',
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
                        <option value="">[ORDENAR POR...]</option>
                        <option value="fecha">Fecha</option>
                        <option value="mes">Mes</option>
                        <option value="ano">Año</option>
                        <option value="dependencia">Dependencia</option>
                        <option value="empresa">Empresa</option>
                    </select>
                </div>
                <div class="col-md-3">
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
            <!--<div class="navbar-form navbar-right" role="search">-->

                <div class="col-md-3">
                    <?php 
                        echo Select2::widget([
                            'id' => 'marcas2',
                            'name' => 'marca',
                            'value' => '',
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
                            'options' => ['placeholder' => 'Fecha Hasta'],
                            'pluginOptions' => [
                                'format' => 'yyyy-mm-dd',
                                'todayHighlight' => true
                            ]
                        ]);
                    ?>
                </div>

                <div class="col-md-3" >
                
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
            <!--</div>-->
        </div>

        <br>
        <div class="row">
            <div class="col-md-4" >
                
                <select class="form-control" id="ano" name="ano">
                    <option value="">Por año</option>
                    <option value="2017">2017</option>
                    <option value="2018">2018</option>
                    <option value="2019">2019</option>
                </select>
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

             <div class="col-md-4">
                    <?php 
                    echo Select2::widget([
                        'name' => 'zonas',
                        'data' => $list_zonas,
                        //'size' => Select2::SMALL,
                        'options' => ['placeholder' => 'Por zona ...', 'id'=>'zonas'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
            ?>
            </div>
        </div>
        <br>
        <!-- <div class="row">
            <div class="col-md-4">
                
                <?php 
                        echo Select2::widget([
                            'id' => 'tipo',
                            'name' => 'tipo',
                            'value' => '',
                            'data' => $arr_tipos,
                            'options' => ['multiple' => false, 'placeholder' => 'POR TIPO...'],
                            'pluginOptions' => [
                            'allowClear' => true
                            ]
                        ]);
                    ?>
            </div>    
        </div> -->

        <label class="checkbox-inline">
          <input type="checkbox" id="tipo_fijo" value="fijo" name="tipo_fijo"> Fijo
        </label>
        <label class="checkbox-inline">
          <input type="checkbox" id="tipo_variable" value="variable" name="tipo_variable"> Variable
        </label>
        <label class="checkbox-inline">
          <input type="checkbox" id="tipo_admin" value="admin" name="tipo_admin"> Admin y sup
        </label>
        
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

<!--******************************************************************************-->

<div id="info"></div>
<div id='registros' class="col-md-12">
	<?= $res ?>
</div>



<script type="text/javascript">
	
$(document).on( "click", "#registros .pagination li", function() {
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
        var empresa=$('#empresas option:selected').val();
        var zona=$('#zonas option:selected').val();
        var tipo=$('#tipo option:selected').val();
        var ano=$('#ano option:selected').val();

        if( $('#tipo_fijo').is(':checked') ) {

            var tipo_fijo=$('#tipo_fijo').val();
        }else{
            var tipo_fijo=null;
        }

        if( $('#tipo_variable').is(':checked') ) {

            var tipo_variable=$('#tipo_variable').val();
        }else{
            var tipo_variable=null;
        }


        if( $('#tipo_admin').is(':checked') ) {

            var tipo_admin=$('#tipo_admin').val();
        }else{
            var tipo_admin=null;
        }
        
        $.ajax({
            url:"<?php echo Url::toRoute('prefactura-fija/informedispositivos')?>",
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
                regional:zona,
                tipo:tipo,
                tipo_fijo:tipo_fijo,
                tipo_variable:tipo_variable,
                tipo_admin:tipo_admin,
                ano:ano
            },
            beforeSend:  function() {
                $('#info').html('Cargando... <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>');
            },
            success: function(data){
                $("#registros").html(data.respuesta);
                $("#info").html('');
            }
        });
    }


    function excel(){
        $("body").addClass("loading");
        var form=document.getElementById("form_excel");
        var input = document.createElement('input');
        input.type = 'hidden';
        input.id = 'excel';
        input.name = 'excel';
        input.value = '';
        form.appendChild(input);
        form.submit();
        $("body").removeClass("loading");
    }
</script>