<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Distrito */

$this->title = 'Actualizar mensaje valor novedad';
?>
    <h1 style="text-align: center;"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_update', [
        'model' => $model,
	    'valores' => $valores,
    ]) ?>