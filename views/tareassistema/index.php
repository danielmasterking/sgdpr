<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TareasSistemaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tareas Sistemas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tareas-sistema-index">

     <div class="page-header">
      <h1><small><i class="fa fa-book fa-fw"></i></small> <?= Html::encode($this->title) ?></h1>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Crear Tarea', ['create'], ['class' => 'btn btn-primary']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'titulo',
            'fecha',
            //'estado',
            [
                'class' => 'yii\grid\DataColumn',
                'label'=>'Estado',
                'value' => function ($data) {
                    return $data->estado=='T'?'Terminado':'En proceso'; 
                },
            ],
            //'descripcion:html',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
