<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TipoReportes */

$this->title = 'Create Tipo Reportes';
$this->params['breadcrumbs'][] = ['label' => 'Tipo Reportes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tipo-reportes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
