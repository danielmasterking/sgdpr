<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NotificacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Notificacion';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notificacion-index">

    <div class="page-header">
      <h1><small><i class="far fa-comment"></i></small> <?= Html::encode($this->title) ?></h1>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php 

        //if($count==0):
        ?>
        <?= Html::a('<i class="fa fa-plus"></i> Crear Notificacion', ['create'], ['class' => 'btn btn-primary']) ?>
    <?php //endif;?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
       // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'descripcion:html',
            'titulo',
            'fecha_inicio',
            'fecha_final',

            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],
        ],
    ]); ?>
</div>
