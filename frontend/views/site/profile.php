<?php
/**
 * Created by PhpStorm.
 * User: yav
 * Date: 05.03.17
 * Time: 12:16
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Profile';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-sm-8">
   <?php
      // A block file picker button with custom icon and label
      echo FileInput::widget([
          'model' => $model,
          'attribute' => 'image',
          'options' => ['multiple' => true],
          'pluginOptions' => [
              'showCaption' => false,
              'showRemove' => false,
              'showUpload' => false,
              'browseClass' => 'btn btn-primary btn-block',
              'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
              'browseLabel' =>  'Upload Receipt'
          ],
          'options' => ['accept' => 'image/*']
      ]);
   ?>
</div>