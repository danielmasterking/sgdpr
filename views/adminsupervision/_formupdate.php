<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use marqu3s\summernote\Summernote;
use app\models\AdminDependencia;
use kartik\date\DatePicker;


$year=date('Y');

$admin_dep=AdminDependencia::find()->where('id_admin='.$model->id)->all();
$array=[];
$value_dep=[];
foreach ($admin_dep as $value) {
    $dep=$value->centro_costos_codigo;
    $array[$dep]=['disabled' => true];
    $value_dep[]=$value->centro_costos_codigo;
    // echo $value->centro_costos_codigo;
}

$cantidad=count($array);


/* @var $this yii\web\View */
/* @var $model app\models\AdminSupervision */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="admin-supervision-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        
        <div class="col-md-6">
            <?= $form->field($model, 'mes')->dropDownList([
            '01' => 'ENERO', 
            '02' => 'FEBRERO',
            '03' => 'MARZO',
            '04' => 'ABRIL',
            '05' => 'MAYO',
            '06' => 'JUNIO',
            '07' => 'JULIO',
            '08' => 'AGOSTO',
            '09' => 'SEPTIEMBRE',
            '10' => 'OCTUBRE',
            '11' => 'NOVIEMBRE',
            '12' => 'DICIEMBRE',
        ]) ?>
        </div>

        <div class="col-md-6">
            <?//= $form->field($model, 'ano')->textInput(['value' => $year,'maxlength' => true,'readonly'  => 'readonly']) ?>
            <?= $form->field($model, 'ano')->dropDownList([
            '2018' => '2018', 
            '2019' => '2019',
            '2020' => '2020' 
           
            ]) ?>
        </div>

       

    </div>


   


    
   

    <br>

    

    <div class="row">
        <div class="col-md-6">
            <label>Dependencias</label>
            <?php 
                echo Select2::widget([
                    'name' => 'dependencias[]',
                    'value'=>$value_dep,
                    'data' => $data_dependencias,
                    //'size' => Select2::SMALL,
                    'options' => ['placeholder' => 'Selecciona dependencias ...', 'multiple' => true,/*'options' =>$array,*/'id'=>'deps'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
        </div>

        <div class="col-md-6">
            <?php 
                echo $form->field($model, 'empresa')->widget(Select2::classname(), [
                'data' => $list_empresas,
                'options' => ['placeholder' => 'Selecciona Empresa ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Empresa');
            ?>
        </div>

    </div>
   

  
    <br>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script type="text/javascript">

$('#fecha_desde,#fecha_hasta').change(function(event) {
            var fecha_inicio=moment($('#fecha_desde').val());
            var fecha_final=moment($('#fecha_hasta').val());
            var dias=fecha_final.diff(fecha_inicio, 'days')+1;

            if(fecha_inicio>fecha_final){

                alert('La fecha final no puede ser menor a la fecha de inicio');

            }else if (!isNaN(dias)) {
                $('#total_dias').val(dias);
            }

    });

    $('#cantidad_serv').keyup(function(event) {
       var precio=$('#precio').val();

       if (precio!='') {

            var total=calcular($(this).val(),precio);
           // alert(total);
            $('#precio_total').val(total[0]);
            $('#precio_dep').val(total[1]);
       }
    });

    $('#precio').keyup(function(event) {
       var cantidad=$('#cantidad_serv').val();

       if (cantidad!='') {

            var total=calcular(cantidad,$(this).val());
            //alert(total);
            $('#precio_total').val(total[0]);
            $('#precio_dep').val(total[1]);
       }
    });


     $('#adminsupervision-horas').keyup(function(event) {
        var ftes= calcular_ftes($(this).val());
        $('#ftes_total').val(ftes[0]);
        $('#ftes_dep').val(ftes[1]);
     });

    function calcular(cantidad,precio_u){

        var total=parseInt(cantidad)*parseInt(precio_u);
        var count =$("#deps :selected").length;
        //alert(count);
        if (count>0) {

            var total_dep=(total/count);
        }else{
            var total_dep=0;
        }

        return [total,Number(Math.round(total_dep+'e2')+'e-2')];

    }

    function calcular_ftes(horas){

        var count = $("#deps :selected").length;
        //alert(count);
        if (count>0) {
            var horas_dep=(horas/count);
        }else{

            var horas_dep=0;
        }
        
        var total=(horas/8);
        var total_dep=(horas_dep/8);

        return [Number(Math.round(total+'e3')+'e-3'),Number(Math.round(total_dep+'e3')+'e-3')];
    }

    $('#deps').change(function(event) {
        //alert('entra');

         var ftes= calcular_ftes($('#adminsupervision-horas').val());
        $('#ftes_total').val(ftes[0]);
        $('#ftes_dep').val(ftes[1]);

        var cantidad=$('#cantidad_serv').val();
        var total=calcular(cantidad,$('#precio').val());
        $('#precio_dep').val(total[1]);

    });
 


 // $('#w1').change(function(event) {
    
 //    var count = $("#w1 :selected").length;

 //    if (count>0) {
 //        $('#precio').removeAttr('readonly');
 //        total(<?php //echo $cantidad ?>);
 //    }else{
 //        $('#precio').attr({
 //            readonly: true,
            
 //        });    

 //        $('#precio').val(''); 
 //        $('#precio_dep').val('');   
 //    }
 // });

 // function total(cant){

 //    var count = parseInt(cant)+parseInt($("#w1 :selected").length);


 //    if (count>0) {
 //        var total_dep=($('#precio').val()/parseInt(count));
 //        var valor=Number(Math.round(total_dep+'e2')+'e-2');
 //        $('#precio_dep').val(valor);
 //    }else{

 //        $('#precio_dep').val('');
 //    }
 //}


</script>
