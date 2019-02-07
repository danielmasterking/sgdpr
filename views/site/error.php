<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">

    <center>
        <img src="<?= Yii::$app->request->baseUrl.'/images/error-500.png' ?>" style="height: 400px;width: 500px;">    
    </center>
    
    <br>
    <div class="alert alert-danger" role="alert">
       <h3> 
            <i class=" glyphicon glyphicon-warning-sign"></i>  Ha ocurrido un error por favor ponerse en contacto con  <a href="mailto:soporte@sistemagestiondpr.com.co">soporte@sistemagestiondpr.com.co</a>
       </h3>
    </div>

</div>
