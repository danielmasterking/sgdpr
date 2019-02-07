<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Distritos';

?>
    <div class="page-header">
	  <h1><small><i class="far fa-dot-circle"></i></small> <?= Html::encode($this->title) ?></h1>
	</div>
	
	<div class="form-group">

	<?= Html::a('<i class="fa fa-plus"></i>',Yii::$app->request->baseUrl.'/distrito/create',['class'=>'btn btn-primary']) ?>
		
	</div>	
    
	 <table  class="display my-data" data-page-length='20' cellspacing="0" width="100%">
	 
       <thead>

       <tr>
           
           <th></th>
		   
           <th>Nombre</th>
		   <th>Regional</th>
		   

           
       </tr>
           

       </thead>	 
	   
	   <tbody>
	   
             <?php foreach($distritos as $distrito):?>	  
			   
              <tr>			   
			   <td><?php
                echo Html::a('<i class="fas fa-edit"></i>',Yii::$app->request->baseUrl.'/distrito/update?id='.$distrito->id,['class'=>'btn btn-primary btn-xs']);
                echo Html::a('<i class="fa fa-plus fa-fw"></i>',Yii::$app->request->baseUrl.'/distrito/dependencias?id='.$distrito->id,['title' => 'Agregar Dependencias','class'=>'btn btn-success btn-xs']);
				echo Html::a('<i class="fa fa-trash"></i>',Yii::$app->request->baseUrl.'/distrito/delete?id='.$distrito->id,['data-method'=>'post','class'=>'btn btn-danger btn-xs']);

                    ?>
				</td>
                
     			<td><?= $distrito->nombre?></td>
				<td><?php
				
				    $zona = $distrito->zonas;
					
					if($zona != null){
						
						echo $zona[0]->zona->nombre;
						
					}
				
				?></td>
				
	
              </tr>
        	 <?php endforeach; ?>			 
	   
	   </tbody>
	 
	 </table>