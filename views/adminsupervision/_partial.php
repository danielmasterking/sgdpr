<?php 
use yii\helpers\Url;


    $permisos = array();
    if( isset(Yii::$app->session['permisos-exito']) ){
        $permisos = Yii::$app->session['permisos-exito'];
    }

?>
<div class="col-md-12">
<div class="table-responsive">
<table class="table ">
    <thead>
        <tr>
            <th></th>
            <th>Id</th>
            <th>Fecha Factura</th>
            <th>Numero Factura</th>
            <th>Fecha Creado</th>
            <th>Ftes </th>
            <th>Total</th>
            <th>Mes</th>
            <th>Año</th>
            <th>Usuario</th>
            <th>Empresa</th>
            
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php 

        foreach($query as $row):
        ?>
        <tr>
            <td>
                <?php if($row['estado']=='abierto'){?>

                <a href="<?php echo Url::toRoute('adminsupervision/view?id='.$row['id'])?>" class="btn btn-primary btn-xs">
                        <i class="fas fa-folder-open"></i> Abrir
                    </a>
                <?php }?>
                <a href="<?php echo Url::toRoute('adminsupervision/imprimir?id='.$row['id'])?>" class="btn btn-danger btn-xs" >
                    <i class="far fa-file-pdf"></i> PDF
                </a>

                <?php if($row['estado']=='cerrado'){?>

                
                <?php if (in_array("administrador", $permisos) or in_array("habilitar-prefactura", $permisos)): ?>
                    <a data-confirm='Seguro desea habilitar esta prefactura' href="<?php echo Url::toRoute('adminsupervision/abrir_pref?id='.$row['id'])?>" class="btn btn-success btn-xs" target="_blank">
                        <i class="fas fa-thumbs-up"></i> Habilitar
                    </a>
                <?php endif ?>
                 <?php }?>

            </td>
            <td><?= $row['id'] ?></td>
            <td><?= $row['fecha_factura'] ?></td>
            <td><?= $row['numero_factura'] ?></td>
            <td><?= $row['created'] ?></td>
            <td><?= $row['ftes_totales'] ?></td>
            <td> <?='$ '.number_format($row['total_factura'], 0, '.', '.').' COP'?>  </td>
            <td><?= $row['mes'] ?></td>
            <td><?= $row['ano'] ?></td>
            <td><?= $row['usuario'] ?></td>
            <td><?= $row['empresa'] ?></td>
            
            <td>
                <?php 
                    if(in_array("administrador", $permisos) or in_array("eliminar_prefactura", $permisos) ){
                                echo '<button type="button" class="btn btn-primary btn-xs" onclick="eliminar('.$row['id'].')">
                                 <i class="fa fa-trash" aria-hidden="true"></i>
                                 </button>';
                    }elseif($row['estado']=='abierto' and $row['usuario']==Yii::$app->session['usuario-exito'] and !in_array("prefactura-regional", $permisos)){
                        echo '<button type="button" class="btn btn-primary btn-xs" onclick="eliminar('.$row['id'].')">
                         <i class="fa fa-trash" aria-hidden="true"></i>
                         </button>';
                    }


                ?>
            </td>



        </tr>

        <?php endforeach; ?>
    </tbody>

</table>
    </div>
</div>