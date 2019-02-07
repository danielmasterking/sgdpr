<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Investigaciones';
$permisos = array();

if( isset(Yii::$app->session['permisos-exito']) ){

  $permisos = Yii::$app->session['permisos-exito'];

}


Modal::begin([
    'id' => 'modal',
    'header' => '<h2>Adjuntos</h2>',
    //'toggleButton' => ['label' => 'Agregar Cotizacion','class'=>'btn btn-primary lock'],
    ]);


    echo"<div id='archivos'>".
        "</div>";


Modal::end();



?>




 <?= $this->render('_tabsDependencia',['codigo_dependencia' => $codigo_dependencia,'investigacion' => $investigacion]) ?>

 <br>
 <div class="table-responsive">
 	<div class="col-md-12">
 		<table class="table table-striped">
 			<thead>
 				<tr>
 					<th>Fecha</th>
 					<th>Detalle</th>
 					<th>Recomendaciones</th>
 					<th>Novedad</th>
 					<th>Adjuntos</th>
 				</tr>
 			</thead>
 			<tbody>
 				<?php

 				foreach($investigaciones as $row ):
 				?>

 				<tr>
 					<td><?= $row->fecha ?></td>
 					<td><?= $row->detalle ?></td>
 					<td><?= $row->recomendaciones ?></td>
 					<td><?= $row->novedad->nombre ?></td>
 					<td>
 						<?php 
 							$ruta = $row->imagen == null ? ' ' : $row->imagen;
        					$ruta = Yii::$app->request->baseUrl.$ruta;
 						?>
 						<!-- <img alt="imagen" class="img-responsive img-thumbnail" src="<?php //echo $ruta ?>" /> -->
 						
 						<a title='Click para ver archivos' data-toggle="modal" data-target="#modal" onclick="archivos(<?= $row->id ?>);">
					 		<i class="fa fa-file-archive-o" aria-hidden="true"></i>
						</a>


						
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
 	
 	 function archivos(id){

        $.ajax({
            url:"<?php echo Yii::$app->request->baseUrl . '/centro-costo/archivos_investigacion'; ?>",
            type:'POST',
            dataType:"json",
            cache:false,
            data: {
                id: id
            },
            beforeSend:  function() {
                $('#archivos').html('Cargando... <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>');
            },
            success: function(data){
               $('#archivos').html(data.respuesta);
            }
        });
    }
 </script>