<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Distrito */

$this->title = 'Crear Dependencia';
?>
    <h1 style="text-align: center;"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
		'ciudades' => $ciudades,
		'marcas' => $marcas,
		'distritos' => $distritos,
		'marcasUsuario' => $marcasUsuario,
		'distritosUsuario' => $distritosUsuario,
		'zonasUsuario' => $zonasUsuario,
        'empresas' => $empresas,	
        'empresas_electronica'=>$empresas_electronica	
    ]) ?>