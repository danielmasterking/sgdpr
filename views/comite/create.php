<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Distrito */

$this->title = 'Formulario de comité';
?>
<?php if(isset($done) && $done === '200'):?>
   
   <p class="alert alert-success">Comité creado de forma correcta.</p>
   
<?php endif;?>

    <div class="page-header">
	  <h1><small><i class="far fa-dot-circle"></i></small> <?= Html::encode($this->title) ?></h1>
	</div>

    <?= $this->render('_form', [
        'model' => $model,
		'novedades' => $novedades,
		'marcas' => $marcas,
		'distritos' => $distritos,
		'dependencias' => $dependencias,
		'marcasUsuario' => $marcasUsuario,
		'distritosUsuario' => $distritosUsuario,
		'zonasUsuario' => $zonasUsuario,		
		'cordinadores' => $cordinadores,
    ]) ?>