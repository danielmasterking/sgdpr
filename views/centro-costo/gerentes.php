<?php  
use yii\helpers\Html;
use kartik\widgets\Select2;
use yii\widgets\ActiveForm;
use  yii\helpers\Url;
$this->title = 'Gerentes';
?>
<a href="<?php echo Url::toRoute('centro-costo/index')?>" class="btn btn-primary " >
    <i class="fa fa-arrow-left"></i> 
</a>
<h1 class="text-center"><?= $this->title ?></h1>

<?php 
$form = ActiveForm::begin();

echo $form->field($gerentesModel, 'usuario')->widget(Select2::classname(), [
    'data' => $gerentesModel->ListUsuarios(),
    'options' => ['placeholder' => 'Selecciona usuarios ...', 'multiple' => true,'id'=>'users'],
    'pluginOptions' => [
        'allowClear' => true
    ]
]);
echo Html::submitButton('Guardar', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']);
ActiveForm::end();
?>

