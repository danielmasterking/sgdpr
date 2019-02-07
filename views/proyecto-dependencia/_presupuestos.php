<?php 
use yii\helpers\Url;

$total_iva=0;
$total_iva_seguridad=0;
$total_iva_riesgo=0;
$total_iva_activo=0;
$total_iva_gasto=0;
?>
<h1>Presupuestos</h1>

<button onclick="tableToExcel('testTable', 'W3C Example Table')" class="btn btn-primary">
    <i class="fas fa-file-excel"></i> Exportar
</button>

<button class="btn btn-primary" data-toggle="modal" data-target="#ModalPresupuesto" id="ag_presupuesto">
    <i class="fas fa-dollar-sign"></i> Agregar presupuesto
</button>

<button class="btn btn-primary" data-toggle="modal" data-target="#ModalPresupuesto" id="update_presupuesto">
    <i class="fas fa-sync-alt"></i> Re agregar presupuesto
</button>
<?php 
if(in_array("coordinador", $permisos) || in_array("administrador", $permisos)){?>
    
    <button style="display:<?php echo $model->estado=='ABIERTO'?'block':'none'; ?>;" id="bloquear_presupuesto" type="button" class="btn btn-danger lock" onclick="cambiarEstadoPresupuesto('CERRADO')">
        <i class="fa fa-unlock"></i>
        BLOQUEAR PRESUPUESTO
    </button>

    <button style="display:<?php echo $model->estado=='CERRADO'?'block':'none'; ?>;"  id="desbloquear_presupuesto" type="button" class="btn btn-danger lock" onclick="cambiarEstadoPresupuesto('ABIERTO')">
        <i class="fa fa-lock"></i>
        DESBLOQUEAR PRESUPUESTO
    </button>
<?php } ?>


<div id="info_bloquear_presupuesto"></div>
<div id="info_cerrar_presupuesto"></div>
<div id="info_procesar"></div>


    <div class="col-md-12">
        <table class="table table-striped" id="testTable">
            <th>
                <?php 
                    if(in_array("revision-financiera", $permisos) || in_array("administrador", $permisos)){?>
                    <td style="width: 25%;font-weight: bold;">Presupuesto</td>
                    <td style="width: 25%;font-weight: bold;">Total Sin IVA</td>
                    <!-- <td style="width: 25%;font-weight: bold;">Total Con IVA</td>     -->
                <?php } ?>
                
                 
                <?php 
                    if(in_array("revision-financiera", $permisos) || in_array("administrador", $permisos)){?>
                    <td style="width: 25%;font-weight: bold;">Total Con IVA</td>    
                <?php } ?>
                <td style="width: 25%;font-weight: bold;">Saldo</td>
                <?php if(in_array("presupuesto_ver_metros_cuadrados", $permisos)):?>

                <td style="width: 25%;font-weight: bold;">Total Metros2</td>

                <?php endif;?>

                <td style="width: 12%;"></td>
            </th>
            <!--<tr>
                <td><b>Total</b></td>
                <?php 
                $total_iva=(($model->suma_total*$model->iva)/100)+$model->suma_total;
                if(in_array("revision-financiera", $permisos) || in_array("administrador", $permisos)){
                    ?>
                    <td><?='$ '.number_format($model->presupuesto_total, 0, '.', '.').' COP'?></td>
                    <td id="suma_total"><?='$ '.number_format($model->suma_total, 0, '.', '.').' COP'?></td>
                    <td id="saldo_total">
                    <?php 
                    if(($model->presupuesto_total-$total_iva)<0){
                        echo '<b style="color:red;">$ '.number_format(($model->presupuesto_total-$total_iva), 0, '.', '.').'</b> COP';
                    }else{
                        echo '<b style="color:green;">$ '.number_format(($model->presupuesto_total-$total_iva), 0, '.', '.').'</b> COP';
                    }
                    ?>  
                    </td>
                <?php } ?>
                <td id="total_iva">

                    <?php
                    
                    echo '$ '.number_format($total_iva, 0, '.', '.').' COP'?>
                </td>

                <?php if(in_array("presupuesto_ver_metros_cuadrados", $permisos)):?>

                <td id='total_m2'>
                    <?php 
                        if ($model->metros_cuadrados!='') {
                            $total_m2=($total_iva/$model->metros_cuadrados);
                            echo '$ '.number_format($total_m2, 0, '.', '.').' COP';    
                        }
                        

                    ?>
                </td>

                <?php endif;?>

                <td id="money-total">
                    <?php 
                    if($model->presupuesto_total<$total_iva){
                        echo '<i class="fa fa-money fa-2x" style="color:red;"></i>';
                    }else{
                        echo '<i class="fa fa-money" style="color:green;"></i>';
                    }
                    ?>
                </td>
            </tr>-->
            <tr>
                <td><b>Seguridad</b></td>
                <?php 
                $total_iva_seguridad=(($model->suma_seguridad*$model->iva)/100)+$model->suma_seguridad;
                if(in_array("revision-financiera", $permisos) || in_array("administrador", $permisos)){
                    
                    ?>
                    <td><?='$ '.number_format($model->presupuesto_seguridad, 0, '.', '.').' COP'?></td>
                    <td id="suma_seguridad"><?='$ '.number_format($model->suma_seguridad, 0, '.', '.').' COP'?></td>
                    
                <?php } ?>
                

               
                
                <td id="total_iva_seguridad">
                    <?php
                    echo '$ '.number_format($total_iva_seguridad, 0, '.', '.').' COP'?>
                </td>

                <?php if(in_array("revision-financiera", $permisos) || in_array("administrador", $permisos)){ ?>
                <td id="saldo_seguridad">
                    <?php 
                    if(($model->presupuesto_seguridad-$total_iva_seguridad)<0){
                        echo '<b >$ '.number_format(($model->presupuesto_seguridad-$total_iva_seguridad), 0, '.', '.').'</b> COP';
                    }else{
                        echo '<b >$ '.number_format(($model->presupuesto_seguridad-$total_iva_seguridad), 0, '.', '.').'</b> COP';
                    }
                    ?>  
                </td>
                <?php } ?>

                 <?php if(in_array("presupuesto_ver_metros_cuadrados", $permisos)):?>
                <td>
                    
                </td>
                <?php endif;?>
                <td id="money-seguridad">
                    <?php 
                    if($model->presupuesto_seguridad<$total_iva_seguridad){
                       // echo '<i class="fa fa-money fa-2x" style="color:red;"></i>';
                    }else{
                        //echo '<i class="fa fa-money" style="color:green;"></i>';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td><b>Riesgo</b></td>
                <?php 
                $total_iva_riesgo=(($model->suma_riesgo*$model->iva)/100)+$model->suma_riesgo;
                if(in_array("revision-financiera", $permisos) || in_array("administrador", $permisos)){
                    
                    ?>
                    <td><?='$ '.number_format($model->presupuesto_riesgo, 0, '.', '.').' COP'?></td>
                    <td id="suma_riesgo"><?='$ '.number_format($model->suma_riesgo, 0, '.', '.').' COP'?></td>
                    
                <?php } ?>
                

                
                <td id="total_iva_riesgo">

                    <?php
                    echo '$ '.number_format($total_iva_riesgo, 0, '.', '.').' COP'?>
                </td>
                <?php  if(in_array("revision-financiera", $permisos) || in_array("administrador", $permisos)){ ?>
                <td id="saldo_riesgo">
                    <?php 
                    if(($model->presupuesto_riesgo-$total_iva_riesgo)<0){
                        echo '<b >$ '.number_format(($model->presupuesto_riesgo-$total_iva_riesgo), 0, '.', '.').'</b> COP';
                    }else{
                        echo '<b >$ '.number_format(($model->presupuesto_riesgo-$total_iva_riesgo), 0, '.', '.').'</b> COP';
                    }
                    ?>  
                </td>
            <?php } ?>
                <?php if(in_array("presupuesto_ver_metros_cuadrados", $permisos)):?>
                <td>
                    

                </td>
            <?php endif;?>

                <td id="money-riesgo">
                    <?php 
                    if($model->presupuesto_riesgo<$total_iva_riesgo){
                        //echo '<i class="fa fa-money fa-2x" style="color:red;"></i>';
                    }else{
                        //echo '<i class="fa fa-money" style="color:green;"></i>';
                    }
                    ?>
                </td>
            </tr>
            <!--<tr>
                <th></th>
                <th>Preestablecido para...</th>
                <th>Neto</th>
                <th>Total Con IVA</th>
                <th>Saldo</th>
                <th></th>
            </tr>-->
            <tr>
                <td><b>Activo</b></td>
                <?php 
                $total_iva_activo=(($model->suma_activo*$model->iva)/100)+$model->suma_activo;
                if(in_array("revision-financiera", $permisos) || in_array("administrador", $permisos)){
                    ?>
                    <td><?='$ '.number_format($model->presupuesto_activo, 0, '.', '.').' COP'?></td>
                    <td id="suma_activo"><?='$ '.number_format($model->suma_activo, 0, '.', '.').' COP'?></td>
                    
                <?php } ?>
                

                <td id="total_iva_activo">
                    <?php
                    echo '$ '.number_format($total_iva_activo, 0, '.', '.').' COP'?>
                </td>

                 <?php  if(in_array("revision-financiera", $permisos) || in_array("administrador", $permisos)){ ?>
                <td id="saldo_activo">
                    <?php 
                     $saldoActivo=($model->presupuesto_activo-$total_iva_activo);
                    if(($model->presupuesto_activo-$total_iva_activo)<0){
                       

                        echo '<b style="color:red;">$ '.number_format($saldoActivo, 0, '.', '.').'</b> COP';
                    }else{
                        echo '<b style="color:green;">$ '.number_format($saldoActivo, 0, '.', '.').'</b> COP';
                    }
                    ?>  
                </td>
            <?php } ?>

                <?php if(in_array("presupuesto_ver_metros_cuadrados", $permisos)):?>
                <td>
                    
                </td>
            <?php endif;?>

                <td id="money-activo">
                    <?php 
                    
                    //if($model->presupuesto_activo<$total_iva_activo){//ANTES
                    if($model->presupuesto_activo<$model->suma_activo){//DESPUES
                        echo '<i class="fas fa-money-bill-alt fa-2x" style="color:red;"></i>';
                    }else{
                        echo '<i class="fas fa-money-bill-alt fa-2x" style="color:green;"></i>';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td><b>Gasto</b></td>
                <?php 
                $total_iva_gasto=(($model->suma_gasto*$model->iva)/100)+$model->suma_gasto;
                if(in_array("revision-financiera", $permisos) || in_array("administrador", $permisos)){
                    ?>
                    <td><?='$ '.number_format($model->presupuesto_gasto, 0, '.', '.').' COP'?></td>
                    <td id="suma_gasto"><?='$ '.number_format($model->suma_gasto, 0, '.', '.').' COP'?></td>
                   
                <?php } ?>
                 

                <td id="total_iva_gasto">
                    <?php
                    //echo '$ '.number_format($total_iva_gasto, 0, '.', '.').' COP'?>
                </td>

                <?php if(in_array("revision-financiera", $permisos) || in_array("administrador", $permisos)){ ?>
                 <td id="saldo_gasto">
                    <?php 
                    $saldoGasto=($model->presupuesto_gasto-$model->suma_gasto);
                    //if(($model->presupuesto_gasto-$total_iva_gasto)<0){//ANTES
                    if(($model->presupuesto_gasto-$model->suma_gasto)<0){//DESPUES
                       // echo '<b style="color:red;">$ '.number_format(($model->presupuesto_gasto-$total_iva_gasto), 0, '.', '.').'</b> COP';
                        
                         echo '<b style="color:red;">$ '.number_format($saldoGasto, 0, '.', '.').'</b> COP';
                    }else{
                       // echo '<b style="color:green;">$ '.number_format(($model->presupuesto_gasto-$total_iva_gasto), 0, '.', '.').'</b> COP';
                         echo '<b style="color:green;">$ '.number_format($saldoGasto, 0, '.', '.').'</b> COP';
                    }
                    ?>  
                    </td>
                <?php } ?>

                <?php if(in_array("presupuesto_ver_metros_cuadrados", $permisos)):?>
                <td>
                    
                </td>
                <?php endif;?>

                <td id="money-gasto">
                    <?php 
                    //if($model->presupuesto_gasto<$total_iva_gasto){//ANTES
                    if($model->presupuesto_gasto<$model->suma_gasto){//DESPUES
                        echo '<i class="fas fa-money-bill-alt fa-2x" style="color:red;"></i>';
                    }else{
                        echo '<i class="fas fa-money-bill-alt fa-2x" style="color:green;"></i>';
                    }
                    ?>
                </td>
            </tr>

             <tr>
                <td><b>Total</b></td>
                <?php 
                //$total_iva=(($model->suma_total*$model->iva)/100)+$model->suma_total;
                $total_iva=($model->suma_gasto+$total_iva_activo);
                if(in_array("revision-financiera", $permisos) || in_array("administrador", $permisos)){
                    ?>
                    <td><?='$ '.number_format($model->presupuesto_total, 0, '.', '.').' COP'?></td>
                    <td id="suma_total"><?='$ '.number_format($model->suma_total, 0, '.', '.').' COP'?></td>
                    
                <?php } ?>
                
                <td id="total_iva">

                    <?php
                    
                    echo '$ '.number_format($total_iva, 0, '.', '.').' COP'?>
                </td>

                <?php if(in_array("revision-financiera", $permisos) || in_array("administrador", $permisos)){ ?>
                    <td id="saldo_total">
                    <?php 
                    if(($model->presupuesto_total-$total_iva)<0){
                       // echo '<b style="color:red;">$ '.number_format(($model->presupuesto_total-$total_iva), 0, '.', '.').'</b> COP';
                        echo '<b style="color:red;">$ '.number_format(($saldoActivo+$saldoGasto), 0, '.', '.').'</b> COP';
                    }else{
                        echo '<b style="color:green;">$ '.number_format(($saldoActivo+$saldoGasto), 0, '.', '.').'</b> COP';
                    }
                    ?>  
                    </td>
                <?php } ?>

                 <?php if(in_array("presupuesto_ver_metros_cuadrados", $permisos)):?>

                <td id='total_m2'>
                    <?php 
                        if ($model->metros_cuadrados!='') {
                            $total_m2=($total_iva/$model->metros_cuadrados);
                            echo '$ '.number_format($total_m2, 0, '.', '.').' COP';    
                        }
                        

                    ?>
                </td>

                <?php endif;?>

                <td id="money-total">
                    <?php 
                    if($model->presupuesto_total<$total_iva){
                        echo '<i class="fas fa-money-bill-alt fa-2x" style="color:red;"></i>';
                    }else{
                        echo '<i class="fas fa-money-bill-alt fa-2x" style="color:green;"></i>';
                    }
                    ?>
                </td>
            </tr>
        </table>
    </div>
 <?php 
if(in_array("coordinador", $permisos) || in_array("administrador", $permisos)){?>
    
    <button id="cerrar_presupuesto" type="submit" class="btn btn-danger lock" onclick="cerrarPresupuesto()" disabled="">
        <i class="fa fa-check"></i>
        ENVIAR A PEDIDOS
    </button>
<?php } ?>

<button id="cr_pedido" class="btn btn-primary" data-toggle="modal" data-target="#Modalpedido" style="display:<?php echo $model->estado=='ABIERTO'?'block':'none'; ?>;">
    <i class="fa fa-plus"></i> Pedir Normal
</button>

<button id="cr_pedido_especial" class="btn btn-primary" data-toggle="modal" data-target="#Modalpedidoespecial" style="display:<?php echo $model->estado=='ABIERTO'?'block':'none'; ?>;">
    <i class="fa fa-plus"></i> Pedir Especial
</button>
   
<?php 
if(in_array("revision-financiera", $permisos) || in_array("administrador", $permisos) || in_array("coordinador", $permisos)){?>
    
    <button id="procesar" type="submit" class="btn btn-primary lock" onclick="procesar()">
        Procesar Saldo Pedidos
    </button>
<?php } ?>
    <input type="hidden" id="total_iva" value="<?php echo $total_iva?>">
    <br><br>