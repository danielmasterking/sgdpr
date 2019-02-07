<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Categorias';

?>

	 <?= $this->render('_tabs',['categoria' =>'active']) ?>
    <h1 style="text-align: center;"><?= Html::encode($this->title) ?></h1>
	
	<div class="form-group">

	<?= Html::a('<i class="fa fa-plus"></i>',Yii::$app->request->baseUrl.'/categoria-visita/create',['class'=>'btn btn-primary']) ?>
		
	</div>	
    
	 <table  class="display my-data" data-page-length='20' cellspacing="0" width="100%">
	 
       <thead>

       <tr>
           
           <th></th>
           <th>Nombre</th>
		  <!--  <th>Criterio</th> -->
		  <th>Estado</th>
           
       </tr>
           

       </thead>	 
	   
	   <tbody>
	   
             <?php foreach($categorias as $categoria):?>	  
			   
              <tr>			   
			   <td><?php
                echo Html::a('<i class="fas fa-edit"></i>',Yii::$app->request->baseUrl.'/categoria-visita/update?id='.$categoria->id,['class'=>'btn btn-primary btn-xs']);
                //echo Html::a('<i class="fa fa-remove"></i>',Yii::$app->request->baseUrl.'/categoria/delete?id='.$categoria->id,['data-method'=>'post']);
                if ($categoria->estado=='A') {
                	echo Html::a('<i class="fa fa-trash"></i>',Yii::$app->request->baseUrl.'/categoria-visita/disabled?id='.$categoria->id,['data-method'=>'post','data-confirm'=>'Desea deshabilitar','class'=>'btn btn-danger btn-xs']);
                }else{
                	echo Html::a('<i class="fa fa-check"></i>',Yii::$app->request->baseUrl.'/categoria-visita/enabled?id='.$categoria->id,['data-method'=>'post','data-confirm'=>'Desea Habilitar','class'=>'btn btn-success btn-xs']);
                }

                    ?>
				</td>
                
     			<td><?= $categoria->nombre?></td>
				<!-- <td><?= $categoria->criterio?></td> -->
				<td>
				 <?php 
				 	
				 	$estado=$categoria->estado=='A'?'<span class="label label-success" >Activo</span>':'<span class="label label-danger" >Inactivo</span>';
				 	
				 	echo $estado;
				 ?>
				</td>
              </tr>
        	 <?php endforeach; ?>			 
	   
	   </tbody>
	 
	 </table>