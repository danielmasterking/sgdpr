<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\SistemaProyectos */

$this->title = 'Create Sistema Proyectos';
$this->params['breadcrumbs'][] = ['label' => 'Sistema Proyectos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sistema-proyectos-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
