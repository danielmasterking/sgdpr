<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Distrito */

$this->title = 'Actualizar periodicidad';
?>
<?= $this->render('_tabs',['periodicidad' => $periodicidad]) ?>
    <h1 style="text-align: center;"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>