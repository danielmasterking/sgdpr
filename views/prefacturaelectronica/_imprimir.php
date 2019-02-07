<?php
use yii\helpers\Url;
$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
$styletd='style="padding: 5px;text-align: center;font-size: 9px;border: 1px solid black;"';
$styletd2='style="padding: 5px;font-size: 10px;border: 1px solid black;"';
$styleth='style="padding: 5px;text-align: center;font-size: 9px;border: 1px solid black;';
date_default_timezone_set ( 'America/Bogota');
?>
<h3 style="text-align: center;font-size: 16px;">Pre-factura <?=$model->fkDependencia->nombre?> mes de <?=strtoupper ($meses[$model->mes-1]);?> de <?=$model->ano?></h3>
<table style="width: 100%;border-collapse: collapse;">
    <tr>
        <td <?=$styletd2?> ><b>MES DE FACTURACION:</b></td>
        <td <?=$styletd2?> ><?=strtoupper ($meses[$model->mes-1]);?></td>
        <td <?=$styletd2?> ><b>REGIONAL:</b></td>
        <td <?=$styletd2?> ><?=strtoupper ($model->regional);?></td>
        <td rowspan="6" style="border: 1px solid black;">
            <img src="<?php echo $model->fkEmpresa->logo?>" width="200" width="110"/>
        </td> 
    </tr>
    <tr>
        <td <?=$styletd2?> ><b>EMPRESA DE SEGURIDAD:</b></td>
        <td <?=$styletd2?> colspan="3"><?=$model->fkEmpresa->nombre?></td>
    </tr> 
    <tr>
        <td <?=$styletd2?> ><b>CIUDAD:</b></td>
        <td <?=$styletd2?> ><?=strtoupper ($model->ciudad);?></td>
        <td <?=$styletd2?> ><b>MARCA:</b></td>
        <td <?=$styletd2?> ><?=strtoupper ($model->marca);?></td>
    </tr>
    <tr>
        <td <?=$styletd2?> ><b>DEPENDENCIA:</b></td>
        <td <?=$styletd2?> ><?=$model->fkDependencia->nombre?></td>
        <td <?=$styletd2?> ><b>CEBE:</b></td>
        <td <?=$styletd2?> ><?=$model->fkDependencia->cebe?></td>
    </tr>
    <tr>
        <td <?=$styletd2?> ><b>FECHA:</b></td>
        <td <?=$styletd2?> ><?= date('Y-m-d') ?></td>
        <td <?=$styletd2?> ><b>CECO:</b></td>
        <td <?=$styletd2?> ><?=$model->fkDependencia->ceco?></td>
    </tr>

    <tr>
        <td <?=$styletd2?> ><b>NUMERO FACTURA:</b></td>
        <td <?=$styletd2?> ><?= $model->numero_factura ?></td>
        <td <?=$styletd2?> ><b>FECHA FACTURA:</b></td>
        <td <?=$styletd2?> ><?=$model->fecha_factura ?></td>
    </tr>
</table>
<br>

<h2 style="text-align: center;font-size: 16px;"> Monitoreo de alarmas</h2>
<table style="width: 100%;border-collapse: collapse;">
    <thead>
        <tr>
            <th <?=$styleth.'"'?>>Monitoreo</th>
            <th <?=$styleth.'"'?>>Sistema Monitoreado</th>
            <th <?=$styleth.'"'?>>Cantidad Servicios</th>
            <th <?=$styleth.'"'?>>Valor Unitario</th>
            <th <?=$styleth.'"'?>>Fecha Inicio</th>
            <th <?=$styleth.'"'?>>Fecha Fin</th>
            <!-- <th <?php //echo $styleth.'"'?>>Empresa</th> -->
            <th <?=$styleth.'"'?>>Total</th>

        </tr>
    </thead>
        <tbody>
            <?php 
                $total_monitoreo=0;
                foreach($monitoreos as $row_monitoreo): 
            ?>
            <tr>
                <td <?=$styletd?>><?= $row_monitoreo->monitoreo  ?></td>
                <td <?=$styletd?>><?= $row_monitoreo->sistemanonitoreado->nombre  ?></td>
                <td <?=$styletd?>><?= $row_monitoreo->cantidad_servicios  ?></td>
                <td <?=$styletd?>><?= '$ '.number_format($row_monitoreo->valor_unitario, 0, '.', '.').' COP'  ?></td>
                <td <?=$styletd?>><?= $row_monitoreo->fecha_inicio  ?></td>
                <td <?=$styletd?>><?= $row_monitoreo->fecha_fin  ?></td>
                <!-- <td <?php //echo $styletd?>><?php // $row_monitoreo->empresa->nombre?></td> -->
                <td <?=$styletd?>>
                    <?php 
                        $total_monitoreo=$total_monitoreo+$row_monitoreo->valor_total;
                        echo '$ '.number_format($row_monitoreo->valor_total, 0, '.', '.').' COP';
                    ?>
                </td>
            </tr>
        <?php endforeach;?>
        <tr>
            <td <?=$styletd?>></td>
            <td <?=$styletd?>></td>
            <td <?=$styletd?>></td>
            <td <?=$styletd?>></td>
            <td <?=$styletd?>></td>
            <td <?=$styletd?>></td>
            <!-- <td <?php //echo $styletd?>></td> -->
            <td <?=$styletd?>>
                <?php 
                
                    echo '$ '.number_format($total_monitoreo, 0, '.', '.').' COP';
                ?>
            </td>

        </tr>
        </tbody>
</table>
<br>
<h2 style="text-align: center;font-size: 16px;">Dispositivos Fijos</h2>
<table style="width: 100%;border-collapse: collapse;">
    <thead>
        <tr>
            <th <?=$styleth.'"'?>>Tipo Alarma </th>
            <th <?=$styleth.'"'?>>Sistema</th>
            <th <?=$styleth.'"'?>>Estado</th>
            <th <?=$styleth.'"'?>>Marca</th>
            <th <?=$styleth.'"'?>>Descripcion</th>
            <th <?=$styleth.'"'?>>Referencia</th>
            <th <?=$styleth.'"'?>>Ubicacion</th>
            <th <?=$styleth.'"'?>>#Zona Panel</th>
            <th <?=$styleth.'"'?>>Meses Pactados</th>
            <th <?=$styleth.'"'?>>Fecha Inicio</th>
            <th <?=$styleth.'"'?>>Fecha Ultima reposicion</th>
            <th <?=$styleth.'"'?>>Detalle ubicacion</th>
            <!-- <th <?php //echo $styleth.'"'?>>Empresa</th> -->
            <th <?=$styleth.'"'?>>$/Mes</th>
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
        <td <?=$styletd?>><?=$value->tipoalarma->nombre?></td>
        <td <?=$styletd?>><?=$value->sistema?></td>
        <td <?=$styletd?>><?=$value->estado?></td>
        <td <?=$styletd?>><?=$value->marcaalarma->nombre?></td>
        <td <?=$styletd?>>
        <?php
           echo  $value->desc->descripcion
        ?>
        </td>
        <td <?=$styletd?>>
        <?php
            echo $value->referencia
        ?>
        </td>
        <td <?=$styletd?>>
        <?php
            echo $value->areas->nombre
        ?>
        </td>
        <td <?=$styletd?>><?=$value->zona_panel?></td>
        <td <?=$styletd?>><?=$value->meses_pactados?></td>
        <td <?=$styletd?>><?=$value->fecha_inicio?></td>
        <td <?=$styletd?>><?=$value->fecha_ultima_reposicion?></td>
        <td <?=$styletd?>><?=$value->detalle_ubicacion?></td>
        <!-- <td <?php //echo $styletd?>><?php //$value->fkEmpresa->nombre?></td> -->
        <td <?=$styletd?>>
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
        
        
        
    </tr>
    <?php 
    } ?>
    <tr>           
        <td <?=$styletd?>></td>
        <td <?=$styletd?>></td>
        <td <?=$styletd?>></td>
        <td <?=$styletd?>></td>
        <td <?=$styletd?>></td>
        <td <?=$styletd?>></td>
        <td <?=$styletd?>></td>
        <td <?=$styletd?>></td>   
        <td <?=$styletd?>></td>
        <td <?=$styletd?>></td>
        <td <?=$styletd?>></td>
        <td <?=$styletd?>></td>
        <!-- <td <?php //echo $styletd?>></td> -->       
        <td <?=$styletd?>>
           <?php echo '$ '.number_format($total, 0, '.', '.').' COP'?>
        </td> 
    </tr>
</table>
<br>
<h2 style="text-align: center;font-size: 16px;">Dispositivos Variable</h2>
<table style="width: 100%;border-collapse: collapse;">
    <thead>
        <tr>
            <th <?=$styleth.'"'?>>Tipo Alarma </th>
            <th <?=$styleth.'"'?>>Sistema</th>
            <th <?=$styleth.'"'?>>Servicio</th>
            <th <?=$styleth.'"'?>>Marca</th>
            <th <?=$styleth.'"'?>>Descripcion</th>
            <th <?=$styleth.'"'?>>Referencia</th>
            <th <?=$styleth.'"'?>>Ubicacion</th>
            <th <?=$styleth.'"'?>>#Zona Panel</th>
            <th <?=$styleth.'"'?>>Fecha Inicio</th>
            <th <?=$styleth.'"'?>>Fecha Final</th>
            <th <?=$styleth.'"'?>>Total Dias</th>
            <!-- <th <?php //echo $styleth.'"'?>>Empresa</th> -->
            <th <?=$styleth.'"'?>>$/Valor Novedad</th>
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
        <td <?=$styletd?>><?= $rows_variable->tipoalarma->nombre ?></td>
        <td <?=$styletd?>><?= $rows_variable->sistema ?></td>
        <td <?=$styletd?>><?= $rows_variable->servicios->nombre ?></td>
        <td <?=$styletd?>><?= $rows_variable->marcaalarma->nombre ?></td>
        <td <?=$styletd?>><?= $rows_variable->desc->descripcion?></td>
        <td <?=$styletd?>>
        <?= $rows_variable->referencia
        ?>
        </td>
        <td <?=$styletd?>>
        <?= $rows_variable->areas->nombre ?>
        </td>
        <td <?=$styletd?>><?=$rows_variable->zona_panel?></td>
        <td <?=$styletd?>><?=$rows_variable->fecha_inicio?></td>
        <td <?=$styletd?>><?=$rows_variable->fecha_fin?></td>
        <td <?=$styletd?>><?=$rows_variable->total_dias?></td>
        <!-- <td <?php //echo $styletd?>><?php //echo $rows_variable->fkEmpresa->nombre?></td> -->
        <td <?=$styletd?>>
        <?php
           //$total_variable=$total_variable+$modelo->number_unformat($rows_variable->valor_novedad);
          echo $rows_variable->valor_novedad;
        ?>
            
        </td>
       
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

            if ($rows_variable->servicios->nombre!='Fallo' && $rows_variable->servicios->nombre!='Terminación') {
                $total_variable=$total_variable+$modelo->number_unformat($rows_variable->valor_novedad);
            }else{
                $total_variable=$total_variable-$modelo->number_unformat($rows_variable->valor_novedad);
            }


            
        ?>
        

    </tr>
    <?php 
    } ?>
    <tr>           
    <td <?=$styletd?>></td>
    <td <?=$styletd?>></td>
    <td <?=$styletd?>></td>
    <td <?=$styletd?>></td>
    <td <?=$styletd?>></td>
    <td <?=$styletd?>></td>
    <td <?=$styletd?>></td>
    <td <?=$styletd?>></td>   
    <td <?=$styletd?>></td>
    <td <?=$styletd?>></td>
    <td <?=$styletd?>></td>
    <!-- <td <?php //echo $styletd?>></td>    -->    
    <td <?=$styletd?>>
       <?php echo '$ '.number_format($total_variable, 0, '.', '.').' COP'?>
    </td> 
</tr>
</table>
<br>
<table style="width: 100%;border-collapse: collapse;">
     <tr>
        <td <?=$styleth.'"'?>><b>Total equipos inalámbricos</b></td>
        <td <?=$styleth.'"'?>><?=  $total_inalambricos?></td>
        <td <?=$styleth.'"'?>><b>Total alarmas de Incendio</b></td>
        <td <?=$styleth.'"'?>><?= $total_incendio ?></td>
    </tr>

    <tr>
        <td <?=$styleth.'"'?>><b>Total equipos alambrados</b></td>
        <td <?=$styleth.'"'?>><?=  $total_alambrados?></td>
        <td <?=$styleth.'"'?>><b>Total alarmas de Intrusión</b></td>
        <td <?=$styleth.'"'?>><?= $total_intrusion ?></td>
    </tr>


    <tr>
        <td <?=$styleth.'"'?>><b>SubTotal Valor Mensual Equipos en Arriendo/Comodato "Incendio"</b></td>
        <td <?=$styleth.'"'?>><?php echo '$ '.number_format($subtotal_incendio, 0, '.', '.').' COP'?></td>

         <td <?=$styleth.'"'?>><b>SubTotal Valor Mensual Equipos en Arriendo/Comodato "Intrusión"</b></td>
        <td <?=$styleth.'"'?>><?php echo '$ '.number_format($subtotal_intrusion, 0, '.', '.').' COP'?></td>
       
    </tr>
    
    

    <tr>
        <td <?=$styleth.'"'?>><b>SubTotal Valor Mensual Monitoreo de Alarmas</b></td>
        <td <?=$styleth.'"'?>>
            <?php  echo '$ '.number_format($total_monitoreo, 0, '.', '.').' COP'; ?>
            
        </td>

        <td <?=$styleth.'"'?>><b>SubTotal Valor Mensual Equipos en Arriendo/Comodato</b></td>
        <td <?=$styleth.'"'?>>
            <?php 
                
                echo '$ '.number_format($total, 0, '.', '.').' COP';
            ?>
            
        </td>

    </tr>

    <tr>
        
    <td <?=$styleth.'"'?>></td>
    <td <?=$styleth.'"'?>></td>
    <td <?=$styleth.'"'?>></td>
    <td <?=$styleth.'"'?>></td>

    </tr>    


    <tr>
        <td <?=$styleth.'"'?>><b>Total servicios  Fallo </b></td>
        <td <?=$styleth.'"'?>>
            <?php 
                
                echo $total_serv_fallo;
            ?>
            
        </td>

        <td <?=$styleth.'"'?>><b>Total servicios Fijo proporcional </b></td>
        <td <?=$styleth.'"'?>>
            <?php 
                
                echo $total_serv_fijo_promo;
            ?>
            
        </td>

    </tr>


    <tr>
        <td <?=$styleth.'"'?>><b>Total servicios Terminación del servicio </b></td>
        <td <?=$styleth.'"'?>>
            <?php 
                
                echo $total_serv_terminacion;
            ?>
            
        </td>

        <td <?=$styleth.'"'?>><b>Total servicios </b></td>
        <td <?=$styleth.'"'?>>
            <?php 
                $total_servicios=($total_serv_fallo+$total_serv_fijo_promo+$total_serv_terminacion);
                echo $total_servicios;
            ?>
            
        </td>

    </tr>

    <tr>
        <td <?=$styleth.'"'?>><b>SubTotal Valor Mensual Fallo</b></td>
        <td <?=$styleth.'"'?>>
            <?php 
                
               echo '$ '.number_format($subtotal_serv_fallo, 0, '.', '.').' COP';
            ?>
            
        </td>

        <td <?=$styleth.'"'?>><b>SubTotal Valor Mensual Fijo proporcional </b></td>
        <td <?=$styleth.'"'?>>
            <?php 
               
               echo '$ '.number_format($subtotal_serv_fijo_promo, 0, '.', '.').' COP';
            ?>
            
        </td>

    </tr>

    <tr>
        <td <?=$styleth.'"'?>><b>SubTotal Valor Mensual Terminación del servicio</b></td>
        <td <?=$styleth.'"'?>>
            <?php 
                
               echo '$ '.number_format($subtotal_terminacion, 0, '.', '.').' COP';
            ?>
            
        </td>

        <td <?=$styleth.'"'?>><b>Total Valor Dispositivo Fijo y Variable  </b></td>
        <td <?=$styleth.'"'?>>
            <?php 
               
                $total_variable_fijo=($total+$total_variable)+$total_monitoreo;

               echo '$ '.number_format($total_variable_fijo, 0, '.', '.').' COP';
            ?>
            
        </td>

    </tr>
</table>
<br><br>
<br>
<table style="width: 100%;border:0px;">
    <tr>
        <td style="text-align: center;border:0px;width: 50%;font-size: 13px;">
            <hr style="width: 90%;">
            <b>VBO. ADMINISTRADOR</b>
        </td>
        <td style="text-align: center;border:0px;width: 10%;font-size: 13px;"></td>
        <td style="text-align: center;border:0px;width: 50%;font-size: 13px;">
            <hr style="width: 90%;">
            <b>VBO. SEGURIDAD DEPENDENCIA</b>
        </td>
    </tr>
    <tr>
        <td style="text-align: center;border:0px;width: 50%;font-size: 13px;">
            <br><br>
            <hr style="width: 90%;">
            <b>VBO. EMPRESA SEGURIDAD</b>
        </td>

        <td style="text-align: center;border:0px;width: 50%;font-size: 13px;">
            <br><br>
            <hr style="width: 90%;">
            <b>VBO. REGIONAL SEGURIDAD</b>
        </td>

        <td style="text-align: center;border:0px;width: 50%;font-size: 13px;">
            <br><br>
            <hr style="width: 90%;">
            <b>VBO. REGIONAL RIESGOS</b>
        </td>
    </tr>
</table>