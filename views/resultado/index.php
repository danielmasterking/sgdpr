<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Resultados';

?>
	
	<?= $this->render('_tabs',['resultados' =>'active']) ?>
	
    <h1 style="text-align: center;"><?= Html::encode($this->title) ?></h1>
	
	<div class="form-group">

	<?= Html::a('<i class="fa fa-plus"></i>',Yii::$app->request->baseUrl.'/resultado/create',['class'=>'btn btn-primary']) ?>
		
	</div>	
    
	 <table  class="display my-data" data-page-length='20' cellspacing="0" width="100%">
	 
       <thead>

       <tr>
           
           <th></th>
           <th>Nombre</th>
           <th>Estado</th>
		   
           
       </tr>
           

       </thead>	 
	   
	   <tbody>
	   
             <?php foreach($resultados as $resultado):?>	  
			   
              <tr>			   
			   <td><?php
                echo Html::a('<i class="fas fa-edit"></i>',Yii::$app->request->baseUrl.'/resultado/update?id='.$resultado->id,['class'=>'btn btn-primary btn-xs']);
                if ($resultado->estado=='A') {
                	echo Html::a('<i class="fa fa-trash"></i>',Yii::$app->request->baseUrl.'/resultado/disabled?id='.$resultado->id,['data-method'=>'post','data-confirm'=>'Desea deshabilitar','class'=>'btn btn-danger btn-xs']);
                }else{
                	echo Html::a('<i class="fa fa-check"></i>',Yii::$app->request->baseUrl.'/resultado/enabled?id='.$resultado->id,['data-method'=>'post','data-confirm'=>'Desea Habilitar','class'=>'btn btn-success btn-xs']);
                }
                    ?>
				</td>
                
     			<td><?= $resultado->nombre?></td>
     			<td>
				 <?php 
				 	
				 	$estado=$resultado->estado=='A'?'<span class="label label-success" >Activo</span>':'<span class="label label-danger" >Inactivo</span>';
				 	
				 	echo $estado;
				 ?>
				</td>
				
              </tr>
        	 <?php endforeach; ?>			 
	   
	   </tbody>
	 
	 </table>