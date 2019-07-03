<style type="text/css">
#mdialTamanio{
  width: 100% !important;
}

.label-default{
    background-color: black !important;
    color :white !important;
}

</style>
<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use app\models\ProyectoSeguimientoArchivo;
use app\models\ProyectosDetalleFinalizar;
use kartik\money\MaskMoney;
use kartik\date\DatePicker;
use yii\bootstrap\Modal;
use kartik\widgets\FileInput;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\datecontrol\DateControl;
use marqu3s\summernote\Summernote;
/* @var $this yii\web\View */
/* @var $model app\models\ProyectoDependencia */

$this->title = "Proyecto-".$model->cecoo->nombre."-".$model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Proyecto Dependencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$usuarios=$model->ProyectoUsuario($model->id);

/*echo "<pre>";
print_r($json_crono);
echo "</pre>";*/
?>
<style type="text/css">
    img.mediana{
        width: 500px; height: 200px;
    }

    #avance{
        width: 70px;
    }

    #calendar {
      max-width: 900px;
      margin: 40px auto;
    }
</style>
<div class="proyecto-dependencia-view">

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-6">
        <p>
            <?= Html::a('<i class="fa fa-arrow-left"></i>', ['index'], ['class' => 'btn btn-danger btn-xs']) ?>
            <?= Html::a('<i class="fa fa-edit"></i> Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-xs']) ?>
            <?php 
                /*if(!$model->sala_control){
                    $block_sala="";
                }else{
                    $block_sala="disabled";
                }

                if($model->sala_control && !$model->acta_entrega){
                    $block_acta="";
                }else{
                    $block_acta="disabled";
                }

                if($model->acta_entrega && !$model->facturacion){
                    $block_facturacion="";
                }else{
                    $block_facturacion="disabled";
                }*/
            ?>
           <?php //if(!$model->sala_control): ?>
           <!--  <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#Modalfinalizar" <?php //echo $block_sala ?>>
                <i class="fas fa-broadcast-tower"></i> Ok Sala
            </button> -->
           <?php //endif; ?>

           <?php //if($model->sala_control && !$model->acta_entrega): ?>
            <!-- <button class="btn btn-warning btn-xs" data-toggle="modal" data-target="#Modalfinalizar" <?php //echo $block_acta  ?>>
                <i class="fas fa-book"></i> Ok Acta
            </button> -->
           <?php //endif; ?>
           <?php //if($model->acta_entrega && !$model->facturacion): ?>
            <!-- <button class="btn btn-success btn-xs" data-toggle="modal" data-target="#Modalfinalizar" <?php //echo $block_facturacion ?>>
                <i class="fas fa-clipboard-list"></i> Factura
            </button> -->
           <?php //endif; ?>
           <?php //if($model->facturacion): ?>
            <!-- <label class="label label-default">Ok</label> -->
           <?php //endif; ?>
        </p>
        </div>
    </div>
    
    <div class="row">
      <div class="col-md-6">
            <table class="table table-striped">
                <tr>
                    <th>Fecha de Creacion:</th>
                    <td><?= $model->created_on?></td>
                    
                </tr>
                <tr>
                    <th>Fecha de finalizacion:</th>
                    <td><?= $fecha_final=$model->Get_fecha_finalizacion($model->id,$model->fecha_apertura) ?> <a href="#" data-toggle="modal" data-target="#modal-fecha-finalizacion"><i class="fa fa-edit"></i> Editar fecha</a></td>
                </tr>
                <tr>
                    <th>Usuario Creador:</th>
                    <td><?= $model->solicitante?></td>
                </tr>
                <!-- <tr>
                    <th>Provedores:</th>
                    <td>
                        <?php 

                           /* $provedores=$model->ProyectoProvedor($model->id);

                            foreach ($provedores as $pr) {
                                
                                echo "<label class='label label-info' style='font-size:13px!important;'>".$pr."</label> - ";
                            }*/

                        ?>
                    </td>
                </tr> -->
                <tr>
                    <th>Usuarios Asignados:</th>
                    <td>
                        <?php 
                            $usuarios=$model->ProyectoUsuario($model->id);

                            foreach ($usuarios as $us) {
                                echo "<label class='label label-info' style='font-size:13px!important;'>".$us."</label> - ";   
                            }

                        ?>
                    </td>
                </tr>
                <!-- <tr>
                    <th><i class="fas fa-broadcast-tower"></i> Ok Sala:</th>
                    <td>
                        <?php  
                           /* echo $model->sala_control?'<span class="text-success"><i class="fa fa-check "></i> Ok</span>':'<span class="text-red"><i class="fas fa-question"></i> Pendiente</span>';*/
                        ?>

                    </td>
                </tr> -->
                <!-- <tr>
                    <th><i class="fas fa-book"></i> Ok Acta:</th>
                    <td>
                        <?php  
                            //echo $model->acta_entrega?'<span class="text-success"><i class="fa fa-check "></i> Ok</span>':'<span class="text-red"><i class="fas fa-question"></i> Pendiente</span>';
                        ?>

                    </td>
                </tr> -->
                <!-- <tr>
                    <th><i class="fas fa-clipboard-list"></i> Factura:</th>
                    <td>
                        <?php  
                            //echo $model->facturacion?'<span class="text-success"><i class="fa fa-check "></i> Ok</span>':'<span class="text-red"><i class="fas fa-question"></i> Pendiente</span>';
                        ?>

                    </td>
                </tr> -->
            </table>
        </div>
        <div class="col-md-6">
              <table class="table">
                <tr>
                    <th>Finalizar</th>
                    <th>Sistema</th>
                    <th>Encargado</th>
                    <th>Avance</th>
                    <th>
                        <i class="fas fa-broadcast-tower"></i> Ok Sala:
                    </th>
                    <th><i class="fas fa-book"></i> Ok Acta:</th>
                    <th><i class="fas fa-clipboard-list"></i> Factura:</th>
                </tr>
                <?php 
                    $cont_sistema=0;
                    $acumulador=0;
                    foreach($sistemas as $st): 
                ?>
                <tr>
                    <td>
                        <?php 
                            $array_finalizar=ProyectosDetalleFinalizar::ActivarEstado($model->id,$st->sistema->id);
                            $finalizar=ProyectosDetalleFinalizar::GetFinalizacion($model->id,$st->sistema->id);
                           
                        ?>
                          
                     
                            
                            <button class="btn <?= $array_finalizar['class']?> btn-xs" data-toggle="modal" data-target="#Modalfinalizar" onclick="proceso_finalizar('<?= $array_finalizar['tipo_form']?>',<?= $st->sistema->id?>);">
                            <?= $array_finalizar['icon']." ".$array_finalizar['texto']?>
                            </button>
                       
                    </td>
                    <th><?= $st->sistema->nombre?></th>
                    <td>
                        <?php 
                            if($st->otro!=''){
                                echo $st->otro;
                            }else{
                                echo $st->empresa->nombre==''?$st->provedor->nombre:$st->empresa->nombre;
                            }

                        ?>
                            
                    </td>
                    <td>
                    <?php 
                        /*$promedio_sistema=round($model->PromedioSistema($model->id,$st->id_sistema),2, PHP_ROUND_HALF_DOWN);
                        if($promedio_sistema>$st->sistema->porcentaje){
                            echo $st->sistema->porcentaje."%";
                        }else{
                            echo $promedio_sistema."%";
                        }*/
                        $promedio_sistema=$model->PromedioSistema($model->id,$st->id_sistema);
                        //if($promedio_sistema>$st->sistema->porcentaje){
                            //echo $st->sistema->porcentaje."%";
                        //}else{
                            echo $promedio_sistema."%";
                        //}
                        

                    ?>
                        
                    </td>
                    <td>
                        <?php 
                           

                            if($finalizar!=null){
                                if ($finalizar['fecha_sala_control']!=null || $finalizar['fecha_sala_control']=='0000-00-00' ) {
                                    echo '<span class="text-success"><i class="fa fa-check "></i> Ok</span>';
                                }else{

                                    echo '<span class="text-red"><i class="fas fa-question"></i> Pendiente</span>';
                                }
                            }else{
                                echo '<span class="text-red"><i class="fas fa-question"></i> Pendiente</span>';
                            }

                        ?>
                    </td>
                    <td>
                        <?php 
                            

                            if($finalizar!=null){
                                //echo $finalizar['fecha_acta_entrega'];
                                //echo  $finalizar['na_acta'];
                                if ($finalizar['fecha_acta_entrega']!='0000-00-00' || $finalizar['na_acta']==1 ) {
                                    echo '<span class="text-success"><i class="fa fa-check "></i> Ok</span>';
                                }else{

                                    echo '<span class="text-red"><i class="fas fa-question"></i> Pendiente</span>';
                                }
                            }else{
                                echo '<span class="text-red"><i class="fas fa-question"></i> Pendiente</span>';
                            }

                        ?>
                    </td>
                    <td>
                         <?php 
                            

                            if($finalizar!=null){
                                //echo $finalizar['fecha_acta_entrega'];
                                //echo  $finalizar['na_acta'];
                                if ($finalizar['fecha_entrega_factura']!='0000-00-00' || $finalizar['na_factura']==1 ) {
                                    echo '<span class="text-success"><i class="fa fa-check "></i> Ok</span>';
                                }else{

                                    echo '<span class="text-red"><i class="fas fa-question"></i> Pendiente</span>';
                                }
                            }else{
                                echo '<span class="text-red"><i class="fas fa-question"></i> Pendiente</span>';
                            }

                        ?>
                    </td>
                </tr>
                <?php
                    $acumulador=$acumulador+$promedio_sistema;
                    $cont_sistema++;
                    endforeach; 
                ?>
                 <tr>
                    <td></td>
                    <th><span class="text-red">%Total Avance</span></th>
                    <td></td>
                    <td>
                        <?php 
                            if($cont_sistema==0){
                                $promedio_total="0";
                            }else{
                                //echo "acumulador=".$acumulador."- num=".$cont_sistema;
                                $promedio_total=round(($acumulador/$cont_sistema),2, PHP_ROUND_HALF_DOWN);
                                if($promedio_total>100){
                                    $promedio_total=100;
                                }
                            }
                            echo $promedio_total."%";

                        ?>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <th>$Presupuesto:</th>
                    <td></td>
                    <td>
                        <?php 

                            echo $model->estado=='CERRADO'?'<span class="text-success"><i class="fas fa-check"></i> OK</span>':'<span class="text-red"><i class="fas fa-comments-dollar"></i> Pendiente</span></th>';

                        ?>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr> 
                <tr>
                    <td></td>
                    <th><i class="fa fa-calendar"></i> Cronograma:</th>
                    <td></td>
                    <td>
                        <?php 

                            echo $model->estado_cronograma=='C'?'<span class="text-success"><i class="fas fa-check"></i> OK</span>':'<span class="text-red"><i class="fas fa-question"></i> Pendiente</span></th>';

                        ?>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr> 

                <tr>
                    <td></td>
                    <th ><i class="fas fa-user-clock"></i> Seguimiento en días:</th>
                    <td></td>
                    <td >
                        <?php 
                            if($model->fecha_apertura!="0000-00-00"){

                                $date1 = new DateTime(date('Y-m-d'));
                                $date2 = new DateTime($fecha_final);
                                $diff = $date1->diff($date2);
                                $dias=$model->dias_seguidos!=0 || $model->dias_seguidos!=''?$model->dias_seguidos:$diff->format('%R%a días');
                                $color=$dias<=20?'label-warning':'label-info';
                                //$color=$diff->days<=15?'label-warning':$color;
                                $color=$dias<=7?'label-success':$color;
                                $color=$date2 < $date1 ?'label-danger':$color;
                                $color=$model->dias_seguidos!=0 || $model->dias_seguidos!=''?'label-default':$color;
                                //echo $diff->days;
                                
                                echo "<h4><span class='label ".$color."' >".$dias." </span></h4>";
                                
                            }else{

                                echo '<span class="text-red"><i class="fas fa-question"></i></span>';
                            }


                        ?>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </div>
    </div>

    
    <!-- Modal para edicion de la fecha -->
    <!-- Modal -->
    <div class="modal fade" id="modal-fecha-finalizacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Edicion fecha finalizacion</h4>
          </div>
          <div class="modal-body">
           <form method="post" action="">
                <label>Fecha Finalizacion:</label>
               <input class="form-control" type="date" name="fecha_fin" value="<?= $model->fecha_apertura?>">
                <br>
                <label>Motivo cambio:</label>
                <textarea class="form-control" name="motivo"></textarea>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary" name="fecha_cambio">Guardar</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- ///////////////////////////////////////////// -->
    <div class="row">

  <!-- Nav tabs -->
    <div class="col-md-12">
      <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active">
            <a href="#home" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-clipboard"></i> Seguimiento</a>
        </li>

        <li role="presentation">
            <a href="#historial" aria-controls="historial" role="tab" data-toggle="tab">
                <i class="fa   fa-tasks"></i> Historial de seguimiento
            </a>
        </li>

        <li role="presentation">
            <a href="#historial_fecha" aria-controls="historial_fecha" role="tab" data-toggle="tab">
                <i class="fas fa-history"></i> Historico fecha finalizacion
            </a>
        </li>

        <li role="presentation">
            <a href="#cronograma" aria-controls="cronograma" role="tab" data-toggle="tab">
                <i class="fa   fa-calendar"></i> Cronograma
            </a>
        </li>

        <?php if(in_array("presupuestos", $permisos)){ ?>
        <li role="presentation">
            <a href="#profile" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-money-bill-alt"></i> Presupuesto</a>
        </li>
       <?php }?>

        <li role="presentation">
            <a href="#finalizacion" aria-controls="finalizacion" role="tab" data-toggle="tab">
                <i class="fas fa-calendar-check"></i> Detalle finalizacion
            </a>
        </li>

      </ul>
      </div>
    </div>

      <!-- Tab panes -->
      <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="home">
            <!-- *********************************************** -->
                <br>

                <div class="row">
                    <div class="col-md-3">
                   
                        <button class="btn btn-primary" data-toggle="modal" data-target="#modal-seguimiento">
                            <i class="fa fa-plus"></i> Agregar seguimiento
                        </button>
                    </div>
                </div>
              

                <h1 class="text-center">Seguimiento</h1>



                <div class="col-md-12">
                    <table class="table table-striped my-data">
                        <thead>
                            <tr>
                                <th></th>
                                <th style="text-align: center;">Sistema</th>
                                <th style="text-align: center;">Fecha</th>
                                <th style="text-align: center;">Reporte</th>
                                <th style="text-align: center;">%Avance</th>
                                <!-- <th style="text-align: center;">Provedor</th> -->
                                <th style="text-align: center;">Tipo reporte</th>
                                <th style="text-align: center;">Usuario</th>
                                <th style="text-align: center;">Adjuntos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($detalle as $det): ?>
                            <tr>
                                <td>
                                    <?= Html::a('<i class="fa fa-edit"></i>', ['editarseguimiento','id'=>$det->id,'id_proyecto'=>$model->id], ['class' => 'btn btn-primary btn-xs']) ?>
                                    <?php if(in_array("proyecto-eliminar-seguimiento", $permisos)){ ?>
                                    
                                    <?= Html::a('<i class="fa fa-trash"></i>', ['deleteseguimiento','id'=>$det->id,'id_proyecto'=>$model->id], ['class' => 'btn btn-danger btn-xs','data-confirm'=>'Seguro desea eliminar?']) ?>
                                    <?php } ?>
                                </td>
                                <td style="text-align: center;"><?= $det->sistema->nombre?></td>
                                <td style="text-align: center;"><?= $det->fecha?></td>
                                <td style="text-align: center;"><?= $det->reporte?></td>
                                <td style="text-align: center;">
                                <?php

                                    if($det->avance!=''){
                                       echo  $det->avance."%";
                                    }else{
                                        echo "N/A";
                                    }
                                ?>
                                    
                                </td>
                               <!--  <td style="text-align: center;"><?//= $det->provedor->nombre?></td> -->
                                <td style="text-align: center;"><?= $det->reportes->nombre?></td>
                                <td style="text-align: center;"><?= $det->usuario?></td>
                                <td style="text-align: center;">
                                    <?php 
                                    $adjuntos=ProyectoSeguimientoArchivo::Adjuntos($det->id);
                                    foreach($adjuntos as $adj):
                                        $nombre_archivo=str_replace('/uploads/seguimiento_proyecto/','',$adj->archivo);

                                        $extension=explode('.',$nombre_archivo);


                                        if($extension[1]=='jpg' or $extension[1]=='JPG' or $extension[1]=='png' or $extension[1]=='PNG' or $extension[1]=='gif' or $extension[1]=='GIF'  ):
                                    ?>  
                                    <a title="Click para ver imagen completa" data-toggle="modal" data-target="#myModal" onclick="cargar_imagen('<?= Yii::$app->request->baseUrl.$adj->archivo ?>');"><img src="<?= Yii::$app->request->baseUrl.$adj->archivo ?>" class="img-responsive img-thumbnail"  style='height:50px;width: 70px'></a><br>

                                    <?php else: ?>
                                    <a title="<?= $nombre_archivo?>"  href="<?= Yii::$app->request->baseUrl.$adj->archivo ?>" download=""><i class="far fa-file-archive fa-2x"></i> </a><br>
                                    <?php endif; ?>

                                    <?php endforeach;?>
                                </td>
                            </tr>
                            <?php endforeach;  ?>
                        </tbody>
                    </table>
                </div>
            <!-- *********************************************** -->
        </div>
        <div role="tabpanel" class="tab-pane" id="profile">
            <?= $this->render('_presupuestos',['permisos'=>$permisos,'model'=>$model]) ?>


            <!-- *********************************************************** -->

            <div class="col-md-12">
              <div class="box box-primary collapsed-box">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-credit-card"></i> Historial de transacciones</h3>

                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                    </button>
                  </div>
                  <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                   <!-- *************************************************** -->
                       <div>
                            <?= $this->render('_tab_transactions') ?>
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="saldos">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th style="text-align: center;">Activo</th>
                                                <th style="text-align: center;">Gasto</th>
                                                <th style="text-align: center;">Fecha</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            <?php foreach ($presupuestos as $key) {?>
                                            <tr>
                                                <td style="text-align: center;"><?='$ '.number_format($key->presupuesto_activo, 0, '.', '.').' COP'?></td>
                                                <td style="text-align: center;"><?='$ '.number_format($key->presupuesto_gasto, 0, '.', '.').' COP'?></td>
                                                <td style="text-align: center;"><?=$key->created_on?></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                        
                                    </table>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="saldos_adicional">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th style="text-align: center;">Activo</th>
                                                <th style="text-align: center;">Gasto</th>
                                                <th style="text-align: center;">Fecha</th>
                                                <th style="text-align: center;">Observacion</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($query_saldo_adicional as $qsa): ?>
                                                <tr>
                                                    <td style="text-align: center;"><?='$ '.number_format($qsa->activo, 0, '.', '.').' COP'?></td>
                                                    <td style="text-align: center;"><?='$ '.number_format($qsa->gasto, 0, '.', '.').' COP'?></td>
                                                    <td style="text-align: center;"><?= $qsa->fecha?></td>
                                                    <td style="text-align: center;"><?= $qsa->observacion?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                       <!-- *************************************************** -->
                </div>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->
            </div>

            <div class="col-md-12">
              <div class="box box-primary collapsed-box">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-save"></i> Guardar datos</h3>

                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                    </button>
                  </div>
                  <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                   <!-- *********************************************************************************** -->
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Orden Interna Gasto(Opex)</label>
                                    <input id="orden_interna_gasto" name="orden_interna_gasto" class="form-control lock" placeholder="Orden Interna Gasto" type="text" maxlength="20">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Orden Interna Activo(Capex)</label>
                                    <input id="orden_interna_activo" name="orden_interna_activo" class="form-control lock" placeholder="Orden Interna Activo" type="text" maxlength="20">
                                </div>
                            </div>
                       
                            <div class="col-md-3 ">
                                <div class="form-group">
                                    <label># Metros cuadrados</label>
                                    <input id="m2" name="m2" class="form-control lock" placeholder="Metros cuadrados" type="number" >
                                </div>
                            </div>


                            <div class="col-md-3 ">
                                <div class="form-group">
                                    <label>IVA</label>
                                    <input id="iva" name="iva" class="form-control lock" placeholder="IVA" type="number" maxlength="2"
                                    <?php echo !in_array("administrador", $permisos)?'readonly':'';?>
                                    >
                                </div>
                            </div>
                        </div>
                        <div id="info_guardar_datos"></div>
                        <button type="submit" id="guardar_datos" class="btn btn-primary lock" onclick="guardarDatosAdicionales()">
                            <i class="fa fa-save"></i> Guardar Datos
                        </button>

                        <!-- *********************************************************************************** -->
                </div>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->
            </div>

            <div class="col-md-12">
              <div class="box box-primary collapsed-box">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-cart-plus"></i> Detalle de pedidos</h3>

                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                    </button>
                  </div>
                  <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  
                        <!-- ************************************************************************************************* -->
                        <?php 
                        Modal::begin([
                            'id' => 'modal-cot',
                            'header' => '<h2>Agregar Cotizacion</h2>',
                            //'toggleButton' => ['label' => 'Agregar Cotizacion','class'=>'btn btn-primary lock'],
                        ]);
                            echo FileInput::widget([
                                'id' => 'file',
                                'options'=>[
                                    'multiple'=>true
                                ],
                                'name' => 'file',
                                'pluginOptions'=>['allowedFileExtensions'=>['xls', 'xlsx', 'pdf','jpg','png','gif','jpeg'],
                                               'maxFileSize' => 5120,
                                ]
                            ]);
                            echo '<br>';
                            echo '<button type="submit" class="btn btn-primary" onclick="subirCotizacion()">'.
                                    'Subir'.
                                 '</button>';
                        Modal::end();
                        ?>
                        <?php
                         Modal::begin([
                          'header' => '<h4>No Aprobado</h4>',
                          'id' => 'modal-no-aprobado',
                          'size' => 'modal-md',
                          ]);
                         echo '<div id="info-na"></div>';
                         echo '<div class="row">';
                             echo '<div class="col-md-8">';
                                 echo '<label>Indique porque NO se Aprueba</label>';
                                 echo '<textarea id="motivo-no-aprobado" class="form-control" rows="4" cols="50"></textarea>';
                             echo '</div>';
                             echo '<div class="col-md-4">';
                                echo '<label>Cantidad no Aprobada</label>';
                                echo '<select id="cantidad-no-aprobado" class="form-control">';
                                    echo '<option value="0">[Elija Cantidad]</option>';
                                echo '</select>';
                             echo '</div>';
                         echo '</div>';
                         echo '<p>&nbsp;</p>';
                         echo '<button onclick="cambiarEstadoNa()" class="btn btn-primary btn-lg">Guardar</button>';
                         Modal::end();
                        ?>
                        <ul class="nav nav-tabs nav-justified">
                            <li id="normal" class="active">
                                <a href="#" id="link_normal" onclick="return false;">Normales</a>
                            </li>
                            <li id="especial" class="">
                                <a href="#" id="link_especial" onclick="return false;">Especiales</a>
                            </li>
                            <li id="normal_no_aprobado" class="">
                                <a href="#" id="link_normal_no_aprobado" onclick="return false;">Normales No Aprobados</a>
                            </li>
                            <li id="especial_no_aprobado" class="">
                                <a href="#" id="link_especial_no_aprobado" onclick="return false;">Especiales No Aprobados</a>
                            </li>
                        </ul>
                        <br>
                        <form id="form_excel" method="post">
                           <div class="row">
                                <div class="col-md-4">
                                    <input type="text" id="buscar" name="buscar" class="form-control" placeholder="Buscar Coincidencias">
                                </div>

                                <div class="col-md-4">
                                    <select id="ordenado" name="ordenado" class="form-control">
                                        <option value="">[ORDENAR POR...]</option>
                                        <option value="fecha">Fecha</option>
                                        <option value="repetido">Repetido</option>
                                        <option value="producto">Producto</option>
                                        <option value="cantidad">Cantidad</option>
                                        <option value="proveedor">Proveedor</option>
                                        <option value="solicitante">Solicitante</option>
                                    </select>
                                </div>
                                
                                
                                <div class="col-md-4">
                                    <select id="forma" name="forma" class="form-control">
                                        <option value="">[FORMA...]</option>
                                        <option value="SORT_ASC">Ascendente</option>
                                        <option value="SORT_DESC">Descendente</option>
                                    </select>
                                </div>
                                        
                                           
                           </div>
                           <br>
                           <div class="row">.
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
                                   
                           </div>
                        </form>

                       <br>
                       <div class="row">
                           <div class="container">
                                <button type="submit" class="btn btn-primary" onclick="excel()">
                                    <i class="fas fa-file-excel"></i> Descargar Busqueda en Excel
                                </button>
                                <button type="submit" class="btn btn-primary" onclick="consultar(0)">
                                    <i class="fa fa-search fa-fw"></i> Buscar
                                </button>
                            </div>
                        </div>
                        <br>

                        
                        <div id="info"></div>
                        <div id="partial"></div>
                        <div class="modal-process"></div>
                        <!-- ************************************************************************************************* -->
                </div>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->
            </div>

            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fas fa-calculator"></i> Consolidado pedidos</h3>

                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                  </div>
                  <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                   <!-- ****************************gastos de pedidos por sistemas*************** -->
                        <div class="row">
                            <div class="col-md-6 col-xs-12 col-md-offset-3">
                                <table class="table table-striped">
                                    
                                    <tr>
                                        <th></th>
                                        <th>Sistema</th>
                                        <th>$ Normales</th>
                                        <th>$ Especiales</th>
                                        <th>$ Total</th>
                                        
                                    </tr>
                                    <?php foreach($array_gasto_pedidos as $apn): ?>
                                        <tr>
                                            <th><i class="fas fa-donate "></i></th>
                                            <td><?= $apn['sistema']?></td>
                                            <td><?='$ '.number_format($apn['total'], 0, '.', '.').' COP'?></td>
                                            <td><?='$ '.number_format($apn['total_especial'], 0, '.', '.').' COP'?></td>
                                            <td><?='$ '.number_format($apn['total_especial']+$apn['total'], 0, '.', '.').' COP'?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            </div>

                           
                           
                        </div>
                        <!-- ************************************************************************* -->
                </div>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->
            </div>
            
            <!-- *********************************************************** -->
        </div>

        <div role="tabpanel" class="tab-pane" id="historial">
            <h1 class="text-center">Historial</h1>
            <?= $this->render('_historial',['historial'=>$historial]) ?>
        </div>

        <div role="tabpanel" class="tab-pane" id="historial_fecha">
            <!-- ************************************************** -->
            <h1 class="text-center">Historico Fecha Finalizacion</h1>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="text-align: center;">Fecha</th>
                                <th style="text-align: center;">Motivo Cambio</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($log_fechas as $lgf): ?>
                                <tr>
                                    <td style="text-align: center;"><?php echo $lgf->fecha ?></td>
                                    <td style="text-align: center;"><?php echo $lgf->descripcion ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td style="text-align: center;"><?php echo $model->fecha_apertura ?></td>
                                <td style="text-align: center;">Fecha asignada inicialmente</td>
                                
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- *************************************************** -->
        </div>

        <div role="tabpanel" class="tab-pane" id="cronograma">
            <br>
            <?php if($model->estado_cronograma=="A"): ?>
            <button class="btn btn-primary" data-toggle="modal" data-target="#Modalcronograma">
                <i class="fa fa-plus"></i> Agregar Cronograma
            </button>
            
            <?php endif; ?>

            <?php if($model->estado_cronograma=="A"){ ?>
            <a href="<?= Url::to(['proyecto-dependencia/bloquear-cronograma', 'id' => $model->id,'accion'=>'C']) ?>" class="btn btn-danger" data-confirm="Seguro desea finalizar el cronograma?">
                <i class="fas fa-ban"></i> Bloquear
            </a>

            <?php }else{ ?>
                <a href="<?= Url::to(['proyecto-dependencia/bloquear-cronograma', 'id' => $model->id,'accion'=>'A']) ?>" class="btn btn-success" data-confirm="Seguro desea abrir el cronograma?">
                 <i class="fas fa-lock-open"></i> Desbloquear
                </a>
            <?php } ?>

            <br><br>
            <div class="panel-group" id="accordioncrono" role="tablist" aria-multiselectable="true">
              <div class="panel panel-primary">
                <div class="panel-heading" role="tab" id="headingOnecrono">
                  <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordioncrono" href="#collapseOnecrono" aria-expanded="true" aria-controls="collapseOnecrono">
                      <i class="fa fa-calendar"></i> Calendario
                    </a>
                  </h4>
                </div>
                <div id="collapseOnecrono" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                  <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="calendar"></div>
                            </div>
                        </div>
                  </div>
                </div>
              </div>
              <div class="panel panel-primary">
                <div class="panel-heading" role="tab" id="headingTwocrono">
                  <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordioncrono" href="#collapseTwocrono" aria-expanded="false" aria-controls="collapseTwocrono">
                      <i class="fa fa-list"></i> Listado
                    </a>
                  </h4>
                </div>
                <div id="collapseTwocrono" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                  <div class="panel-body">
                    <?= $this->render('_list_cronograma',['cronograma'=>$cronograma,'id'=>$id,'estado_crono'=>$model->estado_cronograma]) ?>

                  </div>
                </div>
              </div>
           
            </div>
            
            
            
        </div>

        <div role="tabpanel" class="tab-pane" id="finalizacion">
            <h3 class="text-center"><i class="fas fa-broadcast-tower"></i> Ok sala control</h3>
            <?php if($model->na_sala==false): ?>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Adjunto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?= $model->fecha_sala_control?></td>
                                <td>
                                    <?php if($model->adjunto_sala_control!=''): ?>
                                        <a href="<?php echo Yii::$app->request->baseUrl.$model->adjunto_sala_control ?>" title="Click Para ver adjunto" target="_blank">
                                        <i class="fas fa-file-alt fa-2x"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php else: ?>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3" >
                        <div class="alert alert-info" role="alert" >
                            <h3 class="text-center">No aplica</h3>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <br>
            <h3 class="text-center"><i class="fas fa-book"></i> Ok Acta</h3>
            <?php if($model->na_acta==false): ?>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Adjunto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?= $model->fecha_acta_entrega?></td>
                                <td>
                                    <?php if($model->adjunto_acta_entrega!=''): ?>
                                        <a href="<?php echo Yii::$app->request->baseUrl.$model->adjunto_acta_entrega ?>" title="Click Para ver adjunto" target="_blank">
                                        <i class="fas fa-file-alt fa-2x"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php else: ?>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3" >
                        <div class="alert alert-info" role="alert" >
                            <h3 class="text-center">No aplica</h3>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

             <br>
            <h3 class="text-center"><i class="fas fa-clipboard-list"></i> Ok factura</h3>
            <?php if($model->na_factura==false): ?>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <table class="table table-striped">
                        <thead>
                            <tr>

                                <th>Fecha</th>
                                <th>Se entrego factura</th>
                                <th>Adjunto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?= $model->fecha_entrega?></td>
                                <td>
                                    <?php 
                                        $resp=$model->recibe_factura=='S'?'Si':'No';

                                        echo $resp;

                                    ?>
                                </td>
                                <td>
                                    <?php if($model->adjunto_factura!=''): ?>
                                        <a href="<?php echo Yii::$app->request->baseUrl.$model->adjunto_factura ?>" title="Click Para ver adjunto" target="_blank">
                                        <i class="fas fa-file-alt fa-2x"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php else: ?>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3" >
                        <div class="alert alert-info" role="alert" >
                            <h3 class="text-center">No aplica</h3>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>

      </div>
    </div>
    
</div>


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        
      </div> 
      <div class="modal-body">
       <!-- *************** -->
          <center><img  class="img-responsive img-thumbnail" style='height:500px;width: 700px' id="imagen_adjunto"></center>
       <!-- ***************** -->
      </div>
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> -->
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="ModalPresupuesto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Agregar Presupuesto</h4>
      </div>
      <div class="modal-body">
        <?= $this->render('_agregarpresupuesto',['model'=>$model,'id'=>$id]) ?>
      </div>
     <!--  <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> -->
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="Modalpedido" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Pedir normal</h4>
      </div>
      <div class="modal-body">
        <?= $this->render('pedidos/create_pedido',['ceco'=>$model->cecoo->codigo,'id'=>$id]) ?>
      </div>
     <!--  <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> -->
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="Modalpedidoespecial" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document" id="mdialTamanio">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Pedir especial</h4>
      </div>
      <div class="modal-body">
        <?= $this->render('pedidos/create_pedido_especial',['ceco'=>$model->cecoo->codigo,'id'=>$id]) ?>
      </div>
     <!--  <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> -->
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="Modalfinalizar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Finalizar Proyecto</h4>
      </div>
      <div class="modal-body">
        
       <form action="<?=Url::toRoute(['finalizar', 'id' =>$model->id]);?>" method="post" enctype="multipart/form-data" id="form-finalizar">
        <div id="finalizar_sala">
        <?php //if(!$model->sala_control): ?>
            <h3 class="text-center">Sala de control</h3>
            <div class="row">
                <div class="col-md-12">
                    
                    <label>
                       No aplica 
                        <input type="checkbox" name="check-sala" value="1"  >
                    </label>
                </div>

            </div>

            <div class="row">
                <div class="col-md-6">
                    <input type="hidden" name="form" id="tipo_form">
                    <input type="hidden" name="sistema" id="tipo_sistema">
                    <label>Fecha</label>
                    <input type="date" name="fecha_sala" class="form-control" value="<?= $model->fecha_sala_control?>" <?php //echo $model->sala_control==true?'disabled':'' ?>>
                </div>
                <div class="col-md-6">
                    <?php if($model->adjunto_sala_control==''): ?>
                    <label>Documento</label>
                    <input type="file" name="file_sala" class="form-control" > 
                    <?php else: ?>
                        <br>
                        <a href="<?php echo Yii::$app->request->baseUrl.$model->adjunto_sala_control ?>" title="Click Para ver adjunto" target="_blank">
                            <i class="fas fa-file-alt fa-2x"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <label>Observacion</label>
                    <textarea class="form-control" rows="5" name="obs_sala"></textarea>
                </div>
                
            </div>
        </div>
        <?php //endif; ?>
                

                
        <div id="finalizar_acta">
        <?php //if($model->sala_control && !$model->acta_entrega): ?>
            <h3 class="text-center">Acta de entrega</h3>
            <div class="row">
                <div class="col-md-6">
                    <label>
                        No aplica
                        <input type="checkbox" name="check-acta" value="1" >
                    </label>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                     <!-- <input type="hidden" name="form" value="acta"> -->
                    <label>Fecha </label>
                    <input type="date" name="fecha_acta" class="form-control" value="<?= $model->fecha_acta_entrega?>">
                </div>

                <div class="col-md-6">
                     <?php if($model->adjunto_acta_entrega==''): ?>
                        <label>Documento</label>
                        <input type="file" name="file_acta" class="form-control" > 
                        <?php else: ?>
                            <br>
                            <a href="<?php echo Yii::$app->request->baseUrl.$model->adjunto_acta_entrega ?>" title="Click Para ver adjunto" target="_blank">
                                <i class="fas fa-file-alt fa-2x"></i>
                            </a>
                    <?php endif; ?>
                </div>
            </div>
    
             <div class="row">
                <div class="col-md-12">
                    <label>Observacion</label>
                    <textarea class="form-control" rows="5" name="obs_acta"></textarea>
                </div>
                
            </div>
        <?php //endif; ?>
        </div>       

        <?php //if($model->acta_entrega && !$model->facturacion): ?>
        <div id="finalizar_factura">
            <h3 class="text-center">Facturacion</h3>
            <div class="row">
                <div class="col-md-6">
                    <label>
                        No aplica
                        <input type="checkbox" name="check-facturacion" value="1" >
                    </label>
                </div>

            </div>
            <div class="row">
                <div class="col-md-6">
                    <label>Entrego factura?</label>
                    <label class="radio-inline">
                      <input type="radio" name="recibio_factura" id="inlineRadio1" value="S" checked=""> Si
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="recibio_factura" id="inlineRadio2" value="N"> No
                    </label>

                </div>


            </div>

            <div class="row">
                 <div class="col-md-6">
                   <!--  <input type="hidden" name="form" value="factura"> -->
                    <input type="hidden" name="dias_seguidos" value="<?= $dias?>">
                    <label>Fecha</label>
                    <input type="date" name="fecha_factura" class="form-control">
                </div>
                <div class="col-md-6">
                     <?php if($model->adjunto_factura==''): ?>
                        <label>Documento</label>
                        <input type="file" name="file_factura" class="form-control" > 
                        <?php else: ?>
                            <br>
                            <a href="<?php echo Yii::$app->request->baseUrl.$model->adjunto_factura ?>" title="Click Para ver adjunto" target="_blank">
                                <i class="fas fa-file-alt fa-2x"></i>
                            </a>
                    <?php endif; ?>
                </div>
            </div>
             <div class="row">
                <div class="col-md-12">
                    <label>Observacion</label>
                    <textarea class="form-control" rows="5" name="obs_factura"></textarea>
                </div>
                
            </div>
        <?php //endif; ?>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <?php if($model->estado_proyecto=="A"): ?>
        <button type="submit" class="btn btn-primary" >Guardar cambios</button>
        <?php endif; ?>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal-Seguimiento -->
<div class="modal fade" id="modal-seguimiento" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Agregar</h4>
      </div>
      <div class="modal-body">
        <!-- *************************************************************************************************************************** -->
         <?php $form = ActiveForm::begin([

                'options'=>['enctype'=>'multipart/form-data'],
                'action'=>Url::toRoute(['proyecto-dependencia/agregar', 'id' =>$id]) // important


            ]); ?>

                
                <div class="row">
                    <div class="col-md-4">
                        <?= $form->field($model_seguimiento, 'id_sistema')->widget(Select2::classname(), [
                           
                           'data' => $list_sistemas,
                            'options' => [
                            'id' => 'sistema',
                            'placeholder' => 'Sistemas',
                                                        
                            ],
                        
                          ])
                        ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model_seguimiento, 'fecha')->widget(DateControl::classname(), [
                              'autoWidget'=>true,
                             'displayFormat' => 'php:Y-m-d',
                             'saveFormat' => 'php:Y-m-d',
                              'type'=>DateControl::FORMAT_DATE,
                              'disabled'=>'true'
                 
                       ]);?>
                    </div>

                    <div class="col-md-4">
                        <?= $form->field($model_seguimiento, 'id_tipo_reporte')->widget(Select2::classname(), [
                           
                           'data' => $list_reportes,
                            'options' => [
                            'id' => 'tipo_reportes',
                            'placeholder' => 'Tipo de reporte',
                                                        
                            ],
                        
                          ])
                        ?>
                    </div>
                </div>

              
                
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model_seguimiento, 'reporte')->widget(Summernote::className(), [
                            'clientOptions' => [
                               
                            ]
                        ]); ?>
                    </div>
                </div>

                <div class="row">
                   
                    <!-- <div class="col-md-4">
                        
                        <?php
                            /*$provedores['33']='N/A';
                            echo $form->field($model_seguimiento, 'id_provedor')->dropDownList($provedores,['prompt'=>'Select...']); */
                        ?>
                    </div> -->

                    <div class="col-md-4">
                        <?= $form->field($model_seguimiento, 'usuario')->textInput([
                        'readonly'=>true
                        ]) ?>
                    </div>

                     <div class="col-md-4">
                        
                        <?php
                            echo $form->field($model_seguimiento, 'avance')->dropDownList($array_porcentaje,['id'=>'avance']); 
                        ?>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12">
                        <?php 
                            echo $form->field($model_seguimiento, 'image[]')->widget(FileInput::classname(), [
                            'options' => ['multiple'=>true],
                            'pluginOptions'=>['allowedFileExtensions'=>['jpg', 'gif', 'png','jpeg','pdf'],
                                               //'maxFileSize' => 5120,
                              ]
                             ]);
                        ?>
                    </div>
                </div>
                

            
                <div class="form-group">
                    <?= Html::submitButton($model_seguimiento->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model_seguimiento->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        <!-- *************************************************************************************************************************** -->
      </div>
     <!--  <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> -->
    </div>
  </div>
</div>

<!-- Modal Cronograma-->
<div class="modal fade" id="Modalcronograma" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Cronograma</h4>
      </div>
      <div class="modal-body">
        <?php if($model->estado_cronograma=="A"): ?>
        <?= $this->render('_form_cronograma',['model'=>$model_cronograma,'id'=>$id,'list_usuarios'=>$list_usuarios]) ?>
        <?php else: ?>
            <div class="alert alert-info" role="alert">Este Cronograma ya se encuentra finalizado</div>
        <?php endif; ?>
      </div>
     <!--  <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> -->
    </div>
  </div>
</div>

<!-- Modal Editar Cronograma-->
<div class="modal fade" id="Modaleditarcronograma" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Cronograma</h4>
      </div>
      <div class="modal-body" id="cronobody">

       
      </div>
       <div class="modal-footer">
        <?php if($model->estado_cronograma=="A"): ?>
        <a href=""  class="btn btn-primary" id="editar-crono">
            <i class="fa fa-edit"></i> Editar
        </a>
        <a href=""  class="btn btn-danger" data-confirm="Seguro desea eliminar?" id="eliminar-crono">
            <i class="fa fa-trash"></i> Eliminar
        </a>
        <?php endif; ?>
      </div> 
    </div>
  </div>
</div>

<!-- Modal para agregar adicional del presupuesto -->
<!-- Modal -->
<div class="modal fade" id="myModal-adicional" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Adicional</h4>
      </div>
      <div class="modal-body">
       
        <!-- *********************************************************************************************** -->
         <?php $form_adicional = ActiveForm::begin(); ?>
            
            <div class="row">
                <div class="col-md-6">
                    <?= $form_adicional->field($model_adicional_proyecto, 'activo')->textInput(['type'=>'number']) ?>   
                </div>

                 <div class="col-md-6">
                    <?= $form_adicional->field($model_adicional_proyecto, 'gasto')->textInput(['type'=>'number']) ?>   
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <?= $form_adicional->field($model_adicional_proyecto, 'observacion')->textarea(['rows'=>5]); ?>
                </div>
            </div>
       
            <div id="sum_adicional"><h3><b>$ 0 COP</b></h3></div>

        <!-- *********************************************************************************************** -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
       <!--  <button type="submit" data-confirm="Seguro desea realizar esta accion?" class="btn btn-primary">Guardar</button> -->
       <?= Html::submitButton($model_adicional_proyecto->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model_adicional_proyecto->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?php ActiveForm::end(); ?>
      </div> 
    </div>
  </div>
</div>

<script type="text/javascript">

    function proceso_finalizar(form,sistema){
        $('#tipo_form').val(form);
        $('#tipo_sistema').val(sistema);
        switch (form) {
          case 'sala':
            $('#finalizar_sala').show();
            $('#finalizar_acta').hide();
            $('#finalizar_factura').hide();
           break;
          case 'acta':
           
            $('#finalizar_acta').show();
            $('#finalizar_sala').hide();
            $('#finalizar_factura').hide();
           break;
          case 'factura':
            $('#finalizar_factura').show();
            $('#finalizar_sala').hide();
            $('#finalizar_acta').hide();
           break;
        }
    }

    $("#form-finalizar").submit(function(event) {
        /* Act on the event */
        var confirmar=confirm('Seguro desea realizar esta accion?');
        if(!confirmar){
            return false;
        }
    });

    ////Esta funcion es para la suma de los adicionales para presupuestos
    var valor_activo=$('#proyectospresupuestoadicional-activo');
    var valor_gasto=$('#proyectospresupuestoadicional-gasto');
    $('#proyectospresupuestoadicional-activo,#proyectospresupuestoadicional-gasto').keyup(function(event) {
        /* Act on the event */
        var adicional_activo=!isNaN(parseInt(valor_activo.val()))?parseInt(valor_activo.val()):0;
        var adicional_gasto=!isNaN(parseInt(valor_gasto.val()))?parseInt(valor_gasto.val()):0;
        var total_adicional=(adicional_activo+adicional_gasto);
        $('#sum_adicional').html("<h3><b>$ "+total_adicional.formatPrice()+" COP</b></h3>");
    });

  
    /////////////////////////////////////////////////////////////////////


    function AsignarSistemaTodos(tipo,sistema){//Funcion para asignar sistemas a todos los pedidos seleccionados
         var checkboxValues = new Array();
        //recorremos todos los checkbox seleccionados con .each
        $('.checkBoxid').each(function() {
            if($(this).prop('checked')==true){
                checkboxValues.push($(this).attr("id"));
            }
        });
        var productos_id = checkboxValues.toString();
        $.ajax({
            url:"<?php echo Yii::$app->request->baseUrl . '/proyecto-dependencia/asignar-sistematodos'; ?>",
            type:'POST',
            dataType:"json",
            cache:false,
            data: {
                sistema: sistema,
                productos_id: productos_id,
                tipo: tipo
            },
            beforeSend:  function() {
                $('#info').html('Cambiando... <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>');
            },
            success: function(data){
                //if (data.respuesta==true) {
                    consultar(0)
                //}
                $("#info").html('');
            }
        });
    }

    function AsignarSistema(sistema,producto,tipo){//funcion para asignar individualmente los sistemas a los pedidos
        $.ajax({
            url:"<?php echo Yii::$app->request->baseUrl . '/proyecto-dependencia/asignar-sistema-pedido'; ?>",
            type:'POST',
            dataType:"json",
            cache:false,
            data: {
                sistema: sistema,
                producto: producto,
                tipo: tipo
            },
            beforeSend:  function() {
                $('#info').html('Cambiando... <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>');
            },
            success: function(data){
                if (data.respuesta==true) {
                    consultar(0)
                }
                $("#info").html('');
            }
        });
    }



    function activar_finalizar(checkbox,div){
         
        if ($(checkbox).prop('checked') ) {
            $('#'+div).show('slow/400/fast', function() {
                
            });
        }else{

            $('#'+div).hide('slow/400/fast', function() {
                
            });
        }     
    }
    var url_tab="<?php echo Yii::$app->request->baseUrl . '/proyecto-dependencia/pedidos-normales'; ?>";
    $(document).on("click", "#link_normal, #normal", function(){
        $("#normal").addClass("active");
        $("#especial").removeClass("active");
        $("#normal_no_aprobado").removeClass("active");
        $("#especial_no_aprobado").removeClass("active");
        url_tab="<?php echo Yii::$app->request->baseUrl . '/proyecto-dependencia/pedidos-normales'; ?>";
        if(!normal){
            normal=true;especial=false;normal_no_aprobado=false;especial_no_aprobado=false;
            limpiar()
            consultar(0)
        }
    });
    $(document).on("click", "#link_especial, #especial", function(){
        $("#especial").addClass("active");
        $("#normal").removeClass("active");
        $("#normal_no_aprobado").removeClass("active");
        $("#especial_no_aprobado").removeClass("active");
        url_tab="<?php echo Yii::$app->request->baseUrl . '/proyecto-dependencia/pedidos-especial'; ?>";
        if(!especial){
            especial=true;normal=false;normal_no_aprobado=false;especial_no_aprobado=false;
            limpiar()
            consultar(0)
        }
    });
    $(document).on("click", "#link_normal_no_aprobado, #normal_no_aprobado", function(){
        $("#normal_no_aprobado").addClass("active");
        $("#normal").removeClass("active");
        $("#especial").removeClass("active");
        $("#especial_no_aprobado").removeClass("active");
        url_tab="<?php echo Yii::$app->request->baseUrl . '/proyecto-dependencia/pedidos-normales?estado=2'; ?>";
        if(!normal_no_aprobado){
            normal_no_aprobado=true;normal=false;especial=false;especial_no_aprobado=false;
            limpiar()
            consultar(0)
        }
    });
    $(document).on("click", "#link_especial_no_aprobado, #especial_no_aprobado", function(){
        $("#especial_no_aprobado").addClass("active");
        $("#normal").removeClass("active");
        $("#especial").removeClass("active");
        $("#normal_no_aprobado").removeClass("active");
        url_tab="<?php echo Yii::$app->request->baseUrl . '/proyecto-dependencia/pedidos-especial?estado=2'; ?>";
        if(!especial_no_aprobado){
            especial_no_aprobado=true;normal_no_aprobado=false;normal=false;especial=false;
            limpiar()
            consultar(0)
        }
    });
    function limpiar(){
        $("#buscar").val('')
        $("#ordenado").val('')
        $("#forma").val('')
        $("#desde").val('')
        $("#hasta").val('')
    }
     $(document).on( "click", "#partial .pagination li", function() {
        var page = $(this).attr('p');
        consultar(page);
    });
     consultar(0);
    normal=true;especial=false;
    function consultar(page){
        $("#partial").html('');
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
        $.ajax({
            url:url_tab,
            type:'POST',
            dataType:"json",
            cache:false,
            data: {
                desde: desde,
                hasta: hasta,
                buscar: buscar,
                ordenado: ordenado,
                forma: forma,
                proyecto: <?php echo $model->id?>,
                page: page
            },
            beforeSend:  function() {
                $('#info').html('Cargando... <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>');
            },
            success: function(data){
                $("#partial").html(data.respuesta);
                $("#info").html('');
                <?php if($model->estado=='ENVIADO'){?>
                    $('.lock').prop("disabled", true);
                <?php } ?>
            }
        });
    }
</script>

<script type="text/javascript">
    $(function (){
        $.fn.modal.Constructor.prototype.enforceFocus = function() {};
    });
    var presupuesto_total = parseInt(<?=$model->presupuesto_total?>);
    var presupuesto_seguridad = parseInt(<?=$model->presupuesto_seguridad?>);
    var presupuesto_riesgo = parseInt(<?=$model->presupuesto_riesgo?>);
    var presupuesto_activo = parseInt(<?=$model->presupuesto_activo?>);
    var presupuesto_gasto = parseInt(<?=$model->presupuesto_gasto?>);

    var suma_total = parseInt(<?=$model->suma_total?>);
    var suma_seguridad = parseInt(<?=$model->suma_seguridad?>);
    var suma_riesgo = parseInt(<?=$model->suma_riesgo?>);
    var suma_activo = parseInt(<?=$model->suma_activo?>);
    var suma_gasto = parseInt(<?=$model->suma_gasto?>);

    var orden_interna_gasto = "<?=$model->orden_interna_gasto?>";
    var orden_interna_activo = "<?=$model->orden_interna_activo?>";

    
    var normal=false;
    var especial=false;
    var normal_no_aprobado=false;
    var especial_no_aprobado=false;

    $('#m2').keyup(function(){
        var total=($('#total_iva').val()/$(this).val());
        if ($(this).val()=='') {
            $("#total_m2").html('');    
        }else{
            $("#total_m2").html('$ '+total.formatPrice()+' COP');
        }
    });
    $('#ag_presupuesto').click(function(event) {
        /* Act on the event */
        $('#actualizar').val(0);
    });

     $('#update_presupuesto').click(function(event) {
        /* Act on the event */
        $('#actualizar').val(1);
    });


    function cargar_imagen(src){
        $('#imagen_adjunto').attr({
            src: src
            
        });
    }

    /*$(function(){
        $("#presupuesto_seguridad").maskMoney({thousands:'.', decimal:',', precision: 0, allowZero:true, allowNegative:false, suffix: ''});
        $("#presupuesto_riesgo").maskMoney({thousands:'.', decimal:',', precision: 0, allowZero:true, allowNegative:false, suffix: ''});
        $("#presupuesto_activo").maskMoney({thousands:'.', decimal:',', precision: 0, allowZero:true, allowNegative:false, suffix: ''});
        $("#presupuesto_gasto").maskMoney({thousands:'.', decimal:',', precision: 0, allowZero:true, allowNegative:false, suffix: ''});

    });*/
// $(document).on("keyup", "#presupuesto_seguridad, #presupuesto_riesgo", function(){
//     var seguridad=parseInt(($('#presupuesto_seguridad').val()).replaceAll(".",""))
//     var riesgo=parseInt(($('#presupuesto_riesgo').val()).replaceAll(".",""))
//     $("#info_suma").html('$ '+(seguridad+riesgo).formatPrice()+' COP')
// });


$(document).on("keyup", "#presupuesto_activo, #presupuesto_gasto", function(){

    if($('#presupuesto_activo').val()==""){
        var seguridad=0;    
    }else{
        var seguridad=parseInt(($('#presupuesto_activo').val()).replaceAll(".",""))    
    }
    
    if ($('#presupuesto_gasto').val()=="") {
        var riesgo=0;
    }else{
        var riesgo=parseInt(($('#presupuesto_gasto').val()).replaceAll(".",""))    
    }
    
    $("#info_suma").html('$ '+(seguridad+riesgo).formatPrice()+' COP')
});



function validar(){
    var flag=true;
    // var presupuesto_seguridad=parseInt(($("#presupuesto_seguridad").val()).replaceAll(".", ""));
    // var presupuesto_riesgo=parseInt(($("#presupuesto_riesgo").val()).replaceAll(".", ""));

    var presupuesto_activo=parseInt(($("#presupuesto_activo").val()).replaceAll(".", ""));
    var presupuesto_gasto=parseInt(($("#presupuesto_gasto").val()).replaceAll(".", ""));
    /*var presupuesto_activo=parseInt(($("#presupuesto_activo").val()).replaceAll(".", ""));
    var presupuesto_gasto=parseInt(($("#presupuesto_gasto").val()).replaceAll(".", ""))*/
    if(isNaN(presupuesto_activo)){
        $("#presupuesto_activo").val("0")
    }else{
        $("#presupuesto_activo").val(presupuesto_activo)
    }
    if(isNaN(presupuesto_gasto)){
        $("#presupuesto_gasto").val("0")
    }else{
        $("#presupuesto_gasto").val(presupuesto_gasto)
    }
    /*if(isNaN(presupuesto_activo)){
        $("#presupuesto_activo").val("0")
    }else{
        $("#presupuesto_activo").val(presupuesto_activo)
    }
    if(isNaN(presupuesto_gasto)){
        $("#presupuesto_gasto").val("0")
    }else{
        $("#presupuesto_gasto").val(presupuesto_gasto)
    }*/
    presupuesto_activo=parseInt(($("#presupuesto_activo").val()).replaceAll(".", ""));
    presupuesto_gasto=parseInt(($("#presupuesto_gasto").val()).replaceAll(".", ""));
    /*presupuesto_activo=parseInt(($("#presupuesto_activo").val()).replaceAll(".", ""));
    presupuesto_gasto=parseInt(($("#presupuesto_gasto").val()).replaceAll(".", ""));*/
    if(presupuesto_activo<1 && presupuesto_gasto<1){
        alert('Debe agregar una cantidad a alguno de los presupuestos.')
        flag=false;
    }
    
    if(flag){
        var form=document.getElementById("form_presupuesto");
        form.submit();
    }
}
String.prototype.replaceAll = function(search, replacement) {
    var target = this;
    return target.split(search).join(replacement);
};
Number.prototype.formatPrice = function(n, x) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
    return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&.');
};
$('#presupuesto_seguridad').val(0)
$('#presupuesto_riesgo').val(0)

function cambiarEstadoPresupuesto(estado){
    $.ajax({
        url:"<?php echo Yii::$app->request->baseUrl . '/proyecto-dependencia/estado-presupuesto'; ?>",
        type:'POST',
        dataType:"json",
        cache:false,
        data: {
            presupuesto: <?php echo $model->id?>,
            estado     :estado
        },
        beforeSend:  function() {
            $('#info_bloquear_presupuesto').html('Cambiando... <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>');
        },
        success: function(data){
            if(data.respuesta=='CERRADO'){
                //$("#bloquear_presupuesto").html('<i class="fa fa-lock"></i> DESBLOQUEAR PRESUPUESTO');
                $("#desbloquear_presupuesto").show('slow/400/fast', function() {
                    
                });

                $("#bloquear_presupuesto").hide('slow/400/fast', function() {
                    
                });

                $("#cr_pedido").hide('slow/400/fast', function() {
                });
                $("#cr_pedido_especial").hide('slow/400/fast', function() {
                });

                $('#agregar_adicional').show('slow/400/fast', function() {
                    
                });
            }else if(data.respuesta=='ABIERTO'){
                //$("#bloquear_presupuesto").html('<i class="fa fa-unlock"></i> BLOQUEAR PRESUPUESTO');
                $('#agregar_adicional').hide('slow/400/fast', function() {
                    
                });

                $("#bloquear_presupuesto").show('slow/400/fast', function() {
                    
                });

                $("#desbloquear_presupuesto").hide('slow/400/fast', function() {
                    
                });

                $("#cr_pedido").show('slow/400/fast', function() {
                });

                $("#cr_pedido_especial").show('slow/400/fast', function() {
                });                
            }
            $("#info_bloquear_presupuesto").html('');
        }
    });
}


    function cerrarPresupuesto(){
        if(orden_interna_gasto.trim()!='' && orden_interna_activo.trim()!=''){
            var asignado=verifica_asignado(<?php echo $model->id?>);

            if(asignado>0){

                alert(' Todos los pedidos deben tener asignado activo o gasto');
            }else{
            var r = confirm("¿Desea seguir para Cerrar el Presupuesto?");
                if (r == true) {
                    $("#cerrar_presupuesto").prop("disabled", true);
                    $("body").addClass("loading");
                    $.ajax({
                        url:"<?php echo Yii::$app->request->baseUrl . '/proyecto-dependencia/cerrar-presupuesto'; ?>",
                        type:'POST',
                        dataType:"json",
                        cache:false,
                        data: {
                            proyecto: <?php echo $model->id?>
                        },
                        beforeSend:  function() {
                            $('#info_cerrar_presupuesto').html('Cerrando Presupuesto... <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>');
                        },
                        success: function(data){
                            $("body").removeClass("loading");
                            $("#info_cerrar_presupuesto").html('');
                            $("#cerrar_presupuesto").prop("disabled", false);
                            $('.lock').prop("disabled", true);
                        }
                    });
                }
            }
        }else{
            alert('Debe ingresar las dos ordenes internas.')
            $('#orden_interna_gasto').focus();
        }
    }

    function verifica_asignado(id){
        var respuesta='';
        $.ajax({
            url:"<?php echo Yii::$app->request->baseUrl . '/proyecto-dependencia/verifica_asignado'; ?>",
            type:'POST',
            dataType:"json",
            cache:false,
            async:false,
            data: {
                proyecto: id
            },
            beforeSend:  function() {
                //$('#info_cerrar_presupuesto').html('Cerrando Presupuesto... <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>');
            },
            success: function(data){
                respuesta=data.respuesta;
            }
        });

        return respuesta;
    }

    function guardarDatosAdicionales(){
        $("#guardar_datos").prop("disabled", true);
        $.ajax({
            url:"<?php echo Yii::$app->request->baseUrl . '/proyecto-dependencia/guardar-datos'; ?>",
            type:'POST',
            dataType:"json",
            cache:false,
            data: {
                proyecto: <?php echo $model->id?>,
                iva: $("#iva").val(),
                orden_interna_gasto: $("#orden_interna_gasto").val(),
                orden_interna_activo: $("#orden_interna_activo").val(),
                metros2:$('#m2').val()
            },
            beforeSend:  function() {
                $('#info_guardar_datos').html('Guardando en Presupuesto... <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>');
            },
            success: function(data){
                if (data.respuesta==true) {
                    iva=$("#iva").val();
                    $("#info_guardar_datos").html('');

                    if (data.orden_interna_gasto!='' && data.orden_interna_activo!='') {
                        orden_interna_gasto=data.orden_interna_gasto;
                        orden_interna_activo=data.orden_interna_activo;
                        $('#cerrar_presupuesto').removeAttr('disabled');
                    }


                }else{
                    $("#info_guardar_datos").html(data.respuesta);
                }
                $("#guardar_datos").prop("disabled", false);
            }
        });
    }

 var iva="<?=$model->iva?>";
    $(function() {
        //$("#iva").maskMoney({thousands:'.', decimal:',', precision: 0, allowZero:true, allowNegative:false, suffix: ''});
        $("#iva").val("<?=$model->iva?>")
        $("#orden_interna_gasto").val("<?=$model->orden_interna_gasto?>")
        $("#orden_interna_activo").val("<?=$model->orden_interna_activo?>")
        $("#m2").val("<?= $model->metros_cuadrados ?>");
        
    });
</script>
<script>
  var url="<?php echo Yii::$app->request->baseUrl . '/detalle-maestra/productos'; ?>";
  var nombre_boton='btn-add-producto';
  var productos = [];
  var len_productos = productos.length;
  var index_productos = 1;
  var registros=0;
  var paginas=0;
  var contador=1;
  function buscarPedidos(page,codigo_dependencia){
    $.ajax({
        url:url,
        type:'POST',
        dataType:"json",
        cache:false,
        data: {
            page: page,
              codigo_dependencia: codigo_dependencia
        },
        beforeSend:  function() {
          $('#info').html('Cargando Productos... <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>');
        },
        success: function(data){
            registros=data.count;
            if(contador==2){
            paginas=Math.ceil((registros/500));
          }
          var obj = JSON.parse(data.resultado);
          for ( var index=0; index<obj.length; index++ ) {
              productos.push( obj[index] );
          }
          if (contador <= paginas){
              buscarPedidos(contador,codigo_dependencia);
            contador++;
            }else{
                $("#"+nombre_boton).prop('disabled', false);
            $('#info').html('');
          }
        }
    });
  }
  function enviar(){
    var flag=true;
    if($('#tipo_presupuesto').val()=='0'){
      flag=false;
    }
    if(flag){
      if(validarCantidadCreate()){
        var form=document.getElementById("form_create");
        var input = document.createElement('input');
        input.type = 'hidden';
        input.id = 'pedido';
        input.name = 'pedido';
        input.value = '<?=$id?>';
        form.appendChild(input);
        form.submit();
      }
    }else{
      alert('Elija el presupuesto');
      $('#tipo_presupuesto').focus();
      $("#tipo_presupuesto").css("border","1px solid red");
    }
  }
  function validarCantidadCreate(){
    var pasa_cantidad=true;
      $("input[name*=txt-cant]").each(function(){
        var cantidad=$(this).val();
        if(cantidad <= 0 || cantidad == null || cantidad == 'undefined' || !isNumber(cantidad)){
          pasa_cantidad=false;
          $(this).tooltip({'trigger':'focus', 'title': 'Cantidad debe ser mayor a 0'});
          //alert('Cantidad debe ser mayor a 0');
          $(this).focus();
          $(this).select();
          return false;
        }
      });
    return pasa_cantidad;
  }
  $(document).on("keypress", "input[name*=txt-cant]", function(){
      $(this).tooltip('destroy');
  });
  $("#tipo_presupuesto").change( function() {
    if($(this).val()=='0'){
    }else{
      $("#tipo_presupuesto").css("border","");
    }
  });
  function isNumber(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
  }

  $("#"+nombre_boton).prop('disabled', true);
  buscarPedidos( contador, '<?= $model->cecoo->codigo ?>' );
  contador++;
</script>

<script>
  var url_especial="<?php echo Yii::$app->request->baseUrl.'/maestra-especial/productos'; ?>";
  var nombre_boton_especial='btn-add-producto-especial';
  var codigo_dependencia;
  var productos_especial = [];
  var len_productos_especial = productos_especial.length;
  var index_productos_especial = 1;
  var registros_especial=0;
  var paginas_especial=0;
  var contador_especial=1;
  function buscarPedidosEspecial(page,codigo_dependencia){
    $.ajax({
        url:url_especial,
        type:'POST',
        dataType:"json",
        cache:false,
        data: {
            page: page,
              codigo_dependencia: codigo_dependencia
        },
        beforeSend:  function() {
          $('#info').html('Cargando Productos... <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>');
        },
        success: function(data){
            registros_especial=data.count;
            if(contador_especial==2){
            paginas_especial=Math.ceil((registros_especial/500));
          }
          var obj = JSON.parse(data.resultado);
          for ( var index=0; index<obj.length; index++ ) {
              productos_especial.push( obj[index] );
          }
          if (contador_especial <= paginas_especial){
              buscarPedidosEspecial(contador_especial,codigo_dependencia);
            contador_especial++;
            }else{
                $("#"+nombre_boton_especial).prop('disabled', false);
            $('#info').html('');
          }
        }
    });
  }
  function validarPedido(){
    var flag=true;
    if($('#tipo_presupuesto_especial').val()=='0'){
      flag=false;
    }
    if(flag){
      var val=$('#file').val();
      var pasa_cotizacion=false;
      var validar_cotizacion=false;
      /*$("select[name*=sel-produ]").each(function(){
        var cod_material=$('option:selected', this).attr('cod_material');
          if(cod_material=='1034280' || cod_material=='1034279' || cod_material=='1034281'){
            validar_cotizacion=true;//console.info($('option:selected', this).attr('cod_material'))
          }
      });*/
      if(validar_cotizacion){
          switch(val.substring(val.lastIndexOf('.') + 1).toLowerCase()){
            case 'gif': case 'jpg': case 'png': case 'jpeg': case 'xlsx': case 'xls': case 'pdf':
                //alert("an image");
                pasa_cotizacion=true;
                break;
            default:
                $('#file').val('');
                // error message here
                alert("Por favor, adjunta la cotizacion");
                break;
          }
          if(pasa_cotizacion){
            if(validarProductos()){
              if($('#observaciones').val().trim().length > 0){
                enviarFormulario();
              }else{
                alert("Por favor, ingrese la observacion");
                $('#observaciones').focus();
              }
            }
          }
      }else{
          if(validarCantidadCreate()){
            if($('#observaciones').val().trim().length > 0){
              enviarFormulario();
            }else{
              alert("Por favor, ingrese la observacion");
              $('#observaciones').focus();
            }
          }
      }
    }else{
      alert('Elija el presupuesto');
      $('#tipo_presupuesto').focus();
      $("#tipo_presupuesto").css("border","1px solid red");
    }
  }
  function enviarFormulario(){
    var form=document.getElementById("pedido-form");
    var input = document.createElement('input');
    input.type = 'hidden';
    input.id = 'pedido';
    input.name = 'pedido';
    input.value = '<?=$id?>';
    form.appendChild(input);
    form.submit();
  }
  function validarProductos(){
    var pasa_precio=true;
    var pasa_cantidad=true;
    var pasa_descripcion=true;
    var pasa_proveedor=true;
    $("input[name*=txt-precio]").each(function(){
      //console.log($(this).is('[readonly]'))
      if(!$(this).is('[readonly]')){
        var precio=$(this).val();
        if(precio <= 0 || precio == null || precio == 'undefined'){
          pasa_precio=false;
          $(this).tooltip({'trigger':'focus', 'title': 'Precio debe ser mayor a 0'});
          //alert('Precio debe ser mayor a 0');
          $(this).focus();
          $(this).maskMoney('destroy');
          return false;
        }
      }
    });
    if(pasa_precio){
      $("input[name*=txt-cant]").each(function(){
        var cantidad=$(this).val();
        if(cantidad <= 0 || cantidad == null || cantidad == 'undefined' || !isNumber(cantidad)){
          pasa_cantidad=false;
          $(this).tooltip({'trigger':'focus', 'title': 'Cantidad debe ser mayor a 0'});
          //alert('Cantidad debe ser mayor a 0');
          $(this).focus();
          $(this).select();
          return false;
        }
      });
      if(pasa_cantidad){
        $("input[name*=txt-prod]").each(function(){
          if(!$(this).is('[readonly]')){
            var desc=$(this).val();
            if(desc==''){
              pasa_descripcion=false;
              $(this).tooltip({'trigger':'focus', 'title': 'La descripcion debe Llenar'});
              $(this).focus();
              //alert('La descripcion debe Llenar');
              return false;
            }
          }
        });
        if(pasa_descripcion){
          $("input[name*=txt-proveedor]").each(function(){
            if(!$(this).is('[readonly]')){
              var prov=$(this).val();
              if(prov==''){
                pasa_proveedor=false;
                $(this).tooltip({'trigger':'focus', 'title': 'El proveedor no debe estar Vacio'});
                //alert('El proveedor no debe estar Vacio');
                $(this).focus();
                return false;
              }
            }
          });
        }
      }
    }
    
    
    return pasa_precio && pasa_cantidad && pasa_descripcion && pasa_proveedor;
  }
  function validarCantidadCreate(){
    var pasa_cantidad=true;
      $("input[name*=txt-cant]").each(function(){
        var cantidad=$(this).val();
        if(cantidad <= 0 || cantidad == null || cantidad == 'undefined' || !isNumber(cantidad)){
          pasa_cantidad=false;
          $(this).tooltip({'trigger':'focus', 'title': 'Cantidad debe ser mayor a 0'});
          //alert('Cantidad debe ser mayor a 0');
          $(this).focus();
          $(this).select();
          return false;
        }
      });
    return pasa_cantidad;
  }
  function validarDescripcionCreate(){
    var pasa_cantidad=true;
      $("input[name*=txt-prod]").each(function(){
        var cantidad=$(this).val();
        if(cantidad.length<=0){
          pasa_cantidad=false;
          $(this).tooltip({'trigger':'focus', 'title': 'Debe ingresar la descripcion del Producto'});
          //alert('Cantidad debe ser mayor a 0');
          $(this).focus();
          $(this).select();
          return false;
        }
      });
    return pasa_cantidad;
  }
  $(document).on("keypress", "input[name*=txt-cant]", function(){
    $(this).tooltip('destroy');
  });
  $(document).on("keypress", "input[name*=txt-prod]", function(){
      $(this).tooltip('destroy');
  });
  $(document).on("keypress", "input[name*=txt-proveedor]", function(){
      $(this).tooltip('destroy');
  });
  $(document).on("keypress", "input[name*=txt-precio]", function(){
    if(!$(this).is('[readonly]')){
      $(this).tooltip('destroy');
      $(this).maskMoney({thousands:'.', decimal:',', precision: 0, allowZero:false, allowNegative:false, suffix: ''});
    }
  });
  $(document).on("keyup", "input[name*=txt-precio]", function(){
    this.value = this.value.replace(/[^0-9\.]/g,'');
  });
  $("#tipo_presupuesto").change( function() {
    if($(this).val()=='0'){
    }else{
      $("#tipo_presupuesto").css("border","");
    }
  });
  /*function isNumber(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
  }*/
  $("#"+nombre_boton_especial).prop('disabled', true);
  buscarPedidosEspecial( contador_especial, '<?= $model->cecoo->codigo ?>' );
  contador_especial++;


  function procesar(){
        var total_iva=0;
        $("#procesar").prop("disabled", true);
        $.ajax({
            url:"<?php echo Yii::$app->request->baseUrl . '/proyecto-dependencia/procesar-presupuestos'; ?>",
            type:'POST',
            dataType:"json",
            cache:false,
            data: {
                proyecto: <?php echo $model->id?>
            },
            beforeSend:  function() {
                $('#info_procesar').html('Procesando Pedidos... <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>');
            },
            success: function(data){
                suma_total=parseInt(data.suma_total);
                suma_seguridad=parseInt(data.suma_seguridad);
                suma_riesgo=parseInt(data.suma_riesgo);
                suma_activo=parseInt(data.suma_activo);
                suma_gasto=parseInt(data.suma_gasto);
                $("#suma_total").html('$ '+suma_total.formatPrice()+' COP')
                $("#suma_seguridad").html('$ '+suma_seguridad.formatPrice()+' COP')
                $("#suma_riesgo").html('$ '+suma_riesgo.formatPrice()+' COP')
                $("#suma_activo").html('$ '+suma_activo.formatPrice()+' COP')
                $("#suma_gasto").html('$ '+suma_gasto.formatPrice()+' COP')
               //total_iva=((suma_total*iva)/100)+suma_total;
               total_iva_act=((suma_activo*iva)/100)+suma_activo;
               total_iva=(total_iva_act+suma_gasto);
                console.log(total_iva)
                if(presupuesto_total<total_iva){
                    $("#money-total").html('<i class="fa fa-money fa-2x" style="color:red;"></i>');
                }else{
                    $("#money-total").html('<i class="fa fa-money fa-2x" style="color:green;"></i>');
                }
                /*total_iva_seguridad=((suma_seguridad*iva)/100)+suma_seguridad;
                if(presupuesto_seguridad<total_iva_seguridad){
                    $("#money-seguridad").html('<i class="fa fa-money fa-2x" style="color:red;"></i>');
                }else{
                    $("#money-seguridad").html('<i class="fa fa-money" style="color:green;"></i>');
                }
                total_iva_riesgo=((suma_riesgo*iva)/100)+suma_riesgo;
                if(presupuesto_riesgo<total_iva_riesgo){
                    $("#money-riesgo").html('<i class="fa fa-money fa-2x" style="color:red;"></i>');
                }else{
                    $("#money-riesgo").html('<i class="fa fa-money" style="color:green;"></i>');
                }*/
                total_iva_activo=((suma_activo*iva)/100)+suma_activo;
                /*if(presupuesto_activo<suma_activo){
                    $("#money-activo").html('<i class="fa fa-money fa-2x" style="color:red;"></i>');
                }else{
                    $("#money-activo").html('<i class="fa fa-money" style="color:green;"></i>');
                }*/
                total_iva_gasto=((suma_gasto*iva)/100)+suma_gasto;
                /*if(presupuesto_gasto<total_iva_gasto){
                    $("#money-gasto").html('<i class="fa fa-money fa-2x" style="color:red;"></i>');
                }else{
                    $("#money-gasto").html('<i class="fa fa-money" style="color:green;"></i>');
                }*/
                ///***************////
                if((presupuesto_total-total_iva)<0){
                    $("#saldo_total").html('<b style="color:red;">$ '+(presupuesto_total-total_iva).formatPrice()+'</b> COP');
                }else{
                    $("#saldo_total").html('<b style="color:green;">$ '+(presupuesto_total-total_iva).formatPrice()+'</b> COP');
                }
               /* if((presupuesto_seguridad-total_iva_seguridad)<0){
                    $("#saldo_seguridad").html('<b style="color:red;">$ '+(presupuesto_seguridad-total_iva_seguridad).formatPrice()+'</b> COP');
                }else{
                    $("#saldo_seguridad").html('<b style="color:green;">$ '+(presupuesto_seguridad-total_iva_seguridad).formatPrice()+'</b> COP');
                }
                if((presupuesto_riesgo-total_iva_riesgo)<0){
                    $("#saldo_riesgo").html('<b style="color:red;">$ '+(presupuesto_riesgo-total_iva_riesgo).formatPrice()+'</b> COP');
                }else{
                    $("#saldo_riesgo").html('<b style="color:green;">$ '+(presupuesto_riesgo-total_iva_riesgo).formatPrice()+'</b> COP');
                }*/
                /*if((presupuesto_activo-total_iva_activo)<0){
                    $("#saldo_activo").html('<b style="color:red;">$ '+(presupuesto_activo-total_iva_activo).formatPrice()+'</b> COP');
                }else{
                    $("#saldo_activo").html('<b style="color:green;">$ '+(presupuesto_activo-total_iva_activo).formatPrice()+'</b> COP');
                }
                if((presupuesto_gasto-total_iva_gasto)<0){
                    $("#saldo_gasto").html('<b style="color:red;">$ '+(presupuesto_gasto-total_iva_gasto).formatPrice()+'</b> COP');
                }else{
                    $("#saldo_gasto").html('<b style="color:green;">$ '+(presupuesto_gasto-total_iva_gasto).formatPrice()+'</b> COP');
                }*/
                $("#total_iva").html('$ '+total_iva.formatPrice()+' COP');
                //$("#total_iva_seguridad").html('$ '+total_iva_seguridad.formatPrice()+' COP');
                //$("#total_iva_riesgo").html('$ '+total_iva_riesgo.formatPrice()+' COP');
                $("#total_iva_activo").html('$ '+total_iva_activo.formatPrice()+' COP');
                //$("#total_iva_gasto").html('$ '+total_iva_gasto.formatPrice()+' COP');
                $("#info_procesar").html('');
                $("#procesar").prop("disabled", false);
            }
        });
    }


    var estado_na;
    var producto_na;
    var tipo_na;
    var cantidad_na;
    function setDataNa(estado,producto,tipo,cantidad){
        estado_na=estado;
        producto_na=producto;
        tipo_na=tipo;
        cantidad_na=cantidad;
        $('#cantidad-no-aprobado').find('option').remove().end().append('<option value="0">[Elija Cantidad]</option>')
    .val('0');
        for (var i = 1; i <= cantidad_na; i++) {
            $('#cantidad-no-aprobado').append('<option value="'+i+'">'+i+'</option>');
        }
    }


    function cambiarEstadoNa(){
        if($('#cantidad-no-aprobado').val()!='0'){
            $.ajax({
                url:"<?php echo Yii::$app->request->baseUrl . '/proyecto-dependencia/cambiar-estado'; ?>",
                type:'POST',
                dataType:"json",
                cache:false,
                data: {
                    estado: estado_na,
                    producto: producto_na,
                    tipo: tipo_na,
                    cantidad: $('#cantidad-no-aprobado').val(),
                    motivo: $("#motivo-no-aprobado").val()
                },
                beforeSend:  function() {
                    $('#info').html('Cambiando... <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>');
                },
                success: function(data){
                    if (data.respuesta==true) {
                        consultar(0)
                    }
                    $('#modal-no-aprobado').modal('toggle');
                    $("#info").html('');
                    estado_na="";
                    producto_na="";
                    tipo_na="";
                    cantidad_na="";
                    $("#motivo-no-aprobado").val("")
                    $('#cantidad-no-aprobado').find('option').remove().end().append('<option value="0">[Elija Cantidad]</option>')
                }
            });
        }else{
            alert('Por favor elija al menos 1 Cantidad')
        }
        
    }

    function cambiarGastoActivo(estado,producto,tipo){
        $.ajax({
            url:"<?php echo Yii::$app->request->baseUrl . '/proyecto-dependencia/gasto-activo'; ?>",
            type:'POST',
            dataType:"json",
            cache:false,
            data: {
                estado: estado,
                producto: producto,
                tipo: tipo
            },
            beforeSend:  function() {
                $('#info').html('Cambiando... <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>');
            },
            success: function(data){
                if (data.respuesta==true) {
                    consultar(0)
                }
                $("#info").html('');
            }
        });
    }

     function cambiarGastoActivoCheckBox(estado,tipo){
        var checkboxValues = new Array();
        //recorremos todos los checkbox seleccionados con .each
        $('.checkBoxid').each(function() {
            if($(this).prop('checked')==true){
                checkboxValues.push($(this).attr("id"));
            }
        });
        var productos_id = checkboxValues.toString();
        $.ajax({
            url:"<?php echo Yii::$app->request->baseUrl . '/proyecto-dependencia/gasto-activo-multiple'; ?>",
            type:'POST',
            dataType:"json",
            cache:false,
            data: {
                estado: estado,
                productos_id: productos_id,
                tipo: tipo
            },
            beforeSend:  function() {
                $('#info').html('Cambiando... <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>');
            },
            success: function(data){
                //if (data.respuesta==true) {
                    consultar(0)
                //}
                $("#info").html('');
            }
        });
    }

    function subirCotizacion(){
        var val=$('#file').val();
        var pasa_cotizacion=false;
        switch(val.substring(val.lastIndexOf('.') + 1).toLowerCase()){
            case 'gif': case 'jpg': case 'png': case 'jpeg': case 'xlsx': case 'xls': case 'pdf':
                //alert("an image");
                pasa_cotizacion=true;
                break;
            default:
                $('#file').fileinput('clear');
                // error message here
                alert("Por favor, adjunta la cotizacion");
                break;
        }
        if(pasa_cotizacion){
            var data = new FormData();
            data.append('presupuesto',<?php echo $model->id?>);
            var file_input = $('#file');
            data.append('file',file_input[0].files[0]);
            $.ajax({
                url:"<?php echo Yii::$app->request->baseUrl . '/proyecto-dependencia/subir-cotizacion'; ?>",
                type:'POST',
                data:data,
                cache:false,
                contentType:false,
                processData:false,
                beforeSend:  function() {
                    $('#info').html('Subiendo... <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>');
                },
                success: function(data_response){
                    if(data_response.respuesta==true){//colocar icono de descarga
                        $('#modal').modal('hide')
                        $('#info').html('')
                        $('#file').fileinput('clear');
                    }
                }
            });
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
        input = document.createElement('input');
        input.type = 'hidden';
        input.id = 'proyecto';
        input.name = 'proyecto';
        input.value = '<?php echo $model->id?>';
        form.appendChild(input);
        form.action=url_tab;
        form.submit();
    }

    $('#tipo_reportes').change(function(event) {
        /* Act on the event */
        let reporte=$(this).val();

        if(reporte==6){

            $('#avance').removeAttr('disabled')
        }else{

            $('#avance').attr('disabled', 'disabled');
             $("#avance").val('0')
        }

    });

    //Full calendar plugins para cronograma
    $(function(){
        $('#calendar').fullCalendar({
          selectable: true,
          height:"parent",
          lang: 'es',
          header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay,listWeek'
          },

          //defaultDate: '2019-01-12',
          /*navLinks: true, // can click day/week names to navigate views
          editable: false,
          eventLimit: true, // allow "more" link when too many events*/
          eventRender: function( event, element, view ) {
           element.find('.fc-title').prepend('<span class="fa fa-calendar"></span> '); 
          },
          eventClick: function(calEvent, jsEvent, view) {
            $('#editar-crono').attr({
                href: '<?php echo Yii::$app->request->baseUrl?>/proyecto-dependencia/editarcronograma?id='+calEvent.id+'&id_proyecto=<?= $id?>'
            });
            $('#eliminar-crono').attr({
                href: '<?php echo Yii::$app->request->baseUrl?>/proyecto-dependencia/deletecronograma?id='+calEvent.id+'&id_proyecto=<?= $id?>'
            });
            $.ajax({
                url:"<?php echo Yii::$app->request->baseUrl . '/proyecto-dependencia/info-cronograma'; ?>",
                type:'POST',
                dataType:"json",
                data:{id:calEvent.id},
                cache:false,
                //contentType:false,
                //processData:false,
                beforeSend:  function() {
                    $('#cronobody').html('Cargando... <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>');
                },
                success: function(data_response){
                    
                    $('#cronobody').html(data_response.respuesta);
                    
                }
            });
            $('#Modaleditarcronograma').modal('show');
            

          },
          dayClick: function(date, jsEvent, view) {
            $('#Modalcronograma').modal('show');
            $('#cronogramaproyecto-fecha_inicio-disp').val(date.format())
            $('#cronogramaproyecto-fecha_inicio').val(date.format())
            //alert('Clicked on: ' + date.format());

          },
          select: function(startDate, endDate) {
            //alert('selected ' + startDate.format() + ' to ' + endDate.format());
            $('#Modalcronograma').modal('show');
            $('#cronogramaproyecto-fecha_inicio-disp').val(startDate.format());
            $('#cronogramaproyecto-fecha_inicio').val(startDate.format());

            $('#cronogramaproyecto-fecha_fin-disp').val(endDate.format());
            $('#cronogramaproyecto-fecha_fin').val(endDate.format());


          },
          events:<?= $json_crono?>
        });


        var colorChooser = $('#color-chooser-btn')
        $('#color-chooser > li > a').click(function (e) {
          e.preventDefault()
          //Save color
          currColor = $(this).css('color')
          console.log(currColor)
          //Add color effect to button
          $('#add-new-event').css({ 'background-color': currColor, 'border-color': currColor })
          //var rgb=$('#add-new-event').css('background-color')
          //console.log(hexadecimal)
          /*var reemplazar=rgb.replace("rgb",'');
          reemplazar=reemplazar.replace("(",'');
          reemplazar=reemplazar.replace(")",'');
          var explode=reemplazar.split(',');
          console.log(explode);
          var hexadecimal=rgb2hex(explode[0],explode[1],explode[2]);*/
          $('#new-event').val(currColor)
        });
    });

    function rgb2hex(red, green, blue) {
        var rgb = blue | (green << 8) | (red << 16);
        return '#' + (0x1000000 + rgb).toString(16).slice(1)
    }

    function cambiarEstado(estado,producto,tipo,cantidad){//dovolver a pedidos normales y especiales desde no aprobados
        $.ajax({
            url:"<?php echo Yii::$app->request->baseUrl . '/proyecto-dependencia/cambiar-estado'; ?>",
            type:'POST',
            dataType:"json",
            cache:false,
            data: {
                estado: estado,
                producto: producto,
                tipo: tipo,
                cantidad: cantidad
            },
            beforeSend:  function() {
                $('#info').html('Cambiando... <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>');
            },
            success: function(data){
                if (data.respuesta==true) {
                    consultar(0)
                }
                $("#info").html('');
            }
        });
    }

     $(document).on( "click", ".checkBoxAll", function() {
        $(".checkBoxid").prop('checked', $(this).prop('checked'));
    });
    $(document).on( "click", ".checkBoxid", function() {
        var exist=false;
        $('.checkBoxid').each(function() {
            if($(this).prop('checked')==false){
                exist=true;return 0;
            }
        });
        if(!exist){
            $(".checkBoxAll").prop('checked', true);
        }else{
            $(".checkBoxAll").prop('checked', false);
        }
    });
</script>
