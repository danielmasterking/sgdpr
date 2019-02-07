<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Novedades';

?>
    <div class="page-header">
	  <h1><small><i class="far fa-dot-circle"></i></small> <?= Html::encode($this->title) ?></h1>
	</div>
	
	<div class="form-group">

	<?= Html::a('<i class="fa fa-plus"></i>',Yii::$app->request->baseUrl.'/novedad/create',['class'=>'btn btn-primary']) ?>
		
	</div>	
    
	 <table  class="display my-data" data-page-length='20' cellspacing="0" width="100%">
	 
       <thead>

       <tr>
           
           <th></th>
           <th>Nombre</th>
		   <th>Tipo</th>
		   <th>Estado</th>
           
       </tr>
           

       </thead>	 
	   
	   <tbody>
	   
             <?php foreach($novedades as $novedad):?>	  
			   
              <tr>			   
			   <td><?php
                echo Html::a('<i class="fas fa-edit"></i>',Yii::$app->request->baseUrl.'/novedad/update?id='.$novedad->id,['class'=>'btn btn-primary btn-xs']);
                //echo Html::a('<i class="fa fa-remove"></i>',Yii::$app->request->baseUrl.'/novedad/delete?id='.$novedad->id,['data-method'=>'post']);
                echo Html::a('<i class="fa fa-trash"></i>',Yii::$app->request->baseUrl.'/novedad/desactivar?id='.$novedad->id,['data-method'=>'post','data-confirm'=>'Desea desactivar esta novedad?','class'=>'btn btn-danger btn-xs']);

                    ?>
				</td>
                
     			<td><?= $novedad->nombre?></td>
				<td><?= $novedad->tipo?></td>
				<td><?= $novedad->estado=='A'?'Activo':'Inactivo'?></td>
              </tr>
        	 <?php endforeach; ?>			 
	   
	   </tbody>
	 
	 </table>