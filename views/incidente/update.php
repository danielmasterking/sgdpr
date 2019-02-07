<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Distrito */

$this->title = 'Actualizar  -'.$model->titulo;
?>
	
	<?= Html::a('<i class="fa fa-arrow-left"></i>',Yii::$app->request->baseUrl.'/incidente/view?id='.$model->id,['class'=>'btn btn-primary']) ?>
   <h1 style="text-align:center;"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
		'novedades' => $novedades,
		'dependencias' => $dependencias,
		'marcasUsuario' => $marcasUsuario,
		'distritosUsuario' => $distritosUsuario,
		'zonasUsuario' => $zonasUsuario,
		'usuarios'=>$usuarios,
		'actualizar'=>1,
		'usuarios_incidente'=>$usuarios_incidente,
		'infractores_inv'=>$infractores_inv,
		'list_tipo_infractor'=>$list_tipo_infractor
		//'zonas' => $zonas,
    ]) ?>