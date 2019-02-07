<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Distrito */


$this->title = 'Generar Prefactura';	
//var_dump($filas);
?>
   <h1 style="text-align: center;"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
	
        'model' => $model,
		'dependencias' => $dependencias,
		'empresas' => $empresas,
		'zonasUsuario' => $zonasUsuario,
		'marcasUsuario' => $marcasUsuario,
		'distritosUsuario' => $distritosUsuario,
		'empresasUsuario' => $empresasUsuario,
		 'servicios' => $servicios,
		 'dias' => $dias,
		 'jornadas' => $jornadas,
         'actualizar' => 's',
         'filas' => $filas,		 
		
	
    ]) ?>