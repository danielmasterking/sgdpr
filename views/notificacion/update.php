<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Notificacion */

$this->title = 'Update Notificacion: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Notificacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="notificacion-update">
	<?= Html::a('<i class="fa fa-mail-reply"></i> Volver', ['index'], ['class' => 'btn btn-primary']) ?>
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'actualizar'=>1,
        'usuarios'=>$usuarios,
        'zonas'=>$zonas,
        'usuarios_not'=>$usuarios_not,
        'zona_not'=>$zona_not
    ]) ?>

</div>
