<?php
use yii\helpers\Url;
$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
$styletd='style="padding: 5px;text-align: center;font-size: 9px;border: 1px solid black;"';
$styletd2='style="padding: 5px;font-size: 10px;border: 1px solid black;"';
$styleth='style="padding: 5px;text-align: center;font-size: 9px;border: 1px solid black;';
date_default_timezone_set ( 'America/Bogota');

?>


<h3 style="text-align: center;font-size: 16px;">ADMINISTRACION Y SUPERVICION MES DE <?=strtoupper ($meses[$query->mes-1]);?> de <?=$query->ano?></h3>


<table style="width: 100%;border-collapse: collapse;">
    <tr>
        <td <?=$styletd2?> ><b>MES DE FACTURACION:</b></td>
        <td <?=$styletd2?> ><?=strtoupper ($meses[$query->mes-1]);?></td>
        <td <?=$styletd2?> ><b>AÑO DE FACTURACION:</b></td>
        <td <?=$styletd2?> ><?=strtoupper ($query->ano);?></td>
        <td rowspan="3" style="border: 1px solid black;">
            <img src="<?php echo $query->empresa_seg->logo?>" width="200" width="110"/>
        </td> 
    </tr>
    <tr>
        <td <?=$styletd2?> ><b>EMPRESA DE SEGURIDAD:</b></td>
        <td <?=$styletd2?> ><?=$query->empresa_seg->nombre?></td>

        <td <?=$styletd2?> ><b>NIT:</b></td>
        <td <?=$styletd2?> ><?=$query->empresa_seg->nit?></td>
    </tr> 
    

    <!-- <tr>
       

        <td <?=$styletd2?> ><b>DIAS DE SERVICIO:</b></td>
        <td <?=$styletd2?> ><?=strtoupper ($query->dias);?></td>
        
    </tr>

    <tr>
        <td <?=$styletd2?> ><b>FECHA DESDE :</b></td>
        <td <?=$styletd2?> ><?=strtoupper ($query->fecha_desde);?></td>

        <td <?=$styletd2?> ><b>FECHA HASTA:</b></td>
        <td <?=$styletd2?> ><?=strtoupper ($query->fecha_hasta);?></td>
        
    </tr> -->

    
    <tr>
        <td <?=$styletd2?> ><b>NUMERO FACTURA:</b></td>
        <td <?=$styletd2?> ><?= $query->numero_factura ?></td>

        <td <?=$styletd2?> ><b>FECHA FACTURA:</b></td>
        <td <?=$styletd2?> ><?= $query->fecha_factura ?></td>
        
    </tr>

   
    
</table>
<br>


<!-- <h2 style="text-align: center;font-size: 16px;">DIAS PRESTACION DEL SERVICIO</h2> -->

    <!-- <table style="width: 100%;border-collapse: collapse;">
      <thead>
        <tr>
          <th <?=$styleth.'"'?>>Lunes</th>
          <th <?=$styleth.'"'?>>Martes</th>
          <th <?=$styleth.'"'?>>Miercoles</th>
          <th <?=$styleth.'"'?>>Jueves</th>
          <th <?=$styleth.'"'?>>Viernes</th>
          <th <?=$styleth.'"'?>>Sabado</th>
          <th <?=$styleth.'"'?>>Domingo</th>
          <th <?=$styleth.'"'?>>Festivo</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td <?=$styletd?>><?= $query->lunes?></td>
          <td <?=$styletd?>><?= $query->martes?></td>
          <td <?=$styletd?>><?= $query->miercoles?></td>
          <td <?=$styletd?>><?= $query->jueves?></td>
          <td <?=$styletd?>><?= $query->viernes?></td>
          <td <?=$styletd?>><?= $query->sabado?></td>
          <td <?=$styletd?>><?= $query->domingo?></td>
          <td <?=$styletd?>><?= $query->festivo?></td>
        </tr>
      </tbody>
    </table> -->
<h2 style="text-align: center;font-size: 16px;"> DISPOSITIVOS</h2>
<table style="width: 100%;border-collapse: collapse;">
        <thead>
          <tr>
            <th <?=$styleth.'"'?>>Descripcion</th>
            <th <?=$styleth.'"'?>>Horas</th>
            <!-- <th <?=$styleth.'"'?>>Horas Totales</th>
            <th <?=$styleth.'"'?>>Horas Dependencia</th> -->
            <th <?=$styleth.'"'?>>Hora Inicio</th>
            <th <?=$styleth.'"'?>>Hora Fin</th>
            <th <?=$styleth.'"'?>>Fts</th>
            <th <?=$styleth.'"'?>>Fts totales</th>
            <th <?=$styleth.'"'?>>Fts dependencia</th>
            <th <?=$styleth.'"'?>>Lunes</th>
            <th <?=$styleth.'"'?>>Martes</th>
            <th <?=$styleth.'"'?>>Miercoles</th>
            <th <?=$styleth.'"'?>>Jueves</th>
            <th <?=$styleth.'"'?>>Viernes</th>
            <th <?=$styleth.'"'?>>Sabado</th>
            <th <?=$styleth.'"'?>>Domingo</th>
            <th <?=$styleth.'"'?>>Festivo</th>
            <th <?=$styleth.'"'?>>Cantidad</th>
            <th <?=$styleth.'"'?>>Precio unitario</th>
            <th <?=$styleth.'"'?>>Precio Total</th>
            <th <?=$styleth.'"'?>>Precio Dependencia</th>
            <th <?=$styleth.'"'?>>Detalle</th>
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
          <td <?=$styletd?>><?= $ad->descripcion ?></td>
          <td <?=$styletd?>><?= $ad->horas ?></td>
          <td <?=$styletd?>><?= $ad->hora_inicio ?></td>
          <td <?=$styletd?>><?= $ad->hora_fin ?></td>
          <!-- <td <?=$styletd?>>
            <?php 
             //$horas_cantidad=$ad->horas*$ad->cantidad;

             //echo $horas_cantidad;
            ?>  
          </td> -->
          <!-- <td <?=$styletd?>>
            <?php 
            
              //$horas_dependencia=$horas_cantidad/$count_dep;
              
              //echo round($horas_dependencia,3);
            ?>
            
          </td> -->
          <td <?=$styletd?>><?= $ad->ftes ?></td>
          <td <?=$styletd?>>
            <?php 
              echo $ad->ftes * $ad->cantidad;
            ?>
          </td>
          <td <?=$styletd?>>
            <?php

              //$ftes_totales=$ad->ftes_dependencia*$ad->cantidad;
              //echo $ftes_totales;

              $ftes_totales=$ad->ftes_dependencia;
              echo $ftes_totales;
            ?>
          </td>
          <td <?=$styletd?>><?= $ad->lunes ?></td>
          <td <?=$styletd?>><?= $ad->martes ?></td>
          <td <?=$styletd?>><?= $ad->miercoles ?></td>
          <td <?=$styletd?>><?= $ad->jueves ?></td>
          <td <?=$styletd?>><?= $ad->viernes ?></td>
          <td <?=$styletd?>><?= $ad->sabado ?></td>
          <td <?=$styletd?>><?= $ad->domingo ?></td>
          <td <?=$styletd?>><?= $ad->festivo ?></td>
          <td <?=$styletd?>><?= $ad->cantidad ?></td>
          <td <?=$styletd?>>
            <?='$ '.number_format($ad->precio_unitario, 0, '.', '.').' COP'?>    
          </td>
          <td <?=$styletd?>>
            <?='$ '.number_format($ad->precio_total==0?0:$ad->precio_total, 0, '.', '.').' COP'?>      
          </td>
          <td <?=$styletd?>>
            <?='$ '.number_format($ad->precio_dependencia==0?0:$ad->precio_dependencia, 0, '.', '.').' COP'?>      
          </td>
          <td <?=$styletd?>><?= $ad->detalle ?></td>
        </tr>
      <?php 
        $ftes_total=$ftes_total+$ftes_totales;
        $valor_total=$valor_total+$ad->precio_dependencia;
        $horas_total=$horas_total+$horas_cantidad;
        endforeach; 

        //$horas_dep=$horas_total/$count_dep;
      ?>
        <tr>
          <td colspan="2" <?=$styletd?>></td>
          <td <?=$styletd?>><b><?php //echo $horas_total ?></b></td>
          <td colspan="3" <?=$styletd?>></td>
          <td <?=$styletd?>><b><?= $ftes_total ?></b></td>
          <td colspan="11" <?=$styletd?>></td>
          <td <?=$styletd?>><b><?= '$ '.number_format($valor_total, 0, '.', '.').' COP' ?></b></td>
          <td <?=$styletd?>></td>
        </tr>

        </tbody>
      </table>

<h2 style="text-align: center;font-size: 16px;"> DEPENDENCIAS</h2>


<table style="width: 100%;border-collapse: collapse;">
    <thead>
        <tr>
            <th <?=$styleth.'"'?>></th>
            <th <?=$styleth.'"'?>>Dependencia</th>
            <th <?=$styleth.'"'?>>Regional</th>
            <th <?=$styleth.'"'?>>Ceco</th>
            <th <?=$styleth.'"'?>>Cuenta contable</th>
            <th <?=$styleth.'"'?>>Ciudad</th>
            <th <?=$styleth.'"'?>>Marca</th>
            <!-- <th <?=$styleth.'"'?>>Horas</th> -->
            <th <?=$styleth.'"'?>>ftes</th>
            <th <?=$styleth.'"'?>>Precio</th>
        </tr>
    </thead>
        <tbody>
            <?php 
                
                $count=1;
                foreach($dependencias as $row): 
            ?>
            <tr>
                <td <?=$styletd?>><?= $count ?></td>
                <td <?=$styletd?>><?= $row->dependencia->nombre ?></td>
                <td <?=$styletd?>>
                  <?php

                    $consulta=$modelDep->getzona($row->dependencia->codigo);

                    echo $consulta['nombre'];

                  ?>
                </td>
                <td <?=$styletd?>><?= $row->dependencia->ceco ?></td>
                <td <?=$styletd?>>
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
                <td <?=$styletd?>><?= $row->dependencia->ciudad->nombre?></td>
                <td <?=$styletd?>><?= $row->dependencia->marca->nombre?></td>
                <!-- <td <?=$styletd?>><?= $horas_dep?></td> -->
                <td <?=$styletd?>><?= $ftes_total?></td>
                <td <?=$styletd?>><?='$ '.number_format($valor_total, 0, '.', '.').' COP'?></td>
            </tr>
        <?php $count++; endforeach;?>
       
        </tbody>
</table>


<br><br><br>
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