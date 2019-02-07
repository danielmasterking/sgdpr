<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ManualApp */

$this->title = 'Crear Item Manual';
$this->params['breadcrumbs'][] = ['label' => 'Manual Apps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="manual-app-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'query'=>$query
    ]) ?>

</div>
