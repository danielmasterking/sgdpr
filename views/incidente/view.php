<style type="text/css">
	.timeline{list-style:none;padding:0 0 20px;position:relative;margin-top:-15px}.timeline:before{top:30px;bottom:25px;position:absolute;content:" ";width:3px;background-color:#ccc;left:25px;margin-right:-1.5px}.timeline>li,.timeline>li>.timeline-panel{margin-bottom:5px;position:relative}.timeline>li:after,.timeline>li:before{content:" ";display:table}.timeline>li:after{clear:both}.timeline>li>.timeline-panel{margin-left:55px;float:left;top:19px;padding:4px 10px 8px 15px;border:1px solid #ccc;border-radius:5px;width:45%}.timeline>li>.timeline-badge{color:#fff;width:36px;height:36px;line-height:36px;font-size:1.2em;text-align:center;position:absolute;top:26px;left:9px;margin-right:-25px;background-color:#fff;z-index:100;border-radius:50%;border:1px solid #d4d4d4}.timeline>li.timeline-inverted>.timeline-panel{float:left}.timeline>li.timeline-inverted>.timeline-panel:before{border-right-width:0;border-left-width:15px;right:-15px;left:auto}.timeline>li.timeline-inverted>.timeline-panel:after{border-right-width:0;border-left-width:14px;right:-14px;left:auto}.timeline-badge.primary{background-color:#2e6da4!important}.timeline-badge.success{background-color:#3f903f!important}.timeline-badge.warning{background-color:#f0ad4e!important}.timeline-badge.danger{background-color:#d9534f!important}.timeline-badge.info{background-color:#5bc0de!important}.timeline-title{margin-top:0;color:inherit}.timeline-body>p,.timeline-body>ul{margin-bottom:0;margin-top:0}.timeline-body>p+p{margin-top:5px}.timeline-badge>.glyphicon{margin-right:0px;color:#fff}.timeline-body>h4{margin-bottom:0!important}

	.midiv {
       word-wrap: break-word; 
       max-width:400px; 
       width:400px;
    }

    img.mediana{
  		width: 300px; height: 200px;
	}
</style>
<?php 

use yii\helpers\Url;
use yii\helpers\Html;
use app\models\FotoNovedadIncidente;
use app\models\UsuarioIncidente;
use app\models\InvestigacionInfractor;

$this->title = 'Investigacion';

$permisos = array();

if( isset(Yii::$app->session['permisos-exito']) ){

    $permisos = Yii::$app->session['permisos-exito'];

}

?>

<?= Html::a('<i class="fa fa-arrow-left"></i>',Yii::$app->request->baseUrl.'/incidente',['class'=>'btn btn-primary']) ?>

<?php

if($model->estado=='abierto'){
?>
<?= Html::a('<i class="fa fa-plus"></i> Crear Novedad',Yii::$app->request->baseUrl.'/incidente/novedad_investigacion?id='.$model->id,['class'=>'btn btn-primary']) ?>

<?= Html::a('<i class="fa  fa-edit"></i> Editar',Yii::$app->request->baseUrl.'/incidente/update?id='.$model->id,['class'=>'btn btn-primary','title'=>'Editar']) ?>


<?= Html::button('<i class="fa  fa-lock"></i> Cerrar caso', ['class'=>'btn btn-danger','data-toggle'=>'modal','data-target'=>'#myModal']) ?>

<?php
}
?>

<?php

if($model->estado=='cerrado'){
	if(in_array("crear-investigacion", $permisos)){
?>
<?= Html::button('<i class="fa  fa-folder-open"></i> Abrir Investigacion', ['class'=>'btn btn-danger','onclick'=>'abrir_caso();']) ?>
<?php 
	}
}
?>


<h3 class="text-center"><i class="fa fa-folder-open"></i> <?= $this->title ?> - <?= $model->dependencia->nombre ?></h3>
<br>
<div class="row">
	<div class="col-md-8">
		<div class="row">
			<div class="col-md-4">
				<label>Nombre Investigacion : </label>
				<?= $model->titulo ?>
			</div>

			<div class="col-md-4">
				<label>Dependencia : </label>
				<?= $model->dependencia->nombre ?>
			</div>

		</div>
		<br>
		<div class="row">
			<div class="col-md-4">
				<label>Regional : </label>
				<?= $model->dependencia->ciudad->zona->zona->nombre ?>
			</div>

			<div class="col-md-4">
				<label>Ceco : </label>
				<?= $model->dependencia->ceco ?>
			</div>

			<!-- <div class="col-md-4">
				<img alt="imagen" class="img-responsive img-thumbnail mediana" src="<?= Yii::$app->request->baseUrl.$model->dependencia->foto ?>" />
			</div> -->
		</div>
		<br>
		<div class="row">
			<div class="col-md-4">
				<label>Fecha evento : </label>
				<?= $model->fecha ?>
			</div>

			<div class="col-md-4">
				<label>Fecha Inicio : </label>
				<?= $model->fecha_inicio ?>
			</div>

		</div>
		<br>
		<div class="row">
			<div class="col-md-4">
				<label>Tipo incidente : </label>
				<?= $model->novedad->nombre ?>
			</div>

			<div class="col-md-4">
				<label>Encargados : </label>
				<?php 
					$usuarios_encargados=UsuarioIncidente::find()->where('id_incidente='.$model->id)->all();

					foreach ($usuarios_encargados as $user) {
						
						echo $user->usuario." - ";
					}

				?>
			</div>

		</div>

		<br>
		<div class="row">
			<div class="col-md-4">
				<label>Infractores : </label>
				<?php 

					$infractores=InvestigacionInfractor::find()->where('incidente_id='.$model->id)->all();

					foreach ($infractores as $infrt) {
						echo $infrt->tipoInfractor->nombre." - ";
					}

				?>
			</div>

		</div>

		<br>
		<div class="row">
			<div class="col-md-8">
				<label>Detalle : </label>
				<?= $model->detalle ?>
			</div>

		</div>

		<?php

		if($model->estado=='cerrado'){
		?>
		<br>
		<div class="row">
			<div class="col-md-8">
				<label>Detalle Cierre : </label>
				<?= $model->detalle_cierre ?>
			</div>

		</div>
	
		<?php
		}
		?>
	</div>


	<div class="col-md-4">
		<img alt="imagen" class="img-responsive img-thumbnail mediana" src="<?= Yii::$app->request->baseUrl.$model->dependencia->foto ?>" />
	</div>
</div>



<h3 class="text-center"><i class="fa fa-search"></i> Novedades</h3>

<div class="row">
	<div class="col-md-12">
		<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th style="text-align: center;">Tipo Novedad</th>
					<th style="text-align: center;">Fecha Evento</th>
					<th style="text-align: center;">Usuario</th>
					<th style="text-align: center;">Adjuntos</th>
					<th style="text-align: center;">Descripcion</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					foreach($novedades as $row): 
						$fotos=FotoNovedadIncidente::find()->where('id_novedad='.$row->id)->all();
				?>
					<tr>
						<td><?= $row->tipo_novedades->nombre ?></td>
						<td><?= $row->fecha ?></td>
						<td><?= $row->usuario ?></td>
						<td>
							<?php
							if($fotos==null):
		                    	
								echo "Sin adjunto";
		                    else:		 
				            foreach($fotos as $foto): 

				                $nombre_archivo=str_replace('/uploads/novedad_incidente/','',$foto->foto);
				            	$extension=explode('.',$nombre_archivo);

				            	if($extension[1]=='jpg' or $extension[1]=='JPG' or $extension[1]=='png' or $extension[1]=='PNG' or $extension[1]=='gif' or $extension[1]=='GIF'  ):
				            ?>
		        			

		        			<a title="Click para ver imagen completa" data-toggle="modal" data-target="#myModal_adjunto" onclick="cargar_imagen('<?= Yii::$app->request->baseUrl.$foto->foto ?>');"><img src="<?= Yii::$app->request->baseUrl.$foto->foto ?>" class="img-responsive img-thumbnail"  style='height:50px;width: 70px'></a><br>

							<?php else: ?>
							<a title="<?= $nombre_archivo?>"  href="<?= Yii::$app->request->baseUrl.$foto->foto ?>" download=""><i class="fas fa-file-export fa-2x"></i> </a><br>
							<?php endif; ?>

					        <?php 
					                
					            endforeach;

					            endif;
					        ?>
						</td>
						<td><?= $row->desc_novedad?></td>
					</tr>
				<?php endforeach;?>
			</tbody>
		</table>
		</div>
	</div>
</div>
<!-- ///////////////////////////////////////////////////////////// -->

<?= $this->render('_modal_adjunto') ?>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Departamento a enviar</h4>
      </div>
      <div class="modal-body">
        
		<label class="radio-inline">
		  <input type="radio" name="area" id="recursos" value="R" checked=""> Recursos Humanos
		</label>
		<label class="radio-inline">
		  <input type="radio" name="area" id="judicializacion" value="J"> Judicializacion
		</label>

		<label class="radio-inline">
		  <input type="radio" name="area" id="sin-asignar" value="S"> Sin asignar
		</label>

		<br><br>

		<div class="row">
			<div class="col-md-12">
				<label>Detalle Cierre</label>

				<textarea id="detalle" rows="3" class="form-control"></textarea>
			</div>
		</div>
		
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" onclick="cerrar_caso();">Cerrar Investigacion</button>
      </div>
    </div>
  </div>
</div>



<script type="text/javascript">
	function ver_adjuntos(contador){

		$('#panel'+contador).show('slow/400/fast', function() {
			
		});
	}

	function cerrar_ventana(contador){
		$('#panel'+contador).hide('slow/400/fast', function() {
			
		});
	}

	function cerrar_caso(){

		var confirmar=confirm('Seguro desea cerrar esta Investigacion');
		var area=$('input:radio[name=area]:checked').val();
		var detalle=$('#detalle').val();
		if (confirmar) {

			location.href="<?= Yii::$app->request->baseUrl.'/incidente/cerrarcaso?id='.$model->id?>&area="+area+"&detalle="+detalle;
			
		}
	}

	function abrir_caso(){
		var confirmar=confirm('Seguro desea reabrir esta Investigacion');
		
		if (confirmar) {

			location.href="<?= Yii::$app->request->baseUrl.'/incidente/abrircaso?id='.$model->id?>";
			
		}
	}

	function cargar_imagen(src){
		$('#imagen_adjunto').attr({
			src: src
			
		});
	}
</script>


