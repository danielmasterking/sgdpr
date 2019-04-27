<?php 

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use marqu3s\summernote\Summernote;
use kartik\widgets\TimePicker;

$this->title = 'Editar-'.$model->descripcion;

$hora_inicio_nocturna='00:00';
$hora_fin_nocturna='00:00';
$hora_inicio_diurna='00:00';
$hora_fin_diurna='00:00';
foreach ($jornada as $key) {
    if ($key->nocturna=='S') {
        $hora_inicio_nocturna=$key->hora_inicio;
        $hora_fin_nocturna=$key->hora_fin;
    }else if ($key->nocturna=='N') {
        $hora_inicio_diurna=$key->hora_inicio;
        $hora_fin_diurna=$key->hora_fin;
    }
}

//$display=$model->ftes==0?'style="display:none;"':'';
$checked_si=$model->ftes!=0?'checked':'';
$checked_no=$model->ftes==0?'checked':'';
?>

<?= Html::a('<i class="fa fa-arrow-left"></i> Volver ',Yii::$app->request->baseUrl.'/adminsupervision/view?id='.$view, ['class'=>'btn btn-primary']) ?>


<h1 class="text-center"><?= Html::encode($this->title) ?></h1>


<?php $form = ActiveForm::begin(['id'=>'form']); ?>

<label>Ftes - Horas</label>
<label class="radio-inline"><input type="radio" name="optradio" <?= $checked_si?> onclick="ftes_horas('S')" value="S" id="check_si">Si </label>
<label class="radio-inline"><input type="radio" name="optradio" <?= $checked_no?> onclick="ftes_horas('N')" value="N" id="check_no">No</label>

<div class="row">
    <div class="col-md-4 ftes-horas" <?//= $display?> >
        <?=$form->field($model, 'horas')->widget(TimePicker::classname(), [
        'readonly'=>true,
        'pluginOptions' => [
            //'showSeconds' => true,
            'showMeridian' => false,
            'minuteStep' => 1,
            'secondStep' => 5,
            'defaultTime' => '00:00:00',
        ]])?>
    </div>

    <div class="col-md-4 ftes-horas" <?//= $display?>>
        <?=$form->field($model, 'hora_inicio')->widget(TimePicker::classname(), [
        'readonly'=>true,
        'pluginOptions' => [
            //'showSeconds' => true,
            'showMeridian' => false,
            'minuteStep' => 1,
            'secondStep' => 5,
            'defaultTime' => '00:00:00',
        ]])?>
    </div>
    <div class="col-md-4 ftes-horas" align="center" <?//= $display?>>
        <label>Hasta</label>
        <div id="hora_fin">00:00</div>
    </div>
</div>

<div class="row">
	<div class="col-md-4">
		<?= $form->field($model, 'descripcion')->textInput([]) ?>
	</div>

	

	<div class="col-md-2 ftes-horas" <?//= $display?>>
		<?= $form->field($model, 'ftes')->textInput(['readonly'  => 'readonly','id'=>'ftes_total']) ?>
	</div>


	<div class="col-md-2 ftes-horas" <?//= $display?>>
		<?= $form->field($model, 'ftes_dependencia')->textInput(['readonly'  => 'readonly','id'=>'ftes_dep']) ?>
	</div>

    <div class="col-md-2 ftes-horas">
        <?= $form->field($model, 'ftes_diurno_dep')->textInput(['readonly'  => 'readonly','id'=>'ftes_diurno_dep']) ?>
    </div>

    <div class="col-md-2 ftes-horas">
        <?= $form->field($model, 'ftes_nocturno_dep')->textInput(['readonly'  => 'readonly','id'=>'ftes_nocturno_dep']) ?>
    </div>

</div>

	<label>Dias Prestacion del servicio</label>

    <br>

    <div class="row">
        <div class="col-md-1" align="center">

            <?php   
                $check=$model->lunes=='X'?true:false;
                echo Html::checkbox('lunes', $check, ['label' => 'Lunes','class' => 'dias_sem','id'=>'admindispositivo-lunes']);
            ?>
            <?//= $form->field($model, 'lunes')->checkbox(['class' => 'dias_sem','checked'=>'true']); ?>
        </div>
        <div class="col-md-1" align="center">
            <?php   
                $check=$model->martes=='X'?true:false;
                echo Html::checkbox('martes', $check, ['label' => 'Martes','class' => 'dias_sem','id'=>'admindispositivo-martes']);
            ?>
            <?//= $form->field($model, 'martes')->checkbox(['class' => 'dias_sem','checked'=>'']); ?>
        </div>
        <div class="col-md-2" align="center">
            <?php   
                $check=$model->miercoles=='X'?true:false;
                echo Html::checkbox('miercoles', $check, ['label' => 'Miercoles','class' => 'dias_sem','id'=>'admindispositivo-miercoles']);
            ?>
            <?//= $form->field($model, 'miercoles')->checkbox(['class' => 'dias_sem','checked'=>'']); ?>
        </div>
        <div class="col-md-1" align="center">
            <?php   
                $check=$model->jueves=='X'?true:false;
                echo Html::checkbox('jueves', $check, ['label' => 'Jueves','class' => 'dias_sem','id'=>'admindispositivo-jueves']);
            ?>
            <?//= $form->field($model, 'jueves')->checkbox(['class' => 'dias_sem','checked'=>'']); ?>
        </div>
        <div class="col-md-2" align="center">
            <?php   
                $check=$model->viernes=='X'?true:false;
                echo Html::checkbox('viernes', $check, ['label' => 'Viernes','class' => 'dias_sem','id'=>'admindispositivo-viernes']);
            ?>
            <?//= $form->field($model, 'viernes')->checkbox(['class' => 'dias_sem','checked'=>'']); ?>
        </div>
        <div class="col-md-1" align="center">
             <?php   
                $check=$model->sabado=='X'?true:false;
                echo Html::checkbox('sabado', $check, ['label' => 'Sabado','class' => 'dias_sem','id'=>'admindispositivo-sabado']);
            ?>
            <?//= $form->field($model, 'sabado')->checkbox(['class' => 'dias_sem','checked'=>'']); ?>
        </div>
        <div class="col-md-2" align="center">
            <?php   
                $check=$model->domingo=='X'?true:false;
                echo Html::checkbox('domingo', $check, ['label' => 'Domingo','class' => 'dias_sem','id'=>'admindispositivo-domingo']);
            ?>
            <?//= $form->field($model, 'domingo')->checkbox(['class' => 'dias_sem','checked'=>'']); ?>
        </div>
        <div class="col-md-1" align="center">
            <?php   
                $check=$model->festivo=='X'?true:false;
                echo Html::checkbox('festivo', $check, ['label' => 'Festivo','class' => 'dias_sem','id'=>'admindispositivo-festivo']);
            ?>
            <?//= $form->field($model, 'festivo')->checkbox(['class' => 'dias_sem','checked'=>'']); ?>
        </div>
    </div>


    <div class="row">

    	<div class="col-md-3">
    		<?= $form->field($model, 'cantidad')->textInput(['id'=>'cantidad_serv'/*,'value'=>1*/]) ?>
    	</div>

    	
    	<div class="col-md-3">
            <label>Precio Unitario</label>
            <div class="input-group">
                <span class="input-group-addon" id="basic-addon1"><i class="fas fa-dollar-sign"></i></span>
                <input type="number" name="precio_uni" class="form-control" placeholder="$0" aria-describedby="basic-addon1" id="precio"  required="" value="<?=$model->precio_unitario?>">
            </div>
        </div>

        <div class="col-md-3">
            <label>Precio Total</label>
            <div class="input-group">
                <span class="input-group-addon" id="basic-addon1"><i class="fas fa-dollar-sign"></i></i></span>
                <input type="number" name="precio_total" class="form-control" placeholder="$0" aria-describedby="basic-addon1" id="precio_total" readonly="" required="" value="<?=$model->precio_total?>">
            </div>
        </div>

        <div class="col-md-3">
            <label>Precio dependencia</label>
            <div class="input-group">
                <span class="input-group-addon" id="basic-addon1"><i class="fas fa-dollar-sign"></i></span>
                <input type="number" name="precio_dep" class="form-control" placeholder="$0" aria-describedby="basic-addon1" id="precio_dep" readonly="" required=""  value="<?=$model->precio_dependencia?>">
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'detalle')->widget(Summernote::className(), [
                'clientOptions' => [
                   
                ]
            ]); ?>
        </div>
    </div>


    <div class="form-group">
        <?php //echo  Html::submitButton($model->isNewRecord ? 'Crear' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
        <button class="btn btn-primary btn-lg" onclick="validar();" type="button">Guardar</button>
    </div>



<?php ActiveForm::end(); ?>

<script type="text/javascript">
        /*$('input[type=checkbox]').attr({
            checked: '',
            
        });*/

        var dias_prestados=0;
        $('.dias_sem').each(function( index ) {
            var txt=$(this).attr('id');
            if($(this).is(':checked')){

                if(txt=="admindispositivo-festivo"){
                    dias_prestados=dias_prestados+2;
                }else{
                    dias_prestados=dias_prestados+4;
                }

            }
        });
        //alert(dias_prestados);
    

   
    $(".dias_sem").on('change',function(){
        var txt=$(this).attr('id');
        switch(txt) {
            case "admindispositivo-lunes":
                if($(this).is(':checked')){dias_prestados=dias_prestados+4;}else{dias_prestados=dias_prestados-4;}
                break;
            case "admindispositivo-martes":
                if($(this).is(':checked')){dias_prestados=dias_prestados+4;}else{dias_prestados=dias_prestados-4;}
                break;
            case "admindispositivo-miercoles":
                if($(this).is(':checked')){dias_prestados=dias_prestados+4;}else{dias_prestados=dias_prestados-4;}
                break;
            case "admindispositivo-jueves":
                if($(this).is(':checked')){dias_prestados=dias_prestados+4;}else{dias_prestados=dias_prestados-4;}
                break;
            case "admindispositivo-viernes":
                if($(this).is(':checked')){dias_prestados=dias_prestados+4;}else{dias_prestados=dias_prestados-4;}
                break;
            case "admindispositivo-sabado":
                if($(this).is(':checked')){dias_prestados=dias_prestados+4;}else{dias_prestados=dias_prestados-4;}
                break;
            case "admindispositivo-domingo":
                if($(this).is(':checked')){dias_prestados=dias_prestados+4;}else{dias_prestados=dias_prestados-4;}
                break;
            case "admindispositivo-festivo":
                if($(this).is(':checked')){dias_prestados=dias_prestados+2;}else{dias_prestados=dias_prestados-2;}
                break;
        }
        $('#dias_prestados').html(dias_prestados);
        calcularHoraFin()
    });


    /*var check_no=$('#check_no');

    if(check_no.is(':checked')){*/
        $("#admindispositivo-horas").on('change',function(){
            calcularHoraFin()
        });

        $("#admindispositivo-hora_inicio").on('change',function(){
            calcularHoraFin()
        });

        $("#cantidad_serv").on('keyup',function(){
            calcularFtes()
        });
    //}

    
    
    function calcularHoraFin(){
        var hora_sumar = moment.utc($("#admindispositivo-horas").val(), "hh-mm").hour();
        var minuto_sumar = moment.utc($("#admindispositivo-horas").val(), "hh-mm").minute();
        var hora_final = moment.utc($("#admindispositivo-hora_inicio").val(), "hh-mm");
        hora_final.add(hora_sumar, 'hours').add(minuto_sumar, 'minutes');
        $("#hora_fin").html(hora_final.format("HH:mm"))
        calcularHorasDiurnasNocturnas()
    }

    var inicio_nocturno='<?=$hora_inicio_nocturna?>'//'22:00';
    var fin_nocturno='<?=$hora_fin_nocturna?>'//'05:59';
    var inicio_diurno='<?=$hora_inicio_diurna?>'//'06:00';
    var fin_diurno='<?=$hora_fin_diurna?>'//'21:59';
    var hora_inicio='';
    var hora_fin='';
    var tiempo_diurna='';
    var tiempo_nocturno='';
    var tiempo_diurna2=0;
    var tiempo_nocturno2=0;
    var hora24='24:00';
    var hora00='00:00';


    function calcularHorasDiurnasNocturnas(){
        var jornada=moment.utc($("#admindispositivo-hora_inicio").val(), "hh:mm");
        hora_inicio=moment.utc($("#admindispositivo-hora_inicio").val(), "hh:mm");
        hora_fin=moment.utc($("#hora_fin").html(), "hh:mm");
        inicio_nocturno=moment.utc(inicio_nocturno, "hh:mm");
        fin_nocturno=moment.utc(fin_nocturno, "hh:mm");
        inicio_diurno=moment.utc(inicio_diurno, "hh:mm");
        fin_diurno=moment.utc(fin_diurno, "hh:mm");
        tiempo_nocturno=moment.utc('00:00', "hh-mm")
        tiempo_diurna=moment.utc('00:00', "hh-mm")
        hora00=moment.utc(hora00, "hh:mm");
        hora24=moment.utc(hora24, "hh:mm");
        var hi=hora_inicio;//.hour();
        //console.log(hi)
        var hf=hora_fin;//.hour();
        //console.log(hf)
        var muere=false;
        if (hi.isSameOrAfter(hora00) && hi.isBefore(inicio_diurno)) {// desde las 00 a las 06
            if (hf.isSameOrAfter(hora00) && hf.isBefore(inicio_diurno) && hi.isBefore(hf)) {
                tiempo_nocturno2=hora_inicio.diff(hora_fin);
                tiempo_nocturno.add(0+Math.abs(moment.duration(tiempo_nocturno2).hours()), 'hours')
                    .add(0+Math.abs(moment.duration(tiempo_nocturno2).minutes()), 'minutes');
                //console.log("+N 0-6= "+tiempo_nocturno.format("HH:mm"))
                muere=true;
            }else{
                tiempo_nocturno2=hora_inicio.diff(inicio_diurno);
                tiempo_nocturno.add(0+Math.abs(moment.duration(tiempo_nocturno2).hours()), 'hours')
                    .add(0+Math.abs(moment.duration(tiempo_nocturno2).minutes()), 'minutes');
                //console.log("+N 0-6= "+tiempo_nocturno.format("HH:mm"))
            }
        }else if (hi.isSameOrAfter(inicio_diurno) && hi.isBefore(inicio_nocturno)) {// desde las 06 hasta las 21
            if (hf.isSameOrAfter(inicio_diurno) && hf.isBefore(inicio_nocturno) && hi.isBefore(hf)) {
                tiempo_diurna2=hora_inicio.diff(hora_fin);
                tiempo_diurna.add(0+Math.abs(moment.duration(tiempo_diurna2).hours()), 'hours')
                        .add(0+Math.abs(moment.duration(tiempo_diurna2).minutes()), 'minutes');
                //console.log("+D 6= "+tiempo_diurna.format("HH:mm"))
                muere=true;
            }else{
                tiempo_diurna2=hora_inicio.diff(inicio_nocturno);
                tiempo_diurna.add(0+Math.abs(moment.duration(tiempo_diurna2).hours()), 'hours')
                        .add(0+Math.abs(moment.duration(tiempo_diurna2).minutes()), 'minutes');
                //console.log("+D 6= "+tiempo_diurna.format("HH:mm"))
            }
        }else if (hi.isSameOrAfter(inicio_nocturno) && hi.isBefore(hora24)) {// desde las 21 a las 00
            if(hf.isSameOrAfter(inicio_nocturno) && hf.isBefore(hora24) && hi.isBefore(hf)){
                tiempo_nocturno2=hora_inicio.diff(hora_fin);
                tiempo_nocturno.add(0+Math.abs(moment.duration(tiempo_nocturno2).hours()), 'hours')
                        .add(0+Math.abs(moment.duration(tiempo_nocturno2).minutes()), 'minutes');
                muere=true;
                //console.log("+N 22= "+tiempo_nocturno.format("HH:mm"))
            }else{
                tiempo_nocturno2=hora_inicio.diff(hora24);
                tiempo_nocturno.add(0+Math.abs(moment.duration(tiempo_nocturno2).hours()), 'hours')
                        .add(0+Math.abs(moment.duration(tiempo_nocturno2).minutes()), 'minutes');
                //console.log("+N 22= "+tiempo_nocturno.format("HH:mm"))
            }
        }
        //buscando la hora final
        if (!muere) {
            if (hf.isSameOrAfter(hora00) && hf.isBefore(inicio_diurno)) {// desde las 00 a las 06
                if(hi.isSameOrAfter(hora00) && hi.isBefore(inicio_diurno)){
                    //sumar diurno y de 21 a 24
                    tiempo_diurna2=inicio_diurno.diff(inicio_nocturno);
                    tiempo_diurna.add(0+Math.abs(moment.duration(tiempo_diurna2).hours()), 'hours')
                            .add(0+Math.abs(moment.duration(tiempo_diurna2).minutes()), 'minutes');
                    //console.log("+D muere 0-6= "+tiempo_diurna.format("HH:mm"))
                    tiempo_nocturno2=inicio_nocturno.diff(hora24);
                    tiempo_nocturno.add(0+Math.abs(moment.duration(tiempo_nocturno2).hours()), 'hours')
                        .add(0+Math.abs(moment.duration(tiempo_nocturno2).minutes()), 'minutes');
                    //console.log("+N muere 0-6= "+tiempo_nocturno.format("HH:mm"))
                }else if(hi.isSameOrAfter(inicio_diurno) && hi.isBefore(inicio_nocturno)){
                    //sumar de 22 a 24
                    tiempo_nocturno2=inicio_nocturno.diff(hora24);
                    tiempo_nocturno.add(0+Math.abs(moment.duration(tiempo_nocturno2).hours()), 'hours')
                        .add(0+Math.abs(moment.duration(tiempo_nocturno2).minutes()), 'minutes');
                    //console.log("+N muere 0-6= "+tiempo_nocturno.format("HH:mm"))
                }
                tiempo_nocturno2=hora00.diff(hora_fin);
                tiempo_nocturno.add(0+Math.abs(moment.duration(tiempo_nocturno2).hours()), 'hours')
                    .add(0+Math.abs(moment.duration(tiempo_nocturno2).minutes()), 'minutes');
                //console.log("+N muere 0-6= "+tiempo_nocturno.format("HH:mm"))
            }else if (hf.isSameOrAfter(inicio_diurno) && hf.isBefore(inicio_nocturno)) {//desde las 06 a las 21
                if(hi.isSameOrAfter(inicio_diurno) && hi.isBefore(inicio_nocturno)){
                    //sumar de 22 a 24 y de 0 a 6
                    tiempo_nocturno2=inicio_nocturno.diff(hora24);
                    tiempo_nocturno.add(0+Math.abs(moment.duration(tiempo_nocturno2).hours()), 'hours')
                        .add(0+Math.abs(moment.duration(tiempo_nocturno2).minutes()), 'minutes');
                    //console.log("+N muere 6-22= "+tiempo_nocturno.format("HH:mm"))
                    tiempo_nocturno2=hora00.diff(inicio_diurno);
                    tiempo_nocturno.add(0+Math.abs(moment.duration(tiempo_nocturno2).hours()), 'hours')
                        .add(0+Math.abs(moment.duration(tiempo_nocturno2).minutes()), 'minutes');
                    //console.log("+N muere 6-22= "+tiempo_nocturno.format("HH:mm"))
                }else if(hi.isSameOrAfter(inicio_nocturno) && hi.isBefore(hora24)){
                    //sumar de 0 a 6
                    tiempo_nocturno2=hora00.diff(inicio_diurno);
                    tiempo_nocturno.add(0+Math.abs(moment.duration(tiempo_nocturno2).hours()), 'hours')
                        .add(0+Math.abs(moment.duration(tiempo_nocturno2).minutes()), 'minutes');
                    //console.log("+N muere 6-22= "+tiempo_nocturno.format("HH:mm"))
                }
                tiempo_diurna2=inicio_diurno.diff(hora_fin);
                tiempo_diurna.add(0+Math.abs(moment.duration(tiempo_diurna2).hours()), 'hours')
                        .add(0+Math.abs(moment.duration(tiempo_diurna2).minutes()), 'minutes');
                //console.log("+D muere 6-22= "+tiempo_diurna.format("HH:mm"))
            }else if (hf.isSameOrAfter(inicio_nocturno) && hf.isBefore(hora24)) {//desde las 21 a las 00
                if(hi.isSameOrAfter(inicio_nocturno) && hi.isBefore(hora24)){
                    //sumar de 0 a 6 y sumar diurno
                    tiempo_nocturno2=hora00.diff(inicio_diurno);
                    tiempo_nocturno.add(0+Math.abs(moment.duration(tiempo_nocturno2).hours()), 'hours')
                        .add(0+Math.abs(moment.duration(tiempo_nocturno2).minutes()), 'minutes');
                    //console.log("+N muere 22-24= "+tiempo_nocturno.format("HH:mm"))
                    tiempo_diurna2=inicio_diurno.diff(inicio_nocturno);
                    tiempo_diurna.add(0+Math.abs(moment.duration(tiempo_diurna2).hours()), 'hours')
                            .add(0+Math.abs(moment.duration(tiempo_diurna2).minutes()), 'minutes');
                    //console.log("+D muere 22-24= "+tiempo_diurna.format("HH:mm"))
                }else if(hi.isSameOrAfter(hora00) && hi.isBefore(inicio_diurno)){
                    // sumar diurno
                    tiempo_diurna2=inicio_diurno.diff(inicio_nocturno);
                    tiempo_diurna.add(0+Math.abs(moment.duration(tiempo_diurna2).hours()), 'hours')
                            .add(0+Math.abs(moment.duration(tiempo_diurna2).minutes()), 'minutes');
                    //console.log("+D muere 22-24= "+tiempo_diurna.format("HH:mm"))
                }
                tiempo_nocturno2=inicio_nocturno.diff(hora_fin);
                tiempo_nocturno.add(0+Math.abs(moment.duration(tiempo_nocturno2).hours()), 'hours')
                    .add(0+Math.abs(moment.duration(tiempo_nocturno2).minutes()), 'minutes');
                //console.log("+N muere 22= "+tiempo_nocturno.format("HH:mm"))
            }
        }
        //rectificacion por reseteo de calculos hasta 24 horas
        if (jornada.isSame(hora00) && (hi.duration().asMinutes()-hf.duration().asMinutes())==0) {
            tiempo_nocturno=moment.utc('00:00', "hh-mm")
            tiempo_diurna=moment.utc('00:00', "hh-mm")
            tiempo_nocturno.add(8, 'hours')
                    .add(0, 'minutes');
            tiempo_diurna.add(16, 'hours')
                    .add(0, 'minutes');
        }
        calcularFtes();
        console.log("D= "+tiempo_diurna.format("HH:mm"));
        console.log("N= "+tiempo_nocturno.format("HH:mm"));
    }

       var ftes_diurno=<?= $model->ftes_diurno?>;var ftes_nocturno=<?= $model->ftes_nocturno?>;
    function calcularFtes(){
        
        var dias=parseInt(dias_prestados)
        var cantidad=parseInt($('#cantidad_serv').val())
       
        //ftes_diurno=(((tiempo_diurna.hours()+(tiempo_diurna.minutes()/60))*dias_prestados*cantidad)/240);
       ftes_diurno=(((tiempo_diurna.hours()+(tiempo_diurna.minutes()/60))*dias/**cantidad*/)/240);

       //ftes_nocturno=(((tiempo_nocturno.hours()+(tiempo_nocturno.minutes()/60))*dias_prestados*cantidad)/240);

       ftes_nocturno=(((tiempo_nocturno.hours()+(tiempo_nocturno.minutes()/60))*dias/**cantidad*/)/240);
       
        //var total_ftes=((ftes_diurno + ftes_nocturno)*parseInt($('#modeloprefactura-porcentaje').val()))/100;
       
       
        var str_diurno=ftes_diurno.toString();
        if(str_diurno.indexOf('.') != -1){
            var arr_diurno=str_diurno.split(".");
            var cant_diurno=arr_diurno[1].length;

            if(cant_diurno>3){
                //ftes_diurno=Number(Math.round(ftes_diurno+'e3')+'e-3')
                ftes_diurno=ftes_diurno;
                ftes_diurno=myRound(ftes_diurno,3);
            }


        // console.log('str_diurno:'+cant_diurno);
        }

        var str_nocturno=ftes_nocturno.toString();
        if(str_nocturno.indexOf('.') != -1){
            var arr_nocturno=str_nocturno.split(".");
            var cant_nocturno=arr_nocturno[1].length;

            if(cant_nocturno>3){
                //ftes_nocturno=Number(Math.round(ftes_nocturno+'e3')+'e-3')
                ftes_nocturno=ftes_nocturno;
                ftes_nocturno=myRound(ftes_nocturno,3);
            }


         //console.log('str_diurno:'+cant_nocturno);
        }

        

        /*console.log("ftes_diurno= "+ftes_diurno)
        console.log("ftes_nocturno= "+ftes_nocturno)
        console.log("*******************************")*/
        
        // ftes_diurno=Number(Math.round(ftes_diurno+'e2')+'e-2')
        // ftes_nocturno=Number(Math.round(ftes_nocturno+'e2')+'e-2')

        var total_ftes=(ftes_diurno + ftes_nocturno);
        total_ftes=myRound(total_ftes,3)
        //alert("Diurno:"+ftes_diurno+" -  Nocturno:"+ftes_nocturno);
        //alert(total_ftes);
        //total_ftes=Number(Math.round(total_ftes+'e3')+'e-3')

        if(isNaN(total_ftes)){
            total_ftes=0;
        }
        $('#ftes_total').val(total_ftes)
        /*console.log("ftes_diurno= "+ftes_diurno)
        console.log("ftes_nocturno= "+ftes_nocturno)
        console.log("total_ftes= "+total_ftes)*/
        //calcularPrecio();

        calcular_ftes(total_ftes,ftes_diurno,ftes_nocturno);
    }
	
	/*$('#admindispositivo-horas').keyup(function(event) {
        var ftes= calcular_ftes($(this).val());
        $('#ftes_total').val(ftes[0]);
        $('#ftes_dep').val(ftes[1]);
    });*/

    function calcular_ftes(ftes,ftes_diurnos,ftes_nocturno){
        console.log("ftes:"+ftes);
        console.log("ftes_diurnos:"+ftes_diurnos);
        console.log("ftes_nocturnos:"+ftes_nocturno);

        var count = <?= $num_dep ?>//$("#deps :selected").length;

        //var calculo=Number(Math.round(((ftes/count)*parseInt($('#cantidad_serv').val()))+'e3')+'e-3');
        /*var calculo=((ftes/count)*parseInt($('#cantidad_serv').val()));
            calculo=myRound(calculo,3)*/
        //var ftes_dep=Number(Math.round(calculo+'e3')+'e-3');

        //$('#ftes_dep').val(calculo);
        //$('#ftes_dep').val(ftes_dep);

        //var ftes_diurno_dep=Number(Math.round(((ftes_diurnos/count)*parseInt($('#cantidad_serv').val()))+'e3')+'e-3');
        var ftes_diurno_dep=((ftes_diurnos/count)*parseInt($('#cantidad_serv').val()));
            ftes_diurno_dep=myRound(ftes_diurno_dep,3);
        //var ftes_nocturno_dep=Number(Math.round(((ftes_nocturno/count)*parseInt($('#cantidad_serv').val()))+'e3')+'e-3');

        var ftes_nocturno_dep=((ftes_nocturno/count)*parseInt($('#cantidad_serv').val()));
            ftes_nocturno_dep=myRound(ftes_nocturno_dep,3);

        var calculo=(ftes_diurno_dep+ftes_nocturno_dep);
        calculo=myRound(calculo,3);

        $('#ftes_dep').val(calculo);
        $('#ftes_diurno_dep').val(ftes_diurno_dep);

        $('#ftes_nocturno_dep').val(ftes_nocturno_dep);

         console.log("ftes_dep:"+calculo);
        console.log("ftes_diurnos_dep:"+ftes_diurno_dep);
        console.log("ftes_nocturnos_dep:"+ftes_nocturno_dep);

    }


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

	function calcular(cantidad,precio_u){

        var total=parseInt(cantidad)*parseInt(precio_u);
        var count = <?= $num_dep ?> //$("#deps :selected").length;
        if (count>0) {

            var total_dep=(total/count);
        }else{
            var total_dep=0;
        }

        return [total,Number(Math.round(total_dep+'e2')+'e-2')];

    }

    <?php if($model->horas=='00:00' && $model->hora_inicio=='00:00:00'): ?>

    $("#admindispositivo-horas").val('08:00')
    $("#admindispositivo-hora_inicio").val('06:00')
    <?php endif;?>

    function validar(){
        $('<input>').attr({
            type: 'hidden',
            id: 'hora_fin2',
            name: 'hora_fin2',
            value: $('#hora_fin').html()
        }).appendTo('#form');
        
        $('<input>').attr({
            type: 'hidden',
            id: 'dias_prestados2',
            name: 'dias_prestados2',
            value:dias_prestados
        }).appendTo('#form');
        
        $('<input>').attr({
            type: 'hidden',
            id: 'ftes_diurno ',
            name: 'ftes_diurno',
            value: ftes_diurno
        }).appendTo('#form');
        $('<input>').attr({
            type: 'hidden',
            id: 'ftes_nocturno ',
            name: 'ftes_nocturno',
            value: ftes_nocturno
        }).appendTo('#form');


        //alert("Diurno:"+ftes_diurno );
        //alert("Nocturno:"+ftes_nocturno );
        $('#form').yiiActiveForm('submitForm');
    }

    function ftes_horas(valor){


        if (valor=='S') {
            $('.ftes-horas').show('slow/400/fast', function() {
                
            });
        }else{
            $('.ftes-horas').hide('slow/400/fast', function() {
                
            });
        }
    }

    function myRound(num, dec) {
        var exp = Math.pow(10, dec || 2); // 2 decimales por defecto
        return parseInt(num * exp, 10) / exp;
    }

</script>