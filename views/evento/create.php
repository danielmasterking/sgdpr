<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Distrito */

$this->title = 'Formulario de visita por solicitud o activación';
?>
 <?= $this->render('_tabs',['eventos' => $evento]) ?>

<?php if(isset($done) && $done === '200'):?>
   
   <p style="text-align: center;" class="alert alert-success">Visita por solicitud creada de forma satisfactoria.</p>
   
<?php endif;?>

   <div class="page-header">
      <h1><small><i class="fa fa-suitcase fa-fw"></i></small> <?= Html::encode($this->title) ?></h1>
    </div>

    <?= $this->render('_form', [
                'model' => $model,
				//'modelDetalle' => $modelDetalle,
				'dependencias' => $dependencias,
				'marcasUsuario' => $marcasUsuario,
				'distritosUsuario' => $distritosUsuario,
				'zonasUsuario' => $zonasUsuario,
				'novedades' => $novedades,
    ]) ?>