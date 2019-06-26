<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\models\Notificacion */

$this->title ='Notificaciones';

?>
<div class="notificacion-view">
<a class="btn btn-primary" href="<?php echo Url::toRoute('notificacion/listado-notificaciones')?>">
    <i class="fas fa-arrow-left"></i>
</a>
    <h3 class="text-center"><?= $model->titulo ?></h3><br>
    <div class="col-md-12">
        <?= $model->descripcion ?>
    </div>
</div>
