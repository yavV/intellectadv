<?php

use yii\helpers\Html;
use \yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
//use Profilevals;
use frontend\widgets\profile\ProfileVals;

/* @var $this yii\web\View */
/* @var $model common\models\Profile */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="profile-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'user_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'filefoto')->hiddenInput()->label(false) ?>
    <?php
    if (isset($model->filefoto) && $model->filefoto !==""){
       echo Html::img(Yii::$app->homeUrl . 'upload/'.$model->filefoto,['height' => '200px']);
    }

    ?>

    <?= $form->field($model, 'image')->widget(FileInput::classname(), [
        'options' => [
            'accept' => 'image/*',
            'multiple' => false,
            'enctype' => 'multipart/form-data',
        ],
    ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php

    ActiveForm::end();

    $profForm = ActiveForm::begin(['action' => ['profilefields/update','id' => $model->id]]);

    $_fields = $model->profileFields;
    if (count ($_fields) ===0){
        $_fields = [new \common\models\ProfileFields()];
    }
    $_url = ['profilefields/create','id' => $model->id];
    $_url1=Url::to('profilefields/delete');
    echo ProfileVals::widget([
    'urlAdd' => $_url,
    'urlRemove' => [Url::to('profilefields/delete')],
    'inputMethod' => 'textInput',
    'inputMethodArgs' => [['maxlength' => true]],
    'form' => $profForm,
    'models' => $_fields,
    'attributes' => ['id','field_name','field_value'],
        'refattribute' => 'profile_id',
    ]);

    echo Html::submitButton('Save', ['class' => 'btn btn-success']);

    ActiveForm::end();
    ?>


</div>
