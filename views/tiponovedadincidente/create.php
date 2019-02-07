<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TipoNovedadIncidente */

$this->title = 'Crear';
$this->params['breadcrumbs'][] = ['label' => 'Tipo Novedad Incidentes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tipo-novedad-incidente-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
