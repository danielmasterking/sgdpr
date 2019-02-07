<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Secciones';

?>
	<?= $this->render('_tabs',['seccion' =>'active']) ?>
    <h1 style="text-align: center;"><?= Html::encode($this->title) ?></h1>
	
	<div class="form-group">

	<?= Html::a('<i class="fa fa-plus"></i>',Yii::$app->request->baseUrl.'/seccion/create',['class'=>'btn btn-primary']) ?>
		
	</div>	
    
	 <table  class="display my-data" data-page-length='20' cellspacing="0" width="100%">
	 
       <thead>

       <tr>
           
           <th></th>
           <th>Nombre</th>
		   
           
       </tr>
           

       </thead>	 
	   
	   <tbody>
	   
             <?php foreach($secciones as $seccion):?>	  
			   
              <tr>			   
			   <td><?php
                echo Html::a('<i class="fas fa-edit"></i>',Yii::$app->request->baseUrl.'/seccion/update?id='.$seccion->id,['class'=>'btn btn-primary btn-xs']);
                echo Html::a('<i class="fa fa-trash"></i>',Yii::$app->request->baseUrl.'/seccion/delete?id='.$seccion->id,['data-method'=>'post','class'=>'btn btn-danger btn-xs']);

                    ?>
				</td>
                
     			<td><?= $seccion->nombre?></td>
				
              </tr>
        	 <?php endforeach; ?>			 
	   
	   </tbody>
	 
	 </table>