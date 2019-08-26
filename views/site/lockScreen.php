<?php 
  use yii\helpers\Html;
  //use yii\bootstrap\ActiveForm;
  use \kartik\form\ActiveForm;
  use yii\helpers\Url;
?>
<!-- User name -->
  <div class="lockscreen-name"><?php echo $model->username ?></div>

  <!-- START LOCK SCREEN ITEM -->
  <div class="lockscreen-item">
    <!-- lockscreen image -->
    <div class="lockscreen-image">
      <img src="<?php echo $this->theme->baseUrl; ?>/dist/img/user1-128x128.jpg" alt="User Image">
    </div>
    <!-- /.lockscreen-image -->

    <!-- lockscreen credentials (contains the form) -->
    <form class="lockscreen-credentials" id="login-form" action="<?= Yii::$app->request->baseUrl.'/site/login'?>" method="post">
      <input type="hidden" name="LoginForm[username]" value="<?= $model->username?>">
      <input type="hidden" name="url_actual" value="<?= $url?>">
      <div class="input-group">
        <input name="LoginForm[password]" required="" type="password" class="form-control" placeholder="password">

        <div class="input-group-btn">
          <button type="submit" class="btn"><i class="fa fa-arrow-right text-muted"></i></button>
        </div>
      </div>
    </form>
    <!-- /.lockscreen credentials -->

  </div>
  <!-- /.lockscreen-item -->