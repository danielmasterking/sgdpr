<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SistemaProyectos */

$this->title = 'Update Sistema Proyectos: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Sistema Proyectos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sistema-proyectos-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
