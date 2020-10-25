<?php

use backend\widgets\ActiveForm;
use backend\widgets\meta\MetaForm;
use common\behaviors\TagBehavior;
use common\widgets\dynamicInput\DynamicInputWidget;
use common\widgets\tag\TagsInput;
use yii\helpers\Html;
use common\helpers\Tree;
use common\modules\attachment\widgets\SingleWidget;

/* @var $this yii\web\View */
/* @var $model \common\modules\document\models\Document */
/* @var $moduleModel \common\modules\document\models\document\Article */
/* @var $form backend\widgets\ActiveForm */
?>
    <?php $form = ActiveForm::begin(); ?>
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">通用</a></li>
                <li><a href="#tab_2" data-toggle="tab" aria-expanded="true">扩展</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">

                    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'description')->textarea()?>

                    <?php foreach ($moduleModel->formAttributes() as $attribute): ?>
                        <?= $form->field($moduleModel, $attribute)->widget(DynamicInputWidget::className(), ['type' => $moduleModel->getAttributeType($attribute), 'data' => $moduleModel->getAttributeItems($attribute), 'options' => $moduleModel->getAttributeOptions($attribute)]) ?>
                    <?php endforeach; ?>

                    <?= $form->boxField($model, 'meta', ["collapsed" => true])->widget(MetaForm::className())->header("SEO"); ?>

                </div>

                <div class="tab-pane" id="tab_2">
                    <?= $form->field($model, 'published_at')->widget(
                        \kartik\datetime\DateTimePicker::className(),
                        [
                            'type' => 1,
                            'options' => [
                                'value' => !empty($model->published_at) ? date('Y-m-d H:i:s', $model->published_at) : ''
                            ],
                            'pluginOptions' => ['autoclose' => true]
                        ]
                    ) ?>
                    <?= $form->field($model, 'cover')->widget(SingleWidget::className()) ?>

                    <?= $form->field($model, 'commentEnabled')->checkbox(['label' => '开启评论']) ?>

                    <?= $form->field($model, 'is_top')->checkbox() ?>

                    <?= $form->field($model, 'is_hot')->checkbox() ?>

                    <?= $form->field($model, 'is_best')->checkbox() ?>

                    <?= $form->field($model, 'status')->checkbox() ?>

                    <?= $form->field($model, 'view')->textInput() ?>

                    <?= $form->field($model, TagBehavior::$formName)->label(TagBehavior::$formLable)->widget(TagsInput::className()) ?>

                    <?= $form->field($model, 'source')->textInput() ?>

                </div>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? '发布' : '更新', ['class' => 'btn bg-maroon btn-flat btn-block']) ?>
        </div>

    <?php ActiveForm::end(); ?>
