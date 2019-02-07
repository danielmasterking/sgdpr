<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Permisos';

?>
    <div class="page-header">
	  <h1><small><i class="far fa-dot-circle"></i></small> <?= Html::encode($this->title) ?></h1>
	</div>
	
	<div class="form-group">

	<?= Html::a('<i class="fa fa-plus"></i>',Yii::$app->request->baseUrl.'/permiso/create',['class'=>'btn btn-primary']) ?>
		
	</div>	
    
	 <table  class="display my-data" data-page-length='20' cellspacing="0" width="100%">
	 
       <thead>

       <tr>
           
           <th></th>
		   
           <th>Nombre</th>

           
       </tr>
           

       </thead>	 
	   
	   <tbody>
	   
             <?php foreach($permisos_app as $per):?>	  
			   
              <tr>			   
			   <td><?php
                echo Html::a('<i class="fas fa-edit"></i>',Yii::$app->request->baseUrl.'/permiso/update?id='.$per->id,['class'=>'btn btn-primary btn-xs']);
                echo Html::a('<i class="fa fa-trash"></i>',Yii::$app->request->baseUrl.'/permiso/delete?id='.$per->id,['data-method'=>'post','data-confirm'=>'Seguro desea eliminar?','class'=>'btn btn-danger btn-xs']);

                    ?>
				</td>
                
     			<td><?= $per->nombre?></td>
				
	
              </tr>
        	 <?php endforeach; ?>			 
	   
	   </tbody>
	 
	 </table>