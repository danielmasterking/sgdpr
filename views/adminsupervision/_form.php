<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use marqu3s\summernote\Summernote;
use kartik\date\DatePicker;
use kartik\widgets\TimePicker;

$year=date('Y');
/* @var $this yii\web\View */
/* @var $model app\models\AdminSupervision */
/* @var $form yii\widgets\ActiveForm */

$data_zona=[];

foreach ($zonasUsuario as $row) {
    
    $data_zona[$row->zona_id]=$row->zona->nombre;
}

foreach ($empresasUsuario as $value) {
    $empresa= $value->nit;
}

?>

<div class="admin-supervision-form">

    <?php $form = ActiveForm::begin(['id'=>'form']); ?>

    <div class="row">
        
        <div class="col-md-4">
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

        <div class="col-md-4">
            <?//= $form->field($model, 'ano')->textInput(['value' => $year,'maxlength' => true,'readonly'  => 'readonly']); ?>
            <?= $form->field($model, 'ano')->dropDownList([
            '2018' => '2018', 
            '2019' => '2019',
            '2020' => '2020' 
           
            ]) ?>
        </div>

         <div class="col-md-4">
            <label>Dispositivo</label>
            <?php 
                echo Select2::widget([
                    'name' => 'dispositivo',
                    'data' => $list_disp,
                    //'size' => Select2::SMALL,
                    'options' => ['placeholder' => 'Selecciona un dispositivo ...',/* 'multiple' => true,*/'id'=>'disp'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
        </div>




       <!--  <div class="col-md-4">
            <?php //echo $form->field($model, 'descripcion')->textInput(['required'=>true]) ?>

        </div> -->
    
    </div>


    


    <!-- <div class="row">
        <div class="col-md-4">
           
            <?= $form->field($model, 'horas')->textInput([]) ?>
        </div>

         <div class="col-md-4">
            <?= $form->field($model, 'ftes')->textInput(['readonly'  => 'readonly','id'=>'ftes_total']) ?>
        </div>

        <div class="col-md-4">
            <label>Ftes por dependencia</label>
            <input type="text" name="ftes_dep" id="ftes_dep" class="form-control" readonly="">
        </div>

    </div> -->


    <!-- <label>Dias Prestacion del servicio</label>

    <br><br>

    <div class="row">
        <div class="col-md-1" align="center">
           
            <?= $form->field($model, 'lunes')->checkbox(['class' => 'dias_sem']); ?>
        </div>
        <div class="col-md-1" align="center">
           
            <?= $form->field($model, 'martes')->checkbox(['class' => 'dias_sem',]); ?>
        </div>
        <div class="col-md-2" align="center">
            
            <?= $form->field($model, 'miercoles')->checkbox(['class' => 'dias_sem',]); ?>
        </div>
        <div class="col-md-1" align="center">
            
            <?= $form->field($model, 'jueves')->checkbox(['class' => 'dias_sem',]); ?>
        </div>
        <div class="col-md-2" align="center">
            
            <?= $form->field($model, 'viernes')->checkbox(['class' => 'dias_sem',]); ?>
        </div>
        <div class="col-md-1" align="center">
            
            <?= $form->field($model, 'sabado')->checkbox(['class' => 'dias_sem',]); ?>
        </div>
        <div class="col-md-2" align="center">
            
            <?= $form->field($model, 'domingo')->checkbox(['class' => 'dias_sem',]); ?>
        </div>
        <div class="col-md-1" align="center">
            
            <?= $form->field($model, 'festivo')->checkbox(['class' => 'dias_sem',]); ?>
        </div>
    </div> -->


    
   

    <!-- <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'cantidad')->textInput(['id'=>'cantidad_serv','value'=>1]) ?>

        </div>


        <div class="col-md-3">
            <label>Precio Unitario</label>
            <div class="input-group">
                <span class="input-group-addon" id="basic-addon1"><i class="fa fa-usd"></i></span>
                <input type="number" name="precio_uni" class="form-control" placeholder="$0" aria-describedby="basic-addon1" id="precio"  required="">
            </div>
        </div>

        <div class="col-md-3">
            <label>Precio Total</label>
            <div class="input-group">
                <span class="input-group-addon" id="basic-addon1"><i class="fa fa-usd"></i></span>
                <input type="number" name="precio_total" class="form-control" placeholder="$0" aria-describedby="basic-addon1" id="precio_total" readonly="" required="">
            </div>
        </div>

        <div class="col-md-3">
            <label>Precio dependencia</label>
            <div class="input-group">
                <span class="input-group-addon" id="basic-addon1"><i class="fa fa-usd"></i></span>
                <input type="number" name="precio_dep" class="form-control" placeholder="$0" aria-describedby="basic-addon1" id="precio_dep" readonly="" required="">
            </div>
        </div>
    </div>
 -->
    <br>

    <div class="row">

        <div class="col-md-4">
            <label>Regional</label>
            <?php 
                echo Select2::widget([
                    'name' => 'zona[]',
                    'data' => $data_zona,
                    //'size' => Select2::SMALL,
                    'options' => ['placeholder' => 'Selecciona Regional ...',/* 'multiple' => true,*/'id'=>'zona'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
        </div>

        <div class="col-md-4">
            <label>Dependencias</label>
            <?php 
                echo Select2::widget([
                    'name' => 'dependencias[]',
                    'data' => $data_dependencias,
                    //'size' => Select2::SMALL,
                    'options' => ['placeholder' => 'Selecciona dependencias ...', 'multiple' => true,'id'=>'deps'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
        </div>

        <div class="col-md-4">
            <?php 
                echo $form->field($model, 'empresa')->widget(Select2::classname(), [
                'data' => $list_empresas,
                'options' => ['placeholder' => 'Selecciona Empresa ...'/*,'required'=>true*/,'value'=>$empresa,'id'=>'empresa'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])/*->label(false)*/;
            ?>
        </div>

    </div>


    <!-- <br>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'detalle')->widget(Summernote::className(), [
                'clientOptions' => [
                   
                ]
            ]); ?>
        </div>
    </div> -->
    
    <br>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script type="text/javascript">

    $('#zona').change(function(event) {
        $.ajax({
            url:"<?php echo Yii::$app->request->baseUrl . '/adminsupervision/dependenciaszona'; ?>",
            type:'POST',
            dataType:"json",
            cache:false,
            async:false,
            data: {
                zona: $(this).val(),
                empresa:$('#empresa option:selected').val()
                
            },
            beforeSend:  function() {
                //$('#body_ayuda').html('Cambiando... <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>');
            },
            success: function(data){
               $('#deps').html(data.resp);
            }
        });
        
   });


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
        var count = $("#deps :selected").length;
        if (count>0) {

            var total_dep=(total/count);
        }else{
            var total_dep=0;
        }

        return [total,Number(Math.round(total_dep+'e2')+'e-2')];

    }

    function calcular_ftes(horas){

        var count = $("#deps :selected").length;

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


    $("#disp").change(function(event) {
        if ($(this).val()!='') {
            $('#deps,#empresa,#zona').attr({
                disabled: true
            });
        }else{

            $('#deps,#empresa,#zona').removeAttr('disabled');
        }
    });
 // $('#precio').change(function(event) {
    
 //    total();
 // }).keyup(function(event) {
 //     total();
 // });;


 // $('#w1').change(function(event) {
    
 //    var count = $("#w1 :selected").length;

 //    if (count>0) {
 //        $('#precio').removeAttr('readonly');
 //        total();
 //    }else{
 //        $('#precio').attr({
 //            readonly: true,
            
 //        });    

 //        $('#precio').val(''); 
 //        $('#precio_dep').val('');   
 //    }
 // });

 // function total(){

 //    var count = $("#w1 :selected").length;


 //    if (count>0) {
 //        var total_dep=($('#precio').val()/parseInt(count));
 //        var valor=Number(Math.round(total_dep+'e2')+'e-2');
 //        $('#precio_dep').val(valor);
 //    }else{

 //        $('#precio_dep').val('');
 //    }
 // }


</script>
