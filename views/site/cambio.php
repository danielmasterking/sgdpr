<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


$permisos = array();


if( isset(Yii::$app->session['permisos']) ){

  $permisos = Yii::$app->session['permisos'];

}

/* @var $this yii\web\View */
/* @var $model app\models\Preaviso */
/* @var $form yii\widgets\ActiveForm */

?>
<?php $form = ActiveForm::begin(); ?>

    <?php if($model != null): ?>

      <p class="alert alert-success"> Cambio de clave realizado.</p>

    <?php else: ?>
        <div class="alert alert-info" role="alert">
            <i class="fas fa-info-circle"></i>  Por seguridad te invitamos a cambiar tu contraseña 
            <b>minimo 8 caracteres combinacion de mayusculas y minusculas,numeros y un caracter especial.</b>
        </div>

    <?php endif;?> 
    <div id="pass">
        <label>Nueva Clave</label>
        <input type="password" name="clave" class=" form-control"  id="usuario-password" required="" />
        <div id="mensaje"></div>
    </div>

    <div id="pass-confirm">
        <label>Repetir Clave</label>
        <input type="password" name="confirmacion_clave" class=" form-control" id="pass-conf" required="" />
        <div id="mensaje-confirm"></div>
    </div>

    <p>&nbsp;</p>
    <input type="submit" class="btn btn-primary" name="guardar" value="Cambiar clave" id="pass-conf" required=""/>
    



<?php ActiveForm::end(); ?>
<script type="text/javascript">
  $(function(){
    var mayus= new RegExp("^(?=.*[A-Z])");
    var special= new RegExp("^(?=.*[!@#$&*])");
    var number= new RegExp("^(?=.*[0-9])");
    var lower= new RegExp("^(?=.*[a-z])");
    var len= new RegExp("^(?=.{8,})");
    var seguridad=true;
    var confirmar=true;
    $('#usuario-password').on('keyup',function(event) {
      
      var pass=$(this).val();

      if (mayus.test(pass) && special.test(pass) && number.test(pass) && lower.test(pass) && len.test(pass)) {

        $("#pass").addClass('has-success');
        $("#pass").removeClass('has-error')
        $('#mensaje').html('<p style="color:green">La contraseña es segura </p>');
        seguridad=true;
      }else{

        $("#pass").addClass('has-error');
        $("#pass").removeClass('has-success')
        $('#mensaje').html('<p style="color:red">La contraseña es insegura debe contener minimo 8 caracteres combinacion de mayusculas y minusculas,numeros y un caracter especial. </p>')

        seguridad=false;
      }
      
    });


    $('#pass-conf').on('keyup',function(event) {
      
      var pass=$('#usuario-password').val();
      var conf=$(this).val();

      if (conf===pass) {
        $("#pass-confirm").addClass('has-success');
         $("#pass-confirm").removeClass('has-error')
        $('#mensaje-confirm').html('<p style="color:green">Las contraseñas coinciden. </p>')
        confirmar=true;
      }else{
        $("#pass-confirm").addClass('has-error');
        $("#pass-confirm").removeClass('has-success')
        $('#mensaje-confirm').html('<p style="color:red">Las contraseñas no coinciden. </p>')
        confirmar=false;
      }
      
      
    });


    $('#w0').on('submit',function(event) {

      if (!seguridad) {
        alert('La contraseña no es segura');
        event.preventDefault();
      }

      if(!confirmar){
        alert('Las contraseñas no coinciden');
        event.preventDefault();
      }
      /* Act on the event */
    });


  });
</script>