<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mensajes Valor Novedades';

?>
	<?= $this->render('_tabs',['mensaje' =>'active']) ?>
    <h1 style="text-align: center;"><?= Html::encode($this->title) ?></h1>
	
	<div class="form-group">

	<?= Html::a('<i class="fa fa-plus"></i>',Yii::$app->request->baseUrl.'/mensaje-novedad/create',['class'=>'btn btn-primary']) ?>
		
	</div>	
    
	 <table  class="display my-data" data-page-length='20' cellspacing="0" width="100%">
	 
       <thead>

       <tr>
           
           <th></th>
           <th>Mensaje</th>
		   <th>Novedad</th>
		   <th>Resultado</th>
		   <th>Categoria</th>
		   
           
       </tr>
           

       </thead>	 
	   
	   <tbody>
	   
             <?php 
             	foreach($mensajes as $mensaje):
             	if($mensaje->valorNovedad->novedadCategoriaVisita->estado=='A'):
             ?>	 	  
			   
              <tr>			   
			   <td><?php
                echo Html::a('<i class="fas fa-edit"></i>',Yii::$app->request->baseUrl.'/mensaje-novedad/update?id='.$mensaje->id,['class'=>'btn btn-primary btn-xs']);
                echo Html::a('<i class="fa fa-trash"></i>',Yii::$app->request->baseUrl.'/mensaje-novedad/delete?id='.$mensaje->id,['data-method'=>'post','class'=>'btn btn-danger btn-xs']);

                    ?>
				</td>
                
     			<td><?= $mensaje->mensaje?></td>
				<td><?= $mensaje->valorNovedad->novedadCategoriaVisita->nombre?></td>
				<td><?= $mensaje->valorNovedad->resultado->nombre?></td>
				<td><?= $mensaje->valorNovedad->novedadCategoriaVisita->categoriaVisita->nombre?></td>
              </tr>
        	 <?php 
        	 	endif;
        	 	endforeach; 
        	 ?>			 
	   
	   </tbody>
	 
	 </table>