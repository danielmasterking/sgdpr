<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Distrito */

$this->title = 'Formulario de investigacion';
?>
<?php if(isset($done) && $done === '200'):?>
   
   <p style="text-align:center;" class="alert alert-success">Incidente creado de forma correcta.</p>
   
<?php endif;?>
	
	<?= Html::a('<i class="fa fa-arrow-left"></i>',Yii::$app->request->baseUrl.'/incidente',['class'=>'btn btn-primary']) ?>

    <h1 style="text-align:center;"><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-info" role="alert">
     <i class="fa fa-info"></i> La investigacion se asignara automaticamente al creador de esta
    </div>

    <?= $this->render('_form', [
        'model' => $model,
		'novedades' => $novedades,
		'dependencias' => $dependencias,
		'marcasUsuario' => $marcasUsuario,
		'distritosUsuario' => $distritosUsuario,
		'zonasUsuario' => $zonasUsuario,
		'usuarios'=>$usuarios,
		'list_tipo_infractor'=>$list_tipo_infractor
		//'zonas' => $zonas,
    ]) ?>