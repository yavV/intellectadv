<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Profile */
/* @var $form ActiveForm */
?>
<div class="Profile">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'user_id') ?>
        <?= $form->field($model, 'filefoto') ?>
    
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- Profile -->
