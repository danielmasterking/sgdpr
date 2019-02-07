<style type="text/css">
    .midiv {
       word-wrap: break-word; 
       max-width:600px; 
       width:600px;
       text-align:justify;
    }
</style>

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\AdminSupervision */

$this->title ="Admin Supervision";
$this->params['breadcrumbs'][] = ['label' => 'Admin Supervisions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$permisos = array();
if( isset(Yii::$app->session['permisos-exito']) ){
    $permisos = Yii::$app->session['permisos-exito'];
}


$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"); 
?>
<h3 style="text-align: center;"> <?=$this->title?> mes de <?=strtoupper ($meses[$model->mes-1]);?> de <?=$model->ano?></h3>

<?= Html::a('<i class="fa fa-arrow-left"></i> Volver ',Yii::$app->request->baseUrl.'/adminsupervision/index', ['class'=>'btn btn-primary']) ?>

<?php if($model->estado=='abierto'){?>
<?php echo  Html::a('<i class="fas fa-edit"></i> Editar ',Yii::$app->request->baseUrl.'/adminsupervision/update?id='.$model->id, ['class'=>'btn btn-primary']) ?>

<?php }?>

<br><br>
<div class="row">
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-6">
            MES DE FACTURACION<br>
            <b><?=strtoupper ($meses[$model->mes-1]);?></b>
            </div>


            <div class="col-md-6">
            AÑO DE FACTURACION<br>
            <b><?= $model->ano;?></b>
            </div>
            
        </div>

        
        <br>

        <div class="row">
            
          <div class="col-md-6">
            EMPRESA DE SEGURIDAD<br>
            <b><?=$model->empresa_seg->nombre?></b>
          </div>

          <div class="col-md-6">
            NIT<br>
            <b><?=$model->empresa_seg->nit?></b>
          </div>

            
        </div>
      
        
    </div>
    <div class="col-md-4">
        <img src="<?=Url::to('@web/'.$model->empresa_seg->logo, true)?>" width="200" width="110"/>
    </div>
</div>


<br>

<div id="info"></div>
<?php if($model->estado=='abierto'){?>
<div class="row">
    <div class="col-md-12">
        <button class="btn btn-danger" onclick="actualizar();"><i class="fa fa-key"></i> Finalizar Prefactura</button>
    </div>

</div>

<br>
<form class="form-inline" method="post" >

  <div class="form-group">
    <label for="exampleInputName2">Numero de factura</label>
    <div class="input-group">
        <span class="input-group-addon" id="basic-addon1">#</span>
        <input type="text" class="form-control" id="num_factura" name="num_factura" value="<?= $model->numero_factura ?>">
    </div>

  </div>

  <div class="form-group">
    <label for="exampleInputEmail2">Fecha de factura</label>
    <?= 
            DatePicker::widget([
                'id' => 'fecha_factura',
                'name' => 'fecha_factura',
                'value' => isset($model->fecha_factura)?$model->fecha_factura:date('Y-m-d'),
                'options' => ['placeholder' => 'Fecha factura'],
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true
                ]
            ]);
         ?>
  </div>

  <button type="submit" class="btn btn-primary">
    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
    Actualizar
  </button>

</form>


<br>
<?php }?>

<div class="panel panel-primary">
  <div class="panel-heading"><i class="fas fa-calendar-alt"></i> Dispositivos</div>
  <div class="panel-body">
    
    <?php if($model->estado=='abierto'){?>

    <?= Html::a('<i class="fa fa-plus"></i> Agregar ',Yii::$app->request->baseUrl.'/adminsupervision/createdispositivo?id='.$model->id, ['class'=>'btn btn-primary']) ?>
    <br><br>
    <?php }?>
    <div class="table-responsive">
      <table class="table table-striped my-data">
        <thead>
          <tr>
            <th></th>
            <th>Descripcion</th>
            <th>Horas</th>
            <th>Hora Inicio</th>
            <th>Hora Fin</th>
            <!-- <th>Horas Totales</th> -->
            <!-- <th>Horas Dependencia</th> -->
             <th>Fts Diurnos</th>
             <th>Fts Diurnos Dep</th>
            <th>Fts Nocturnos</th>
            <th>Fts Nocturnos Dep</th>
            <th>Fts</th>
            <th>Fts Totales</th>
            <th>Ftes Dependencia</th>
            <th>Lunes</th>
            <th>Martes</th>
            <th>Miercoles</th>
            <th>Jueves</th>
            <th>Viernes</th>
            <th>Sabado</th>
            <th>Domingo</th>
            <th>Festivo</th>
            <th>Cantidad</th>
            <th>Precio unitario</th>
            <th>Precio Total</th>
            <th>Precio Dependencia</th>
            <th>Detalle</th>
          </tr>
        </thead>
        <tbody>
         <?php

         $ftes_total=0;
         $valor_total=0;
         $horas_total=0;


         foreach($admin_disp as $ad):
         ?>
        <tr>
          <td>
            <?php 
              echo Html::a('<i class="fa  fa-edit" ></i>',Yii::$app->request->baseUrl.'/adminsupervision/update-servicio?id='.$ad->id.'&view='.$id,['data-method'=>'post','title'=>'Editar','class'=>'btn btn-primary btn-xs']);

              echo Html::a('<i class="fa  fa-trash " ></i>',Yii::$app->request->baseUrl.'/adminsupervision/deleteservicio?id='.$ad->id.'&view='.$id,['data-method'=>'post','data-confirm' => 'Está seguro de eliminar elemento','title'=>'Eliminar','class'=>'btn btn-danger btn-xs']);


            ?>
          </td>
          <td><?= $ad->descripcion ?></td>
          <td><?= $ad->horas ?></td>
          <td><?= $ad->hora_inicio ?></td>
          <td><?= $ad->hora_fin ?></td>
          <!-- <td>
            <?php 
             //$horas_cantidad=$ad->horas*$ad->cantidad;

             //echo $horas_cantidad;
            ?>
              
          </td> -->
         <!--  <td>
            <?php 
            
              //$horas_dependencia=$horas_cantidad/$count_dep;
              
              //echo round($horas_dependencia,3);
            ?>
            
          </td> -->
          <td><?= $ad->ftes_diurno ?></td>
          <td><?= $ad->ftes_diurno_dep ?></td>
          <td><?= $ad->ftes_nocturno?></td>
          <td><?= $ad->ftes_nocturno_dep?></td>
          <td><?= $ad->ftes ?></td>
          <td>
            <?php 
              echo $ad->ftes * $ad->cantidad;
            ?>
              
          </td>
          <td>
            <?php

              //$ftes_totales=$ad->ftes_dependencia*$ad->cantidad;
              //echo $ftes_totales;
              $ftes_totales=$ad->ftes_dependencia;
              echo $ftes_totales;
            ?>
          </td>
          <td><?= $ad->lunes ?></td>
          <td><?= $ad->martes ?></td>
          <td><?= $ad->miercoles ?></td>
          <td><?= $ad->jueves ?></td>
          <td><?= $ad->viernes ?></td>
          <td><?= $ad->sabado ?></td>
          <td><?= $ad->domingo ?></td>
          <td><?= $ad->festivo ?></td>
          <td><?= $ad->cantidad ?></td>
          <td>
            <?='$ '.number_format($ad->precio_unitario, 0, '.', '.').' COP'?>    
          </td>
          <td>
            <?='$ '.number_format($ad->precio_total==0?0:$ad->precio_total, 0, '.', '.').' COP'?>      
          </td>
          <td>
            <?='$ '.number_format($ad->precio_dependencia==0?0:$ad->precio_dependencia, 0, '.', '.').' COP'?>      
          </td>
          <td><?= $ad->detalle ?></td>
        </tr>
      <?php 
        if ($ad->precio_unitario<0) {
          $ftes_total=$ftes_total-$ftes_totales;
        }else{
          $ftes_total=$ftes_total+$ftes_totales;
        }

        $valor_total=$valor_total+$ad->precio_dependencia;
        $horas_total=$horas_total+$horas_cantidad;
        endforeach; 

       // $horas_dep=$horas_total/$count_dep;
        
      ?>
      <tfoot>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td><b><?php //echo $horas_total ?></b></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td><b><?= $ftes_total ?></b></td>
          <td colspan="11"></td>
          <td><b><?= '$ '.number_format($valor_total, 0, '.', '.').' COP' ?></b></td>
          <td></td>
        </tr>
      </tfoot>
        </tbody>
      </table>
    </div>

  </div>
</div>



<div class="panel panel-primary">
  <div class="panel-heading"><i class="fas fa-building"></i> Dependencias</div>
  <div class="panel-body">
       
      <div class="table-responsive">
       <table class="table table-striped my-data">
           <thead>
               <tr>
                   <th>Dependencia </th>
                   <th>Regional</th>
                   <th>Ceco</th>
                   <th>Cuenta contable</th>
                   <th>Ciudad</th>
                   <th>Marca</th>
                <!--    <th>Horas</th> -->
                   <th>Ftes</th>
                   <th>Precio</th>
                   <!-- <th></th> -->
               </tr>
           </thead>
           <tbody>
               <?php foreach($admin_dep as $row): ?>
                <tr>
                    <td><?= $row->dependencia->nombre ?></td>
                    <td>
                      <?php

                        $consulta=$modeldep->getzona($row->dependencia->codigo);

                        echo $consulta['nombre'];

                      ?>
                    </td>
                    <td><?= $row->dependencia->ceco ?></td>
                    <td>
                    <?php
                      $ceco=(string) $row->dependencia->ceco;
                      $resultado = substr($ceco, 0,1);

                      switch ($resultado) {
                        case '3':

                          echo 533505001 ;

                          break;
                        
                        default:
                          echo 523505001;
                          break;
                      }
                    ?>
                    
                    </td>
                    <td><?= $row->dependencia->ciudad->nombre?></td>
                    <td><?= $row->dependencia->marca->nombre?></td>
                    <!-- <td><?= $horas_dep?></td> -->
                    <td><?= $ftes_total?></td>
                    <td><?='$ '.number_format($valor_total, 0, '.', '.').' COP'?></td>
                    <!-- <td>

                        <?php //if(in_array("administrador", $permisos)){ ?>
                        <a href="#" onclick="eliminar('<?php //echo $row->id?>');return false;" title='Eliminar'>
                            <i class="fa fa-trash"></i>
                        </a>
                        <?php //} ?>

                    </td> -->

                </tr>

               <?php endforeach; ?>
           </tbody>

       </table> 
      </div>

  </div>
</div>


<script type="text/javascript">
    function actualizar(){
        var numero_factura="<?php echo $model->numero_factura; ?>";
        var fecha_factura="<?php echo $model->fecha_factura; ?>";

        if (numero_factura=='' || fecha_factura=='') {

            alert('La prefactura debe tener asignado un numero y fecha de factura para ser finalizada');

            return false;
        }else{
            var url="<?php echo Url::toRoute('adminsupervision/finalizar')?>";
            var r = confirm('¿Desea Finalizar la Pre-factura?');
            if (r == true) {
                $.ajax({
                    url: url,
                    type:'POST',
                    dataType:"json",
                    cache:false,
                    data: {
                        id: <?=$model->id?>
                    },
                    beforeSend:  function() {
                        $('#info').html('Cargando... <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>');
                    },
                    success: function(data){
                        if(data.respuesta=='true'){
                            location.href="<?php echo Url::toRoute('adminsupervision/view')?>"+"?id="+"<?=$model->id?>";
                        }else{
                            $("#info").html('<div class="alert alert-danger alert-dismissable">'+
                                              '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+
                                              '<strong>Alerta!</strong> No se pudo Finalizar la Prefactura. '+data.respuesta+
                                            '</div>'
                                            );
                        }
                        $("#info").html('');
                    }
                });
            }
        }
    }

    function eliminar(id){
        var r = confirm("¿Seguro desea Eliminar esta dependencia ?");
        if (r == true) {
            location.href="<?php echo Url::toRoute('adminsupervision/delete_dependencia?id=');?>"+id+"&admin=<?=$model->id?>";
        }
    }

</script>