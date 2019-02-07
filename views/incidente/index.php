<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Investigaciones';
$permisos = array();

if( isset(Yii::$app->session['permisos-exito']) ){

  $permisos = Yii::$app->session['permisos-exito'];

}




?>

 <br>


 <div class="page-header">
      <h1><small><i class="fa fa-search fa-fw"></i></small> <?= Html::encode($this->title) ?></h1>
    </div>


<a href="<?php echo Url::toRoute('incidente/create')?>" class="btn btn-primary">
    <i class="fa fa-plus"></i> Crear Investigacion
</a>

<br><br>


 <div class="table-responsive">
 	<div class="col-md-12">
 		<table class="table table-striped my-data" data-page-length='30'>
 			<thead>
 				<tr>
                    <th></th>
 					<th>Fecha Evento</th>
 					<th>Nombre Investigacion</th>
 					<th>Usuario</th>
 					<th>Dependencia</th>
                    <th>Regional</th>
 					<th>Estado</th>
                    <th>Area encargada</th>
 					<th></th>

 				</tr>
 			</thead>
 			<tbody>
 				<?php

 				foreach($investigaciones as $row ):
 				?>

 				<tr>
                    <td>
                        <?php //if($row->estado=='abierto'): ?>

                        <a  href="<?php echo Url::toRoute('incidente/view?id='.$row->id)?>" class="btn btn-primary btn-xs"
                            data-toggle="tooltip" data-placement="bottom" title="Ver Novedades"
                        >
                            <i class="fa fa-folder-open"></i> Abrir 
                        </a>

                        <a href="<?php echo Url::toRoute('incidente/imprimir?id='.$row->id)?>" class="btn btn-danger btn-xs" 
                        data-toggle="tooltip" data-placement="bottom" title="Descargar PDF"
                        >
					    <i class="fas fa-file-pdf"></i> PDF
						</a>

                        <?php //endif; ?>
                    </td>
 					<td><?= $row->fecha ?></td>
 					<td><?= $row->titulo ?></td>
 					<td><?= $row->usuario?></td>
 					<td><?= $row->dependencia->nombre ?></td>
                    <td><?= $row->dependencia->ciudad->zona->zona->nombre ?></td>
 					<td><?= $row->estado ?></td>
                    <td>
                        <?php 
                            switch ($row->area_encargada) {
                                case 'R':
                                    echo 'Recursos Humanos'; 
                                    break;

                                case 'J':
                                    echo 'judicializacion'; 
                                    break;

                                case 'S':
                                    echo 'Sin asignar'; 
                                    break;
                                
                                default:
                                    '';
                                    break;
                            }
                            
                        ?>
                            
                    </td>
 					<td>
                        <?php
                        if(in_array("administrador", $permisos) or in_array("eliminar-investigacion", $permisos)){
                        ?>
 						<?= Html::a('<i class="fa fa-trash"></i>',Yii::$app->request->baseUrl.'/incidente/delete?id='.$row->id,['class'=>'btn btn-primary btn-xs','data-confirm' => 'Seguro desea eliminar','title'=>'Eliminar']) ?>
                        <?php }?>
 					</td>
 					
 				</tr>
 				<?php
 				endforeach;
 				?>

 			</tbody>
 		</table>
 	</div>
 	
 </div>
 <script type="text/javascript">

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

 	
 	
 </script>