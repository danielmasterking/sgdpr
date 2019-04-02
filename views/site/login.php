<?php
use yii\helpers\Html;
//use yii\bootstrap\ActiveForm;
use \kartik\form\ActiveForm;


/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */
$this->theme->baseUrl="https://cvsc.co/sgs/themes/ADMIN_LTE";
$this->title='Login'
?>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
 <center>
    <img src="<?php echo $this->theme->baseUrl; ?>/dist/img/431px-Grupo_Exito_logo.png" alt="..." class="img-responsive" style="height:100px;width: 170px;">
  </center>
  <br>

<?php 

    $flashMessages = Yii::$app->session->getAllFlashes();
    if ($flashMessages) {
        foreach($flashMessages as $key => $message) {
            echo "<div class='alert alert-" . $key . " alert-dismissible' role='alert'>
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                    $message
                </div>";   
        }
    }
?>
<!-- <div class="row" >
  <div class="col-md-12 ">
   <div class="alert alert-info" role="alert">
    <i class="fas fa-info fa-2x"></i> Por seguridad la nueva clave de acceso es <b>Exito321*</b> una vez ingresado el sistema solicitara un cambio de contraseña
   </div>
  </div>
</div> -->

<div class="login-box-body" style="background-color:rgba(255,255, 255, 0.6) !important;border: 2px solid !important;">
    <?php 
      if (count($errors)>0):
        
    ?> 
      <div class="alert alert-danger" role="alert">
        <?php foreach($errors as $err):
          echo $err[0];

        endforeach; ?>
      </div>

    <?php 
        
      endif; 
    ?>


    <?php $form = ActiveForm::begin([
           'id' => 'login-form',
           'options' => ['class' => 'form-horizontal','role'=>'form'],
       ]); ?>
      <div class="form-group has-feedback">
      	<input id="loginform-username" type="text" class="form-control" placeholder="Usuario" name="LoginForm[username]" required="" value='<?= $usuario?>'>
        <?php 
        	//echo $form->field($model, 'username')->textInput(['class'=>'form-control','placeholder'=>'Usuario'])->label(false);
        ?>
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
      	<input id="loginform-password" type="password" class="form-control" placeholder="Contraseña" name="LoginForm[password]" required="">
        <?php  
        	//echo $form->field($model, 'password')->passwordInput(['class'=>'form-control','placeholder'=>'Usuario'])->label(false);
        ?>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>

      <?= $form->field($model, 'rememberMe')->checkbox([]) ?>
      
      <div class="row">
        
        <!-- /.col -->
        <div class="col-xs-12">

          <?= Html::submitButton('<i class="glyphicon glyphicon-log-in"></i> Ingresar', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
        </div>

         
        <!-- /.col -->
      </div>
      <br>
      <div class="row">
        <div class="col-xs-12">

          <button class="btn btn-success btn-block btn-flat" type="button" data-toggle="modal" data-target="#myModal">
            <i class="fas fa-key"></i> Recuperar Contraseña
          </button>
        </div>
      </div>
    <?php ActiveForm::end(); ?>


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3><i class="fas fa-key"></i> Recuperar contraseña</h3>
      </div>
      <div class="modal-body">
        <input type="text" name="email" id="email" class="form-control" placeholder="Introduce el email o tu nombre de usuario">
        <div id="info"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary"
        onclick="validar();">Enviar</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Nueva Contraseña</h4>
      </div>
      <div class="modal-body">

        <div class="alert alert-info" role="alert">
            <i class="fas fa-info-circle"></i>  Por seguridad te invitamos a cambiar tu contraseña 
            <b>minimo 8 caracteres combinacion de mayusculas y minusculas,numeros y un caracter especial.</b>
        </div>
        <form method="post" action="<?php echo Yii::$app->request->baseUrl . '/site/reestablecer-clave'; ?>" id="form-clave">
          <input type="hidden" name="usuario" id="user-app">
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
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">cerrar</button>
        <button type="submit" class="btn btn-primary">Cambiar</button>
        </form>
      </div>
    </div>
  </div>
</div>
    

  </div>
  <script type="text/javascript">
    $(function(){
      $('#loginform-rememberme').attr({checked: false});
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


    $('#form-clave').on('submit',function(event) {

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


    function validar(){
      //alert($('#email').val())
      $.ajax({
            url:"<?php echo Yii::$app->request->baseUrl . '/site/verificar-usuario'; ?>",
            type:'POST',
            dataType:"json",
            cache:false,
            data: {
               user:$('#email').val()
            },
            beforeSend:  function() {
                $('#info').html('verificando... <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>');
            },
            success: function(data_response){
              //alert(data_response.respuesta)
              if (data_response.respuesta==1) {
                $("#info").html('');
                  swal( 
                    "Usuario Existente",'','success'
                  )
                  .then((value) => {
                    $('#user-app').val($('#email').val());
                    $('#myModal1').modal('show')
                  });
              }else{
                $("#info").html('');
                 swal({
                  title: "Este usuario no existe",
                  text: "",
                  icon: "warning",
                });
              }
            }
        });
      
    }
  </script>