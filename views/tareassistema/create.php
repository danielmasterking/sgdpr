<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TareasSistema */

$this->title = 'Create Tareas Sistema';
$this->params['breadcrumbs'][] = ['label' => 'Tareas Sistemas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tareas-sistema-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
