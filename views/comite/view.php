<style type="text/css">
	.btn:focus, .btn:active, button:focus, button:active {
	  outline: none !important;
	  box-shadow: none !important;
	}

	#image-gallery .modal-footer{
	  display: block;
	}

	.thumb{
	  margin-top: 15px;
	  margin-bottom: 15px;
	}
</style>
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use marqu3s\summernote\Summernote;
use kartik\widgets\TimePicker;
use kartik\widgets\FileInput;
use kartik\widgets\DepDrop ;
use kartik\datecontrol\Module;
use kartik\datecontrol\DateControl;
use app\models\FotoComite;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Comité ';
$ComiteDependencias = $model->dependencias;
$ComiteMarcas = $model->distritos;
$ComiteCordinadores = $model->cordinadores;
$dependencias = array();
$marcas = array();
$cordinadores = array();
if($ComiteDependencias != null){
	
	foreach($ComiteDependencias as $key){
		
		$dependencias [] = array('nombre' => $key->dependencia->nombre);
		
	}
	
}

if($ComiteMarcas != null){
	
	foreach($ComiteMarcas as $key){
		
		$marcas [] = array('nombre' => $key->distrito->nombre);
		
	}
	
}

if($ComiteCordinadores != null){
	
	foreach($ComiteCordinadores as $key){
		
		$cordinadores [] = array('nombre' => $key->usuario0->nombres.' '.$key->usuario0->apellidos);
		
	}
	
}



?>
<div class="form-group">

	<?php if(isset($dependencia)):?>
	<?= Html::a('<i class="fa fa-arrow-left"></i>',Yii::$app->request->baseUrl.'/centro-costo/comite?id='.$dependencia,['class'=>'btn btn-primary']) ?>
	<?php else:?>
		<?= Html::a('<i class="fa fa-arrow-left"></i>',Yii::$app->request->baseUrl.'/usuario/comite?id='.$model->usuario,['class'=>'btn btn-primary']) ?>
	<?php endif;?>
	<?= Html::a('<i class="far fa-file-pdf"></i> Pdf',Yii::$app->request->baseUrl.'/comite/pdf?id='.$model->id,['class'=>'btn btn-primary pull-right']) ?>

</div>      


<h1 style="text-align: center;"><?= Html::encode($this->title) ?></h1>

<div class="col-md-12">

	<div class="col-md-6">
		<label>Fecha de creación</label>
		<input type="text"  class="form-control" value="<?= $model->fecha?>" readonly="readonly"/>
	</div>
	<div class="col-md-6">

		<label>Creado por</label>
		<input type="text"  class="form-control" value="<?= $model->usuario?>" readonly="readonly"/>
	</div>

	<p>&nbsp;</p>
	<div class="col-md-12">

		<label>Tipo de comité</label>
		<input type="text"  class="form-control" value="<?= $model->novedad->nombre?>" readonly="readonly"/>
	</div>
	<p>&nbsp;</p>
	<?php if($dependencias != null):?>
	<div class="col-md-12">
		<label><strong>Lugar:</strong></label>

		<table class="table">

			<thead>

				<tr>

					<td></td>


				</tr>

			</thead>

			<tbody>


				<?php foreach($dependencias as $key):?>
				
				<tr>

					<td><?=$key['nombre']?></td>

				</tr>

			<?php endforeach;?>



		</tbody>

	</table>



</div>
<?php endif;?>
<?php if($marcas != null):?>
<div class="col-md-12">
	<label><strong>Distrito:</strong></label>

	<table class="table">

			<thead>

				<tr>

					<td></td>


				</tr>

			</thead>

			<tbody>


				<?php foreach($marcas as $key):?>
				
				<tr>

					<td><?=$key['nombre']?></td>

				</tr>

			<?php endforeach;?>



		</tbody>

	</table>
</div>
<?php endif;?>
<?php if($cordinadores != null):?>
<div class="col-md-12">
		<label><strong>Cordinador:</strong></label>

		<table class="table">

			<thead>

				<tr>

					<th></th>


				</tr>

			</thead>

			<tbody>


				<?php foreach($cordinadores as $key):?>
				
				<tr>

					<td><?=$key['nombre']?></td>

				</tr>

			<?php endforeach;?>



		</tbody>

	</table>
</div>
<?php endif;?>	   
<p>&nbsp;</p>
<div class="col-md-12">
	<label>Observaciones</label>

	<?= Summernote::widget([

		'name' => 'observaciones',
		'value' => $model->observaciones,
		'clientOptions' => [

		'enable' => false,

		]
		]) ?>

	</div>

	<p>&nbsp;</p>
	<div class="col-md-12">
		<label>Registro Fotografico</label>

		<div class="row">
	   <?php 
	  		$fotos=FotoComite::fotos($model->id);

	  		if($fotos!=null):

	  		foreach($fotos as $ft):
	  	?>
	  	    <div class="col-lg-3 col-md-4 col-xs-6 thumb">
                <a class="thumbnail" href="#" data-image-id="" data-toggle="modal" data-title=""
                   data-image="<?php echo Yii::$app->request->baseUrl.$ft->archivo?>"
                   data-target="#image-gallery">
                    <img class="img-thumbnail"
                         src="<?php echo Yii::$app->request->baseUrl.$ft->archivo?>"
                         alt="Foto">
                </a>
            </div>
	  	
	  	<?php 

	  		endforeach;
	  		endif;
	  	?>
	  	</div>

		<?php

		/**********************Rendering Image *******************************/
		if($model->foto != null && $model->foto != ''){

			?>

			<img src="<?=Yii::$app->request->baseUrl.$model->foto?>" alt="Fotografía" class="img-responsive img-thumbnail"/>			  

			<?php

		}			  

		?>



	</div>

	<p>&nbsp;</p>
	<div class="col-md-12">


		<?php

		/**********************Rendering Image *******************************/
		if($model->lista != null && $model->lista != ''){

			/**Validar imagen o pdf o xls*/
			if((strpos($model->lista, 'pdf') !== false || strpos($model->lista, 'xls') !== false || strpos($model->lista, 'xlsx') !== false ) ){


				?>
				<label>Acta de asistencia</label>
				<p>
					<a href="http://cvsc.com.co/sgs/web<?=$model->lista?>" download>
						<?=$model->lista?>
					</a>
				</p>

				<?php
			}else{

				?>

				<img src="<?=Yii::$app->request->baseUrl.$model->lista?>" alt="Fotografía" class="img-responsive img-thumbnail"/>			  
				<?php			   
			}

			?>



			<?php

		}


		?>

	</div>	   



<div class="modal fade" id="image-gallery" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="image-gallery-title"></h4>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img id="image-gallery-image" class="img-responsive col-md-12" src="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary float-left" id="show-previous-image"><i class="fa fa-arrow-left"></i>
                    </button>

                    <button type="button" id="show-next-image" class="btn btn-secondary float-right"><i class="fa fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
<script type="text/javascript">
	let modalId = $('#image-gallery');

$(document)
  .ready(function () {

    loadGallery(true, 'a.thumbnail');

    //This function disables buttons when needed
    function disableButtons(counter_max, counter_current) {
      $('#show-previous-image, #show-next-image')
        .show();
      if (counter_max === counter_current) {
        $('#show-next-image')
          .hide();
      } else if (counter_current === 1) {
        $('#show-previous-image')
          .hide();
      }
    }

    /**
     *
     * @param setIDs        Sets IDs when DOM is loaded. If using a PHP counter, set to false.
     * @param setClickAttr  Sets the attribute for the click handler.
     */

    function loadGallery(setIDs, setClickAttr) {
      let current_image,
        selector,
        counter = 0;

      $('#show-next-image, #show-previous-image')
        .click(function () {
          if ($(this)
            .attr('id') === 'show-previous-image') {
            current_image--;
          } else {
            current_image++;
          }

          selector = $('[data-image-id="' + current_image + '"]');
          updateGallery(selector);
        });

      function updateGallery(selector) {
        let $sel = selector;
        current_image = $sel.data('image-id');
        $('#image-gallery-title')
          .text($sel.data('title'));
        $('#image-gallery-image')
          .attr('src', $sel.data('image'));
        disableButtons(counter, $sel.data('image-id'));
      }

      if (setIDs == true) {
        $('[data-image-id]')
          .each(function () {
            counter++;
            $(this)
              .attr('data-image-id', counter);
          });
      }
      $(setClickAttr)
        .on('click', function () {
          updateGallery($(this));
        });
    }
  });

// build key actions
$(document)
  .keydown(function (e) {
    switch (e.which) {
      case 37: // left
        if ((modalId.data('bs.modal') || {})._isShown && $('#show-previous-image').is(":visible")) {
          $('#show-previous-image')
            .click();
        }
        break;

      case 39: // right
        if ((modalId.data('bs.modal') || {})._isShown && $('#show-next-image').is(":visible")) {
          $('#show-next-image')
            .click();
        }
        break;

      default:
        return; // exit this handler for other keys
    }
    e.preventDefault(); // prevent the default action (scroll / move caret)
  });

</script>
