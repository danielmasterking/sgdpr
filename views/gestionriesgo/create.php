<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\GestionRiesgo */

$this->title = 'Formulario Gestion Riesgo';
$this->params['breadcrumbs'][] = ['label' => 'Gestion Riesgos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gestion-riesgo-create">

    <div class="page-header">
      <h1><small><i class="fab fa-free-code-camp"></i></small> <?= Html::encode($this->title) ?></h1>
    </div>

    <?php 

        $flashMessages = Yii::$app->session->getAllFlashes();
        if ($flashMessages) {
            foreach($flashMessages as $key => $message) {
                echo "<div class='alert alert-" . $key . " alert-dismissible' role='alert'>
                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                        $message
                    </div>";   
            }
        }
    ?>

    <?= $this->render('_form', [
        'model' => $model,
        'dependencias'=> $dependencias,
        'distritosUsuario'=>$distritosUsuario,
        'marcasUsuario'=>$marcasUsuario,
        'zonasUsuario'=>$zonasUsuario,
        'consultas'=>$consultas,
        //'list_respuestas'=>$list_respuestas
        'respuestas'=>$respuestas
    ]) ?>

</div>
