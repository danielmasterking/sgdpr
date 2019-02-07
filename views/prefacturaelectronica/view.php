<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\LinkPager;
use kartik\date\DatePicker;

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Prefactura Fijas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"); 
?>
<h3 style="text-align: center;">Prefactura <?=$model->fkDependencia->nombre?> mes de <?=strtoupper ($meses[$model->mes-1]);?> de <?=$model->ano?></h3>


<?php 

    $flashMessages = Yii::$app->session->getAllFlashes();
    if ($flashMessages) {
        foreach($flashMessages as $key => $message) {
            echo "<div class='alert alert-" . $key . " alert-dismissible' role='alert'>
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                    $message
                </div>";   
        }
    }
?>



<?= Html::a('<i class="fa fa-arrow-left"></i> Volver a Pre-facturas',Yii::$app->request->baseUrl.'/prefacturaelectronica/index', ['class'=>'btn btn-primary']) ?>
<br><br>
<div class="row">
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-6">
            MES DE FACTURACION<br>
            <b><?=strtoupper ($meses[$model->mes-1]);?></b>
            </div>
            <div class="col-md-6">
                REGIONAL<br>
                <b><?=strtoupper ($model->regional);?></b>
            </div>
        </div>
         <div class="row">
            <div class="col-md-12">
                EMPRESA DE SEGURIDAD<br>
                <b><?php echo $model->fkEmpresa->nombre?></b>
            </div>
        </div> 
        <div class="row">
            <div class="col-md-6">
                CIUDAD<br>
                <b><?=strtoupper ($model->ciudad);?></b>
            </div>
            <div class="col-md-6">
                MARCA<br>
                <b><?=strtoupper ($model->marca);?></b>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                DEPENDENCIA<br>
                <b><?=$model->fkDependencia->nombre?></b>
            </div>
            <div class="col-md-6">
                CEBE<br>
                <b><?=$model->fkDependencia->cebe?></b>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
            </div>
            <div class="col-md-6">
                CECO<br>
                <b><?=$model->fkDependencia->ceco?></b>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <img src="<?php echo Url::to('@web/'.$model->fkEmpresa->logo, true)?>" width="200" width="110"/>
    </div> 
</div>
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
<?php }?>
<br>
<!-- ********************************************************************* -->
<!--Monitoreos-->
<div class="panel panel-primary">
    <div class="panel-heading" role="tab" id="headingOne">
        <h4 class="panel-title">
            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#monitoreo" aria-expanded="true" aria-controls="collapseOne">
                Monitoreo de alarmas
            </a>
        </h4>
    </div>
    <div id="monitoreo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Total</th>
                            <th>Monitoreo</th>
                            <th>Sistema Monitoreado</th>
                            <th>Cantidad Servicios</th>
                            <th>Valor Unitario</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <!-- <th>Empresa</th>  -->                           
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $total_monitoreo=0;
                            foreach($monitoreos as $row_monitoreo): 
                        ?>
                        <tr>
                            <td>
                                <?php 
                                    $total_monitoreo=$total_monitoreo+$row_monitoreo->valor_total;
                                    echo '$ '.number_format($row_monitoreo->valor_total, 0, '.', '.').' COP';
                                ?>
                            </td>
                            <td><?= $row_monitoreo->monitoreo  ?></td>
                            <td><?= $row_monitoreo->sistemanonitoreado->nombre  ?></td>
                            <td><?= $row_monitoreo->cantidad_servicios  ?></td>
                            <td><?= '$ '.number_format($row_monitoreo->valor_unitario, 0, '.', '.').' COP'  ?></td>
                            <td><?= $row_monitoreo->fecha_inicio  ?></td>
                            <td><?= $row_monitoreo->fecha_fin  ?></td>
                            <!-- <td><?= $row_monitoreo->empresa->nombre?></td> -->
                            
                        </tr>
                    <?php endforeach;?>
                    <tr>

                        <td>
                           <b> <?php 
                            
                                echo '$ '.number_format($total_monitoreo, 0, '.', '.').' COP';
                            ?></b>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        

                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!---->
<div class="panel panel-primary">
    <div class="panel-heading" role="tab" id="headingOne">
        <h4 class="panel-title">
            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#fijo" aria-expanded="true" aria-controls="collapseOne">
                Dispositivos fijos
            </a>
        </h4>
    </div>
    <div id="fijo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped display my-data" >
                    <thead>
                        <tr>
                            <th >$/Mes</th>
                            <th >Tipo Alarma </th>
                            <th>Sistema</th>
                            <th >Estado</th>
                            <th >Marca</th>
                            <th >Descripcion</th>
                            <th >Referencia</th>
                            <th >Ubicacion</th>
                            <th >#Zona Panel</th>
                            <th >Meses Pactados</th>
                            <th >Fecha Inicio</th>
                            <th >Fecha Ultima reposicion</th>
                            <th>Detalle ubicacion</th>
                            <!-- <th>Emprea</th> -->
                        </tr>
                    </thead>
                    <?php 
                    $total=0;
                    $total_inalambricos=0;
                    $total_incendio=0;
                    $total_alambrados=0;
                    $total_intrusion=0;
                    $subtotal_incendio=0;
                    $subtotal_intrusion=0;
                    foreach ($dispositivos as $value) {
                        
                        ?>
                        <tr>
                            <td>
                                <?php 
                                    echo '$ '.$value->valor_arrendamiento_mensual.' COP';
                                    $total=$total+$modelo->number_unformat($value->valor_arrendamiento_mensual);


                                    if ($value->sistema=='Inalámbrico') {
                                         $total_inalambricos= $total_inalambricos+1;
                                    }elseif($value->sistema=='Alambrado'){
                                        $total_alambrados=$total_alambrados+1;
                                    }

                                    if ($value->tipoalarma->nombre=='Incendio') {
                                        $total_incendio=$total_incendio+1;
                                        $subtotal_incendio=$subtotal_incendio+$modelo->number_unformat($value->valor_arrendamiento_mensual);

                                    }elseif($value->tipoalarma->nombre=='Intrusión'){
                                        $total_intrusion=$total_intrusion+1;
                                        $subtotal_intrusion=$subtotal_intrusion+$modelo->number_unformat($value->valor_arrendamiento_mensual);

                                    }
                                   
                                ?>
                                    
                            </td>
                            <td><?=$value->tipoalarma->nombre?></td>
                            <td><?=$value->sistema?></td>
                            <td><?=$value->estado?></td>
                            <td><?=$value->marcaalarma->nombre?></td>
                            <td>
                            <?php
                               echo  $value->desc->descripcion
                            ?>
                            </td>
                            <td>
                            <?php
                                echo $value->referencia
                            ?>
                            </td>
                            <td>
                            <?php
                                echo $value->areas->nombre
                            ?>
                            </td>
                            <td><?=$value->zona_panel?></td>
                            <td><?=$value->meses_pactados?></td>
                            <td><?=$value->fecha_inicio?></td>
                            <td><?=$value->fecha_ultima_reposicion?></td>
                            <td><?=$value->detalle_ubicacion?></td>
                            <!-- <td><?php //echo $value->fkEmpresa->nombre?></td> -->
                            
                            
                            
                        </tr>
                    <?php 
                    } ?>
                    <tfoot>
                        <tr>     
                            <td>
                               <b><?php echo '$ '.number_format($total, 0, '.', '.').' COP'?></b>
                            </td>      
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>   
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>       
                             

                        </tr>
                    </tfoot>
                </table>


                <?php 

                 // echo LinkPager::widget([
                 //      'pagination' => $pag_fijos,
                 // ]);

                ?>

            </div>
        </div>
    </div>
</div>

<!--DISPOSITIVO VARIABLE-->
    
<div class="panel panel-primary">
    <div class="panel-heading" role="tab" id="headingOne">
        <h4 class="panel-title">
            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#variable" aria-expanded="true" aria-controls="collapseOne">
                Dispositivos variables
            </a>
        </h4>
    </div>
    <div id="variable" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
        <div class="panel-body">

        <?php
        if($model->estado=='abierto'){
            echo Html::a('<i class="fa fa-plus"></i> Agregar Nuevo Variable',Yii::$app->request->baseUrl.'/prefacturaelectronica/createvariable?id='.$model->id,['class'=>'btn btn-primary']);
        }

        echo "<br><br>";
        ?>


            <div class="table-responsive">
                <table class="table table-striped display my-data">
                    <thead>
                        <tr>
                            <th >$/Valor Novedad</th>
                            <th >Tipo Alarma </th>
                            <th>Sistema</th>
                            <th >Servicio</th>
                            <th >Marca</th>
                            <th >Descripcion</th>
                            <th >Referencia</th>
                            <th >Ubicacion</th>
                            <th >#Zona Panel</th>
                            <th >Fecha Inicio</th>
                            <th >Fecha Final</th>
                            <th >Total Dias</th>
                            <th>Explicacion</th>
                            <!-- <th>Empresa</th> -->
                            <th></th>
                        </tr>
                    </thead>
                    <?php 
                    $total_variable=0;
                    $total_serv_fallo=0;
                    $total_serv_fijo_promo=0;
                    $total_serv_terminacion=0;
                    $subtotal_serv_fallo=0;
                    $subtotal_serv_fijo_promo=0;
                    $subtotal_terminacion=0;

                    foreach ($variables as $rows_variable) {
                        
                        ?>
                        <tr>
                             <td>
                            <?php
                               //$total_variable=$total_variable+$modelo->number_unformat($rows_variable->valor_novedad);
                              echo $rows_variable->valor_novedad;
                            ?>
                                
                            </td>
                            <td><?= $rows_variable->tipoalarma->nombre ?></td>
                            <td><?= $rows_variable->sistema ?></td>
                            <td><?= $rows_variable->servicios->nombre ?></td>
                            <td><?= $rows_variable->marcaalarma->nombre ?></td>
                            <td><?= $rows_variable->desc->descripcion?></td>
                            <td>
                            <?= $rows_variable->referencia
                            ?>
                            </td>
                            <td>
                            <?= $rows_variable->areas->nombre ?>
                            </td>
                            <td><?=$rows_variable->zona_panel?></td>
                            <td><?=$rows_variable->fecha_inicio?></td>
                            <td><?=$rows_variable->fecha_fin?></td>
                            <td><?=$rows_variable->total_dias?></td>
                            <td><?=$rows_variable->explicacion?></td>
                            <!-- <td><?php //echo $rows_variable->fkEmpresa->nombre ?></td> -->
                            <td>
                                <?php 

                                    if ($rows_variable->servicios->nombre=='Fallo') {
                                        
                                        $total_serv_fallo=$total_serv_fallo+1;
                                        $subtotal_serv_fallo=$subtotal_serv_fallo+$modelo->number_unformat($rows_variable->valor_novedad);

                                    }elseif($rows_variable->servicios->nombre=='Fijo Proporcional'){
                                        
                                        $total_serv_fijo_promo=$total_serv_fijo_promo+1;
                                        $subtotal_serv_fijo_promo=$subtotal_serv_fijo_promo+$modelo->number_unformat($rows_variable->valor_novedad);

                                    }elseif($rows_variable->servicios->nombre=='Terminación'){

                                        $total_serv_terminacion=$total_serv_terminacion+1;
                                        $subtotal_terminacion=$subtotal_terminacion+$modelo->number_unformat($rows_variable->valor_novedad);
                                    }


                                    if($model->estado=='abierto'){
                                        echo Html::a('<i class="fa fa-trash"></i>',Yii::$app->request->baseUrl.'/prefacturaelectronica/deletedispositivo?id_disp='.$rows_variable->id.'&id='.$model->id,['title'=>'Eliminar','data-method'=>'post','data-confirm' => 'Está seguro de eliminar elemento','class'=>'btn btn-danger btn-xs']);
                                    }



                                    if ($rows_variable->servicios->nombre!='Fallo' && $rows_variable->servicios->nombre!='Terminación') {
                                        $total_variable=$total_variable+$modelo->number_unformat($rows_variable->valor_novedad);
                                    }else{
                                        $total_variable=$total_variable-$modelo->number_unformat($rows_variable->valor_novedad);
                                    }
                                ?>
                            </td>


                        </tr>
                    <?php 
                     } ?>
                    <tfoot>
                        <tr>         
                            <td>
                               <b><?php echo '$ '.number_format($total_variable, 0, '.', '.').' COP'?></b>
                            </td>  
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>   
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>  
                        </tr>
                    </tfoot>

                </table>
            </div>
        </div>
    </div>
</div>

<table class="table">

    <tr>
        <td><b>Total equipos inalámbricos</b></td>
        <td><?=  $total_inalambricos?></td>
        <td><b>Total alarmas de Incendio</b></td>
        <td><?= $total_incendio ?></td>
    </tr>

    <tr>
        <td><b>Total equipos alambrados</b></td>
        <td><?=  $total_alambrados?></td>
        <td><b>Total alarmas de Intrusión</b></td>
        <td><?= $total_intrusion ?></td>
    </tr>


    <tr>
        <td><b>SubTotal Valor Mensual Equipos en Arriendo/Comodato "Incendio"</b></td>
        <td><?php echo '$ '.number_format($subtotal_incendio, 0, '.', '.').' COP'?></td>

         <td><b>SubTotal Valor Mensual Equipos en Arriendo/Comodato "Intrusión"</b></td>
        <td><?php echo '$ '.number_format($subtotal_intrusion, 0, '.', '.').' COP'?></td>
       
    </tr>
    
    

    <tr>
        <td><b>SubTotal Valor Mensual Monitoreo de Alarmas</b></td>
        <td>
            <?php  echo '$ '.number_format($total_monitoreo, 0, '.', '.').' COP'; ?>
            
        </td>

        <td><b>SubTotal Valor Mensual Equipos en Arriendo/Comodato</b></td>
        <td>
            <?php 
                
                echo '$ '.number_format($total, 0, '.', '.').' COP';
            ?>
            
        </td>

    </tr>

    <tr>
        
    <td></td>
    <td></td>
    <td></td>
    <td></td>

    </tr>    


    <tr>
        <td><b>Total servicios  Fallo </b></td>
        <td>
            <?php 
                
                echo $total_serv_fallo;
            ?>
            
        </td>

        <td><b>Total servicios Fijo proporcional </b></td>
        <td>
            <?php 
                
                echo $total_serv_fijo_promo;
            ?>
            
        </td>

    </tr>


    <tr>
        <td><b>Total servicios Terminación del servicio </b></td>
        <td>
            <?php 
                
                echo $total_serv_terminacion;
            ?>
            
        </td>

        <td><b>Total servicios </b></td>
        <td>
            <?php 
                $total_servicios=($total_serv_fallo+$total_serv_fijo_promo+$total_serv_terminacion);
                echo $total_servicios;
            ?>
            
        </td>

    </tr>

    <tr>
        <td><b>SubTotal Valor Mensual Fallo</b></td>
        <td>
            <?php 
                
               echo '$ '.number_format($subtotal_serv_fallo, 0, '.', '.').' COP';
            ?>
            
        </td>

        <td><b>SubTotal Valor Mensual Fijo proporcional </b></td>
        <td>
            <?php 
               
               echo '$ '.number_format($subtotal_serv_fijo_promo, 0, '.', '.').' COP';
            ?>
            
        </td>

    </tr>

    <tr>
        <td><b>SubTotal Valor Mensual Terminación del servicio</b></td>
        <td>
            <?php 
                
               echo '$ '.number_format($subtotal_terminacion, 0, '.', '.').' COP';
            ?>
            
        </td>

        <td><b>Total Valor Dispositivo Fijo y Variable  </b></td>
        <td>
            <?php 
               
                $total_variable_fijo=($total+$total_variable)+$total_monitoreo;

               echo '$ '.number_format($total_variable_fijo, 0, '.', '.').' COP';
            ?>
            
        </td>

    </tr>

</table>

<!---->
<?php if($model->estado=='abierto'){?>
<script type="text/javascript">

    $(document).ready(function(){
        // $('table.display').DataTable({
        //     "language":{
        //         "url":"//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
        //     },

        // });
    });


    function actualizar(){
        var numero_factura="<?php echo $model->numero_factura; ?>";
        var fecha_factura="<?php echo $model->fecha_factura; ?>";

        if (numero_factura=='' || fecha_factura=='') {

            alert('La prefactura debe tener asignado un numero y fecha de factura para ser finalizada');

            return false;
        }else{

                var url="<?php echo Url::toRoute('prefacturaelectronica/update')?>";
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
                                location.href="<?php echo Url::toRoute('prefacturaelectronica/view')?>"+"?id="+"<?=$model->id?>";
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
        var r = confirm("¿Seguro desea Eliminar este Dispositivo?");
        if (r == true) {
            location.href="<?php echo Url::toRoute('prefactura-fija/delete-prefactura-dispositivo?id=');?>"+id+"&prefactura=<?=$model->id?>";
        }
    }
</script>
<?php }?>