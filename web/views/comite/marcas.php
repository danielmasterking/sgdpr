<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Comités';


?>
    <?= $this->render('_tabs',['marcas' => $marcas]) ?>

        
	 <table  class="display my-data" data-page-length='20' cellspacing="0" width="100%">
	 
       <thead>

       <tr>
           
           <th></th>
		   <th>Codigo</th>
           <th>Fecha</th>
		   <th>Tipo</th>
		   <th>Observaciones</th>
          
       </tr>
           

       </thead>	 
	   
	   <tbody>
	   
             <?php foreach($comites as $comite):?>	  
			   
              <tr>			   
			   <td><?php
			   echo Html::a('<i class="fa fa-eye"></i>',Yii::$app->request->baseUrl.'/comite/view?id='.$comite->comite_id,['title'=>'ver']);
              // echo Html::a('<i class="fa fa-pencil fa-fw"></i>',Yii::$app->request->baseUrl.'/capacitacion/update?id='.$capacitacion->capacitacion_id);
               //echo Html::a('<i class="fa fa-remove"></i>',Yii::$app->request->baseUrl.'/capacitacion/delete?id='.$capacitacion->capacitacion_id,['data-method'=>'post']);

                    ?>
				</td>
                <td><?= $comite->comite_id?></td>
     			<td><?= $comite->comite->fecha?></td>
				<td><?= $comite->comite->novedad->nombre?></td>
				<td><?= $comite->comite->observaciones?></td>
				
              </tr>
        	 <?php endforeach; ?>			 
	   
	   </tbody>
	 
	 </table>