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
 <center>
    <img src="<?php echo $this->theme->baseUrl; ?>/dist/img/431px-Grupo_Exito_logo.png" alt="..." class="img-responsive" style="height:100px;width: 170px;">
  </center>
  <br>

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
        <div class="col-xs-4">

          <?= Html::submitButton('<i class="glyphicon glyphicon-log-in"></i> Ingresar', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
        </div>
        <!-- /.col -->
      </div>
    <?php ActiveForm::end(); ?>

    

  </div>
  <script type="text/javascript">
    $(function(){
      $('#loginform-rememberme').attr({checked: false});
    });
  </script>