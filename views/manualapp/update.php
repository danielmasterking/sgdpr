<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ManualApp */

$this->title = 'Actualizar: ' . $model->modulo;
$this->params['breadcrumbs'][] = ['label' => 'Manual Apps', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="manual-app-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'actualizar'=>true
    ]) ?>

</div>
