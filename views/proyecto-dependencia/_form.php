<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\models\ProyectoDependencia */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="proyecto-dependencia-form">

    <?php $form = ActiveForm::begin(); ?>

   	<div class="row">
      <div class="col-md-6">
        <?= $form->field($model, 'nombre')->textInput() ?>   
      </div>

   		<div class="col-md-6">
   			<?= $form->field($model, 'ceco')->widget(Select2::classname(), [
		       
			   'data' => $data_dependencias,
				'options' => [
				'id' => 'dependencia',
				'placeholder' => 'Dependencia'							
			    ],
		    
		      ])
    		?>
   		</div>
   	</div>
    
    <div class="row">
   		<div class="col-md-6">
   			<?= $form->field($model, 'created_on')->widget(DateControl::classname(), [
				  'autoWidget'=>true,
				 'displayFormat' => 'php:Y-m-d',
				 'saveFormat' => 'php:Y-m-d',
				  'type'=>DateControl::FORMAT_DATE,
          'disabled'=>true
     
           ]);?>
		</div>

		<div class="col-md-6">
			<?= $form->field($model, 'fecha_apertura')->widget(DateControl::classname(), [
				  'autoWidget'=>true,
				 'displayFormat' => 'php:Y-m-d',
         'disabled'=>$model->isNewRecord?false:true,
				 'saveFormat' => 'php:Y-m-d',
				  'type'=>DateControl::FORMAT_DATE,
     
           ]);?>
		</div>
   	</div>

     <div class="row">
      <div class="col-md-6">
        <?= $form->field($model, 'fecha_inicio_trabajo')->widget(DateControl::classname(), [
          'autoWidget'=>true,
         'displayFormat' => 'php:Y-m-d',
         'saveFormat' => 'php:Y-m-d',
          'type'=>DateControl::FORMAT_DATE,
          //'disabled'=>true
     
           ]);?>
    </div>

    <div class="col-md-6">
      <?= $form->field($model, 'dias_trabajo')->textInput(['type'=>'number']) ?> 
    </div>
    </div>

   	<div class="row">
   		<div class="col-md-6">
   			<?= $form->field($model, 'solicitante')->textInput(['maxlength' => true,'readonly'=>'']) ?>		
   		</div>
      <div class="col-md-6">
      <label>Usuarios asignados</label>
        <?php
          if(!$model->isNewRecord){
            echo Select2::widget([
                'name' => 'usuarios[]',
                'data' => $model->Usuarios(),
                //'size' => Select2::SMALL,
                'value'=>$arrayUsuarios,
                'options' => ['placeholder' => 'Seleccionar ...', 'multiple' => true,'id'=>'usuarios'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
          }else{
            echo Select2::widget([
                'name' => 'usuarios[]',
                'data' => $model->Usuarios(),
                //'size' => Select2::SMALL,
                'options' => ['placeholder' => 'Seleccionar ...', 'multiple' => true,'id'=>'usuarios'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
          }
        ?>
      </div>

   		<!-- <div class="col-md-6">
   			<label>Provedores</label>
   			<?php 
          /*if(!$model->isNewRecord){
   				 echo Select2::widget([
	                    'name' => 'provedor[]',
	                    'data' => $model->Provedores(),
	                    //'size' => Select2::SMALL,
                      'value'=>$arrayProvedor,
	                    'options' => ['placeholder' => 'Selecciona Provedores ...', 'multiple' => true,'id'=>'provedor'],
	                    'pluginOptions' => [
	                        'allowClear' => true
	                    ],
                	]);

          }else{
            echo Select2::widget([
                      'name' => 'provedor[]',
                      'data' => $model->Provedores(),
                      //'size' => Select2::SMALL,
                      'options' => ['placeholder' => 'Selecciona Provedores ...', 'multiple' => true,'id'=>'provedor'],
                      'pluginOptions' => [
                          'allowClear' => true
                      ],
                  ]);
          }*/

   			?>
   		</div> -->
   	</div>

    
    
    <br>
    <div class="row">
      <div class="form-inline">
       
        <button class="btn btn-success " type="button" id="btn-agregar" title="Clic para agregar sistema"><i class="fa fa-check"></i></button>
       
        <div class="form-group col-md-8">
         
          <!-- <label>Sistemas a seguir</label> -->
          <?php 

            //if(!$model->isNewRecord){
              echo Select2::widget([
                        'name' => '',
                        'data' => $model->Sistemas(),
                        //'size' => Select2::SMALL,
                        'value'=>$arraySistema,
                        'options' => ['placeholder' => 'Selecciona sistemas a seguir ...', /*'multiple' => true,*/'id'=>'sistemas'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
           /* }else{
             echo Select2::widget([
                        'name' => 'sistemas[]',
                        'data' => $model->Sistemas(),
                        //'size' => Select2::SMALL,
                        'options' => ['placeholder' => 'Selecciona sistemas ...', 'multiple' => true,'id'=>'sistemas'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
            }*/

          ?>
        </div>
       
      </div>
    </div>
    <br>
    <div class="row">
       <div class="col-md-12">

        <table class="table table-striped" id="tbl_encargado">
          <thead>
            <tr>
              <th>Sistema</th>
              <th>Encargado</th>
              <th>Otro</th>
              <th>Finalizados</th>
              <th></th>
            </tr>
          </thead>
          <tbody >
            <?php if(!$model->isNewRecord){ ?>
              <?php foreach($proyectoSistema as $py): ?>
                <tr>
                  <td><?php echo  $py->sistema->nombre ?><input type="hidden" name="sistemas[]" value="<?php echo  $py->id_sistema ?>" ></td>
                  <td>
                    <select name='encargado[]' id='encargado_<?php echo  $py->id_sistema ?>' style="<?= $py->otro!=''?'display: none;':''; ?>">
                      <?php foreach($array_empresas as $nit=>$em):  ?>
                        <option value="<?= $nit?>" <?php echo $py->encargado==$nit?'selected':'' ?>><?= $em?></option>
                      <?php endforeach; ?>
                    </select>
                    <input type="text" name="otro[]" style="<?= $py->otro!=''?'':'display: none;'; ?>"" id="otro_<?php echo  $py->id_sistema ?>" value="<?= $py->otro?>">
                  </td>
                  <td>
                    <input type="checkbox" name="check_otro[]" onclick="activar_otro(this,<?php echo  $py->id_sistema ?>)" <?= $py->otro!=''?'checked':''; ?>>
                  </td>

                  <td>
                    <?php 
                      echo Select2::widget([
                          'name' => 'tipos_finalizado['.$py->id_sistema.'][]',
                          'data' => $array_finalizados,
                          //'size' => Select2::SMALL,
                          'value'=>$model->Get_finalizados_seleccionados($model->id,$py->id_sistema),
                          'options' => ['placeholder' => 'Selecciona una opcion ...', 'multiple' => true,'id'=>'tipo_finalizado_'.$py->id_sistema.''],
                          'pluginOptions' => [
                              'allowClear' => true
                          ],
                      ]);
                    ?>
                  </td>
                  <td>
                    <button class='btn btn-danger btn-xs' type='button' onclick='quitar(this);'><i class='fa fa-trash'></i></button>
                  </td>
                </tr>
              <?php endforeach; ?>

            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  
    <br>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
