<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model admin\models\LogActionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="log-action-search">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
		'options'=>['class' => 'form-inline'],
	]); ?>

<?= $form->field($model, 'user_name', [
	'template' => '<div class="input-group input-group-sm"><span class="input-group-addon">{label}</span>{input}</div>',
])->label(null, [ 'style' => 'line-height: normal']) ?>

<?= $form->field($model, 'title', [
	'template' => '<div class="input-group input-group-sm"><span class="input-group-addon">{label}</span>{input}</div>',
])->label(null, [ 'style' => 'line-height: normal']) ?>

<?php echo $form->field($model, 'type', [
	'template' => '<div class="input-group input-group-sm"><span class="input-group-addon">{label}</span>{input}</div>',
])->dropDownList(\service\Log::getListType(), ['prompt' => \wskm::t('Please select')])->label(\Wskm::t('Type', 'admin'), [ 'style' => 'line-height: normal']) ?>

<?php echo $form->field($model, 'level', [
	'template' => '<div class="input-group input-group-sm"><span class="input-group-addon">{label}</span>{input}</div>',
])->dropDownList(\service\Log::getListLevel(), ['prompt' => \wskm::t('Please select')])->label(\Wskm::t('Level', 'admin'), [ 'style' => 'line-height: normal']) ?>

	<div class="form-group">
		<?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-info btn-sm']) ?>
		<?= Html::button(Yii::t('app', 'Delete'), [
			'class' => 'btn btn-danger btn-sm', 
			'id' => 'btn-delete',
			'data-toggle' => "modal",
			'data-target' => "#myModal",
		]) ?>
	</div>

	<?php ActiveForm::end(); ?>
		
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
		<form action="<?= \yii\helpers\Url::to(['log-action/delete-expired']) ?>" method="post" >
			<?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->getCsrfToken()) ?>
			<div class="modal-body">
				 <div class="form-group">
				  <label for=""><?= \Wskm::t('Delete the number of days before the log?', 'log') ?></label>
				  <input type="number" name='day' class="form-control" value='7' />
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-danger"><?= \Wskm::t('Delete') ?></button>
				<button type="button" class="btn btn-default" data-dismiss="modal"><?= \Wskm::t('Close') ?></button>
			</div>
		</form>
    </div>
  </div>
</div>