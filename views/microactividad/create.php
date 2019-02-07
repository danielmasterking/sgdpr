<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Distrito */

$this->title = 'Crear Microactividad';
?>
<?= $this->render('_tabs',['microactividad' => $microactividad]) ?>
    <h1 style="text-align: center;"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'macroactividades' => $macroactividades,
    ]) ?>