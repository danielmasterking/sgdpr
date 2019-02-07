<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TipoInfractor */

$this->title = 'Update Tipo Infractor: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Tipo Infractors', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tipo-infractor-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
