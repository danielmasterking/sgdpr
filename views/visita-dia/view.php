<script src="https://code.highcharts.com/highcharts.src.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
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
use app\models\DetalleVisitaSeccion;
use app\models\CategoriaVisita;
use app\models\DetalleVisitaDia;
use app\models\ValorNovedad;
use app\models\VisitaFotos;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Visita Quincenal ';
//$detalle_visita = $model->detalle; //array con detalle de la visita
//$seguridad_electronica = false;



?>

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
<div class="form-group">

	    <?php if(isset($dependencia)):?>
		  <?= Html::a('<i class="fa fa-arrow-left"></i>',Yii::$app->request->baseUrl.'/centro-costo/visita?id='.$dependencia,['class'=>'btn btn-primary']) ?>
		<?php else:?>
		 <?= Html::a('<i class="fa fa-arrow-left"></i>',Yii::$app->request->baseUrl.'/usuario/visita?id='.$model->usuario,['class'=>'btn btn-primary']) ?>
		<?php endif;?>

		<?//= Html::a('<i class="fa fa-file-pdf-o"></i> Pdf',Yii::$app->request->baseUrl.'/visita-dia/pdf?id='.$model->id,['class'=>'btn btn-primary']) ?>

		<?= Html::a('<i class="far fa-file-pdf"></i> Pdf',Yii::$app->request->baseUrl.'/visita-dia/imprimir?id='.$model->id.'&dependencia='.$dependencia,['class'=>'btn btn-primary']) ?>


		 <a class="btn btn-primary" role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
 			<i class="fas fa-chart-bar"></i> Estadisticas 
		</a> 

		</div>      

     
	 <h1 style="text-align: center;"><?= Html::encode($this->title) ?></h1>

	
	<div class="collapse" id="collapseExample">
  		<div class="well">
			<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
  			<?php 
  			$cont_cat=0;
  		    foreach($categorias as $cat):
  			?>
  			
  			
			  <div class="panel panel-primary">
			    <div class="panel-heading" role="tab" id="headingOne">
			      <h4 class="panel-title">
			        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne<?php echo $cont_cat?>" aria-expanded="true" aria-controls="collapseOne">
			          <i class="fas fa-chart-area"></i> <?php echo $cat->nombre?>
			        </a>
			      </h4>
			    </div>
			    <div id="collapseOne<?php echo $cont_cat?>" class="panel-collapse collapse <?php echo $cont_cat==0?'in':'' ?>" role="tabpanel" aria-labelledby="headingOne">
			      <div class="panel-body">

				    <div id="container<?php echo $cont_cat?>">
	    			
	    		    </div>
			      </div>
			    </div>
			  </div>
			 
			<?php 
			

			$eje_y=$model_visita->respuestas_visita($cat->id,$dependencia);
			$eje_x=$model_visita->categorias_visita($cat->id);
			?>

			<script type="text/javascript">
			Highcharts.chart('container<?php echo $cont_cat?>', {
		    chart: {
		        type: 'bar'
		    },
		    title: {
		        text: '<?php echo $cat->nombre ?>'
		    },
		    subtitle: {
		        text: ''
		    },
		    xAxis: {
		        categories: <?php echo $eje_x ?>,
		        title: {
		            text: null
		        }
		    },
		    yAxis: {
		        min: 0,
		        title: {
		            text: '',
		            align: 'high'
		        },
		        labels: {
		            overflow: 'justify'
		        }
		    },
		    tooltip: {
		        valueSuffix: ' '
		    },
		    plotOptions: {
		        bar: {
		            dataLabels: {
		                enabled: true
		            }
		        }
		    },
		    legend: {
		        layout: 'vertical',
		        align: 'right',
		        verticalAlign: 'top',
		        x: -40,
		        y: 80,
		        floating: true,
		        borderWidth: 1,
		        backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
		        shadow: true
		    },
		    credits: {
		        enabled: false
		    },
		    series:<?php echo $eje_y ?>
		});
		</script>
			

  		
  			<?php
  			$cont_cat++;
  			endforeach;
  			?>
			</div>

    		
  		</div>
	</div> 

	<div class="col-md-12">
		<table class="table table-striped">
		 	<tr>
		 		<th>Fecha de creación:</th>
		 		<td><?= $model->fecha?></td>
		 		<td rowspan="5" style="text-align: center;">
				<img style=" width: 400px; height: 200px;" alt="imagen" class="img-responsive img-thumbnail" src="<?=Yii::$app->request->baseUrl.$model->dependencia->foto?>" />
				</td>
		 	</tr>
		 	<tr>
		 		<th>Creada por:</th>
		 		<td><?= $model->usuario?></td>
		 	</tr>
		 	<tr>
		 		<th>Dependencia:</th>
		 		<td><?= $model->dependencia->nombre?></td>
		 	</tr>

		 	<tr>
		 		<th>Atendió Visita:</th>
		 		<td><?= $model->responsable?></td>
		 	</tr>

		 	<tr>
		 		<th>Otro:</th>
		 		<td><?= $model->otro?></td>
		 	</tr>

		 	<tr>
		 		<th>Observaciones:</th>
		 		<td colspan="2"><?= $model->observaciones?></td>
		 	</tr>
		 </table>
	 </div>

	<div class="col-md-12">
	<!-- ********************************************************************************************************* -->
	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
	  <div class="panel panel-default">
	    <div class="panel-heading" role="tab" id="headingOne">
	      <h4 class="panel-title">
	        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
	          <i class="fa  fa-camera"></i> Foto
	        </a>
	      </h4>
	    </div>
	    <div id="collapseOne" class="panel-collapse collapse " role="tabpanel" aria-labelledby="headingOne">
	      <div class="panel-body">
	      	 <div class="row">
			   <?php 
			  		$fotos=VisitaFotos::fotos($model->id);

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
	 			$ruta = $model->foto == null ? ' ' : $model->foto;
        		$ruta = Yii::$app->request->baseUrl.$ruta; 

        		if($model->foto!=null):
			?> 
			 <img style="height: 500px;width:800px;" alt="imagen" class="img-responsive img-thumbnail" src="<?= $ruta ?>" />
			<?php endif;?>
	      </div>
	    </div>
	  </div>
   
    </div>
    </div>
	 <!-- ********************************************************************************************************* -->
	 
	

	<h1 class="text-center">Detalle</h1>
	<br>

 <div class="row" id="estadistica">
	
	<div class="col-md-8 table-responsive " style="padding-left: 0px;">	
		<table class="table table-striped">
			<?php 
			$orden=1;
			$calif=0;
			$array_calif=array();
			foreach($categorias as $cat1):

				$detalle_visita=DetalleVisitaDia::Detalle_visitas($cat1->id,$id_visita);

			?>
				<tr>
					<th style="text-align: center;" colspan="5" class="danger"><?= $cat1->nombre ?></th>
				</tr>

				<tr >	
					<th style="text-align: center;"></th>
					<th style="text-align: center;">Novedad</th>
					<th style="text-align: center;">Resultado</th>
					<th style="text-align: center;">Mensaje</th>
					<th style="text-align: center;">Comentario</th>
				</tr>
			<?php  

				$calif_secc=0;
				foreach($detalle_visita as $detalle):

				
				$valor_calif=ValorNovedad::porcentaje($detalle->novedad_categoria_visita_id,$detalle->resultado_id);

				$calif+=$valor_calif;

				$calif_secc+=$valor_calif;

				//echo $valor_calif;
			?>
			
				<tr>
					<td style="text-align: center;"><b><?= $orden."." ?></b></td>
					<td style="text-align: center;"><?= $detalle->novedad->nombre?></td>
					<td style="text-align: center;"><?= $detalle->resultado->nombre?></td>
					<td style="text-align: center;"><?= $detalle->mensajeNovedad->mensaje?></td>
					<td style="text-align: center;"><?= $detalle->observacion?></td>
				</tr>

				<?php 
					$detalle_seccion=DetalleVisitaSeccion::find()->where('detalle_visita_dia_id='.$detalle->id)->all();

					if($detalle_seccion!=null):
				?>
				<tr>
					<td colspan="5" style="text-align: center;" class="info"><b>Secciones- <?= $cat1->nombre ?></b></td>
				</tr>

				<tr >
					<th style="text-align: center;"></th>
					<th style="text-align: center;">Seccion</th>
					<th style="text-align: center;" colspan="3">Resultado</th>
					<!-- <th style="text-align: center;">Mensaje</th>
					<th style="text-align: center;"> Comentario</th> -->
				</tr>

				<?php 
					$arr_secc=['a','b','c','d','e','f','g','h','i','j','k','l','m','n','ñ','o','p','q','r','s','t','u','x','y','z'];
					$i=0;
					foreach($detalle_seccion as $secc): 
				?>
				<tr>
					<td style="text-align: center;"><b><?= $arr_secc[$i] ?>.</b></td>
					<td style="text-align: center;"><?= $secc->seccion->nombre?></td>
					<td style="text-align: center;" colspan="3"><?= $secc->resultado_secc->nombre?></td>
					<!-- <td style="text-align: center;"><?//= $secc->mensaje_secc->mensaje?></td>
					<td style="text-align: center;"><?//= $secc->observacion?></td> -->

				</tr>
				<?php 
					$i++;
					endforeach;
				?>
				<tr>
					<td colspan="5" class="info" style="text-align: center;" ></td>
				</tr>
				<?php
				endif;
				?>

				

			

		<?php 
			$orden++;
			endforeach;


			$array_calif[$cat1->nombre]=$calif_secc;

			endforeach;		 
		?>
		</table>
	</div>

	<div class="col-md-4">
		<div id="container_negativo" style="height: 350px;width: 550px;"></div>		
	</div>

	<div class="row">
		<div class="col-md-4">
			
		<table class="table table-striped">
			<?php 

			foreach($array_calif as $key=> $value ):
			?>
			<tr>
				<th style="text-align: center;"><?= $key ?></th>
				<td style="text-align: center;"><?= $value."%" ?></td>
			</tr>
			<?php
			endforeach;
			?>
			
		</table>

		</div>
		<div class="col-md-4">
			<div class="panel panel-primary">
			  <div class="panel-heading"><h3 class="text-center">Total%</h3></div>
			  <div class="panel-body">
			    <h1 class="text-center text-danger"><?= $calif."%"?></h1>
			  </div>
			</div>
		
		</div>
	</div>

	
		
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

	
	
<script type="text/javascript">
	Highcharts.theme = {
    colors: ['#c0392b','#f4d03f','#dc7633',' #52be80 '],
    chart: {
        backgroundColor: null,
        style: {
            fontFamily: 'Dosis, sans-serif'
        }
    },
    title: {
        style: {
            fontSize: '16px',
            fontWeight: 'bold',
            textTransform: 'uppercase'
        }
    },
    tooltip: {
        borderWidth: 0,
        backgroundColor: 'rgba(219,219,216,0.8)',
        shadow: false
    },
    legend: {
        itemStyle: {
            fontWeight: 'bold',
            fontSize: '13px'
        }
    },
    xAxis: {
        gridLineWidth: 1,
        labels: {
            style: {
                fontSize: '12px'
            }
        }
    },
    yAxis: {
        minorTickInterval: 'auto',
        title: {
            style: {
                textTransform: 'uppercase'
            }
        },
        labels: {
            style: {
                fontSize: '12px'
            }
        }
    },
    plotOptions: {
        candlestick: {
            lineColor: '#404048'
        }
    },


    // General
    background2: '#F0F0EA'

};

// Apply the theme
Highcharts.setOptions(Highcharts.theme);

Highcharts.chart('container_negativo', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Mayores Novedades generadas'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                }
            }
        }
    },
    series: [{
        name: 'Porcentaje',
        colorByPoint: true,
        data:  <?= $json_negativo?>
    }]
});

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