<?php

/**
 * Created by PhpStorm.
 * User: yav
 * Date: 05.03.17
 * Time: 18:41
 */

namespace frontend\widgets\profile;

use yii\helpers\Html;
use yii\base\Widget;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\widgets\Pjax;
use yii\helpers\Json;
use yii\web\View;

/**
 * Widget for display dynamic fields, adding and removing their use Pjax.
 *
 * Home page: https://github.com/bupy7/yii2-dynamic-fields
 *
 * @author Vasilij Belosludcev http://mihaly4.ru
 * @since 1.0.0
 */
class ProfileVals extends Widget
{
    /**
     * @var ActiveForm the form that this field is associated with.
     */
    public $form;
    /**
     * @var array Options of the 'field' method.
     * @see \yii\widgets\ActiveForm::field()
     */
    public $fieldOptions = [];
    /**
     * @var string Name of input method from \yii\widgets\ActiveField. By default 'textInput'.
     * @see \yii\widgets\ActiveField
     */
    public $inputMethod = 'textInput';
    /**
     * @var array Arguments of current $inputMethod as array. First argument is [0], second is [1] and etc.
     * Example: By default $inputMehod is 'textInput'. Then argemnts can be: [['maxlength' => true]].
     */
    public $inputMethodArgs = [];
    /**
     * @var array Models the data model that this widget is associated with.
     */
    public $models;
    /**
     * @var string Primary key of model. By default 'id'.
     */
    public $primaryKey = 'id';
    /**
     * @var string the model attribute that this widget is associated with.
     */
    public $attributes;
    public $refattribute;
    /**
     * @var mixed URL of action for create new model.
     */
    public $urlAdd;
    /**
     * @var mixed URL of action for delete model.
     */
    public $urlRemove;
    /**
     * @var array Options of action button.
     */
    public $buttonOptions = ['class' => 'btn btn-default'];
    /**
     * @var string Template of input. List allow tokens: {input} and {button}. In token {button} will be inserted
     * action button. In token {input} will be inserted input field.
     */
    public $inputStTemplate = '<div><div class="row" style="display: inline-flex;">';
    public $inputTemplate = '<div class="input-group">{input}<span class="input-group-btn">';
    public $inputEndTemplate = '</span></div>';
    public $buttonTemplate = '{button}</span></div></div></div>';
    /**
     * @var array Options of Pjax.
     * @see \yii\widgets\Pjax
     */
    public $pjaxOptions = [
        'enablePushState' => false,
        'clientOptions' => [
            'type' => 'post',
        ],
    ];
    /**
     * @var boolean Whether set 'true' then will be displays label for each field and not only for first field.
     */
    public $labelEach = false;
    /**
     * @var boolean Whether set 'true' then will be displays hint for each field and not only for first field.
     */
    public $hintEach = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (!$this->hasModel()) {
            throw new InvalidConfigException("Either 'models' and 'attribute' properties must be specified.");
        }
        if (empty($this->urlAdd) || empty($this->urlRemove)) {
            throw new InvalidConfigException("Either 'urlAdd' and 'urlRemove' properties must be specified.");
        }
        Pjax::begin($this->pjaxOptions);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $form = clone $this->form;
        if (empty($form->fieldConfig['template'])) {
            $form->fieldConfig['template'] = "{label}\n{input}\n{hint}\n{error}";
        }
        if (empty($form->fieldConfig['labelOptions'])) {
            $form->fieldConfig['labelOptions'] = ['class' => 'control-label'];
        }
        $keys = array_keys($this->models);
        $form->fieldConfig['template'] = str_replace('{input}', $this->inputTemplate, $form->fieldConfig['template']);
        $button = Html::a(
            Html::tag('span', '', [
                'class' => 'glyphicon glyphicon-plus',
            ]), $this->urlAdd, $this->buttonOptions
        );

        // echo $form->field($this->models[$keys[0]], "[{$keys[0]}]{$this->refattribute}", $this->fieldOptions);

        //echo $this->field($form, $this->models[$keys[0]], "[{$keys[0]}]{$this->refattribute}");

        echo $this->fields($form, $this->models[$keys[0]], $keys[0], $this->attributes, $button);

        if (!$this->labelEach) {
            $form->fieldConfig['template'] = str_replace(
                '{label}', Html::tag('label', '', $form->fieldConfig['labelOptions']), $form->fieldConfig['template']
            );
        }
        if (!$this->hintEach) {
            $form->fieldConfig['template'] = preg_replace(
                '/\{hint\}|\{hint\}\\n|\{hint\}\s/i', '', $form->fieldConfig['template']
            );
        }
        for ($i = 1; $i != count($keys); $i++) {
            $button = Html::a(
                Html::tag('span', '', [
                    'class' => 'glyphicon glyphicon-minus',
                ]),
                array_merge((array)$this->urlRemove, [
                    'id' => $this->models[$i]->{$this->primaryKey},
                ]),
                $this->buttonOptions
            );
            //echo $this->fields($form, $this->models[$keys[$i]], $keys[$i],$this->attributes, $button);
            echo $this->fields($form, $this->models[$keys[$i]], $keys[$i],$this->attributes, '');
        }
        if ($this->form->enableClientScript) {
            $dfClientOptions = [];
            for ($i = 0; $i != count($form->attributes); $i++) {
                foreach ($this->attributes as $attribute)
                if (strpos($form->attributes[$i]['name'], $attribute) !== false) {
                    $dfClientOptions[] = $form->attributes[$i];
                }
            }
            $dfClientOptions = Json::encode($dfClientOptions);
            $formClientOptions = Json::encode($this->form->attributes);
            $js = <<<JS
(function($) {
    var dfClientOptions = {$dfClientOptions},
        \$form = $('#{$this->form->id}');
    \$form.yiiActiveForm('data').attributes = {$formClientOptions};
    for (var i = 0; i != dfClientOptions.length; i++) {
        \$form.yiiActiveForm('add', dfClientOptions[i]);
    }
}(jQuery));
JS;
            $this->view->registerJs($js, View::POS_LOAD);
        }
        Pjax::end();
    }

    /**
     * Render field of \yii\widgets\ActiveForm.
     * @param \yii\widgets\ActiveForm $form
     * @param \yii\base\Model $model The data model.
     * @param string $attribute Attribute name.
     * @param string $button Action button of field.
     * @return string
     */
    protected function field($form, $model, $attributes, $button)
    {

            $field = $form->field($model, $attributes, $this->fieldOptions);
            $field = call_user_func_array([$field, $this->inputMethod], $this->inputMethodArgs);


        return str_replace('{button}', $button, $field);
    }
    protected function fields($form, $model,$key, $attributes, $button)
    {

        $fields = $this->inputStTemplate;
            $field0 = $form->field($model, "[{$key}]{$attributes[0]}", $this->fieldOptions)->hiddenInput()->label(false);
            //$fields .= call_user_func_array([$field0, $this->inputMethod], $this->inputMethodArgs);
        $fields .= $field0.'</div>';
        //$fields .= $this->inputEndTemplate;

        $field0 = $form->field($model, "[{$key}]{$attributes[1]}", $this->fieldOptions);
        $fields .= call_user_func_array([$field0, $this->inputMethod], $this->inputMethodArgs);
        $fields .= $this->inputEndTemplate;

            $field1 = $form->field($model, "[{$key}]{$attributes[2]}", $this->fieldOptions);

            $fields .= call_user_func_array([$field1, $this->inputMethod], $this->inputMethodArgs);
        $fields .= $this->buttonTemplate;
        //$this->inputTemplate



        return str_replace('{button}', $button, $fields);
    }
    /**
     * @return boolean whether this widget is associated with a data model.
     */
    protected function hasModel()
    {
        if (is_array($this->models) && $this->attributes !== null && !empty($this->models)) {
            foreach ($this->models as $model) {
                if (!($model instanceof Model)) {
                    return false;
                }
            }
        } else {
            return false;
        }
        return true;
    }
}