<?php 

use yii\helpers\Html;
use yii\helpers\Url;

?>



<table class=" " >
   		<thead>
   			<th></th>
   			<th>Dependencia</th>
   			<!-- <th>Regional</th> -->
   			<th>Ciudad</th>
   			<th>Calif Anual %</th>
   		</thead>
   		<tbody>
   			<?php 
   			
   			foreach($dependencias as $rows_dep):
   				
   			?>
   			<tr>
   				<td>
   					<?php echo Html::a('<i class="fa fa-eye"></i>',Yii::$app->request->baseUrl.'/centro-costo/visita?id='.$rows_dep['codigo'],['target'=>'_blank']); ?>
   				</td>
   				<td><?= $rows_dep['nombre']?></td>
   				<!-- <td><?= $rows_dep['regional']?></td> -->
   				<td><?= $rows_dep['ciudad']?></td>
   				<td>
   					<?php 
   						$calif_ano=0;
			            foreach ($arr_meses as $key_mes => $value_mes) {
			            

			                $num_visita= $model_visita->Num_visitas($key_mes,$rows_dep['codigo'],$fecha_inicio,$fecha_final);

			                if ($num_visita==0) {
			                    $calif=0;

			                }elseif($num_visita>=2){

			                   $calif=100;

			                }elseif($num_visita<2){

			                    $calif=50;
			                }


			                $calif_mes=round(($calif*8.33)/100, 2, PHP_ROUND_HALF_DOWN);

			                $calif_ano+=$calif_mes;
			            }

			            echo "<span class='text-danger'>".$calif_ano." % </span>";
   					?>
   				</td>

   			</tr>
   			<?php endforeach;?>
   		</tbody>
   </table>