<?php  
  use yii\helpers\Html;
  use app\assets\AppAsset;
  $this->theme->baseUrl="https://cvsc.co/sgs/themes/ADMIN_LTE";
  AppAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <?= Html::csrfMetaTags() ?>
  <title><?= Html::encode($this->title) ?></title>
  <?php $this->head() ?>
  <!-- Tell the browser to be responsive to screen width -->

  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <!-- <link rel="stylesheet" href="../../bower_components/bootstrap/dist/css/bootstrap.min.css"> -->
  <!-- Font Awesome -->
  <!-- <link rel="stylesheet" href="../../bower_components/font-awesome/css/font-awesome.min.css"> -->
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo $this->theme->baseUrl; ?>/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo $this->theme->baseUrl; ?>/dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo $this->theme->baseUrl; ?>/plugins/iCheck/square/blue.css">

  <link rel="icon" type="image/png" href="<?php echo $this->theme->baseUrl; ?>/dist/img/431px-Grupo_Exito_logo.png" />

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <style type="text/css">
    body{

      background-image: url(<?php echo $this->theme->baseUrl."/dist/img/EXITO-ENVIGADO.jpg"; ?>) !important;
      background-size: 100% 100% !important;
      background-repeat: no-repeat !important;
      /*background-attachment: fixed !important;*/
    }

    @media (max-width: 575.98px) { 
        body{
          background-size: 415px 800px !important;
        }

    }
  </style>
</head>
<body class="hold-transition login-page" >
<?php $this->beginBody() ?>


<div class="col-md-4 col-md-offset-4" >
  <div class="login-logo">
    <a href="#" >Sistema de gestíon <b>DPR</b></a>
  </div>
  <!-- /.login-logo -->
  <?php echo $content ?>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<!-- <script src="../../bower_components/jquery/dist/jquery.min.js"></script> -->
<!-- Bootstrap 3.3.7 -->
<!-- <script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script> -->
<!-- iCheck -->
<script src="<?php echo $this->theme->baseUrl; ?>/plugins/iCheck/icheck.min.js"></script>
<!-- Smartsupp Live Chat script -->
<script type="text/javascript">
var _smartsupp = _smartsupp || {};
_smartsupp.key = '53f8d6e7e81b45ad6bc911e68182a236fd4ea00f';
window.smartsupp||(function(d) {
  var s,c,o=smartsupp=function(){ o._.push(arguments)};o._=[];
  s=d.getElementsByTagName('script')[0];c=d.createElement('script');
  c.type='text/javascript';c.charset='utf-8';c.async=true;
  c.src='https://www.smartsuppchat.com/loader.js?';s.parentNode.insertBefore(c,s);
})(document);
</script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage(); ?>
