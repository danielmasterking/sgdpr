<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TiposFinalizadoProyectos */

$this->title = 'Create Tipos Finalizado Proyectos';
$this->params['breadcrumbs'][] = ['label' => 'Tipos Finalizado Proyectos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tipos-finalizado-proyectos-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
