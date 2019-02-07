<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\GestionRiesgo */

$this->title = 'Gestion Riesgos-'.$model->dependencia->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Gestion Riesgos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gestion-riesgo-view">
<?= Html::a('<i class="fa fa-arrow-left"></i> Volver a gestiones',Yii::$app->request->baseUrl.'/gestionriesgo/informe-novedades',['class'=>'btn btn-primary']) ?>

<a href="<?php echo Url::toRoute('gestionriesgo/imprimir?id='.$model->id)?>" class="btn btn-danger " target="_blank">
    <i class="fas fa-file-pdf"></i> PDF
</a>
    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <div class="table-responsive">
    <table class="table table-striped">
      <tr>
        <th>Fecha Creacion:</th>
        <td><?= $model->fecha?></td>

        <th>Fecha Creacion:</th>
        <td><?= $model->fecha_visita?></td>

        <td rowspan="4" style="text-align: center;">
            <img  style='height:200px;width: 400px' alt="imagen" class="img-responsive img-thumbnail" src="<?=Yii::$app->request->baseUrl.$model->dependencia->foto?>" />
        </td>
      </tr>

      <tr>
        <th>Regional:</th>
        <td><?= $model->dependencia->ciudad->zona->zona->nombre?></td>

        <th>Marca:</th>
        <td><?= $model->dependencia->marca->nombre?></td>
      </tr>

      <tr>
        <th>Usuario:</th>
        <td colspan="3"><?= $model->usuario?></td>
      </tr>
      <tr>
        <th>Observacion:</th>
        <td colspan="3"><?= $model->observacion?></td>
      </tr>
        
    </table>
    </div>

    <h1 class="text-center">Gestion</h1>
    <div class="table-responsive">
    <table class="table table-striped ">
        <thead>
            <tr>
                <th></th>
                <th>Tema</th>
                <th>Respuesta</th>
                <th>Observaciones</th>
                <th>Plan de accion</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $orden=1;
            foreach($consulta as $row){
            ?>

            <tr>
                <td><b><?= $orden?>.</b></td>
                <td><?= $row->consulta->descripcion?></td>
                <td><?= $row->respuesta->descripcion?></td>
                <td><?= $row->observaciones?></td>
                <td><?= $row->planes_de_accion?></td>


            </tr>
            <?php 
                $orden++;
                }
            ?>
        </tbody>
    </table>
    </div>
</div>
