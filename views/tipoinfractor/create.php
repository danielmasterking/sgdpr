<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TipoInfractor */

$this->title = 'Create Tipo Infractor';
$this->params['breadcrumbs'][] = ['label' => 'Tipo Infractors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tipo-infractor-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
